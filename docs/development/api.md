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

## OAuth2 Authorization

farmOS includes an OAuth2 Authorization server for providing 3rd party clients 
access to the farmOS API. Rather than using a user's username and password to
authenticate, OAuth2 uses access tokens to authenticate users. The access tokens
are provided to both 1st and 3rd party clients who wish to access a user's 
protected resources from a server. Clients store the access token instead of the
user's credentials, which makes it a more secure authentication method.

Read more about the [OAuth 2.0 standards]

### Authorization Flows

The [OAuth 2.0 standards] outline 4 [Oauth2 Grant Types] to be used in an OAuth2
Authorization Flow - They are the *Authorization Code, Implicit, Password
Credentials* and *Client Credentials* Grants. Currently, the farmOS API
supports all of these grant types except for the **Client Credentials** 
grant. The **Authorization Code Grant** and **Implicit Grant** are the only
Authorization Flows recommended by farmOS for use with 3rd party clients. See
**Refreshing Tokens** for documentation on how to retrieve new `access_tokens`
after expiration.

**NOTE:** Only use the **Password Grant** if the client can be trusted with a
farmOS username and password (this is considered *1st party*). The
**Client Credentials Grant** is often used for machine authentication not
associated with a user account. Due to limitations with the Drupal 7
[oauth2_server] module, access tokens provided via the Client Credentials Grant
cannot be associated with the Drupal Permissions system to access protected
resources. farmOS will hopefully support the Client Credentials Grant when
farmOS migrates to Drupal 8 and can use the [simple_oauth] module.

#### Authorization Code Grant

The Authorization Code Grant is most popular for 3rd party client authorization.

Requesting resources is a four step process:

1. First, the client sends a request to the farmOS server `/oauth2/authorize`
endpoint requesting an `Authorization Code`. The user logs in and authorizes
the client to have the OAuth Scopes it is requesting.
    
        Copy this link to browser -
        http://localhost/oauth2/authorize?response_type=code&client_id=farmos_api_client&redirect_uri=http://localhost/api/authorized&state=p4W8P5f7gJCIDbC1Mv78zHhlpJOidy
   
2. After the user accepts, the server redirects
to the `redirect_uri` with an authorization `code` and `state` in the query
parameters.

        Example redirect url from server:
        http://localhost/api/authorized?code=9eb9442c7a2b011fd59617635cca5421cd089943&state=p4W8P5f7gJCIDbC1Mv78zHhlpJOidy
        
3. Copy the `code` and `state` from the URL into the body of a POST request.
The `grant_type`, `client_id`, `client_secret` and `redirect_uri` must also be
included in the POST body. The client makes a POST request to the
`/oauth2/token` endpoint to retrieve an `access_token` and `refresh_token`.

    ```console
    foo@bar:~$ curl -X POST -d "grant_type=authorization_code&code=ae4d1381cc67def1c10dc88a19af6ac30d7b5959&client_id=farmos_api_client&client_secret=client_secret&redirect_uri=http://localhost/api/authorized" http://localhost/oauth2/token
    {"access_token":"3f9212c4a6656f1cd1304e47307927a7c224abb0","expires_in":"10","token_type":"Bearer","scope":"farmos_restws_access","refresh_token":"292810b04d688bfb5c3cee28e45637ec8ef1dd9e"}
    ```
4. The client sends the access token in the request header to access protected
resources. The header is an Authorization header with a Bearer token: 
 `Authorization: Bearer access_token`
    
   ```console
   foo@bar:~$ curl --header "Authorization: Bearer b872daf5827a75495c8194c6bfa4f90cf46c143e" http://localhost/farm.json
   {"name":"farmos-server","url":"http:\/\/localhost","api_version":"1.1","user":{"uid":"1","name":"admin", .... 
   ```

#### Implicit Grant

The Implicit Grant is similar to the Authorization Code Grant but only requires
two steps. The downside is that it does not provide a `refresh_token` to
generate new `access_tokens` after their expiration. The entire Authorization
process must be completed again.

Requesting protected resources is a three step process:

1. First, the client sends a request to the farmOS server `/oauth2/authorize`
endpoint with `response_type` set to `token`. The server redirects the client to
an`Authorization` page where the user logs in an authorizes the client access to
the OAuth Scopes it is requesting.
        
        Copy this link to browser:
        http://localhost/oauth2/authorize?response_type=token&client_id=farmos_api_client&redirect_uri=http://localhost/api/authorized&state=sample_state

2. After the user accepts, the server
redirects with an `access_token` in the URL Fragment.

        Example redirect url from server:
        http://localhost/api/authorized#access_token=decf13e124664570ae7d4300990280ac43aeb313&expires_in=300&token_type=Bearer&scope=farmos_restws_access&state=akdsfjalsdfjasdfkk

3. The client sends the `access_token` in the request header to access protected
resources. The header is an Authorization header with a Bearer token: 
 `Authorization: Bearer access_token`
    
    ```console
    foo@bar:~$ curl --header "Authorization: Bearer decf13e124664570ae7d4300990280ac43aeb313" http://localhost/farm.json
    {"name":"farmos-server","url":"http:\/\/localhost","api_version":"1.1","user":{"uid":"1","name":"admin", .... 
    ```

#### Password Credentials Grant

#### Refreshing Tokens

### OAuth2 Authorization with the farmOS API

#### Authorization in farmOS.py

#### Authorization in farmOS.js


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

## /farm.json

farmOS provides an informational API endpoint at `/farm.json`, which includes
the farm name, URL, API version, the system of measurement used by the farm,
information about the currently authenticated user, and information about
available entity types and bundles.

For example:

    {
      "name": "My Farm",
      "url": "https://myfarm.mydomain.com",
      "api_version": "1.0",
      "system_of_measurement": "metric",
      "user": {
        "uid": "3",
        "name": "My Username",
        "mail": "myemail@mydomain.com",
      },
      "resources": {
        "log": {
          "farm_activity": {
            "label": "Activity",
            "label_plural": "Activities",
            "fields" {
              "area": {
                "label": "Areas",
                "type": "taxonomy_term_reference",
                "required": 0,
              },
              ...
            },
          }
        },
        ...
      }
    }

The `system_of_measurement` with be either `metric` or `us`.

The `resources` section contains a list of entity types (aka "resource types")
that are available. Within each is a list of bundles (eg: "log types", "asset
types", etc). Each bundle contains information such as its translated `label`,
and a list of available `fields` and their metadata (`label`, `required`, etc).
The `taxonomy_term` bundles also contain their `vid` (vocabulary ID), which is
necessary when creating new terms (see [Creating taxonomy terms]).

### API Version

**Current API version: 1.2**

It is *highly* recommended that you check the API version of the farmOS system
you are communicating with, to be sure that your code is using the same version.
If any changes are made that are not backwards compatible, the API version will
change. So by checking it in your code, you can prevent unexpected things from
happening if you try to use unsupported API features.

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
    * Areas&ast;: `/taxonomy_term.json?bundle=farm_areas`
    * Quantity Units: `/taxonomy_term.json?bundle=farm_quantity_units`
    * Crops/varieties: `/taxonomy_term.json?bundle=farm_crops`
    * Animal species/breeds: `/taxonomy_term.json?bundle=farm_animal_types`
    * Log categories: `/taxonomy_term.json?bundle=farm_log_categories`
    * Seasons: `/taxonomy_term.json?bundle=farm_season`
    * ...

&ast; Note that areas are currently represented as Drupal taxonomy terms, but may be
changed to assets in the future. See [Make "Area" into a type of Farm Asset].

## Creating records

Records can be created with a `POST` request of JSON/XML objects to the endpoint
for the entity type you would like to create.

### Creating logs

First, here is a very simple example of an observation log in JSON. The bare
minimum required fields are `name`, `type`, and `timestamp`.

    {
      "name": "Test observation via REST",
      "type": "farm_observation",
      "timestamp": "1526584271",
    }

Here is a `curl` command to create that log in farmOS:

    curl -X POST [AUTH] -H 'Content-Type: application/json' -d '{"name": "Test observation via REST", "type": "farm_observation", "timestamp": "1526584271"}' [URL]/log

Most log types have the same set of standard fields. Some specific log types
differ slightly. The following are standard fields available on most log types:

* `id`: The log ID (unique database primary key).
* `name`: The log name.
* `type`: The log type. Some types that are included in farmOS are:
    * `farm_activity`: General activity logs.
    * `farm_observation`: General observation logs.
    * `farm_input`: An input to one or more assets/areas.
    * `farm_harvest`: A harvest from one or more assets/areas.
    * `farm_seeding`: A seeding log (specific to Planting assets).
    * `farm_transplanting`: A transplanting log (specific to Planting assets).
    * `farm_maintenance`: A maintenance log (specific to Equipment assets).
* `timestamp`: A timestamp representing when the logged event took place.
* `done`: Boolean value indicating whether the log is "done" or not.
* `notes`: A free-form text field for log notes.
    * Note that this should be a JSON object with a `value` property and a
      `format` property set to `farm_format`.
* `asset`: Reference(s) to asset(s) that the log is associated with.
* `equipment`: Reference(s) to equipment assets that were used in the process.
* `area`: Reference(s) to area(s) that the log is associated with.
* `geofield`: Geometry specific to the logged event.
* `movement`: Movement information (see "Field collections" below).
* `membership`: Group membership information (see "Field collections" below).
* `quantity`: Quantity measurements (see "Field collections" below).
* `images`: Image files attached to the log.
* `files`: Other files attached to the log.
* `flags`: Flags associated with the log. Examples:
    * `priority`
    * `monitor`
    * `review`
* `log_category`: A reference to one or more log categories.
* `log_owner`: A reference to one or more users that the log is assigned to.
    * Note that this is different from `uid`, which is the user who created the
      log.
* `created`: A timestamp representing when the log was created.
* `changed`: A timestamp representing when the log was last updated.
* `uid`: A reference to the user who created the log.
* `data`: An arbitrary string of data. This can be used to store additional data
  on the log as JSON, XML, YAML, etc. **It is the resposibility of the API user
  to respect data format that is already in the log.**

#### Input logs

Additional fields on input logs (all optional):

* `material`: Reference(s) to material(s) that were applied.
* `input_purpose`: A description of the purpose of the input.
* `input_method`: A description of how the input was applied.
* `input_source`: A description of where the input was obtained.
* `date_purchase`: A timestamp representing when the input material was
  purchased.
* `lot_number`: The lot number(s) of the input material, if available.

#### Harvest logs

Additional fields on harvest logs (all optional):

* `lot_number`: The harvest lot number.

#### Seeding and transplanting logs

Seeding and transplanting logs are log types that are specific to Planting
assets (to indicate when seedings/transplantings took place, along with quantity
and location information). They have some key differences from other log types:

* The `asset` field is required and must reference an asset of type `planting`.
* They do not have the `areas` or `geofield` fields, and instead expect to have
  a `movement` field collection with information about where it took place. This
  is used to set the location of the planting at a given point in time.
* Seeding logs also have a `seed_source` field which can be used to specify
  where seeds came from (free-form text field).

### Creating assets

Assets must have a `name` and a `type`, and some asset types have additional
required fields. For example `planting` assets require a `crop` reference (for
crop/variety), `animal` assets require an `animal_type` reference (for
species/breed), and other types may have other requirements. A quick way to find
out which fields are required is to open the "Add asset" form in farmOS and look
for the red asterisk that indicates a required field.

A very basic "Equipment" asset looks like this:

    {
      "name": "Tractor",
      "type": "equipment",
      "manufacturer": "Allis-Chambers",
      "model": "G",
      "serial_number": "1234567890",
    }

Here is a `curl` command to create that asset in farmOS:

    curl -X POST [AUTH] -H 'Content-Type: application/json' -d '{"name": "Tractor", "type": "equipment", "manufacturer": "Allis-Chambers", "model": "G", "serial_number": "1234567890"}' [URL]/farm_asset

Most asset types have the same set of standard fields. Some specific asset types
differ slightly. The following are standard fields available on most log types:

* `id`: The asset ID (unique database primary key).
* `name`: The asset name.
* `type`: The asset type. Some types that are included in farmOS are:
    * `planting`
    * `animal`
    * `equipment`
    * `group`
* `description`: A free-form text field for an asset description.
    * Note that this should be a JSON object with a `value` property and a
      `format` property set to `farm_format`.
* `archived`: A timestamp representing when the asset was archived. If this is
  `0` then the asset is not archived.
* `images`: Image files attached to the asset.
* `files`: Other files attached to the asset.
* `flags`: Flags associated with the log. Examples:
    * `priority`
    * `monitor`
    * `review`
* `created`: A timestamp representing when the asset was created.
* `changed`: A timestamp representing when the asset was last changed.
* `uid`: A reference to the user who created the asset.
* `data`: An arbitrary string of data. This can be used to store additional data
  on the asset as JSON, XML, YAML, etc. **It is the resposibility of the API
  user to respect data format that is already in the log.**

Assets will also have the following special-purpose fields, which are computed
based on the asset's log history. These fields cannot be written to directly,
they can only be changed via logs.

* `location`: An array of references to areas that the asset is currently
  located in, based on its most recent completed movement log.
* `geometry`: The current geometry of the asset, based on its most recent
  completed movement log.

#### Planting assets

Additional fields on animal assets:

* `crop`: Reference(s) to the crops/varieties this is a planting of.
   **Required.**
* `parent`: Reference(s) to assets that this planting is derived from.

#### Animal assets

Additional fields on animal assets:

* `animal_type`: Reference to the animal's species/breed. **Required.**
* `animal_nicknames`: Nickname(s) for the animal.
* `animal_castrated`: Boolean indicating whether or not this animal has been
  castrated.
* `animal_sex`: The sex of the animal. Available options:
    * `F` (female)
    * `M` (male)
* `tag`: ID Tag(s) that are assigned to the animal (see "Field collections"
  below).
* `parent`: Reference(s) to the animal's parent(s).
* `date`: A timestamp representing the birth date of the animal.

#### Equipment assets

Additional fields on equipment assets (all optional):

* `manufacturer`: The equipment manufacturer.
* `model`: The equipment model.
* `serial_number`: The equipment serial number.

### Creating taxonomy terms

farmOS allows farmers to build vocabularies of terms for various categorization
purposes. These are referred to as "taxonomies" in farmOS (and Drupal), although
"vocabulary" is sometimes used interchangeably.

Some things that are represented as taxonomy terms include quantity units,
crops/varieties, animal species/breeds, input materials, and log categories.
See "Endpoints" above for specific API endpoints URLs.

A very basic taxonomy term JSON structure looks like this:

    {
      "tid": "3",
      "name": "Cabbage",
      "description": "",
      "vocabulary": {
        "id": "7",
        "resource": "taxonomy_vocabulary",
      },
      "parent": [
        {
          "id": "10",
          "resource": "taxonomy_term",
        },
      ],
      "weight": "5",
    }

The `tid` is the unique ID of the term (database primary key). When creating a
new term, the only required fields are `name` and `vocabulary`. The vocabulary
is an ID that corresponds to the specific vocabulary the term will be a part of
(eg: quantity units, crops/varieties, log categories, etc). The fields `parent`
and `weight` control term hierarchy and ordering (a heavier `weight` will sort
it lower in the list).

When fetching a list of terms from farmOS, you can filter by the vocabulary's
"machine name" (or "bundle") like so:

    curl [AUTH] [URL]/taxonomy_term.json?bundle=farm_crops

When creating a new term, however, you need to provide the vocabulary ID in the
term object, not the machine name/bundle. In order to get the vocabulary ID, you
can look it up using the `/taxonomy_vocabulary` endpoint, which lists all
available vocabularies. Or, you can find it in `farm.json` (described above).

The vocabulary ID may be different from one farmOS system to another, so if you
save it internally in your application, just know that it may not correspond to
the same vocabulary on another farmOS. Therefore it is recommended that you look
it up each time you need it.

For example, to get the `vid` that corresponds to the `farm_crops` vocabulary:

    curl [AUTH] [URL]/taxonomy_vocabulary.json?machine_name=farm_crops

Or, by loading the information in `farm.json` and parsing out the vocabulary ID:

    curl [AUTH] [URL]/farm.json

Before you create a new term, you should check to see if it already exists:

    curl [AUTH] [URL]/taxonomy_term.json?bundle=farm_crops&name=Broccoli

Then, you can create a new term (replace `[VID]` with the ID of the vocabulary
the term should be added to):

    curl [AUTH] -X POST -H 'Content-Type: application/json' -d '{"name": "Broccoli", "vocabulary": "[VID]"}' [URL]/taxonomy_term

**Creating an area**

Areas are taxonomy terms in farmOS, but they have some additional fields on them
for mapping purposes. In order for an area to show up on a map, it must have an
`area_type` set, and a geometry in the `geofield`.

A very basic area record looks like this:

    {
      "name": "North field",
      "description": "",
      "area_type": "field",
      "geofield": [
        {
          "geom": "POINT(-31.04003861546518 39.592143995003994)",
        },
      ],
    }

The geometry should be in [Well-Known Text].

Some available area types include:

* `field`
* `bed`
* `building`
* `greenhouse`
* `property`
* `paddock`
* `water`
* `landmark`
* `other`

Note that area types are defined by modules (eg: `paddock` is provided by the
`farm_livestock` module), so the area types available on your farmOS may vary.

You can fetch all areas of a particular type (eg: `field`) like this:

    curl [AUTH] [URL]/taxonomy_term.json?bundle=farm_areas&area_type=field

## Updating records

Records can be updated with a `PUT` request of JSON/XML objects to the record's
endpoint (with ID). Only include the fields you want to update - everything
else will be left unchanged.

Do not add `.json` or `.xml` to the endpoint's URL. Instead, add a
`Content-Type` header that specifies either `application/json` or
`application/xml`.

For example, to change the name of log 1:

    curl -X PUT [AUTH] -H 'Content-Type: application/json' -d '{"name": "Change the log name"}' [URL]/log/1

Areas will also have the following special-purpose fields, which are computed
based on log history. These fields cannot be written to directly, they can only
be changed via logs.

* `assets`: An array of references to assets that are currently located in the
  area, based on asset movement logs.

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

Available `measure` options are:

* `count`
* `length`
* `weight`
* `area`
* `volume`
* `time`
* `temperature`
* `value`
* `rate`
* `rating`
* `ratio`
* `probability`

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
[Creating taxonomy terms]: #creating-taxonomy-terms
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
[OAuth 2.0 standards]: https://oauth.net/2/
[OAuth2 Grant Types]: https://oauth.net/2/grant-types/
[oauth2_server]: https://www.drupal.org/project/oauth2_server
[simple_oauth]: https://www.drupal.org/project/simple_oauth/