(function ($) {
  Drupal.behaviors.farm_theme_vbo = {
    attach: function (context, settings) {

      // Attach the toggle function to both the VBO select and select-all checkboxes.
      $('.vbo-select', context).click(event, function() {
        Drupal.behaviors.farm_theme_vbo.toggle(event.target);
      });
      $('.vbo-table-select-all', context).click(event, function() {
        Drupal.behaviors.farm_theme_vbo.toggle(event.target);
      });

      // Hide VBO buttons by default.
      $('.vbo-views-form .form-wrapper', context).hide();

      // Toggle the buttons.
      Drupal.behaviors.farm_theme_vbo.toggle(context);
    },

    // Toggle the visibility of the VBO buttons.
    toggle: function (context) {

      // Check to see if the VBO buttons are visible.
      var visible = $('.vbo-views-form .form-wrapper').is(':visible');

      // Find all checkboxes within the given VBO.
      var checked = $(context).closest('.vbo-views-form').find('input:checked');

      // Do we want to show the VBO buttons?
      var show = false;

      // If any boxes are checked, show the VBO buttons.
      if (checked.length) {
        show = true;
      }

      // Get the VBO buttons fieldset.
      var buttons = $(context).closest('.vbo-views-form').find('.form-wrapper');

      // If the VBO buttons are hidden and we want to show them, slide down.
      if (!visible && show) {
        buttons.slideDown();
      }

      // Or, if the buttons are visible, and we want to hide them, slide up.
      else if (visible && !show) {
        buttons.slideUp();
      }
    }
  };
})(jQuery);