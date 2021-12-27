# Quantities

All Logs in farmOS have the ability to record structured quantitative
measurements alongside other details. These can be used to collect data about
your farm activities in an organized way, which can be analyzed later to
provide insights.

Each Quantity consists of four optional sub-fields:

* Measure - What type of measurement is this? Eg: Weight, Volume, Count,
  Temperature, etc.
* Value - The measurement value (a number).
* Units - The unit of measure. This can be anything you like, but it's always
  good to keep your units consistent for later analysis.
* Label - Labels are just a text field that allow you to label the Quantity.
  This is helpful if you have multiple Quantities of the same measure or units.

A single Log in farmOS can have multiple Quantities, but only the first will be
included when viewing a list of Logs. In order to see a list of all Quantities,
go to Records > Quantities and optionally filter by Log name, type, date range,
or Quantity fields like measure, value, units, or label. A CSV file can be
exported from the filtered results. This provides a flexible approach to
gathering ongoing quantitative data on your farm, and then analyzing it for
trends over time.

For more information on Quantity records, refer the
[Quantities](/model/type/quantity) section of the [farmOS data model](/model)
docs.
