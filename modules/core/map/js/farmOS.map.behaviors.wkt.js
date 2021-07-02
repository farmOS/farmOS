(function () {
  farmOS.map.behaviors.wkt = {
    attach: function (instance) {

      const settings = instance.farmMapSettings;

      // If WKT was set, create a layer.
      if (settings.wkt) {
        var wkt = settings.wkt;
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

      var focusLayerPromise = Promise.resolve(layer);

      // If edit is true, enable drawing controls.
      if (settings.behaviors && settings.behaviors.wkt && settings.behaviors.wkt.edit) {
        if (layer !== undefined) {
          instance.editAttached = instance.addBehavior('edit', { layer: layer });
        } else {
          instance.editAttached = instance.addBehavior('edit');
          // Focus on the edit layer if no layer was provided
          focusLayerPromise = instance.editAttached
            .then(() => instance.edit.layer);
        }

        // Add the snappingGrid behavior.
        instance.addBehavior('snappingGrid');
      }

      focusLayerPromise.then(focusLayer => {
        // Enable the line/polygon measure behavior.
        instance.addBehavior('measure', { layer: focusLayer });

        // If the layer has features, zoom to them.
        // Otherwise, zoom to all vectors.
        if (focusLayer !== undefined) {
          instance.zoomToLayer(focusLayer);
        } else {
          instance.zoomToVectors();
        }
      });
    },
    weight: 100,
  };
}());
