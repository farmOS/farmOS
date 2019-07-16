(function ($) {
  Drupal.behaviors.farm_area_generate = {
    attach: function (context, settings) {

      // Calculate the timezone offset in milliseconds.
      var tzoffset = (new Date()).getTimezoneOffset() * 60000;

      // Iterate through the graphs.
      for (var i = 0; i < settings.farm_livestock_report.graphs.length; i++) {

        // Get the graph name, id, and data.
        var name = settings.farm_livestock_report.graphs[i]['name'];
        var id = settings.farm_livestock_report.graphs[i]['id'];
        var data = settings.farm_livestock_report.graphs[i]['data'];

        // Initialize variables.
        var dates=[];
        var values=[];

        // Initialize the default_units
        var default_units = '';

        // Iterate through the data and put it into the arrays.
        for (var j = 0; j < data.length; j++) {
          // Set the default units to the first log with recorded units.
          if (default_units == '') {
            default_units = data[j].units;
          }
          // Exclude weights that have a unit different than the default units.
          // Accept weights that do not have a unit recorded.
          if(data[j].units != default_units && data[j].units != '') {
            continue;
          }

          var date = new Date((data[j].timestamp * 1000) - tzoffset).toISOString();
          dates.push(date);
          values.push(data[j].value);
        }

        // Assemble variables for plotly.
        var graph_data=[{
          x: dates,
          y: values,
          name: name,
          type: 'scatter'
        }];
        var layout = {
          title: name,
          height: 400,
          xaxis: { title: 'Date' },
          yaxis: { title: 'Weight (' + default_units + ')' }
        };

        // Draw the graph to the element.
        element = document.getElementById(id);
        Plotly.newPlot(element, graph_data, layout);
      }
    }
  };
}(jQuery));
