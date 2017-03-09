# Mapping your farm

farmOS gives you the ability to organize all of the various places on (and off)
your farm. These places are referred to as "Areas" in farmOS, and they can be
referenced when you are [logging events]. They are specifically useful in
movement logs for [setting location of assets].

To create an area, go to the Farm Dashboard and click the "Add an area" button.
This will take you to a form for defining an area. Here is a quick overview of
the fields available to you when you are describing an area:

* **Name** - The first (and only) thing an area needs is a name. All the other
  fields are optional.
* **Area type** - farmOS uses the concept of "area type" to organize and
  color-code areas on a map. Some of the types available include: Property,
  Building, Field, and Water. The area type field is not required, but if you do
  not use it then the area will not be displayed on the main map.
* **Geometry** - The Geometry field lets you draw your area on a map, using
  points, lines, and polygons (see a more detailed description below).
* **Description** - This is a simple text tield that you can use to describe
  each area in further detail. It can be used to take notes, but it is
  recommended that any activities be recorded using logs instead, because they
  have a timestamp associated with them.
* **Photo(s)** - This field lets you attach photos to your area.
* **Files** - This field lets you attach files to your area. You can also use
  this to upload KML files and automatically import them into the Geometry field
  above (see more information below).
* **Relations** - The "Parent" and "Weight" fields let you define a hierarchy
  and order to your areas. You can edit these fields individually on each area,
  or you can use the drag-and-drop hierarchy editor - which is much easier for
  moving a lot of areas around at once (more details below).

## Using geometry fields

Here are some common things you will do with the geometry fields in farmOS:

### Zooming

There are four ways to zoom in/out:

1. On touch screens, you can "pinch zoom" using two fingers.
2. With a computer mouse, you can use the scroll wheel to zoom in/out.
3. The plus (+) and minus (+) buttons in the top left of the map control zoom.
4. There is a "Geolocate" button in the upper left (looks like a bullseye) that
   will automatically zoom in to your current location. On a computer this will
   use your IP address, and on a mobile device it will use your GPS.

### Drawing

There are four buttons for drawing shapes:

1. **Point**: Click on the map to create a point.
2. **Line**: Create a series of line segments by clicking points on the map, and
   double-clicking when you're done. You can also hold shift to draw freehand.
3. **Circle**: Create a circle by clicking where you want the center to be,
   dragging the circle outward to expand it, and clicking again to finish.
4. **Polygon**: A polygon works the same as a line, except it will create a
   closed shape at the end, whereas a line will not be filled in.

<video width="100%" controls>
  <source src="/img/demos/farmOS-mapping-areas.mp4" type="video/mp4">
</video>

### Modifying

There are three buttons for modifying shapes:

1. **Edit**: Click the edit button, and then click a shape to select it. You can
   click and drag any of the vertices to modify the overall shape.
2. **Move**: Click the edit button, and then click a shape to select it. Then
   click and drag the shape to move it to a different position.
3. **Clear**: The clear button will clear ALL shapes from the map. If you do
   accidentally click this, refresh the page WITHOUT saving, and you will revert
   to the previously saved shapes. Note that this will also revert any other
   changes to your area that you haven't saved.

### Importing a KML file

KML files are special shape files that define a geometry on a map. They can be
created with various GIS/mapping software. If you already have your farm mapped
in another software (like [Google Earth]), you can export KML files for each
area and then import them into farmOS's geometry fields.

To import a KML file, follow these steps:

1. Create a new area (or edit an existing one).
2. Scroll down to the Files field and upload your KML file.
3. Scroll up to the Geometry field, and just below the map you will see a button
   labeled "Find using Files field". If you uploaded a valid KML file, you will
   see the shape(s) appear in the map.

<video width="100%" controls>
  <source src="/img/demos/farmOS-kml-import.mp4" type="video/mp4">
</video>

## Remember to save!

When you're done modifying your area, remember to click the Save button at the
bottom of the page to save your changes.

## Organizing areas hierarchically

There are two ways to arrange areas hierarchically in farmOS:

1. When you are editing an individual area, click "Relations" at the bottom and
   use the "Parent" and "Weight" values to define the area's relationship to
   other areas.
2. Or, you can click and drag all your areas at once into a hierachal list. To
   do this, click on the Areas link in the main menu, and in the right column
   you will see a list of all your areas with a heading of "Hierachy (change)".
   Click "(change)" to be brought to the hierarchy editor. Click and drag the
   areas up and down, and left and right to arrange them how you want, and then
   click "Save" at the bottom of the page.

<video width="100%" controls>
  <source src="/img/demos/farmOS-area-hierarchy.mp4" type="video/mp4">
</video>

## Generate beds

farmOS includes a special "Area Generator" module that makes it easy to
automatically generate a whole bunch of areas in bulk. The original goal was to
make it easier to generate parallel beds within a field, but it may provide
additional possibilities in the future.

To use the area generator to generate beds, follow these steps:

1. Go to the "Areas" page (from the main menu) and click the "Area generator"
   tab.
2. Select the field that the beds will be created within.
3. Set the "Area type" to "Bed".
4. Enter the number of beds that should be generated within the field.
5. Set the orientation of the beds, and use the "Preview" button to see how
   they look.
6. When you are satisfied with the preview, click the "Generate" button to
   generate the beds.

Beds will be numbered and labeled using the parent area's name.

<video width="100%" controls>
  <source src="/img/demos/farmOS-area-generator.mp4" type="video/mp4">
</video>

[logging events]: /guide/logs
[setting location of assets]: /guide/location
[Google Earth]: https://www.google.com/earth

