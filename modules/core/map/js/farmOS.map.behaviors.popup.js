(function () {
  farmOS.map.behaviors.popup = {
    attach: function (instance) {

      // Helper function to build the feature name as a link.
      const featureName = function (feature) {
        // Bail if either the name or url aren't defined.
        const name = feature.get('name');
        const url = feature.get('url');
        if (name === undefined || url === undefined) {
          return name;
        }
        // Build a link with the url and name.
        return `<a href="${url}">${name}</a>`;
      }

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
              const name = featureName(item);
              if (name !== undefined) {
                names.push(name);
              }
            });
            if (names.length !== 0) {
              feature.set('description', '<ul><li>' + names.join('</li><li>') + '</li></ul>');
            }
            feature.set('name', names.length + ' item(s):');
          }

          var name = featureName(feature) || '';
          var description = feature.get('description') || '';
          var measurement = instance.measureGeometry(feature.getGeometry(), instance.units);
          if (name !== '' || measurement !== '' || description !== '') {
            content = '<h4 class="ol-popup-name">' + name + '</h4><div class="ol-popup-measurement"><small>' + measurement + '</small></div><div class="ol-popup-description">' + description + '</div>';
          }
        }
        return content;
      });
    },
    weight: 100,
  };
}());
