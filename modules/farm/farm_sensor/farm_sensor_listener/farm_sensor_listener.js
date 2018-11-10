(function ($) {
  Drupal.behaviors.farm_area_generate = {
    attach: function (context, settings) {

      // Calculate the timezone offset in milliseconds.
      var tzoffset = (new Date()).getTimezoneOffset() * 60000;

      // Iterate through the graphs.
      for (var i = 0; i < settings.farm_sensor_listener.graphs.length; i++) {

        // Get the graph name, id, and data.
        var name = settings.farm_sensor_listener.graphs[i]['name'];
        var id = settings.farm_sensor_listener.graphs[i]['id'];
        var data = settings.farm_sensor_listener.graphs[i]['data'];

        // Initialize variables.
        var dates=[];
        var values=[];

        // Iterate through the data and put it into the arrays.
        for (var j = 0; j < data.length; j++) {
          var date = new Date((data[j].timestamp * 1000) - tzoffset).toISOString();
          dates.push(date);
          values.push(data[j][name]);
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
          xaxis: { title: 'date' },
          yaxis: { title: name }
        };

        // Draw the graph to the element.
        element = document.getElementById(id);
        Plotly.newPlot(element, graph_data, layout);
      }
    }
  };
}(jQuery));
