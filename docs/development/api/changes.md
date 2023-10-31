# API Changes

## 3.x vs 2.x

The [Simple OAuth](https://www.drupal.org/project/simple_oauth) module has been
updated to version 6. This includes a few breaking changes which may affect API
integrations. farmOS includes code to handle the transition of its own OAuth
clients and scopes, but if you have made any additional clients that used
special roles they will also need to be updated.

The biggest changes are that the "Implicit" grant type has been
removed, and the "Password Credentials" grant type has been moved to an optional
"Simple OAuth Password Grant" module, which must be enabled in order to use that
grant type.

There have also been changes to how scopes are provided. User roles no longer
act as scopes by default. Instead, scopes must be created separately to
reference each role they represent. Scopes can also be associated with
individual permissions and can reference parent scopes to create
hierarchical scope trees. farmOS provides `static` scopes for each of the
default roles: `farm_manager`, `farm_worker` and `farm_viewer`.

The default farmOS client that is included with farmOS has also been
moved to a separate module that is not enabled by default. After the update to
farmOS 3.x, all access tokens will be invalidated, but refresh tokens will still
work to get a new access token.

Other notable changes:

- [Material quantities can reference multiple material types](https://www.drupal.org/node/3395697)
- Log `timestamp` is marked as `required` in JSON Schema
- Allowed values are declared in JSON Schema `oneOf` / `anyOf` enumerations for
  more entity attributes.

## 2.x vs 1.x

farmOS 1.x used the [RESTful Web Services](https://drupal.org/project/restws)
module, which provided API endpoints for each entity type (asset, log, taxonomy
term, etc).

farmOS 2.x uses the new [JSON:API](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module)
module included with Drupal core, which follows the [JSON:API](https://jsonapi.org/)
specification for defining API resources.

The root API endpoint is `/api`.

### JSON Schema

farmOS 2.x also provides [JSON Schema](https://json-schema.org/) information
about all available resources. The root endpoint for schema information is
`/api/schema`.

In farmOS 1.x, the `/farm.json` endpoint provided similar information in the
`resources` property. This has been removed in favor of JSON Schema.

### Authentication

See [API Authentication](/development/api/authentication) for more information
about authorizing and authenticating farmOS 2.x API requests.

Notable changes from 1.x include:

- The new authorization URL is `/oauth/authorize` (was `/oauth2/authorize`).
- The new token URL is `/oauth/token` (was `/oauth2/token`).
- Requests should use `Content-Type: application/vnd.api+json` (was
  `Content-Type: application/json`).

### Farm info endpoint

In farmOS 1.x, an informational API endpoint was provided at `/farm.json`. This
included various information describing the farmOS server configuration,
authenticated user, installed languages and available entity types and bundles.
This information was provided as either a simple value or a JSON object:

```json
{
  "name": "My Farm",
  "url": "https://myfarm.mydomain.com",
  "api_version": "1.0",
  "system_of_measurement": "metric",
  "user": { ... },
  "languages": { ... },
  "resources": { ... },
  "metrics": { ... }
}
```

In farmOS 2.x, a root `/api` endpoint either provides this information, or is a
gateway to this information.

The simple values previously available from
`/farm.json` are now provided in the `meta.farm` object at `/api`:

```json
{
   "jsonapi":{ ... },
   "data":[],
   "meta":{
      "links":{
         "me":{
            "meta":{
               "id":"7b2af019-3191-40ca-b221-616f9a365722"
            },
            "href":"http://localhost/api/user/user/7b2af019-3191-40ca-b221-616f9a365722"
         }
      },
      "farm":{
         "name":"My farm name",
         "url":"http://localhost",
         "version":"2.x",
         "system_of_measurement": "metric"
      }
   },
   "links":{ ... }
}
```

The `resources` object has been replaced with the `links` object that
describes all the available resource types and their endpoints. Information
previously provided in the other JSON objects are now available as standalone
resources at their respective endpoints:

- `user` - `/api/user/user`
    - The authenticated user's ID is included in the `meta.links.me` object
      with a link to the user's resource. The user's attributes, such as name
      and language, can be retrieved from that endpoint.
- `languages` -  `/api/configurable_language/configurable_language`

### Resource endpoints

In farmOS 1.x, API endpoints for each entity type were available at
`/[entity_type].json`.

For example: `/log.json`

In farmOS 2.x, a root `/api` endpoint is provided, with a `links` object that
describes all the available resource types and their endpoints. These follow
a URL pattern of `/api/[entity-type]/[bundle]`.

For example: `/api/log/activity`

"Bundles" are "sub-types" that can have different sets (bundles) of fields on
them. For example, a "Seeding Log" and a "Harvest Log" will collect different
information, but both are "Logs" (events).

To illustrate the difference between 1.x and 2.x, here are the endpoints for
retrieving all Activity logs.

- farmOS 1.x: `/log.json?type=farm_activity`
- farmOS 2.x: `/api/log/activity`

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

### Record structure

JSON:API has some rules about how records are structured that differ from
farmOS 1.x. These rules make the API more explicit.

In farmOS 1.x, all the fields/properties of a record were on the same level.

For example, a simple observation log looked like this:

```
{
    "id": "5"
    "type": "farm_observation",
    "name": "Test observation",
    "timestamp": "1526584271",
    "asset": [
      {
        "resource": "farm_asset",
        "id": "123"
      }
    ]
}
```

In farmOS 2.x, JSON:API dictates that the "attributes" and "relationships" of a
record be explicitly declared under `attributes` and `relationships` properties
in the JSON.

The same record in farmOS 2.x looks like:

```
{
  "id": "9bc49ffd-76e8-4f86-b811-b721cb771327"
  "type": "log--observation",
  "attributes": {
    "name": "Test observation",
    "timestamp": "1526584271",
  },
  "relationships": {
    "asset": {
      "data": [
        {
          "type": "asset--animal",
          "id": "75116e3e-c45e-431d-8b58-1fce6bb315cf",
        }
      ]
    }
  }
}
```

### Filtering

The URL query parameters for filtering results have a different syntax in 2.x.
Refer to the [Drupal.org JSON:API Filtering documentation](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/filtering)
for more information.

To illustrate, this is how to filter activity logs by their completed status:

- farmOS 1.x: `/log.json?type=activity&done=1`
- farmOS 2.x: `/api/log/activity?filter[status]=complete`

### Text format

Long text fields (like `notes`) include `value` and `format` sub-properties,
where `value` is the text value, and `format` is the "Text format" to use when
displaying the text. This is used to filter user-supplied text, to only allow
certain HTML tags (filtering out potential XSS vulnerabilities), convert URLs
to links, etc.

This works the same in farmOS 2.x, but the default `format` has changed from
`farm_format` to `default`.

### Logs

#### Log types

The `farm_` prefix has been dropped from all log type names. For example, in
farmOS 1.x an Activity log was `farm_activity`, and in farmOS 2.x it is simply
`activity`.

Additionally, the "Soil test" and "Water test" log types have been merged into
a single "Lab test" log type.

Also note that "Sale" and "Purchase" logs have been moved out of farmOS core to
a new [farmOS Ledger](https://drupal.org/project/farm_ledger) module.

Below is the full list of log types in farmOS 1.x and their new names in 2.x:

- `farm_activity` -> `activity`
- `farm_harvest` -> `harvest`
- `farm_input` -> `input`
- `farm_maintenance` -> `maintenance`
- `farm_medical` -> `medical`
- `farm_observation` -> `observation`
- `farm_seeding` -> `seeding`
- `farm_soil_test` -> `lab_test`
- `farm_transplanting` -> `transplanting`
- `farm_water_test` -> `lab_test`

#### Log fields

Log field names are largely unchanged, with a few exceptions (note that *new*
fields are not listed here):

- `area` -> `location` (See "Areas" below)
- `date_purchase` -> `purchase_date`
- `done` -> `status` (see "Log status" below)
- `files` -> `file`
- `flags` -> `flag`
- `geofield` -> `geometry`
- `images` -> `image`
- `input_method` -> `method`
- `input_source` -> `source`
- `inventory` (merged into `quantity` entities)
- `log_category` -> `category`
- `log_owner` -> `owner`
- `material` (migrated to "Material" `quantity` entities)
- `seed_source` -> `source`
- `soil_lab` -> `lab` (see "Laboratory" below)
- `water_lab` -> `lab` (see "Laboratory" below)
- `quantity` (see "Quantities" below)

See also "Text format" above for information about the changes to the `format`
parameter of long text fields.

#### Log status

In farmOS 1.x, logs had a boolean property called `done` which was either `1`
(done) or `0` (not done).

In 2.x, the `done` property has changed to `status`, and can be set to either
`done` or `pending`. Additional states may be added in the future.

#### Laboratory

In farmOS 1.x, Soil test and Water test logs had a "Laboratory" field for
storing the name of the lab that performed the test as a string.

In 2.x, a new "Labs" taxonomy has been added, and the "Laboratory" field on
Lab test logs is a term reference field.

### Assets

Asset records in farmOS 1.x had an entity type of `farm_asset`. In farmOS 2.x,
the `farm_` prefix has been dropped. The entity type is now simply `asset`.

#### Asset types

Asset type names are largely unchanged, with one notable exception: the
"Planting" asset type has been renamed to "Plant".

Below is the full list of asset types in farmOS 1.x and their new names in 2.x:

- `animal` (unchanged)
- `compost` (unchanged)
- `equipment` (unchanged)
- `group` (unchanged)
- `planting` -> `plant`
- `sensor` (unchanged)

#### Asset fields

Asset field names are largely unchanged, with a few exceptions (note that *new*
fields are not listed here):

- `animal_castrated` -> `is_castrated`
- `animal_nicknames` -> `nickname`
- `animal_sex` -> `sex`
- `animal_tag` -> `id_tag`
- `archived` -> `status` and `archived` (see "Asset status" below)
- `crop` -> `plant_type`
- `date` -> `birthdate` (on `animal` assets)
- `description` -> `notes` (see also "Text format" above)
- `flags` -> `flag`
- `files` -> `file`
- `images` -> `image`

#### Asset status

In farmOS 1.x, assets had a property called `archived` which was either `0`,
which indicated that the asset was active, or a timestamp that recorded when
the asset was archived.

In farmOS 2.x, these have been split into two separate fields:

- `status` - The status of the asset (either `active` or `archived`).
- `archived` - The timestamp when the asset was archived. This will be empty
  if the asset is active.

### Taxonomies

farmOS 2.x continues to use Drupal's core `taxonomy_term` entities to represent
vocabularies of terms. The vocabulary machine names have changed, to drop the
`farm_` prefix, and to standardize plurality.

- `farm_animal_types` -> `animal_type`
- `farm_areas` has been removed (see "Areas" below)
- `farm_log_categories` -> `log_category`
- `farm_materials` -> `material_type`
- `farm_season` -> `season`
- `farm_crops` -> `plant_type`
- `farm_crop_families` -> `crop_family`
- `farm_quantity_units` -> `unit`

### Areas

farmOS 1.x had the concept of "Areas" for representing places/locations. These
were taxonomy terms in the `farm_areas` vocabulary. In farmOS 2.x, these areas
are migrated to new asset types, and any asset can now be designated as a
"location". New asset types are provided, including "Land", "Structure", and
"Water", which have the "location" designation by default. Additional types can
be provided by modules.

Because any asset can be a location, some new fields are available on assets,
including:

- `is_location` - Boolean indicating whether or not other assets can be moved
  to this asset.
- `is_fixed` - Boolean indicating that the asset has a fixed geometry and
  therefore does not move.
- `intrinsic_geometry` - A geofield representing the intrinsic geometry of
  "fixed" assets.

Additionally, two "computed" fields are available on all assets, which provide
quick access to the asset's current location and geometry, regardless of
whether or not it is "fixed":

- `geometry` - The asset's current geometry. This will be the same as the
  `intrinsic_geometry` for "fixed" assets. Otherwise, it will mirror the
  geometry of the asset's most recent movement log.
- `location` - The asset's current location (an asset reference). This will
  always be empty for "fixed" assets. Otherwise, it will mirror the location
  reference field of the asset's most recent movement log.

### Quantities

In farmOS 1.x, log quantities were saved within separate Field Collection
entities. farmOS used the [RESTful Web Services Field Collection](https://drupal.org/project/restws_field_collection)
module to  hide the fact that these were separate entities, allowing their
field to be accessed and modified in the same request to the host entity.

In farmOS 2.x, quantities are represented as `quantity` entities. These are
referenced under a log's `relationships` in JSON:API, and have a JSON:API
resource name of `quantity--quantity`. In order to add a quantity to a new or
existing log, they must be created in a separate API request before they can be
referenced by the log. Quantities still have `measure`, `value`, `unit` and
`label` fields.

### Files

farmOS 1.x used the [RESTful Web Services File](https://www.drupal.org/project/restws_file)
module to enable file uploads via the API. The API accepted an array of
base64-encoded strings to be included in the JSON body payload of the host
entity.

In farmOS 2.x, file uploads are supported by the core JSON:API module. Instead
of base64-encoded strings, the API requires a separate `POST` of binary data
for each file to upload. This reflects "real" PHP upload semantics, allowing
for faster and larger file uploads via the API. This also means that files
cannot be uploaded in the same request that creates an entity. Instead, a file
can be uploaded to an *existing entity* in a single request, or the file can be
uploaded and assigned to an entity in two separate requests. Refer to the
[Drupal.org JSON:API File Uploads documentation](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/file-uploads)
for more information.

For example, to upload an image file to an existing observation log with `curl`:

    curl https://example.com/api/log/observation/{UUID}/image \
       -H 'Accept: application/vnd.api+json' \
       -H 'Content-Type: application/octet-stream' \
       -H 'Content-Disposition: attachment; filename="observation.jpg"' \
       -H 'Authorization: Bearer …………' \
       --data-binary @/path/to/observation.jpg
