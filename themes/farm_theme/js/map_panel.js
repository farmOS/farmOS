(function ($) {
  Drupal.behaviors.farm_theme_map_collapse = {
    attach: function(context, settings) {

      // If a map is inside a collapsed fieldset, update the map size when the
      // fieldset is expanded.
      $('fieldset.collapsible', context).once('map-collapse', function (index, element) {
        if ($('.farm-map', element).length) {
          $(element).on('shown.bs.collapse', function () {
            var mapId = $('.farm-map', element).attr('id');
            var index = farmOS.map.targetIndex(mapId);
            if (index !== -1) {
              farmOS.map.instances[index].map.updateSize();
            }
          });
        }
      });
    },
  };
})(jQuery);
