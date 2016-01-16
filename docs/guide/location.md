# Movements and location

The locations of all assets in farmOS are determined by "Movement" logs.

When you create an asset, it will not be located anywhere until a movement log
is added to it.

farmOS determines the "current location" of an asset by looking at the asset's 
most recent movement log (with a date less than or equal to the present moment).
Every movement has a "From" and a "To" field on it (only the "To" field is
required), and whatever area is referenced in the "To" field is considered to be
the asset's current location. Only movement logs that have been marked as "done"
are taken into consideration.

## Creating movement logs

There are three ways that movement logs can be created:

1. Click "Add a Movement" when you are viewing a single asset. This will present
   you with a new movement log form, and automatically fill in the "Assets"
   field with the asset you were looking at.
2. Select multiple assets in a list, and click the "Move" button at the bottom.
   This allows you to move multiple assets at once. Similarly, this will present
   you with a new movement log form, and automatically fill in the "Assets"
   field with the assets you selected in the list.
3. Click "Add a Movement" from the farmOS dashboard. This presents you with a
   blank movement log form, which you can fill in however you'd like.

There is also a shortcut: when you are editing an asset, you will see a field
labeled "Location". This field will show the asset's current location, and if
you change it a new movement log will automatically be created in the background
for you. Doing this will set the date of the movement log to the moment you
click "Save", and the movement will be marked "done" immediately.

## Movement log fields

Here is a quick summary of the fields on a movement log:

* **Date** - (required) When the movement took place, or when it will take place.
* **Assets** - (required) Specify the assets that are being moved.
* **From** - (optional) Allows you to reference a specific area that assets are
  moving FROM. This is optional, and in some cases it's filled in automatically
  for you. It is primarily intended for more accurate historical records, and to
  help identify inconsistencies or missing data.
* **To** - (required) This is the most important field on the movement log. The
  area that is referenced with this field will be considered the asset's
  location.
* **Geometry** - (optional) Similar to [Farm Areas], movement logs have a
  geometry field which can be used to describe exactly where the assets are
  being moved to. If you leave this blank, the geometry will be automatically
  copied from the area referenced in the "To" field (if available).

[Farm Areas]: /guide/mapping

