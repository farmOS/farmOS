# Data Stream

The data stream module provides a custom data stream entity for farmOS. Each
data stream entity represents a time-series stream of data provided by a
farmOS asset, such as Sensor or Equipment assets. Data streams can also specify
any assets which their data is describing, such as a Plant, Animal or
Land assets. Data streams are identified by a `UUID`, and have `private_key` and
`public` fields to limit access to their data.

Further functionality is provided by data stream types. Each type can provide a
custom settings form, methods to retrieve and save data, methods for handling
API requests, and custom display options. This allows custom data stream types
to save data to the farmOS DB or request data from a third party API.

## Using data stream types

The data stream bundle plugin class can be accessed from a data stream entity
with the `getPlugin()` method:

```php
// Load the data stream.
$data_streams = $this->entityTypeManager()->getStorage('data_stream')->loadByProperties([
  'uuid' => $uuid,
]);

// Bail if UUID is not found.
if (empty($data_streams)) {
  return;
}

/** @var \Drupal\data_stream\Entity\DataStreamInterface $data_stream */
$data_stream = reset($data_streams);

// Get the data stream plugin.
$plugin = $data_stream->getPlugin();

// Access methods on the plugin.
// Available methods will vary depending on type.
$data = $plugin->storageGet();
```

## Core data stream types

### Basic data stream

The data stream module provides a `basic` data stream type. Basic data streams
receive data via the farmOS API and save data to the farmOS SQL database. Each
basic data stream represents a single "value"; a sensor that records
temperature and humidity would provide two data streams. Data can be accessed
via the API with the `private_key`, or by anyone if the data stream is set to
`public`. Basic data streams also provide simple ways to view data in a
table or graph, and export as CSV.

### Listener (Legacy) data stream

The `farm_sensor_listener` module provides a `legacy_listener` data stream type
that is compatible with the Listener sensor type from farmOS 1.x. It is
similar to the `basic` type but has a few differences:

- Each data stream saves multiple values (eg: temperature and humidity are
  saved to the same data stream)
- A `public_key` attribute identifies the data stream instead of a `UUID`.
- It responds to the legacy API endpoint at `/farm/sensor/listener/{public_key}`
  (to match farmOS 1.x).

## Custom data stream types

Custom data stream types can be created to integrate with data stored outside
of farmOS (such as time-series databases or 3rd party APIs), provide advanced
views of data, and other custom behavior.

Data stream types can be provided by adding two files to a module:

1. An entity type config file (YAML), and:
2. A bundle plugin class (PHP).

For more information see [Entity Types](/development/module/entities).

### Data stream bundle plugin

Data stream bundle plugins must implement the `DataStreamTypeInterface`. The
`DataStreamTypeBase` class can be used as starting point.

Plugins can optionally implement the `DataStreamStorageInterface` and the
`DataStreamApiInterface` to adhere to a common interface other data stream
types might use.

See the "Basic" data stream bundle plugin as an example
(`Drupal\data_stream\Plugin\DataStream\DataStreamType\Basic`).
