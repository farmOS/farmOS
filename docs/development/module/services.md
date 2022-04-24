# Services

farmOS provides some [services](https://symfony.com/doc/current/service_container.html)
that encapsulate common logic like querying logs and getting an asset's current
location. Some of these services are documented here.

## Asset location service

**Service name**: `asset.location`

The asset location service provides methods that encapsulate the logic for
determining an asset's location and geometry.

Note that these methods do not perform access checking on any of the assets or
logs used to determine location. It is up to downstream code to ensure access
controls are respected.

**Methods**:

`isLocation($asset)` - Check if an asset is a location. Returns a boolean.

`isFixed($asset)` - Check if an asset is fixed. Returns a boolean.

`hasLocation($asset)` - Check if an asset is located within other location
assets. Returns a boolean.

`hasGeometry($asset)` - Check if an asset has geometry. Returns a boolean.

`getLocation($asset)` - Get location assets that an asset is located within.
Returns an array of asset entities.

`getGeometry($asset)` - Get an asset's geometry. Returns a Well-Known Text
string.

`getMovementLog($asset)` - Find the latest movement log that references an
asset. Returns a log entity, or `NULL` if no logs were found.

`setIntrinsicGeometry($asset, $wkt)` - Set an asset's intrinsic geometry, given
a string in Well-Known Text format.

`getAssetsByLocation($locations)` - Get assets that are in locations.

**Example usage**:

```php
// Get an asset's current geometry.
$geometry = \Drupal::service('asset.location')->getGeometry($asset);
```

## Asset inventory service

**Service name**: `asset.inventory`

The asset inventory service provides methods that encapsulate the logic for
determining an asset's inventory.

Note that these methods do not perform access checking on any of the assets or
logs used to determine inventory. It is up to downstream code to ensure access
controls are respected.

**Methods**:

`getInventory($asset, $measure = '', $units = '')` - Get inventory summaries
for an asset. Returns an array of arrays with the following keys: `measure`,
`value`, `units`. This can be optionally filtered by `$measure` and `$units`.

**Example usage**:

```php
// Get summaries of all inventories for an asset.
$all_inventory = \Drupal::service('asset.inventory')->getInventory($asset);

// Get the current inventory for a given measure and units.
$gallons_of_fertilizer = \Drupal::service('asset.inventory')->getInventory($asset, 'volume', 'gallons');
```

## Field factory service

**Service name**: `farm_field.factory`

The field factory service provides two methods to make the process of creating
Drupal entity base and bundle field definitions easier and more consistent in
farmOS. This is used by modules that add [fields](/development/module/fields)
to [entity types](/development/module/entities).

Base fields are added to *all* bundles of a given entity type (eg: all logs).
Bundle fields are only added to *specific* bundles (eg: only "Input" logs).

Using this service is optional. It simply generates instances of Drupal core's
`BaseFieldDefinition` class or the Entity API module's `BundleFieldDefinition`
class, with farmOS-specific opinions to help enforce some consistency among
farmOS core and contrib modules. You can create instances of these field
definition classes directly instead of using the farmOS field factory service.
Or you can take the object produced by the service and customize it further
using standard Drupal field definition methods. This service is provided only
as a shortcut.

For more information on Drupal core's field definition API, see
[Drupal FieldTypes, FieldWidgets and FieldFormatters](https://www.drupal.org/docs/drupal-apis/entity-api/fieldtypes-fieldwidgets-and-fieldformatters)

**Methods**:

`baseFieldDefinition($options)` - Generates a base field definition, given an
array of options (see below).

`bundleFieldDefinition($options)` - Generates a bundle field definition, given
an array of options (see below).

**Options**:

Both methods expect an array of field definition options. These include:

- `type` (required) - The field data type. Each type may require additional
  options. Supported types include:
    - `boolean` - True/false checkbox.
    - `entity_reference` - Reference other entities. Additional options:
        - `target_type` (required) - The entity type to reference (eg: `asset`,
          `log`, `plan`)
        - `target_bundle` (optional) - The allowed target bundle. For example,
          a `target_type` of `asset` and a `target_bundle` of `animal` would
          limit references to animal assets.
        - `auto_create` (optional) Only used when `target_type` is set to
          `taxonomy_term`. If `auto_create` is set, term references will be
          created automatically if the term does not exist.
    - `file` - File upload.
    - `fraction` - High-precision decimal number storage.
    - `geofield` - Geometry on a map.
    - `image` - Image upload.
    - `list_string` - Select list with allowed values. Additional options:
        - `allowed_values` - An associative array of allowed values.
        - `allowed_values_function` - The name of a function that returns an
          associative array of allowed values.
    - `string_long` - Unformatted text field of unlimited length.
    - `text_long` - Formatted text field of unlimited length.
    - `timestamp` - Date and time.
- `label` - The field label.
- `description` - The field description.
- `required` - Whether the field is required.
- `multiple` - Whether the field should allow multiple values. Defaults to
  `FALSE`.
- `cardinality` - How many values are allowed (eg: `1` for single value
  fields, `-1` for unlimited values). This is an alternative to `multiple`,
  and will take precedence if it is set. Defaults to `1`.

Other options are available for more advanced use-cases. Refer to the
[FarmFieldFactory](https://github.com/farmOS/farmOS/blob/2.x/modules/core/field/src/FarmFieldFactory.php)
class to understand how they work.

For more information and example code, see [Adding fields](/development/module/fields).

## Group membership service

**Service name**: `group.membership`

The group membership service provides methods that encapsulate the logic for
determining an asset's group membership. This is provided by the optional Group
Asset module, and will only be available if that module is installed.

Note that these methods do not perform access checking on any of the assets or
logs used to determine group membership. It is up to downstream code to ensure
access controls are respected.

**Methods**:

`hasGroup($asset)` - Check if an asset is a member of a group. Returns a
boolean.

`getGroup($asset)` - Get group assets that an asset is a member of. Returns an
array of asset entities.

`getGroupAssignmentLog($asset)` - Find the latest group assignment log that
references an asset. Returns a log entity, or `NULL` if no logs were found.

`getGroupMembers($groups, $recurse)` - Get assets that are members of groups,
optionally recursing into child groups.

**Example usage:**

```php
// Get the groups that an asset is a member of.
$groups = \Drupal::service('group.membership')->getGroup($asset);
```

## Log query service

**Service name**: `farm.log_query`

The log query service is a helper service for building a standard log database
query. This is primarily used to query for the "latest" log of an asset.
The asset location and group membership services use this.

Note that you must set specify whether or not you want access checking to be
performed on the queried logs by running the `accessCheck()` method on the
query object that is returned. This will determine whether or not logs that
the current user does not have access to will be filtered out. If you are
trying to find the "latest" log of an asset for a particular purpose, filtering
out logs can cause inconsistent results, so typically `accessCheck(FALSE)` is
necessary. It is the responsibility of the code that uses this service to
understand the security implications of the data this returns, and perform
additional access checking if necessary.

**Methods**:

`getQuery($options)` - Builds a log database query.

The query will be sorted by log `timestamp` and `id`, descending.

It accepts a keyed array of options:

- `type` (string) - Filter by log type.
- `timestamp` (Unix timestamp) - Filter to logs by timestamp. Only logs with
  a timestamp less than or equal to this will be included.
- `status` (string) - Filter by log status.
- `asset` (asset entity) - Filter to logs that reference a particular asset.
- `limit` (int) - Only include this many results.

**Example usage**:

```php
// Find the latest "movement" log for an asset.
$options = [
  'asset' => $asset,
  'timestamp' => \Drupal::time()->getCurrentTime(),
  'status' => 'done',
  'limit' => 1,
];
$query = \Drupal::service('farm.log_query')->getQuery($options);
$query->condition('is_movement', TRUE);
$query->accessCheck(FALSE);
$log_ids = $query->execute();

// Load the first log.
$log = \Drupal::service('entity_type.manager')->getStorage('log')->load(reset($log_ids));
```
