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
projects[entityreference][version] = "1.4"

projects[entityreference_view_widget][subdir] = "contrib"
projects[entityreference_view_widget][version] = "2.0-rc7"
; Patch to fix blank checkboxes.
projects[entityreference_view_widget][patch][] = "http://www.drupal.org/files/issues/entityreference_view_widget_suffix-2524296-2.patch"

projects[exif_orientation][subdir] = "contrib"
projects[exif_orientation][version] = "1.2"

projects[features][subdir] = "contrib"
projects[features][version] = "2.10"

projects[feeds][subdir] = "contrib"
projects[feeds][version] = "2.0-beta3"

projects[feeds_tamper][subdir] = "contrib"
projects[feeds_tamper][version] = "1.1"

projects[field_collection][subdir] = "contrib"
projects[field_collection][version] = "1.0-beta12"

projects[field_group][subdir] = "contrib"
projects[field_group][version] = "1.5"

projects[fraction][subdir] = "contrib"
projects[fraction][version] = "1.4"
; Issue #2893271: Add Min, Max, Prefix, Suffix settings to Fraction fields
projects[fraction][patch][] = "http://www.drupal.org/files/issues/fraction_field_settings-2893271-4.patch"

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

projects[job_scheduler][subdir] = "contrib"
projects[job_scheduler][version] = "2.0-alpha3"

projects[jquery_update][subdir] = "contrib"
projects[jquery_update][version] = "3.0-alpha5"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.3"

projects[libraries_cdn][subdir] = "contrib"
projects[libraries_cdn][version] = "1.7"

projects[log][subdir] = "contrib"
projects[log][version] = "1.8"
; Issue #2402191: Integrate Log with Feeds module
projects[log][patch][] = "http://www.drupal.org/files/issues/log_feeds-2402191-1.patch"

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
; Hide empty Openlayers Geofield labels.
projects[openlayers][patch][] = "http://www.drupal.org/files/issues/openlayers_geofield_empty_label-2880034-1.patch"

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
projects[views][version] = "3.16"

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
projects[module_filter][version] = "2.1"

; -----------------------------------------------------------------------------
; Themes
; -----------------------------------------------------------------------------

projects[bootstrap][version] = "3.14"
; Issue #2634358: Multiple collapsible fieldsets have broken triggers in BS3.3.4
projects[bootstrap][patch][] = "http://www.drupal.org/files/issues/bootstrap_multifieldset-2634358-10.patch"

; -----------------------------------------------------------------------------
; Libraries
; -----------------------------------------------------------------------------

libraries[backbone][download][type] = "get"
libraries[backbone][download][url] = "http://github.com/jashkenas/backbone/archive/1.1.2.zip"

libraries[modernizr][download][type] = "get"
libraries[modernizr][download][url] = "http://github.com/Modernizr/Modernizr/archive/v2.8.3.zip"

libraries[underscore][download][type] = "get"
libraries[underscore][download][url] = "http://github.com/jashkenas/underscore/archive/1.7.0.zip"
