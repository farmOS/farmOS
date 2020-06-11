(function ($) {
  farmOS.map.behaviors.move = {
    attach: function (instance) {

      // When features are changed in the map, drop the WKT into the data field.
      instance.edit.wktOn('featurechange', function(wkt) {
        $('#' + instance.target).parent().find('textarea').val(wkt);
      });
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}(jQuery));
