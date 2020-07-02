(function ($) {
  // Define a Drupal ajax command for loading the assets current location
  // wkt from the hidden input field and preview it on the map.
  Drupal.ajax.prototype.commands.previewCurrentLocation = function() {
    var wkt = $('#current-location input[name="move[current_location]"]').val();
    if (wkt !== 'undefined') {
      farmOS.map.behaviors.move.previewCurrentLocation(wkt);
    }
  }

  // Define a Drupal ajax command for loading the Movement To area wkt
  // from a hidden input field and preview it as an editable layer in the map.
  Drupal.ajax.prototype.commands.updateMovementLayer = function() {
    var wkt = $('#movement-geometry textarea[name="move[area][geometry][data]"]').val();
    if (wkt) {
      farmOS.map.behaviors.move.updateMovementLayer(wkt);
    }
  }
}(jQuery));
