# Installation

farmOS is a [Drupal distribution], so it is essentially a [Drupal] codebase that
combines [Drupal core] with a set of pre-selected contributed modules.

## Recomended release

The recommended approach is to download the latest release of the pre-packaged
[farmOS distribution] from Drupal.org.

The Drupal.org packaged release is pre-built and ready to go. Just drop it into
a hosted web server environment and it will work the same as Drupal. For more
information on installing Drupal, see the official [Drupal Installation Guide].

## Custom build

Alternatively, you can also build the distribution yourself using Drush Make.
This is essentially what the drupal.org automatic packaging script does, so it
is generally not necessary to do this unless you have a specific reason to.

Simply checkout the [farmOS repository] and run the following command:

    drush make build-farm.make farm

This will build the farmOS distribution in a directory called "farm". Point your
server's webroot to this directory (or move the contents to your server's
webroot) and open it in a browser to access farmOS.

## Requirements

You will need a web server with all the basic [requirements of Drupal].

In addition to Drupal's basic requirements, farmOS also needs the following:

* **PHP 5.3+.** Drupal 7 itself only requires PHP 5.2+, but farmOS makes heavy
  use of the [Openlayers module], which uses some newer features of PHP.

[Drupal distribution]: https://drupal.org/documentation/build/distributions
[Drupal]: https://drupal.org
[Drupal core]: https://drupal.org/project/drupal
[https://drupal.org/project/farm]: https://drupal.org/project/farm
[farmOS distribution]: https://drupal.org/project/farm
[Drupal Installation Guide]: https://drupal.org/documentation/install
[requirements of Drupal]: https://drupal.org/requirements
[Openlayers module]: https://drupal.org/project/openlayers

