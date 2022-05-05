(function () {

  // Open the Gin toolbar by default.
  var itemName = 'Drupal.gin.toolbarExpanded';
  if (localStorage.getItem(itemName) === null) {
    localStorage.setItem(itemName, 'true');
  }
}());
