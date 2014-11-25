api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Drupal core
; -----------------------------------------------------------------------------

projects[drupal][type] = core
projects[drupal][version] = 7.34

; -----------------------------------------------------------------------------
; FarmOS installation profile
; -----------------------------------------------------------------------------

projects[farm][type] = profile
projects[farm][download][type] = git
projects[farm][download][url] = http://git.drupal.org/project/farm.git
projects[farm][download][branch] = 7.x-1.x
