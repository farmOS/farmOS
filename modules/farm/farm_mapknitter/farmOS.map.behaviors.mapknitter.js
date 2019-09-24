(function () {
  farmOS.map.behaviors.mapknitter = {
    attach: function (instance) {
      var slug = Drupal.settings.farm_map.behaviors.mapknitter.slug;
      var title = Drupal.settings.farm_map.behaviors.mapknitter.title;
      var opts = {
        title: title,
        url: 'https://mapknitter.org/tms/' + slug + '/{z}/{x}/{-y}.png',
        group: 'MapKnitter',
        visible: true,
      };
      instance.addLayer('xyz', opts);
    },
    weight: -101,
  };
}());
