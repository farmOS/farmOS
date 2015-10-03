FARM ACCESS
===========

Provides mechanisms for managing farmOS user access permissions.

This module is part of the [farmOS](http://drupal.org/project/farm)
distribution.

Three hooks are provided (see example usage in farm_access.api.php):

* hook_farm_access_roles() - for defining farm roles
* hook_farm_access_perms() - for defining farm permissions
* hook_farm_access_perms_alter() - for altering role permissions defined by other modules

Three roles are provided via hook_farm_access_roles():

* Farm Manager
* Farm Worker
* Farm Viewer

Most farmOS modules implement hook_farm_access_perms() to add their necessary
permissions to each of the three roles above. But you can also write a custom
module that implements this hook to do the same thing... if you want to craft
your own permissions. Or, you can use hook_farm_access_perms_alter() to alter
the list of permissions defined by others.

The nice thing about this approach is we can include it in the farmOS
distribution (http://drupal.org/project/farm), but if other people want to use
a farm_* module individually, and want to define their own permissions, they
can do that without a dependency on farm_access. So it's the best of all worlds,
I think.

The only catch (right now), is that the permissions are very strictly enforced.
If you try to disable certain permissions in the Drupal UI, they will be
immediately reset to those defined by modules that implement
hook_farm_access_perms(). This is because the module is not super complicated
... it just tries to determine which permissions the role DOES have, and
compares it to a list of permissions it SHOULD have... then it adds/removes
permissions to sync them up. If this is a problem for someone, they have two
choices: don't use the Farm Access module, and do it yourself... or implement
hook_farm_access_perms_alter() to make changes to the permissions available
to each role.

DEPENDENCIES
------------

**Requires Drupal core patch!**

Note that this module depends on a patch to Drupal core that ensures vocabulary
names are used in the naming of permissions, rather than vocabulary IDs (which
can vary from site to site).

Here is the patch: http://www.drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

And the related issue thread: http://drupal.org/node/995156

If you are using the farmOS distribution, this patch is already included.

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
