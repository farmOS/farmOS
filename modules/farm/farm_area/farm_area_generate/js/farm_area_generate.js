(function ($) {
  Drupal.behaviors.farm_area_generate = {
    attach: function (context, settings) {

      // Initialize a setting for storing the temporary preview layer.
      Drupal.settings.farm_area_generate_preview_layer = Drupal.settings.farm_area_generate_preview_layer || {};

      // When an Openlayers popup is displayed, copy the area to the area
      // generator form field.
      $(document).on('openlayers.Component:Popup', function (event, options) {
        var area_name = $('.ol-popup-name a', context).html();
        if (area_name) {
          $('#edit-area', context).val(area_name);
        }
      });
    }
  };

  Drupal.farm_area_generate = {

    // Function for adding WKT as a layer to the map.
    preview: function (wkt) {

      // Load the Openlayers map.
      var map_id = $('.openlayers-map').attr('id');
      var map = Drupal.openlayers.getMapById(map_id);

      // If a bed layer was already added, remove it.
      if (Drupal.settings.farm_area_generate_preview_layer) {
        map.map.removeLayer(Drupal.settings.farm_area_generate_preview_layer);
      }

      // Generate a new layer from WKT.
      var format = new ol.format.WKT();
      var feature = format.readFeature(wkt, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      });
      Drupal.settings.farm_area_generate_preview_layer = new ol.layer.Vector({
        source: new ol.source.Vector({
          features: [feature]
        })
      });

      // Add layer to the map.
      map.map.addLayer(Drupal.settings.farm_area_generate_preview_layer);
    }
  };

  // Define a Drupal ajax command for loading the wkt from the hidden input
  // field and previewing it on the map.
  Drupal.ajax.prototype.commands.farmAreaGeneratePreview = function() {
    var wkt = $('#generated-wkt input[name="wkt"]').val();
    if (wkt) {
      Drupal.farm_area_generate.preview(wkt);
    }
  }
}(jQuery));
