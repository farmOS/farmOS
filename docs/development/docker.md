# farmOS on Docker

> [Docker] is an open-source project that automates the deployment of applications
> inside software containers. - [Wikipedia]

A farmOS Docker image is available on [Docker Hub] at
[https://hub.docker.com/r/farmos/farmos](https://hub.docker.com/r/farmos/farmos).
It is built automatically from the Dockerfile included with the
[farmOS distribution], whenever new commits are pushed to the
[farmOS GitHub repository].

This can be used for local farmOS development purposes, or for hosting your own
farmOS instance on a web server with the help of Docker.

**This document primarily covers how to set up a local development environment
with Docker. It does not cover how to host a production farmOS instance. The
image should support this, but it is largely untested.**

## General info

### Environment variables

The farmOS Dockerfile exposes a few environment variables, which can be
overridden when the container is run.

* `FARMOS_VERSION` - This is the tagged/packaged release of farmOS that should be
downloaded from drupal.org. This will be used if `FARMOS_DEV` is `false` (see
below). This will always be the most recently packaged release of farmOS.
* `FARMOS_DEV_BRANCH` - This is the branch that should be used for development
purposes. This will be used if `FARMOS_DEV` is `true` (see below). The default
is the branch you currently have checked out.
* `FARMOS_DEV` - This is a boolean variable to specify whether you want
to set up a development environment in the Docker container (when it is set to
`true`), or you want to use the official packaged release defined in
`FARMOS_VERSION` above (when it is set to `false`). The default is `false`. See
more detailed information about how this works below.

### Development vs production

The Dockerfile can theoretically be used for either development or production
environments. The farmOS codebase will be set up differently, depending on the
status of the `FARMOS_DEV` environment variable.

When `FARMOS_DEV` is set to `true`, farmOS is built using `drush make` with the
`--working-copy` flag. This ensures that working Git directories are checked out
for each of the [farmOS projects]. It will use whatever branch is specified in
the `FARMOS_DEV_BRANCH` variable. The `FARMOS_VERSION` variable is ignored.

When `FARMOS_DEV` is set to `false` (this is the default), then the officially
packaged release of farmOS will be downloaded from drupal.org and unpacked in
the container. It will download the version specified in the `FARMOS_VERSION`
variable. The `FARMOS_DEV_BRANCH` variable is ignored.

### Persistent data

The farmOS Docker image assumes that the entire farmOS codebase (located in
`/var/www/html` within the container) will be mounted on the host as a
[Docker volume]. A Docker `ENTRYPOINT` script is included to ensure that the
farmOS codebase is created within that volume.

When the container is destroyed, everything in `/var/www/html` (the entire
farmOS codebase) is preserved in the host. When a new container is created, the
`docker-entrypoint.sh` script rebuilds the farmOS codebase based on the status
of the environment variables described above. When this happens, everything in
the codebase is wiped out and replaced automatically, with the exception of the
`/var/www/html/sites` directory - which is preserved across the rebuild.

This ensures that Drupal's `settings.php`, any uploaded files (in
`sites/default/files`), and any additional modules that you add to
`sites/*/modules` are not lost when the farmOS codebase is rebuilt.

**WARNING:** If you have a development environment (created with `FARMOS_DEV` set to
`true`) and you have made changes to any of the working Git repositories in
`/var/www/html/profiles/farm`, they will be overwritten when the container is
rebuilt. The best way to avoid this is to copy any overridden modules/themes to
the `sites/all/modules` or `sites/all/themes` directory so that they are
preserved when the container is rebuilt.

## Local development with Docker Compose

The recommended approach for local farmOS development in Docker is to use
[Docker Compose] to build both the farmOS container and the MySQL database
container.

The `docker-compose.yml` file included with farmOS sets `FARMOS_DEV` to `true`
by default, so it is intended only for development purposes at this time.

### Install Docker and Docker Compose

First, install Docker and Docker Compose:

* [Install Docker]
    * On Mac OS X, use "Docker for Mac" (not "Docker Toolbox")
    * On Windows, use "Docker for Windows" (not "Docker Toolbox")
    * On Linux, follow the directions on docker.com
* [Install Docker Compose]

### Create containers

To create the farmOS Docker containers, first clone the farmOS repository:

    git clone https://github.com/farmOS/farmOS.git && cd farmOS

Then, use `docker-compose up` to create the containers:

    sudo docker-compose up

This will create two containers: a farmOS application container, and a MySQL
database container.

**Note:** It will take some time for the containers to start the first time.
This is because the farmOS codebase needs to be built when the container is run
(it is not built in the image itself). For this reason, you might not want to
use the `-d` flag at the end of `sudo docker-compose up`, so that you can see
the progress. Once it is built, stopping and starting the container is very
quick.

### Persistent volumes

The docker-compose.yml file defines two Docker volumes that will be available on
your host system:

* `/var/html/www` from the farmOS application container
* `/var/lib/mysql` from the MySQL database container

Both will be made available within a `.data` directory in the farmOS repository
on the host. This is where you will be able to access the code for development
purposes. It is also how your database and files are persisted when the
containers are destroyed and rebuilt.

### Install farmOS

Once the containers are up and running, you can install farmOS using the Drupal
installer. This is a simple step-by-step process that you will need to go
through when you first access the site in your browser.

To find the IP address of your farmOS container, use the following command:

    sudo docker inspect --format '{{ .NetworkSettings.Networks.farmos_default.IPAddress }}' farmos_www_1

Visit the IP address in a browser - you should see the Drupal/farmOS installer.

In the "Set up database" step of installation, use the following values:

* Database name: `farmos`
* Database username: `farmos`
* Database password: `farmos`
* Under "Advanced options", change "Database host" to: `db`

Follow the instructions to continue with the installation and you should be left
with a fully-functioning farmOS instance running in a Docker container!

### Stop/start containers

To stop your running containers:

    sudo docker-compose stop

To start your containers:

    sudo docker-compose start

### Upgrade/rebuild containers

When a new version of farmOS is released, or if you want to freshen up your
development environment, you will need to rebuild the codebase in /var/www/html.

This is done automatically when the containers are destroyed and rebuilt.

    sudo docker-compose stop
    sudo docker-compose rm
    sudo docker pull farmos
    sudo docker-compose up

[Docker]: https://www.docker.com
[Wikipedia]: https://en.wikipedia.org/wiki/Docker_(software)
[Docker Hub]: https://hub.docker.com
[farmOS distribution]: https://www.drupal.org/project/farm
[farmOS GitHub repository]: https://github.com/farmOS/farmOS
[farmOS projects]: /development/projects
[Docker volume]: https://docs.docker.com/engine/tutorials/dockervolumes
[Docker Compose]: https://docs.docker.com/compose
[Install Docker]: https://docs.docker.com/engine/installation
[Install Docker Compose]: https://docs.docker.com/compose/install

