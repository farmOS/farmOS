# API Changes

## 2.x vs 1.x

### Endpoints

farmOS 1.x used the [RESTful Web Services](https://drupal.org/project/restws)
module. This provided API endpoints for each entity type at
`/[entity_type].json`.

farmOS 2.x uses the new JSON:API module included with Drupal core, which
follows the [JSON:API](https://jsonapi.org/) specification for defining API
resources. The root endpoint is now `/api`.

Within the `/api` endpoint is a `links` object with information about all
the available resource types and their endpoints. Typically these follow a URL
pattern of `/[entity-type]/[bundle]`.

For example, to access a list of Activity logs:

- farmOS 1.x: `/log.json?type=farm_activity`
- farmOS 2.x: `/api/log/activity`

farmOS 2.x also provides [JSON Schema](https://json-schema.org/) information
about all available resources. The root endpoint for schema information is
`/api/schema`.

### IDs

farmOS 2.x assigns
[UUIDs](https://en.wikipedia.org/wiki/Universally_unique_identifier)
(universally unique identifiers) to all resources, and uses them in the API.

This differs from farmOS 1.x, which used the integer IDs directly from the
auto-incrementing database table that the record was pulled from. The benefit
of UUIDs is they are guaranteed to be unique across multiple farmOS databases,
whereas the old IDs were not.

The internal integer IDs are not exposed via the API, so all code that needs to
integrate should use the new UUIDs instead.

Also note that the migration from farmOS 1.x to 2.x does not preserve the
internal integer IDs, so they may be different after migrating to 2.x.

### Logs

#### Type names

The `farm_` prefix has been dropped from all log type names. For example, in
farmOS 1.x an Activity log was `farm_activity`, and in farmOS 2.x it is simply
`activity`.

Additionally, the "Soil test" and "Water test" log types have been merged into
a single "Lab test" log type.

Below is the full list of log types in farmOS 1.x and their new names in 2.x:

- `farm_activity` -> `activity`
- `farm_harvest` -> `harvest`
- `farm_input` -> `input`
- `farm_maintenance` -> `maintenance`
- `farm_medical` -> `medical`
- `farm_observation` -> `observation`
- `farm_purchase` -> `purchase`
- `farm_sale` -> `sale`
- `farm_seeding` -> `seeding`
- `farm_soil_test` -> `lab_test`
- `farm_transplanting` -> `transplanting`
- `farm_water_test` -> `lab_test`

#### Field names

Log field names are largely unchanged, with a few exceptions (note that *new*
fields are not listed here):

- `date_purchase` -> `purchase_date`
- `done` -> `status` (see "Status" below)
- `files` -> `file`
- `flags` -> `flag`
- `geofield` -> `geometry`
- `images` -> `image`
- `input_method` -> `method`
- `input_source` -> `source`
- `log_category` -> `category`
- `log_owner` -> `owner`
- `seed_source` -> `source`
- `soil_lab` -> `lab`
- `water_lab` -> `lab`

#### Status

In farmOS 1.x, logs had a boolean property called `done` which was either `1`
(done) or `0` (not done).

In 2.x, the `done` property has changed to `status`, and can be set to either
`done` or `pending`. Additional states may be added in the future.
