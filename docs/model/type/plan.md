# Plans

Plans are higher-level records that organize Assets and Logs around a
particular goal. Modules can provide Plan types, along with additional UI and
logic for Assets and Logs that are managed by them.

## Type

Each Plan must have a type. All Plan types have a common set of attributes and
relationships. Specific Plan types may also add additional attributes and
relationships (collectively referred to as "fields"). Plan types are defined by
modules, and are only available if their module is enabled.

*farmOS core does not currently provide any Plan types.*

## ID

Each Plan will be assigned two unique IDs in the database: a universally unique
identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Plans have a number of attributes that serve to describe their meta information.
All Plans have the same standard set of attributes. Modules can add additional
attributes.

### Standard attributes

Attributes that are common to all Plan types include:

- Name
- Status
- Flags
- Notes
- Data

#### Name

Plans must have a name that describes them. The name is used in lists of Plans
to easily identify them at quick glance.

#### Status

Plans can be marked as "active" or "archived" to indicate their status.
Archived Plans will be hidden from most lists in farmOS unless they are
explicitly requested.

#### Flags

Flags can be added to Plans to help with sorting and filtering. farmOS
provides a set of default flags, including "Priority", "Needs review", and
"Monitor". Modules can provide additional flags, such as "Organic".

#### Notes

Notes can be added to a Plan to describe it in more detail. This is a freeform
text field that allows a limited set of HTML tags, including links, lists,
blockquotes, emphasis, etc.

#### Data

Plans have a hidden "data" field on them that is only accessible via the API.
This provides a freeform plain text field that can be used to store additional
data in any format (eg: JSON, YAML, XML). One use case for this field is to
store remote system IDs that correspond to the Plan. So if the Plan is
created or managed by software outside of farmOS, it can be identified easily.
It can also be used to store additional structured metadata that does not fit
into the standard Plan attributes.

## Relationships

Plans can reference other record types (like Assets and Logs) that are "part of
the Plan". These relationships can be simple (referencing the Asset/Log ID), or
a Plan-type providing module can define more complex relationships by including
other metadata alongside it.

For example, a Crop Plan might reference a set of Plant Assets that represent
the crops being grown in a particular season. Apart from just referencing the
Plant Asset IDs, a Crop Plan may also reference specific Seeding and/or
Transplanting Log IDs alongside those Plantings. It may also include attributes
that are specific to the particular planning process that is being modelled. In
addition to the Asset and Log IDs, perhaps a Crop Plan wants to store pieces of
information like "days to harvest" or "harvest window" for each Plant Asset.
These pieces of information do not belong on the Asset level itself, because
they are specific to the Plan&ast;. Therefore, they should be thought of as
metadata of the relationship itself.

Another example might be an Input Plan, that allows users to enter "Target" and
"Actual" values for the amounts of a material that was applied. This Plan could
create relationships to the Input Logs that it manages. The "Actual" quantity
of material applied would be stored on the Input Log itself, but the "Target"
is stored in the relationship between the Plan and the Log.

&ast; It is worth noting that some of this data may *also* be stored generally
on Terms, and copied to the Plan when it is instantiated. For example: imagine
a Crop/variety Term that has a "days to harvest" attribute on it, allowing you
to define this as a system-wide default that is then copied into your Crop Plan
when you create a new Plant Asset that references the Crop/variety Term. This
allows the value to be overridden on the Plan+Asset level, in case conditions
require it to.

### Standard relationships

All Plans have the same standard set of relationships. Modules can add
additional relationships.

Relationships that are common to all Plan types include:

- Files
- Images

#### Images

Images can be attached to Plans. This provides a place to store photos that can
be displayed alongside the Plan in farmOS. If the photos pertain to specific
Assets or Logs within the Plan, it may be better to attach them to those
records instead of the Plan.

#### Files

Files can be attached to Plans. This provides a place to put documents such as
Shapefiles, PDFs, CSVs, or other files associated with the Plan. If the files
pertain to specific Assets or Logs within the Plan, it may be better to attach
them to those records instead of the Plan.

### Additional relationships

Plans *may* contain additional relationships:

- Assets
- Logs

However, the module that is providing the Plan type may decide to replace these
with more advanced Asset/Log relationships if necessary. So the exact
relationships may vary by Plan type.

#### Assets

Plans can reference Assets that are part of the Plan.

#### Logs

Plans can reference Logs that are part of the Plan.
