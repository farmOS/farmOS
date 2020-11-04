# Data Stream

The data stream module provides a custom data stream entity for farmOS. Each
data stream entity represents a time-series stream of data provided by a
farmOS asset, such as Sensor or Equipment assets. Data streams can also specify
any assets which their data is describing, such as a Plant, Animal or
Area asset. Data streams are identified by a `UUID`, and have `private_key` and
`public` attributes to limit access to their data.

Further functionality is provided by data stream types. Each type can provide a
custom settings form, methods to retrieve and save data, methods for handling
API requests, and custom display options. A data stream type can save data
to the farmOS DB or request data from a third party API.

## Using data stream types

The data stream plugin, which defines additional behavior for the data stream
type, can be accessed from a data stream entity with the `getPlugin()` method:

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

### Listener data stream

The data stream module provides a `listener` data stream type. Listeners
receive data via the farmOS API and save data to the farmOS SQL database. Each
listener data stream represents a single "value"; a sensor that records
temperature and humidity would provide two data streams. Data can be accessed
via the API with the `private_key`, or by anyone if the data stream is set to
`public`. Listener data streams also provide simple ways to view data in a
table or graph, and export as CSV.

### Legacy listener data stream

The `farm_sensor_listener` module provides a `legacy_listener` data stream type
that is compatible with the Listener sensor type from farmOS 1.x. It is
similar to the `listener` type but has a few differences:

- Each data stream saves multiple values (eg: temperature and humidity are
  saved to the same data stream)
- A `public_key` attribute identifies the data stream instead of a `UUID`.
- It responds to the legacy API endpoint at `/farm/sensor/listener/{public_key}`
  (to match farmOS 1.x).

## Custom data stream types

Custom data stream types can be created to integrate with data stored outside
of farmOS (such as time-series databases or 3rd party APIs), provide advanced
views of data, and other custom behavior.

Two things are required to provide a custom data stream type:

- A config entity that defines a bundle of the `data_stream` entity.
- A data stream plugin that provides custom behavior for the data stream type.

The data stream bundle and plugin must both have the same `id`.

### Data stream bundle

A data stream bundle can be defined in a `data_stream.type.ID.yml` file
inside the `config/install` directory of a custom module. The following defines
the `listener` data stream bundle:

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - data_stream
id: listener
label: Listener
description: 'Listener stream'
```

With a bundle of the `data_stream` entity defined, additional fields can be
added to the bundle. Fields should be added to save any additional data
necessary to configure individual data streams of the custom type such as
: API keys, external ID, display options, etc.

### Data stream plugin

Data stream plugins are discovered using Annotation discovery in the `src
/plugin/DataStream` directory of a custom module. Plugins must implement the
`DataStreamPluginInterface`, and the `DataStreamPluginBase` class
can be used as starting point.

Plugins can optionally implement the `DataStreamStorageInterface` and the
`DataStreamApiInterface` to adhere to a common interface other data stream
types might use.

The following defines the `listener` data stream plugin class:

```php
<?php

namespace Drupal\data_stream\Plugin\DataStream;

use Drupal\data_stream\DataStreamApiInterface;
use Drupal\data_stream\DataStreamStorageInterface;
use Drupal\data_stream\DataStreamPluginBase;


/**
 * DataStream plugin that provides listener behavior.
 *
 * @DataStream(
 *   id = "listener",
 *   label = @Translation("Listener"),
 * )
 */
class ListenerDataStream extends DataStreamPluginBase implements DataStreamStorageInterface, DataStreamApiInterface {
  ...
}
```
