# Migrating from farmOS v1

**Note: Migrating directly from farmOS v1 to v3+ is not supported. Migrate from
v1 to v2 first, then *update* to future versions using the normal
[update process](update).**

The upgrade path from farmOS v1 to v2 is performed via a database migration.
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
* If you are using the "Farm sensor: Listener" module, see
  [Sensor data streams](#sensor-data-streams) below
* See [Limitations](#limitations) below.

## Running the migration

Follow the steps below to migrate your farmOS 1.x data to farmOS 2.x:

1. Install farmOS 2.x.
2. Install the farmOS modules you intend to use at `/farm/settings/modules`
   (this will determine what data is migrated). If you have any community
   modules installed in 1.x be sure to download and install 2.x versions.
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

   It is also recommended that you increase the PHP `memory_limit` for Drush by
   adding the following to `settings.php`:

        if (PHP_SAPI === 'cli') {
          ini_set('memory_limit', '512M');
        }

4. Copy user-uploaded files to the new directory (see
   [Migrating files](#migrating-files) below).
5. Install the farmOS Migrate (`farm_migrate`) module.
6. Run the farmOS 1.x Migrations via Drush:

        drush farm_migrate:import

Alternatively, migration groups can be run individually, if you need
more control over the process. They must be run in this order:

    drush migrate:import --group=farm_migrate_config
    drush migrate:import --group=farm_migrate_role
    drush migrate:import --group=farm_migrate_user
    drush migrate:import --group=farm_migrate_file
    drush migrate:import --group=farm_migrate_taxonomy
    drush migrate:import --group=farm_migrate_asset
    drush migrate:import --group=farm_migrate_area
    drush migrate:import --group=farm_migrate_asset_parent
    drush migrate:import --group=farm_migrate_sensor_data
    drush migrate:import --group=farm_migrate_quantity
    drush migrate:import --group=farm_migrate_log
    drush migrate:import --group=farm_migrate_plan

To view the status of all farmOS 1.x migrations:

    drush migrate:status --tag="farmOS 1.x"

After all migrations are complete, perform a thorough examination of data to
confirm that nothing is missing or incorrect. The original 1.x database will
not be touched during the migration, so if issues are discovered it can
continue to be used as the canonical farmOS database until further testing and
debugging can be performed. See [Troubleshooting](#troubleshooting) below for
known issues.

Please open bug reports in the farmOS issue queue if new issues are discovered.

## Rolling back migration

Migrations can be rolled back with the following command:

    drush farm_migrate:rollback

Alternatively, migration groups can be rolled back individually. This should be
done in the following order (reverse of the order of import):

    drush migrate:rollback --group=farm_migrate_plan
    drush migrate:rollback --group=farm_migrate_log
    drush migrate:rollback --group=farm_migrate_quantity
    drush migrate:rollback --group=farm_migrate_sensor_data
    drush migrate:rollback --group=farm_migrate_asset_parent
    drush migrate:rollback --group=farm_migrate_area
    drush migrate:rollback --group=farm_migrate_asset
    drush migrate:rollback --group=farm_migrate_taxonomy
    drush migrate:rollback --group=farm_migrate_file
    drush migrate:rollback --group=farm_migrate_user
    drush migrate:rollback --group=farm_migrate_role
    drush migrate:rollback --group=farm_migrate_config

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

    cp -rp /var/www/farmOS_1.x/sites/default/files /var/www/farmOS_2.x/web/sites/default/files/migrate

    cp -rp /var/www/farmOS_1.x/sites/default/private/files /var/www/farmOS_2.x/web/sites/default/private/files/migrate

The farmOS migration code will automatically move files from `files/migrate/*`
to `files/*`. Only the files that it finds in the `{file_managed}` table will
be moved, leaving behind various temporary files in the `migrate` directory
that are no longer needed after the migration. This `migrate` directory can be
deleted after the migration, once it has been confirmed that everything was
migrated successfully.

## Sensor data streams

If you are using the "Farm sensor: Listener" module in farmOS 1.x to collect
data from sensors, there are a few extra steps and considerations for migrating
and maintaining these data streams in farmOS 2.x.

farmOS 2.x introduces a new entity type called "Data streams", which represent
named sets of time-series data. All data from the old "listener" module can be
migrated into named data streams.

In order to migrate data, you must enable the "Sensor listener (legacy)" module
in farmOS 2.x. This can be found in Drupal core's `/admin/modules` page, or it
can be enabled via Drush:

    drush en farm_sensor_listener

Enabling this module does two things:

1. Adds a migration for 1.x sensor data into 2.x data streams.
2. Creates URL endpoints that match the legacy listener paths, which ensures
   that farmOS will continue receiving and storing data being pushed from your
   sensors.

If you have active sensors that are sending data to your farmOS, and you want
to minimize downtime/interruption of these streams during migration, one
approach is to set up your 2.x instance on a different domain, run migrations,
and then point your domain to the new server. Be sure to set the TTL value of
your domain's DNS record to 5 minutes or less to ensure that the change
propagates quickly. It is highly recommended that you test the migrations at
least once before this, to ensure that they all work smoothly.

If you do not have any active sensors sending data, then you can optionally
uninstall the `farm_sensor_listener` module after running migrations. This will
remove the legacy API endpoints, but still keep your migrated data.

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

### Validation

Validation is performed on all areas, assets, logs, plans, and taxonomy terms
as they are migrated. This will check things like required fields, allowed
values, etc. In some cases the data in a 1.x database will not pass validation,
either because it was not properly validated originally, or due to legacy bugs
in the farmOS 1.x code. If any entities fail validation, migration will stop
and an error like the following will be displayed:

    farm_migrate_asset_plant Migration - 1 failed.

You can view validation messages for individual migrations by running
`drush migrate:messages [migration-id]`, which will provide more details. For
example:

    $ drush migrate:messages farm_migrate_asset_plant
     -------------- ------------------- ------- ---------------------------------------------------------
      Source ID(s)   Destination ID(s)   Level   Message
     -------------- ------------------- ------- ---------------------------------------------------------
      432                                1       [asset: 432]: plant_type=This value should not be null.
     -------------- ------------------- ------- ---------------------------------------------------------

This gives you the opportunity to fix the data in your 1.x database. Then you
can rollback and re-run the migration, like so:

    drush migrate:rollback farm_migrate_asset_plant
    drush migrate:import farm_migrate_asset_plant

### Reset status

If an error occurs during migration, the status of the broken migration may be
stuck as "Importing". In order to rerun the migration, first reset the status
and then roll back the migration. Replace `[migration_id]` with ID of the
migration that is stuck.

    drush migrate:reset-status [migration_id]
    drush migrate:rollback [migration_id]

### Memory limit

If you have a large amount of data, there is a chance you may encounter run
into the PHP memory limit. You will see an error like the following:

    Fatal error: Allowed memory size of 268435456 bytes exhausted

If this happens, you can increase the PHP `memory_limit` setting for Drush by
tweaking the `ini_set('memory_limit', '512M');` line in your `settings.php`,
assuming your host's memory can accommodate it.

**Be sure to roll back any migrations that were being processed when the error
occurred to ensure that no data is corrupted.**

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
