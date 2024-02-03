# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.1.0] 2024-02-02

### Added

- [Announce new releases on farmOS.discourse.group #780](https://github.com/farmOS/farmOS/pull/780)
- [Add a Product asset type and Product type taxonomy #787](https://github.com/farmOS/farmOS/pull/787)
- [Inventory quick form #766](https://github.com/farmOS/farmOS/pull/766)
- [Add UI for creating instances of quick forms #785](https://github.com/farmOS/farmOS/pull/785)
- [Show map on /locations #779](https://github.com/farmOS/farmOS/pull/779)
- [Provide a plan_record entity type for plan record relationships with metadata #781](https://github.com/farmOS/farmOS/pull/781)

### Changed

- [Recommend running composer update twice #653](https://github.com/farmOS/farmOS/pull/786)
- [Edit form UI improvements #770](https://github.com/farmOS/farmOS/pull/770)
- [Improve asset and log CSV exports #783](https://github.com/farmOS/farmOS/pull/783)
- [Remove "All" from "Items per page" options in Views #776](https://github.com/farmOS/farmOS/pull/776)

## [3.0.1] 2024-01-18

### Added

- [Add min/max options to integer fields in farm_field.factory #768](https://github.com/farmOS/farmOS/pull/768)

### Changed

- Allow users with asset view access to see /asset/%id/locations.

### Fixed

- [Patch drupal/core to fix Issue #3414883: datetime_timestamp widget does not use default field value #771](https://github.com/farmOS/farmOS/pull/771)
- [Fix duplicated revision tab on entities #773](https://github.com/farmOS/farmOS/pull/773)
- Improve access checking on location hierarchy forms.

## [3.0.0] 2024-01-05

This is the first "stable" release of farmOS v3. See the release notes for
[3.0.0-beta1] below for more information about major changes in the 3.x branch,
including breaking changes to be aware of.

### Changed

- [Increase weight of Asset and Log tasks on canonical user route #757](https://github.com/farmOS/farmOS/pull/757)
- [Update Drupal core to 10.2](https://github.com/farmOS/farmOS/pull/765)

### Deprecated

- [Issue #3410701: Deprecate d7_asset plugin](https://www.drupal.org/project/farm/issues/3410701)

### Fixed

- [Correct alter hook to add password grant to static scopes #755](https://github.com/farmOS/farmOS/pull/755)
- [Use strict identical operator when checking geometry format #756](https://github.com/farmOS/farmOS/pull/756)

## [3.0.0-beta3] 2023-11-27

### Fixed

- [Fix KML serialization #753](https://github.com/farmOS/farmOS/pull/753)

## [3.0.0-beta2] 2023-11-03

### Fixed

- Fixed 3.0.0-betaX tagged release packaging of tarballs and Docker images.
- [Update csv_serialization dependency to ^4.0 #745](https://github.com/farmOS/farmOS/pull/745)
- [Fix warning message when rendering link to birth log #746](https://github.com/farmOS/farmOS/pull/746)

## [3.0.0-beta1] 2023-11-01

This is the first release of the farmOS 3.x branch, following
[semantic versioning](https://semver.org/). This means changes have been made which may be
incompatible with existing integrations. These "breaking" changes are described
below, with links to specific issues/pull requests for more details.

farmOS v3 updates Drupal to version 10. Drupal 9 is end-of-life as of
[November 1st, 2023](https://www.drupal.org/docs/understanding-drupal/drupal-9-release-date-and-what-it-means/how-long-will-drupal-9-be-supported).
If you have built any add-on modules for farmOS, you will need to check that
they are compatible with Drupal 10, and declare support in your `*.info.yml`
file by changing `core_version_requirement` from `^9` to `^9 || ^10` (to
indicate that it works on both versions), or just `^10` (to indicate that it
only works on Drupal 10). The PHPStan tool that is included with the farmOS
`3.x-dev` Docker image can be used to perform static analysis of your module
code to see if there are deprecations that need to be fixed. See
[farmOS coding standards](https://farmos.org/development/environment/code/) for
more information.

If you are using PostgreSQL, Drupal 10 requires PostgreSQL version 12 or
greater, with the `pg_trgm` extension enabled. If you have PostgreSQL 13 or
greater, the `pg_trgm` extension will be enabled automatically during farmOS
installation. PostgreSQL 12 users, or users who are updating from farmOS 2.x to
3.x, will need to enable it manually by running the following query after the
farmOS database has been created, but before farmOS is installed/updated:
`CREATE EXTENSION pg_trgm;`

The [Simple OAuth](https://www.drupal.org/project/simple_oauth) module has been
updated to version 6. This includes a few breaking changes which may affect API
integrations. farmOS includes code to handle the transition of its own OAuth
clients and scopes, but if you have made any additional clients that used
special roles they will also need to be updated. The biggest changes are that
the "Implicit" grant type has been removed, and the "Password Credentials" grant
type has been moved to an optional "Simple OAuth Password Grant" module, which
must be enabled in order to use that grant type. The default farmOS client that
is included with farmOS has also been moved to a separate module that is not
enabled by default. After the update to farmOS 3.x, all access tokens will be
invalidated, but refresh tokens will still work to get a new access token.

farmOS 2.x included code to help migrate data from a farmOS 1.x database. This
code has been removed from farmOS 3.x If you are still on farmOS 1.x, you will
need to *migrate* to farmOS 2.x, and then *update* to farmOS 3.x. For more
information, see
[Migrating from farmOS v1](https://farmOS.org/hosting/migration/).

Lastly, the following deprecated functions/methods have been removed:

- `farm_log_asset_names_summary()`
- `QuickFormInterface::getId()`

### Changed

- [Issue #3382616: Remove v1 migrations from farmOS 3.x](https://www.drupal.org/project/farm/issues/3382616)
- [QuickFormInterface::getId() is replaced by QuickFormInterface::getQuickId()](https://www.drupal.org/node/3379686)
- [Remove deprecated farm_log_asset_names_summary()](https://www.drupal.org/node/3359456)
- [Issue #3394069: Update quantities to use bundle permission granularity](https://www.drupal.org/node/3394069)
- [Issue #3357679: Allow material quantities to reference multiple material types](https://www.drupal.org/project/farm/issues/3357679)
- [Issue #3330490: Update Drupal core to 10.x in farmOS](https://www.drupal.org/project/farm/issues/3330490)
- [Issue #3256745: Move default farm OAuth2 client to a separate module](https://www.drupal.org/project/farm/issues/3256745)
- [Update Simple OAuth module to v6 #743](https://github.com/farmOS/farmOS/pull/743)
- [Issue #3396419: Make log timestamp required](https://www.drupal.org/project/log/issues/3396419)
- Patch JSON:API Schema module for [Issue #3397275: Use OptionsProviderInterface::getPossibleOptions() for allowed field values (anyOf / oneOf)](https://www.drupal.org/project/jsonapi_schema/issues/3397275)

### Fixed

- [Issue #3197581: Cache needs to be cleared after setting MapBox API key for the first time](https://www.drupal.org/project/farm/issues/3197581)

## [2.2.2] 2023-10-25

### Changed

- [Refactor Move and Group actions into quick forms #736](https://github.com/farmOS/farmOS/pull/736)

### Fixed

- [Fix planting quick form creating empty quantities #737](https://github.com/farmOS/farmOS/pull/737)
- [Add post update hook to install the new quick_form entity type on existing farmOS installations #738](https://github.com/farmOS/farmOS/pull/738)
- Patch State Machine module to fix [Issue #3396186: State constraint is not validated on new entities](https://www.drupal.org/project/state_machine/issues/3396186)

## [2.2.1] 2023-10-09

### Fixed

- [Fix asset_lookup and term_lookup exception messages #731](https://github.com/farmOS/farmOS/pull/731)
- [Prevent saving invalid ID tag types #725](https://github.com/farmOS/farmOS/issues/725)
- [Quick form actions cause RouteNotFoundException: Route farm.quick.[id] does not exist. #727](https://github.com/farmOS/farmOS/issues/727)

## [2.2.0] 2023-10-06

This is the second minor release of the farmOS 2.x branch, following
[semantic versioning](https://semver.org/). This means new functionality is
added in a backwards compatible manner.

farmOS 2.2.0 adds a CSV import module with importers for all asset, log, and
taxonomy term types, as well as a framework for module developers to build
their own custom importers for bespoke CSV templates. See
https://farmOS.org/development/module/csv for more information.

A new Group membership assignment quick form is provided for easily assigning
asset group membership. This works similar to the Movement quick form that was
added in v2.1.0.

This release also makes a number of UI/UX improvements, including a new Setup
menu item which will serve as a place for common "farm data setup" tasks.

See links below for more details.

## Added

- [farmOS v2 CSV import module #722](https://github.com/farmOS/farmOS/pull/722)
- [Add a Group membership assignment quick form #723](https://github.com/farmOS/farmOS/pull/723)
- [farmOS Setup Menu #706](https://github.com/farmOS/farmOS/pull/706)
- [Issue #3354935: Configurable quick forms](https://www.drupal.org/project/farm/issues/3354935)
- [Add an Account Admin role with permission to administer users and assign managed roles #714](https://github.com/farmOS/farmOS/pull/714)
- [Add action links to add location assets on locations page #709](https://github.com/farmOS/farmOS/pull/709)

### Changed

- [Dashboard improvements #712](https://github.com/farmOS/farmOS/pull/712)
- [Condense metrics UI #711](https://github.com/farmOS/farmOS/pull/711)
- [Condense views table UI #713](https://github.com/farmOS/farmOS/pull/713)
- [Decrease horizontal and top margins on pages #719](https://github.com/farmOS/farmOS/pull/719)
- [Misc quick form code and documentation improvements #703](https://github.com/farmOS/farmOS/pull/703)

### Deprecated

- [QuickFormInterface::getId() is replaced by QuickFormInterface::getQuickId()](https://www.drupal.org/node/3379686)
- [Issue #3359452: Deprecate farm_log_asset_names_summary()](https://www.drupal.org/project/farm/issues/3359452)
- [farmOS v1 migrations are deprecated and will be removed in farmOS 3.x](https://www.drupal.org/node/3382609)

### Fixed

- [Validate quantity entities created by create_quantity #721](https://github.com/farmOS/farmOS/pull/721)
- [Update farmOS-map to v2.2.2 #724](https://github.com/farmOS/farmOS/pull/724) to fix [Map Search Bar no longer generating results #197](https://github.com/farmOS/farmOS-map/issues/197)

## [2.1.3] 2023-09-20

### Changed

- [Use Gin border radius for the map #710](https://github.com/farmOS/farmOS/pull/710)

### Fixed

- [Fix composer.json version constraints for migrate_plus and migrate_tools #702](https://github.com/farmOS/farmOS/pull/702)
- [Fix birth log quick form apostrophe becomes &#039; #698](https://github.com/farmOS/farmOS/issues/698)
- [Exclude block.block.gin_content from farm_update #715](https://github.com/farmOS/farmOS/pull/715)
- [Fix hideable boolean settings form #718](https://github.com/farmOS/farmOS/pull/718)

### Security

- Update Drupal core to 9.5.11 for [SA-CORE-2023-006](https://www.drupal.org/sa-core-2023-006)

## [2.1.2] 2023-07-18

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

[Unreleased]: https://github.com/farmOS/farmOS/compare/3.1.0...HEAD
[3.1.0]: https://github.com/farmOS/farmOS/releases/tag/3.1.0
[3.0.1]: https://github.com/farmOS/farmOS/releases/tag/3.0.1
[3.0.0]: https://github.com/farmOS/farmOS/releases/tag/3.0.0
[3.0.0-beta3]: https://github.com/farmOS/farmOS/releases/tag/3.0.0-beta3
[3.0.0-beta2]: https://github.com/farmOS/farmOS/releases/tag/3.0.0-beta2
[3.0.0-beta1]: https://github.com/farmOS/farmOS/releases/tag/3.0.0-beta1
[2.2.2]: https://github.com/farmOS/farmOS/releases/tag/2.2.2
[2.2.1]: https://github.com/farmOS/farmOS/releases/tag/2.2.1
[2.2.0]: https://github.com/farmOS/farmOS/releases/tag/2.2.0
[2.1.3]: https://github.com/farmOS/farmOS/releases/tag/2.1.3
[2.1.2]: https://github.com/farmOS/farmOS/releases/tag/2.1.2
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
