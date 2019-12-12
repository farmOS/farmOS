(function () {
  farmOS.map.behaviors.wkt = {
    attach: function (instance) {

      // If WKT was set, create a layer.
      if (Drupal.settings.farm_map.wkt[instance.target] !== undefined) {
        var wkt = Drupal.settings.farm_map.wkt[instance.target];
        var type = 'vector';
        var opts = {
          title: 'Geometry',
          color: 'orange',
        };
        if (wkt !== '' && wkt !== 'GEOMETRYCOLLECTION EMPTY') {
          type = 'wkt';
          opts.wkt = wkt;
        }
        var layer = instance.addLayer(type, opts);
      }

      // If zoom is true and the layer has features, zoom to them.
      // Otherwise, zoom to all vectors.
      if (Drupal.settings.farm_map.behaviors.wkt.zoom) {
        if (layer !== undefined) {
          instance.zoomToLayer(layer);
        }
        else {
          instance.zoomToVectors();
        }
      }
    }
  };
}());
