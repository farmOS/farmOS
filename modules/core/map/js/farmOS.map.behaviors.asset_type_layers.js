(function () {
  farmOS.map.behaviors.asset_type_layers = {
    attach: function (instance) {

      // Check if there are asset type layers to add.
      if (drupalSettings.farm_map[instance.target].asset_type_layers !== undefined) {

        // Add layers for each area type.
        var layers = drupalSettings.farm_map[instance.target].asset_type_layers;
        Object.values(layers).reverse().forEach( layer => {

          // Build a url to the asset type geojson.
          const url = new URL(window.location.origin + '/assets/' + layer.asset_type + '/geojson');

          // Include provided filters.
          const filters = layer.filters ?? {};
          Object.entries(filters).forEach( ([key, value]) => {
            url.searchParams.append(key, value);
          });

          // Default to the 'Assets' group.
          const group = layer.group ?? 'Assets';

          // Build the layer.
          var opts = {
            title: layer.label,
            url,
            color: layer.color,
            group,
          };
          var newLayer = instance.addLayer('geojson', opts);

          // If zoom is true, zoom to the layer vectors.
          if (layer.zoom !== undefined && layer.zoom) {
            var source = newLayer.getSource();
            source.on('change', function () {
              instance.zoomToVectors();
            });
          }
        });
      }

      // @todo: Display area details in popup.
      // Load area details via AJAX when an area popup is displayed.
      // instance.popup.on('farmOS-map.popup', function (event) {
      //   var area_details = jQuery('.ol-popup-description .area-details');
      //   if (area_details.attr('id') === undefined) {
      //     return;
      //   }
      //   area_details.html('test!');
      //   var area_id = area_details.attr('id').replace('area-details-', '');
      //   if (area_id) {
      //     area_details.html('Loading area details...');
      //     jQuery.getJSON(Drupal.settings.farm_map.base_path + 'farm/area/' + area_id + '/details', function(data) {
      //       if (data) {
      //         area_details.html(data);
      //         var position = event.target.getPosition();
      //         event.target.setPosition();
      //         event.target.setPosition(position);
      //       }
      //       else {
      //         area_details.html('');
      //       }
      //     });
      //   }
      // });
    }
  };
}());
