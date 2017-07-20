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
* **Farm Admin** - Administrative interface for managing the farm.
* **Farm Area** - Features for managing farm areas.
* **Farm Asset** - A farm asset entity type.
* **Farm Crop** - Features for managing farm crops.
* **Farm Equipment** - Features for managing farm equipment.
* **Farm Fields** - Provides common base field definitions for farmOS entity
  types.
* **Farm Livestock** - Features for managing farm livestock.
* **Farm Log** - Provides integration with the Log module.
* **Farm Map** - Provides OpenLayers configuration for farm maps.
* **Farm MapKnitter** - Provides integration with Public Lab's MapKnitter.org.
* **Farm Quantity** - Provides a framework for dealing with quantities.
* **Farm Sensor** - Features for managing farm sensors.
* **Farm Soil** - Provides features for soil health management.
* **Farm Taxonomy** - Common farm taxonomies.
* **Farm Tour** - Provides tours of the farmOS system using the Bootstrap Tour
  module.

### Other modules

These modules aren't included with the farmOS distribution, but they can be
added to extend your farmOS functionality:

* **[Farm Bee](https://drupal.org/project/farm_bee)** - Features for beekeeping.
* **[Farm Maple](https://drupal.org/project/farm_maple)** - Features for
  management of maple tapping and production.
* **[Farm Map: Finland](https://github.com/rkioski/farm_map_fi)** - Finnish map
  layers for farmOS maps.
* **[Farm Map: Norway](https://github.com/farmOS/farm_map_no)** - Norwegian map
  layers for farmOS maps.
* **[Farm Mushroom](https://drupal.org/project/farm_mushroom)** - Features for
  managing mushroom production.
* **[FarmOS NWS](https://github.com/bitsecondal/farmosnws)** - Imports data
  from the National Weather Service into Drupal for use by FarmOS.
* **[Farm Sensor: Atmospi](https://github.com/mstenta/farm_sensor_atmospi)** -
  Integrates farmOS and [Atmospi](https://github.com/mstenta/atmospi) sensors.

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
[Bootstrap]: https://drupal.org/project/bootstrap

