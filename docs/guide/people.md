# People

farmOS allows a farm to have multiple "users" accessing it, and each of those
users is assigned one or more "roles" to grant them different levels of
permission.

<div class="embed-responsive embed-responsive-16by9">
  <video class="embed-responsive-item" width="100%" controls>
    <source src="/demo/people.mp4" type="video/mp4">
  </video>
</div>

## Roles

Three roles are provided with farmOS:

### Farm Manager

Farm Managers have access to everything in farmOS. They can create areas, add
assets, record logs, and change configuration.

### Farm Worker

Farm Workers have most of the same permissions as Managers, but they cannot
change configuration.

### Farm Viewer

Farm Viewers are limited to viewing farmOS areas, assets, and logs - but they
cannot edit anything.

The Farm Viewer role is useful if you want to share your farm's activities with
someone, but you don't want to give them the ability to make changes.

For example, if you are applying for Organic certification in the United States,
you can create a user with the Farm Viewer role for your certifying agent, so
they can log into your farmOS and see your records.

## Advanced Role Customization

Permissions for the provided roles cannot be modified through the admin UI since
they are controlled by the Farm Access Roles module. This is not generally an
issue since the provided roles have been carefully tailored to work for most
applications. However if required, there are three options for customizing user
permissions:

### Additional Roles

The simplest way to create custom user permissions is to use a few additional
roles along with the provided ones. With this strategy, users are given the
minimum required permissions using the provided roles then granted any further
permissions via custom additional roles that are manually configured.

For example, suppose some users who have the Farm Worker role but not the Farm
Manager role need to be able to Configure farm reports - which they cannot do
with their current Farm Worker role. One option would be to make them all Farm
Managers. However, this could be confusing or risky if it doesn't match their
real-world role or trust level. Instead a new role called "Farm Report Manager"
(the name is arbitrary) can be created and given permission to Configure farm
reports. This new role can then be selectively given to just those users who
need the additional set of permissions.

### Alternate Roles

In some cases where significantly different permissions are required than
default provided roles, it may be preferable to disable the Farm Access Roles
module and create alternate roles manually - or through a custom module.

For example, suppose some users are responsible only for animals and other users
are responsible only for plantings. One option would be to make them all Farm
Workers or use the above strategy of additional roles to give those users only
the required permissions on top of the Farm Viewer role. However, in some
scenarios it may be desirable to make alternate roles which completely supercede
the provided ones.

This carries some advantages;

* Allows role naming and structure to more directly match an organizations'
* May allow closer adherance to the [Principle of Least Privilege][1] where the
  existing roles are overly permissive for most users

But also some disadvantages;

* The alternate roles have to be manually maintained over time - including
  across farmOS version upgrades which may imply permission changes for all
  features to work or continue working
* All the permissions for the alternate roles have to be manually configured
  which increases the likelihood of human error in that configuration granting
  potentially dangerous permissions to some users

### Farm Access Permission Hook

Another strategy involves using the provided roles, but leveraging the
`hook_farm_access_perms` hook from another module to modify the permissions of
those roles. The documentation for that is included in the farm_access module -
see [farm_access/farm_access.api.php][2].

[1]: https://en.wikipedia.org/wiki/Principle_of_least_privilege
[2]: https://github.com/farmOS/farmOS/blob/7.x-1.x/modules/farm/farm_access/farm_access.api.php
