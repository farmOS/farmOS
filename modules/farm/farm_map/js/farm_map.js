(function ($) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      if (settings.farm_map.maps) {
        settings.farm_map.maps.forEach(function (target) {
          $('#' + target, context).once('farm-map', function () {
            farmOS.map.create(target);
          });
        });
      }
    }
  };
}(jQuery));
