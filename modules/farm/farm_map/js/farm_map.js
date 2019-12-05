(function ($) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      var units = Drupal.settings.farm_map.units;
      if (settings.farm_map.maps) {
        settings.farm_map.maps.forEach(function (target) {
          $('#' + target, context).once('farm-map', function () {
            farmOS.map.create(target, { units: units });
          });
        });
      }
    }
  };
}(jQuery));
