# Quantities

A Quantity in farmOS is a granular unit of quantitative data used to represent
a single data point.

Quantities do not exist on their own, but rather are created with and are
referenced by [Logs](/model/type/log). Logs provide the supporting metadata to
give context to the data stored in Quantities, including timestamp, location,
relation to [Assets](/model/type/asset), etc.

## Type

Each Quantity must have a type. All Quantity types have a common set of
attributes and relationships. Specific Quantity types (also called "bundles")
may also add additional attributes and relationships (collectively referred to
as "fields"). Quantity types are defined by modules, and are only available if
their module is enabled. The modules included with farmOS define the following
Quantity types:

- Standard
- Material
- Test

## ID

Each Quantity will be assigned two unique IDs in the database: a universally
unique identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Quantities have a number of attributes that serve to describe their meta
information. All Quantities have the same standard set of attributes. Modules
can add additional attributes.

### Standard attributes

Attributes that are common to all Quantity types include:

- Measure
- Value
- Label

#### Measure

The Measure attribute can be used to specify what type of measurement is being
recorded. The available options are:

- Count
- Length/depth
- Weight
- Area
- Volume
- Time
- Temperature
- Pressure
- Water content
- Value
- Rate
- Rating
- Ratio
- Probability

#### Value

The Quantity value is a decimal number. Internally this is represented as two
integers (numerator and denominator).

#### Label

A Quantity may have a label assigned to it. This helps to distinguish multiple
Quantities of the same measure within a Log.

### Additional attributes

Quantities *may* contain additional attributes:

- Inventory adjustment

#### Inventory adjustment

A Quantity can be designated as an "inventory adjustment" to reset, increment,
or decrement the inventory of Assets referenced (see "Inventory asset" below).

For more information, see [farmOS Inventory Logic](/model/logic/inventory).

## Relationships

All Quantities have the same standard set of relationships. Modules can add
additional relationships.

### Standard relationships

Relationships that are common to all Quantity types include:

- Unit

#### Unit

The Unit of measurement is stored as a [Term](/model/type/term) in the Units
vocabulary.

### Additional relationships

Quantities *may* contain additional relationships:

- Inventory asset

#### Inventory asset

Quantities can reference Assets, along with the "Inventory adjustment"
attribute (above) to record adjustments to the Asset's inventory.

This field is added to all Quantity types by default only if the Inventory
module is enabled.

For more information, see [farmOS Inventory Logic](/model/logic/inventory).

## Type-specific fields

In addition to the fields that are common to all Quantity types described
above, some types add additional type-specific fields. These include:

#### Standard Quantities

Standard Quantities do not define any type-specific fields.

#### Material Quantities

Material Quantities are the default type on Input logs. They have the
following additional relationships:

- Material type (References Terms in the "Material types" vocabulary)

#### Test Quantities

Test Quantities are the default type on Lab test logs. They have the following
additional relationships:

- Test method (References Terms in the "Test methods" vocabulary)
