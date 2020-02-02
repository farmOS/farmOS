(function ($) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      var options = {
        units: Drupal.settings.farm_map.units,
        interactions: {
          onFocusOnly: true
        },
      };
      $('.farm-map', context).each(function (index, element) {
        $(element).once('farm-map', function () {
          $(element).attr('tabIndex', 0);
          farmOS.map.create($(element).attr('id'), options);
        });
      });
    }
  };
}(jQuery));
