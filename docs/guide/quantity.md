# Quantity measurements

Most [logs] in farmOS have the ability to record structured quantity
measurements alongside other details. These can be used to collect data about
your farm activities in an organized way, which can be analyzed later to
provide insights.

Quantity measurements can be added via the "Quantity" field on logs. More than
one quantity measurement can be added to a single log.

The "Quantity" field consists of four optional sub-fields:

* Measure - What type of measurement is this? Eg: Weight, Volume, Count,
  Temperature, etc.
* Value - The measurement value (a number).
* Units - The unit of measure. This can be anything you like, but it's always
  good to keep your units consistent, as much as possible, for later analysis.
* Label - Labels are just a text field that allow you to add an additional note
  to the quantity measurement. This is helpful if you have multiple quantities
  of the same measure.

## Quantity report

A single log in farmOS can have multiple quantity measurements, but when
viewing a list of logs only the first quantity measurement will be displayed. A
special "Quantity Report" module is provided specifically  for querying logged
quantity measurements . This allows you to specify filter criteria and generate
a list of quantity measurements that can be viewed in farmOS or exported to a
CSV file. This provides a very flexible approach to gathering ongoing
quantitative data on your farm, and then analyzing it for trends over time.

This module is still in "beta", and more filters/capabilities are being added.
If you have ideas, please [create feature requests on GitHub]. It is not
enabled by default when farmOS is installed, so you need to turn it on in order
to use it.

[logs]: /guide/logs
[create feature requests on GitHub]: /community/contribute

