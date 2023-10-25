(function () {
  farmOS.map.behaviors.quick_movement = {
    attach: function (instance) {

      // Create a layer for the current asset location.
      var opts = {
        title: 'Current Location',
        color: 'blue',
      };
      instance.currentLocationLayer = instance.addLayer('vector', opts);

      // If an asset geometry was pre-populated, add it to the layer.
      if (instance.farmMapSettings.behaviors.quick_movement.asset_geometry) {
        this.updateAssetGeometry(instance, instance.farmMapSettings.behaviors.quick_movement.asset_geometry)
      }
    },

    // When updating asset geometry, update the current location layer.
    updateAssetGeometry: function (instance, wkt) {

      // Clear features from the layer.
      instance.currentLocationLayer.getSource().clear();

      // If WKT is not empty, add features to the layer and zoom.
      if (wkt) {
        instance.currentLocationLayer.getSource().addFeatures(instance.readFeatures('wkt', wkt));
        instance.zoomToLayer(instance.currentLocationLayer);
      }
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}());
