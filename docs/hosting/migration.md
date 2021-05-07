---
title: 1.x Migration
---

# Migrating from farmOS 1.x to 2.x

The upgrade path from farmOS 1.x to 2.x is performed via a database migration.
farmOS 2.x includes a **farmOS Migrate** module that leverage's Drupal core's
[Migrate API](https://drupal.org/docs/drupal-apis/migrate-api) to provide
migrations for each asset type, log type, etc. These migrations are defined in
YML configuration files included with the farmOS Migrate module.

## Important considerations

* Do not migrate into a farmOS 2.x instance that already has records. This is
  to ensure that the internal auto-incrementing IDs of records are maintained.
* Execute the migrations in the *exact* order they are shown below. It is
  especially important that all assets are migrated *before* any areas, because
  areas are converted to assets during the migration, which can cause ID
  conflicts/collisions.
  See [Issue #3203228](https://www.drupal.org/project/farm/issues/3203228)
* Uploaded photos/files must be copied to the destination filesystem before
  migrating. See [Migrating files](#migrating-files) below.
* See [Limitations](#limitations) below.

## Running the migration

Follow the steps below to migrate your farmOS 1.x data to farmOS 2.x:

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

4. Copy user-uploaded files to the new directory (see
   [Migrating files](#migrating-files) below).
5. Install the farmOS Migrate (`farm_migrate`) module.
6. Run the farmOS 1.x Migrations via Drush (in this order):

        drush migrate:import --group=farm_migrate_config
        drush migrate:import --group=farm_migrate_role
        drush migrate:import --group=farm_migrate_user
        drush migrate:import --group=farm_migrate_file
        drush migrate:import --group=farm_migrate_taxonomy
        drush migrate:import --group=farm_migrate_asset
        drush migrate:import --group=farm_migrate_area
        drush migrate:import --group=farm_migrate_sensor_data
        drush migrate:import --group=farm_migrate_quantity
        drush migrate:import --group=farm_migrate_log
        drush migrate:import --group=farm_migrate_plan

7. Confirm that all the above migrations were successful before running the
   final migration, which focuses only on populating the "Parents" field of
   assets. This migration cannot be rolled back (see
   [Issue #3189740](https://www.drupal.org/project/farm/issues/3189740)):

        drush migrate:import --group=farm_migrate_reference

To view the status of all farmOS 1.x migrations:

    drush migrate:status --tag="farmOS 1.x"

After all migrations are complete, perform a thorough examination of data to
confirm that nothing is missing or incorrect. The original 1.x database will
not be touched during the migration, so if issues are discovered it can
continue to be used as the canonical farmOS database until further testing and
debugging can be performed. See [Troubleshooting](#troubleshooting) below for
known issues.

Please open bug reports in the farmOS issue queue if new issues are discovered.

## Migrating files

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

## Troubleshooting

If an error occurs during migration, the status of the broken migration may be
stuck as "Importing". In order to rerun the migration, first reset the status
and then roll back the migration. Replace `[migration_id]` with ID of the
migration that is stuck.

    drush migrate:reset-status [migration_id]
    drush migrate:rollback [migration_id]

### Movement logs

farmOS 2.x changes the way asset movements are described via logs. There is a
single "Location reference" and "Geometry" field on logs now, as opposed to
the separate "Move to" and "Movement geometry" fields that existed in 1.x. The
migration will use the movement area references and geometry if they are
present, and will automatically mark the log as a movement.

However, if the log has additional area references and geometry data, then the
migration logic will detect the conflict and one of the following errors will
be thrown:

> Log 123 has both area references and movement area references.

> Log 123 has both a geometry and a movement geometry.

If these errors are encountered, the migration will halt and can not be
completed until either:

1. the logs in the old database are cleaned up, or
2. the migration script is explicitly allowed to overwrite non-movement area
   references and geometry

Manual clean up involves reviewing the logs that cause errors in the old
database, deleting the "Areas" and "Geometry" fields (or copying them into the
"Move to" and "Movement geometry" fields), and retrying the migration. In some
cases it may make sense to split the log into two separate logs, in order to
retain information.

Alternatively, the migration script can be allowed to automatically overwrite
the "Areas" and "Geometry" data from the log, and only keep the "Move to" and
"Movement geometry" data. This can be configured by  adding the following line
to `settings.php`:

    $settings['farm_migrate_allow_movement_overwrite'] = TRUE;

**Beware that this may result in loss of data/context if the separate fields
were being used intentionally. It is recommended that logs be reviewed manually to
understand whether or not the data is needed.**

After running the migration with this setting, warnings for each log will be
stored, and can be viewed with:

    drush migrate:messages [migration_id]

### Quantities

The farmOS 2.x migration creates all Quantity entities before it creates the
Log entities that reference them. This means that it is possible to end up with
orphaned quantities, if for instance you do not migrate all of your log types
from farmOS 1.x. There is no built-in way to clean these up currently, so it is
recommended that all log types be migrated.
