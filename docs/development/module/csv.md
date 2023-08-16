# CSV importers

[CSV](https://en.wikipedia.org/wiki/Comma-separated_values) files are an easy
way to import data into farmOS.

The farmOS Import CSV module (`farm_import_csv`) provides a framework for
building CSV importers using Drupal's
[Migrate API](https://www.drupal.org/docs/drupal-apis/migrate-api).

The module uses this framework to provide "default" CSV importers for each
asset, log, and taxonomy term type. These are useful if you can fit your data
into them, but in some cases a more customized CSV template and/or import logic
might be necessary.

## Migration YML

Modules can provide their own CSV importers by adding a single YML file to
their `config/install` directory, which will add the importer when the module
is installed.

The YML file defines all the configuration necessary for the importer,
using the Drupal [Migrate Plus](https://drupal.org/project/migrate_plus)
module's `migration` configuration entity type.

The basic template for a CSV importer is as follows (replace all
`{{ VARIABLE }}` sections with your specific configuration):

```yaml
langcode: en
status: true
dependencies: {  }
id: {{ UNIQUE_ID }}
label: '{{ LABEL }}'
migration_group: farm_import_csv
migration_tags: []
source:
  plugin: csv_file
destination:
  plugin: 'entity:{{ ENTITY_TYPE }}'
process:
  {{ MAPPING_CONFIG }}
migration_dependencies: {  }
third_party_settings:
  farm_import_csv:
    access:
      permissions:
        - {{ PERMISSION_STRING }}
    columns:
      {{ COLUMN_DESCRIPTIONS }}
```

- `{{ UNIQUE_ID }}` must be a unique machine-name for the importer, consisting
  of only alphanumeric characters and underscores.
- `{{ LABEL }}` will be the name of the importer shown in the farmOS UI.
- `{{ ENTITY_TYPE }}` should be `asset`, `log`, or `taxonomy_term`.
- `{{ MAPPING_CONFIG }}` is where all the Drupal Migrate API's `process`
  pipeline configuration is defined. This is responsible for mapping CSV column
  names to entity fields (or additional processing).
  See [Process pipeline](#process-pipeline) below for more information.
- `{{ PERMISSION_STRING }}` should be a Drupal permission that the user must
  have in order to use the importer. Multiple permissions can be included on
  separate lines.
- `{{ COLUMN_DESCRIPTIONS }}` should be an array of items with `name` and
  `description` keys to describe each CSV column.

### Example

Here is an example of an importer for "egg harvests", which will import a CSV
with columns named `Date` and `Eggs`. It will create a harvest log named
"Collected [num] egg(s)" for each row with the number of eggs saved in a
standard `count` quantity:

`egg-harvests.csv`

```csv
Date,Eggs
2023-09-15,12
2023-09-16,14
2023-09-17,9
```

`config/install/migrate_plus.migration.egg_harvest.yml`

```yaml
langcode: en
status: true
dependencies: {  }
id: egg_harvest
label: 'Egg harvest importer'
migration_group: farm_import_csv
migration_tags: []
source:
  plugin: csv_file
  constants:
    UNIT: egg(s)
    LOG_NAME_PREFIX: Collected
destination:
  plugin: 'entity:log'
process:
  # Hard-code the bundle.
  type:
    plugin: default_value
    default_value: harvest
  # Parse the log timestamp with strtotime() from Date column.
  timestamp:
    plugin: callback
    callable: strtotime
    source: Date
  # Create or load "egg(s)" unit term.
  _unit:
    plugin: entity_generate
    entity_type: taxonomy_term
    value_key: name
    bundle_key: vid
    bundle: unit
    source: constants/UNIT
  # Create a quantity from the Eggs column.
  quantity:
    - plugin: skip_on_empty
      source: Eggs
      method: process
    - plugin: static_map
      map: { }
      default_value: [ [ ] ]
    - plugin: create_quantity
      default_values:
        type: standard
        measure: count
      values:
        value: Eggs
        units: '@_unit'
  # Auto-generate the log name.
  name:
    plugin: concat
    source:
      - constants/LOG_NAME_PREFIX
      - Eggs
      - constants/UNIT
    delimiter: ' '
  # Mark the log as done.
  status:
    plugin: default_value
    default_value: done
migration_dependencies: {  }
third_party_settings:
  farm_import_csv:
    access:
      permissions:
        - create harvest log
    columns:
      - name: Date
        description: Date of egg harvest.
      - name: Eggs
        description: Number of eggs harvested.
```

### Process pipeline

The `process` section of the YML is used to define a "process pipeline" for
mapping source data from CSV columns into properties of the destination entity.
Each `process` item declares one or more "process plugins" that can be chained
together to transform data before it is saved to the destination entity.

The simplest example is the `get` process plugin, which copies values from the
source to the destination without any modification. For example, the following
process pipeline will populate the log name from a CSV column called
`Log name`:

```yaml
...
process:
  name:
    - plugin: get
      source: Log name
...
```

There is also a shorthand syntax for the `get` plugin, which is even simpler:

```yaml
...
process:
  name: Log name
...
```

Chaining plugins together provides more advanced capabilities. For following
process pipeline will populate the log categories from a CSV column called
`Log categories`, using the `explode` plugin to split a comma-separated list
of categories into separate items, and the `term_lookup` plugin to look up
existing terms from the `log_category` vocabulary to reference:

```yaml
...
process:
  category:
    - plugin: explode
      delimiter: ,
      source: Log categories
    - plugin: term_lookup
      bundle: log_category
...
```

Only the first plugin in a process pipeline needs to define the `source` CSV
column name.

See [Resources](#resources) below for lists of available process plugins.

#### farmOS process plugins

In addition to the process plugins provided by Drupal core and the
[Migrate Plus](https://drupal.org/project/migrate_plus) module, farmOS also
provides some process plugins of its own.

##### asset_lookup

The `asset_lookup` plugin extends the `entity_lookup` plugin to make it easier
to populate asset reference fields on entities. It will attempt to look up an
asset using multiple properties, in the following order of precedence:

- UUID
- ID tag
- Name
- ID (primary key)

```yaml
...
process:
  equipment:
    - plugin: asset_lookup
      bundle: equipment
      source: Equipment used
...
```

The `bundle` property is optional, and will limit the allowed asset types. It
can be a single asset type, or an array of multiple types. If omitted, then
all asset types will be allowed.

This plugin assumes a single asset is being looked up. If a source CSV column
may have multiple comma-separate values, use an `explode` plugin before the
`asset_lookup`, and move the `source: Equipment used` to it, as demonstrated in
the example in [Process pipeline](#process-pipeline) above.

If the `source` CSV column contains any values, and any of the asset lookups
fail, the plugin will cause the whole row import to fail and an error will be
shown to the user.

The plugin will ignore case sensitivity, and will automatically trim whitespace
from the start and end of CSV source values.

##### term_lookup

The `term_lookup` plugin extends the `entity_lookup` plugin to make it easier
to populate taxonomy term reference fields on entities.

Example:

```yaml
process:
  animal_type:
    - plugin: term_lookup
      bundle: animal_type
      source: Animal type
```

The `bundle` property is required.

If the `source` CSV column contains any values, and any of the term lookups
fail, the plugin will cause the whole row import to fail and an error will be
shown to the user.

The plugin will ignore case sensitivity, and will automatically trim whitespace
from the start and end of CSV source values.

## Resources

A complete overview of all the options available with Drupal's Migrate API is
outside the scope of this documentation, but the following links are a good
place to learn more.

Also note that CSVs are just one type of data source for migrations. These
resources are not specific to CSV imports, but the same principles apply
generally.

- [Drupal Migrate API documentation](https://www.drupal.org/docs/drupal-apis/migrate-api)
- [Migrate API overview](https://www.drupal.org/docs/drupal-apis/migrate-api/migrate-api-overview)
- [Migrate process plugins overview](https://www.drupal.org/docs/8/api/migrate-api/migrate-process-plugins/migrate-process-overview).
- [31 days of Drupal migrations](https://understanddrupal.com/courses/31-days-of-migrations/)
- [List of core Migrate process plugins](https://www.drupal.org/docs/8/api/migrate-api/migrate-process-plugins/list-of-core-migrate-process-plugins)
- [List of process plugins provided by Migrate Plus](https://www.drupal.org/docs/8/api/migrate-api/migrate-process-plugins/list-of-process-plugins-provided-by-migrate-plus)
