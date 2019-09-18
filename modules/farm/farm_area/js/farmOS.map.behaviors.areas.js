(function () {
  farmOS.map.behaviors.areas = {
    attach: function (instance) {

      // Add layers for each area type.
      if (Drupal.settings.farm_map.behaviors.areas.layers !== undefined) {
        var layers = Drupal.settings.farm_map.behaviors.areas.layers;
        for (var i = 0; i < layers.length; i++) {
          var opts = {
            title: layers[i].label,
            url: layers[i].url,
            color: layers[i].style,
            group: 'Areas',
          };
          var layer = instance.addLayer('geojson', opts);

          // If zoom is true, zoom to all vector layers when they load.
          if (Drupal.settings.farm_map.behaviors.areas.zoom) {
            var source = layer.getSource();
            source.on('change', function () {
              instance.zoomToVectors();
            });
          }
        }
      }

      // Load area details via AJAX when an area popup is displayed.
      instance.popup.on('farmOS-map.popup', function (event) {
        var area_details = jQuery('.ol-popup-description .area-details');
        if (area_details.attr('id') === undefined) {
          return;
        }
        var area_id = area_details.attr('id').replace('area-details-', '');
        if (area_id) {
          area_details.html('Loading area details...');
          jQuery.getJSON(Drupal.settings.farm_map.base_path + 'farm/area/' + area_id + '/details', function(data) {
            if (data) {
              area_details.html(data);
              var position = event.target.getPosition();
              event.target.setPosition();
              event.target.setPosition(position);
            }
            else {
              area_details.html('');
            }
          });
        }
      });
    }
  };
}());
