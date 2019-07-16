// Get the plan ID from Drupal.settings.
var planId = Drupal.settings.farm_plan_map.plan_id;

// Get the map object.
var map = data.map;

// Define the GeoJSON layers that we will include.
var farmPlanMapLayers = [
  {
    url: "/farm/areas/geojson/all/" + planId,
    color: "purple",
  },
  {
    url: "/farm/assets/geojson/full/all/" + planId,
    color: "green",
  },
];

// Define color styles.
var colors = {
  purple: "rgba(204,51,102,1)",
  green: "rgba(51,153,51,1)",
};

// Start an empty extent which we will extend as sources are loaded.
var extent = ol.extent.createEmpty();

// Define a function for zooming to all vector sources within the map.
function zoomToVectorSources() {
  map.getLayers().forEach(function(layer) {
    if (typeof layer.getSource === "function") {
      var source = layer.getSource();
      if (source !== "null" && source instanceof ol.source.Vector) {
        if (source.getState() === "ready" && source.getFeatures().length > 0) {
          ol.extent.extend(extent, source.getExtent());
          var fitOptions = {
            size: map.getSize(),
            constrainResolution: false,
            padding: [20, 20, 20, 20],
          };
          map.getView().fit(extent, fitOptions);
        }
      }
    }
  });
}

// Build each source, layer, and style.
for (var i = 0; i < farmPlanMapLayers.length; i++) {

  // Create the style.
  var fill = new ol.style.Fill({
    color: "rgba(0,0,0,0)"
  });
  var stroke = new ol.style.Stroke({
    color: colors[farmPlanMapLayers[i].color],
    width: 2
  });
  var style = [
    new ol.style.Style({
      image: new ol.style.Circle({
        fill: fill,
        stroke: stroke,
        radius: 4
      }),
      fill: fill,
      stroke: stroke
    })
  ];

  // Create the source.
  var source = new ol.source.Vector({
    url: farmPlanMapLayers[i].url,
    format: new ol.format.GeoJSON(),
  });

  // Create the layer and add it to the map.
  var layer = new ol.layer.Vector({
    source: source,
    style: style,
  });
  map.addLayer(layer);

  // Zoom to the combined extent of all sources as they are loaded.
  source.on("change", zoomToVectorSources);
}
