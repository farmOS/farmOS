/**
 * @file
 * Prevent malformed numeric input from being submitted as an empty value.
 */
(function (Drupal, once, $) {
  'use strict';

  Drupal.behaviors.farmNumberValidation = {
    attach(context) {
      const forms = once('farm-number-validation', 'form.farm-number-validation', context);
      forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
          if (event.submitter && event.submitter.formNoValidate) {
            return;
          }
          const numbers = form.querySelectorAll('input[type="number"]');
          const invalid = Array.from(numbers).find((input) => (
            input.willValidate && input.validity.badInput
          ));
          if (!invalid) {
            return;
          }

          // Stop before form protection and duplicate-submit handlers run, so a
          // corrected value can still be saved and unsaved changes stay guarded.
          event.preventDefault();
          event.stopImmediatePropagation();

          // Open outer details first, including Drupal's hidden vertical panes.
          const parents = $(invalid).parents('details').get().reverse();
          parents.forEach((details) => {
            const tab = $(details).data('verticalTab');
            if (tab) {
              tab.focus();
            }
            else {
              details.open = true;
            }
          });
          invalid.focus();
          invalid.reportValidity();
        }, true);
      });
    },
  };
})(Drupal, once, jQuery);
