/**
 * @file
 * Locations drag and drop.
 */

(function ($, Drupal, settings) {

  "use strict"

  // @TODO drag and drop validate if exist.
  // @TODO validate circular references.

  Drupal.behaviors.locationsDragAndDrop = {
    attach: function (context, settings) {

      // Function for toggling drag and drop.
      var toggleDragAndDrop = function() {
        $('input#edit-save').attr('disabled', !dragAndDropEnabled)
        $('input#edit-reset').attr('disabled', !dragAndDropEnabled)
        domTree.config.dragAndDrop.enabled = dragAndDropEnabled
      }

      // Enable Inspire Tree.
      var tree = new InspireTree({
        data: settings.asset_tree,
      })
      var domTree = new InspireTreeDOM(tree, {
        target: '.locations-tree',
        dragAndDrop: true
      })
      tree.nodes().expand()

      // Start with drag and drop disabled.
      var dragAndDropEnabled = false
      toggleDragAndDrop()

      // Toggle drag and drop when the button is clicked.
      $('input#edit-toggle').on('click', function(event) {
        event.preventDefault()
        dragAndDropEnabled = !dragAndDropEnabled
        toggleDragAndDrop()
        // Reattach the DOM tree to the locations tree jQuery object.
        domTree.attach($('.locations-tree'));
      })

      // Maintain a list of hierarchy changes as items are moved.
      var changes = {}
      tree.on('node.drop', function(event, source, target, index) {

        // Determine the new parent. If target is null, then it means that the
        // child was moved to the root context, which either means the child
        // will no longer have a parent, or if we are in the context of a
        // specific asset's children, that asset will become the new parent.
        var new_parent = (target === null) ? settings.asset_parent : target.asset_id

        // Create a change record, if one doesn't already exist.
        if (!changes.hasOwnProperty(source.id)) {
          if (source.original_parent !== new_parent) {
            changes[source.id] = {
              'asset_id': source.asset_id,
              'original_parent': source.original_parent,
              'new_parent': new_parent,
            }
          }
        }

        // Otherwise, if a change record already exists, we will either update
        // it, or delete it (if the child is changing back to its original
        // parent, in which case a change is no longer necessary).
        else {
          if (changes[source.id].original_parent !== new_parent) {
            changes[source.id].new_parent = new_parent
          }
          else {
            delete changes[source.id]
          }
        }

        // Save the change records as a JSON string in the hidden input field.
        $('input[name=changes]').val(JSON.stringify(changes))
      })

      // Link to locations when drag and drop is disabled.
      tree.on('node.click', function(event, node) {
        event.preventDefault()
        if (node.url && !dragAndDropEnabled) {
          window.location.href = node.url
        }
      });

    }
  }

})(jQuery, Drupal, drupalSettings)
