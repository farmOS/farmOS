(function () {
  farmOS.map.behaviors.geofield = {
    attach: function (instance) {
      instance.edit.wktOn('featurechange', function(wkt) {
        console.log('here!');
        document.querySelector('#' + instance.target).parentElement.querySelector('textarea').value = wkt;
      });
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
}());
