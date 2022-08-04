# Maps

farmOS includes features for rendering and manipulating geometry data in
map-based UIs.

It uses [farmOS-map](https://github.com/farmOS/farmOS-map), which is based on
the open-source [OpenLayers](https://openlayers.org/) project. This includes
tools for drawing and editing geometries, adding imagery and vector layers, and
a framework for writing custom behaviors.

farmOS-map is maintained by the farmOS community as a standalone library for
common agricultural mapping needs. It is designed to be reusable in any
application with similar needs. It is not specific to or dependent on farmOS
itself. Rather, farmOS includes it as a dependency, and provides some helpful
wrappers for using it inside modules. This page describes how to use farmOS-map
in farmOS modules.

For more information about the farmOS-map library itself and what it provides,
refer to the farmOS-map documentation on GitHub:

[github.com/farmOS/farmOS-map](https://github.com/farmOS/farmOS-map)

## Render element

Maps can be embedded in pages as a `farm_map` type render element.

```php
$build['mymap'] = [
  '#type' => 'farm_map',
  '#map_type' => 'default',
  '#map_settings' => [
    'mysetting' => 'myvalue',
  ],
  '#behaviors' => [
    'mybehavior',
  ],
];
```

**Properties:**

- `#map_type` (optional) - See [Map types](#map-types). Defaults to `default`.
- `#map_settings` (optional) - An array of map settings, which will be passed
  into the map instance's client-side JavaScript object, so they are available
  in behavior JavaScript.
- `#behaviors` (optional) - See [Behaviors](#behaviors). Defaults to `[]` (but
  behaviors may also be added by map types and render events).

## Form element

Editable maps can be embedded in forms with a `farm_map_input` type element.
These maps will have the drawing/editing controls enabled, allowing geometries
to be added/edited/deleted directly in the map. A default value can be used to
pre-populate the map with a geometry. A text field can be optionally displayed
beneath the map to show the raw geometry data (auto-updates during editing).

**Example:**

```php
$form['mymap'] = [
  '#type' => 'farm_map_input',
  '#title' => t('My Geometry'),
  '#map_type' => 'default',
  '#map_settings' => [
    'mysetting' => 'myvalue',
  ],
  '#behaviors' => [
    'mybehavior',
  ],
  '#display_raw_geometry' => TRUE,
  '#default_value' => 'POINT(-45.967095060886315 32.77503850904169)',
];
```

**Properties:**

- `#map_type` (same as render element, above)
- `#map_settings` (same as render element, above)
- `#behaviors` (same as render element, above)
- `#display_raw_geometry` (optional) - Whether to show a text field below the
  map with the raw geometry value in Well-Known Text (WKT) format. Defaults to
  `FALSE`.
- `#default_value` (optional) - The default geometry value to display in the
  map initially, in Well-Known Text (WKT) format. This geometry will be
  editable in the map unless `#disabled` is `TRUE`.

## Map types

farmOS modules can optionally define "map types", which are then referenced in
the `#map_type` property of the render and form elements.

**This is optional and in most cases the `default` map type is sufficient.**

Map types are used to define reusable map configurations with common
[behaviors](#behaviors). They can be targeted by [render event](#render-events)
subscribers to add/modify behavior in certain contexts.

Map types are represented as Drupal config entities, installed via modules,
just like asset types, log types, flags, etc.

A very simple example of a custom map type definition looks like this:

`my_module/config/install/farm_map.map_type.mymaptype.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - my_module
id: mymaptype
label: My Map Type
description: "My module's custom map type."
behaviors: {  }
options: {  }
```

**Properties**

- `id` - A unique ID for the map type. This will be referenced in `#map_type`.
- `label` - A human-readable label for the map type.
- `description` - A human-readable description for the map type.
- `behaviors` - A list of [behaviors](#behaviors) to attach to maps of this
  type by default.
- `options` - Default options that will be merged with `#map_settings` and
  passed into `farmOS.map.create()`. See:
  [github.com/farmOS/farmOS-map#creating-a-map](https://github.com/farmOS/farmOS-map#creating-a-map)

## Behaviors

The farmOS-map library uses the concept of "behaviors" to encapsulate common
and reusable sets of map behavior logic into JavaScript objects that can be
"attached" to map instances.

Behaviors can be used to add layers to a map, add new buttons/controls, enable
OpenLayers interactions, connect maps with other elements of a page like forms,
etc.

For general information about farmOS-map behaviors, see:
[github.com/farmOS/farmOS-map#adding-behaviors](https://github.com/farmOS/farmOS-map#adding-behaviors)

Some behaviors that farmOS provides include:

- `wkt` - Adds a vector layer to the map based on a Well-Known Text (WKT)
  string. Edit controls can be optionally enabled to allow drawing, modifying,
  moving, and deleting geometries within the map. This behavior is enabled
  automatically in the `farm_map_input` form element, and when `wkt` is
  included in `#map_settings.`
- `input` - Listens for changes to geometries in the map and copies them to a
  form input (`textfield` or `hidden`) to be saved/manipulated server-side.
  This behavior is enabled automatically in the `farm_map_input` form element.
- `popup` - Adds a popup interaction to the map, which appears when a geometry
  feature is clicked.
- `asset_type_layers` - Adds asset geometry vector and cluster layers to a
  map. This behavior is responsible for adding the "Locations" layers on the
  farmOS dashboard map, the "Assets" and "Asset counts" layers to asset maps,
  automatically zooming to visible geometries, and adding asset details to
  popups when a geometry is clicked (depends on the `popup` behavior).

### Providing behaviors

Modules can provide their own behaviors with a couple of additional files.

The behavior itself is represented as a Drupal config entity, which gets
installed as a YML config file during module installation.

For example (replace `my_module` with the module name, and `mybehavior` with
the behavior name):

`my_module/config/install/farm_map.behavior.mybehavior.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - my_module
id: mybehavior
label: My Behavior
description: 'Adds my custom behavior logic.'
library: 'my_module/behavior_mybehavior'
settings: { }
```

The module must declare the behavior JavaScript file as a "library" so that
it can be included in the page(s) that need it.

For example (replace `my_module` with the module name, and `mybehavior` with
the behavior name):

`my_module/my_module.libraries.yml`

```yaml
behavior_mybehavior:
  js:
    js/farmOS.map.behaviors.mybehavior.js: { }
  dependencies:
    - farm_map/farm_map
```

Finally, the behavior JavaScript file should have a path and filename that
matches the library definition.

For example (replace `my_module` with the module name, and `mybehavior` with
the behavior name):

`my_module/js/farmOS.map.behaviors.mybehavior.js`

```js
(function () {
  farmOS.map.behaviors.mybehavior = {
    attach: function (instance) {

      // My custom behavior logic.
    }
  };
}());
```

The `instance` object represents the farmOS-map instance, and includes helper
methods for common needs (eg: `instance.addLayer()`), as well as direct access
to the OpenLayers map object at `instance.map`.

For more information see:
[github.com/farmOS/farmOS-map](https://github.com/farmOS/farmOS-map)

### Attaching behaviors

Behaviors can be "attached" (enabled) in a map in a few different ways:

- [Map types](#map-types) can include a list of default `behaviors`.
- The `#behaviors` property of the `farm_map` [render element](#render-element)
  and `farm_map_input` [form element](#form-element) can add specific behaviors
  to individual elements.
- A [render event](#render-events) subscriber can use the
  `$event->addBehavior()` method.

In all cases the behavior's `id` (as defined it its config entity YML) is used.

### Behavior settings

Some behaviors may require additional settings based on their context. Best
practice is to include these in the map settings so that they are available in
the behavior JavaScript in the following way:

`const settings = instance.farmMapSettings.behaviors.mybehavior;`

This can be accomplished in different ways, depending on how the behavior is
being attached to the map.

[Map types](#map-types) can add behavior settings to their `options` property.
For example:

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - my_module
id: mymaptype
label: My Map Type
description: "My module's custom map type."
behaviors:
  - mybehavior
options:
  behaviors:
    mybehavior:
      mysetting: True
```

Maps added as [render](#render-element) or [form](#form-element) elements can
add behavior settings in their `#map_settings` property. For example:

```php
$build['mymap'] = [
  '#type' => 'farm_map',
  '#map_settings' => [
    'behaviors' => [
      'mybehavior' => [
        'mysetting' => TRUE,
      ],
    ],
  ],
  '#behaviors' => [
    'mybehavior',
  ],
];
```

Behaviors that are added via [render event](#render-events) subscribers can add
settings at the same time:

```php
$event->addBehavior('mybehavior', ['mysetting' => TRUE]);
```

All of the above approaches will make the settings available in the behavior
JavaScript in the same place.

## Render events

farmOS will trigger an event when a map is rendered. Modules can set up an
event subscriber to perform additional logic at that time, such as adding
behaviors.

For example, to add a behavior to all maps in farmOS, add the following two
files (replace `my_module` with the module name, and `mybehavior` with the
behavior name):

`my_module/my_module.services.yml`

```yaml
services:
  my_module_map_render_event_subscriber:
    class: Drupal\my_module\EventSubscriber\MapRenderEventSubscriber
    tags:
      - { name: 'event_subscriber' }
```

`my_module/src/EventSubscriber/MapRenderEventSubscriber`

```php
<?php

namespace Drupal\my_module\EventSubscriber;

use Drupal\farm_map\Event\MapRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the MapRenderEvent.
 */
class MapRenderEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MapRenderEvent::EVENT_NAME => 'onMapRender',
    ];
  }

  /**
   * React to the MapRenderEvent.
   *
   * @param \Drupal\farm_map\Event\MapRenderEvent $event
   *   The MapRenderEvent.
   */
  public function onMapRender(MapRenderEvent $event) {
    $event->addBehavior('mybehavior');
  }

}
```
