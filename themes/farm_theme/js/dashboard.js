(function ($) {
  Drupal.behaviors.farm_theme_dashboard = {
    attach: function(context, settings) {
      $('ul.tabs--primary li a', context).each(function(index) {
        var link_text = $(this).clone().children().remove().end().text();
        var icon = 'leaf';
        switch (link_text) {
          case 'Dashboard':
            icon = 'dashboard';
            break;
          case 'Calendar':
            icon = 'calendar';
            break;
        }
        $(this).prepend('<span class="icon glyphicon glyphicon-' + icon + '"></span> ');
      });
    }
  }
})(jQuery);
