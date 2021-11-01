# farmOS Data Model

## Goals

The goal of the farmOS data model is to represent and organize a wide variety
of agricultural and ecological systems to allow for easy access, analysis, and
interoperability. This ranges from small-scale garden records to industrial
agriculture to national forestry management.

farmOS can model data that was recorded via manual data entry, as well as data
streams from sensors or other applications.

## Record types

Data is organized into a set of high-level record types. The two primary record
keeping data types are **Assets** and **Logs**. Other types include
**Quantities**, **Terms**, **Plans**, and **Users**.

- [Assets](/model/type/asset)
- [Logs](/model/type/log)
- [Quantities](/model/type/quantity)
- [Data streams](/model/type/data_stream)
- [Files](/model/type/file)
- [Terms](/model/type/term)
- [Plans](/model/type/plan)
- [Users](/model/type/user)

## Logic

- [Location](/model/logic/location)
- [Group membership](/model/logic/group)
- [Inventory](/model/logic/inventory)

## Conventions

Beyond the record types that farmOS provides, it is helpful to develop
conventions around how they are used.

[farmOS Data Conventions](/model/convention)
