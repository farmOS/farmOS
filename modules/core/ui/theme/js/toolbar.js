(function () {

  // Open the Gin toolbar by default.
  var itemName = 'Drupal.gin.toolbarExpanded';
  if (localStorage.getItem(itemName) === null) {
    localStorage.setItem(itemName, 'true');
  }
  // @todo Remove this when new Gin version is released.
  // Gin changed the name after 8.x-3.0-alpha37.
  itemName = 'GinSidebarOpen';
  if (localStorage.getItem(itemName) === null) {
    localStorage.setItem(itemName, 'true');
  }
}());
