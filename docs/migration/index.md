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

4. Install the farmOS Migrate (`farm_migrate`) module.
5. Run the farmOS 1.x Migration via Drush:

        drush migrate:import --group=farm_migrate
