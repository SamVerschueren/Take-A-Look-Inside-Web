var myLat;
var myLon; 
var buildingLayer;
var markerFeatures;
var activePopup;

$("div#map").live('pagebeforeshow', function() {
    if(mapLoaded) 
        if(typeof myRouteVector!='undefined')
            myRouteVector.destroyFeatures();  
            
                 /*   if(mapDirect != null){
        $.each(markerFeatures, function (markerFeature){
            if(typeof markerFeatures[markerFeature] != 'undefined')
                markerFeatures[markerFeature].popup.hide();
        });
        //activeFeaturePopup.hide();
        markerFeatures[mapDirect].popup.show();
        }  */ 
   /* if(typeof myRouteVector!='undefined')
        myRouteVector.destroyFeatures();   */
});      

$("div#map").live('pageshow', function() {
    if(!mapLoaded) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(loadMap, function() {
                alert('Could not detect position.');
            });
        }
    }
    else {
        map.setCenter(lonlat);          
    }
});

function loadMap(position) {
    mapLoaded = true;
    
    map = new OpenLayers.Map({
        div: "mapview",
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
    myLon=position.coords.longitude;
    myLat=position.coords.latitude;
    lonlat = new OpenLayers.LonLat(myLon, myLat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    
    
    map.setCenter(lonlat);
    map.zoomTo(16);
    
    var locationLayer = new OpenLayers.Layer.Markers('LocationLayer');
    locationLayer.id = 'LocationLayer';
    
    var buildingLayer = new OpenLayers.Layer.Markers('BuildingLayer');
    buildingLayer.id = 'BuildingLayer';
    
    var ol = new OpenLayers.Layer.OSM(); 
    myRouteVector = new OpenLayers.Layer.Vector();
    map.addLayers([ol,myRouteVector]);   
    
    map.addLayer(locationLayer);
    map.addLayer(buildingLayer);
    
    // Adding the markers to the layer
    var size = new OpenLayers.Size(25,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
    var icon = new OpenLayers.Icon('img/my-location.png', size, offset);
    locationLayer.addMarker(new OpenLayers.Marker(lonlat,icon));
    $.getJSON('/REST/Building.json?select=buildingID;longitude;latitude', function(data) {
       // var latDestination;
       // var lonDestination;
        $.each(data.Building, function(key, val) {
            addMarker(buildingLayer, val.longitude, val.latitude, val.buildingID);     

            latDestination=val.latitude;
            lonDestination=val.longitude;
        });    

        //get route JSON
        /*var url = '/map/transport.php?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+position.coords.latitude+'&'+
        'flon='+position.coords.longitude+'&'+
        'tlat='+latDestination+'&'+
        'tlon='+lonDestination+
        '&v=foot&fast=0&layer=mapnik';*/
            
    });
}

function addMarker(layer, lon, lat, id) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    var size = new OpenLayers.Size(25,41);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('img/marker.png', size, offset);
    
    var popup = new OpenLayers.Popup.FramedCloud("Popup", 
        lonlat, null,
        '<button onclick="routeTo('+lon+','+lat+')">Route</button>'
        , null,
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

function routeTo (lon,lat) {
    
    currentPopup.toggle();
    //get route JSON
      var url = '/REST/transport.json?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+myLat+'&'+
        'flon='+myLon+'&'+
        'tlat='+lat+'&'+
        'tlon='+lon+
        '&v=foot&fast=0&layer=mapnik';      
       
        //draw route   
    myRouteVector.destroyFeatures();   
    var routeStyle = { strokeColor: '#0000ff', 
            strokeOpacity: 0.5,
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
                
                myRouteVector.style=routeStyle;
                myRouteVector.addFeatures([new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString([start_point, end_point]).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")))]);     
            }
         });
    });
}