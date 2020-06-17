api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.72

; Patch core to fix Issue #995156: Use vocabulary machine name for permissions
projects[drupal][patch][995156] = http://drupal.org/files/issues/995156-5_portable_taxonomy_permissions_0.patch

; Patch core to fix Issue #2057693: PostgreSQL orderBy method adds fields it doesn't need to, leading to fatal errors
; when the result is used as an insert subquery
projects[drupal][patch][2057693] = http://www.drupal.org/files/issues/2019-04-30/2057693-D7-42.patch

; Patch core to fix Issue #1327224: Access denied to taxonomy term image
projects[drupal][patch][1327224] = http://www.drupal.org/files/issues/2018-07-12/taxonomy-term-file-download-access-d7-1327224-79.patch
