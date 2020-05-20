# Updating farmOS

**ALWAYS BACKUP YOUR DATABASE, CODE, AND FILES BEFORE ATTEMPTING AN UPDATE!**

New versions of farmOS will be released on a regular basis, and it's important
to stay up-to-date so that you can receive new features, bug fixes, and security
patches when they become available.

It is recommended that you either download the officially packaged releases of
farmOS from [https://drupal.org/project/farm] or run [farmOS on Docker]. This
will ensure that the necessary patches are applied, and that the farmOS
distribution updates are included.

**Note that automatic updates through Drupal's UI are disabled by farmOS** See
[https://drupal.org/node/3136140] for more information.

## Update procedure

Updating farmOS is basically the same process as [updating Drupal core]. The
following procedure will ensure that your update goes smoothly.

**Docker note**: if you are hosting farmOS in Docker, see
[Hosting farmOS with Docker] for Docker-specific update instructions.

1. **Backup your database, code, and files!** Always do this before updating. Be
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

[https://drupal.org/project/farm]: https://drupal.org/project/farm
[farmOS on Docker]: /development/docker
[https://drupal.org/node/3136140]: https://drupal.org/node/3136140
[updating Drupal core]: https://drupal.org/node/1223018
[Hosting farmOS with Docker]: /hosting/docker
[Run database updates]: https://drupal.org/upgrade/running-update-php
[developing with updates in mind]: /development/update-safety

