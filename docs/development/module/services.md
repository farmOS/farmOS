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

`hasLocation($asset, $timestamp = NULL)` - Check if an asset is located within other
location assets, optionally at a given timestamp (defaults to current time).
Returns a boolean.

`hasGeometry($asset, $timestamp = NULL)` - Check if an asset has geometry, optionally
at a given timestamp (defaults to current time). Returns a boolean.

`getLocation($asset, $timestamp = NULL)` - Get location assets that an asset is
located within, optionally at a given timestamp (defaults to current time).
Returns an array of asset entities.

`getGeometry($asset, $timestamp = NULL)` - Get an asset's geometry, optionally at a
given timestamp (defaults to current time). Returns a Well-Known Text string.

`getMovementLog($asset, $timestamp = NULL)` - Find the latest movement log that
references an asset, optionally at a given timestamp (defaults to current
time). Returns a log entity, or `NULL` if no logs were found.

`setIntrinsicGeometry($asset, $wkt)` - Set an asset's intrinsic geometry, given
a string in Well-Known Text format.

`getAssetsByLocation($locations, $timestamp = NULL)` - Get assets that are in
locations, optionally at a given timestamp (defaults to current time).

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

`getInventory($asset, $measure = '', $units = 0, $timestamp = NULL)` - Get
inventory summaries for an asset, optionally at a given timestamp (defaults
to current time). Returns an array of arrays with the following keys:
`measure`, `value`, `units`. This can be optionally filtered by `$measure`
(string) and `$units` (term ID).

**Example usage**:

```php
// Get summaries of all inventories for an asset.
$all_inventory = \Drupal::service('asset.inventory')->getInventory($asset);

// Get the current inventory for a given measure (string) and units (term id).
$gallons_of_fertilizer = \Drupal::service('asset.inventory')->getInventory($asset, 'volume', 123);
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
    - `decimal` - Decimal number with fixed precision. Additional options:
        - `precision` (optional) - Total number of digits (including after the
          decimal point). Defaults to 10.
        - `scale` (optional) - Number digits to the right of the decimal point.
          Defaults to 2.
    - `email` - Email field.
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
    - `integer` - Integer number. Additional options:
        - `size` (optional) - The integer database column size (`tiny`,
          `small`, `medium`, `normal`, or `big`). Defaults to `normal`.
        - `min` (optional) - The minimum value.
        - `max` (optional) - The maximum value.
    - `list_string` - Select list with allowed values. Additional options:
        - `allowed_values` - An associative array of allowed values.
        - `allowed_values_function` - The name of a function that returns an
          associative array of allowed values.
    - `string` - Unformatted text field of fixed length. Additional options:
        - 'max_length' - Maximum length. Defaults to 255.
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
[FarmFieldFactory](https://github.com/farmOS/farmOS/blob/3.x/modules/core/field/src/FarmFieldFactory.php)
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

`hasGroup($asset, $timestamp = NULL)` - Check if an asset is a member of a group,
optionally at a given timestamp (defaults to current time). Returns a boolean.

`getGroup($asset, $timestamp = NULL)` - Get group assets that an asset is a member
of, optionally at a given timestamp (defaults to current time). Returns an
array of asset entities.

`getGroupAssignmentLog($asset, $timestamp = NULL)` - Find the latest group
assignment log that references an asset, optionally at a given timestamp
(defaults to current time). Returns a log entity, or `NULL` if no logs were
found.

`getGroupMembers($groups, $recurse = TRUE, $timestamp = NULL)` - Get assets that
are members of groups, optionally recursing into child groups, and optionally
at a given timestamp (defaults to current time).

**Example usage:**

```php
// Get the groups that an asset is a member of.
$groups = \Drupal::service('group.membership')->getGroup($asset);
```
