# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- [Update composer.md guide to capture locked farmOS version #697](https://github.com/farmOS/farmOS/pull/697)

### Fixed

- [Leaving empty the Parent field in the Assign Parent For This Asset form leads to unexpected error #683](https://github.com/farmOS/farmOS/issues/683)
- [Correct namespace for MigrateToolsCommands #700](https://github.com/farmOS/farmOS/pull/700)
- [Improve asset bundle logic in ID tag widget #699](https://github.com/farmOS/farmOS/pull/699)

## [2.1.1] 2023-05-23

### Fixed

- [Fix undeclared dependency on farm_group in birth quick form #682](https://github.com/farmOS/farmOS/pull/682)

## [2.1.0] 2023-05-19

This is the first minor release of the farmOS 2.x branch, following
[semantic versioning](https://semver.org/). This means new functionality is
added in a backwards compatible manner.

farmOS 2.1.0 adds two quick forms that were present in farmOS 1.x. The Birth
quick form makes it easy to record animal births, by creating the birth log and
children animal assets all in one step. The Movement quick form (formerly the
Animal Movement quick form in 1.x) makes it easy to record the movement of
assets to a new location, with the option of customizing their geometry at the
same time.

In editable maps, it is now possible to paste WKT into the textarea below the
map and the map geometry will be automatically updated.

This release also moves the "View", "Edit", and "Revisions" links on assets,
logs, plans, and taxonomy terms from primary tabs to secondary tabs, to create
a better separation from the other primary tabs.

### Added

- [Refresh map edit layer when WKT is pasted into data input field #670](https://github.com/farmOS/farmOS/pull/670)
- [Add QuickStringTrait::entityLabelsSummary() method for summarizing entity labels #675](https://github.com/farmOS/farmOS/pull/675)
- [Add asset inventory views field #679](https://github.com/farmOS/farmOS/pull/679)
- [Birth quick form (ported from farmOS 1.x) #656](https://github.com/farmOS/farmOS/pull/656)
- [Movement quick form (ported from farmOS 1.x) #677](https://github.com/farmOS/farmOS/pull/677)

### Changed

- Update farmOS-map to [v2.2.1](https://github.com/farmOS/farmOS-map/releases/tag/v2.2.1)
- [Remove field links from asset entity browser view #673](https://github.com/farmOS/farmOS/pull/673)
- [Move default entity tabs to secondary tabs #634](https://github.com/farmOS/farmOS/pull/634)

### Fixed

- [Fix bulk move/group action log names when unsetting location/group #669](https://github.com/farmOS/farmOS/pull/669)
- [Fix farm_log_asset_names_summary() $cutoff parameter #674](https://github.com/farmOS/farmOS/pull/674)

### Security

- Update Drupal core to 9.5.8 for [SA-CORE-2023-005](https://www.drupal.org/sa-core-2023-005)

## [2.0.4] 2023-04-03

### Added

- [Add "Speed" to the list of quantity measures #658](https://github.com/farmOS/farmOS/pull/658)
- [Include Fraction bundle fields in default Views #664](https://github.com/farmOS/farmOS/pull/664)
- [Allow map to be resized vertically #663](https://github.com/farmOS/farmOS/pull/663)
- [Add integer, decimal, and email field support to field factory service #666](https://github.com/farmOS/farmOS/pull/666)
- [Add a QuickFormElementsTrait with a buildInlineContainer() method #654](https://github.com/farmOS/farmOS/pull/654)

### Changed

- [Do not add birth log mother to animal assets that already have parents #655](https://github.com/farmOS/farmOS/pull/655)
- [Simplify all map resize logic to use ResizeObserver #662](https://github.com/farmOS/farmOS/pull/662)
- [Replace all usages of docker-compose with native docker compose #627](https://github.com/farmOS/farmOS/pull/627)
- [Allow max_length to be overridden on string fields #666](https://github.com/farmOS/farmOS/pull/666)
- [Standardize all taxonomy bundle labels to be singular #661](https://github.com/farmOS/farmOS/pull/661)
- [Update Drupal core to 9.5.7 #667](https://github.com/farmOS/farmOS/pull/667)

### Fixed

- [Fix quick form toolbar menu links #657](https://github.com/farmOS/farmOS/pull/657)
- [Respect user timezone in midnight default datetime values #665](https://github.com/farmOS/farmOS/pull/665)

## [2.0.3] 2023-03-15

### Added

- [Create a QuickFormTestBase class that other quick form tests can extend from #650](https://github.com/farmOS/farmOS/pull/650)

### Security

- Update Drupal core to 9.5.5 for [SA-CORE-2023-002](https://www.drupal.org/sa-core-2023-002),
  [SA-CORE-2023-003](https://www.drupal.org/sa-core-2023-003), and [SA-CORE-2023-004](https://www.drupal.org/sa-core-2023-004).

## [2.0.2] 2023-03-10

### Added

- [Document building farmOS with Composer #648](https://github.com/farmOS/farmOS/pull/648)

### Fixed

- [Convert geometry values to WKT in GeofieldWidget #640](https://github.com/farmOS/farmOS/pull/640)
- [Fix map ui being unreadable on dark mode #642](https://github.com/farmOS/farmOS/pull/642)
- Patch Drupal core to fix [Issue #3266341: Views pagers do math on disparate data types, resulting in type errors in PHP 8](https://www.drupal.org/project/drupal/issues/3266341)
- [Implement FarmBreadcrumbBuilder::applies() to only affect desired routes](https://github.com/farmOS/farmOS/pull/644)
- [Attempt to update the map size until it is rendered #576](https://github.com/farmOS/farmOS/issues/576)
- [Show map popup for the smallest feature at the clicked point #652](https://github.com/farmOS/farmOS/pull/652)

## [2.0.1] 2023-02-08

### Added

- [Add farmOS API kernel tests #638](https://github.com/farmOS/farmOS/pull/638)
- [Add breadcrumb on canonical user page to people page](https://github.com/farmOS/farmOS/pull/644)

### Changed

- [Move land and structure type fields higher up for consistency #632](https://github.com/farmOS/farmOS/pull/632)
- [Change asset action date pickers to use HTML5 calendar widgets #630](https://github.com/farmOS/farmOS/pull/630)
- [Allow setting time in bulk actions and quick forms via datetime element #635](https://github.com/farmOS/farmOS/pull/635)

### Fixed

- [Add folder creation and clear caches to Configure private filesystem in the Hosting and Environment docs #628](https://github.com/farmOS/farmOS/pull/628)
- [Issue #3336698: Add "project: farm" to farm.info.yml to fix drupal.org usage statistics](https://www.drupal.org/project/farm/issues/3336698)
- [Fix type error when un-assigning asset from group #631](https://github.com/farmOS/farmOS/pull/631)
- [Issue #3335267 by m.stenta, farmer-ed, penyaskito: 405 Method Not Allowed when trying to patch entities as a user with non-default language](https://www.drupal.org/project/farm/issues/3335267)

## [2.0.0] 2023-01-01

This is the first official "stable" release of farmOS v2! Moving forward,
releases will follow standard [semantic versioning](https://semver.org/).

### Changed

- [Update Drupal core to 9.5.x](https://github.com/farmOS/farmOS/pull/621)

### Fixed

- [Prevent circular asset location #568](https://github.com/farmOS/farmOS/pull/568)
- [Prevent circular group membership #562](https://github.com/farmOS/farmOS/pull/562)
- [Issue #3328419: Uninstalling farm_ui_views module breaks site](https://www.drupal.org/project/farm/issues/3328419)
- [Remove line breaks and whitespace from log CSV quantity cells #622](https://github.com/farmOS/farmOS/pull/622)

## [2.0.0-rc1] 2022-12-15

PHP 8+ is now the recommended minimum version requirement for farmOS. The
official farmOS Docker images run PHP 8+ as of this release. PHP 7.4's
[end-of-life](https://www.php.net/supported-versions.php) was November 28th,
2022. farmOS still works with PHP 7.4 as of this writing, but support is not
officially guaranteed moving forward. If you maintain a farmOS module, please
test that it works with PHP 8. If you host farmOS, please upgrade PHP to 8+ as
soon as possible.

### Added

- [Issue #3186530: farmOS 2.x PHP 8 support](https://www.drupal.org/project/farm/issues/3186530)
- [Include GEOS version in status report #614](https://github.com/farmOS/farmOS/pull/614)

### Changed

- [Remove default_measure configuration from quantity type definitions #612](https://github.com/farmOS/farmOS/pull/612)
- [Shorten name of tests and delivery workflow #617](https://github.com/farmOS/farmOS/pull/617)
- [Only show the first quantity in log Views #619](https://github.com/farmOS/farmOS/pull/619)
- [Issue #3310286: Add $timestamp parameter to service methods](https://www.drupal.org/project/farm/issues/3310286)

### Fixed

- [Correct hook_farm_update_exclude_config API docs #608](https://github.com/farmOS/farmOS/pull/608)
- [Correct CSS classname for priority flag #609](https://github.com/farmOS/farmOS/pull/609)
- [Fix user admin permissions form alter for managed roles #610](https://github.com/farmOS/farmOS/pull/610)
- [Fix migration of lab field to taxonomy terms #611](https://github.com/farmOS/farmOS/pull/611)
- [Fix TypeError when adding email delivery #616](https://github.com/farmOS/farmOS/pull/616)
- [Fix enable/disable buttons on data stream notifications page #613](https://github.com/farmOS/farmOS/pull/613)

## [2.0.0-beta8.1] 2022-11-26

### Fixed

- [Fix update hook for converting lab to taxonomy #606](https://github.com/farmOS/farmOS/pull/606)

### Changed

- [Issue #3308740: Update Drush to ^11](https://www.drupal.org/project/farm/issues/3308740)
- Update [Migrate Plus](https://www.drupal.org/project/migrate_plus) and [Migrate Tools](https://www.drupal.org/project/migrate_tools) to ^6.

## [2.0.0-beta8] 2022-11-25

This release fixes an issue with the input log migration from farmOS v1. If you
have migrated input logs from farmOS v1, and they referenced multiple material
types, they may have been affected. An update hook is included with this
release that will attempt to re-connect to the v1 database used during
migration to automatically fix the issue. If the v1 database is no longer
available, then you will need to fix these logs manually. For more information,
see: https://github.com/farmOS/farmOS/issues/579

If you would like to skip the automatic fix, add the following line to your
`settings.php` (this can be removed after running update.php):

`$settings['farm_migrate_skip_input_log_migration_fix'] = TRUE;`

### Added

- [Add action to bulk categorize logs #590](https://github.com/farmOS/farmOS/pull/590)
- [Add action for bulk assigning asset parent #588](https://github.com/farmOS/farmOS/pull/588)
- [Show "Add log" action links on /asset/[id]/logs/[type] #596](https://github.com/farmOS/farmOS/pull/596)
- [Add "Date received" and "Date processed" fields to lab test logs](https://github.com/farmOS/farmOS/pull/602)
- [Add a test quantity type with test method taxonomy #604](https://github.com/farmOS/farmOS/pull/604)
- [Add tissue lab test type #605](https://github.com/farmOS/farmOS/pull/605)

### Changed

- Update farmOS-map to [v2.1.0](https://github.com/farmOS/farmOS-map/releases/tag/v2.1.0)
- [Issue #3311264: Coordinate upgrade of Consumers module to get client_id base field](https://www.drupal.org/project/farm/issues/3311264)
- [Issue #3282186: Update simple_oauth to ^5.2.2](https://www.drupal.org/project/farm/issues/3282186)
- [Make translatable strings consistent between fields and actions #594](https://github.com/farmOS/farmOS/pull/594)
- [Issue #3316925: Mark certain classes as @internal to indicate non-public APIs](https://www.drupal.org/project/farm/issues/3316925)
- [Render link to taxonomy terms in farm entity views #595](https://github.com/farmOS/farmOS/pull/595)
- [Issue #3203129: Use GitHub Actions to build Docker Hub images](https://www.drupal.org/project/farm/issues/3203129)
- [Announce new releases on Mastodon/Twitter via farmOS-microblog #599](https://github.com/farmOS/farmOS/pull/599)
- [Improve dependency relationships of asset/log/quantity modules #601](https://github.com/farmOS/farmOS/pull/601)
- [Convert lab field to term reference on lab test logs #603](https://github.com/farmOS/farmOS/pull/603)

### Fixed

- [Adapt csv export logic to support a type filter allowing single or multiple values. #584](https://github.com/farmOS/farmOS/pull/584)
- [Click sort columns expand filters fieldset #586](https://github.com/farmOS/farmOS/pull/586)
- [Issue #3189918: Broken relationship schema link in farmOS 2.x JSON:API](https://www.drupal.org/project/farm/issues/3189918)
- [Issue #3322227: Document schema title wrong for multiple resource types](https://www.drupal.org/project/jsonapi_schema/issues/3322227)
- [Change default client secret to be NULL to avoid issue #3322325 #597](https://github.com/farmOS/farmOS/pull/597)
- [Fix input log quantity material migration #598](https://github.com/farmOS/farmOS/pull/598)
- Patch simple_oauth to fix [Issue #3322325: Cannot authorize clients with empty string set as secret](https://www.drupal.org/project/simple_oauth/issues/3322325)

## [2.0.0-beta7] 2022-09-29

### Added

- [Add button/menu item for data stream notifications collection page #555](https://github.com/farmOS/farmOS/pull/555)
- [Issue #3306227: Dispatch events for asset presave, insert, update, delete](https://www.drupal.org/project/farm/issues/3306227)
- [Issue #3306344: Allow views exposed filters to be collapsed](https://www.drupal.org/project/farm/issues/3306344)
- [Issue #3309234: Add PHPStan to test and delivery workflow](https://www.drupal.org/project/farm/issues/3309234)
- [Issue #3309198: Allow users to override Gin theme settings](https://www.drupal.org/project/farm/issues/3309198)
- [Add owner field to assets #537](https://github.com/farmOS/farmOS/pull/537)
- [Add log asset filter to all displays of farm_quantity view #569](https://github.com/farmOS/farmOS/pull/569)

### Changed

- [Improve API docs #557](https://github.com/farmOS/farmOS/pull/557)
- [Issue #3305724 by pcambra, m.stenta: Allow map type and behaviors to be configurable in map blocks](https://www.drupal.org/project/farm/issues/3305724)
- [Update Drupal core to 9.4.x](https://github.com/farmOS/farmOS/pull/566)
- [Update AssetLocationInterface::getAssetsByLocation to return asset objects keyed by ID #565](https://github.com/farmOS/farmOS/pull/565)
- [Simplify location query processing of asset ids #564](https://github.com/farmOS/farmOS/pull/564)
- Update farmOS-map to [v2.0.7](https://github.com/farmOS/farmOS-map/releases/tag/v2.0.7) to [improve edit control icons](https://github.com/farmOS/farmOS-map/pull/179)

### Fixed

- [Fix for Autocomplete dropdown not showing in Chrome on Android #552](https://github.com/farmOS/farmOS/issues/552)
- [Uncaught (in promise) TypeError: instance.editAttached is undefined #550](https://github.com/farmOS/farmOS/issues/550)
- [Map form element #required is not enforced #560](https://github.com/farmOS/farmOS/issues/560)
- [Incorrect translation placeholders for asset names #540](https://github.com/farmOS/farmOS/issues/540)
- Update farmOS-map to [v2.0.5](https://github.com/farmOS/farmOS-map/releases/tag/v2.0.5) to fix [Uncaught (in promise) TypeError: o.getChangeEventType is not a function #551](https://github.com/farmOS/farmOS/issues/551)
- [Fix [warning] Invalid argument supplied for foreach() EntityViewsDataTaxonomyFilterTrait.php:26 #575](https://github.com/farmOS/farmOS/pull/575)
- [Set reduce_duplicates: true in Views exposed filters for multivalue fields #571](https://github.com/farmOS/farmOS/pull/571)
- [Update core map behaviors to properly depend on core/drupalSettings #578](https://github.com/farmOS/farmOS/pull/578)
- [Issue #3243922 by paul121, Symbioquine, m.stenta: farmOS-map chunks fail to load when map is rendered via AJAX](https://www.drupal.org/project/farm/issues/3243922)

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

[Unreleased]: https://github.com/farmOS/farmOS/compare/2.1.1...HEAD
[2.1.1]: https://github.com/farmOS/farmOS/releases/tag/2.1.1
[2.1.0]: https://github.com/farmOS/farmOS/releases/tag/2.1.0
[2.0.4]: https://github.com/farmOS/farmOS/releases/tag/2.0.4
[2.0.3]: https://github.com/farmOS/farmOS/releases/tag/2.0.3
[2.0.2]: https://github.com/farmOS/farmOS/releases/tag/2.0.2
[2.0.1]: https://github.com/farmOS/farmOS/releases/tag/2.0.1
[2.0.0]: https://github.com/farmOS/farmOS/releases/tag/2.0.0
[2.0.0-rc1]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-rc1
[2.0.0-beta8.1]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta8.1
[2.0.0-beta8]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta8
[2.0.0-beta7]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta7
[2.0.0-beta6]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta6
[2.0.0-beta5]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta5
[2.0.0-beta4]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta4
[2.0.0-beta3]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta3
[2.0.0-beta2]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta2
[2.0.0-beta1]: https://github.com/farmOS/farmOS/releases/tag/2.0.0-beta1
