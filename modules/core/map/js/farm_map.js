(function (Drupal) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      const defaultOptions = {
        // TODO: Set the map units.
        //units: drupalSettings.farm_map.units,
        interactions: {
          onFocusOnly: true
        },
      };
      context.querySelectorAll('.farm-map').forEach(function (element) {
        element.setAttribute('tabIndex', 0);
        const mapId = element.getAttribute('id');
        const mapOptions = { ...defaultOptions, ...drupalSettings.farm_map[mapId].instance};
        farmOS.map.create(mapId, mapOptions);
        context.querySelectorAll('.ol-popup-closer').forEach(function (element) {
          element.onClick = function (element) {
            element.focus();
          };
        });
      });

      // Add an event listener to update the map size when the Gin toolbar is toggled.
      if (context === document) {
        document.addEventListener('toolbar-toggle', function(e) {

          // Only continue if map instances are provided.
          if (typeof farmOS !== 'undefined' && farmOS.map.instances !== 'undefined') {

            // Set a timeout so the computed CSS properties are applied
            // before updating the map size.
            setTimeout(function () {
              // Update the map size of all map instances.
              farmOS.map.instances.forEach(function (instance) {
                instance.map.updateSize();
              });

            }, 200);
          }
        });
      }
    }
  };
}(Drupal));
