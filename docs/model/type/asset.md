# Assets

Assets represent the things that are being tracked or managed. Land, plants,
animals, and equipment are all types of Assets. Modules can provide additional
Asset types.

Assets are generally limited in the information they contain. Most of the
valuable historical information about an Asset will be stored in **Logs** that
reference it.

## Type

Each Asset must have a type. All Asset types have a common set of attributes
and relationships. Specific Asset types may also add additional attributes and
relationships (collectively referred to as "fields"). Asset types are defined
by modules, and are only available if their module is enabled. The modules
included with farmOS define the following Asset types:

- Land
- Plant
- Animal
- Equipment
- Compost
- Structure
- Sensor
- Water
- Material
- Product
- Group&ast;

&ast;Group Assets are unique in that they can "contain" other Assets as "group
members". This is a flexible feature that can be used for many purposes. One
typical use case is representing "herds" of Animal Assets. Group membership
changes are recorded via Logs (similar to location changes), so it is possible
to see all the Groups that an Asset was a member of in the past, when/why they
were moved, etc. See [farmOS Group Membership Logic](/model/logic/group) for
more information.

## ID

Each Asset will be assigned two unique IDs in the database: a universally unique
identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Assets have a number of attributes that serve to describe their meta information.
All Assets have the same standard set of attributes. Modules can add additional
attributes.

### Standard attributes

Attributes that are common to all Asset types include:

- Name
- Status
- Flags
- Geometry
- Intrinsic geometry
- Is location
- Is fixed
- Notes
- ID Tags
- Data

#### Name

Assets must have a name that describes them. The name is used in lists of Assets
to easily identify them at quick glance.

#### Status

Assets can be marked as "active" or "archived" to indicate their status.
Archived Assets will be hidden from most lists in farmOS unless they are
explicitly requested.

#### Flags

Flags can be added to Assets to help with sorting and filtering. farmOS
provides a set of default flags, including "Priority", "Needs review", and
"Monitor". Modules can provide additional flags, such as "Organic".

#### Geometry

The geometry of an Asset describes where it exists at a given point in time.
An Asset can either be "fixed", or it can be moved around via movement Logs.
This geometry field is not editable itself, but is rather computed based on the
[farmOS Location Logic](/model/logic/location).

See related fields "Intrinsic geometry" and "Is fixed" below.

#### Intrinsic geometry

If an Asset is "fixed" in location (see "Is fixed" below), then it can have an
"intrinsic geometry" to describe where it exists. This is only used if the
Asset is designated as "fixed". Otherwise, its geometry and location are
determined by movement Logs.

For more information, see [farmOS Location Logic](/model/logic/location).

#### Is location

An Asset can be designated as a "location" to indicate that other Assets may be
moved to it using movement Logs.

For more information, see [farmOS Location Logic](/model/logic/location).

#### Is fixed

Assets can be designated as "fixed" to indicate that they do not move around
in space and time. Examples of fixed Assets include land, buildings, water
sources, fixed infrastructure such as pivot irrigation systems, etc. If an
Asset is fixed, then it can have "intrinsic geometry" (see "Intrinsic geometry"
above). Otherwise, its geometry and location can change over time, as
determined by movement Logs.

For more information, see [farmOS Location Logic](/model/logic/location).

#### Notes

Notes can be added to an Asset to describe it in more detail. This is a
freeform text field that allows a limited set of HTML tags, including links,
lists, blockquotes, emphasis, etc.

#### ID Tags

Often an Asset will have ID tags associated with it. For example, an animal may
have an RFID collar or an ear tag with a unique ID. ID tags in farmOS can
store this ID, as well as its type and location.

#### Data

Assets have a hidden "data" field on them that is only accessible via the API.
This provides a freeform plain text field that can be used to store additional
data in any format (eg: JSON, YAML, XML). One use case for this field is to
store remote system IDs that correspond to the Asset. So if the Asset is
created or managed by software outside of farmOS, it can be identified easily.
It can also be used to store additional structured metadata that does not fit
into the standard Asset attributes.

### Additional attributes

Assets *may* contain additional attributes:

- Inventory

#### Inventory

The inventory attribute summarizes current Asset inventory levels. This field
is not editable itself, but is rather computed based on "inventory adjustment"
logs. Each inventory can have a "measure", "value", and "units".

For more information, see [farmOS Inventory Logic](/model/logic/inventory).

This field is added to all Asset types by default only if the Inventory module
is enabled.

## Relationships

Assets can be related to other records in farmOS These relationships are
stored as reference fields on Asset records.

All Assets have the same standard set of relationships. Modules can add
additional relationships.

Relationships that are common to all Asset types include:

- Location
- Parents
- Owners
- Images
- Files

#### Location

Similar to the Asset "Geometry" field described above, an Asset's location
describes where it is. Whereas the "Geometry" field contains raw geometry
data (points, lines, and polygons), this field is a reference to one or more
other Assets, which themselves are designated as "locations" (see "Is location"
attribute above). If an Asset is designated as "fixed" then it can have an
"intrinsic geometry" (see "Intrinsic geometry" above), but it will not have a
location.

For more information, see [farmOS Location Logic](/model/logic/location).

#### Parents

Assets can specify "Parent" Assets that they descend from. This creates a
lineage relationship that can be used to track breeding of Plant and Animal
Assets. It can also be used to create more general hierarchical relationships
between Assets such as representing a "bed" inside a "field" (represented by
two Land Assets related through the Parents field).

Multiple parents are allowed, but circular relationships are not.

#### Owners

Assets can be assigned to one or more Users in farmOS.

#### Images

Images can be attached to Assets. This provides a place to store photos of the
Asset.

#### Files

Files can be attached to Assets. This provides a place to put documents such as
Shapefiles, PDFs, CSVs, or other files associated with the Asset.

### Additional relationships

Assets *may* contain additional relationships:

- Group membership

#### Group membership

The group membership of an Asset references Group Assets which the Asset is a
member of. This field is not editable itself, but is rather computed based on
"group assignment" logs.

For more information, see [farmOS Group Membership Logic](/model/logic/group).

This field is added to all Log types by default only if the Group module is
enabled.

## Type-specific fields

In addition to the fields that are common to all Asset types described
above, some types add additional type-specific fields. These include:

#### Animal Assets

Animal Assets have the following additional attributes:

- Birthdate (timestamp)
- Is castrated (boolean)
- Nicknames (multiple strings)
- Sex ("F" or "M" string)

And the following additional relationships:

- Animal type (References a Term in the "Animal type" vocabulary)

#### Equipment Assets

Equipment Assets have the following additional attributes:

- Manufacturer (string)
- Model (string)
- Serial number (string)

#### Land Assets

Land Assets have the following additional attributes:

- Land type (string)

#### Material Assets

Material Assets have the following additional relationships:

- Material type (references a Term in the "Material type" vocabulary)

#### Plant Assets

Plant Assets have the following additional relationships:

- Plant type (references a Term in the "Plant type" vocabulary)
- Season (references a Term in the "Season" vocabulary)

#### Product Assets

Product Assets have the following additional relationships:

- Product type (references a Term in the "Product type" vocabulary)

#### Sensor Assets

Sensor Assets have an additional "Data streams" relationship, which is used to
reference [Data Streams](/model/type/data_stream) associated with the sensor.

#### Structure Assets

Structure Assets have the following additional attributes:

- Structure type (string)
