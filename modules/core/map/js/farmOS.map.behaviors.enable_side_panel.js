(function () {
  // Make the built-in farmOS-map 'sidePanel' behavior attach on map instantiation
  farmOS.map.behaviors.sidePanel = farmOS.map.namedBehaviors.sidePanel;

  // Make the built-in farmOS-map 'layerSwitcherInSidePanel' behavior attach on map instantiation
  farmOS.map.behaviors.layerSwitcherInSidePanel = farmOS.map.namedBehaviors.layerSwitcherInSidePanel;
}());
