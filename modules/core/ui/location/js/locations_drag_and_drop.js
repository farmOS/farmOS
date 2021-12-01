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
      var toggleDragAndDrop = function() {
        $('input#edit-save').attr('disabled', !dragAndDropEnabled)
        $('input#edit-reset').attr('disabled', !dragAndDropEnabled)
        domTree.config.dragAndDrop.enabled = dragAndDropEnabled
      }

      var tree = new InspireTree({
        data: settings.asset_tree,
      })
      var domTree = new InspireTreeDOM(tree, {
        target: '.locations-tree',
        dragAndDrop: true
      })
      tree.nodes().expand()

      var dragAndDropEnabled = false
      toggleDragAndDrop()
      $('input#edit-toggle').on('click', function(event) {
        event.preventDefault()
        dragAndDropEnabled = !dragAndDropEnabled
        toggleDragAndDrop()
        // Reattach the DOM tree to the locations tree jQuery object.
        domTree.attach($('.locations-tree'));
      })

      var changes = {}
      tree.on('node.drop', function(event, source, target, index) {
        var new_parent = (target === null) ? settings.asset_parent : target.asset_id
        if (!changes.hasOwnProperty(source.id)) {
          if (source.original_parent !== new_parent) {
            changes[source.id] = {
              'asset_id': source.asset_id,
              'original_parent': source.original_parent,
              'new_parent': new_parent,
            }
          }
        }
        else {
          if (changes[source.id].original_parent !== new_parent) {
            changes[source.id].new_parent = new_parent
          }
          else {
            delete changes[source.id]
          }
        }
        $('input[name=changes]').val(JSON.stringify(changes))
      })

      tree.on('node.click', function(event, node) {
        event.preventDefault()
        if (node.url && !dragAndDropEnabled) {
          window.location.href = node.url
        }
      });

    }
  }

})(jQuery, Drupal, drupalSettings)
