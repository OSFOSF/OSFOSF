#!/bin/bash
# Usage:
#  bash wp-create-legacy.sh POD_NAME PORT WP_DB.sql WP_CONTENT_PATH
#
# This script is adapted from https://github.com/cyberworm-uk/wordpress-podman/blob/main/wp-create-legacy.sh
function conflict_fail() {
  printf "FAIL: Conflicting %s (%s)\n" "${1}" "${2}"
  exit 1
}

# setup environment variables...
DB_PASSWORD="$(head -c 32 /dev/urandom | base64 | tr -cd "[a-zA-Z0-9]")"
PREFIX=${1:-blog}
PORT=${2:-8772}
WP_CONTENT=${4}

if [[ -z "$3" ]]; then
    echo "Please specify the path of the imported database"
    exit 1
elif [[ ! -f "$3" ]]; then
    echo "Database $3 doesn't exist"
    exit 1
fi

# check for conflicts...
podman pod exists "${PREFIX}" && conflict_fail pod "${PREFIX}"
podman volume exists "${PREFIX}-var-www-html" && conflict_fail volume "${PREFIX}-var-www-html"
podman volume exists "${PREFIX}-mariadb" && conflict_fail volume "${PREFIX}-mariadb"
podman volume exists "${PREFIX}-nginx-conf" && conflict_fail volume "${PREFIX}-nginx-conf"
podman container exists "${PREFIX}-db" && conflict_fail container "${PREFIX}-db"
podman container exists "${PREFIX}-fpm" && conflict_fail container "${PREFIX}-fpm"
podman container exists "${PREFIX}-nginx" && conflict_fail container "${PREFIX}-nginx"

# create storage volumes...
echo ""
echo "Creating storage volumes"
podman volume create "${PREFIX}-var-www-html"
podman volume create "${PREFIX}-mariadb"
podman volume create "${PREFIX}-nginx-conf"

# create resources (optionally publish on port 80)...
echo "Creating pod ${PREFIX} which will listen on port $PORT"
podman pod create --name "${PREFIX}" --publish $PORT:$PORT/tcp
# create a secret to store mysql password...
printf "%s" "${DB_PASSWORD}" | podman secret create "${PREFIX}-mysql-root" -
# generate basic nginx config...
echo "server {
  listen $PORT;
  listen [::]:$PORT;
  root /var/www/html;
  index index.php;
  server_name _;
  server_tokens off;
  location / {
    try_files \$uri \$uri/ /index.php?\$args;
  }
  location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    fastcgi_param SCRIPT_NAME \$fastcgi_script_name;
  }
}" > $(podman volume inspect -f '{{ .Mountpoint }}' "${PREFIX}-nginx-conf")/blog.conf


echo ""
echo "Starting mariadb, fpm and nginx instances"
# spawn mariadb instance...
podman run --pod ${PREFIX} --rm -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-db" \
  --secret "${PREFIX}-mysql-root" \
  --env MARIADB_ROOT_PASSWORD_FILE="/run/secrets/${PREFIX}-mysql-root" \
  --env MARIADB_DATABASE=blog \
  --volume "${PREFIX}-mariadb:/var/lib/mysql" \
  docker.io/library/mariadb:latest
# spawn fpm instance to execute requests...
podman run --pod ${PREFIX} --rm -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-fpm" \
  --secret "${PREFIX}-mysql-root" \
  --env WORDPRESS_DB_HOST=127.0.0.1 \
  --env WORDPRESS_DB_USER=root \
  --env WORDPRESS_DB_PASSWORD_FILE="/run/secrets/${PREFIX}-mysql-root" \
  --env WORDPRESS_DB_NAME=blog \
  --volume "${PREFIX}-var-www-html:/var/www/html" \
  docker.io/library/wordpress:fpm-alpine
# spawn nginx instance to serve requests...
podman run --pod ${PREFIX} --rm -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-nginx" \
  --volume "${PREFIX}-nginx-conf:/etc/nginx/conf.d" \
  --volume "${PREFIX}-var-www-html:/var/www/html" \
  docker.io/library/nginx:alpine


# wait for wp-config.php and mariadb to be ready
sleep 5

podman exec -it $PREFIX-nginx sed -i "/WORDPRESS_TABLE_PREFIX/a define('WP_HOME', 'http://localhost:$PORT');\ndefine('WP_SITEURL', 'http://localhost:$PORT');" /var/www/html/wp-config.php
podman exec -it $PREFIX-nginx sed -i 's/^\$table_prefix.*$/\$table_prefix="wpnf_";/' /var/www/html/wp-config.php

echo "Importing OSF's database"
CONTAINER_DB_PATH=/var/lib/mysql/osfdb.sql
if grep -E -qs ".zip$" <<< "$3"; then
    tmp_db=$(sed -E -n "s/(.*).zip$/\1/p" <<< "$3")
    unzip "$3" -d /tmp/
else
    cp "$3" /tmp/
    tmp_db=$3
fi

tmp_db=/tmp/"$(basename "$tmp_db")"

podman cp "$tmp_db" OSF-db:$CONTAINER_DB_PATH
rm -f $tmp_db

PASSWORD=`podman exec "${PREFIX}-db" cat "/run/secrets/${PREFIX}-mysql-root"`

podman exec -it "${PREFIX}-db" sh -c "mariadb -uroot -p${PASSWORD} < $CONTAINER_DB_PATH"
podman exec -it "${PREFIX}-db" sh -c "rm -f $CONTAINER_DB_PATH"

if [[ -d "$WP_CONTENT" ]]; then
    echo "Copy OSF's wp-content which includes theme, plugins to container"
    podman cp "$WP_CONTENT" "$PREFIX-nginx":/var/www/html/
    echo "Fixing permission of copied files"
    wp_content_dir=$(podman volume inspect OSF-var-www-html| sed -En 's/^\s+"Mountpoint": "(.*)",$/\1/p')/wp-content
    uid=$(stat -c '%u' "${wp_content_dir}/plugins/akismet")
    if [[ -z $uid ]]; then
        echo "Failed to get uid of pre-isntalled akismet"
        exit 1
    fi
    sudo chown -R $uid:$uid -R "${wp_content_dir}"
fi
