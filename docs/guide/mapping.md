# Mapping locations

farmOS gives you the ability to organize all the various places on (and off)
your farm. These places are represented as [Assets](/guide/assets) in farmOS,
and they can be referenced when you are [logging events](/guide/logs). Assets
can be located in other Assets via movement Logs and
[location logic](/guide/location).

To create a location Asset, go to the farmOS Dashboard and click the "Add
asset" button. Select the Asset type you want to map, such as Land or
Structure, and you will be taken to a form for creating the Asset.

You will see a Geometry field with a map for drawing the shape of the Asset.
Generally a single polygon is all that is required, but points and lines are
also allowed.

Make sure that the "Is location" option is enabled so that the Asset is
designated as a location.

When you're done modifying your area, remember to click the Save button at the
bottom of the page to save your changes.

## Map controls

### Zooming

There are three ways to zoom in/out:

- On touch screens, you can "pinch zoom" using two fingers.
- With a computer mouse, you can use the scroll wheel to zoom in/out (click
   on the map first to enable scroll zoom).
- The plus (+) and minus (-) buttons in the top left of the map control zoom.

### Geolocating

A "Geolocate" button is available in the top left of the map. This will use your
device's GPS (if available) and/or IP-based geolocation service to try to find
your current location and center the map on that point. While geolocation is
turned on, your position will automatically update as you move around. In
addition to the point, a circle will also be displayed around the point with an
accuracy radius to tell you how confident the geolocated position is.

### Address search

A search button is available in the top of the map for performing an address
search. As you type into the search box, matching addresses will be displayed
in a dropdown. Select the address that you want from that list, and the map
will be automatically re-centered on that location. Geocoding is provided by
[Nominatim](https://nominatim.org/).

### Drawing

There are four buttons for drawing shapes:

- **Polygon**: Use this to draw closed shapes like squares, rectangles, etc.
  Click at each vertex of the shape, and end by either connecting to the first
  point or double-clicking to connect automatically.
- **Line**: Create a series of line segments by clicking points on the map, and
  double-clicking when you're done. You can also hold shift to draw freehand.
- **Point**: Click on the map to create a point.
- **Circle**: Create a circle by clicking where you want the center to be,
  dragging the circle outward to expand it, and clicking again to finish.

### Modifying

There are three buttons for modifying shapes:

- **Modify**: Click the modify button, and then click a shape to select it. You
  can click and drag any of the vertices to modify the overall shape.
- **Move**: Click the move button, and then click a shape to select it. Then
  click and drag the shape to move it to a different position.
- **Delete**: The delete button will clear any selected shapes from the map.
  This button will appear when either the Modify or Move buttons are active,
  and a shape has been selected. If you accidentally delete a shape, refresh
  the page WITHOUT saving, and you will revert to the previously saved shapes.
  Note that this will also revert any other changes to your area that you
  haven't saved.

## Geometry import and export

See [Importing data](/guide/import) and [Exporting data](/guide/export).

## Location hierarchy

Location Assets can be organized into a hierarchy. This is visible from the
"Locations" page in the Toolbar. There are three ways to modify the hierarchy:

- Click "Locations" in the toolbar, then click "Toggle drag and drop" at the
  bottom. Drag locations to their new parents to modify the hierarchy. Click
  "Save" when you are done, or "Reset" to undo your changes.
- The same drag and drop editor is available for child hierarchies of
  individual location Assets. Go to the parent Asset record and click the
  "Locations" tab to see the hierarchy of children, with the option to modify
  via drag and drop.
- When you are editing an individual location Asset, modify its "Parent"
  relationship to point to the Asset that should appear above it in the
  hierarchy.

## Use the Snapping Grid

farmOS includes a "snapping grid" tool to aid in creating regular/aligned
geometries. When drawing, the cursor will only snap to existing geometries by
default. However, the snapping grid adds a grid of evenly spaced points to which
the cursor will snap preferentially.

![snapping_grid_demo](https://user-images.githubusercontent.com/30754460/88995756-5cb22300-d2a0-11ea-88a1-50edac1c0168.gif)

To activate the grid, click the '#' icon in the bottom left corner above the
scale line, then select two points. The first point is the "origin" of the grid
while the second point we will call a "rotation anchor" and describes how the
grid should be rotated around the origin point.

In addition to controlling the origin/rotation of the grid, its dimensions can
be specified. To do so, hover your mouse over the '#' icon then enter the `x` and
`y` dimensions in the controls which appear. Since the grid can be rotated, the
dimensions are independent of the compass directions on the map. Instead, the `x`
dimension represents the distances between the grid points on lines parallel with
the line formed by the origin and rotation anchor points. Similarly, the `y` dimension
represents distances perpendicular to that line.

![farmOS Snapping Grid dimensions screenshot](/img/snapping_grid_dimension_explanation.png)
