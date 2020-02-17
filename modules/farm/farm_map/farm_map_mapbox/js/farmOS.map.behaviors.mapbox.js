(function () {
  farmOS.map.behaviors.mapbox = {
    attach: function (instance) {
      var key = Drupal.settings.farm_map.behaviors.mapbox.api_key;
      this.addMapboxLayer(instance, 'Mapbox Outdoors', 'mapbox.outdoors', key);
      this.addMapboxLayer(instance, 'Mapbox Satellite', 'mapbox.satellite', key, true);
    },
    addMapboxLayer: function (instance, title, tileset, key, visible = false) {
      var opts = {
        title: title,
        url: 'https://api.mapbox.com/v4/' + tileset + '/{z}/{x}/{y}.png?access_token=' + key,
        group: 'Base layers',
        base: true,
        visible: visible,
      };
      instance.addLayer('xyz', opts);
    }
  };
}());
