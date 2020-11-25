# Drush

The farmOS Docker image comes pre-installed with
[Drush](https://www.drush.org), which provides shell commands for working with
a Drupal installation.

## Running Drush in Docker

In order to run the `drush` command, you must use `docker exec` to run the
command inside the farmOS container.

    docker exec -it -u www-data farmos_www_1 drush

For example, the following will run the `drush cr` command to rebuild caches:

    docker exec -it -u www-data farmos_www_1 drush cr

## Useful commands

Some useful Drush commands are documented here.

### Rebuild caches

    drush cr

### Install a module

    drush en log
