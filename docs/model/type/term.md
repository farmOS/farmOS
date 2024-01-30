# Terms

Vocabularies (also referred to as taxonomies or ontologies) are used to
organize and manage Terms used in various contexts throughout farmOS. These can
be used for flagging, categorization, and organization of other record types.

Most Terms in farmOS are user-defined, and the vocabularies are empty when
farmOS is first installed. As [Logs](/model/type/log) and
[Assets](/model/type/asset) are created, Terms that are used to describe them
are automatically generated in the appropriate vocabularies. These can then be
used to filter records in the future.

## Type

Each term must have a type, which is a reference to the vocabulary that it is
in. Vocabularies are defined by modules, and are only available if their module
is enabled. The modules included with farmOS define the following vocabularies:

- Animal type
- Log category
- Material type
- Plant type
- Product type
- Season
- Unit

## ID

Each Term will be assigned two unique IDs in the database: a universally unique
identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Terms have a number of attributes that serve to describe their meta information.
All Terms have the same standard set of attributes. Modules can add additional
attributes.

### Standard attributes

Attributes that are common to all Term types include:

- Name
- Description
- Weight

#### Name

Terms must have a name.

#### Description

Optionally, Terms may have a description.

#### Weight

The Term's weight determines how it is ordered in the vocabulary. See related
"Parent" relationship below for organizing Terms into a hierarchy.

## Relationships

All Terms have the same standard set of relationships. Modules can add
additional relationships.

### Standard relationships

Relationships that are common to all Term types include:

- Parent

#### Parent

Terms can specify "Parent" Terms to create a hierarchy. See related "Weight"
attribute above for ordering Terms within the hierarchy.

## Type-specific fields

In addition to the fields that are common to all Term types described
above, some types add additional type-specific fields. These include:

#### Plant Type Terms

Terms in the "Plant type" vocabulary have the following additional attributes:

- Days to maturity (Integer)
- Days to transplant (Integer)

And the following additional relationships:

- Companions (references other Terms in the "Plant type" vocabulary)
- Crop family (references a Term in the "Crop Family" vocabulary)
- Images (references uploaded image files)
