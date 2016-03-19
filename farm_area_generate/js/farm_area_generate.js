(function ($) {
  Drupal.behaviors.farm_area_generate = {
    attach: function (context, settings) {

      // When an Openlayers popup is displayed, copy the area to the area
      // generator form field.
      $(document).on('openlayers.Component:Popup', function (event, options) {
        var area_name = $('.ol-popup-name a', context).html();
        if (area_name) {
          $('#edit-area', context).val(area_name);
        }
      });
    }
  };
}(jQuery));
