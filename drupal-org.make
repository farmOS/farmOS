api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Modules (contrib)
; -----------------------------------------------------------------------------

projects[bootstrap_tour][subdir] = "contrib"
projects[bootstrap_tour][version] = "2.4"
; Patch to fix role access control (https://www.drupal.org/node/2534986).
projects[bootstrap_tour][patch][] = "http://www.drupal.org/files/issues/access_by_role_doesn_t-2534986-1.patch"

projects[colorbox][subdir] = "contrib"
projects[colorbox][version] = "2.10"

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.9"
; Patch to fix "Autosubmit forms in modals are broken" (https://www.drupal.org/node/1823088).
projects[ctools][patch][] = "http://www.drupal.org/files/issues/eventBubblingBugIE8-1823088-9.patch"

projects[date][subdir] = "contrib"
projects[date][version] = "2.9"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.6"

projects[entityreference][subdir] = "contrib"
projects[entityreference][version] = "1.1"

projects[entityreference_view_widget][subdir] = "contrib"
projects[entityreference_view_widget][version] = "2.0-rc6"
; Patch to fix blank checkboxes.
projects[entityreference_view_widget][patch][] = "http://www.drupal.org/files/issues/entityreference_view_widget_suffix-2524296-2.patch"

projects[exif_orientation][subdir] = "contrib"
projects[exif_orientation][version] = "1.0"

projects[features][subdir] = "contrib"
projects[features][version] = "2.7"

projects[field_collection][subdir] = "contrib"
projects[field_collection][version] = "1.0-beta10"

projects[filefield_paths][subdir] = "contrib"
projects[filefield_paths][version] = "1.0"

projects[fraction][subdir] = "contrib"
projects[fraction][version] = "1.3"

projects[geocoder][subdir] = "contrib"
projects[geocoder][version] = "1.2"
; Patch to fix multi-value filefield support.
projects[geocoder][patch][] = "http://www.drupal.org/files/issues/geocoder_multivalue_filefields-2352887-1.patch"
; Patch to add support for KMZ files.
projects[geocoder][patch][] = "http://www.drupal.org/files/issues/geocoder_kmz-2352931-2.patch"

projects[geofield][subdir] = "contrib"
projects[geofield][version] = "2.3"
; Patch to fix deleting map features.
projects[geofield][patch][] = "http://www.drupal.org/files/issues/geofield-delete_feature_fix-1350320-20.patch"

projects[geophp][subdir] = "contrib"
projects[geophp][version] = "1.7"

projects[inline_entity_form][subdir] = "contrib"
projects[inline_entity_form][version] = "1.6"

projects[jquery_update][subdir] = "contrib"
projects[jquery_update][version] = "3.0-alpha3"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.2"

projects[libraries_cdn][subdir] = "contrib"
projects[libraries_cdn][version] = "1.7"

projects[log][subdir] = "contrib"
projects[log][version] = "1.2"

projects[logintoboggan][subdir] = "contrib"
projects[logintoboggan][version] = "1.5"
; Display "Request new password" link in access denied login form.
projects[logintoboggan][patch][] = "http://www.drupal.org/files/issues/logintoboggan-403passwordlink-1823764-5.patch"

projects[navbar][subdir] = "contrib"
projects[navbar][version] = "1.6"

projects[openlayers][subdir] = "contrib"
projects[openlayers][download][type] = "git"
projects[openlayers][download][url] = "http://git.drupal.org/project/openlayers.git"
projects[openlayers][download][branch] = "7.x-3.x"
projects[openlayers][download][revision] = "deee17fd3bf605a273f2bfacca40b84aa1993c65"
; Fix GoogleMaps in Geofield context.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_googlemaps_postbuild-2538168-5.patch"
; Remove base layer assignment code.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_base_layer_mechanism-2543130-4.patch"
; Set Google Maps tilt to 0.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_google_maps_tilt_0-2573005-1.patch"

projects[openlayers_geolocate_button][subdir] = "contrib"
projects[openlayers_geolocate_button][version] = "3.1"

projects[panels][subdir] = "contrib"
projects[panels][version] = "3.5"

projects[pathauto][subdir] = "contrib"
projects[pathauto][version] = "1.3"

projects[pathauto_entity][subdir] = "contrib"
projects[pathauto_entity][version] = "1.0"

projects[registry_autoload][subdir] = "contrib"
projects[registry_autoload][version] = "1.3"

projects[restws][subdir] = "contrib"
projects[restws][version] = "2.4"

projects[role_delegation][subdir] = "contrib"
projects[role_delegation][version] = "1.1"

projects[service_container][subdir] = "contrib"
projects[service_container][version] = "1.0-beta5"

projects[strongarm][subdir] = "contrib"
projects[strongarm][version] = "2.0"

projects[token][subdir] = "contrib"
projects[token][version] = "1.6"

projects[views][subdir] = "contrib"
projects[views][version] = "3.11"
; Fix AJAX with exposed filters.
projects[views][patch][] = "http://www.drupal.org/files/issues/views_with_exposed-1809958-28.patch"

projects[views_bulk_operations][subdir] = "contrib"
projects[views_bulk_operations][version] = "3.3"

projects[views_data_export][subdir] = "contrib"
projects[views_data_export][version] = "3.0-beta9"

projects[views_geojson][subdir] = "contrib"
projects[views_geojson][version] = "1.x-dev"
; Patch to fix autogenerated JSON sources when Drupal is in a subdirectory (https://www.drupal.org/node/2606092).
projects[views_geojson][patch][] = "http://www.drupal.org/files/issues/views_geojson_openlayers_source_url-2606092-1.patch"

projects[views_tree][subdir] = "contrib"
projects[views_tree][version] = "2.0"

; -----------------------------------------------------------------------------
; Modules (Development)
; -----------------------------------------------------------------------------

projects[backup_migrate][subdir] = "dev"
projects[backup_migrate][version] = "3.1"

projects[devel][subdir] = "dev"
projects[devel][version] = "1.5"

projects[diff][subdir] = "dev"
projects[diff][version] = "3.2"

projects[module_filter][subdir] = "dev"
projects[module_filter][version] = "2.0"

; -----------------------------------------------------------------------------
; Modules (farm)
; -----------------------------------------------------------------------------

projects[farm_access][subdir] = "farm"
projects[farm_access][version] = "1.0"

projects[farm_admin][subdir] = "farm"
projects[farm_admin][version] = "1.0-beta6"

projects[farm_area][subdir] = "farm"
projects[farm_area][version] = "1.0-beta7"

projects[farm_asset][subdir] = "farm"
projects[farm_asset][version] = "1.0-beta6"

projects[farm_crop][subdir] = "farm"
projects[farm_crop][version] = "1.0-beta7"

projects[farm_equipment][subdir] = "farm"
projects[farm_equipment][version] = "1.0-beta7"

projects[farm_fields][subdir] = "farm"
projects[farm_fields][version] = "1.0-beta2"

projects[farm_livestock][subdir] = "farm"
projects[farm_livestock][version] = "1.0-beta7"

projects[farm_log][subdir] = "farm"
projects[farm_log][version] = "1.0-beta7"

projects[farm_map][subdir] = "farm"
projects[farm_map][version] = "1.0-beta7"

projects[farm_mapknitter][subdir] = "farm"
projects[farm_mapknitter][version] = "1.1"

projects[farm_sensor][subdir] = "farm"
projects[farm_sensor][version] = "1.0-beta4"

projects[farm_soil][subdir] = "farm"
projects[farm_soil][version] = "1.0-beta7"

projects[farm_taxonomy][subdir] = "farm"
projects[farm_taxonomy][version] = "1.0-beta4"

projects[farm_tour][subdir] = "farm"
projects[farm_tour][version] = "1.0-beta3"

; -----------------------------------------------------------------------------
; Themes
; -----------------------------------------------------------------------------

projects[bootstrap][version] = "3.1-beta3"
; Patch to fix http://www.drupal.org/node/2468625
projects[bootstrap][patch][] = "http://www.drupal.org/files/issues/bootstrap_panel_element_id-2468625-7.patch"
; Patch to fix bootstrap_fluid_container default theme setting.
projects[bootstrap][patch][] = "http://www.drupal.org/files/issues/bootstrap_fluid_container_default-2556603-1.patch"

projects[farm_theme][version] = "1.0-beta7"

; -----------------------------------------------------------------------------
; Libraries
; -----------------------------------------------------------------------------

libraries[backbone][download][type] = "get"
libraries[backbone][download][url] = "http://github.com/jashkenas/backbone/archive/1.1.2.zip"

libraries[modernizr][download][type] = "get"
libraries[modernizr][download][url] = "http://github.com/Modernizr/Modernizr/archive/v2.8.3.zip"

libraries[underscore][download][type] = "get"
libraries[underscore][download][url] = "http://github.com/jashkenas/underscore/archive/1.7.0.zip"

