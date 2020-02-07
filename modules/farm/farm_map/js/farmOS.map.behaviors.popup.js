(function () {
  farmOS.map.behaviors.popup = {
    attach: function (instance) {

      // Create a popup and add it to the instance for future reference.
      instance.popup = instance.addPopup(function (event) {
        var content = '';
        var feature = instance.map.forEachFeatureAtPixel(event.pixel, function(feature, layer) { return feature; });
        if (feature) {

          // If the feature is a cluster, then create a list of names and add it
          // to the overall feature's description.
          var features = feature.get('features');
          if (features !== undefined) {
            var names = [];
            features.forEach(function (item) {
              if (item.get('name') !== undefined) {
                names.push(item.get('name'));
              }
            });
            if (names.length !== 0) {
              feature.set('description', '<ul><li>' + names.join('</li><li>') + '</li></ul>');
            }
            feature.set('name', names.length + ' item(s):');
          }

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
