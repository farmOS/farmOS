# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

Update to the latest farmOS 3.x release before attempting to update to farmOS
4.x. All the automated database and configuration updates for the farmOS 2.x and
3.x branches have been removed. Updating to the latest release on the 3.x branch
will ensure that all necessary changes have been applied before updating to 4.x.

farmOS v4 updates Drupal to version 11. If you have built any add-on modules for
farmOS, you will need to check that they are compatible with Drupal 11, and
declare support in your `*.info.yml` file by changing `core_version_requirement`
from `^10` to `^10 || ^11` (to indicate that it works on both versions), or just
`^11` (to indicate that it only works on Drupal 11). The PHPStan tool that is
included with the farmOS `4.x-dev` Docker image can be used to perform static
analysis of your module code to see if there are deprecations that need to be
fixed. See
[farmOS coding standards](https://farmos.org/development/environment/code/) for
more information.

Drupal 11 requires PHP 8.3+, and has the following minimum database version
requirements:

- PostgreSQL 16+
- MariaDB 10.6+
- MySQL/Percona 8.0+
- SQLite 3.45+

### Added

- [Add support for attributes in farmOS plugin types #963](https://github.com/farmOS/farmOS/pull/963)
- [Add a hook for excluding fields from CSV importers #958](https://github.com/farmOS/farmOS/pull/958)
- [Include config fields in CSV importers #959](https://github.com/farmOS/farmOS/pull/959)
- Add support for decimal and integer fields in CSV importers.

### Changed

- farmOS 4.x requires PHP 8.3+.
- [Issue #3488916: Update Drupal core to 11.x](https://www.drupal.org/project/farm/issues/3488916)
- [Convert equipment to a base field #956](https://github.com/farmOS/farmOS/pull/956)
- [Convert quick to a base field #957](https://github.com/farmOS/farmOS/pull/957)
- [Make farmOS API OAuth2 Server optional #973](https://github.com/farmOS/farmOS/pull/973)

### Deprecated

- [farmOS core plugin type annotations are deprecated](https://www.drupal.org/node/3523485)

### Removed

- [Remove 2.x and 3.x update hooks](Remove 2.x and 3.x update hooks #962)
- Remove [deprecated Drupal 7 asset migration source plugin](https://www.drupal.org/node/3410747).
- Remove [deprecated Drupal 7 plan migration source plugin](https://www.drupal.org/node/3498065).
- Remove [deprecated data stream migration plugins](https://www.drupal.org/node/3498069).
- Remove [EXIF Orientation](https://www.drupal.org/project/exif_orientation) module (see [Automatically rotate derivative images based on EXIF Orientation #941](https://github.com/farmOS/farmOS/pull/941)).
- Remove [JSON:API Extras](https://www.drupal.org/project/jsonapi_extras) module (see [Remove dependency on JSON:API Extras module #964](https://github.com/farmOS/farmOS/pull/964)).
- [Do not generate keys during farm_api_install() #972](https://github.com/farmOS/farmOS/pull/972)

### Fixed

- [Fix plan_record bundle field providers #969](https://github.com/farmOS/farmOS/pull/969)

## farmOS 3.x

farmOS 3.x release notes are available in the 3.x branch's
[CHANGELOG.md](https://github.com/farmOS/farmOS/blob/3.x/CHANGELOG.md)

## farmOS 2.x

farmOS 2.x release notes are available in the 2.x branch's
[CHANGELOG.md](https://github.com/farmOS/farmOS/blob/2.x/CHANGELOG.md)

## farmOS 1.x

farmOS 1.x release notes are available in the
[farmOS releases on Drupal.org](https://www.drupal.org/project/farm/releases?version=7.x-1).

[Unreleased]: https://github.com/farmOS/farmOS/compare/3.x...4.x
