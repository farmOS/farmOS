(function (Drupal) {
  Drupal.behaviors.farm_ui_theme_map = {
    attach: function (context, settings) {

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
