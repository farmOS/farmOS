# Updating farmOS

**ALWAYS BACKUP YOUR DATABASE, CODE, AND FILES BEFORE ATTEMPTING AN UPDATE!**

New versions of farmOS are released on a regular basis, and it's important to
stay up-to-date so that you can receive new features, bug fixes, and security
patches when they become available. Find the latest farmOS version on the
[GitHub release page](https://github.com/farmOS/farmOS/releases).

## Update procedure

1. **Backup your database and files!** Always do this before updating. Be ready
   and able to roll-back in the event that something goes wrong. Typically this
   can be done by creating a database dump and a tarball of your `web/sites`
   directory. If you are using Docker, be sure you can roll back to the
   previous image version. If you are using packaged releases, be sure you
   also keep a copy of the old farmOS codebase before updating.
2. **Update the farmOS codebase.** This will depend on how you have deployed
   farmOS. See [Updating via Docker](#updating-via-docker) or
   [Updating via packaged releases](#updating-via-packaged-releases) below for
   specific instructions. If you are building a custom farmOS codebase with
   Composer see
   [Updating dependencies](/hosting/composer#updating-dependencies).
3. **Run automated updates.** Navigate to `https://[hostname]/update.php` in
   your browser and follow the steps to run automated updates. It is important
   to do this before using the new version of farmOS to ensure that any
   necessary changes to the database or configuration are made.
4. **Clear caches.** farmOS caches can be cleared by going to
   `https://[hostname]/admin/config/development/performance` in your browser
   and clicking "Clear all caches", or via the command line with Drush:
   `drush cr`. Cache clearing is only necessary if no updates are performed
   during `update.php`, otherwise they will be cleared automatically.

### Updating via Docker

If you have [deployed farmOS via Docker](/hosting/install#farmos-in-docker) you
can update to a new version of farmOS by pulling the new Docker image version:

    docker pull farmos/farmos:[version]

This can also be done by updating your `docker-compose.yml` file if you are
using Docker Compose and restarting your containers.

Assuming that `/opt/drupal/web/sites` is the only directory persisted outside
of the container, this will update the farmOS codebase, which is located in
`/opt/drupal` within the container.
See [Persistence](/hosting/install#persistence) for more information.

### Updating via packaged releases

If you have [deployed farmOS via packaged releases](/hosting/install#packaged-releases)
you can update to a new version of farmOS by downloading the new release
tarball and unpacking it in place of the old codebase, replacing everything
except the `web/sites` directory. Be sure to *replace* (not *merge*) all files
and directories.

**Do not overwrite the `web/sites` directory, because it contains all your
site-specific settings and uploaded files.**

### Maintenance mode

Optionally, you may put farmOS into "maintenance mode" to prevent users from
accessing it during the update process.
Navigate to `https://[hostname]/admin/config/development/maintenance` in your
browser, enable maintenance mode, perform the update, confirm the new version
works, then disable maintenance mode. If you are the only user of farmOS then
this is not necessary, but be sure to run `update.php` before using the new
version.

### Drush

An alternative to running the automated updates by visiting `update.php` in
your browser is to use the command-line tool [Drush](https://www.drush.org).

The Drush command for running updates is:

    drush updb

If you are running farmOS in Docker with the standard Docker Compose
configuration, you can run this command inside the container with:

    docker compose exec www drush updb

## Security releases

When there are security updates released for farmOS dependency modules, you may
see the following message:

> There is a security update available for your version of Drupal. To ensure
> the security of your server, you should update immediately! See the available
> updates page for more information.

The farmOS maintainers will update these dependencies in the development branch
of farmOS as soon as possible, and if the vulnerabilities affect farmOS
directly a new version will be tagged and released. Many vulnerabilities are
mitigated by the fact that farmOS is only accessible to users with a login.
In those cases the maintainers may deem a release unnecessary. This message can
be disabled by uninstalling the "Update Manager" module.
