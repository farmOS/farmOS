# API

farmOS has a [REST API] that other applications/systems can use to communicate
with farmOS programatically. The API provides [CRUD] operations for all of the
main farmOS [record types] (Drupal entity types). This is accomplished with the
[RESTful Web Services] module.

**Note for Sensor Data:** If you are looking for documentation about pushing
and pulling data from sensors in farmOS, see [Sensors](/guide/assets/sensors).

The following documentation provides a brief overview of the farmOS API for
developers who are interested in using it. For more general information, please
refer to the [RESTful Web Services module documentation].

In all of the commands below, replace the following placeholders with your
specific farmOS URL, username, and password.

* `[URL]` - Base URL of the farmOS instance, without trailing slash (eg: `https://example.farmos.net`)
* `[USER]` - User name (eg: `MyUserName`)
* `[PASS]` - Password (eg: `MyPassword`)
* `[AUTH]` - Authentication parameters for `curl` commands (this will depend on
  the authentication method you choose below).

## Authentication

### Cookie and Token

The simplest approach is to authenticate via Drupal's `user_login` form and
save the session cookie provided by Drupal. Then, you can use that to retrieve
a CSRF token from `[URL]/restws/session/token`, which is provided and required
by the `restws` module. The cookie and token can then be included with each API
request.

The following `curl` command will authenticate using the username and password,
and save the session cookie to a file called `farmOS-cookie.txt`. Then it will
get a session token and save that to a `$TOKEN` variable. Together, the cookie
and token can be used to make authenticated requests.

    curl --cookie-jar farmOS-cookie.txt -d 'name=[USER]&pass=[PASS]&form_id=user_login' [URL]/user/login
    TOKEN="$(curl --cookie farmOS-cookie.txt [URL]/restws/session/token)"

After running those two commands, the cookie and token can be included with
subsequent `curl` requests via the `--cookie` and `-H` parameters:

    --cookie farmOS-cookie.txt -H "X-CSRF-Token: ${TOKEN}"

This should be used to replace `[AUTH]` in the `curl` examples that follow.

### Basic Authentication

An alternative approach is to use [HTTP Basic Authentication].

The RESTful Web Services module comes with a Basic Authentication sub-module,
which provides the ability to include the username and password with each
request using HTTP Basic Authentication. This modules is included with farmOS
but is disabled by default.

**By default the module only tries to authenticate usernames prefixed with 'restws'.**
This can be changed by modifying the regex used against usernames - see the
[RESTful Web Services Basic Authentication module documentation].

**SSL encryption is highly recommended.** This option requires sending
credentials with every request which means extra care must be taken to not
expose these credentials. Using SSL will encrypt the credentials with each
request. Otherwise, they will be sent in plaintext.

Using basic authentication makes it much easier to test the API with
development tools such as [Postman] or [Restlet]. You just need to add basic
authentication credentials to the request header. The request returns a cookie,
which seems to be saved by these applications. This means subsequent requests
don't need the authentication header, as long as the cookie is saved.

Basic Authentication can be used in `curl` requests via the `-u` parameter:

    -u [USER]:[PASS]

This should be used to replace `[AUTH]` in the `curl` examples that follow.

## Requesting records

The following `curl` command examples demonstrate how to get lists of records
and individual records in JSON format. They use the stored `farmOS-cookie.txt`
file and `$TOKEN` variable generated with the commands above.

**Lists of records**

This will retrieve a list of harvest logs in JSON format:

    curl [AUTH] [URL]/log.json?type=farm_harvest

Records can also be requested in XML format by changing the file extension in
the URL:

    curl [AUTH] [URL]/log.xml?type=farm_harvest

**Individual records**

Individual records can be retrieved in a similar way, by including their entity
ID. This will retrieve a log with ID 1:

    curl [AUTH] [URL]/log/1.json

**Endpoints**

The endpoint to use depends on the entity type you are requesting:

* Assets: `/farm_asset.json`
    * Plantings: `/farm_asset.json?type=planting`
    * Animals: `/farm_asset.json?type=animal`
    * Equipment: `/farm_asset.json?type=equipment`
    * ...
* Logs: `/log.json`
    * Activities: `/log.json?type=farm_activity`
    * Observations: `/log.json?type=farm_observation`
    * Harvests: `/log.json?type=farm_harvest`
    * Inputs: `/log.json?type=farm_input`
    * ...
* Taxonomy terms: `/taxonomy_term.json`
    * Areas*: `/taxonomy_term.json?bundle=farm_areas`
    * ...

*Note that areas are currently represented as Drupal taxonomy terms, but may be
changed to assets in the future. See [Make "Area" into a type of Farm Asset].

## Creating records

Records can be created with a `POST` request of JSON/XML objects to the endpoint
for the entity type you would like to create.

First, here is a very simple example of an observation log in JSON. The bare
minimum required fields are `name`, `type`, and `timestamp`.

    {
      "name": "Test observation via REST",
      "type": "farm_observation",
      "timestamp": "1526584271",
    }

Here is a `curl` command to create that log in farmOS:

    curl -X POST [AUTH] -H 'Content-Type: application/json' -d '{"name": "Test observation via REST", "type": "farm_observation", "timestamp": "1526584271"}' [URL]/log

## Updating records

Records can be updated with a `PUT` request of JSON/XML objects to the record's
endpoint (with ID). Only include the fields you want to update - everything
else will be left unchanged.

Do not add `.json` or `.xml` to the endpoint's URL. Instead, add a
`Content-Type` header that specifies either `application/json` or
`application/xml`.

For example, to change the name of log 1:

    curl -X PUT [AUTH] -H 'Content-Type: application/json' -d '{"name": "Change the log name"}' [URL]/log/1

## Field collections

Some fields are stored within [Field Collections] in farmOS, which are
technically separate entities attached to the host entity. farmOS uses the
[RESTful Web Services Field Collection] module to hide the fact that these are
separate entities, so that their fields can be accessed and modified in the
same request as the host entity.

In farmOS Field Collections are used for the following:

### Quantity measurements

[Quantity] measurements are recorded on logs, and can each include a measure
(eg: count, weight, volume, etc), a value (decimal number), a unit (a reference
to a Units taxonomy term, and a label (a free-form text field).

A log with a quantity measurement looks like this:

    {
      "name": "Example quantity measurement log",
      "type": "farm_observation",
      "timestamp": "1551983893",
      "quantity": [
        {
          "measure": "weight",
          "value": "100",
          "unit": {"id": "15"},
          "label": "My label",
        },
      ],
    }

All measurement properties are optional. The label is useful if you have more
than one measurement of the same measure (eg: two weight measurements) in the
same log. Use the label to add more specific information about what each
measurement is for.

### Movements

[Movements] are defined on logs, and include an area reference (where the
asset(s) are moving to), and optionally a geometry (if you need to define a more
specific location than the area reference itself).

A log with a movement looks like this:

    {
      "name": "Example movement log",
      "type": "farm_activity",
      "timestamp": "1551983893",
      "asset": [
        {"id": "123"},
      ],
      "movement": {
        "area": [
          {"id": "5"},
        ],
        "geometry": "POINT(-31.04003861546518 39.592143995003994)",
      },
    }

Within the `movement` structure, the area ID should be an area term ID (`tid` on
the area object). The geometry should be in [Well-Known Text]. It is possible to
reference multiple areas, indicating that the asset is moved to multiple areas
(eg: a planting in multiple beds).

### Group membership

[Group] membership changes are defined on logs, and include one or more group
asset IDs. This indicates that any referenced asset(s) were moved to the
group(s) specified at the time of the log.

A log with a group membership change looks like this:

    {
      "name": "Example group membership change log",
      "type": "farm_activity",
      "timestamp": "1551983893",
      "asset": [
        {"id": "123"},
      ],
      "membership": {
        "group": [
          {"id": "456"},
        ],
      },
    }

Groups are a type of asset in farmOS, so the group ID is actually an asset ID.

### Inventory adjustments

[Inventory] adjustments are defined on logs, and include one or more pairs of
asset IDs (the asset for which inventory is being adjusted) and an adjustment
value (postitive or negative number, to indicate an increase or decrease in the
inventory level).

A log with an inventory adjustment looks like this:

    {
      "name": "Example group membership change log",
      "type": "farm_activity",
      "timestamp": "1551983893",
      "inventory": [
        {
          "asset": {"id": "123"},
          "value": "-10",
        },
      ],
    }

### Animal ID Tags

Animal ID Tags can be added to [animal] assets, and include a Tag ID, Tag Type,
and Tag Location.

An animal asset with an ID tag looks like this:

    {
      "name": "Betsy",
      "type": "animal",
      "animal_type": {"id": "10"},
      "tag": [
        {
          "id": "123456",
          "location": "Left ear",
          "type": "Ear tag",
        },
      ],
    }

The ID and Location fields can be anything you want.

Available tag types are:

* `Brand`
* `Ear tag`
* `Tattoo`
* `Leg band`
* `Chip`
* `Other`

## Uploading files

Files can be attached to records using the API.

Most record types (areas, assets, logs, etc) have both a "Photo(s)" field (for
image files) and a "File(s)" field (for other files). The machine names of
these fields are:

* Photo(s): `images`
* File(s): `files`

The API expects an array of base64-encoded strings. Multiple files can be
included in the array.

For example (replace `[BASE64_CONTENT]` with the base64 encoding of the file):

    {
      "name": "Test file upload via REST",
      "type": "farm_observation",
      "timestamp": "1534261642",
      "images": [
        "data:image/jpeg;base64,[BASE64_CONTENT]",
      ],
    }

Here is an example using `curl` (replace `[filename]` with the path to the
file). This will create a new observation log with the file attached to the
"Photo(s)" field of the log.

    # Get the file MIME type.
    FILE_MIME=`file -b --mime-type [filename]`

    # Encode the file as a base64 string and save it to a variable.
    FILE_CONTENT=`base64 [filename]`

    # Post a log with the file attached to the photo field.
    # <<CURL_DATA is used because base64 encoded files are generally longer
    # the max curl argument length. See:
    # https://unix.stackexchange.com/questions/174350/curl-argument-list-too-long
    curl -X POST [AUTH] -H "Content-Type: application/json" -d @- [URL]/log <<CURL_DATA
    {"name": "Test file upload via REST", "type": "farm_observation", "timestamp": "1534261642", "images": ["data:${FILE_MIME};base64,${FILE_CONTENT}"]}
    CURL_DATA

## Troubleshooting

Some common issues and solutions are described below. If these do not help,
submit a support request on [GitHub] or ask questions in the
[farmOS chat room].

* **HTTPS redirects** - If your farmOS instance automatically redirects from
  `http://` to `https://`, make sure you are making requests using the
  `https://` protocol. The redirect can cause issues with some API requests.
* **Date format** - All dates in farmOS are stored in [Unix timestamp] format.
  Most frameworks/languages provide functions for generating/converting
  dates/times to this format. Only Unix timestamps will be accepted by the API.

[REST API]: https://en.wikipedia.org/wiki/Representational_state_transfer
[CRUD]: https://en.wikipedia.org/wiki/Create,_read,_update_and_delete
[record types]: /development/architecture
[RESTful Web Services]: https://www.drupal.org/project/restws
[RESTful Web Services module documentation]: https://www.drupal.org/node/1860564
[HTTP Basic Authentication]: https://en.wikipedia.org/wiki/Basic_access_authentication
[RESTful Web Services Basic Authentication module documentation]: https://cgit.drupalcode.org/restws/tree/restws_basic_auth/README.txt
[Postman]: https://www.getpostman.com/
[Restlet]: https://restlet.com/
[Make "Area" into a type of Farm Asset]: https://www.drupal.org/project/farm/issues/2363393
[Field Collections]: https://drupal.org/project/field_collection
[RESTful Web Services Field Collection]: https://drupal.org/project/restws_field_collection
[Quantity]: /guide/quantity
[Movements]: /guide/location
[Well-Known Text]: https://en.wikipedia.org/wiki/Well-known_text_representation_of_geometry
[Group]: /guide/assets/groups
[Inventory]: /guide/inventory
[animal]: /guide/assets/animals
[GitHub]: https://github.com/farmOS/farmOS
[farmOS chat room]: https://riot.im/app/#/room/#farmOS:matrix.org
[Unix timestamp]: https://en.wikipedia.org/wiki/Unix_time

