(function (drupalSettings) {
  // Set window.farmosMapPublicPath to tell farmOS-map where to load chunks from.
  if (!!drupalSettings.farm_map_public_path) {
    window.farmosMapPublicPath = drupalSettings.farm_map_public_path;
  }
}(drupalSettings));
