# Migrating from farmOS 1.x to 2.x

1. Install farmOS 2.x.
2. Install the farmOS modules you intend to use (this will determine what
   data is migrated).
3. Add farmOS 1.x database connection info to `settings.php`:

        $databases['migrate']['default'] = [
          'database' => 'my_farmos_1x_db',
          'username' => 'my-db-username',
          'password' => 'my-db-password',
          'prefix' => '',
          'host' => 'localhost',
          'port' => '3306',
          'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
          'driver' => 'mysql',
        ];

4. Copy user-uploaded files to the new directory (see "Uploaded files" below).
5. Install the farmOS Migrate (`farm_migrate`) module.
6. Run the farmOS 1.x Migrations via Drush (in this order):

        drush migrate:import --group=farm_migrate
        drush migrate:import --group=farm_migrate_taxonomy
        drush migrate:import --group=farm_migrate_asset
        drush migrate:import --group=farm_migrate_log
        drush migrate:import --group=farm_migrate_reference

## Uploaded files

farmOS allows files to be uploaded/attached to records. In order to migrate
these files, they need to be copied into new site's files/private directories.

The farmOS migration code will look for files in the following locations:

- Public files: `public://migrate`
- Private files: `private://migrate`

The `public://` and `private://` prefixes map to the "Public file system path"
and "Private file system path" configured in farmOS 1.x and 2.x at:
`/admin/config/media/file-system`. This may vary for each installation.

For example, if you have farmOS 1.x installed in `/var/www/farmOS_1.x` and
farmOS 2.x in `/var/www/farmOS_2.x`, and both are configured to use
`sites/default/files` for public files, and `sites/default/private` for private
files, then copy the files as follows:

    cp -rp /var/www/farmOS_1.x/sites/default/files /var/www/farmOS_2.x/sites/default/files/migrate

    cp -rp /var/www/farmOS_1.x/sites/default/private/files /var/www/farmOS_2.x/sites/default/private/files/migrate

The farmOS migration code will automatically move files from `files/migrate/*`
to `files/*`. Only the files that it finds in the `{file_managed}` table will
be moved, leaving behind various temporary files in the `migrate` directory
that are no longer needed after the migration. This `migrate` directory can be
deleted after the migration, once it has been confirmed that everything was
migrated successfully.

## Limitations

The farmOS migration code is designed to migrate a *default* farmOS 1.x
database to 2.x. If any customizations have been made on top of the defaults,
they will not be migrated.

This includes (but is not limited to):

- Custom asset, entity, taxonomies, and log types
- Custom fields
- Custom roles

If you maintain a contrib/custom module for farmOS 1.x, it is your
responsibility to update the modules for 2.x and provide migration logic.
