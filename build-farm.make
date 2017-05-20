api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

includes[] = drupal-org-core.make

; -----------------------------------------------------------------------------
; farmOS installation profile
; -----------------------------------------------------------------------------

projects[farm][type] = profile
;projects[farm][version] = 1.0-beta13
projects[farm][download][type] = git
projects[farm][download][url] = http://github.com/farmOS/farm.git
projects[farm][download][branch] = 7.x-1.x

