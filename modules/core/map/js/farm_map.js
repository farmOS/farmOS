(function ($, Drupal) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      var options = {
        // TODO: Set the map units.
        //units: drupalSettings.farm_map.units,
        interactions: {
          onFocusOnly: true
        },
      };
      $('.farm-map', context).each(function (index, element) {
        $(element).once('farm-map').each(function () {
          $(element).attr('tabIndex', 0);
          farmOS.map.create($(element).attr('id'), options);
          $('.ol-popup-closer', context).click(function () {
            $(element).focus();
          });
        });
      });
    }
  };
}(jQuery, Drupal));
