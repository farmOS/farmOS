FARM MANAGER
============

Provides a Farm Manager role with all farm permissions.

This module is part of the [FarmOS](http://drupal.org/project/farm)
distribution.

This module provides two things: 1) a new role called "Farm Manager", and 2) a
hook that other modules can implement to assign permissions to that role:
hook_farm_manager_perms(), which just needs to return an array of permission
strings.

The Farm Manager module implements this hook on behalf of all the farm_*
modules to automatically add all their necessary permissions. But you can also
write a custom module that implements this hook to do the same thing... if you
want to craft your own permissions for the Farm Manager role. Maybe in the
future, we can add an alter hook to alter the ones provided by other modules as
well. This will be very flexible.

The nice thing about this approach is we can include it in the FarmOS
distribution (http://drupal.org/project/farm), but if other people want to use
the farm_* module individually, and want to define their own permissions, they
can do that. So it's the best of all worlds, I think.

The only catch (right now), is that the permissions are very strictly enforced.
If you try to disable certain permissions in the Drupal UI, they will be
immediately reset to those defined in the Farm Manager module. This is because
the module is not super complicated ... it just tries to determine which
permissions the role DOES have, and compares it to a list of permissions it
SHOULD have... then it adds/removes permissions to sync them up. If this is a
problem for someone, they have two choices: don't use the Farm Manager module,
and do it yourself... or help us write an alter hook to be able to alter the
permissions in code.

DEPENDENCIES
------------

**Requires Drupal core patch!**

Note that this module depends on a patch to Drupal core that ensures vocabulary
names are used in the naming of permissions, rather than vocabulary IDs (which
can vary from site to site).

Here is the patch: http://www.drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

And the related issue thread: http://drupal.org/node/995156

If you are using the FarmOS distribution, this patch is already included.

INSTALLATION
------------

Install as you would normally install a contributed drupal module. See:
http://drupal.org/documentation/install/modules-themes/modules-7 for further
information.

MAINTAINERS
-----------

Current maintainers:
 * Michael Stenta (m.stenta) - https://drupal.org/user/581414

This project has been sponsored by:
 * [Farmier](http://farmier.com)
   Built on top of FarmOS, Farmier is a hosted platform that provides
   website and farm management tools to farmers in the cloud.
