# Updating farmOS

New versions of farmOS will be released on a regular basis, and it's important
to stay up-to-date so that you can receive new features, bug fixes, and security
patches when they become available.

Updating farmOS is basically the same process as [updating Drupal core].

The general procedure is:

1. Backup your code and database! Always do this before making big changes. Be
   ready and able to roll-back in the event that something goes wrong.
2. Download the new recommended release of farmOS from
   [https://drupal.org/project/farm]
3. Unzip the compressed folder, and replace everything in your Drupal codebase
   EXCEPT the `/sites/` folder. The easiest way to do this is to unzip to a new
   folder, move the `/sites/` folder from your old codebase to the new one, and
   then rename the new folder so that it takes the place of your old codebase.
   It's critical that you do not overwrite the `/sites/` folder, because that's
   where your uploaded files are stored, as well as the configuration files that
   tell Drupal how to connect to your database.
4. Clear your cache and rebuild your registry with [Drush] and
   [Drush Registry Rebuild]. This isn't always necessary, but it doesn't hurt.

    `drush cc all`

    `drush rr`

5. Run database updates by going to `http://[my-farmOS-site]/update.php` and
   following the steps. These updates will ensure that your database structure
   matches what your code expects.


[updating Drupal core]: https://drupal.org/node/1223018
[https://drupal.org/project/farm]: https://drupal.org/project/farm
[Drush]: https://github.com/drush-ops/drush
[Drush Registry Rebuild]: https://drupal.org/project/registry_rebuild

