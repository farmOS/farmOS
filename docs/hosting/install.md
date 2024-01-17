# Installing farmOS

These instructions are for installing farmOS on a live production server.
For local development/testing, please refer to the
[development environment](/development/environment)
documentation.

## Server requirements

farmOS is based on [Drupal](https://drupal.org), and therefore shares many of
the same [requirements](https://drupal.org/docs/system-requirements).

### Web server

In addition to Drupal's basic requirements, farmOS has the following server
dependencies. The [farmOS Docker images](#farmos-in-docker) include these.

- **PHP 8+**
- **PHP configuration** - The following PHP settings are recommended:
    - `memory_limit=256M`
    - `max_execution_time=240`
    - `max_input_time=240`
    - `max_input_vars=5000`
    - `realpath_cache_size=4096K`
    - `realpath_cache_ttl=3600`
- **[PHP BCMath extension](https://www.php.net/manual/en/book.bc.php)** and
  **[GEOS](https://trac.osgeo.org/geos)** are required for accurate geometric
  calculations. farmOS can be installed without these, but production usage
  without them is strongly discouraged.

### Database server

A database server needs to be provisioned that farmOS can connect to.
PostgreSQL is recommended. MySQL/MariaDB and SQLite are also supported.

This can be installed on the same server as farmOS (either directly or in a
Docker container), or it can be on a separate server.

If PostgreSQL is used, it must be version 12 or higher, and the `pg_trgm`
extension must be installed and enabled on the farmOS database. On PostgreSQL
13+ this will be enabled automatically by farmOS. On PostgreSQL 12, the
following SQL query must be run on the farmOS database by a PostgreSQL
superuser to enable the extension:

    CREATE EXTENSION pg_trgm;

### SSL

Although not strictly a requirement, some features (like the "Geolocate" button
on maps) will only work over a secure connection. [Field Kit](https://farmOS.app)
requires SSL in order to connect to it. SSL is also recommended if you are
streaming sensor data into farmOS, to keep your sensor's private key a secret.

A common strategy is to use [Nginx](https://nginx.org) as a reverse proxy with
SSL termination, which listens on port 443 and forwards to farmOS on port 80.
[Let's Encrypt](https://letsencrypt.org) is a good option for free SSL
certificate issuance, and renewal can be automated via cron.

These resources may be helpful:

- [Drupal HTTPS Information](https://www.drupal.org/https-information)
- [Reverse Proxy Forum Post](https://farmos.discourse.group/t/running-behind-reverse-proxy/108) -
  Includes links to related GitHub issues and examples of how others have
  configured reverse proxies serving HTTPS.
- [Local HTTPS](/development/environment/https) - Documentation for running an
  Nginx reverse proxy with self-signed certificates for local farmOS
  development with HTTPS.

### Satellite map layers

farmOS includes an optional [Mapbox](https://www.mapbox.com) module that can be
enabled to add satellite imagery layers to the map. A Mapbox API key is
required. For more information, see Mapbox's official documentation:
[Access tokens](https://docs.mapbox.com/help/how-mapbox-works/access-tokens).
Enable the Mapbox module at Setup > Modules, and then add the API key at
Setup > Settings > Map > Mapbox.

## farmOS Codebase

There are two supported approaches to deploying the farmOS codebase:

1. Using [Docker](https://docker.com) images.
2. Using packaged releases.

Docker is the recommended method of hosting farmOS because it encapsulates the
server level dependencies that farmOS needs.

If you need to build a more customized farmOS codebase, including modules
provided by the community, or custom modules written by yourself, see
[Building farmOS with Composer](/hosting/composer).

### farmOS in Docker

Official farmOS Docker images are available on Docker Hub:
[https://hub.docker.com/r/farmos/farmos](https://hub.docker.com/r/farmos/farmos)

This allows farmOS to be run in a Docker container with:

    docker pull farmos/farmos:3.x.y
    docker run --rm -p 80:80 -v "${PWD}/sites:/opt/drupal/web/sites" farmos/farmos:3.x.y

Replace `3.x.y` with the desired version. Find the latest farmOS version on the
[GitHub release page](https://github.com/farmOS/farmOS/releases). Using the
`latest` Docker tag is not recommended, because updates require manual steps.
See [Updating farmOS](/hosting/update) for more info.

This will pull the farmOS Docker image, provision a farmOS web server container
listening on port 80, and bind-mount a `sites` directory into the container for
persistence of settings and uploaded files.

#### Docker Compose

[Docker Compose](https://docs.docker.com/compose) can be used to encapsulate these decisions.

An example `docker-compose.production.yml` configuration file is provided in
the farmOS repository's `docker` directory, with an accompanying `README.md`.
Copy this to a file named `docker-compose.yml` in the directory you would like
to install farmOS, update the `farmos/farmos:x.y.z` version reference, and run:

    docker compose up -d

#### Persistence

All site-specific settings and user-uploaded files are stored in
`/opt/drupal/web/sites` inside the container, so it is important that the
contents of this directory be persisted outside of the container. Bind-mounting
a directory from the host into the container is the recommended way to achieve
this.

The `docker run` command above does this, as well as the example
`docker-compose.yml` provided in the farmOS repository's `docker` directory.

If the `sites` directory is not persisted, all settings and files will be lost
when the container is destroyed, and you will be prompted to install farmOS
again when a new container is started.

#### Customizing PHP

If customizations to PHP's configuration are required, such as increasing the
maximum upload size limit, you can bind-mount a custom PHP settings file into
the container.

Create a file called `php.ini` alongside `docker-compose.yml`:

```
upload_max_filesize = 50M
post_max_size = 50M
```

Bind-mount `php.ini` into the `www` service in your `docker-compose.yml` file:

```
    volumes:
      ...
      - './php.ini:/usr/local/etc/php/conf.d/farmos.ini'
```

### Packaged releases

An alternative to the Docker-based deployment is to install the farmOS codebase
directly on the host server using a packaged release tarball, available from
GitHub: [github.com/farmOS/farmOS/releases](https://github.com/farmOS/farmOS/releases)

Packaged releases include everything from the `/opt/drupal` directory in the
Docker image. This represents the entire farmOS codebase, pre-built with
[Composer](https://getcomposer.org).

Download and unpack the tarball on your web server, and point the document root
at the `web` subdirectory.

## Installing farmOS

Once you have the farmOS codebase deployed, and a database server provisioned,
you can proceed with the web-based farmOS installation. Visit the farmOS
server's hostname in your browser and follow the steps to install farmOS and
optional modules.

### File uploads

In order to upload files, a private filesystem path must be configured in the
`settings.php` file after installation is complete.

If you are using the official Docker image, and bind-mounting the `sites`
directory as a volume, add the following line to `sites/default/settings.php`:

    $settings['file_private_path'] = '/opt/drupal/web/sites/default/private/files';

Additionally, create the folder `/opt/drupal/web/sites/default/private/`.

Set the correct user and permissions:

Folder ownership and group should match the web server user. If you are using
the farmOS Docker image (running Apache), this will be `www-data`.

Folder permissions should be set to `770` or `drwxrwx---`.

If you are using a packaged release outside of Docker, replace `/opt/drupal/web`
with the path to the webroot directory that contains your `sites` directory.

Finally, make sure to clear the caches by visiting Administration >
Configuration > Development > Performance and clicking the `Clear all caches`
button, or use Drush via the command line: `drush cr`.

### Cron

farmOS performs routine tasks via its built-in cron. This includes database
cleanup and garbage collection. A cron job must be configured on the host
system to trigger this. There are three ways to do this:

**1. Via the `drush cron` command (recommended)**

The Drush command-line tool provides a `cron` command for triggering the
execution of cron tasks. This can be run via a scheduled system `crontab`.
Weekly is enough for most use-cases.

The actual `drush` command will vary depending on how/where you have farmOS
installed. Drush is located in `vendor/bin/drush` within the farmOS codebase,
and may require additional arguments to find your farmOS database correctly.
For more information, see the [Drush cron documentation](https://www.drush.org/latest/cron).

If you are running farmOS in the official Docker container, your `crontab`
will look something like this (replace `[container-name]` with the name of your
running farmOS container):

`4 0 * * 0 sudo docker exec -it -u www-data [container-name] drush cron > /dev/null`

**2. Via the secret cron URL**

A special URL is available for running cron via an HTTP request. This URL
includes a secret key to prevent it from being run by unauthorized requests.
To find the secret cron URL, while logged in as a farmOS administrator, go to
Administration > Configuration > System > Cron. The URL will look like this:

`https://[base-url]/cron/[secret-key]`

Use `wget` in a Linux cron job to request this URL on a schedule. Weekly is
enough for most use-cases.

`4 0 * * 0 wget -O - -q -t 1 [cron-url] > /dev/null`

The only disadvantage of this approach is it may run into PHP `memory_limit` or
`max_execution_time` restrictions, if the cron tasks are particularly long or
complex.

**3. Via the "Automated Cron" module (not recommended)**

Drupal also includes an "Automated Cron" module, which is disabled by default
in farmOS. When enabled, this module will automatically perform cron tasks at
the end of normal visitor requests. This option is only recommended if your
host system does not have the ability to configure cron jobs. It can cause
random requests to farmOS to be very slow, as the cron tasks are tacked onto
the normal page loading process.
