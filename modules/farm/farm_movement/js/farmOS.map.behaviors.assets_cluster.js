(function () {
  farmOS.map.behaviors.assets_cluster = {
    attach: function (instance) {
      if (Drupal.settings.farm_map.behaviors.assets_cluster.types !== undefined) {
        var types = Drupal.settings.farm_map.behaviors.assets_cluster.types;
        if (Drupal.settings.farm_map.behaviors.assets_cluster.type) {
          types = types.filter(function (type) {
            return type.type === Drupal.settings.farm_map.behaviors.assets_cluster.type;
          });
        }
        for (var i = 0; i < types.length; i++) {

          // Add an asset cluster layer.
          var opts = {
            title: types[i].label + ' (cluster)',
            url: types[i].centroid,
            group: 'Assets',
          };
          instance.addLayer('cluster', opts);
        }
      }
    }
  };
}());
