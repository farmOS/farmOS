# Fields

## Adding fields

A module may add additional fields to assets, logs, and other entity types in
farmOS.

The following documents how to add fields to existing entity types. See
[Entity types](/development/module/entities) to understand how to create new
asset, log, and plan types with custom fields on them.

### Base fields

If the field should be added to all bundles of a given entity type (eg: all log
types), then they should be added as "base fields" via
`hook_entity_base_field_info()`.

A `farm_field.factory` helper service is provided to make this easier. For more
information on how this works, see [Field factory service](/development/module/services/#field-factory-service).

To get started, place the following in the `[modulename].module` file:

```php
<?php

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Implements hook_entity_base_field_info().
 * NOTE: Replace 'mymodule' with the module name.
 */
function mymodule_entity_base_field_info(EntityTypeInterface $entity_type) {
  $fields = [];

  // 'log' specifies the entity type to apply to.
  if ($entity_type->id() == 'log') {
    // Options for the new field. See Field options below.
    $options = [
      'type' => 'string',
      'label' => t('My new field'),
      'description' => t('My field description.'),
      'weight' => [
        'form' => 10,
        'view' => 10,
      ],
    ];
    // NOTE: Replace 'myfield' with the internal name of the field.
    $fields['myfield'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);
  }

  return $fields;
}
```

### Bundle fields

If the field should only be added to a single bundle (eg: only "Input" logs),
then they should be added as "bundle fields" via
`hook_farm_entity_bundle_field_info()`&ast;

&ast; Note that this is a custom hook provided  by farmOS, which may be
deprecated in favor of a core Drupal hook in the future. See core issue:
[https://www.drupal.org/node/2346347](https://www.drupal.org/node/2346347)

A `farm_field.factory` helper service is provided to make this easier. For more
information on how this works, see [Field factory service](/development/module/services/#field-factory-service).

The format for bundle field definitions is identical to base field definitions
(above), but the `bundleFieldDefinition()` method must be used instead of
`baseFieldDefinition()`.

To get started, place the following in the `[modulename].module` file:

```php
<?php

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Implements hook_farm_entity_bundle_field_info().
 * NOTE: Replace 'mymodule' with the module name.
 */
function mymodule_farm_entity_bundle_field_info(EntityTypeInterface $entity_type, $bundle) {
  $fields = [];

  // Add a new string field to Input Logs. 'log' specifies the entity type and
  // 'input' specifies the bundle.
  if ($entity_type->id() == 'log' && $bundle == 'input') {
    // Options for the new field. See Field options below.
    $options = [
      'type' => 'string',
      'label' => t('My new field'),
      'description' => t('My field description.'),
      'weight' => [
        'form' => 10,
        'view' => 10,
      ],
    ];
    // NOTE: Replace 'myfield' with the internal name of the field.
    $fields['myfield'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  }

  return $fields;
}
```

## Select options

Certain fields on assets and logs include a list of options to select from.
These include:

- **Flags** (on assets, logs, and plans)
    - Monitor (`monitor`)
    - Needs review (`needs_review`)
    - Priority (`priority`)
- **Land types** (on Land assets)
    - Property (`property`)
    - Field (`field`)
    - Bed (`bed`)
    - Paddock (`paddock`)
    - Landmark (`landmark`)
    - Other (`other`)
- **Structure types** (on Structure assets)
    - Building (`building`)
    - Greenhouse (`greenhouse`)
- **Lab test type** (on Lab test logs)
    - Soil test (`soil`)
    - Water test (`water`)
- **ID tag type** (on assets)
    - Electronic ID (`eid`, on all assets)
    - Other (`other`, on all assets)
    - Brand (`brand`, on Animal assets)
    - Ear tag (`ear_tag`, on Animal assets)
    - Leg band (`leg_band`, on Animal assets)
    - Tattoo (`tattoo`, on Animal assets)

These options are provided as configuration entities by farmOS modules in the
form of YAML files.

Existing options can be overridden or removed by editing/deleting the entities
in the active configuration of the site. (**Warning** changing core types runs
the risk of conflicting with future farmOS updates).

Note that the file name is important and must follow a specific pattern. This
is generally in the form `[select_module_name].[select_field].[id].yml`. See
the examples for more info.

### Examples:

#### Flag

An "Organic" flag can be provided by a module named `my_module` by creating a
file called `farm_flag.flag.organic.yml` in `my_module/config/install`:

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - my_module
id: organic
label: Organic
entity_types: null
```

Note that the file name is in the form `farm_flag.flag.[id].yml`.

The most important parts are the `id`, which is a unique machine name for
the flag, `label`, which is the human readable/translatable label that will be
shown in the select field and other parts of the UI, and `entity_types`, which
can optionally specify the entity types and bundles that this flag applies to.

The `langcode` and `status` and `dependencies` are standard configuration
entity properties. By putting the module's name in "enforced modules" it will
ensure that the flag is removed when the module is uninstalled.

Flags can be limited to certain entity types and bundles via an optional
`entity_types` property. This accepts a set of entity types with arrays of
bundles that the flag applies to (or `all` to apply to all bundles). For
example, to create a flag that only applies to Animal assets:

```yaml
entity_types:
  asset:
    - animal
```

To create a flag that applies to all asset types and log types, but not plans,
specify `all` for the `asset` and `log` bundles, but omit the `plan` entity
type:

```yaml
entity_types:
  asset:
    - all
  log:
    - all
```

#### Land type

The "Land" module in farmOS provides a "Field" type like this:

`land/config/install/farm_land.land_type.field.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - farm_land
id: field
label: Field
```

Note that the file name is in the form `farm_land.land_type.[id].yml`.

#### Structure type

The "Structure" module in farmOS provides a "Building" type like this:

`structure/config/install/farm_structure.structure_type.building.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - farm_structure
id: building
label: Building
```

Note that the file name is in the form `farm_structure.structure_type.[id].yml`.

#### Lab test type

The "Lab test" module in farmOS provides a "Soil test" type like this:

`lab_test/config/install/farm_lab_test.lab_test_type.soil.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - farm_lab_test
id: soil
label: Soil test
```

Note that the file name is in the form `farm_lab_test.lab_test_type.[id].yml`.

#### ID tag type

ID tag types are similar to Flags, in that they have an `id` and `label`. They
also have an additional `bundle` property, which allows them to be limited to
certain types of assets.

For example, an "Ear tag" type, provided by the "Animal asset" module, only
applies to "Animal" assets:

`animal/config/install/farm_id_tag.id_tag.ear_tag.yml`

```yaml
langcode: en
status: true
dependencies:
  enforced:
    module:
      - farm_animal
      - farm_id_tag
id: ear_tag
label: Ear tag
bundles:
  - animal
```

Note that the file name is in the form `farm_flag.flag.ear_tag.[id].yml`.

If you want the tag type to apply to all assets, set `bundles: null`.
(or can it just be omitted?)
