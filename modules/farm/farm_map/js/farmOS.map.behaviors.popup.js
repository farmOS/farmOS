(function () {
  farmOS.map.behaviors.popup = {
    attach: function (instance) {

      // Create a popup and add it to the instance for future reference.
      instance.popup = instance.addPopup(function (event) {
        var content = '';
        var feature = instance.map.forEachFeatureAtPixel(event.pixel, function(feature, layer) { return feature; });
        if (feature) {
          var name = feature.get('name') || '';
          var description = feature.get('description') || '';
          if (name !== '' || description !== '') {
            content = '<div class="ol-popup-content"><h4 class="ol-popup-name">' + name + '</h4><div class="ol-popup-description">' + description + '</div></div>';
          }
        }
        return content;
      });
    }
  };
}());
