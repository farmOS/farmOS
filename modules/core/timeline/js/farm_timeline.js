(function (Drupal, drupalSettings, once, farmOS) {
  Drupal.behaviors.farm_timeline = {
    attach: function (context, settings) {
      once('timelineGantt', '.farm-timeline', context).forEach(async function (element) {
        const opts = {
          props: {
            taskElementHook: (node, task) => {
              let popup;

              function onHover() {
                popup = createPopup(task, node);
              }

              function onLeave() {
                if (popup) {
                  popup.remove();
                }
              }

              node.addEventListener('mouseenter', onHover);
              node.addEventListener('mouseleave', onLeave);
              return {
                destroy() {
                  node.removeEventListener('mouseenter', onHover);
                  node.removeEventListener('mouseleave', onLeave);
                }
              }
            }
          },
        };

        // Create the timeline instance.
        const timeline = farmOS.timeline.create(element, opts);

        // Helper function to process a single row and its children recursively.
        const processRow = async function(row) {

          // Handle URL string to fetch row data dynamically.
          if (typeof row === "string") {

            // Fetch and process the array of returned rows.
            const data = await fetch(row)
              .then(res => res.json())
              .then(data => data?.rows ?? []);
            const awaitedRows = await Promise.all(data.map(processRow));

            // Aggregate all rows and tasks from processed rows.
            return awaitedRows.reduce((accumulator, current) => {
              return {
                rows: [...accumulator.rows, ...current.rows],
                tasks: [...accumulator.tasks, ...current.tasks],
              };
            }, {
              rows: [],
              tasks: []
            });
          } else if (!row) {
            // Handle potential null/undefined rows
            return { rows: [], tasks: [] };
          }

          // Begin processing a single parent row.
          // First process all child rows.
          const awaitedChildren = await Promise.all((row.children ?? []).map(processRow));

          // Aggregate child rows and tasks.
          const aggregatedChildren = awaitedChildren.reduce((accumulator, current) => {
            return {
              rows: [...accumulator.rows, ...current.rows],
              tasks: [...accumulator.tasks, ...current.tasks],
            };
          }, {
            rows: [],
            tasks: []
          });

          // Map the parent row to a row object and include children rows.
          row.children = aggregatedChildren.rows;
          const mappedRow = Drupal.behaviors.farm_timeline.mapRow(row);

          // Collect all tasks from the parent row and add all child tasks.
          const tasks = [
            ...(row.tasks?.map(Drupal.behaviors.farm_timeline.mapTask) ?? []),
            ...aggregatedChildren.tasks,
          ];

          // Return the final processed parent row and all tasks.
          return {
            rows: [mappedRow],
            tasks: tasks,
          };
        };

        // Helper function to add row to timeline after processing.
        const addRow = async function(row) {
          return processRow(row)
            .then(data => {
              timeline.addRows(data.rows);
              timeline.addTasks(data.tasks);
            })
        }

        // Add all provided timeline rows to the timeline.
        const timelineRows = JSON.parse(element.dataset?.timelineRows) ?? [];
        await Promise.all(timelineRows.map(addRow));

        function createPopup(task, node) {
          const rect = node.getBoundingClientRect();
          const div = document.createElement('div');
          div.className = 'sg-popup';
          div.innerHTML = `
            <div class="sg-popup-title">${task.label}</div>
            <div class="sg-popup-item">From: ${new Date(task.from).toLocaleDateString()}</div>
            <div class="sg-popup-item">To: ${new Date(task.to).toLocaleDateString()}</div>
        `;
          div.style.position = 'absolute';
          div.style.top = `${rect.bottom + window.scrollY + 5}px`;
          div.style.left = `${rect.left + rect.width / 2}px`;

          if (task?.meta?.entity_type === 'log') {
            div.innerHTML = `
            <div class="sg-popup-title">Log: ${task.meta.label}</div>
            <div class="sg-popup-item">Type: ${task.meta.entity_bundle}</div>
            <div class="sg-popup-item">Timestamp: ${new Date(task.from).toLocaleDateString()}</div>
        `;
          }

          if (task?.meta?.stage) {
            div.innerHTML = `
            <div class="sg-popup-title">Stage: ${task.meta.stage}</div>
            <div class="sg-popup-item">From: ${new Date(task.from).toLocaleDateString()}</div>
            <div class="sg-popup-item">To: ${new Date(task.to).toLocaleDateString()}</div>
        `;
          }

          document.body.appendChild(div);
          return div;
        }

        // Open entity page on click.
        timeline.timeline.api.tasks.on.select((task) => {
          task = task[0];
          if (task.model?.linkUrl) {
            window.location.replace(task.model.linkUrl);
          } else if (task.model?.editUrl) {
            var ajaxSettings = {
              url: task.model.editUrl,
              dialogType: 'dialog',
              dialogRenderer: 'off_canvas',
            };
            var myAjaxObject = Drupal.ajax(ajaxSettings);
            myAjaxObject.execute();
          } else {
            let dialog = document.getElementById('drupal-off-canvas');
            if (dialog) {
              Drupal.dialog(dialog, {}).close();
            }
          }
        });
      });
    },
    // Helper function to map row properties.
    mapRow: function(row) {
      return {
        id: row.id,
        label: row.label,
        headerHtml: row.link,
        expanded: row.expanded ?? false,
        draggable: row.draggable ?? false,
        resizable: row.resizable ?? false,
        // Only provide a children array if there are children
        // otherwise an expanded icon will appear for rows without children.
        children: row.children.length ? row.children : null,
      };
    },
    // Helper function to map task properties.
    mapTask: function(task) {
      return {
        id: task.id,
        resourceId: task.resource_id,
        from: task.start,
        to: task.end,
        label: task.label ?? '',
        linkUrl: task.link_url,
        editUrl: task.edit_url,
        draggable: task.draggable ?? false,
        resizable: task.resizable ?? false,
        meta: task?.meta,
        classes: task.classes,
      };
    },
  };
}(Drupal, drupalSettings, once, farmOS));
