(function ($) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      var units = Drupal.settings.farm_map.units;
      var interactions = { onFocusOnly: true };
      if (settings.farm_map.maps) {
        settings.farm_map.maps.forEach(function (target) {
          $('#' + target, context).once('farm-map', function () {
            $('#' + target, context).attr('tabIndex', 0);
            farmOS.map.create(target, { units: units, interactions: interactions });
          });
        });
      }
    }
  };
}(jQuery));
