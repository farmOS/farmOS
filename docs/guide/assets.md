# Managing assets

All the important and valuable things on your farm are represented as "assets"
in farmOS. Assets include Plantings, Animals, Equipment, etc.

Assets are organized into different sections in the farmOS interface, and can
be accessed from the main menu. You can add assets from within each asset type's
dashboard.

<video width="100%" controls>
  <source src="/img/demos/farmOS-assets.mp4" type="video/mp4">
</video>

## Asset fields

Each asset type will have it's own set of fields, but some of the common ones
include:

* **Name** (required) - The name of the asset.
* **Current location** - This is actually not a real field. It is a shortcut for
  creating movement logs to assign asset location. For more information see
  [Movements and location].
* **Photos** - This field lets you attach photos to your asset.
* **Description** - This is a simple text tield that you can use to describe
  each asset in further detail. It can be used to take notes, but it is
  recommended that any activities be recorded using logs instead, because they
  have a timestamp associated with them.

## Asset cluster maps

In the dashboard of each asset, there is an "asset cluster map" that displays
counts of assets in a map, along with the geometries of their locations.

They are called "cluster" maps because they use a feature of the
[Openlayers mapping library] called a "Cluster source". This means that the
location of all assets of a particular type (ie: animals) are loaded into a map
at once, and they are "clustered" into points based on their proximity to one
another.

So if you have 20 animals all within the same relative area, you will see a
single point with a "20" on it. You can click on that point to see a list of the
animals, and if you zoom in, that point will automatically break up into
multiple other cluster points, showing more precise locations.

Here's a little more nitty-gritty on how this works: Movement logs are used to
record the [location of assets in farmOS], along with a geometry field for
storing precise geodata about location. This geometry is being loaded into
cluster maps twice. The first is to draw the actual geometry of the asset
location. And the second is to generate the cluster points. The points
themselves are just the "centroid" of the geometry itself - which basically
means it's the average centerpoint, represented in latitude and longitude. So by
displaying both the actual geometry, and the centroid points, you're able to get
a very nice overview of exactly where assets are on your farm. Pretty cool huh?

## Archiving assets

Assets can be marked as "active" or "inactive". Inactive assets will not show up
in farmOS unless you specifically want to see them. So for example, when you are
done harvesting a planting, you can mark it as "inactive" to archive it.

[Movements and location]: /guide/location
[Openlayers mapping library]: http://openlayers.org
[location of assets in farmOS]: /guide/location

