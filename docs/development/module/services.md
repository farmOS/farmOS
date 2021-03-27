# Services

farmOS provides some [services](https://symfony.com/doc/current/service_container.html)
that encapsulate common logic like querying logs and getting an asset's current
location. Some of these services are documented here.

## Asset location service

**Service name**: `asset.location`

The asset location service provides methods that encapsulate the logic for
determining an asset's location and geometry.

**Methods**:

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

**Example usage**:

```php
// Get an asset's current geometry.
$geometry = \Drupal::service('asset.location')->getGeometry($asset);
```

## Asset inventory service

**Service name**: `asset.inventory`

The asset inventory service provides methods that encapsulate the logic for
determining an asset's inventory.

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

## Group membership service

**Service name**: `group.membership`

The group membership service provides methods that encapsulate the logic for
determining an asset's group membership. This is provided by the optional Group
Asset module, and will only be available if that module is installed.

**Methods**:

`hasGroup()` - Check if an asset is a member of a group. Returns a boolean.

`getGroup()` - Get group assets that an asset is a member of. Returns an array
of asset entities.

`getGroupAssignmentLog()` - Find the latest group assignment log that
references an asset. Returns a log entity, or `NULL` if no logs were found.

**Example usage:**

```php
// Get the groups that an asset is a member of.
$geometry = \Drupal::service('group.membership')->getGroup($asset);
```

## Log query service

**Service name**: `log.query`

The log query service is a helper service for building a standard log database
query. This is primarily used to query for the "latest" log of an asset.
The asset location and group membership services use this.

**Methods**:

`getQuery()` - Builds a log database query.

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
$query = \Drupal::service('log.query')->getQuery($options);
$query->condition('is_movement', TRUE);
$log_ids = $query->execute();

// Load the first log.
$log = \Drupal::service('entity_type.manager')->getStorage('log')->load(reset($log_ids));
```
