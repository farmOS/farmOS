(function () {

  // Open the Gin toolbar by default.
  itemName = 'GinSidebarOpen';
  if (localStorage.getItem(itemName) === null) {
    localStorage.setItem(itemName, 'true');
  }
}());
