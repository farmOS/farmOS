api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.34

; Patch core to use vocabulary machine names in permissions.
projects[drupal][patch][995156] = http://drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

; -----------------------------------------------------------------------------
; FarmOS installation profile
; -----------------------------------------------------------------------------

projects[farm][type] = profile
projects[farm][download][type] = git
projects[farm][download][url] = http://git.drupal.org/project/farm.git
projects[farm][download][branch] = 7.x-1.x
