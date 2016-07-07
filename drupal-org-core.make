api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.50

; Patch core to use vocabulary machine names in permissions.
projects[drupal][patch][995156] = http://drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

; Patch core to fix imagerotate() in PHP 5.5.
projects[drupal][patch][2215369] = http://www.drupal.org/files/issues/php_5_5_imagerotate-2215369-51.patch

