(function (Drupal, once) {
  Drupal.behaviors.farm_ui_datetime = {
    attach: function (context, settings) {
      var wrappers = once('toggle-time', '.form-datetime-wrapper div[data-farm-toggle-time="hide"]', context);
      wrappers.forEach(this.addShowTimeButton);
    },
    addShowTimeButton: function (wrapper) {
      var link = document.createElement('a');
      link.href = 'javascript: void(0)';
      link.innerHTML = 'Show time';
      link.classList.add('toggle-time');
      link.addEventListener('click', Drupal.behaviors.farm_ui_datetime.showTime);
      wrapper.querySelector('.form-type--date').appendChild(link);
    },
    showTime: function (event) {
      var wrapper = event.target.closest('.form-datetime-wrapper div[data-farm-toggle-time]');
      var link = wrapper.querySelector('a.toggle-time');
      link.parentNode.removeChild(link);
      wrapper.dataset.farmToggleTime = 'show';
    },
  };
}(Drupal, once));
