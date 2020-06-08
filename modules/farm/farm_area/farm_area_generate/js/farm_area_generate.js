(function ($) {
  // Define a Drupal ajax command for loading the wkt from the hidden input
  // field and previewing it on the map.
  Drupal.ajax.prototype.commands.farmAreaGeneratePreview = function() {
    var wkt = $('#generated-wkt input[name="wkt"]').val();
    if (wkt) {
      farmOS.map.behaviors.area_generate.preview(wkt);
    }
  }
}(jQuery));
