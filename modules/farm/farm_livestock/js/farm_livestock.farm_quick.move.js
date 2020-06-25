(function ($) {
  // Define a Drupal ajax command for loading the assets current location
  // wkt from the hidden input field and preview it on the map.
  Drupal.ajax.prototype.commands.previewCurrentLocation = function() {
    var wkt = $('#current-location input[name="move[current_location]"]').val();
    if (wkt) {
      farmOS.map.behaviors.move.previewCurrentLocation(wkt);
    }
  }
}(jQuery));
