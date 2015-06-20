api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.38

; Patch core to use vocabulary machine names in permissions.
projects[drupal][patch][995156] = http://drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

; Patch core to allow migration of field_farm_area_type from allowed_values
; to allowed_values_function.
; @TODO: Remove this patch after 7.x-1.0-beta4 release.
projects[drupal][patch][2453195] = https://www.drupal.org/files/issues/2453195-no-exception-with-allowed-values-function-7x-4.patch

