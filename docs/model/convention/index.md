# Conventions

## In theory

The farmOS data model describes basic record types that can be used to
represent a farming system, but *how* those record types are used is left up to
the user in large part.

It is not the data model's responsibility to enforce an opinionated standard
for all data that it stores. Instead, it aims to provide the flexibility for
these standards to be developed through a collaborative community effort on top
of the data model over time. These standards are collectively referred to as
"conventions".

Conventions are not part of the data model itself, but understanding how they
relate to and are built on top of the model helps develop good record keeping
habits, which result in more consistent and comparable data.

One of the longer term goals of farmOS is to be a platform that supports the
collaborative development of these conventions over time. As new standards
are developed and adopted in the community, they can be written into modules
that provide different levels of "enforcement of" or "compliance to" these
conventions.

The data model, combined with established conventions, and tools like the
[farmOS Aggregator](https://github.com/farmOS/farmOS-aggregator) can enable a
wide variety of data sharing, aggregating, and reporting use-cases.

## In practice

Within an individual farmOS instance, you may develop your own conventions
around naming your Assets. Or you may develop a standard operating procedure
for certain types of common data entry, to ensure that it always goes into
farmOS in the same manner and format.

Across separate farmOS instances, developing shared conventions enables data to
be more easily aggregated and compared at larger scales.

Some ways in which conventions are already being developed include:

- Quick forms, surveys, and other data entry tools that collect specific
  information and store it consistently in Logs.
- Reports that query the database for Logs that match a certain convention
  and summarize them in different ways.
