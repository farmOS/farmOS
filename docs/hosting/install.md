---
title: Installing
---

# Installing farmOS

## Server requirements

farmOS is based on [Drupal](https://drupal.org), and therefore shares many of
the same [requirements](https://drupal.org/docs/system-requirements).

### Web server

In addition to Drupal's basic requirements, farmOS has the following server
dependencies. The [farmOS Docker images](#farmos-in-docker) include these.

- **PHP 7+**
- **PHP configuration** - The following PHP settings are recommended:
    - `memory_limit=256M`
    - `max_execution_time=240`
    - `max_input_time=240`
    - `max_input_vars=5000`
    - `realpath_cache_size=4096K`
    - `realpath_cache_ttl=3600`
- **[PHP BCMath extension](https://www.php.net/manual/en/book.bc.php)** is
  required for accurate geometric polygon area calculations.
- **[GEOS](https://trac.osgeo.org/geos)** is required by the Bed Generator
  module.

### Database server

A database server needs to be provisioned that farmOS can connect to.
PostgreSQL is recommended. MySQL/MariaDB and SQLite are also supported.

This can be installed on the same server as farmOS (either directly or in a
Docker container), or it can be on a separate server.

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

### API Keys

Optional modules are available for adding satellite imagery layers to maps (eg:
Mapbox, Google Maps, etc). However, because these layers are hosted by
third-party providers, API keys are required to use them. Instructions for
obtaining API keys are available via the links below. API keys can be entered
into farmOS by going to Settings > Map.

- [Mapbox](https://docs.mapbox.com/help/how-mapbox-works/access-tokens)
- [Google Maps](https://developers.google.com/maps/documentation/javascript/get-api-key)

## farmOS Codebase

There are two supported approaches to deploying the farmOS codebase:

1. Using [Docker](https://docker.com) images.
2. Using packaged releases.

Docker is the recommended method of hosting farmOS because it encapsulates the
server level dependencies that farmOS needs.

### farmOS in Docker

Official farmOS Docker images are available on Docker Hub:
[https://hub.docker.com/r/farmos/farmos](https://hub.docker.com/r/farmos/farmos)

This allows farmOS to be run in a Docker container with:

    docker pull farmos/farmos:2.x
    docker run --rm -p 80:80 -v "${PWD}/sites:/opt/drupal/web/sites" farmos/farmos:2.x

This will pull the farmOS Docker image, provision a farmOS web server container
listening on port 80, and bind-mount a `sites` directory into the container for
persistence of settings and uploaded files.

#### Docker Compose

[Docker Compose](https://docs.docker.com/compose) can be used to encapsulate these decisions.

An example `docker-compose.production.yml` configuration file is provided in
the farmOS repository's `docker` directory, with an accompanying `README.md`.
Copy this to a file named `docker-compose.yml` in the directory you would like
to install farmOS and run:

    docker-compose up -d

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
