(function ($) {
  Drupal.behaviors.farm_theme_help = {
    attach: function(context, settings) {
      if ($('.region-help', context).length) {
        var helpGlyphicon = '<span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Expand/collapse help text"></span>';
        var closeGlyphicon = '<span class="glyphicon glyphicon-remove" aria-hidden="true" title="Click to hide help" style="float: right;"></span>';
        $('.page-header', context).append(' ' + helpGlyphicon);
        $('.region-help', context).prepend(' ' + closeGlyphicon);
        if (localStorage.getItem('farmThemeHelpCollapsed') === '1') {
          $('.region-help', context).hide();
        }
        $('.page-header .glyphicon-question-sign', context).click(function() {
          Drupal.behaviors.farm_theme_help.toggleHelp();
        });
        $('.region-help .glyphicon-remove', context).click(function() {
          $('.page-header .glyphicon-question-sign').tooltip('show');
          Drupal.behaviors.farm_theme_help.toggleHelp();
        });
      }
    },
    toggleHelp: function(context) {
      $('.region-help', context).slideToggle();
      if (localStorage.getItem('farmThemeHelpCollapsed') === '1') {
        localStorage.removeItem('farmThemeHelpCollapsed');
      } else {
        localStorage.setItem('farmThemeHelpCollapsed', '1');
      }
    }
  }
})(jQuery);
