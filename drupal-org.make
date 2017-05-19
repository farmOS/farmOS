api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Modules (contrib)
; -----------------------------------------------------------------------------

projects[bootstrap_tour][subdir] = "contrib"
projects[bootstrap_tour][version] = "2.5"
; Patch to fix secondary tours not running after you end the first one.
projects[bootstrap_tour][patch][] = "http://www.drupal.org/files/issues/add_unique_tour_name_to_avoid_local_storage_collisions-2533524-9.patch"

projects[colorbox][subdir] = "contrib"
projects[colorbox][version] = "2.13"

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.12"
; Patch to fix Issue #2643108: Entity reference popup not centered in Linux
projects[ctools][patch][] = "http://www.drupal.org/files/issues/ctools-fix_modal_position_after_ajax-1803104-13.patch"

projects[date][subdir] = "contrib"
projects[date][version] = "2.10"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.8"

projects[entityreference][subdir] = "contrib"
projects[entityreference][version] = "1.3"

projects[entityreference_view_widget][subdir] = "contrib"
projects[entityreference_view_widget][version] = "2.0-rc7"
; Patch to fix blank checkboxes.
projects[entityreference_view_widget][patch][] = "http://www.drupal.org/files/issues/entityreference_view_widget_suffix-2524296-2.patch"

projects[exif_orientation][subdir] = "contrib"
projects[exif_orientation][version] = "1.1"

projects[features][subdir] = "contrib"
projects[features][version] = "2.10"

projects[field_collection][subdir] = "contrib"
projects[field_collection][version] = "1.0-beta12"

projects[field_group][subdir] = "contrib"
projects[field_group][version] = "1.5"

projects[fraction][subdir] = "contrib"
projects[fraction][version] = "1.4"

projects[geocoder][subdir] = "contrib"
projects[geocoder][version] = "1.3"

projects[geofield][subdir] = "contrib"
projects[geofield][version] = "2.3"
; Patch to fix deleting map features.
projects[geofield][patch][] = "http://www.drupal.org/files/issues/geofield-delete_feature_fix-1350320-20.patch"

projects[geophp][subdir] = "contrib"
projects[geophp][version] = "1.x-dev"
; Patch to use BCMath for arithmetic.
projects[geophp][patch][] = "http://www.drupal.org/files/issues/geophp_bcmath-2625348-1.patch"

projects[inline_entity_form][subdir] = "contrib"
projects[inline_entity_form][version] = "1.8"

projects[jquery_update][subdir] = "contrib"
projects[jquery_update][version] = "3.0-alpha5"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.3"

projects[libraries_cdn][subdir] = "contrib"
projects[libraries_cdn][version] = "1.7"

projects[log][subdir] = "contrib"
projects[log][version] = "1.7"

projects[multiupload_filefield_widget][subdir] = "contrib"
projects[multiupload_filefield_widget][version] = "1.13"

projects[multiupload_imagefield_widget][subdir] = "contrib"
projects[multiupload_imagefield_widget][version] = "1.3"

projects[navbar][subdir] = "contrib"
projects[navbar][version] = "1.7"

projects[openlayers][subdir] = "contrib"
projects[openlayers][version] = "3.1"
; Remove base layer assignment code.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_base_layer_mechanism-2543130-4.patch"
; Move Elements dependency to Openlayers Examples.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_examples_elements_dependency-2620098-2.patch"
; Switch to ROADMAP when zoomed further than Google's max zoom level.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_gmap_zoom_switch_type-2680273-1.patch"
; Add a jQuery trigger event when popups are displayed.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_popup_trigger-2687781-1.patch"
; Fix map behavior detach.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_detach-2688029-5.patch"
; Move geocoder AJAX callback from Geofield module.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_widget_geocode_callback-2762367-1.patch"
; Fix Geocode from field.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_geocode_button_fix-2755899-9.patch"

projects[openlayers_geolocate_button][subdir] = "contrib"
projects[openlayers_geolocate_button][version] = "3.2"

projects[pathauto][subdir] = "contrib"
projects[pathauto][version] = "1.3"
; Fix SQLite3 issue.
projects[pathauto][patch][] = "http://www.drupal.org/files/issues/2582655-path-pathauto-is-alias-reserved-db-query.patch"

projects[pathauto_entity][subdir] = "contrib"
projects[pathauto_entity][version] = "1.0"

projects[registry_autoload][subdir] = "contrib"
projects[registry_autoload][version] = "1.3"

projects[restws][subdir] = "contrib"
projects[restws][version] = "2.7"

projects[role_delegation][subdir] = "contrib"
projects[role_delegation][version] = "1.1"

projects[service_container][subdir] = "contrib"
projects[service_container][version] = "1.0-beta5"

projects[strongarm][subdir] = "contrib"
projects[strongarm][version] = "2.0"

projects[token][subdir] = "contrib"
projects[token][version] = "1.7"

projects[views][subdir] = "contrib"
projects[views][version] = "3.15"

projects[views_bulk_operations][subdir] = "contrib"
projects[views_bulk_operations][version] = "3.4"

projects[views_data_export][subdir] = "contrib"
projects[views_data_export][version] = "3.2"

projects[views_geojson][subdir] = "contrib"
projects[views_geojson][version] = "1.0-beta3"

projects[views_tree][subdir] = "contrib"
projects[views_tree][version] = "2.0"

; -----------------------------------------------------------------------------
; Modules (Development)
; -----------------------------------------------------------------------------

projects[diff][subdir] = "dev"
projects[diff][version] = "3.3"

projects[module_filter][subdir] = "dev"
projects[module_filter][version] = "2.0"

; -----------------------------------------------------------------------------
; Modules (farm)
; -----------------------------------------------------------------------------

projects[farm_access][subdir] = "farm"
;projects[farm_access][version] = "1.2"
projects[farm_access][download][type] = git
projects[farm_access][download][branch] = 7.x-1.x

projects[farm_admin][subdir] = "farm"
;projects[farm_admin][version] = "1.0-beta10"
projects[farm_admin][download][type] = git
projects[farm_admin][download][branch] = 7.x-1.x

projects[farm_area][subdir] = "farm"
;projects[farm_area][version] = "1.0-beta11"
projects[farm_area][download][type] = git
projects[farm_area][download][branch] = 7.x-1.x

projects[farm_asset][subdir] = "farm"
;projects[farm_asset][version] = "1.0-beta9"
projects[farm_asset][download][type] = git
projects[farm_asset][download][branch] = 7.x-1.x

projects[farm_crop][subdir] = "farm"
;projects[farm_crop][version] = "1.0-beta12"
projects[farm_crop][download][type] = git
projects[farm_crop][download][branch] = 7.x-1.x

projects[farm_equipment][subdir] = "farm"
;projects[farm_equipment][version] = "1.0-beta12"
projects[farm_equipment][download][type] = git
projects[farm_equipment][download][branch] = 7.x-1.x

projects[farm_fields][subdir] = "farm"
;projects[farm_fields][version] = "1.0-beta4"
projects[farm_fields][download][type] = git
projects[farm_fields][download][branch] = 7.x-1.x

projects[farm_livestock][subdir] = "farm"
;projects[farm_livestock][version] = "1.0-beta12"
projects[farm_livestock][download][type] = git
projects[farm_livestock][download][branch] = 7.x-1.x

projects[farm_log][subdir] = "farm"
;projects[farm_log][version] = "1.0-beta12"
projects[farm_log][download][type] = git
projects[farm_log][download][branch] = 7.x-1.x

projects[farm_map][subdir] = "farm"
;projects[farm_map][version] = "1.0-beta10"
projects[farm_map][download][type] = git
projects[farm_map][download][branch] = 7.x-1.x

projects[farm_mapknitter][subdir] = "farm"
;projects[farm_mapknitter][version] = "1.2"
projects[farm_mapknitter][download][type] = git
projects[farm_mapknitter][download][branch] = 7.x-1.x

projects[farm_quantity][subdir] = "farm"
;projects[farm_quantity][version] = "1.0-beta1"
projects[farm_quantity][download][type] = git
projects[farm_quantity][download][branch] = 7.x-1.x

projects[farm_sensor][subdir] = "farm"
;projects[farm_sensor][version] = "1.0-beta9"
projects[farm_sensor][download][type] = git
projects[farm_sensor][download][branch] = 7.x-1.x

projects[farm_soil][subdir] = "farm"
;projects[farm_soil][version] = "1.0-beta11"
projects[farm_soil][download][type] = git
projects[farm_soil][download][branch] = 7.x-1.x

projects[farm_taxonomy][subdir] = "farm"
;projects[farm_taxonomy][version] = "1.0-beta7"
projects[farm_taxonomy][download][type] = git
projects[farm_taxonomy][download][branch] = 7.x-1.x

projects[farm_tour][subdir] = "farm"
;projects[farm_tour][version] = "1.0-beta6"
projects[farm_tour][download][type] = git
projects[farm_tour][download][branch] = 7.x-1.x

; -----------------------------------------------------------------------------
; Themes
; -----------------------------------------------------------------------------

projects[bootstrap][version] = "3.13"

projects[farm_theme][type] = "theme"
;projects[farm_theme][version] = "1.0-beta12"
projects[farm_theme][download][type] = git
projects[farm_theme][download][branch] = 7.x-1.x

; -----------------------------------------------------------------------------
; Libraries
; -----------------------------------------------------------------------------

libraries[backbone][download][type] = "get"
libraries[backbone][download][url] = "http://github.com/jashkenas/backbone/archive/1.1.2.zip"

libraries[modernizr][download][type] = "get"
libraries[modernizr][download][url] = "http://github.com/Modernizr/Modernizr/archive/v2.8.3.zip"

libraries[underscore][download][type] = "get"
libraries[underscore][download][url] = "http://github.com/jashkenas/underscore/archive/1.7.0.zip"

