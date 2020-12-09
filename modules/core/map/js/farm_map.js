(function (Drupal) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      var options = {
        // TODO: Set the map units.
        //units: drupalSettings.farm_map.units,
        interactions: {
          onFocusOnly: true
        },
      };
      context.querySelectorAll('.farm-map').forEach(function (element) {
        element.setAttribute('tabIndex', 0);
        farmOS.map.create(element.getAttribute('id'), options);
        context.querySelectorAll('.ol-popup-closer').forEach(function (element) {
          element.onClick = function (element) { element.focus() };
        });
      });
    }
  };
}(Drupal));
