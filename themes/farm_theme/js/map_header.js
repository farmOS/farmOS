(function ($) {
  Drupal.behaviors.farm_theme_map = {
    attach: function(context, settings) {

      // Specify the ID of the map wrapper.
      var id = 'block-farm-map-farm-map';

      // Load the collapsed state from localStorage (default to expanded).
      var collapsed = localStorage.getItem('farmThemeMapCollapsed') || false;

      // Activate the Bootstrap collapse component on the wrapper element.
      $('#' + id, context).addClass('collapse');
      $('#' + id, context).collapse({
        toggle: !collapsed,
      });

      // Generate a link for expanding/collapsing the map.
      var link = '<a href="#' + id + '" data-toggle="collapse" aria-expanded="' + (collapsed ? 'false' : 'true') + '" aria-controls="' + id + '" style="float: right; margin: 0.5em 1em 0 0;"><span class="glyphicon glyphicon-globe"></span> Toggle map</a>';
      $('#' + id, context).after(link);

      // When the map is expanded/collapsed, toggle the link text and update
      // the OpenLayers map.
      $('#' + id).on('shown.bs.collapse', function () {
        Drupal.behaviors.farm_theme_map.toggleCollapsed(id, false);
      });
      $('#' + id).on('hidden.bs.collapse', function () {
        Drupal.behaviors.farm_theme_map.toggleCollapsed(id, true);
      });
    },

    // Toggle the map collapsed state.
    toggleCollapsed: function(id, collapsed) {

      // If the map is being expanded, update the OpenLayers map size.
      if (!collapsed) {
        var mapId = $('#' + id + ' .farm-map').attr('id');
        var index = farmOS.map.targetIndex(mapId);
        if (index !== -1) {
          farmOS.map.instances[index].map.updateSize();
        }
      }

      // Save the state to localStorage.
      if (collapsed) {
        localStorage.setItem('farmThemeMapCollapsed', '1');
      } else {
        localStorage.removeItem('farmThemeMapCollapsed');
      }
    }
  };
})(jQuery);
