(function ($) {
  farmOS.map.behaviors.geofield = {
    attach: function (instance) {
      instance.edit.wktOn('featurechange', function(wkt) {
        $('#' + instance.target).parents('.field-widget-farm-map-geofield').find('textarea').val(wkt);
      });
    },
  };
}(jQuery));
