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

        // Get all features at the point that was clicked and sort them by area from smallest to largest.
        // @todo GeometryCollections have an area of 0, which can cause some undesirable behavior.
        var clickedFeatures = instance.map.getFeaturesAtPixel(event.pixel);
        const sortValue = feature => typeof feature.getGeometry().getArea === 'function' ? feature.getGeometry().getArea() : 0;
        clickedFeatures.sort((a,b) => sortValue(a) - sortValue(b))

        // Get the first clicked feature.
        var feature = clickedFeatures[0];
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

          // A popup name is required.
          var name = featureName(feature) || '';
          if (name !== '') {

            // Get the description and measurement.
            var description = feature.get('description') || '';
            var measurement = instance.measureGeometry(feature.getGeometry(), instance.units);

            // Build content with all three values, even if empty. The measurement and description divs may be used
            // as placeholders for map behaviors to place additional information.
            content = '<h4 class="ol-popup-name">' + name + '</h4><div class="ol-popup-measurement"><small>' + measurement + '</small></div><div class="ol-popup-description">' + description + '</div>';
          }
        }
        return content;
      });
    },

    // Make sure this runs early so other behaviors can dispatch popup events
    // with instance.popup.on().
    weight: -100,
  };
}());
