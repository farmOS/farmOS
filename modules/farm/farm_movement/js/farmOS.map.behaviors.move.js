(function ($) {
  farmOS.map.behaviors.move = {
    attach: function (instance) {

      // Save the instance to this for reference in other methods.
      /**
       * @todo
       * This assumes that there is only one map on the page. It will apply the
       * preview layer to the last map.
       */
      this.instance = instance;

      // When features are changed in the map, drop the WKT into the data field.
      instance.edit.wktOn('featurechange', function(wkt) {
        $('#' + instance.target).parent().find('textarea').val(wkt);
      });
    },

    // Update the assets current location map layer.
    previewCurrentLocation: function (wkt) {

      // Remove current location layer.
      if (this.currentLocationLayer) {
        this.instance.map.removeLayer(this.currentLocationLayer);
        this.currentLocationLayer = null;
      }

      // Create current location layer with the WKT.
      // Do not put the layer inside a group, because map.removeLayer() (used
      // above) does not recurse into layer groups.
      var opts = {
        title: 'Current Location',
        color: 'blue',
        wkt: wkt,
      };
      this.currentLocationLayer = this.instance.addLayer('wkt', opts);
      this.instance.zoomToLayer(this.currentLocationLayer);
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}(jQuery));
