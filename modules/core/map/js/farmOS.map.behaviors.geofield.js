(function () {
  farmOS.map.behaviors.geofield = {
    attach: function (instance) {
      instance.editAttached.then(() => {
        instance.edit.wktOn('featurechange', function(wkt) {
          document.querySelector('#' + instance.target).parentElement.querySelector('textarea').value = wkt;
        });
      });
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}());
