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

      // Create an editable movement layer.
      // Init as an empty vector layer, the layer can be recreated
      // as an editable WKT layer later.
      var opts = {
        title: 'Movement',
        color: 'orange',
      };
      this.movementLayer = this.instance.addLayer('vector', opts);

      this.addEditBehaviors();
    },

    // Update the assets current location map layer.
    previewCurrentLocation: function (wkt) {

      // Remove current location layer.
      if (this.currentLocationLayer) {
        this.instance.map.removeLayer(this.currentLocationLayer);
        this.currentLocationLayer = null;
      }

      // Only add a layer if WKT is not an empty string.
      if (wkt.length > 0) {

        // Create current location layer with the WKT.
        // Do not put the layer inside a group, because map.removeLayer() (used
        // above) does not recurse into layer groups.
        var opts = {
          title: 'Current Location',
          color: 'blue',
          wkt: wkt,
        };
        this.currentLocationLayer = this.instance.addLayer('wkt', opts);
        this.instance.zoomToVectors();

        // Add measure behavior.
        this.instance.addBehavior('measure', { layer: this.currentLocationLayer });
      }
    },

    // Recreate the Movement map layer.
    updateMovementLayer: function (wkt) {

      // Remove current location layer.
      if (this.movementLayer) {
        this.instance.map.removeLayer(this.movementLayer);
        this.movementLayer = null;
      }

      // Create current location layer with the WKT.
      // Do not put the layer inside a group, because map.removeLayer() (used
      // above) does not recurse into layer groups.
      var opts = {
        title: 'Movement',
        color: 'orange',
        wkt: wkt,
      };
      this.movementLayer = this.instance.addLayer('wkt', opts);

      this.addEditBehaviors();

      // Zoom to all vector layers.
      this.instance.zoomToVectors();

      // Save the map instance ID.
      const target = this.instance.target;

      // Update the data field with the selected areas WKT.
      $('#' + target).parent().find('textarea').val(wkt);

      // When features are changed in the map, drop the WKT into the data field.
      this.instance.edit.wktOn('featurechange', function(wkt) {
        $('#' + target).parent().find('textarea').val(wkt);
      });
    },

    // Adds behaviors for editing movements
    addEditBehaviors() {
      // Make the layer editable.
      this.instance.addBehavior('edit', { layer: this.movementLayer });

      // Add the snappingGrid behavior.
      this.instance.addBehavior('snappingGrid');

      // Add measure behavior.
      this.instance.addBehavior('measure', { layer: this.movementLayer});
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}(jQuery));
