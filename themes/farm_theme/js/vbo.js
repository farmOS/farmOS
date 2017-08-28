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

      // When a VBO config form is displayed, we want to hide everything else
      // on the page. This is accomplished by passing in a few variables to JS
      // via hook_views_bulk_operations_form_alter() when the step is config.
      if ((typeof Drupal.settings.farm_theme !== 'undefined') && (typeof Drupal.settings.farm_theme.vbo_hide !== 'undefined')) {

        // Hide everything within the page content, and then show only the
        // View. (use both the View's name and display when building the
        // selector to ensure specificity).
        var view_name = Drupal.settings.farm_theme.view_name;
        var view_display = Drupal.settings.farm_theme.view_display;
        var selector = '.view-id-' + view_name + '.view-display-id-' + view_display;
        $('.region-content > :not(' + selector + ')', context).hide();
        $(selector, context).appendTo('.region-content');

        // Hide breadcrumbs, tabs, and action links as well.
        $('.breadcrumb', context).hide();
        $('.tabs--primary', context).hide();
        $('.action-links', context).hide();
      }
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
