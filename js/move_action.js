(function ($) {
  Drupal.behaviors.farm_log_move_action = {
    attach: function (context, settings) {

      /**
       * This script is added to the "Move" action form.
       * It automatically checks/unchecks the "Done" checkbox
       * depending on the date that is entered. Dates in the
       * future are assumed to be "not done", whereas dates in
       * the past (or present) are assumed to be "done".
       */

      // When the movement date is changed...
      $('#edit-timestamp select', context).change(event, function() {

        // Get the select list values.
        var month = $(event.target).closest('#edit-timestamp').find('#edit-timestamp-month').val();
        var day = $(event.target).closest('#edit-timestamp').find('#edit-timestamp-day').val();
        var year = $(event.target).closest('#edit-timestamp').find('#edit-timestamp-year').val();

        // Convert the date to a timestamp.
        var timestamp = Date.parse(year + '-' + month + '-' + day);

        // Get the timestamp at the start of today.
        var now = new Date();
        var today = Date.parse(new Date(now.getFullYear(), now.getMonth(), now.getDate()));

        // Get the "Done" checkbox element.
        var done = $(event.target).closest('form').find('#edit-done');

        // If the movement is taking place after today, uncheck the "Done" checkbox.
        // Otherwise, check it.
        if (timestamp > today) {
          done.prop('checked', false);
        }
        else {
          done.prop('checked', true);
        }
      });
    }
  };
})(jQuery);