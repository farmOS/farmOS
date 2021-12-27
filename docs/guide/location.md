# Movements and location

The locations of all Assets in farmOS are determined by "Movement" fields on
Log entries.

When you create an Asset, it will not be located anywhere until a Log is added
that "moves" it somewhere. These Logs are referred to as "movement" Logs, even
though they are actually a more specific Log type like "Activity",
"Observation", "Seeding", etc. Any Log type can be used to move Assets.

farmOS determines the "current location" of an Asset by looking at the Asset's
most recent movement Log (with a date less than or equal to the present moment).
Only Logs that have been marked as "complete" are taken into consideration.

Every Log has a "Location" reference field (for referencing the location Asset
that things are moving to), a "Geometry" field (for recording the precise
geometry where things are moving), and an "Is movement" field (for designating
the Log as a movement).

For more information on Asset location, refer to the
[location logic](/model/logic/location) section of the
[farmOS data model](/model) docs.

## Movement Logs

There are three ways that movement Logs can be created:

- Click "Add an activity" (or other Log type) when you are viewing a single
  Asset. This will present you with a new Log form, and automatically fill in
  the "Assets" field with the Asset you were looking at. In the "Location"
  field, select the new location of the Assets, and check the "Is movement"
  box to designate the Log as a movement.
- Select multiple Assets in a list, and select the "Move assets" option that
  appears. This allows you to move multiple Assets at once. This will present
  you with a simplified form for specifying details of the movement.
- Click "Add Log" from the farmOS dashboard, and select a Log type. This
  presents you with a blank Log form, which you can fill in however you'd like.
  Use the "Location", "Geometry", and "Is movement" fields as described above.
