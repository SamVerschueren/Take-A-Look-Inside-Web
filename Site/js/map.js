var myLat;
var myLon; 
var buildingLayer;
var markerFeatures;
var activePopup;
var liked;

$("div#map").live('pagebeforeshow', function() {
    if(mapLoaded) 
        if(typeof myRouteVector!='undefined')
            myRouteVector.destroyFeatures();  
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
        showMapDirectPopup();        
        //markerArray[mapDirect].erase();             
    }
    //showMapDirectPopup(); 
});

function showMapDirectPopup(){    
    if(typeof mapDirect!='undefined'){
        if(markerFeatures[mapDirect].popup==null )
            fillPopup(markerFeatures[mapDirect]);
        if(!markerFeatures[mapDirect].popup.visible())       
            showPopup(markerFeatures[mapDirect].popup) 
        mapDirect=undefined;
    }  
}

function showPopup(popup){
    //alert(Object.keys(popup));
    if(typeof activePopup!='undefined'){
        if(activePopup.id==popup.id)
            activePopup.toggle();
        else{
            activePopup.hide();
            popup.show();
        }
    }
    else
        popup.show();
    activePopup=popup;
    if(typeof activePopup!='undefined' && activePopup.visible()){
        localStorage["mustsee"+activePopup.id]==1 ?
            $("img#mustSeeButton").attr("src","img/favorites-selected.png"):
            $("img#mustSeeButton").attr("src","img/favorites.png"); 
        $('div#mapButtons').show();
    }
    else
        $('div#mapButtons').hide();
}

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
    $.getJSON('http://tali.irail.be/REST/Building.json?select=buildingID;longitude;latitude', function(data) {
       // var latDestination;
       // var lonDestination;
       markerFeatures=new Array();
        $.each(data.building, function(key, val) {
            addMarker(buildingLayer, val.longitude, val.latitude, val.buildingID);    

            latDestination=val.latitude;
            lonDestination=val.longitude;
        });
        showMapDirectPopup();                
    });
   
}

function addMarker(layer, lon, lat, id) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    var size = new OpenLayers.Size(25,41);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('img/marker.png', size, offset);

    var feature = new OpenLayers.Feature(layer, lonlat); 
    feature.data.icon = icon;
    feature.data.overflow = 'auto';
    feature.id = id;              
    var marker = feature.createMarker();   
    markerFeatures[id] = feature;
    marker.events.register('click', feature, markerClick);    
    layer.addMarker(marker);
}

var markerClick = function (evt) {
    var caller = this;
    if(caller.popup==null)
        fillPopup(caller);
    else showPopup(caller.popup);
    OpenLayers.Event.stop(evt);
}
function fillPopup (feature){    
     $.getJSON('http://tali.irail.be/REST/Building/buildingID/' + feature.id + '.json', function(data) {         
         var lonlat = new OpenLayers.LonLat(data.building[0].longitude, data.building[0].latitude).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));         
         var popup= new OpenLayers.Popup(feature.id,
                   lonlat,
                   null,
                   data.building[0].name,
                   true,
                   function(){closePopup()});
        popup.autoSize=true;
        popup.setBackgroundColor('#444');
        feature.popup=popup; 
        feature.popup.contentHTML='<h1>' + data.building[0].name + '</h1><p>' + data.building[0].description + '<span class="moreInfo">More info...</span></p>';
        
        map.addPopup(feature.popup);
        showPopup(feature.popup);
        markerFeatures[feature.id]=feature;              
    });        
}

function mustSeeClick(){
    var buildingID=activePopup.id;
    var device="lievenANDROID";
    var method;
    
    
    if(localStorage["mustsee"+activePopup.id]==1){
        method="unlike";
        localStorage["mustsee"+activePopup.id]=0;
        
    }else {
        method="like";
        localStorage["mustsee"+activePopup.id]=1;
    }
    $.post("http://localhost/REST/Building.php?buildingID="+buildingID+"&method="+method+"&device="+device,function(data){
        alert(data);
    }); 
        if(localStorage["mustsee"+activePopup.id]==1)
        $("img#mustSeeButton").attr("src","img/favorites-selected.png")
    else
        $("img#mustSeeButton").attr("src","img/favorites.png");  
    

}

function routeToClick(){
    
    $.getJSON('http://tali.irail.be/REST/Building/buildingID/' + activePopup.id + '.json', function(data) {  
        routeTo(data.building[0].longitude,data.building[0].latitude);
        closePopup();
    });
}
function closePopup(){
    $('div#mapButtons').hide();
    if(typeof activePopup!='undefined'){
        activePopup.hide();
        activePopup=undefined;
    }
    
}

function routeTo (lon,lat) {
    //get route JSON
      var url = 'http://tali.irail.be/REST/transport.json?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
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