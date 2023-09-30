# Data Streams

Data Streams are records that represent sets of data streamed from sensors or
other real-world devices.

## Type

Each Data Stream must have a type. All Asset types have a common set of
attributes and relationships. Specific Data Stream types may also add
additional attributes and relationships (collectively referred to as "fields").
Data Stream types are defined by modules, and are only available if their
module is enabled. The modules included with farmOS define the following Data
Stream types:

- Basic
- Listener (Legacy)

## ID

Each Data Stream will be assigned two unique IDs in the database: a universally
unique identifier (UUID), and an internal numeric ID.

The UUID will be unique **across** farmOS databases. The internal ID will only
be unique to a **single** farmOS database. Therefore, the farmOS API uses UUIDs
to ensure that IDs pulled from multiple farmOS databases do not conflict.
Internally, farmOS modules use the internal IDs to perform CRUD operations.

## Attributes

Data Streams have a number of attributes that serve to describe their meta
information. All Data Stream have the same standard set of attributes. Modules
can add additional attributes.

### Standard attributes

Attributes that are common to all Data Stream types include:

- Name
- Private key
- Public

#### Name

Data Streams must have a name that describes them. The name is used in lists of
Data Streams to easily identify them at quick glance.

#### Private key

A Data Stream's private key is the password used to post data to (and get data
from) its API endpoint.

#### Public

A Data Stream may be marked as "public" to allow read-access from the API
without a private key. This is useful for loading data into public
third-party apps or scripts, for graphing or other purposes. Data Streams
are not public by default.

## Relationships

Data Streams can be related to other records in farmOS These relationships are
stored as reference fields on Data Stream records.

All Data Streams have the same standard set of relationships. Modules can add
additional relationships.

Relationships that are common to all Data Streams types include:

- Assets

#### Assets

Data Streams can reference one or more Assets to indicate that the data they
collect is directly relevant to them.

For example, if a soil moisture sensor that is installed in a field can be
represented with a Sensor Asset (for the soil moisture sensor device itself),
with a Data Stream (soil moisture data readings), that reference a Land Asset
(the field it is installed in). This makes it possible for a single device to
be moved/reused for monitoring multiple Assets, by moving the Sensor Asset
and creating new Data Streams.

## Type-specific fields

In addition to the fields that are common to all Data Stream types described
above, some types add additional type-specific fields. These include:

#### Basic Data Streams

Basic Data Streams do not define any type-specific fields.

#### Listener (Legacy) Data Streams

Listener (Legacy) Data Streams have an additional "public key" attribute,
which is used in the Data Stream's API endpoint for posting/getting data. This
was used in farmOS v1 to provide a unique ID for the sensor, separate from the
Sensor Asset ID that housed the data. This is no longer needed in farmOS v2+,
because each Data Stream has its own UUID, which is used in the API endpoints
instead. The public key is retained for Legacy (Listener) Data Streams to
ensure that existing sensors can continue to push data without needing to be
reconfigured.
