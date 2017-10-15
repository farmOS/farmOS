(function ($) {
  Drupal.behaviors.farm_theme_help = {
    attach: function(context, settings) {
      if ($('.region-help', context).length) {
        var glyphicon = '<span class="glyphicon glyphicon-question-sign" aria-hidden="true" title="Click for more information"></span>';
        $('.page-header', context).append(' ' + glyphicon);
        $('.region-help', context).hide();
        $('.page-header .glyphicon-question-sign', context).click(function() {
          $('.region-help', context).slideToggle();
        });
      }
    }
  }
})(jQuery);
