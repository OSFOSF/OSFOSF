#!/bin/bash
# Usage:
#  bash wp-create-legacy.sh POD_NAME PORT WP_DB.sql WP_CONTENT_PATH
#
# This script is adapted from https://github.com/cyberworm-uk/wordpress-podman/blob/main/wp-create-legacy.sh
function conflict_fail() {
  printf "FAIL: Conflicting %s (%s)\n" "${1}" "${2}"
  exit 1
}

function wait_for_something() {
    local _what
    _what=2
    _wait_time=0
    _MAX_WAIT_TIME=60
    while ! $1 ; do
        sleep 1
        _loop=$((_loop + 1))
        if [ $_loop -gt 60 ]; then
            echo "$_what doesn't exist after waiting $_MAX_WAIT_TIME seconds, something wrong!"
            exit 1
        fi
    done
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
printf "Creating storage volumes\n"
podman volume create "${PREFIX}-var-www-html"
podman volume create "${PREFIX}-mariadb"
podman volume create "${PREFIX}-nginx-conf"

# create resources (optionally publish on port 80)...
printf "\nCreating pod ${PREFIX} which will listen on port $PORT\n"
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


printf "\nStarting mariadb, fpm and nginx instances\n"
# spawn mariadb instance...
podman run --pod ${PREFIX} -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-db" \
  --secret "${PREFIX}-mysql-root" \
  --env MARIADB_ROOT_PASSWORD_FILE="/run/secrets/${PREFIX}-mysql-root" \
  --env MARIADB_DATABASE=blog \
  --volume "${PREFIX}-mariadb:/var/lib/mysql" \
  docker.io/library/mariadb:latest
# spawn fpm instance to execute requests...
podman run --pod ${PREFIX} -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-fpm" \
  --secret "${PREFIX}-mysql-root" \
  --env WORDPRESS_DB_HOST=127.0.0.1 \
  --env WORDPRESS_DB_USER=root \
  --env WORDPRESS_DB_PASSWORD_FILE="/run/secrets/${PREFIX}-mysql-root" \
  --env WORDPRESS_DB_NAME=blog \
  --volume "${PREFIX}-var-www-html:/var/www/html" \
  docker.io/library/wordpress:fpm-alpine
# spawn nginx instance to serve requests...
podman run --pod ${PREFIX} -d --label io.containers.autoupdate=registry \
  --name "${PREFIX}-nginx" \
  --volume "${PREFIX}-nginx-conf:/etc/nginx/conf.d" \
  --volume "${PREFIX}-var-www-html:/var/www/html" \
  docker.io/library/nginx:alpine


# wait for wp-config.php to be ready
function wait_for_wp_config() {
    podman exec -it "${PREFIX}-fpm" sh -c "test -f /var/www/html/wp-config.php"
}
wait_for_something wait_for_wp_config "/var/www/html/wp-config.php"

podman exec -it $PREFIX-nginx sed -i "/WORDPRESS_TABLE_PREFIX/a define('WP_HOME', 'http://localhost:$PORT');\ndefine('WP_SITEURL', 'http://localhost:$PORT');" /var/www/html/wp-config.php
podman exec -it $PREFIX-nginx sed -i 's/^\$table_prefix.*$/\$table_prefix="wpnf_";/' /var/www/html/wp-config.php

printf "\nImporting OSF's database\n"
CONTAINER_DB_PATH=/var/lib/mysql/osfdb.sql
if grep -E -qs ".zip$" <<< "$3"; then
    tmp_db=$(sed -E -n "s/(.*).zip$/\1/p" <<< "$3")
    unzip "$3" -d /tmp/
else
    cp "$3" /tmp/
    tmp_db=$3
fi

tmp_db=/tmp/"$(basename "$tmp_db")"

if ! podman cp "$tmp_db" ${PREFIX}-db:$CONTAINER_DB_PATH; then
    echo "Failed to copy database $tmp_db to container"
    rm -f $tmp_db
    exit 1
fi
rm -f $tmp_db


function wait_for_db() {
    podman exec -it "${PREFIX}-db" sh -c "mariadb -uroot -p${DB_PASSWORD} -e exit &> /dev/null"
    #podman exec -it "${PREFIX}-db" sh -c "test -e /run/mysqld/mysqld.sock "
}

# Wait for database server to be ready
# somehow "wait_for_something wait_for_db" doesn't work as expected and "sleep
# 5" is still needed
sleep 5
wait_for_something wait_for_db "Database connection"

if podman exec -it "${PREFIX}-db" sh -c "mariadb -uroot -p${DB_PASSWORD} < $CONTAINER_DB_PATH"; then
podman exec -it "${PREFIX}-db" sh -c "rm -f $CONTAINER_DB_PATH"
fi

if [[ -d "$WP_CONTENT" ]]; then
    printf "\nCopying OSF's wp-content which includes theme, plugins and some uploads to container\n"
    podman cp "$WP_CONTENT" "$PREFIX-nginx":/var/www/html/
    printf "\nFixing permission of copied files\n"
    wp_content_dir=$(podman volume inspect -f '{{ .Mountpoint }}' "${PREFIX}-var-www-html")/wp-content
    uid=$(stat -c '%u' "${wp_content_dir}/plugins/akismet")
    if [[ -z $uid ]]; then
        echo "Failed to get uid of pre-isntalled akismet"
        exit 1
    fi
    sudo chown -R $uid:$uid -R "${wp_content_dir}"
fi
