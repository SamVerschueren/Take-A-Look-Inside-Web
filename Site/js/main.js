var homeCategory = "";
var mapLoaded = false;

$(function() {    
    $('.button').click(function(event) {
        var id = event.target.id; 
       
        homeCategory = id.toLowerCase();
       
        $(".smallIcon").removeClass("active");
        $("#" + id + ".smallIcon").addClass('active');
       
        window.location.href = "#home_category"; 
       
        changeContent();
    });
});


var changeContent = function() {
    $('h1#home_category_head').html("");
    $('p#home_category_content').html(""); 
    
    if(homeCategory=='mustsee') {
        fillHomeCategoryMustSee();
    } 
    else if(homeCategory=='looklater') {
        
    }
    else if(homeCategory=='seen') {
        
    }
    else if(homeCategory=='favorites') {
        
    }
}

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
    
    lonlat = new OpenLayers.LonLat(position.coords.longitude, position.coords.latitude).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
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
        var url = '/map/transport.php?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+position.coords.latitude+'&'+
        'flon='+position.coords.longitude+'&'+
        'tlat='+latDestination+'&'+
        'tlon='+lonDestination+
        '&v=foot&fast=0&layer=mapnik';
            
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
    
    //markerArray[id] = marker;

    //marker.events.register('mousedown', feature, markerClick);
    
    layer.addMarker(marker);
}

function fillHomeCategoryMustSee() { 
    $('home_category_text').text("Must See"); 
    
    $.getJSON('/REST/Building.json?orderby=mustSee&select=name;buildingID', function(data) {
        var list = $("<ol />"); 
        
        $.each(data.Building, function(key, val) {
            var li = $('<li />').attr('id', val.buildingID).html('<a href=map.html/buildingID='+val.buildingID+'>'+val.name+'</a>'); 
            li.attr('href',"map.html"); 
            list.append(li); 
        });
     
        list.append("</ol>");
         
        $('h1#home_category_head').append('Must See'); 
        $('p#home_category_content').append(list);
    });
};