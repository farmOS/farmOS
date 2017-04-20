(function ($) {
  Drupal.behaviors.farm_theme_log_geofield = {
    attach: function (context, settings) {

      // Collapse the geofield fieldset by default. We do this in JS
      // rather than PHP because a PHP collapsed fieldset breaks
      // the Openlayers Geofield map for some reason. But if we let
      // that render first, and then collapse with JS, it works.
      /**
       * @todo
       * https://www.drupal.org/node/2644580
       * https://www.drupal.org/node/2579009
       */
      setTimeout(function(){
        var fieldset = $('.field-type-geofield fieldset.collapsible', context);
        var toggle = $(fieldset.find('[data-toggle=collapse]').data('target'));
        toggle.collapse('hide');
      }, 1000);

    },
  };
})(jQuery);
