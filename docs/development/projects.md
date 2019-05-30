# Projects

[farmOS] is built as a set of separate but inter-dependent [Drupal] projects.

Drupal is a modular system, and farmOS follows in those footsteps by providing
all of it's features as singularly-focused modules on top of Drupal core.

All of these various modules, their dependencies, third-party libraries, and
the official farmOS Drupal theme are packaged together into a
[farm-focused Drupal distribution] that is collectively referred to as "farmOS".

Distributions of Drupal are roughly analogous to distributions of Linux. They
serve to collect various code and configuration together in an intentional way.
More information can be found in the [Drupal distribution documentation].

To learn more about Drupal in general, refer to the [Drupal documentation].

## Distribution

* [farmOS Distribution]

The purpose of the farmOS distribution is to collect all the modules in one
package, along with some default configuration, theming, etc. Drupal.org has an
automated packaging system, so if you are getting started with farmOS,
downloading and installing a packaged release is the recommended approach. See
[installing farmOS] for more information.

The farmOS repository itself does not include a fully-built codebase. So if you
clone it from drupal.org or Github, you will either need to build it yourself
with [Drush] or use [Docker].

## Modules

### Included in farmOS

These modules are included directly in the farmOS distribution repository:

* **Farm Access** - Provides mechanisms for managing farmOS user access
  permissions.
* **Farm Area** - Features for managing farm areas.
* **Farm Area Generator** - A tool for automatically generating areas. Requires
  GEOS library.
* **Farm Area Import** - A tool for importing multiple area geometries from a
  single KML file.
* **Farm Asset** - A farm asset entity type.
* **Farm Asset Children** - Provides child asset relationships.
* **Farm Asset Property** - Provides the ability to assign arbitrary (key-value
  pairs) properties to assets.
* **Farm Asset Views** - Default Views of farm assets.
* **Farm Calendar** - Provides a calendar view of farmOS logs.
* **Farm Constraint** - Provides a framework for managing foreign key
  constraints between farmOS entities.
* **Farm Crop** - Features for managing farm crops.
* **Farm Dashboard** - Provides a farmOS dashboard at /farm.
* **Farm Equipment** - Features for managing farm equipment.
* **Farm Fields** - Provides common base field definitions for farmOS entity
  types.
* **Farm Flags** - Provides a general purpose record flagging system.
* **Farm Group** - Features for managing group assets like herds, flocks, etc.
* **Farm Help** - Help pages for farmOS.
* **Farm Import** - Provides a framework for creating CSV importers in farmOS.
* **Farm Inventory** - Provides inventory management functionality for farm
  assets.
* **Farm Livestock** - Features for managing farm livestock.
* **Farm Log** - Provides integration with the Log module.
* **Farm Log: Activity** - Provides an activity log type.
* **Farm Log: Harvest** - Provides a harvest log type.
* **Farm Log: Input** - Provides an input log type.
* **Farm Log: Observation** - Provides an observation log type
* **Farm Log: Sale** - Provides a sale log type.
* **Farm Map** - Provides OpenLayers configuration for farm maps.
* **Farm MapKnitter** - Provides integration with Public Lab's MapKnitter.org.
* **Farm Menu** - Provides a menu for farm pages.
* **Farm Movement** - Provides movement field and related asset location code.
* **Farm People** - A view of people associated with the farm.
* **Farm Plan** - A farm plan entity type.
* **Farm Plan Consideration** - Provides features for managing considerations
  within a farm plan.
* **Farm Quantity** - Provides a framework for dealing with quantities.
* **Farm Quantity Log** - Provides helper functions for querying logs with quantity data.
* **Farm Quantity Report** - Generate reports of quantity measurements.
* **Farm Quick** - Provides a framework for quick forms in farmOS.
* **Farm Season** -   Provides a taxonomy and fields for organizing assets and logs by season.
* **Farm Sensor** - Features for managing farm sensors.
* **Farm Sensor: Listener** - ...
* **Farm Soil** - Provides features for soil health management.
* **Farm Soil NRCS** - Provides integration with NRCS Soil Survey data.
* **Farm Soil Test** - .Features for managing soil tests.
* **Farm Soil: Compost** - Features for managing compost.
* **Farm Tour** - Provides tours of the farmOS system using the Bootstrap Tour
  module.
* **Farm UI** - Provides standardized user interface support to farmOS modules.
* **Farm Water** - Provides features for water management.
* **Farm Water Test** - Features for managing water tests.

### Other modules

These modules aren't included with the farmOS distribution, but they can be
added to extend your farmOS functionality:

* **[Farm Bee](https://github.com/farmOS/farm_bee)** - Features for beekeeping.
* **[Farm Biodynamic](https://github.com/farmOS/farm_biodynamic)** - Provides a
  flag for marking records as "Biodynamic" in farmOS.
* **[Farm Eggs](https://github.com/farmOS/farm_eggs)** - Provides a simple UI
  for creating egg harvest logs.
* **[Farm Forest](https://github.com/mstenta/farm_forest)** - Forest management
  module for farmOS.
* **[Farm Grazing](https://github.com/farmOS/farm_grazing)** - Provides a simple UI
  for creating egg harvest logs.
* **[Farm Maple](https://github.com/farmOS/farm_maple)** - Features for
  management of maple tapping and production.
* **[Farm Map: Finland](https://github.com/rkioski/farm_map_fi)** - Finnish map
  layers for farmOS maps.
* **[Farm Map: Norway](https://github.com/farmOS/farm_map_no)** - Norwegian map
  layers for farmOS maps.
* **[Farm Mushroom](https://github.com/farmOS/farm_mushroom)** - Features for
  managing mushroom production.
* **[FarmOS NWS](https://github.com/almostengr/farmosnws)** - Imports data
  from the National Weather Service into Drupal for use by farmOS.
* **[Farm Nutrient](https://github.com/farmOS/farm_nutrient)** - Nutrient
  management planning module for farmOS.
* **[Farm Organic](https://github.com/farmOS/farm_organic)** - Provides flags
  for marking records as "Organic" and "Not Organic" in farmOS.
* **[Farm Produce Safety](https://github.com/farmOS/farm_produce_safety)** -
  A produce safety record keeping module for farmOS.
* **[Farm Sensor: Atmospi](https://github.com/mstenta/farm_sensor_atmospi)** -
  Integrates farmOS and [Atmospi](https://github.com/mstenta/atmospi) sensors.

For more information and documentation about these modules, see
[farmOS community modules].

## Theme

The official farmOS theme ("Farm Theme") that is included with farmOS is a
Drupal theme based off of [Bootstrap].

[farmOS]: http://farmos.org
[Drupal]: https://drupal.org
[farm-focused Drupal distribution]: https://drupal.org/project/farm
[Drupal distribution documentation]: https://www.drupal.org/documentation/build/distributions
[Drupal documentation]: https://www.drupal.org/documentation
[farmOS Distribution]: https://drupal.org/project/farm
[installing farmOS]: /hosting/installing
[Drush]: http://www.drush.org
[Docker]: /development/docker
[farmOS community modules]: /guide/contrib
[Bootstrap]: https://drupal.org/project/bootstrap

