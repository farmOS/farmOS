# Composer

The farmOS development Docker image comes pre-installed with
[Composer](https://getcomposer.org), which is used for dependency management.

## Running Composer in Docker

In order to run the `composer` command, you must use `docker exec` to run the
command inside the farmOS container.

    docker exec -it -u www-data farmos_www_1 composer

For example, the following will run the `composer help` command:

    docker exec -it -u www-data farmos_www_1 composer help'

**Warning**: If `composer update farmos/farmos` is run, it will replace the
Git repository in `web/profiles/farm`, discarding all
changes/branches/remotes/etc.

## Common tasks

Some common Composer tasks are documented here.

### Adding a module

    composer require drupal/[module]

This will download the module into the `web/modules/contrib` directory, and add
it to the root `composer.json` file.

If the module is being added to the farmOS installation profile itself, you
need to manually move the `require` line from the root `composer.json` to
`web/profiles/farm/composer.json` and commit it to that repository.

To install the module, use [Drush](/development/environment/drush).

## Notes

- `Could not delete /var/www/html/web/sites/default/default.settings.php`
  See https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates#s-troubleshooting-permission-issues-prevent-running-composer

