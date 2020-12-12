# Entity types

Assets, logs, plans, taxonomy terms, users, etc are all types of "entities" in
farmOS/Drupal terminology. Entities can have sub-types called "bundles", which
represent "bundles of fields". Some fields may be common across all bundles of
a given entity type, and some fields may be bundle-specific.

## Adding asset, log, and plan types

Asset types, log types, and plan types can be provided by adding two files to a
module:

1. An entity type config file (YAML), and:
2. A bundle plugin class (PHP).

For example, the "Activity" log type is provided as follows:

`config/install/log.type.activity.yml`:

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - farm_activity
id: activity
label: Activity
description: ''
name_pattern: 'Activity log [log:id]'
workflow: log_default
new_revision: true
```

`src/Plugin/Log/LogType/Activity.php`:

```php
<?php

namespace Drupal\farm_activity\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the activity log type.
 *
 * @LogType(
 *   id = "activity",
 *   label = @Translation("Activity"),
 * )
 */
class Activity extends FarmLogType {

}
```

## Bundle fields

Bundles can declare field definitions in their plugin class via the
`buildFieldDefinitions()` method.

A `farm_field.factory` helper service is provided to make this easier.

The Equipment asset type does this to add "Manufacturer", "Model", and
"Serial number" fields:

```php
  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'manufacturer' => [
        'type' => 'string',
        'label' => $this->t('Manufacturer'),
        'weight' => [
          'form' => -20,
          'view' => -50,
        ],
      ],
      'model' => [
        'type' => 'string',
        'label' => $this->t('Model'),
        'weight' => [
          'form' => -15,
          'view' => -40,
        ],
      ],
      'serial_number' => [
        'type' => 'string',
        'label' => $this->t('Serial number'),
        'weight' => [
          'form' => -10,
          'view' => -30,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($info);
    }
    return $fields;
  }
```

For more information, see [Adding fields](/development/module/fields).
