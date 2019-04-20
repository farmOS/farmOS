# Running farmOS with Docker

This directory contains files necessary to build the farmOS Docker image, along
with example `docker-compose.yml` files that can be used for running farmOS in
Docker containers.

To run a farmOS development environment, copy `docker-compose.development.yml`
into a new directory on your server, rename it to `docker-compose.yml` and run
`docker-compose up`.

If you would like to experiment with installing farmOS on PostgreSQL with
PostGIS, copy the `docker-compose.override.postgis.yml` file to the same
directory and rename it to `docker-compose.override.yml`. This will override
the `db` configuration from `docker-compose.development.yml`.

For more information, see farmOS.org/hosting/docker.
