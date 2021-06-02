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
      var dragAndDropEnabled = false
      $('a.locations-tree-save').attr('disabled', !dragAndDropEnabled)
      $('a.locations-tree-reset').attr('disabled', !dragAndDropEnabled)

      var tree = new InspireTree({
        data: settings.asset_tree,
      })
      var domTree = new InspireTreeDOM(tree, {
        target: '.locations-tree',
      })
      tree.nodes().expand()

      $('.locations-tree-toggle').on('click', function(event) {
        event.preventDefault()
        dragAndDropEnabled = !dragAndDropEnabled
        $('a.locations-tree-save').attr('disabled', !dragAndDropEnabled)
        $('a.locations-tree-reset').attr('disabled', !dragAndDropEnabled)
        domTree.config.dragAndDrop.enabled = dragAndDropEnabled
      })


      var changes = {}
      tree.on('node.drop', function(event, source, target, index) {
        var destination = (target === null) ? settings.asset_parent : target.uuid
        if (!changes.hasOwnProperty(source.id)) {
          if (source.original_parent !== destination) {
            changes[source.id] = {
              'uuid': source.uuid,
              'original_parent': source.original_parent,
              'original_type': source.original_type,
              'destination': destination,
              'type': (target === null) ? settings.asset_parent_type : target.type,
            }
          }
        }
        else {
          if (changes[source.id].original_parent !== destination) {
            changes[source.id].destination = destination
          }
          else {
            delete changes[source.id]
          }
        }

      })

      $('.locations-tree-reset').on('click', function(event) {
        event.preventDefault()
        // Reset the changes so nothing is pushed accidentally.
        changes = {}
        // Reset the tree to the original status.
        tree.reload()
      })

      $('.locations-tree-save').on('click', function(event) {
        event.preventDefault()
        var button = $(this)
        var messages = new Drupal.Message()
        messages.clear()

        var entries = Object.entries(changes)
        if (entries.length <= 0) {
          messages.add(Drupal.t('No changes to save'), { type: 'status' })
          return
        }

        button.addClass('spinner')
        button.attr('disabled',true)

        var token = ''
        $.ajax({
          async: false,
          url: Drupal.url('session/token'),
          success(data) {
            if (data) {
              token = data
            }
          },
        })

        for (var [treeUuid, item] of entries) {
          if (item.destination === '' && item.original_parent !== '') {
            var deleteItem = {
              'data': [
                {
                  'type': 'asset--' + item.original_type,
                  'id': item.original_parent,
                }
              ]
            }
            $.ajax({
              type: 'DELETE',
              cache: false,
              headers: {
                'X-CSRF-Token': token,
              },
              url: '/api/asset/' + item.original_type + '/' + item.uuid + '/relationships/parent',
              data: JSON.stringify(deleteItem),
              contentType: 'application/vnd.api+json',
              success: function success(data) {
                messages.clear()
                messages.add(Drupal.t('Locations have been saved'), { type: 'status' })
                button.toggleClass('spinner')
                button.attr('disabled',false)
                delete changes.treeUuid
              },
              error: function error(xmlhttp) {
                var e = new Drupal.AjaxError(xmlhttp)
                messages.clear()
                messages.add(e.message, { type: 'error' })
                button.removeClass('spinner')
                button.attr('disabled',false)
              }
            })
          }
          else {
            var patch = {
              'data': [
                {
                  'type': 'asset--' + item.type,
                  'id': item.destination,
                }
              ]
            }
            $.ajax({
              type: 'POST',
              cache: false,
              headers: {
                'X-CSRF-Token': token,
              },
              url: '/api/asset/' + item.type + '/' + item.uuid + '/relationships/parent',
              data: JSON.stringify(patch),
              contentType: 'application/vnd.api+json',
              success: function success(data) {
                if (item.original_parent !== settings.asset_parent) {
                  var deleteItem = {
                    'data': [
                      {
                        'type': 'asset--' + item.original_type,
                        'id': item.original_parent,
                      }
                    ]
                  }

                  $.ajax({
                    type: 'DELETE',
                    cache: false,
                    headers: {
                      'X-CSRF-Token': token,
                    },
                    url: '/api/asset/' + item.original_type + '/' + item.uuid + '/relationships/parent',
                    data: JSON.stringify(deleteItem),
                    contentType: 'application/vnd.api+json',
                    success: function success(data) {
                      messages.clear()
                      messages.add(Drupal.t('Locations have been saved'), { type: 'status' })
                      button.removeClass('spinner')
                      button.attr('disabled',false)
                      delete changes.treeUuid
                    },
                    error: function error(xmlhttp) {
                      var e = new Drupal.AjaxError(xmlhttp)
                      messages.clear()
                      messages.add(e.message, { type: 'error' })
                      button.removeClass('spinner')
                      button.attr('disabled',false)
                    }
                  })
                }
                else {
                  messages.clear()
                  messages.add(Drupal.t('Locations have been saved'), { type: 'status' })
                  button.removeClass('spinner')
                  button.attr('disabled',false)
                }
              },
              error: function error(xmlhttp) {
                var e = new Drupal.AjaxError(xmlhttp)
                messages.clear()
                messages.add(e.message, { type: 'error' })
                button.removeClass('spinner')
                button.attr('disabled',false)
              }
            })
          }
        }
      })


      tree.on('node.click', function(event, node) {
        event.preventDefault()
        window.location.href = node.url
      });

    }
  }

})(jQuery, Drupal, drupalSettings)
