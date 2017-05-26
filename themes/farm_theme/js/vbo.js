(function ($) {
  Drupal.behaviors.farm_theme_vbo = {
    attach: function (context, settings) {

      // Reset the visibility of the VBO buttons if the VBO form is clicked.
      $('.vbo-views-form', context).click(function(event) {
        Drupal.behaviors.farm_theme_vbo.reset_buttons(event.target);
      });

      // Hide VBO buttons by default.
      $('.vbo-views-form .form-wrapper', context).hide();

      // Reset the buttons.
      Drupal.behaviors.farm_theme_vbo.reset_buttons(context);
    },

    // Reset the visibility of the VBO buttons.
    reset_buttons: function (context) {

      // Find all checked boxes within the given VBO.
      var checked = $(context).closest('.vbo-views-form').find('input:checked');

      // Get the VBO buttons fieldset.
      var buttons = $(context).closest('.vbo-views-form').find('.form-wrapper');

      // Check to see if the VBO buttons are visible.
      var visible = buttons.is(':visible');

      // If any boxes are checked, show the VBO buttons.
      var show = (checked.length) ? true : false;

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