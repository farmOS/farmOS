api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Modules (contrib)
; -----------------------------------------------------------------------------

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.4"

projects[date][subdir] = "contrib"
projects[date][version] = "2.7"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.3"

projects[entityreference][subdir] = "contrib"
projects[entityreference][version] = "1.1"

projects[features][subdir] = "contrib"
projects[features][version] = "1.0"

projects[field_group][subdir] = "contrib"
projects[field_group][version] = "1.3"

projects[fraction][subdir] = "contrib"
projects[fraction][version] = "1.1"

projects[log][subdir] = "contrib"
projects[log][version] = "1.x"

projects[views][subdir] = "contrib"
projects[views][version] = "3.7"

; -----------------------------------------------------------------------------
; Modules (farm)
; -----------------------------------------------------------------------------

projects[farm_taxonomy][type] = "module"
projects[farm_taxonomy][subdir] = "farm"
projects[farm_taxonomy][download][type] = "git"
projects[farm_taxonomy][download][url] = "http://github.com/mstenta/farm_taxonomy.git"
projects[farm_taxonomy][download][branch] = "7.x-1.x"

projects[farm_log][type] = "module"
projects[farm_log][subdir] = "farm"
projects[farm_log][download][type] = "git"
projects[farm_log][download][url] = "http://github.com/mstenta/farm_log.git"
projects[farm_log][download][branch] = "7.x-1.x"
