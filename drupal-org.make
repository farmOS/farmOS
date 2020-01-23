api = 2
core = 7.x

; -----------------------------------------------------------------------------
; Modules (contrib)
; -----------------------------------------------------------------------------

projects[bootstrap_tour][subdir] = "contrib"
projects[bootstrap_tour][version] = "2.5"
; Patch to fix secondary tours not running after you end the first one.
projects[bootstrap_tour][patch][] = "http://www.drupal.org/files/issues/add_unique_tour_name_to_avoid_local_storage_collisions-2533524-9.patch"

projects[calendar][subdir] = "contrib"
projects[calendar][version] = "3.5"
; Patch to fix Issue #2160183: Undefined index: groupby_times
projects[calendar][patch][] = "http://www.drupal.org/files/issues/calendar-2160183-18.patch"

projects[colorbox][subdir] = "contrib"
projects[colorbox][version] = "2.13"

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.15"
; Patch to fix Issue #2643108: Entity reference popup not centered in Linux
projects[ctools][patch][] = "http://www.drupal.org/files/issues/ctools-fix_modal_position_after_ajax-1803104-25.patch"

projects[date][subdir] = "contrib"
projects[date][version] = "2.11-beta3"
; Patch to fix Issue #3067396: Date CSS not added for date_select Form API elements
projects[date][patch][] = "http://www.drupal.org/files/issues/2019-07-16/date-select-css-3067396-3.patch"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.9"

projects[entityreference][subdir] = "contrib"
projects[entityreference][version] = "1.5"

projects[entityreference_view_widget][subdir] = "contrib"
projects[entityreference_view_widget][version] = "2.1"

projects[exif_orientation][subdir] = "contrib"
projects[exif_orientation][version] = "1.2"

projects[features][subdir] = "contrib"
projects[features][version] = "2.11"

projects[feeds][subdir] = "contrib"
projects[feeds][version] = "2.0-beta4"
; Issue #1428096: Import using label of list field succeeds, but item not selected in list field
projects[feeds][patch][] = "http://www.drupal.org/files/issues/feeds-map-to-allowed-value-1428096-8.patch"

projects[feeds_tamper][subdir] = "contrib"
projects[feeds_tamper][version] = "1.2"

projects[field_collection][subdir] = "contrib"
projects[field_collection][version] = "1.1"

projects[field_group][subdir] = "contrib"
projects[field_group][version] = "1.6"

projects[field_group_easy_responsive_tabs][subdir] = "contrib"
projects[field_group_easy_responsive_tabs][version] = "1.2"

projects[fraction][subdir] = "contrib"
projects[fraction][version] = "1.8"

projects[geocoder][subdir] = "contrib"
projects[geocoder][version] = "1.4"

projects[geofield][subdir] = "contrib"
projects[geofield][version] = "2.4"

projects[geophp][subdir] = "contrib"
projects[geophp][version] = "1.x-dev"
; Patch to use BCMath for arithmetic.
projects[geophp][patch][] = "http://www.drupal.org/files/issues/2019-12-31/geophp_bcmath-2625348-5.patch"

projects[inline_entity_form][subdir] = "contrib"
projects[inline_entity_form][version] = "1.9"

projects[job_scheduler][subdir] = "contrib"
projects[job_scheduler][version] = "2.0"

projects[jquery_update][subdir] = "contrib"
projects[jquery_update][version] = "3.0-alpha5"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.5"

projects[log][subdir] = "contrib"
projects[log][version] = "1.13"

projects[multiupload_filefield_widget][subdir] = "contrib"
projects[multiupload_filefield_widget][version] = "1.13"

projects[multiupload_imagefield_widget][subdir] = "contrib"
projects[multiupload_imagefield_widget][version] = "1.3"

projects[navbar][subdir] = "contrib"
projects[navbar][version] = "1.7"

projects[pathauto][subdir] = "contrib"
projects[pathauto][version] = "1.3"
; Fix SQLite3 issue.
projects[pathauto][patch][] = "http://www.drupal.org/files/issues/2582655-path-pathauto-is-alias-reserved-db-query.patch"

projects[pathauto_entity][subdir] = "contrib"
projects[pathauto_entity][version] = "1.0"

projects[restws][subdir] = "contrib"
projects[restws][version] = "2.8"
; Add ability to filter by entity bundle.
projects[restws][patch][] = "http://www.drupal.org/files/issues/restws-2857490-2-Filter-by-bundle.patch"
; Fix Issue #2090177: Possible misuse of "bundle keys"
projects[restws][patch][] = "http://www.drupal.org/files/issues/restws-bundleKeyTerms-2090177-12.patch"
; Fix Issue #1084144: Respond to CORS preflight requests
projects[restws][patch][] = "http://www.drupal.org/files/issues/2019-06-17/restws-support_options_request-1084144-11-18.patch"

projects[restws_field_collection][subdir] = "contrib"
projects[restws_field_collection][version] = "1.1"

projects[restws_file][subdir] = "contrib"
projects[restws_file][version] = "1.3"
; Fix Issue #3019850: Notice: Trying to get property of non-object in restws_file_restws_request_alter() (line 30 of restws_file.module).
projects[restws_file][patch][] = "http://www.drupal.org/files/issues/2019-10-26/restws_file-bundlenotice-3019850-3.patch"

projects[strongarm][subdir] = "contrib"
projects[strongarm][version] = "2.0"

projects[token][subdir] = "contrib"
projects[token][version] = "1.7"

projects[views][subdir] = "contrib"
projects[views][version] = "3.23"
; Fix PHP Notice: Undefined variable: options in views_handler_filter_term_node_tid->value_form()
projects[views][patch][] = "http://www.drupal.org/files/issues/2019-07-03/views-3065777-2.patch"

projects[views_bulk_operations][subdir] = "contrib"
projects[views_bulk_operations][version] = "3.5"

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
projects[diff][version] = "3.4"

projects[module_filter][subdir] = "dev"
projects[module_filter][version] = "2.2"

; -----------------------------------------------------------------------------
; Themes
; -----------------------------------------------------------------------------

projects[bootstrap][version] = "3.26"

; -----------------------------------------------------------------------------
; Libraries
; -----------------------------------------------------------------------------

libraries[backbone][download][type] = "get"
libraries[backbone][download][url] = "http://github.com/jashkenas/backbone/archive/1.4.0.zip"

libraries[easy-responsive-tabs][download][type] = "get"
libraries[easy-responsive-tabs][download][url] = "https://github.com/samsono/Easy-Responsive-Tabs-to-Accordion/archive/1.2.2.zip"

libraries[farmOS-map][download][type] = "get"
libraries[farmOS-map][download][url] = "http://github.com/farmOS/farmOS-map/releases/download/v0.9.4/v0.9.4-dist.zip"

libraries[modernizr][download][type] = "get"
libraries[modernizr][download][url] = "http://github.com/Modernizr/Modernizr/archive/v2.8.3.zip"

libraries[underscore][download][type] = "get"
libraries[underscore][download][url] = "http://github.com/jashkenas/underscore/archive/1.9.1.zip"

libraries[oauth2-server-php][download][type] = "get"
libraries[oauth2-server-php][download][url] = "http://github.com/bshaffer/oauth2-server-php/archive/v1.11.1.zip"
