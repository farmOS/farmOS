(function () {
  farmOS.map.behaviors.wkt = {
    attach: function (instance) {

      // If WKT was set, create a layer.
      if (drupalSettings.farm_map[instance.target].wkt) {
        var wkt = drupalSettings.farm_map[instance.target].wkt;
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

      // If edit is true, enable drawing controls.
      if (drupalSettings.farm_map[instance.target].behaviors.wkt.edit) {
        if (layer !== undefined) {
          instance.addBehavior('edit', { layer: layer });
        } else {
          instance.addBehavior('edit');
          var layer = instance.edit.layer;
        }

        // Add the snappingGrid behavior.
        instance.addBehavior('snappingGrid');
      }

      // Enable the line/polygon measure behavior.
      instance.addBehavior('measure', { layer: layer });

      // If the layer has features, zoom to them.
      // Otherwise, zoom to all vectors.
      if (layer !== undefined) {
        instance.zoomToLayer(layer);
      } else {
        instance.zoomToVectors();
      }
    },
    weight: 100,
  };
}());
