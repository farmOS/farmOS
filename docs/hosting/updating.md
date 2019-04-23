# Updating farmOS

New versions of farmOS will be released on a regular basis, and it's important
to stay up-to-date so that you can receive new features, bug fixes, and security
patches when they become available.

## Do not use Drupal's automatic update feature

Drupal provides an interface for downloading and updating contributed modules
automatically.

**This process may break your farmOS system.**

farmOS is a Drupal distribution, and includes patches to some contributed
Drupal modules that are necessary for proper functioning. Drupal's automatic
update feature will not apply the necessary patches when it downloads the new
version of a module.

It also does not support updating Drupal distributions (only modules and
themes). farmOS is a Drupal distribution, and new versions often include
automated update code to ensure a smooth update from one version of farmOS to
the next.

Therefore, it is recommended that you either download the officially packaged
releases of farmOS from [https://drupal.org/project/farm],
[build it yourself with Drush], or run [farmOS on Docker]. This will ensure
that the necessary patches are applied, and that the farmOS distribution
updates are included.

## Update procedure

Updating farmOS is basically the same process as [updating Drupal core]. The
following procedure will ensure that your update goes smoothly.

**Docker note**: if you are hosting farmOS in Docker, see
[Hosting farmOS with Docker] for Docker-specific update instructions.

1. Backup your code and database! Always do this before making big changes. Be
   ready and able to roll-back in the event that something goes wrong.
2. Download the new recommended release of farmOS from
   [https://drupal.org/project/farm]
3. Unzip the compressed folder, and replace everything in your Drupal codebase
   EXCEPT the `/sites/` folder. **Do not overwrite the `/sites/` folder, because
   it contains content and configuration for your site.**
4. [Run database updates].

Optionally:

* Revert all Features via Drush. **If you have intentionally overridden any
  specific farmOS configurations, then you should NOT do this.** You will need
  to resolve any merge conflicts with farmOS core Features changes in order to
  complete the upgrade. See [developing with updates in mind].

    `drush fra`

[updating Drupal core]: https://drupal.org/node/1223018
[Hosting farmOS with Docker]: /hosting/docker
[https://drupal.org/project/farm]: https://drupal.org/project/farm
[build it yourself with Drush]: /hosting/installing
[farmOS on Docker]: /development/docker
[Drush]: https://github.com/drush-ops/drush
[Drush Registry Rebuild]: https://drupal.org/project/registry_rebuild
[Run database updates]: https://drupal.org/upgrade/running-update-php
[developing with updates in mind]: /development/update-safety

