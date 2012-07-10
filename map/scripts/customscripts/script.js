var map, drawControls;

$(function() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(success, error);
	}
});

function success(position) {
	map = new OpenLayers.Map({
		div: "map",
		theme: null,
		controls: [
			new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.TouchNavigation({
				dragPanOptions: {
					enableKinetic: true
				}
			}),
			new OpenLayers.Control.Zoom()
		],
		layers: [
			new OpenLayers.Layer.OSM("OpenStreetMap", null, {
				transitionEffect: "resize"
			})
		]
	});
	
	var lonlat = new OpenLayers.LonLat(position.coords.longitude, position.coords.latitude).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
	map.setCenter(lonlat);
	map.zoomTo(14);
		
	// Create new layer and add layer to the map	
	var markers = new OpenLayers.Layer.Markers("Markers");
	map.addLayer(markers);

	// Adding the markers to the layer
	var size = new OpenLayers.Size(25,25);
	var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
	var icon = new OpenLayers.Icon('img/my-location.png', size, offset);
	markers.addMarker(new OpenLayers.Marker(lonlat,icon));
	
	$.getJSON('../Building.json', function(data) {
        $.each(data.Building, function(key, val) {
            addMarker(markers, val.longitude, val.latitude);
        });
    });
}

function addMarker(layer, lon, lat) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    var size = new OpenLayers.Size(25,41);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('img/marker.png', size, offset);
    
    layer.addMarker(new OpenLayers.Marker(lonlat,icon));
}

function error(message) {
	alert("GEOLocation not supported");
}