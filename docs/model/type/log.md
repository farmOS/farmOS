# Logs

Logs represent events, both active and passive. Observations, harvests, and
inputs are all types of Logs. Modules can provide additional Log types.

## Type

Each Log must have a type. All Log types have a common set of attributes and
relationships. Specific Log types may also add additional attributes and
relationships (collectively referred to as "fields"). Log types are defined by
modules, and are only available if their module is enabled. The modules
included with farmOS define the following Log types:

- Activity
- Observation
- Input
- Harvest
- Lab test
- Maintenance
- Medical
- Seeding
- Transplanting

## ID

Each Log will be assigned two unique IDs in the database: a universally unique
identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Logs have a number of attributes that serve to describe their meta information.
All Logs have the same standard set of attributes. Modules can add additional
attributes.

### Standard attributes

Attributes that are common to all Log types include:

- Name
- Timestamp
- Status
- Flags
- Geometry
- Is movement
- Notes
- Data

#### Name

Logs must have a name that describes them. This will be automatically generated
using a type-specific naming pattern if the Log is saved with an empty name.
The name is used in lists of Logs to easily identify them at quick glance.

#### Timestamp

Logs always have a timestamp which indicates when they took place.

#### Status

Logs can be marked as "pending" or "done", to indicate whether they are
"planned" or "actual" events. Every change that is made to a Log is stored as
a revision, so that it's possible to see how a plan evolves over time until it
eventually becomes a canonical record of the event that took place.

#### Flags

Flags can be added to Logs to help with sorting and filtering. farmOS provides
a set of default flags, including "Priority", "Needs review", and "Monitor".
Modules can provide additional flags, such as "Organic".

#### Geometry

Geometry data can be added to a Log to describe where it took place using
points, lines, and polygons.

When combined with the "Is movement" attribute (below), this will update the
computed geometry of all Assets referenced on the Log.

See the "Geometry" attribute of [Assets](/model/type/asset#geometry).
For more information, see [farmOS Location Logic](/model/logic/location).

#### Is movement

A Log can be designated as a "movement" to indicate that any Assets referenced
by it are being moved to the specified locations and/or geometry (see
"Geometry" above and "Locations" below).

If a Log is designated as a movement, and no location or geometry are defined,
then the referenced Assets will no longer have a location or geometry.

For more information, see [farmOS Location Logic](/model/logic/location).

#### Notes

Notes can be added to a Log to describe it in more detail. This is a freeform
text field that allows a limited set of HTML tags, including links, lists,
blockquotes, emphasis, etc.

#### Data

Logs have a hidden "data" field on them that is only accessible via the API.
This provides a freeform plain text field that can be used to store additional
data in any format (eg: JSON, YAML, XML). One use case for this field is to
store remote system IDs that correspond to the Log. So if the Log is created
or managed by software outside of farmOS, it can be identified easily. It can
also be used to store additional structured metadata that does not fit into
the standard Log attributes.

### Additional attributes

Logs *may* contain additional attributes:

- Is group assignment

#### Is group assignment

A log can be designated as a "group assignment" to indicate that any Assets
referenced by it are being assigned to the referenced Group Assets (see
"Groups" below).

If a Log is designated as a group assignment, and no Group Assets are referenced,
then the referenced Assets will no longer be members of a group.

For more information, see [farmOS Group Membership Logic](/model/logic/group).

## Relationships

Logs can be related to the **Assets** and location(s) they pertain to. They can
have quantitative data via related **Quantities**. They can be assigned to the
**Users** who are responsible for them. And they can be organized using
**Terms** and other metadata.

These relationships are stored as reference fields on Logs. References are
uni-directional, meaning that Logs reference Assets, but Assets do not
reference Logs. It is possible to retrieve all Logs that reference a particular
Asset, as well as retrieve all Assets referenced by a Log.

All Logs have the same standard set of relationships. Modules can add
additional relationships.

### Standard relationships

Relationships that are common to all Log types include:

- Assets
- Locations
- Quantities
- Owners
- Categories
- Images
- Files

#### Assets

Logs can specify which Assets they pertain to. Over time, this builds a rich
historical record of everything that has happened to a particular Asset.

#### Locations

Logs can reference Assets that are designated as "locations" to indicate where
they took place.

This differs from the Assets relationship described above. The distinction is:

- The "Asset" relationship means "this happened *TO* this Asset".
- The "Location" relationship means "this happened *IN* this Asset".

This distinction is important, and it makes intuitive sense for many use-cases.
For example, if you are creating a Maintenance Log, you can say that it was
maintenance applied to a tractor Asset (referenced in the "Asset" field), and
it was performed in a barn Asset (referenced in the "Location" field).

However, some cases are less intuitive - specifically when you want to represent
an action that is being performed directly to Assets that are designated as
locations.

For example: if you are applying an input to a pasture (represented as an Asset
of type "Land"), then the action is happening both *TO* the pasture and *IN*
the pasture. In this case, the pasture Asset can be referenced in either or both
the "Asset" and "Location" fields. This decision is left up to the
[convention](/model/convention) of the end-user or module that implements it.

When combined with the "Is movement" attribute (above), this will move all Assets
referenced on the Log to the locations specified. For more information, see
[farmOS Location Logic](/model/logic/location).

See also: the "Is location" attribute of [Assets](/model/type/asset#is_location).

#### Quantities

Quantities are records that contain quantitative data. These are used to
represent things like input amounts, harvest totals, time tracking, etc. For
more information, see: [Quantities](/model/type/quantity).

#### Owners

Logs can be assigned to one or more Users in farmOS.

#### Categories

Logs can be assigned to one or more categories to help with sorting and
filtering.

Categories differ from the "Log Type" in a few ways:

- A Log can be in multiple categories.
- Categories of a Log can change.
- The same set of Categories is available across all Log types.
- Categories are optional, type is required.

Categories are therefore a more flexible and dynamic method of organizing
sets of Logs, regardless of type.

Categories differ from "Flags" in that categories only apply to Logs. Flags can
be applied to Assets, Plans, and Logs. Flags are intended to bring attention to
a Log, and are often highlighted in the UI. Flags may also be added and removed
from a Log (eg: adding/removing the "Needs review" flag), whereas categories
will tend to be fixed.

Flags are also more strictly defined and controlled than categories. Categories
are Terms in the "Log categories" vocabulary, so they can be added, edited,
deleted, and rearranged through the UI. Flags must be defined in code by a
module.

#### Images

Image can be attached to Logs. This provides a place to store photos associated
with the task.

#### Files

Files can be attached to Logs. This provides a place to put documents such as
Shapefiles, PDFs, CSVs, or other files associated with the task.

### Additional relationships

Logs *may* contain additional relationships:

- Equipment used
- Groups

#### Equipment used

Logs can specify which Equipment Assets were used to perform a task.

This differs from the Assets field (described above), which is for referencing
the Assets that were the focus of the task. For example, if a Tractor is used
to cultivate a field, the "Equipment used" field would reference the Tractor,
and the "Assets" field would reference a Land Asset representing the field.

This field is added to all Log types by default only if the Equipment module is
enabled.

#### Groups

Logs can reference Group Assets, along with the "Is group assignment" attribute
(above) to indicate which group(s) the Assets will be members of.

This field is added to all Log types by default only if the Group module is
enabled.

For more information, see [farmOS Membership Logic](/model/logic/group).

## Type-specific fields

In addition to the fields that are common to all Log types described
above, some types add additional type-specific fields. These include:

#### Harvest Logs

Harvest Logs have the following additional attributes:

- Lot number (string)

#### Input Logs

Input Logs have the following additional attributes:

- Lot number (string)
- Method (string)
- Purchase date (timestamp)
- Source (string)

#### Lab Test Logs

Lab Test Logs have the following additional attributes:

- Laboratory (string)
- Test type (string)

#### Medical Logs

Medical Logs have the following additional attributes:

- Veterinarian (string)

#### Seeding Logs

Seeding Logs have the following additional attributes:

- Lot number (string)
- Purchase date (timestamp)
- Source (string)
