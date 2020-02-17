(function () {
  farmOS.map.behaviors.plan = {
    attach: function (instance) {

      // Get the plan ID from Drupal.settings.
      var planId = Drupal.settings.farm_map.behaviors.plan.plan_id;

      // Define the GeoJSON layers that we will include.
      var layers = [
        {
          title: 'Areas',
          url: '/farm/areas/geojson/all/' + planId,
          color: 'purple',
          group: 'Plan',
        },
        {
          title: 'Assets',
          url: '/farm/assets/geojson/full/all/' + planId,
          color: 'green',
          group: 'Plan',
        },
      ];

      // Add layers to the map.
      for (var i = 0; i < layers.length; i++) {
        var layer = instance.addLayer('geojson', layers[i]);

        // If zoom is true, zoom to all vector layers when they load.
        if (Drupal.settings.farm_map.behaviors.plan.zoom) {
          var source = layer.getSource();
          source.on('change', function () {
            instance.zoomToVectors();
          });
        }
      }
    }
  };
}());
