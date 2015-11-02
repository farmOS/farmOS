(function ($) {
  Drupal.behaviors.farm_tour = {
    attach: function (context, settings) {

      // If a tour is available, add a question mark icon link to the navbar.
      if (settings.farm_tour.tour.name) {
        var link = '?tour=' + settings.farm_tour.tour.name;
        $('#navbar .secondary').append('<li><a href="' + link + '"><span class="glyphicon glyphicon-question-sign"></span></a></li>');
      }
    }
  };
})(jQuery);
