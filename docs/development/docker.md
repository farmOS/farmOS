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
`--working-copy` flag. This ensures that a working Git directory is checked out
for the farmOS installation profile (in `profiles/farm` within the built
codebase). It will use whatever branch is specified in the `FARMOS_DEV_BRANCH`
variable. The `FARMOS_VERSION` variable is ignored.

When `FARMOS_DEV` is set to `false` (this is the default), then the officially
packaged release of farmOS will be downloaded from drupal.org and unpacked in
the container. It will download the version specified in the `FARMOS_VERSION`
variable. The `FARMOS_DEV_BRANCH` variable is ignored.

### Rebuilding the codebase

The farmOS Docker image assumes that the entire farmOS codebase (located in
`/var/www/html` within the container) will be mounted on the host as a
[Docker volume]. A Docker `ENTRYPOINT` script is included to ensure that the
farmOS codebase is created within that volume. When the container is destroyed,
everything in `/var/www/html` persists in the host volume.

Since the farmOS codebase is built in the `ENTRYPOINT` script, and not in the
Dockerfile, that means that updating your Docker image will not necessarily
update your farmOS codebase.

Therefore, the `ENTRTYPOINT` script has logic to decide if/when the farmOS
codebase should be rebuilt. Simply put, if you are running a packaged release of
farmOS, and a new version of farmOS is released, all you have to do is pull an
updated farmOS Docker image and rebuild your container to get the new version.

If, on the other hand, you have a development build (`FARMOS_DEV` is `true`),
then the only way to trigger a rebuild is to delete `profiles/farm/farm.info`.
Then, stop and start your container and the farmOS development codebase will be
rebuilt with `drush make`.

More specifically, farmOS will be rebuilt if one of the following is true:

1. `profiles/farm/farm.info` is missing, or:
2. `FARMOS_DEV` is `false` and the version string in `profiles/farm/farm.info`
does not match `FARMOS_VERSION` in the Dockerfile.

When the codebase is rebuilt, everything in `/var/www/html` is wiped out and
replaced automatically, with the exception of the `/var/www/html/sites`
directory - which is preserved across the rebuild. This ensures that Drupal's
`settings.php`, any uploaded files (in `sites/default/files`), and any
additional modules that you add to `sites/*/modules` are not lost when the
codebase is rebuilt.

**WARNING:** If you have a development environment (created with `FARMOS_DEV`
set to `true`) and you have made changes to any of the working Git repositories
in `/var/www/html/profiles/farm`, they will be overwritten when the codebase is
rebuilt. The best way to avoid this is to copy any overridden modules/themes to
the `sites/all/modules` or `sites/all/themes` directory so that they are
preserved when the codebase is rebuilt.

**ALSO NOTE:** When the codebase is rebuilt, it does NOT automatically run
update.php on the site. You must do this manually. See the [Updating farmOS]
page (specifically the steps for clearing cache and running database updates)
for more details.

## Local development with Docker Compose

The recommended approach for local farmOS development in Docker is to use
[Docker Compose] to build both the farmOS container and the MariaDB or
PostgreSQL database container.

The `docker-compose.yml` file included with farmOS sets `FARMOS_DEV` to `true`
by default, so it is intended only for development purposes at this time.

### PostgreSQL + PostGIS

If you want to use a PostgreSQL database with the PostGIS extension, an
additional `docker-compose.postgis.yml` file is provided which can be used to
override the default `docker-compose.yml` database configuration.

To use the provided override file, use the `-f` flag with all `docker-compose`
commands to read from both the `docker-compose.yml` file and the
`docker-compose.postgis.yml` file. For example, instead of
`sudo docker-compose up` you will run:

    sudo docker-compose -f docker-compose.yml -f docker-compose.postgis.yml up

Alternatively, if you rename `docker-compose.postgis.yml` to
`docker-compose.override.yml`, it will be picked up automatically and you can
simply use `sudo docker-compose up` like you would normally.

### Install Docker and Docker Compose

First, install Docker and Docker Compose:

* [Install Docker]
    * On Mac OS X, use "dlite" (not "Docker for Mac" or "Docker Toolbox")
    * On Windows, use "Docker for Windows" (not "Docker Toolbox")
    * On Linux, follow the directions on docker.com
* [Install Docker Compose]

#### Mac Specific Instructions
Due to [performance issues] with shared volumes in Docker for Mac, [dlite] is
currently the suggested way to host Docker images. The following sections
describe how to install dlite and start/stop containers:

##### Installing
1. Download the latest release from [dlite releases] on GitHub
2. Extract dlite from the tarball and copy it to `/usr/local/bin/dlite`
3. Run: `chmod +x /usr/local/bin/dlite`
4. Initialize dlite by running: `dlite init`
5. Start dlite host by running: `dlite start`

##### Starting
1. Proceed with [Create Containers](#create-containers)
2. Fix networking by running `docker network connect bridge farmos_www_1`
3. Proceed with [Install farmOS](#install-farmos) at [http://farmos\_www\_1.docker](http://farmos_www_1.docker)

##### Stopping
1. Run: `docker-compose stop`
2. Remove networking: `docker network disconnect bridge farmos_www_1`

### Create containers

To create the farmOS Docker containers, first clone the farmOS repository:

    git clone https://github.com/farmOS/farmOS.git && cd farmOS

Then, use `docker-compose up` to create the containers:

    sudo docker-compose up

This will create two containers: a farmOS application container, and a MariaDB
database container (see "PostgreSQL + PostGIS" section above if you would
rather use PostgreSQL).

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
* `/var/lib/mysql` from the MariaDB database container (or
  `/var/lib/postgresql/data` if you are using PostgreSQL)

Both will be made available within a `.data` directory in the farmOS repository
on the host. This is where you will be able to access the code for development
purposes. It is also how your database and files are persisted when the
containers are destroyed and rebuilt.

#### File ownership

On a Linux host, all the files in `.data` will have an owner and group of
`root`. For development purposes, it is recommended that you change the owner
of everything in the `www` container to your local user. This can be done with
the following command (executed from the repository's root directory):

    sudo chown -R ${USER} .data/www

This changes the owner of *everything* in /var/www/html to the currently logged
in user on the host. But it leaves the group alone.

This will persist until the codebase is rebuilt. When rebuilt,
`docker-entrypoint.sh` changes the owner and group of the `.data/www/sites`
directory to `www-data`. So you would need to re-run the above `chown` after
each rebuild. For development environments this isn't a big problem because
rebuilds don't happen automatically - they need to be triggered by deleting
`profiles/farm/farm.info`.

### Install farmOS

Once the containers are up and running, you can install farmOS using the Drupal
installer. This is a simple step-by-step process that you will need to go
through when you first access the site in your browser.

#### Browser address

If you are running Docker on Linux, you can simply go to `http://localhost` in
your browser. Otherwise, you may need to look up the IP address of the Docker
container that was created and access it that way.

To find the IP address of your farmOS container, use the following command:

    sudo docker inspect --format '{{ .NetworkSettings.Networks.farmos_default.IPAddress }}' farmos_www_1

Visit the IP address in a browser - you should see the Drupal/farmOS installer.

#### Database setup

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

[Docker]: https://www.docker.com
[Wikipedia]: https://en.wikipedia.org/wiki/Docker_(software)
[Docker Hub]: https://hub.docker.com
[farmOS distribution]: https://www.drupal.org/project/farm
[farmOS GitHub repository]: https://github.com/farmOS/farmOS
[Docker volume]: https://docs.docker.com/engine/tutorials/dockervolumes
[Updating farmOS]: /hosting/updating
[Docker Compose]: https://docs.docker.com/compose
[Install Docker]: https://docs.docker.com/engine/installation
[Install Docker Compose]: https://docs.docker.com/compose/install
[performance issues]: https://forums.docker.com/t/file-access-in-mounted-volumes-extremely-slow-cpu-bound
[dlite]: https://github.com/nlf/dlite
[dlite releases]: https://github.com/nlf/dlite/releases

