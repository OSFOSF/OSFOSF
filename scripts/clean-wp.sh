#!/bin/bash
# Usage:
#  bash clean-wp.sh POD_NAME
#
PREFIX=${1:-blog}

podman pod stop $PREFIX 
podman container rm -f $PREFIX-db $PREFIX-fpm $PREFIX-nginx 
podman pod rm $PREFIX
podman secret rm $PREFIX-mysql-root  
podman volume rm $PREFIX-mariadb $PREFIX-nginx-conf $PREFIX-var-www-html 
