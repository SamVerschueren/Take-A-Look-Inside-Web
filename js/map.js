var myLat;
var myLon; 
var buildingLayer;
var markerFeatures;
var activePopup;
var iconSize = new OpenLayers.Size(25,41);
var iconOffset = new OpenLayers.Pixel(-(iconSize.w/2), -iconSize.h);

$(function() {
    if(window.location.hash == '') {
       // alert('Go to index page');
    }
    else {
     //   alert('Go to the map, token -> ' + window.location.hash);
    }
    
 
}); 
$("div#map").live('pageshow', function() {
    if(!mapLoaded) {
         if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(loadMap, function() {
                    alert('Could not detect');
                });    
        }  
    }         
});

$("div#map").live('pagebeforeshow', function() {  
    if(mapLoaded){ 
        if(typeof myRouteVector!='undefined')
            myRouteVector.destroyFeatures();
    }else{
        $.getJSON('http://tali.irail.be/REST/Category.json',function(data){
            $.each(data.category, function (key,val){
                var checkbox = $("<input />").attr({type: 'checkbox', id: 'filter' + val.categoryID, checked: 'checked'});
                $(checkbox).change(filterClick);
                
                var label = $("<label />").attr('for', 'filter' + val.categoryID).html(val.name);
                var li = $("<li />").attr('class', val.name.toLowerCase()).append(checkbox).append(' ').append(label); 
                $('ul#filterSection').append(li);  
            });
        });
    }              
});  

function loadMap(position) {
    var mapBoxTiles = new OpenLayers.Layer.XYZ(
                                            "MapBox Streets",
                                            [
                                                "http://a.tiles.mapbox.com/v3/mapbox.mapbox-streets/${z}/${x}/${y}.png",
                                                "http://b.tiles.mapbox.com/v3/mapbox.mapbox-streets/${z}/${x}/${y}.png",
                                                "http://c.tiles.mapbox.com/v3/mapbox.mapbox-streets/${z}/${x}/${y}.png",
                                                "http://d.tiles.mapbox.com/v3/mapbox.mapbox-streets/${z}/${x}/${y}.png"
                                            ], {
                                                sphericalMercator: true,
                                                wrapDateLine: true,
                                                transitionEffect: "resize",
                                                buffer: 1,
                                                numZoomLevels: 18
                                            }
                                         );
    mapLoaded = true;   
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
        layers: [mapBoxTiles]
    }); 
    
    myLon=position.coords.longitude;
    myLat=position.coords.latitude;
    
    lonlat = new OpenLayers.LonLat(myLon, myLat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    
    map.setCenter(lonlat);
    map.zoomTo(16);     
}
