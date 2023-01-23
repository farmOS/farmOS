# Running farmOS with Docker

This directory contains files necessary to build the farmOS Docker image, along
with example `docker-compose.yml` files that can be used for running farmOS in
Docker containers.

## Development environment

To run a farmOS development environment, copy `docker-compose.development.yml`
into a new directory on your server, rename it to `docker-compose.yml` and run
`docker compose up`.

This example mounts a local `www` directory on the host as a volume in the
container at `/opt/drupal`, which allows for local development with an IDE.

## Production environment

To run a farmOS production environment, use `docker-compose.production.yml` as
an example for building your own configuration. It references a non-existent
`farmos/farmos:x.y.z` image version tag, which should be replaced with the most
recent farmOS stable release version.

This example mounts a local `sites` directory on the host as a volume in the
container at `/opt/drupal/web/sites`, which contains the site-specific settings
and uploaded files. This allows a production farmOS instance to be updated by
simply pulling a new image (and then manually running database updates via Drush
or `/update.php`). Everything outside of the `sites` directory will not be
preserved and will be replaced with the new official farmOS files.

Note that this example does not include a database. It is assumed that in
production environments the database will be managed outside of Docker.

For more information, see https://farmOS.org/hosting.
