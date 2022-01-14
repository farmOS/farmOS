# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Localization module (`farm_l10n`) for enabling translations.
- "Other" Structure type
- [Open the Gin toolbar by default #470](https://github.com/farmOS/farmOS/pull/470)
- [Enforce that the changelog is updated with every pull request #469](https://github.com/farmOS/farmOS/pull/469)

### Changed

- Do not include archived assets in metrics count.
- Remove "administer farm map" from Manager role permissions.
- [Add allow-plugins config #467](https://github.com/farmOS/farmOS/pull/467)

### Fixed

- [Data stream and notification permissions are not granted to managed roles. #479](https://github.com/farmOS/farmOS/issues/479)
- Sort locations by name, using natural sort algorithm.
- [Quantity module breaks config_translation #480](https://github.com/farmOS/farmOS/issues/480)
- [Log categories are not migrated to v2 #481](https://github.com/farmOS/farmOS/pull/481)
- Make local action buttons translatable.
- Fix permission for map settings form (/farm/settings/map).
- Patch `jsonapi_schema` module to fix
  [Issue #3256795: Float fields have a null schema](https://www.drupal.org/project/jsonapi_schema/issues/3256795)
- Allow all three database tests to run even when one fails (workaround
  for [Issue #3241653](https://www.drupal.org/project/farm/issues/3241653)).
- Run SQLite3 tests in sequence instead of in parallel (another workaround for
  [Issue #3241653](https://www.drupal.org/project/farm/issues/3241653)).

### Security

- Update Simple OAuth module to 5.0.6 for [SA-CONTRIB-2022-002](https://www.drupal.org/sa-contrib-2022-002)

## [2.0.0-beta1] 2022-01-01

farmOS 2.x is a complete rewrite of farmOS for [Drupal 9](https://www.drupal.org/about/9).
This brings many improvements, modernizations, and new features. The following
is a brief summary of notable changes from the 1.x branch of farmOS (aka
`7.x-1.x` for Drupal 7).

Detailed release notes will be included in this file with each new release
moving forward.

### Notable changes from farmOS 1.x

- Data model
  - [Documented data model](https://farmOS.org/model)
  - [Areas are now types of Assets](https://farmos.org/development/api/changes/#areas)
  - New Asset types: Land, Structure, Water, Material, Seed
  - Planting Assets are renamed to Plant
  - New Log types: Lab test (merged Soil and Water tests)
  - [Inventory tracking](https://farmos.org/model/logic/inventory/) for all
    Asset types
  - Improved [Asset location](https://farmos.org/model/logic/location/) logic,
    including the ability to designate Assets as "fixed" (with intrinsic
    geometry) and/or "locations" (allowing other Assets to be moved to them)
  - Improved [Group membership](https://farmos.org/model/logic/group/) logic,
    including member inheritence of group location
  - Support for [Quantity types](https://farmos.org/model/type/quantity/#type)
  - Revisions for tracking changes to records
  - Improved "Data streams" framework for sensors and other time-series data
    collection
  - ID tags on all Asset types
  - Flags can be limited by record type
- User interface/experience (UI/UX)
  - Improved location hierarchy drag-and-drop editor, including ability to edit
    sub-hierarchies
  - Improved KML/KMZ importer for bulk Land Asset creation
  - Geocoding of GeoJSON and GPX files (in addition to KML/KMZ) on individual
    Assets and Logs
  - Farm settings UI with simplified module installer
  - [Gin](https://www.lullabot.com/podcasts/lullabot-podcast/gin-admin-theme-drupals-future-ui)
    admin theme
  - Improved mobile support
- APIs, libraries, and developer experience (DX)
  - [Documented API changes](https://farmos.org/development/api/changes/)
  - Modernized RESTful API built on [JSON:API](https://jsonapi.org/)
  - [JSON Schema](https://json-schema.org/) for all API resources
  - 2.x API support in [farmOS.js](https://github.com/farmOS/farmOS.js) and
    [farmOS.py](https://github.com/farmOS/farmOS.py) libraries
  - Updated [farmOS-map](https://github.com/farmOS/farmOS-map) library based on
    [OpenLayers](https://openlayers.org/)
  - Improved APIs for [module builders](https://farmos.org/development/module/)
  - Object-oriented architecture based on [Symfony](https://symfony.com/)
  - Dependency management via [Composer](https://getcomposer.org/)
  - Automated testing via [PHPUnit](https://phpunit.de/) and
    [GitHub Actions](https://github.com/farmOS/farmOS/actions)
  - Coding standards enforcement via [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
  - Feature branch previews via [Tugboat](https://www.tugboat.qa/)
- Hosting
  - PostgreSQL database support (alongside MySQL, MariaDB, and SQLite3)
  - [Automated migration](https://farmos.org/hosting/migration/) from 1.x to 2.x
  - Improved performance with lazy-loading code and caching options
  - Improved support for translation/localization (l10n)
- Security
  - Support from the [Drupal Security Team](https://www.drupal.org/drupal-security-team)
  - Drupal 9 will be supported (with security updates) until November 2023.
  - Drupal 10 will be released mid-2022. farmOS will be prepared to update as
    soon as possible. This process will be trivial compared to the upgrade from
    Drupal 7, which required a complete refactor of the codebase. By comparison,
    updating from Drupal 9 to 10 will simply involve updating deprecated code.

[Unreleased]: https://github.com/farmOS/farmOS/compare/2.0.0-beta1...HEAD
[2.0.0-beta1]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta1
