api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.54

; Patch core to use vocabulary machine names in permissions.
projects[drupal][patch][995156] = http://drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

; Patch core to fix file_ajax_upload() causes malformed Drupal.settings.
projects[drupal][patch][2870289] = http://www.drupal.org/files/issues/file_ajax_upload-2870289-2.patch
