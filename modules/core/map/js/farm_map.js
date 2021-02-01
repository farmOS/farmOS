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
          element.onClick = function (element) { element.focus() };
        });
      });
    }
  };
}(Drupal));
