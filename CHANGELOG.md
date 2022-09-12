# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- [Add button/menu item for data stream notifications collection page #555](https://github.com/farmOS/farmOS/pull/555)
- [Issue #3306227: Dispatch events for asset presave, insert, update, delete](https://www.drupal.org/project/farm/issues/3306227)
- [Issue #3306344: Allow views exposed filters to be collapsed](https://www.drupal.org/project/farm/issues/3306344)

### Changed

- [Improve API docs #557](https://github.com/farmOS/farmOS/pull/557)
- [Issue #3305724 by pcambra, m.stenta: Allow map type and behaviors to be configurable in map blocks](https://www.drupal.org/project/farm/issues/3305724)
- [Update Drupal core to 9.4.x](https://github.com/farmOS/farmOS/pull/566)

### Fixed

- [Fix for Autocomplete dropdown not showing in Chrome on Android #552](https://github.com/farmOS/farmOS/issues/552)
- [Uncaught (in promise) TypeError: instance.editAttached is undefined #550](https://github.com/farmOS/farmOS/issues/550)
- [Map form element #required is not enforced #560](https://github.com/farmOS/farmOS/issues/560)
- [Incorrect translation placeholders for asset names #540](https://github.com/farmOS/farmOS/issues/540)
- Update farmOS-map to [v2.0.5](https://github.com/farmOS/farmOS-map/releases/tag/v2.0.5) to fix [Uncaught (in promise) TypeError: o.getChangeEventType is not a function #551](https://github.com/farmOS/farmOS/issues/551)
- [Prevent circular group membership #562](https://github.com/farmOS/farmOS/pull/562)

## [2.0.0-beta6] 2022-07-30

### Added

- [Issue #3290929: Provide a farmOS map form element](https://www.drupal.org/project/farm/issues/3290929)
- [Issue #3290993: Add "Move asset" button next to the current location field](https://www.drupal.org/project/farm/issues/3290993)
- [Generate unique form IDs for quick forms #547](https://github.com/farmOS/farmOS/pull/547)

### Security

- Update Drupal core to 9.3.16 for [SA-CORE-2022-011](https://www.drupal.org/sa-core-2022-011).
- Update Drupal core to 9.3.19 for [SA-CORE-2022-012](https://www.drupal.org/sa-core-2022-012), [SA-CORE-2022-013](https://www.drupal.org/sa-core-2022-013), [SA-CORE-2022-014](https://www.drupal.org/sa-core-2022-014), and [SA-CORE-2022-015](https://www.drupal.org/sa-core-2022-015).

## [2.0.0-beta5] 2022-06-02

### Changed

- [Issue #3275161: Allow IMG tags in default text format](https://www.drupal.org/project/farm/issues/3275161)
- [Update toolbar logo spacing for gin beta #527](https://github.com/farmOS/farmOS/pull/527)
- [Only show active plans by default #529](https://github.com/farmOS/farmOS/pull/529)

### Fixed

- [Do not check php-geos requirement in the update phase #526](https://github.com/farmOS/farmOS/pull/526)
- Patch entity_reference_revisions module to fix upstream issue [#3267304](https://www.drupal.org/project/entity_reference_revisions/issues/3267304).

### Security

- Update Drupal core to 9.3.12 for [SA-CORE-2022-008](https://www.drupal.org/sa-core-2022-008) and
  [SA-CORE-2022-009](https://www.drupal.org/sa-core-2022-009).
- Update Drupal core to 9.3.14 for [SA-CORE-2022-010](https://www.drupal.org/sa-core-2022-010).

## [2.0.0-beta4] 2022-04-13

### Added

- [Link from entities to their referenced terms and show entity views on taxonomy terms #458](https://github.com/farmOS/farmOS/pull/458).
- [Encourage GEOS PHP extension use #521](https://github.com/farmOS/farmOS/pull/521)

### Changed

- Update [farmOS-map](https://github.com/farmOS/farmOS-map) to [v2.0.4](https://github.com/farmOS/farmOS-map/releases/tag/v2.0.4).
- [Issue #3270561: Upgrade to gin beta](https://www.drupal.org/project/farm/issues/3270561)
- [Separate Docker image build from testing jobs in run-test.yml workflow #522](https://github.com/farmOS/farmOS/pull/522)
- [Merge test and release workflows into a unified delivery workflow #523](https://github.com/farmOS/farmOS/pull/523)
- [Improve fields documentation #505](https://github.com/farmOS/farmOS/pull/505)

### Fixed

- [Only require a name to build map popups #515](https://github.com/farmOS/farmOS/pull/515)
- [Issue #3269543 by paul121: Automatically remove prepopulated entities from quick forms](https://www.drupal.org/project/farm/issues/3269543)
- [Do not add views handlers for unsupported field types #512](https://github.com/farmOS/farmOS/pull/512)
- [Allow importing KML with empty geometries #510](https://github.com/farmOS/farmOS/issues/510)

### Security

- Update Drupal core to 9.3.8 for [SA-CORE-2022-005](https://www.drupal.org/sa-core-2022-005).
- Update Drupal core to 9.3.9 for [SA-CORE-2022-006](https://www.drupal.org/sa-core-2022-006).

## [2.0.0-beta3] 2022-03-03

### Added

- Document farmOS cron set-up: https://farmos.org/hosting/install#cron
- [Issue #3253433: Provide a helper function for loading flag options and allowed values](https://www.drupal.org/project/farm/issues/3253433)

### Changed

- [Issue #3259245: Change getGroupMembers to return an array of assets keyed by their ID](https://www.drupal.org/project/farm/issues/3259245)

### Fixed

- [Issue #3260645: CSV Export in Quantities not functioning](https://www.drupal.org/project/farm/issues/3260645)
- [Issue #3262752: Record type menu items lose translations](https://www.drupal.org/project/farm/issues/3262752)
- Fix access check for "Developer information" on sensors and data streams.
- [Maps broken with Uncaught SyntaxError: Unexpected token '?' #501](https://github.com/farmOS/farmOS/issues/501)
- [Asset autocomplete breaks when asset has parentheses at the end #502](https://github.com/farmOS/farmOS/issues/502)
- [Issue #3265207: API keys directory failure prevents farm client creation](https://www.drupal.org/project/farm/issues/3265207)
- [Issue #3264564: No space rendered in field suffix](https://www.drupal.org/project/farm/issues/3264564)
- [Error: Call to a member function get() on null in ContentEntityGeometryNormalizer.php on line 64 #493](https://github.com/farmOS/farmOS/issues/493)

### Security

- Update Drupal core to 9.3.6 for [SA-CORE-2022-003](https://www.drupal.org/sa-core-2022-004)
  and [SA-CORE-2022-004](https://www.drupal.org/sa-core-2022-004).

## [2.0.0-beta2] 2022-01-19

### Added

- Add a Planting quick form module.
- Create a dedicated section in farmOS modules form for "Quick form modules".
- Provide a `quantity_measure_options()` helper function.
- Localization module (`farm_l10n`) for enabling translations.
- "Other" Structure type
- [Open the Gin toolbar by default #470](https://github.com/farmOS/farmOS/pull/470)
- [Enforce that the changelog is updated with every pull request #469](https://github.com/farmOS/farmOS/pull/469)

### Changed

- Do not include archived assets in metrics count.
- Remove "administer farm map" from Manager role permissions.
- [Add allow-plugins config #467](https://github.com/farmOS/farmOS/pull/467)

### Fixed

- [Issue #3224663: Type-specific CSV exports do not respect exposed filters](https://www.drupal.org/project/farm/issues/3224663)
- [Improvements to sensor and data stream developer info #491](https://github.com/farmOS/farmOS/pull/491)
- [Data is not deleted when a data stream entity is deleted #488](https://github.com/farmOS/farmOS/issues/488)
- [Data does not immediately appear when posting to data streams #484](https://github.com/farmOS/farmOS/issues/484)
- [Route "entity.data_stream.collection" does not exist. #486](https://github.com/farmOS/farmOS/issues/486)
- Fix Quick Form help text so that it works with new multi-route approach.
- Remove entity ID from entity autocomplete form elements.
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

- Update Drupal core to 9.3.3 for [SA-CORE-2022-001](https://www.drupal.org/sa-core-2022-001)
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

[Unreleased]: https://github.com/farmOS/farmOS/compare/2.0.0-beta5...HEAD
[2.0.0-beta5]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta5
[2.0.0-beta4]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta4
[2.0.0-beta3]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta3
[2.0.0-beta2]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta2
[2.0.0-beta1]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta1
