# Fields

## Select options

Certain fields on assets and logs include a list of options to select from.
These include:

- **Flags** (on assets and logs)
    - Monitor (`monitor`)
    - Needs review (`needs_review`)
    - Priority (`priority`)
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
id: monitor
label: Monitor
```

The most important parts are the `id`, which is a unique machine name for
the flag, and `label`, which is the human readable/translatable label that
will be shown in the select field and other parts of the UI.

The `langcode` and `status` and `dependencies` are standard configuration
entity properties. By putting the module's name in "enforced modules" it will
ensure that the flag is removed when the module is uninstalled.

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

ID tag types are similar to Flags, in that they have an `id` and `label`, but
they also have an additional property: `bundle`. This allows the tag type to
be limited to certain types of assets.

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

If you want the tag type to apply to all assets, set `bundle: null`.
