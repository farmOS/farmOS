# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed

- [Update entity type definitions in key_value store #1091](https://github.com/farmOS/farmOS/pull/1091)

## [4.0.3] 2026-06-18

### Changed

- Update [drupal/simple_oauth](https://www.drupal.org/project/simple_oauth) to
  [^6.1.1](https://www.drupal.org/project/simple_oauth/releases/6.1.1) and
  remove patch for
  [Issue #3573262: Calculated permissions have a cache max age of 0](https://www.drupal.org/project/simple_oauth/issues/3573262)
  (merged upstream).
- Update [drupal/gin](https://www.drupal.org/project/gin) to
  [5.0.13](https://www.drupal.org/project/gin/releases/5.0.13) (fixes
  [Issue #3567613: Show sidebar toggle is not usable](https://www.drupal.org/project/gin/issues/3567613)).
- [Use Drupal core RevisionHtmlRouteProvider #1087](https://github.com/farmOS/farmOS/pull/1087)

### Fixed

- [Fix dropbutton margin in local actions block #1086](https://github.com/farmOS/farmOS/pull/1086)

### Security

- Update Drupal core to
  [11.3.12](https://www.drupal.org/project/drupal/releases/11.3.12) for
  [SA-CORE-2026-005](https://www.drupal.org/sa-core-2026-005),
  [SA-CORE-2026-006](https://www.drupal.org/sa-core-2026-006),
  [SA-CORE-2026-007](https://www.drupal.org/sa-core-2026-007),
  [SA-CORE-2026-008](https://www.drupal.org/sa-core-2026-008), and
  [SA-CORE-2026-009](https://www.drupal.org/sa-core-2026-009).

## [4.0.2] 2026-05-20

### Deprecated

- [EntityFieldManager::rebuildBundleFieldMap() is deprecated](https://www.drupal.org/node/3591108)

### Fixed

- [Fix dashboard block title access #1075](https://github.com/farmOS/farmOS/pull/1075)

### Security

- Updated Drupal core to 11.3.10 for
  [SA-CORE-2026-004](https://www.drupal.org/sa-core-2026-004).

## [4.0.1] 2026-04-16

### Security

- Updated Drupal core to 11.3.7 for
  [SA-CORE-2026-001](https://www.drupal.org/sa-core-2026-001), and
  [SA-CORE-2026-002](https://www.drupal.org/sa-core-2026-002).

## [4.0.0] 2026-03-20

This is the first "stable" release of farmOS v4.

Read the [Community blog post](https://farmOS.org/blog/2026/farmOS-v4-and-v3.5/)
for a summary of notable changes.

If you are updating from farmOS v3, please refer to the release notes for
breaking changes in [4.0.0-beta1], [4.0.0-beta2], [4.0.0-beta3], and
[4.0.0-beta4] below.

### Changed

- [Validate that logs only reference assets in the same farm #1070](https://github.com/farmOS/farmOS/pull/1070)

### Fixed

- [Fix ContentEntityGeometryNormalizer support check #1064](https://github.com/farmOS/farmOS/pull/1064)

## [4.0.0-beta4] 2026-03-09

### Fixed

- [Fix Fatal error: Trait FarmFormProtectionTrait not found during update from 3.x #1063](https://github.com/farmOS/farmOS/pull/1063)
- [Allow importing taxonomy term CSVs without parent column #1054](https://github.com/farmOS/farmOS/pull/1054)
- [Fix exposed taxonomy term reference filters in Views #1060](https://github.com/farmOS/farmOS/pull/1060)
- [Fix asset parent/child farm validation when neither asset is in a farm #1061](https://github.com/farmOS/farmOS/pull/1061)

### Changed

- [Move "Add log" action and asset field population logic to farm_log_asset module #1053](https://github.com/farmOS/farmOS/pull/1053)
- Update PostgreSQL to 17 in Docker Compose configurations.

## [4.0.0-beta3] 2026-02-21

### Added

- [Document browser version support #1047](https://github.com/farmOS/farmOS/pull/1047)

### Changed

- [Change comment form "Save" button to "Save comment" #1046](https://github.com/farmOS/farmOS/pull/1046)

### Fixed

- [Fix missing action links on entity collection pages #1050](https://github.com/farmOS/farmOS/pull/1050)
- [Fix asset and plan update hooks for prefixed database tables #1051](https://github.com/farmOS/farmOS/pull/1051)
- [Validate entity bundle in asset/log/etc page_type display logic #1048](https://github.com/farmOS/farmOS/pull/1048)
- [Clear access_policy cache when modules are installed #1049](https://github.com/farmOS/farmOS/pull/1049)

## [4.0.0-beta2] 2026-02-12

### Changed

- Update `drupal/simple_oauth` patch for [Issue #3573262: Calculated permissions have a cache max age of 0](https://www.drupal.org/project/simple_oauth/issues/3573262) [#1044](https://github.com/farmOS/farmOS/pull/1044)

## [4.0.0-beta1] 2026-02-11

This is the first release of the farmOS 4.x branch, following
[semantic versioning](https://semver.org/). This means changes have been made
which may be incompatible with existing integrations. These "breaking" changes
are described below, with links to specific issues/pull requests for more
details.

Update to the latest farmOS 3.x release before attempting to update to farmOS
4.x. All the automated database and configuration updates for the farmOS 2.x and
3.x branches have been removed. Updating to the latest release on the 3.x branch
will ensure that all necessary changes have been applied before updating to 4.x.

Please note that pre-built "packaged" releases of farmOS are no longer one of
the recommended methods for hosting farmOS. They will still be generated with
each release, but system administrators are encouraged to move to a deployment
workflow that uses Docker and/or Composer. See the updated farmOS installation
guide for more information.

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

farmOS v4 requires PHP 8.4, and has the following minimum database version
requirements (inherited from
[Drupal 11](https://www.drupal.org/docs/getting-started/system-requirements/database-server-requirements)):

- PostgreSQL 16+
- MariaDB 10.6+
- MySQL/Percona 8.0+
- SQLite 3.45+

### Added

- [Add a farmOS setup wizard #1035](https://github.com/farmOS/farmOS/pull/1035)
- [Add an Organization entity type with a Farm bundle #849](https://github.com/farmOS/farmOS/pull/849)
- [Add Google Maps base layers #946](https://github.com/farmOS/farmOS/pull/946)
- [Allow API access to organizations #1010](https://github.com/farmOS/farmOS/pull/1010)
- [Issue #3304608: Add an "abandoned" log status](https://www.drupal.org/project/farm/issues/3304608)
- [Add default plan status options: "planning", "done", "abandoned" #986](https://github.com/farmOS/farmOS/pull/986)
- [Add Term Merge module #961](https://github.com/farmOS/farmOS/pull/961)
- [Add a Config Admin role for granting access to farmOS configuration #1022](https://github.com/farmOS/farmOS/pull/1022)
- [Add support for attributes in farmOS plugin types #963](https://github.com/farmOS/farmOS/pull/963)
- [Set asset/log flags via CSV importers #955](https://github.com/farmOS/farmOS/pull/955)
- [Add a hook for excluding fields from CSV importers #958](https://github.com/farmOS/farmOS/pull/958)
- [Include config fields in CSV importers #959](https://github.com/farmOS/farmOS/pull/959)
- Add support for decimal and integer fields in CSV importers.
- [Add map layer for "Other Location" assets #966](https://github.com/farmOS/farmOS/pull/966)
- [Add ability to assign plan ownership #1015](https://github.com/farmOS/farmOS/pull/1015)
- [Show violation messages when validation fails in bulk action forms #1018](https://github.com/farmOS/farmOS/pull/1018)
- [Add warning when navigating away from forms with unsaved changes #1025](https://github.com/farmOS/farmOS/pull/1025)
- [Document how to use symfony/mailer for email SMTP relay #844](https://github.com/farmOS/farmOS/pull/844)
- [Document setup of local Git repositories #1032](https://github.com/farmOS/farmOS/pull/1032)

### Changed

- [farmOS 4.x requires PHP 8.4 #979](https://github.com/farmOS/farmOS/pull/979)
- [Issue #3488916: Update Drupal core to 11.x](https://www.drupal.org/project/farm/issues/3488916)
- [Update farmOS-map to v3.0.0 #1038](https://github.com/farmOS/farmOS/pull/1038)
- [Run Docker container as www-data user #1009](https://github.com/farmOS/farmOS/pull/1009)
- [Declare PHP extensions as required: bcmath, exif, geos #1013](https://github.com/farmOS/farmOS/pull/1013)
- [Update Drupal to v11.2, PHPUnit to v11, PHPStan to v2 #980](https://github.com/farmOS/farmOS/pull/980)
- [Update Docker base image to Debian Trixie #992](https://github.com/farmOS/farmOS/pull/992)
- [Change how assets and plans are archived #986](https://github.com/farmOS/farmOS/pull/986)
- [Change Animal asset is_castrated attribute to is_sterile #960](https://github.com/farmOS/farmOS/pull/960)
- [Convert equipment to a base field #956](https://github.com/farmOS/farmOS/pull/956)
- [Convert quick to a base field #957](https://github.com/farmOS/farmOS/pull/957)
- [Make farmOS API OAuth2 Server optional #973](https://github.com/farmOS/farmOS/pull/973)
- [Make the farmOS API optional #974](https://github.com/farmOS/farmOS/pull/974)
- [Move QuantityCsvNormalizer to farm_csv module #977](https://github.com/farmOS/farmOS/pull/977)
- [The farm_account_admin role has moved to a new farm_account_admin module](https://www.drupal.org/node/3527786)
- [The farm_manager, farm_worker, and farm_viewer roles have moved to their own modules](https://www.drupal.org/node/3527787)
- [Allow plans without active status in farm_plan View #978](https://github.com/farmOS/farmOS/pull/978)
- [Rename primary tab in edit forms to General #985](https://github.com/farmOS/farmOS/pull/985)
- [Inject entity_type.manager and current_user service dependencies into QuickFormBase class #989](https://github.com/farmOS/farmOS/pull/989)
- [Allow the "type" base field view display to be configured #990](https://github.com/farmOS/farmOS/pull/990)
- [Title improvements to entity forms, buttons, and Views #996](https://github.com/farmOS/farmOS/pull/996)
- [Use PHP 8 constructor property promotion #1005](https://github.com/farmOS/farmOS/pull/1005)
- [Autowire injected service dependencies #1006](https://github.com/farmOS/farmOS/pull/1006)
- [Autowire classes that extend from PluginBase #1008](https://github.com/farmOS/farmOS/pull/1008)
- [Convert all procedural hook implementations to Hook classes #1007](https://github.com/farmOS/farmOS/pull/1007)
- [Implement ManagedRolePermissions access policy and simple_oauth scope granularity #922](https://github.com/farmOS/farmOS/pull/922)
- [Change farmOS Update config revert logic from opt-out to opt-in #1011](https://github.com/farmOS/farmOS/pull/1011)
- [Issue #3413263: Use Entity API query access handler to filter entity queries based on user permissions](https://www.drupal.org/project/farm/issues/3413263)
- [Allow more granular access to views #965](https://github.com/farmOS/farmOS/pull/965)
- [Do not set blank revision log messages #1029](https://github.com/farmOS/farmOS/pull/1029)
- [Sort user selection alphabetically by name #1026](https://github.com/farmOS/farmOS/pull/1026)
- [Sort "Active plans" by last updated #1027](https://github.com/farmOS/farmOS/pull/1027)
- [Do not use birth log label in child revision log message #1019](https://github.com/farmOS/farmOS/pull/1019)
- [Soften farm_field module dependencies #1020](https://github.com/farmOS/farmOS/pull/1020)
- [Workers can only delete their own records #1033](https://github.com/farmOS/farmOS/pull/1033)
- [Workers cannot update or delete taxonomy terms #1033](https://github.com/farmOS/farmOS/pull/1033)
- [Allow birth quick form to create up to 20 children #1030](https://github.com/farmOS/farmOS/pull/1030)
- [Always consider revision translations affected #1041](https://github.com/farmOS/farmOS/pull/1041)
- [Update drupal/simple_oauth to ^6.1 #1028](https://github.com/farmOS/farmOS/pull/1028)

### Deprecated

- [farmOS core plugin type annotations are deprecated](https://www.drupal.org/node/3523485)
- [Asset entity last_archived base field is deprecated](https://www.drupal.org/node/3539444)

### Removed

- [Remove asset status field #986](https://github.com/farmOS/farmOS/pull/986)
- [Move Field Kit module to contrib #1037](https://github.com/farmOS/farmOS/pull/1037)
- [Remove 2.x and 3.x update hooks #962](https://github.com/farmOS/farmOS/pull/962)
- Remove [deprecated Drupal 7 asset migration source plugin](https://www.drupal.org/node/3410747)
- Remove [deprecated Drupal 7 plan migration source plugin](https://www.drupal.org/node/3498065)
- Remove [deprecated data stream migration plugins](https://www.drupal.org/node/3498069)
- Remove [EXIF Orientation](https://www.drupal.org/project/exif_orientation) module (see [Automatically rotate derivative images based on EXIF Orientation #941](https://github.com/farmOS/farmOS/pull/941))
- Remove [JSON:API Extras](https://www.drupal.org/project/jsonapi_extras) module (see [Remove dependency on JSON:API Extras module #964](https://github.com/farmOS/farmOS/pull/964))
- Remove [Migrate Source UI](https://www.drupal.org/project/migrate_source_ui) module (see [Remove dependency on Migrate Source UI module #994](https://github.com/farmOS/farmOS/pull/994))
- [Do not generate keys during farm_api_install() #972](https://github.com/farmOS/farmOS/pull/972)
- [Remove asset_admin and plan_admin Views #1012](https://github.com/farmOS/farmOS/pull/1012)
- Remove `farm_settings` module (see [The farm_settings module has been merged into farm_setup](https://www.drupal.org/node/3559903))
- [Remove farm_install_modules() installation task #1034](https://github.com/farmOS/farmOS/pull/1034)

### Fixed

- [Fix plan_record bundle field providers #969](https://github.com/farmOS/farmOS/pull/969)
- [Fix logic for forcing entity revision creation #988](https://github.com/farmOS/farmOS/pull/969)
- [Fix CSV importer compatibility with migrate_source_ui v1.3+ #993](https://github.com/farmOS/farmOS/pull/993)
- [Check that real path exists before loading exif data #1000](https://github.com/farmOS/farmOS/pull/1000)
- [Do not allow entity revisions to be reverted #1004](https://github.com/farmOS/farmOS/pull/1004)
- [Fix dashboard block access checking #1031](https://github.com/farmOS/farmOS/pull/1031)
- [Fix page for adding quick form instances #1017](https://github.com/farmOS/farmOS/pull/1017)
- [Fix errors during update from 3.x #1042](https://github.com/farmOS/farmOS/pull/1042)

## farmOS 3.x

farmOS 3.x release notes are available in the 3.x branch's
[CHANGELOG.md](https://github.com/farmOS/farmOS/blob/3.x/CHANGELOG.md)

## farmOS 2.x

farmOS 2.x release notes are available in the 2.x branch's
[CHANGELOG.md](https://github.com/farmOS/farmOS/blob/2.x/CHANGELOG.md)

## farmOS 1.x

farmOS 1.x release notes are available in the
[farmOS releases on Drupal.org](https://www.drupal.org/project/farm/releases?version=7.x-1).

[Unreleased]: https://github.com/farmOS/farmOS/compare/4.0.3...4.x
[4.0.3]: https://github.com/farmOS/farmOS/releases/tag/4.0.3
[4.0.2]: https://github.com/farmOS/farmOS/releases/tag/4.0.2
[4.0.1]: https://github.com/farmOS/farmOS/releases/tag/4.0.1
[4.0.0]: https://github.com/farmOS/farmOS/releases/tag/4.0.0
[4.0.0-beta4]: https://github.com/farmOS/farmOS/releases/tag/4.0.0-beta4
[4.0.0-beta3]: https://github.com/farmOS/farmOS/releases/tag/4.0.0-beta3
[4.0.0-beta2]: https://github.com/farmOS/farmOS/releases/tag/4.0.0-beta2
[4.0.0-beta1]: https://github.com/farmOS/farmOS/releases/tag/4.0.0-beta1
