# Exporting data

## CSV

All [Asset](/guide/assets) and [Log](/guide/logs) lists in farmOS provide an
"Export CSV" action that will generate a CSV of selected records. These include
most of the record's information, including columns that are not visible in the
list pages themselves.

[Quantity](/guide/quantities) lists provide an "Export CSV" link at the bottom of the page
that serve a similar purpose. These exports include all of the columns that are
visible on the Quantity list page, including information about the Quantity
itself, as well as some information about the Log records that the Quantity
is attached to.

Any sorts or filters that are applied to the list will be represented in the
CSV output.

**Warning: CSV exports do not include all data.**

The [farmOS API](/development/api) is the best way to get access to all raw data
in a farmOS instance.

## KML

The *farmOS KML* module provides an option for exporting the geometry of one or
more Assets or Logs. Open any list of Assets or Logs, select the ones you want
to include in your export, and select the "Export KML" option that appears at
the bottom. A new KML file will be generated with all the geometries that were
selected.
