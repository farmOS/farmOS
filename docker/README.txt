# Running farmOS with Docker

This directory contains files necessary to build the farmOS Docker image, along
with example `docker-compose.yml` files that can be used for running farmOS in
Docker containers.

## Development environment

To run a farmOS development environment, copy `docker-compose.development.yml`
into a new directory on your server, rename it to `docker-compose.yml` and run
`docker-compose up`.

If you would like to experiment with installing farmOS on PostgreSQL with
PostGIS, copy the `docker-compose.override.postgis.yml` file to the same
directory and rename it to `docker-compose.override.yml`. This will override
the `db` configuration from `docker-compose.development.yml`.

## Production environment

To run a farmOS production environment, use `docker-compose.production.yml` as
an example for building your own configuration. Note that this example does not
include a database. It is assumed that in production environments the database
will be managed outside of Docker.

For more information, see farmOS.org/hosting/docker.
