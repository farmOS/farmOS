(function ($) {
  Drupal.behaviors.farm_theme_glyphicons = {
    attach: function(context, settings) {
      $('#navbar ul.secondary li a', context).each(function(index) {
        Drupal.behaviors.farm_theme_glyphicons.glyphicon(this);
      });
      $('ul.tabs--primary li a', context).each(function(index) {
        Drupal.behaviors.farm_theme_glyphicons.glyphicon(this, 'leaf');
      });
    },
    glyphicon: function(element, default_icon) {
      default_icon = typeof default_icon !== 'undefined' ? default_icon : false;
      var link_text = $(element).clone().children().remove().end().text();
      switch (link_text) {
        case 'Dashboard':
          icon = 'dashboard';
          break;
        case 'Calendar':
          icon = 'calendar';
          break;
        case 'Help':
          icon = 'question-sign';
          break;
        case 'Create new account':
          icon = 'user';
          break;
        case 'My account':
          icon = 'user';
          break;
        case 'Log out':
          icon = 'log-out';
          break;
        case 'Log in':
          icon = 'log-in';
          break;
        case 'Request new password':
          icon = 'lock';
          break;
        default:
          icon = typeof default_icon !== 'undefined' ? default_icon : FALSE;
      }
      if (icon) {
        $(element).prepend('<span class="icon glyphicon glyphicon-' + icon + '"></span> ');
      }
    }
  }
})(jQuery);
