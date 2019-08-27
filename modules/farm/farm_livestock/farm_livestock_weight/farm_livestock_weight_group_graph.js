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
        var average_dates=[];
        var average_values=[];
        var average_value_text=[];

        var gain_dates=[];
        var gain_values=[];

        // Initialize the default_units
        var default_units = '';

        // Iterate through the data and put it into the arrays.
        for (var d in data) {
          if (default_units == '') {
            default_units = data[d].units;
          }
          var date = new Date(d).toISOString();
          average_dates.push(date);
          average_values.push(data[d].average_weight);
          average_value_text.push(data[d].animal_count + " animals weighed");

          if (data[d].hasOwnProperty('gain')) {
            gain_dates.push(date);
            gain_values.push(data[d].gain);
          }
        }

        // Assemble variables for plotly.
        var average_data={
          x: average_dates,
          y: average_values,
          text: average_value_text,
          name: 'Average Weight',
          type: 'scatter'
        };

        var gain_data={
          x: gain_dates,
          y: gain_values,
          yaxis: 'y2',
          name: 'Average Daily Gain',
          type: 'scatter'
        };

        var all_data = [average_data, gain_data];

        var layout = {
          title: name,
          height: 400,
          xaxis: { title: 'Date' },
          yaxis: { title: 'Average Weight (' + default_units + ')' },
          yaxis2: {
            title: 'Average Daily Gain (' + default_units + ')',
            overlaying: 'y',
            side: 'right'
          }
        };

        // Draw the graph to the element.
        element = document.getElementById(id);
        Plotly.newPlot(element, all_data, layout);
      }
    }
  };
}(jQuery));
