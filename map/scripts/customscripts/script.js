var map, drawControls;

$(function() {
    if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(success, error);
	}
	
	$.getJSON('/REST/Category.json', function(data) {	 
        $.each(data.Category, function(key, value) {            
            var checkbox = $("<input />").attr({type: 'checkbox', id: 'filter' + value.categoryID});
            $(checkbox).change(function() {
                alert(this.id);
            });
              
            var label = $("<label />").attr('for', 'filter' + value.categoryID).html(value.name);
        
            var li = $("<li />").append(checkbox).append(' ').append(label); 
        
            $("ul.filterMenu").append(li);      
        });
	});
	
	$("div.filter").click(function(evt) {
        $("ul.filterMenu").toggle('slow');
	});
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
	map.zoomTo(16);
		
	// Create new layer and add layer to the map	
	var markers = new OpenLayers.Layer.Markers("Markers");
	map.addLayer(markers);

	// Adding the markers to the layer
	var size = new OpenLayers.Size(25,25);
	var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
	var icon = new OpenLayers.Icon('img/my-location.png', size, offset);
	markers.addMarker(new OpenLayers.Marker(lonlat,icon));
	$.getJSON('/TakeALookInside/REST/Building.json?select=buildingID;longitude;latitude', function(data) {
	    var lat;
	    var lon;
        $.each(data.Building, function(key, val) {
            addMarker(markers, val.longitude, val.latitude, val.buildingID);     
            lat=val.latitude;
            lon=val.longitude;
        });
        
        var url = '/TakeALookInside/map/transport.php?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+position.coords.latitude+'&'+
        'flon='+position.coords.longitude+'&'+
        'tlat='+lat+'&'+
        'tlon='+lon+
        '&v=foot&fast=1&layer=mapnik';
        
        //draw route
        var lineLayer = new OpenLayers.Layer.Vector("Line Layer"); 

        map.addLayer(lineLayer);                    
        map.addControl(new OpenLayers.Control.DrawFeature(lineLayer, OpenLayers.Handler.Path));                                     
        var points = new Array(
           new OpenLayers.Geometry.Point('3.7254270','51.0544520'),
           new OpenLayers.Geometry.Point('3.7252200','51.0538500')
        );
        
        var line = new OpenLayers.Geometry.LineString(points);
        
        var style = { 
          strokeColor: '#0000ff', 
          strokeOpacity: 0.5,
          strokeWidth: 5
        };
        
        var lineFeature = new OpenLayers.Feature.Vector(line, null, style);
        lineLayer.addFeatures([lineFeature]);
        
        
        
        $.get(url, function(data) {
            $.each(data.coordinates, function(lon,lat) {
               // addMarker(markers,lat,lon,4);
               
               
            });
        });
    });
    
    
    
    setInterval(function(){ 
        //alert("5sec"); 
    }, 5000);
}

function addMarker(layer, lon, lat, id) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    var size = new OpenLayers.Size(25,41);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('img/marker.png', size, offset);
    
    var feature = new OpenLayers.Feature(layer, lonlat); 
    feature.data.icon = icon;
    feature.closeBox = true;
    feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, { 'autoSize': true });
    feature.data.popupContentHTML = 'Dit is een test.';
    feature.data.overflow = 'auto';
    feature.id = id;        
            
    var marker = feature.createMarker();

    marker.events.register('mousedown', feature, markerClick);
    /*marker.events.register('mouseover', marker, markerOver);
    marker.events.register('mouseout', marker, markerOut);*/
    
    layer.addMarker(marker);
}

var markerClick = function (evt) {
    var caller = this;
    
    $.getJSON('/REST/Building/buildingID/' + caller.id + '.json', function(data) {
        var h1 = $('<h1 />').html(data.Building[0].name);
        
        var myDiv = $('<div />').append(h1);
        
        caller.data.popupContentHTML = $(myDiv).html();
        
        if (caller.popup == null) {
            caller.popup = caller.createPopup(this.closeBox);
            map.addPopup(caller.popup);
            caller.popup.show();
        } else {
            caller.popup.toggle();
        }
        currentPopup = caller.popup;
    });
    
    OpenLayers.Event.stop(evt);
}

/*var markerOver = function(evt) {
    this.setOpacity(0.8);
    document.body.style.cursor='pointer';
        
    OpenLayers.Event.stop(evt);
}

var markerOut = function(evt) {
    this.setOpacity(1);
    document.body.style.cursor='auto';

    OpenLayers.Event.stop(evt);
}*/

function error(message) {
	alert("GEOLocation not supported");
}