(function (Drupal, once) {
  Drupal.behaviors.farm_ui_datetime = {
    attach: function (context, settings) {
      var wrappers = once('toggle-time', '.form-datetime-wrapper', context);
      wrappers.forEach(this.hideTime);
    },
    hideTime: function (wrapper) {
      var link = document.createElement('a');
      link.href = 'javascript: void(0)';
      link.innerHTML = 'Show time';
      link.classList.add('toggle-time');
      link.addEventListener('click', Drupal.behaviors.farm_ui_datetime.showTime);
      wrapper.querySelector('.form-type--date').appendChild(link);
      wrapper.querySelector('input.form-time').style.display = 'none';
    },
    showTime: function (event) {
      var wrapper = event.target.closest('.form-datetime-wrapper');
      wrapper.querySelector('input.form-time').style.display = 'block';
      var link = wrapper.querySelector('a.toggle-time');
      link.parentNode.removeChild(link);
    },
  };
}(Drupal, once));
