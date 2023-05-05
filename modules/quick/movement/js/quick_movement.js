(function (Drupal) {
  Drupal.behaviors.quick_movement = {
    attach: function (context, settings) {

      // Only run this when the asset geometry or location geometry wrappers
      // are loaded/reloaded.
      if (!context.dataset || !(context.dataset.movementGeometry === 'asset-geometry' || context.dataset.movementGeometry === 'location-geometry')) {
        return;
      }

      // Get WKT from the hidden input field.
      var wkt = context.querySelector('input').value;

      // Get the farmOS-map element and instance.
      var element = context.parentElement.querySelector('[data-drupal-selector="edit-geometry-map"]');
      var instance = farmOS.map.instances[farmOS.map.targetIndex(element)];

      // If this is asset geometry, refresh the map asset geometry.
      if (context.dataset.movementGeometry === 'asset-geometry') {
        farmOS.map.behaviors.quick_movement.updateAssetGeometry(instance, wkt);
      }

      // If this is location geometry, copy WKT into the map's value field and
      // dispatch the input event so that the input behavior refreshes the map.
      if (context.dataset.movementGeometry === 'location-geometry') {
        var input = context.parentElement.querySelector('[data-drupal-selector="edit-geometry-value"]');
        input.value = wkt;
        input.dispatchEvent(new Event('input'));
      }
    }
  };
}(Drupal));
