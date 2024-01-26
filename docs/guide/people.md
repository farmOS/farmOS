# People

farmOS allows a farm to have multiple "users" accessing it, and each of those
users is assigned one or more "roles" to grant them different levels of
permission.

Roles can be "managed" or "unmanaged." The permissions of managed roles are
controlled by modules and cannot be modified through the UI. Unmanaged roles
can be added/edited through the UI.

Three default managed roles are provided with farmOS:

- **Manager** - Has access to everything in farmOS. They can create, edit, and
  delete records, and they can change configuration settings.
- **Worker** - Has most of the same permissions as Managers, but cannot change
  configuration.
- **Viewer** - Limited to viewing farmOS records - but they cannot edit, delete
  or change configuration. This role is useful if you want to share your farm's
  activities with someone, but you don't want to give them the ability to make
  changes. For example, if you need to share your farm records with an Organic
  Certifying Agent, you can give them a user account with the Viewer role.

These roles can be disabled by uninstalling the "Default Roles" module.

The "farmOS Account Admin Role" module provides another optional managed role
called **Account Admin**, which has permission to add/edit/remove other users.
This is useful in situations where an instance administrator wants to give
someone the ability to set up other accounts, without giving them full admin
access.

Permissions for managed roles cannot be modified through the UI. This is not
generally an issue since the provided roles have been carefully tailored to
work for most applications. In some cases, you may want to further customize
user permissions.

The simplest way to customize permissions is to add unmanaged roles alongside
the managed ones. With this strategy, users are given the minimum required
permissions using the managed roles then granted additional permissions via
unmanaged roles that are manually configured. Permissions are additive, so
a user with multiple roles will have all the permissions included with each.

For example, imagine some users who have the Worker role but not the Manager
role need to be able to configure farm reports - which they cannot do with
Worker role alone. One option would be to make them all Managers, but this can
be confusing or risky if it doesn't match their real-world role or trust level.
Instead, a new role called "Report Manager" (the name is arbitrary) can be
created with permission to configure farm reports. This new role can then be
selectively given to just those users who need the additional access.

In some cases where significantly different permissions are required than
default managed roles, it may be preferable to disable the "Default Roles"
module and create alternate roles manually - or through a custom module.

For example, imagine some users are responsible only for animals and other
users are responsible only for plants. One option would be to make them all
Workers or use the above strategy of additional roles to give those users only
the required permissions on top of the Viewer role. However, in some scenarios
it may be desirable to make alternate roles which completely supersede the
provided ones.

This carries some advantages:

- Allows role naming and structure to more directly match an organizations'
- May allow closer adherence to the
  [Principle of Least Privilege](https://en.wikipedia.org/wiki/Principle_of_least_privilege)
  where the existing roles are overly permissive for most users

But also some disadvantages:

* The alternate roles have to be manually maintained over time - including
  across farmOS version upgrades which may imply permission changes for all
  features to work or continue working
* All the permissions for the alternate roles have to be manually configured
  which increases the likelihood of human error in that configuration granting
  potentially dangerous permissions to some users
