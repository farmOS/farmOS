(function () {
  farmOS.map.behaviors.areas = {
    attach: function (instance) {

      // Add layers for each area type.
      if (Drupal.settings.farm_map.behaviors.areas.layers !== undefined) {
        var layers = Drupal.settings.farm_map.behaviors.areas.layers;
        for (var i = 0; i < layers.length; i++) {
          var opts = {
            title: layers[i].label,
            url: layers[i].url,
            color: layers[i].style,
            group: 'Areas',
          };
          var layer = instance.addLayer('geojson', opts);

          // If zoom is true, zoom to all vector layers when they load.
          if (Drupal.settings.farm_map.behaviors.areas.zoom) {
            var source = layer.getSource();
            source.on('change', function () {
              instance.zoomToVectors();
            });
          }
        }
      }
    }
  };
}());
