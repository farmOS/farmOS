(function () {
  farmOS.map.behaviors.input = {
    attach: function (instance) {

      // Get the data input form element.
      var input = instance.map.getTargetElement().parentElement.querySelector('[data-map-geometry-field]')

      // When features change in the edit layer, write WKT to the input form element.
      instance.editAttached && instance.editAttached.then(() => {
        instance.edit.wktOn('featurechange', function(wkt) {
          if (input.value !== wkt) {
            input.value = wkt;
          }
        });
      });

      // Add an event listener to the input element, which will attempt to
      // import new WKT when it changes.
      input.addEventListener('input', (e) => {

        // If the value is empty, only clear features from the layer.
        if (!input.value) {
          instance.edit.layer.getSource().clear();
          return;
        }

        // Clear features from the layer.
        instance.edit.layer.getSource().clear();

        // Read features from WKT and add them to the layer.
        var features = instance.readFeatures('wkt', input.value);
        instance.edit.layer.getSource().addFeatures(features);

        // Zoom to the layer.
        instance.zoomToLayer(instance.edit.layer);
      });
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}());
