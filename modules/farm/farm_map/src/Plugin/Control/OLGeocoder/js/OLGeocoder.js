Drupal.openlayers.pluginManager.register({
  fs: 'openlayers.Control:OLGeocoder',
  init: function(data) {
    try {
      var geocoder = new Geocoder('nominatim', {
        provider: 'osm',
        placeholder: 'Search for address...',
        limit: 5,
        autoComplete: true
      });
      data.map.addControl(geocoder);
    }
    catch(err) {
      console.log(err.message);
    }
  }
});
