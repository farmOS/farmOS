# Movements and location

The locations of all assets in farmOS are determined by "Movement" fields on
log entries.

When you create an asset, it will not be located anywhere until a log is added
that includes this movement information. These logs can be referred to as
"movement logs" even though they are actually an "Activity", "Observation", or
other log type.

farmOS determines the "current location" of an asset by looking at the asset's
most recent movement log (with a date less than or equal to the present moment).
Only logs that have been marked as "done" are taken into consideration.

Every movement has a "Movement To" field on it, which is required in order to
record a movement. It also includes an optional "Movement Geometry" field,
which can be used to specify a more specific location of the assets on a map.

## Creating movement logs

There are three ways that movement logs can be created:

1. Click "Add an activity" (or other log type) when you are viewing a single
   asset. This will present you with a new log form, and automatically fill in
   the "Assets" field with the asset you were looking at. In the "Movement"
   fieldset, select an area in the "Movement To" field to record a movement.
2. Select multiple assets in a list, and click the "Move" button at the bottom.
   This allows you to move multiple assets at once. Similarly, this will present
   you with a new activity log form, and automatically fill in the "Assets"
   field with the assets you selected in the list. Add an area to the "Movement
   To" field to record a movement.
3. Click "Add a log" from the farmOS dashboard, and select a log type. This
   presents you with a blank log form, which you can fill in however you'd like.
   Add an area to the "Movement To" field to record a movement.

If you leave an activity log name blank, and it includes movement information,
it will default the log name to "Move [asset] to [area]".

There is also a shortcut: when you are editing an asset, you will see a field
labeled "Location". This field will show the asset's current location, and if
you change it a new observation log will automatically be created when you save
the asset titled "Current location: [area]". Doing this will set the date of
the log to the moment you clicked "Save", and it will be marked "done"
immediately.

## Movement fields

Here is a quick summary of the fields in the "Movement" fieldset:

* **To** - (required) This is the most important field on a movement log. The
  area that is referenced with this field will be considered the asset's
  location.
* **Geometry** - (optional) movements can be defined with a more specific
  geometry on the map using this field. This can be useful for temporary
  locations (like a moveable fence) within a larger area. If you leave this
  blank, the geometry will be automatically copied from the area referenced in
  the "Movement To" field (if available).

[Farm Areas]: /guide/areas

