(function (Drupal, drupalSettings) {
  Drupal.behaviors.farm_map = {
    attach: function (context, settings) {
      context.querySelectorAll('[data-map-instantiator="farm_map"]').forEach(function (element) {

          // Only create a map once per element.
          if (element.getAttribute('processed')) return;
          element.setAttribute('processed', true);

          element.setAttribute('tabIndex', 0);

          const drupalSettingsKey = element.getAttribute('id');
          const mapInstanceOptions = {};
          Drupal.behaviors.farm_map.createMapInstance(context, element, drupalSettingsKey, mapInstanceOptions);
      });
    },

    createMapInstance: function(context, element, drupalSettingsKey, mapInstanceOptions) {

      // Get the units.
      let units = 'metric';
      if (!!drupalSettings.farm_map.units) {
        units = drupalSettings.farm_map.units;
      }

      // Build default options.
      const defaultOptions = {
        units,
        interactions: {
          onFocusOnly: true
        },
      };

      const mapOptions = {
        ...defaultOptions,
        ...drupalSettings.farm_map[drupalSettingsKey].instance,
        ...mapInstanceOptions
      };
      const instance = farmOS.map.create(element, mapOptions);

      // Expose settings on the instance so other behaviors don't need to know how to look them up in drupalSettings
      instance.farmMapSettings = drupalSettings.farm_map[drupalSettingsKey] || {};

      context.querySelectorAll('.ol-popup-closer').forEach(function (element) {
        element.onClick = function (element) {
          element.focus();
        };
      });

      // If the map is rendered as part of a form field, update the map size
      // when the field's visible state changes,
      const formWrapper = element.closest('div.form-wrapper');
      if (formWrapper != null) {
        const formWrapperObserver = new MutationObserver((mutations) => {

          // Only update the map size if the wrapper was previously hidden.
          if (mutations.some((mutation) => { return mutation.oldValue.includes('display: none')})) {
            instance.map.updateSize();
          }
        });

        // Observe the style attribute.
        formWrapperObserver.observe(formWrapper, {
          attributeFilter: ["style"],
          attributeOldValue: true
        })
      }

      // If the map is inside a details element, update the map size when
      // the details element is toggled.
      const details = element.closest('details');
      if (details != null) {
        details.addEventListener('toggle', function() {
          instance.map.updateSize();
        });
      }

      // Handle other cases where the map may not have a correct size when
      // it is initially rendered. This happens for modal and off-canvas
      // dialogs where there is no easy way to detect when the dialog is
      // finished loading and the map can be updated.
      // Iterate every 0.5 seconds for 3 seconds until the map
      // can be rendered and gets a size. Once rendered end the loop.
      const delay = ms => new Promise(res => setTimeout(res, ms));
      async function updateSize(instance) {
        for (let i = 0; i < 6; i++) {
          if (!instance.map.getSize().includes(0)) {
            return;
          }
          instance.map.updateSize();
          await delay(500);
        }
      }
      updateSize(instance);

      return instance;
    }

  };
}(Drupal, drupalSettings));
