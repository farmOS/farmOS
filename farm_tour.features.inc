<?php
/**
 * @file
 * farm_tour.features.inc
 */

/**
 * Implements hook_default_bootstrap_tour().
 */
function farm_tour_default_bootstrap_tour() {
  $items = array();
  $items['farm_areas'] = entity_import('bootstrap_tour', '{
    "title" : "Farn Areas",
    "name" : "farm_areas",
    "description" : "A tour of area management.",
    "roles" : "2",
    "autorun" : "1",
    "start_path" : "farm\\/areas",
    "enabled" : "1",
    "rdf_mapping" : [],
    "steps" : [
      {"path":"farm\\/areas","selector":"#navbar .menu .active","placement":"bottom","title":"Area managment","content":"farmOS maintains a list of all the areas on (and off) your farm.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".action-links li:first-child","placement":"right","title":"Add areas","content":"You can add areas by clicking the \\u0022Add an area\\u0022 button.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":"#edit-name","placement":"bottom","title":"Area name","content":"Areas have a name...","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":"#edit-field-farm-area-type-und","placement":"bottom","title":"Area type","content":"Areas can be assigned a type. Examples: Property, Field, Building, Water, etc.\\n\\nAreas are color-coded by type on the map, and will only appear if they have a type.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":".field-widget-openlayers-geofield","placement":"top","title":"Area geometry","content":"Each area can be drawn with points, lines, and polygons on a map to represent its geometry.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".openlayers-container","placement":"right","title":"Area mapping","content":"All areas are shown on the map, color-coded by type.\\n\\nOnly areas that have been assigned a type will appear on the map.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".view-farm-areas","placement":"left","title":"Organized hierarchically","content":"And they can be arranged hierarchically for better organization.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"farm\\/movements","selector":"h1","placement":"right","title":"Asset movements","content":"farmOS uses areas to represent the location of assets that it manages.\\n\\nLocation is set by creating a movement log.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_movement","selector":"h1","placement":"right","title":"Movement logs","content":"Movement logs record a movement of one or more assets to a specific area.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_movement","selector":"#edit-timestamp","placement":"right","title":"Time of movement","content":"Movement logs have a date... (when the movement occurred - or when it will occur)","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_movement","selector":"#edit-field-farm-asset","placement":"bottom","title":"Assets being moved","content":"... a list of assets that are being moved ...","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_movement","selector":"#edit-field-farm-move-from-und","placement":"bottom","title":"Where is the movement from?","content":"... a \\u0022From\\u0022 field (optional) ...","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_movement","selector":"#edit-field-farm-move-to-und","placement":"bottom","title":"Where is the movement to?","content":"... and a \\u0022To\\u0022 field for recording where the assets are moving to. This is the important piece of information, and it defines the location of assets.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":"#navbar .menu .active","placement":"bottom","title":"Area Management","content":"That\\u0027s all there is to it! Now go map your farm!","content_text_format":"plain_text","rdf_mapping":[]}
    ]
  }');
  $items['farm_intro'] = entity_import('bootstrap_tour', '{
    "title" : "farmOS Intro",
    "name" : "farm_intro",
    "description" : "An introduction to farmOS.",
    "roles" : "2",
    "autorun" : "1",
    "start_path" : "\\u003Cfront\\u003E",
    "enabled" : "1",
    "rdf_mapping" : [],
    "steps" : [
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"top","title":"Welcome to farmOS!","content":"This tour will give you a quick overview of the farmOS system. Click \\u0022Next\\u0022 to continue.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#tour","placement":"bottom","title":"Help!","content":"You can always see this tour again by clicking the question mark in the upper right.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"top","title":"farmOS terminology","content":"farmOS organizes almost everything into two basic types: \\u0022assets\\u0022 and \\u0022logs\\u0022.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#navbar ul:first-child","placement":"bottom","title":"Assets","content":"Assets are \\u0022things of value\\u0022 in the farm. Examples of asset types include: Plantings, Animals, Equipment, etc.\\n\\nYou can find lists of assets, organized by type, in the main menu.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":".nav-tabs li:last-child","placement":"bottom","title":"Logs","content":"\\u0022Logs\\u0022 are events that take place with a particular asset (or group of assets).\\n\\nYou can browse all logs in the past and future via the \\u0022All Logs\\u0022 page in the Farm Dashboard.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":".action-links li:last-child","placement":"right","title":"Adding logs","content":"You can add logs via these buttons on most pages, or by finding the specific asset or area you\\u0027re working with, and adding a log directly from that page.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"bottom","title":"Dashboard","content":"The Farm Dashboard is the homepage of your farmOS site. You can get back to it at anytime by clicking \\u0022farmOS\\u0022 or your site\\u0027s name in the upper left of any page.","content_text_format":"markup","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":".openlayers-container","placement":"right","title":"Farm map","content":"This map will show all your areas, color-coded by type.\\n\\nTo learn more about area management, click \\u0022End Tour\\u0022 and select \\u0022Areas\\u0022 from the main menu.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#block-system-main","placement":"left","title":"Planned tasks","content":"The \\u0022Plan\\u0022 shows you all the logs you have planned.\\n\\n\\u0022Late Tasks\\u0022 will show you ones that have passed, but were not marked \\u0022Done\\u0022 yet.","content_text_format":"plain_text","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"top","title":"That\\u0027s it!","content":"Pretty simple, right?\\n\\nNow go forth! And keep records!","content_text_format":"plain_text","rdf_mapping":[]}
    ]
  }');
  return $items;
}
