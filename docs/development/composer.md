# Composer

The farmOS development Docker image comes pre-installed with
[Composer](https://getcomposer.org), which is used for dependency management.

## Running Composer in Docker

In order to run the `composer` command, you must use `docker exec` to run the
command inside the farmOS container.

    sudo docker exec -it -u www-data -e COMPOSER_MEMORY_LIMIT=-1 farmos_www_1 composer

For example, the following will run the `composer update` command:

    sudo docker exec -it -u www-data -e COMPOSER_MEMORY_LIMIT=-1 farmos_www_1 composer update

## Common tasks

Some common Composer tasks are documented here.

### Updating dependencies

    composer update

### Adding a module

    composer require drupal/[module]

This will download the module into the `web/modules/contrib` directory, and add
it to the root `composer.json` file.

If the module is being added to the farmOS installation profile itself, you
need to manually move the `require` line from the root `composer.json` to
`web/profiles/farm/composer.json` and commit it to that repository.

To install the module, use [Drush](/development/drush).
