var map, drawControls;
var markerArray = new Array();

$(function() {
    if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(success, error);
	}
	
	$.getJSON('/REST/Category.json', function(data) {	 
        $.each(data.Category, function(key, value) {            
            var checkbox = $("<input />").attr({type: 'checkbox', id: 'filter' + value.categoryID, checked: 'checked'});
            $(checkbox).change(markerChange);
              
            var label = $("<label />").attr('for', 'filter' + value.categoryID).html(value.name);
            
            var li = $("<li />").append(checkbox).append(' ').append(label); 
        
            $("ul.filterMenu").append(li);      
        });
	});
	
	$("div.filter").click(function(evt) {
        $("ul.filterMenu").toggle('slow');
	});
});

var markerChange = function(evt) {
    var target = evt.target;
    var id = target.id.replace('filter', '');
    
    $.getJSON('/REST/Building/categoryID/' + id + '.json?select=buildingID;longitude;latitude', function(data) {
        $.each(data.Building, function(key, value) {
            if($(target).is(':checked')) {
                markerArray[value.buildingID].display(true);
            }
            else {
                markerArray[value.buildingID].display(false);
            }       
        }); 
    });
}

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
	var locationLayer = new OpenLayers.Layer.Markers('LocationLayer');
	locationLayer.id = 'LocationLayer';
	
	var buildingLayer = new OpenLayers.Layer.Markers('BuildingLayer');
	buildingLayer.id = 'BuildingLayer';
	
	map.addLayer(locationLayer);
	map.addLayer(buildingLayer);

	// Adding the markers to the layer
	var size = new OpenLayers.Size(25,25);
	var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
	var icon = new OpenLayers.Icon('img/my-location.png', size, offset);
	locationLayer.addMarker(new OpenLayers.Marker(lonlat,icon));
	$.getJSON('/REST/Building.json?select=buildingID;longitude;latitude', function(data) {
	    var latDestination;
	    var lonDestination;
        $.each(data.Building, function(key, val) {
            addMarker(buildingLayer, val.longitude, val.latitude, val.buildingID);     
            latDestination=val.latitude;
            lonDestination=val.longitude;
        });    
        /*
        //get route JSON
        var url = '/map/transport.php?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+position.coords.latitude+'&'+
        'flon='+position.coords.longitude+'&'+
        'tlat='+latDestination+'&'+
        'tlon='+lonDestination+
        '&v=foot&fast=1&layer=mapnik';
           
               
        //draw route   
        var ol = new OpenLayers.Layer.OSM(); 
        var routeStyle = { strokeColor: '#0000ff', 
                strokeOpacity: 1,
                strokeWidth: 5
    	};        
        $.get(url, function(data) { 
        	var previouslonpos=0;
        	var previouslatpos=0; 
	         $.each(data.coordinates, function(num,latlonpos) {
	         	if(previouslatpos==0){
	         		previouslonpos=latlonpos[0];
	         		previouslatpos=latlonpos[1];
	         	}
	         	else{	         	
		        	var start_point = new OpenLayers.Geometry.Point(previouslonpos,previouslatpos); 
		    		var end_point = new OpenLayers.Geometry.Point(latlonpos[0],latlonpos[1]);
		    		previouslonpos=latlonpos[0];
	         		previouslatpos=latlonpos[1];
		    		var vector = new OpenLayers.Layer.Vector();
		    		vector.style=routeStyle;
		    		vector.addFeatures([new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString([start_point, end_point]).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")))]);
		    		map.addLayers([ol,vector]);        
				}
	         });
        });*/
    });
}

function addMarker(layer, lon, lat, id) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    var size = new OpenLayers.Size(25,41);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('img/marker.png', size, offset);
    
    var popup = new OpenLayers.Popup.FramedCloud("Popup", 
        lonlat, null,
        '<a target="_blank" href="http://openlayers.org/">We</a> ' +
        'could be here.<br>Or elsewhere.', null,
        true // <-- true if we want a close (X) button, false otherwise
    );
    
    popup.hide();
    
    var feature = new OpenLayers.Feature(layer, lonlat); 
    feature.data.icon = icon;
    feature.popup = popup;
    feature.data.overflow = 'auto';
    feature.id = id;        
            
    var marker = feature.createMarker();
                             
    map.addPopup(popup);
    
    markerArray[id] = marker;

    marker.events.register('mousedown', feature, markerClick);
    
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

function error(message) {
	alert("GEOLocation not supported");
}