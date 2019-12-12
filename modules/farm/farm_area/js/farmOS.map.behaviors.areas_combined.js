(function () {
  farmOS.map.behaviors.areas_combined= {
    attach: function (instance) {

      // Add a layer for all areas.
      var opts = {
        title: 'All areas',
        url: Drupal.settings.farm_map.base_path + 'farm/areas/geojson/all',
        color: 'grey',
      };
      var layer = instance.addLayer('geojson', opts);
      var source = layer.getSource();

      // If zoom is true, zoom to the layer when it loads.
      if (Drupal.settings.farm_map.behaviors.areas_combined.zoom) {
        source.on('change', function () {
          instance.zoomToLayer(layer);
        });
      };
    }
  };
}());
