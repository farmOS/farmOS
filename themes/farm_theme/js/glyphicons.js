(function ($) {
  Drupal.behaviors.farm_theme_glyphicons = {
    attach: function(context, settings) {
      var glyphicons_text = settings['farm_theme']['glyphicons_text'];
      $('#navbar ul li a', context).each(function(index) {
        Drupal.behaviors.farm_theme_glyphicons.glyphicon(this, glyphicons_text);
      });
      $('ul.tabs--primary li a', context).each(function(index) {
        Drupal.behaviors.farm_theme_glyphicons.glyphicon(this, glyphicons_text);
      });
    },
    glyphicon: function(element, glyphicons_text) {
      var link_text = $(element).clone().children().remove().end().text().trim();
      var icon = '';
      if (glyphicons_text[link_text] !== undefined) {
        icon = glyphicons_text[link_text]
      }
      if (icon && !$(element).find('span.glyphicon').length) {
        $(element).prepend('<span class="icon glyphicon glyphicon-' + icon + '"></span> ');
      }
    }
  }
})(jQuery);
