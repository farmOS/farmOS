(function () {
  farmOS.map.behaviors.area_generate = {
    attach: function (instance) {

      // Save the instance to this for reference in other methods.
      this.instance = instance;

      // When an area popup is displayed, copy the area to the area generator
      // form field.
      instance.popup.on('farmOS-map.popup', function (event) {
        var area_name = jQuery('.ol-popup-name a').html();
        if (area_name) {
          jQuery('#edit-area').val(area_name);
        }
      });
    },

    // Preview WKT in the map.
    preview: function (wkt) {

      // Remove preview layer.
      if (this.layer) {
        this.instance.map.removeLayer(this.layer);
        this.layer = null;
      }

      // Create preview layer with the WKT.
      // Do not put the layer inside a group, because map.removeLayer() (used
      // above) does not recurse into layer groups.
      var opts = {
        title: 'Preview',
        color: 'blue',
        wkt: wkt,
      };
      this.layer = this.instance.addLayer('wkt', opts);
    }
  };
}());
