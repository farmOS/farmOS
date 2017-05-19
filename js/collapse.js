(function ($) {
  Drupal.behaviors.farm_theme_collapse = {
    attach: function (context, settings) {
      setTimeout(function(){
        $('.farm-theme-collapse', context).each(function(index) {
          var toggle = $($(this).find('[data-toggle=collapse]').data('target'));
          toggle.collapse('hide');
        });
      }, 1000);
    }
  };
})(jQuery);
