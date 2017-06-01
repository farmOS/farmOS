(function ($) {
  Drupal.behaviors.farm_area_details = {
    attach: function (context, settings) {

      // When an Openlayers popup is displayed, load area details.
      $(document).on('openlayers.Component:Popup', function (event, options) {

        // Load the area details.
        var area_details = jQuery('.ol-popup-description .area-details');
        var area_id = area_details.attr('id').replace('area-details-', '');
        if (area_id) {
          jQuery.getJSON(Drupal.settings.farm_area.base_path + '/' + area_id + '/details', function(data) {
            if (data) {
              area_details.html(data);
              options.overlay.setPosition(options.evt.coordinate);
            }
          });
        }
      });
    }
  };
}(jQuery));
