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
    "title" : "Farm Areas",
    "name" : "farm_areas",
    "description" : "A tour of area management.",
    "roles" : "2",
    "autorun" : "0",
    "start_path" : "farm\\/areas",
    "enabled" : "1",
    "rdf_mapping" : [],
    "steps" : [
      {"path":"farm\\/areas","selector":"#navbar .menu .active","placement":"bottom","title":"Area managment","content":"farmOS maintains a list of all the areas on (and off) your farm.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".action-links li:first-child","placement":"right","title":"Add areas","content":"You can add areas by clicking the \\u0022Add an area\\u0022 button.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":"#edit-name","placement":"bottom","title":"Area name","content":"Areas have a name...","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":"#edit-field-farm-area-type-und","placement":"bottom","title":"Area type","content":"Areas can be assigned a type. Examples: Property, Field, Building, Water, etc.\\n\\nAreas are color-coded by type on the map, and will only appear if they have a type.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"admin\\/structure\\/taxonomy\\/farm_areas\\/add","selector":".field-widget-openlayers-geofield","placement":"top","title":"Area geometry","content":"Each area can be drawn with points, lines, and polygons on a map to represent its geometry.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".openlayers-container","placement":"right","title":"Area mapping","content":"All areas are shown on the map, color-coded by type.\\n\\nOnly areas that have been assigned a type will appear on the map.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":".view-farm-areas","placement":"left","title":"Organized hierarchically","content":"And they can be arranged hierarchically for better organization.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/logs\\/movements","selector":"h1","placement":"bottom","title":"Asset movements","content":"farmOS uses areas to represent the location of assets that it manages.\\n\\nLocation is set by creating a log with movement information.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_activity","selector":"h1","placement":"bottom","title":"Logging movements","content":"Movements can be recorded with most log types.\\n\\nFor example: you can record that you moved assets by creating a new Activity log.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_activity","selector":"#edit-timestamp","placement":"right","title":"Time of movement","content":"All logs have a date... (when the movement occurred - or when it will occur)","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_activity","selector":"#edit-field-farm-asset","placement":"bottom","title":"Assets being moved","content":"... a list of assets that are being moved ...","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"log\\/add\\/farm_activity","selector":"#edit-field-farm-movement-und-0-field-farm-move-to-und","placement":"bottom","title":"Where is the movement to?","content":"... a \\u0022Movement To\\u0022 field for recording where the assets are moving to. This is the important piece of information. It will be used to determine the location of assets elsewhere in farmOS.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/areas","selector":"#navbar .menu .active","placement":"bottom","title":"Area Management","content":"That\\u0027s all there is to it! Now go map your farm!","content_text_format":"farm_format","rdf_mapping":[]}
    ]
  }');
  $items['farm_intro'] = entity_import('bootstrap_tour', '{
    "title" : "farmOS Intro",
    "name" : "farm_intro",
    "description" : "An introduction to farmOS.",
    "roles" : "2",
    "autorun" : "0",
    "start_path" : "\\u003Cfront\\u003E",
    "enabled" : "1",
    "rdf_mapping" : [],
    "steps" : [
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"top","title":"Welcome to farmOS!","content":"This tour will give you a quick overview of the farmOS system. Click \\u0022Next\\u0022 to continue.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#navbar ul.menu:first-child","placement":"bottom","title":"farmOS terminology","content":"farmOS organizes things into three general types: \\u0022areas\\u0022, \\u0022assets\\u0022, and \\u0022logs\\u0022.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#navbar li a.areas","placement":"bottom","title":"Areas","content":"\\u0022Areas\\u0022 are various places on (and off) your farm. They are displayed on a map and help you to organize your assets and logs by location.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#navbar li a.assets","placement":"bottom","title":"Assets","content":"\\u0022Assets\\u0022 are things that the farm owns. Plantings, Animals, Equipment, etc are all considered assets.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#navbar li a.logs","placement":"bottom","title":"Logs","content":"\\u0022Logs\\u0022 are events that take place on the farm. Activities, Observations, and Movements are three examples of log types.\\n\\nThey are generally associated with specific assets, but they can also be general records.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"bottom","title":"Dashboard","content":"The Farm Dashboard is the homepage of your farmOS site. You can get back to it at anytime by clicking \\u0022farmOS\\u0022 or your site\\u0027s name in the upper left of any page.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":".openlayers-container","placement":"right","title":"Farm map","content":"This map will show all your areas, color-coded by type.\\n\\nTo learn more about area management, click \\u0022End Tour\\u0022 and select \\u0022Areas\\u0022 from the main menu.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#block-system-main","placement":"left","title":"Planned tasks","content":"The \\u0022Plan\\u0022 shows you all the logs you have planned.\\n\\n\\u0022Late Tasks\\u0022 will show you ones that have passed, but were not marked \\u0022Done\\u0022 yet.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"#views-form-log-plan-block-plan","placement":"bottom","title":"Completing tasks","content":"You can mark tasks as \\u0022completed\\u0022 by clicking the checkbox next to them and clicking the \\u0022Done\\u0022 button below the list.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"\\u003Cfront\\u003E","selector":"","placement":"top","title":"That\\u0027s it!","content":"Pretty simple, right? That\\u0027s just the basics, but it should get you started.\\n\\nNow go forth! And keep records!","content_text_format":"farm_format","rdf_mapping":[]}
    ]
  }');
  $items['farm_plantings'] = entity_import('bootstrap_tour', '{
    "title" : "Farm Plantings",
    "name" : "farm_plantings",
    "description" : "A tour of plantings.",
    "roles" : "2",
    "autorun" : "0",
    "start_path" : "farm\\/assets\\/plantings",
    "enabled" : "1",
    "rdf_mapping" : [],
    "steps" : [
      {"path":"farm\\/assets\\/plantings","selector":"","placement":"top","title":"Plantings","content":"\\u0022Plantings\\u0022 represent the plants you\\u0027re growing.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/assets\\/plantings","selector":"h1","placement":"bottom","title":"Terminology","content":"In farmOS:\\n\\nPlantings = the actual plants you have growing\\n\\nCrops = the various \\u0022types\\u0022 of plants you grow, in general (species\\/varieties)","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/assets\\/plantings","selector":"#navbar li a.logs","placement":"right","title":"Logs","content":"Plantings have a few special types of logs (in addition to Activities, Observations, and Movements):\\n\\nHarvests - for recording harvests from the planting\\nInputs - for recording inputs to the planting\\nSeedings - for recording specific seeding details\\nTransplantings - for recording details when a transplanting occurs","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/assets\\/plantings","selector":".action-links li:first-child","placement":"top","title":"Getting started","content":"To get started, add a planting for each crop you are growing.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/asset\\/add\\/planting","selector":"#edit-name","placement":"bottom","title":"Planting name","content":"A planting represents real plants that are growing at a given time, so it can be helpful to name it in a way that includes that information.\\n\\nFor example, if you are growing 2 plantings of cabbages in 2016, you might call them \\u00222016 Cabbage Planting 1\\u0022 and \\u00222016 Cabbage Planting 2\\u0022.\\n\\nThis makes it easier to browse your list of plantings later.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/asset\\/add\\/planting","selector":"#edit-field-farm-crop-und","placement":"top","title":"Crop","content":"The \\u0022Crop\\u0022 of a planting is the \\u0022type\\u0022 of plant being grown. It could be a species, or a variety.\\n\\nCrops can be defined on-the-fly by simply typing a new crop name into this field.  They can then be described in greater detail and organized hierarchically later.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/asset\\/add\\/planting","selector":"#edit-active","placement":"top","title":"Active","content":"Plantings (like all assets in farmOS) are considered \\u0022active\\u0022 when they are alive\\/functioning. So when you harvest and clean up your cabbage planting, you can mark it as \\u0022Inactive\\u0022.\\n\\nOnly active plantings will be listed in farmOS by default - but you can also search through the archive of inactive assets whenever you want.","content_text_format":"farm_format","rdf_mapping":[]},
      {"path":"farm\\/asset\\/add\\/planting","selector":"#edit-submit","placement":"top","title":"Save","content":"Once you save your planting, you\\u0027ll be able to add logs to it, like seedings, transplantings, inputs, harvest, activities, observations, etc.","content_text_format":"farm_format","rdf_mapping":[]}
    ]
  }');
  return $items;
}
