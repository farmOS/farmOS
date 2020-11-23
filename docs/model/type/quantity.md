# Quantities

A Quantity in farmOS is a granular unit of quantitative data used to represent
a single data point.

Quantities do not exist on their own, but rather are created with and are
referenced by [Logs](/model/type/log). Logs provide the supporting metadata to
give context to the data stored in Quantities, including timestamp, location,
relation to [Assets](/model/type/asset), etc.

## Type

Currently there is only one type of Quantity.

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

## Relationships

All Quantities have the same standard set of relationships. Modules can add
additional relationships.

### Standard relationships

Relationships that are common to all Quantity types include:

- Unit

#### Unit

The Unit of measurement is stored as a [Term](/model/type/term) in the Units
vocabulary.
