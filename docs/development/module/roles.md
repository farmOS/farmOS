# Roles

Roles are groups of permissions that can be assigned to users to grant them
granular access to data and features in farmOS.

Module developers can define new roles, and specify which permissions they
should include. farmOS also builds on top of Drupal's role and permission
system to provide a concept of "Managed Roles".

## Managed Roles

The farmOS Access module provides methods to create user roles with permissions
that are managed for the purposes of farmOS. These roles cannot be modified
from the Admin Permissions UI. Instead, these roles allow permissions to be
provided by other modules that want to provide sensible defaults for common
farmOS roles.

### Creating a managed role

User roles are provided as Drupal Configuration Entities. Managed roles are
provided in the same way the only difference being that they include
additional third party settings the farmOS Access module uses to build
managed permissions. The `user.role.*.third_party.farm_acccess` schema
defines the structure of these settings.

- `access`: An optional array of default access permissions.
    - `config`: Boolean that specifies whether the role should have access to
      configuration. Only grant this to trusted roles.
    - `entity`: Access permissions relating to entities.
        - `view all`: Boolean that specifies the role should have access to view
          all bundles of all entity types.
        - `create all`: Boolean that specifies the role should have access to
          create all bundles of all entity types.
        - `update all`: Boolean that specifies the role should have access to
          update all bundles of all entity types.
        - `delete all`: Boolean that specifies the role should have access to
          delete all bundles of all entity types.
        - `type`: Access permissions for specific entity types.
            - `{entity_type}`: The id of the entity type. eg: `log`,`asset`,
              `taxonomy_term`, etc.
              - `{operation}`: The operation to grant bundles of this entity
                type. Eg: `create`, `view any`, `view own`, `delete any`,
                `delete own`, etc.
                - `{bundle}`: The id of the entity type bundle or `all` to
                  grant the operation permission to all bundles of the entity
                  type.

Settings used for the Manager role (full access to all entities + access to
configuration):

`user.role.farm_manager.yml`

```yaml
# (standard role config goes here)
third_party_settings:
  farm_role:
    access:
      config: true
      entity:
        view all: true
        create all: true
        update all: true
        delete all: true
```

Example settings to define a "Harvester" role with these limitations:

* View all log entities.
* Only create harvest logs, update harvest logs, and delete own harvest logs.
* View all asset entities.
* Only update planting assets.
* View, edit and delete any taxonomy_term entity.

`user.role.farm_harvester.yml`

```yaml
# (standard role config goes here)
third_party_settings:
  farm_role:
    access:
      entity:
        view all: true
        type:
          log:
            create:
              - harvest
            update any:
              - harvest
            delete own:
              - harvest
          asset:
            update any:
              - planting
          taxonomy_term:
            edit:
              - all
            delete:
              - all
```

### Providing permissions for managed roles

Modules can define sensible permissions to any managed roles. These permissions
are provided by creating a `ManagedRolePermissions` plugin in the
`module.managed_role_permissions.yml` file. The following keys can be provided:

- `default_permissions`: A list of permissions that will be added to *all*
  managed roles.
- `config_permissions`: A list of permissions that will be added to managed
  roles that have access to configuration (`config: true`).
- `permission_callbacks`: A list of callbacks in controller notation that
  return an array of permissions to add to managed roles. Callbacks are
  provided a `Role` object so that permissions can be applied conditionally
  based on the managed role's settings.

As an example, the `farm_role` module provides the following permissions:

`farm_role.managed_role_permissions.yml`

```yaml
farm_role:
  default_permissions:
    - access content
    - access user profiles
    - change own username
  config_permissions:
    - access administration pages
    - access taxonomy overview
```

#### Permission callbacks

Example that adds permissions conditionally based on the role name and settings:

Plugin definition:

`my_module.managed_role_permissions.yml`

```yaml
my_module:
  permission_callbacks:
    - Drupal\my_module\CustomPermissions::permissions
```

Example implementation of a `permission_callback`:

`my_module/src/CustomPermissions.php`

```php
<?php

namespace Drupal\my_module;

use Drupal\user\RoleInterface;

/**
 * Example custom permission callback.
 */
class CustomPermissions {

  /**
   * Return an array of permission strings that will be added to the role.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The role to add permissions to.
   *
   * @return array
   *   An array of permission strings.
   */
  public function permissions(RoleInterface $role) {

    // Array of permissions to return.
    $perms = [];

    // Add permissions based on role name.
    if ($role->id() == 'farm_manager') {
      $perms = 'my manager permission';
    }

    // Get the farm_role third party settings from the Role entity.
    $access_settings = $role->getThirdPartySetting('farm_role', 'access');
    $entity_settings = $access_settings['entity'] ?: [];

    // Only add permissions if `update all` and `delete all` are true.
    if (!empty($entity_settings['update all'] && $entity_settings['delete all'])) {
      $perms[] = 'recover all permission';
    }

    // Return array of permissions.
    return $perms;
  }
}
```
