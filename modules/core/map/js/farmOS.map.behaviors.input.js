(function () {
  farmOS.map.behaviors.input = {
    attach: function (instance) {
      instance.editAttached && instance.editAttached.then(() => {
        instance.edit.wktOn('featurechange', function(wkt) {
          instance.map.getTargetElement().parentElement.querySelector('[data-map-geometry-field]').value = wkt;
        });
      });
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}());
