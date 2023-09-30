# API

farmOS provides an API that other applications and systems can use to read and
write records via HTTP requests.

## Client Libraries

Client libraries are available for interacting with the farmOS API:

- [farmOS.js](https://github.com/farmOS/farmOS.js) - [documentation](https://farmos.org/development/farmos-js/)
- [farmOS.py](https://github.com/farmOS/farmOS.py) - [documentation](https://farmos.org/development/farmos-py/)

## JSON:API

farmOS adheres to the [JSON:API](https://jsonapi.org/) specification for
defining API resources and uses the [JSON:API](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module)
module included with Drupal core.

Refer to the Drupal JSON:API [documentation](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module)
for all features including:

- [Core concepts](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/core-concepts)
- [Filtering](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/filtering)
- [Pagination](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/pagination)
- [Sorting](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/sorting)
- and many more.

### Endpoints

farmOS uses the `/api` path prefix for all JSON:API endpoints.

A root `/api` endpoint provides information meta information about the
authenticated user and the farmOS server:

```json
  "meta": {
    "links": {
      "me": {
        "meta": {
          "id": "e437f724-45cd-4c36-852b-e91f7daec5fd"
        },
        "href": "https://farmos.site/api/user/user/e437f724-45cd-4c36-852b-e91f7daec5fd"
      }
    },
    "farm": {
      "name": "Farm Name",
      "url": "https://farmos.site",
      "version": "3.x",
      "system_of_measurement": "metric"
    }
  }
```

The root `/api` endpoint also provides a `links` object that describes all
the available resource types and their endpoints. These follow a URL pattern of
`/api/[entity-type]/[bundle]`.

For example: `/api/log/activity`

"Bundles" are "sub-types" that can have different sets (bundles) of fields on
them. For example, a "Seeding Log" and a "Harvest Log" will collect different
information, but both are "Logs" (events).

### IDs

farmOS assigns [UUIDs](https://en.wikipedia.org/wiki/Universally_unique_identifier)
(universally unique identifiers) to all resources, and uses them in the API.

## JSON Schema

[JSON Schema](https://json-schema.org/) is used to describe the available API
resources.

To begin exploring the farmOS API schema, visit `/api/schema`. From there, you
can traverse a graph of interconnected schemas describing the entire API.
