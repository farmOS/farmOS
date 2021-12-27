# Logging events

Logs represent all kinds of events. You can be as granular as you want: the
more information you're recording, the more you can look back on and learn from
in the future.

Planning ahead in farmOS is exactly the same as recording things that already
happened. The only difference is that the date is in the future, and the Log
status is "pending" instead of "complete".

Logs that are in the future and "pending" will appear in your "Upcoming Tasks"
list on the dashboard. Underneath that is a "Late tasks" list, which shows all
"pending" Logs with a date in the past.  So as time goes by, you can mark your
Logs as done (or not), and it will keep track of what happened and what didn't.

Logs can be assigned to one or more person(s) in farmOS using the Log's *Owner*
field. Users can view a list of all Logs assigned to them by navigating to their
profile and then clicking the "Logs" tab.

Logs can be also be categorized for organizing, sorting, and filtering your
Logs in ways that make sense to you, so you can find the Logs you need easily
in the future.

If you want to create a Log that references multiple Assets, you can either
create the Log and then add each Asset to it individually, or you can select
multiple Assets in a list and click the "Add log" button that appears at the
bottom. This will open up a new Log form with the Assets pre-selected.

For more information on Log records, refer the [Logs](/model/type/log)
section of the [farmOS data model](/model) docs.

## Log types

farmOS comes with a number of Log types, summarized below. Additional types can
be added via modules.

### Activities

Activities are a sort of catch-all, or default, Log type, which can be used for
general planning and record keeping of activities that don't fit any of the
other, more specific, Log types.

### Observations

Observations are used to record any kind of passive observation on the farm. For
example, recording that a planting has germinated is an observation. This is a
very flexible Log type that can be used for a lot of different things.

### Inputs

Input Logs are used to record resources that are put into an Asset. Fertilizer
(for plants) or feed (for animals) can be recorded with input Logs.

### Harvests

Harvest Logs are used to record harvests taken from an Asset.

In some cases, you may want to archive the Asset when a harvest is recorded
(eg: harvesting a crop or slaughtering an animal). In other cases, one Asset
may produce multiple harvests (eg: picking apples or collecting eggs).

### Seedings

Seeding Logs are used to represent when seeds are planted in the ground or in
containers. With a seeding Log, you can specify the seeding quantity (eg: 100
lbs, 20 72-plug trays, etc), and you can specify where the seeding occurred so
that farmOS knows where the plant Asset is located.

### Transplantings

Transplanting Logs are used to represent when a plant Asset is moved from one
place to another. For example, when vegetable starts are moved from a greenhouse
to the field, or when tree saplings are purchased from a nursery and planted in
the ground. Similar to seeding Logs, transplantings can have a quantity and a
location.

### Lab tests

Lab test logs can be used to record when/where you have samples analyzed by a
lab, and the results can be attached as a file and/or recorded as
[Quantity](/guide/quantities) measurements on the Log.

These can be used for tracking soil tests, water tests, plant tissue analysis,
etc. Logs can be linked to a specific land or water Asset, and you can specify
the exact points on a map where samples were taken from if you want, as well as
the name of the lab that performed the analysis.

### Maintenance

Maintenance Logs can be used to record when you perform maintenance on a piece
of equipment, structure, or other Asset type. This can be a repair, a tune-up,
an oil change, a cleaning, or anything else that is related to the proper use
and functioning of the Asset. All your maintenance records can be organized,
categorized, and filtered like other Log types in farmOS.

### Medical

Medical Logs can be used to record animal health records. This can be a
veterinary visit, administering medicine/vaccinations, or other medical
procedures. You can also use standard **Input** Logs when administering
medicine or vaccinations, if you prefer, and reserve **Medical** Logs for more
serious events/procedures.

### Births

Birth Logs can be used to record the birth of one or more animals on the farm.
Birth Logs can optionally reference the mother animal, and when they are saved
they will automatically update the "Parents" and "Date of birth" fields on all
referenced child animals. The "Date of birth" field on animal records will
automatically link back to their birth Log (if one exists). The child animal
Asset records must be created before the birth Log, so that they can be
referenced.
