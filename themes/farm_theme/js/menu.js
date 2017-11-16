(function ($) {
  Drupal.behaviors.farm_theme_menu = {
    attach: function(context, settings) {
      if (settings.farm_theme.menu_dividers) {
        var dividers = settings.farm_theme.menu_dividers;
        var menus = ['assets', 'logs', 'plans'];
        var weights = [0, 100];
        for (var i = 0; i < menus.length; i++) {
          for (var j = 0; j < weights.length; j++) {
            if (dividers[menus[i]][weights[j]] !== undefined) {
              var li = dividers[menus[i]][weights[j]];
              $('.nav .dropdown .' + menus[i], context).siblings('.dropdown-menu').children('li:eq(' + li + ')').after($('<li class="divider"></li>'));
            }
          }
        }
      }
    }
  }
})(jQuery);
