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

A `farm_field.factory` helper service is provided to make this easier.

To get started, place the following in the `[modulename].module` file:
```php
<?php

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Implements hook_entity_base_field_info().
 */
function mymodule_entity_base_field_info(EntityTypeInterface $entity_type) {                    // <-- Replace 'mymodule' with the module name.
  $fields = [];

  // Add a new string field to Log entities.
  if ($entity_type->id() == 'log') {                                                            // <-- Specifies the entity type to apply to.
    $options = [                                                                                // <-- Options for the new field. See Field options below.
      'type' => 'string',
      'label' => t('My new field'),
      'description' => t('My field description.'),
      'weight' => [
        'form' => 10,
        'view' => 10,
      ],
    ];
    $fields['myfield'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options); // <-- Replace 'myfield' with the internal name of the field.
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

The format for bundle field definitions is identical to base field definitions
(above), but the `bundleFieldDefinition()` method must be used instead of
`baseFieldDefinition()`.

To get started, place the following in the `[modulename].module` file:
```php
<?php

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Implements hook_farm_entity_bundle_field_info().
 */
function mymodule_farm_entity_bundle_field_info(EntityTypeInterface $entity_type, $bundle) {      // <-- Replace 'mymodule' with the module name.
  $fields = [];

  // Add a new string field to Input Logs.
  if ($entity_type->id() == 'log' && $bundle == 'input') {                                        // <-- Specifies the entity type and bundle to apply to.
    $options = [                                                                                  // <-- ptions for the new field. See Field options below.
      'type' => 'string',
      'label' => t('My new field'),
      'description' => t('My field description.'),
      'weight' => [
        'form' => 10,
        'view' => 10,
      ],
    ];
    $fields['myfield'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options); // <-- Replace 'myfield' with the internal name of the field.
  }

  return $fields;
}
```

## Field options
The following keys are available to use when specifying fields:

| Option | Description |
|:-:|:--|
| `'label'` | What the field should be called when displayed to the user. |
| `'description'` | A description of the field and how to use it. |
| `'computed'` | Whether the field is computed. |
| `'required'` | Specify this to force the user to enter a value into this field. |
| `'revisionable'` | Make the field revisionable, unless told otherwise. |
| `'cardinality'` | Set cardinality, if specified. If neither `'cardinality'` or `'multiple'` is specified, this defaults to 1. |
| `'multiple'` | If `'cardinality'` is not specified and `'multiple'` is, set the cardinality to unlimited. |
| `'translatable'` | Only makes the field translatable if it is specified |
| `'default_value_callback'` | Sets the default value callback, if specified |
| `'type'` | The datatype of the field, represented as one of the following strings: <ul><li>[`'boolean'`](#boolean)</li><li>[`'entity_reference'`](#entity-reference)</li><li>[`'entity_reference_revisions'`](#entity-reference-revisions)</li><li>[`'file'`](#file-and-image)</li><li>[`'image'`](#file-and-image)</li><li>[`'fraction'`](#fraction)</li><li>[`'geofield'`](#geofield)</li><li>[`'id_tag'`](#id-tag)</li><li>[`'inventory'`](#inventory)</li><li>[`'list_string'`](#list-string)</li><li>[`'string'`](#string)</li><li>[`'string_long'`](#long-string)</li><li>[`'timestamp'`](#timestamp)</li></ul> Click one of the above or scroll down for options relating to each specific type.  |
| `'hidden'` | Hide the field in form and view displays, if specified. The hidden option can either be set to `true`, which will hide it in both form and view displays, or it can be set to `'form'` or `'view'`, which will only hide it in the form or view display. |
| `'form_display_options'` & `'view_display_options'` | Override form and view display options, if specified. |
| `'weight'` | Contains an associative array with the keys `'form'` and `'view'`, giving hints on whereabouts the field should appear. |

### Datatype specific options
#### Boolean
Currently there are no boolean-specific options.

#### Entity reference
| Option     | Description             |
|:----------:|:------------------------|
| `'target_type'` | Required. What sort of entity the reference should be targeting. This can be one of: <ul><li>`'asset'`</li><li>`'log'`</li><li>`'taxonomy_term'`</li><li>`'user'`</li><li>`'data_stream'`</li></ul> |
| `'target_bundle'` | Used when `'target_type'` is set to `'asset'` or `'taxonomy_term'`. Used to specify the bundle to look for. |
| `'auto_create'` | Used when `'target_type'` is set to `'taxonomy_term'`. If `'auto_create'` is set, term references will be created automatically if not already defined. |

#### Entity reference revisions
| Option     | Description             |
|:----------:|:------------------------|
| `'target_type'` | Required. This currently must be `'quantity'`. |

#### File and image
| Option | Description |
|:------:|:------------|
| `'file_directory'` | The directory that the image will be uploaded to in relation to the private file system path. If not set, this will be `farm/[date:custom:Y]-[date:custom:m]`. |

For images, the allowed file extensions are `png`, `gif`, `jpg` and `jpeg`.  
For other files, the allowed file extensions are `csv`, `doc`, `docx`, `gz`, `geojson`, `gpx`, `kml`, `kmz`, `logz`, `mp3`, `odp`, `ods`, `odt`, `ogg`, `pdf`, `ppt`, `pptx`, `tar`, `tif`, `tiff`, `txt`, `wav`, `xls`, `xlsx` and `zip`.

#### Fraction
Currently there are no fraction-specific options.

#### Geofield
Currently there are no geofield-specific options.

#### ID tag
Currently there are no ID tag-specific options.

#### Inventory
Currently there are no inventory-specific options.

#### List string
| Option | Description |
|:------:|:------------|
| `'allowed_values'` | Optionally specify allowed values |
| `'allowed_values_function'` | Optionally specify a function that returns the allowed values |

#### String
Maximum length of 255 characters. Currently there are no string-specific coptions.

#### Long string
This is for longer messages than the string type. Currently there are no long string-specific options.

#### Timestamp
Currently there are no long timestamp-specific options.

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

#### ID tag type

ID tag types are similar to Flags, in that they have an `id` and `label`. They
also have an additional `bundle` property, which allows them to be limited to
certain types of assets.

For example, an "Ear tag" type, provided by the "Animal asset" module, only
applies to "Animal" assets:

`animal/config/install/farm_flag.flag.ear_tag.yml`

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

If you want the tag type to apply to all assets, set `bundles: null`.
(or can it just be omitted?)
