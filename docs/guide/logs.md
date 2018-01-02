# Logging events

Logs are records of all kinds of events. You can be as granular as you want: the
more information you're recording, the more you can look back on and learn from
in the future.

<video width="100%" controls>
  <source src="/demo/logs.mp4" type="video/mp4">
</video>

## Calendar

In addition to the standard lists by log type, you can also view all logs in
a calendar format. This is available via the main menu, next to "My Account",
or directly at `farm/calendar`. Month, week, day, and year views are all
available.

## Standard log types

There are a number of different type of logs in farmOS - each with it's own
purpose and set of fields. Some of the general log types are described below,
but there are also other log types that pertain to specific [asset types].

### Activities

Activities are a sort of catch-all, or default, log type, which can be used for
general planning and recordkeeping of activities that don't fit any of the
other, more specific, log types.

### Observations

Observations are used to record any kind of passive observation on the farm. For
example, seeing that a planting has germinated is an observation. This is a very
flexible log type that can be used for a lot of different things. It comes with
it's own "Observation Type" vocabulary for defining your own custom
categorizations.

### Inputs

Input logs are used to record resources that are put into an asset. Fertlizer
(for plantings) or feed (for animals) can be recorded with input logs.

### Harvests

Harvest logs are used to record harvests.

## Other log types

In addition to the standard log types described above, there are a few other
types provided for specific purposes. Note that these log types are provided by
modules that may not be turned on by default in your farmOS. If you do not see
these types in your farmOS, turn on the applicable module (or ask you farmOS
host to do so for you).

### Soil tests

Soil test logs can be used to record when you have a soil test performed. They
can be linked to a specific field or area, and you can specify the exact points
on a map where samples were taken from. Integration is also provided with the
US NRCS Soil Survey API, which allows you to view soil type map overlays, as
well as look up soil name for the specific sample points in your soil tests.

<video width="100%" controls>
  <source src="/demo/soil.mp4" type="video/mp4">
</video>

### Water tests

Water test logs can be used to record when you have a water test performed.
Similar to soil test logs, they can be linked to a specific field or area, and
you can specify the lab that performed the test. Some regulatory guidelines
require that water tests are taken for both field and pack house water sources.

### Sales

Sale logs provide the ability to record the sale of specific assets. You can
specify quantity sold, unit price, total price, customer, and invoice number.
Sales logs can only represent the sale of a single item, and are not intended
for use as multi-item invoices. The primary purpose of sale logs is to connect
the final dots for food traceability.

## Log features

### Task assignment

Logs can be assigned to one or more person(s) in farmOS using the log's *Owner*
field. Users can view a list of all logs assigned to them by clicking
"My Account" in the menu and then selecting the "Logs" tab of their user
profile.

### Categorization

Logs can be assigned to one or more categories. A set of pre-defined categories
are provided by farmOS, and more can be added by users. Log categories enable
you to organize, sort, and filter your logs in ways that make sense to you, so
you can find the logs you need easily in the future.

[asset types]: /guide/assets

