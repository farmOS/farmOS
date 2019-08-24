(function ($) {
  Drupal.behaviors.farm_livestock_weight_graph = {
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
        for (var d in data) {
          if (default_units == '') {
            default_units = data[d].units;
          }
          var date = new Date(d).toISOString();
          dates.push(date);
          values.push(data[d].total_weight/data[d].animal_count);
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
