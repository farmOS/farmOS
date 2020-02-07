(function () {
  farmOS.map.behaviors.assets_full = {
    attach: function (instance) {
      if (Drupal.settings.farm_map.behaviors.assets_full.types !== undefined) {
        var types = Drupal.settings.farm_map.behaviors.assets_full.types;
        if (Drupal.settings.farm_map.behaviors.assets_full.type) {
          types = types.filter(function (type) {
            return type.type === Drupal.settings.farm_map.behaviors.assets_full.type;
          });
        }
        for (var i = 0; i < types.length; i++) {

          // Add a full asset geometry layer.
          var opts = {
            title: types[i].label,
            url: types[i].full,
            color: 'orange',
            group: 'Assets',
          };
          var layer = instance.addLayer('geojson', opts);
          layer.getStyle().getStroke().setLineDash([4, 12]);
        }

        // If zoom is true, zoom to layer(s).
        if (Drupal.settings.farm_map.behaviors.assets_full.zoom) {
          var source = layer.getSource();
          source.on('change', function () {

            // If one type was defined, zoom to the individual layer.
            if (types.length === 1) {
              instance.zoomToLayer(layer);
            }

            // Otherwise, zoom to all vectors.
            else {
              instance.zoomToVectors();
            }
          });
        }
      }
    }
  };
}());
