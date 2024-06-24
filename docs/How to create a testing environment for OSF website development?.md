
The OSF website is built using WordPress. This guide shows how to create a WP instance for local website development on Linux (in theory, it should work for Windows and Mac as well but it hasn't been tested) using podman. If you have another way to create a WP instance locally, you can skip the following content and simply ask Coiby for access to OSF data.

Suppose you will visit your WP locally via http://localhost:8779,

1. Follow [Podman Installation | Podman](https://podman.io/docs/installation#installing-on-linux) to install podman on Linux.
2. Contact Coiby for access to OSF database, theme and plugins
2. Download [wp-create-legacy.sh](scripts/wp-create-legacy.sh) which will create a pod, 3 containers, 3 volumes and a secret and use it to create a group of containers. Execute the script with 4 arguments i.e. the name of pod, the port, the path to database and the path of the folder of wp-content
```sh
$ bash wp-create-legacy.sh OSF 8779 /tmp//_osfdb.sql ~/osf_backups/wp-content

$ podman pod ls
POD ID        NAME           STATUS      CREATED        INFRA ID      # OF CONTAINERS
b3ea44bed6c5  OSF            Running     9 seconds ago  46d9d149f13d  4

```
3. Visit http://localhost:8779
