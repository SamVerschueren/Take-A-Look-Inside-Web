var myLat;
var myLon; 
var buildingLayer;
var markerFeatures;
var activePopup;
var routeDrawnToLocation=-1;
var iconSize = new OpenLayers.Size(25,41);
var iconOffset = new OpenLayers.Pixel(-(iconSize.w/2), -iconSize.h);

var server;

$(function() {
    server = $("base").attr("href");
    
    if($("div#map").length > 0) {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(loadMap, function() {
                loadMap();
            });    
        }
        else {
            loadMap();
        }

        loadFilter();
        $('#routeToButton').click(function(event){
            routeToClick();
        })
        $('div#fireFilterSection').click(fireFilterSection);
        
        if($('input[name=redirect]').val() != '') {
            var token = $('input[name=redirect]').val();
            
            $.getJSON(server+'/Building?token='+token,function(building){
                mapDirect=building.id;
                
                showMapDirectPopup();
            });
        }
    }
});

function loadFilter() {
    $.getJSON(server+'/Category', function(categories) {
        $.each(categories, function (key, category) {
            var checkbox = $("<input />").attr({type: 'checkbox', id: 'filter' + category.id, checked: 'checked'});
            $(checkbox).change(filterClick);
            
            var label = $("<label />").attr('for', 'filter' + category.id).html(category.name);
            var li = $("<li />").attr('class',category.name.toLowerCase()).append(checkbox).append(' ').append(label); 
            //Make whole label clickable
            $(li).click(function(evt) {
                //find the checkbox
                var c=$(this).find('input:first');
                //change checked state
                c.attr('checked',!c.is(':checked'));
                //call the filterclick method
                c.trigger('change');                    
            });  
            $('ul#filterSection').append(li);  
        });
    });
}

/**
 * This function will be executed when clicked on the filtersection. 
 */
var fireFilterSection = function(event) {
    $('div#filter').slideToggle('slow', function() {
        if($('div#filter').is(':visible')) {
            $('#legendarrow').addClass('rotate');   
        }
        else {
            $('#legendarrow').removeClass('rotate');   
        }
    }); 
}

/**
 * Event that is fired after selecting or unselecting a filter in the filter menu. * 
 */
var filterClick = function(evt) {
    var target = evt.target;
    var id = target.id.replace('filter', '');
    //Get all buildings in the category
    $.getJSON(server+'/Building/Category/' + id, function(buildings) {
        //for each category        
        $.each(buildings, function(key, building) {
            //if it is checked, display     it
            if($(target).is(':checked')) {
                markerFeatures[building.id].marker.display(true);
            }
            //if not checked, don't display
            else {
                markerFeatures[building.id].marker.display(false);
            }       
        }); 
    });
}

/**
 * Method that should be called after setting the mapDirect variable
 * 
 * It makes it possible to move to the map page and show
 * a popup of the building with the buildingID that is stored in 'mapDirect'.
 * Sets center of the map to the target building.
 * Clears mapDirect afterwards
 */
function showMapDirectPopup(){    
    if(typeof mapDirect == 'undefined' || typeof markerFeatures[mapDirect] == 'undefined') {
        map.setCenter(lonlat);  
    }
    else {
        //create popup        
        if(markerFeatures[mapDirect].popup==null) {
            fillPopup(markerFeatures[mapDirect]);
        }
        //if popup not shown
        if(markerFeatures[mapDirect].popup !=null && !markerFeatures[mapDirect].popup.visible()) {
            //show it       
            showPopup(markerFeatures[mapDirect].popup);
        }

        $.getJSON(server+'/Building?id='+mapDirect , function(building) {
            //get lonlat of building to set center
            var lonlatBuilding= new OpenLayers.LonLat(building.location.longitude,building.location.latitude).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
            map.setCenter(lonlatBuilding);
        });
        //unset mapDirect
        mapDirect=undefined;
    }   
}

/**
 * Method to load the map
 *  
 * @param       position        The position of the device
 */
function loadMap(position) {  
    if(position===undefined) {
        myLon=3.7219830;
        myLat=51.0546200;
    }
    else {
        myLon=position.coords.longitude;
        myLat=position.coords.latitude;
    }
      
    /**
     * This is the mapbox tileset, this one is used. 
     */
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
                   
    /**
     * The openstreetmap tileset, this one is not used. 
     * If you want to use this tileset, changes the layers property of the map.
     */                                    
    var openStreetMapTiles = new OpenLayers.Layer.OSM("OpenStreetMap", null, { transitionEffect: "resize" });

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
            new OpenLayers.Control.Navigation({
                dragPanOptions: {
                    enableKinetic: true
                }
            }),
            new OpenLayers.Control.Zoom()
        ],
        layers: [mapBoxTiles]                   /* Change this in [openStreetMapTiles] to change the tileset to default */
    });
    
    lonlat = new OpenLayers.LonLat(myLon, myLat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));

    //set center of the map to user location, zoom level to 16
    map.setCenter(lonlat);
    map.zoomTo(16);
    
    //create layer for the user's location marker
    locationLayer = new OpenLayers.Layer.Markers('LocationLayer');
    locationLayer.id = 'LocationLayer';
    
    //create user location marker
    var size = new OpenLayers.Size(25,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
    var myLocationIcon = new OpenLayers.Icon('content/images/my-location.png', size, offset);
    myLocationMarker= new OpenLayers.Marker(lonlat,myLocationIcon);
    locationLayer.addMarker(myLocationMarker);    
    
    
    //Add listener to watch the user's position to update his position on the map
    //geo_success is called when a new position is retrieved
    //geo_error is called when an error occurs
    //{enableHighAccuracy:true, maximumAge:30000, timeout:27000}
    //      sets timeout & maximumAge to force refreshes
    //      enableHighAcurracy improves accuracy & is mandatory to work on android 2.x devices. 
    if(navigator.geolocation){
        wpid = navigator.geolocation.watchPosition(geo_success, geo_error,
             {enableHighAccuracy:true, maximumAge:30000, timeout:27000});
    }    
    
    //create building layer
    buildingLayer = new OpenLayers.Layer.Markers('BuildingLayer');
    buildingLayer.id = 'BuildingLayer';
    
    //create routing layer
    var ol = new OpenLayers.Layer.OSM(); 
    myRouteVector = new OpenLayers.Layer.Vector();
    
    //add layers to the map
    map.addLayers([ol,myRouteVector]);      
    map.addLayer(locationLayer);
    map.addLayer(buildingLayer);
    
    //Add markers for buildings
    $.getJSON(server+'/Building', function(buildings) {
        markerFeatures=new Array();
        $.each(buildings, function(key, building) {
            addMarker(buildingLayer, building.location.longitude, building.location.latitude, building.id, building.category.name);    
            latDestination=building.location.latitude;
            lonDestination=building.location.longitude;
        });
        showMapDirectPopup();                
    });   
}

/**
 * Adds marker to the specified layer.
 * 
 * @param {Object} layer        layer to add the marker to
 * @param {Object} lon          longitude of the marker
 * @param {Object} lat          latitude of the marker
 * @param {Object} id           id of the building
 * @param {Object} category     category NAME of the category
 */
function addMarker(layer, lon, lat, id, category) {
    var lonlat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));    
    var icon=new OpenLayers.Icon(getIcon(id,category),iconSize,iconOffset);         
    var feature = new OpenLayers.Feature(layer, lonlat); 
    feature.data.icon = icon;
    feature.data.overflow = 'auto';
    feature.id = id;      
    var marker = feature.createMarker();   
    markerFeatures[id] = feature;
    //register touchstart & click event
    //click is needed for PC
    //touch is needed for mobile devices
    marker.events.register('touchstart', feature, markerClick);  
    marker.events.register('click', feature, markerClick);     
    layer.addMarker(marker);
}

/**
 * Method that sets the correct icon of a building
 * These can be: unseen, seen & favorited.
 * Should be called after watching the movie of a building or after favoriting/unfavoriting a building.
 * 
 * @param {Object} buildingID       building ID of the building
 * @param {Object} category         category NAME of the category to which the building belongs to, it is the NAME
 *                                  because the name is used in the iconname.
 */
function getIcon(buildingID,category){    
    return 'content/images/markers/'+category.toLowerCase()+'.png';
}

/**
 * Event that is call when a marker is clicked, it shows the popup.
 * 
 */
var markerClick = function(evt) {
    var caller = this;
    
    if(caller.popup==null)
        fillPopup(caller);
    else 
        showPopup(caller.popup);
        
    OpenLayers.Event.stop(evt);
}

/**
 * Fills the popup of a specific feature (=~ marker)
 * 
 * @param {Object} feature
 */
function fillPopup(feature) {       
    //get building info JSON
    $.getJSON(server+'/Building?id=' + feature.id, function(building) {      
        var lonlat = new OpenLayers.LonLat(building.location.longitude, building.location.latitude).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));         
        var popup= new OpenLayers.Popup(feature.id,
                  lonlat,
                  null,
                  building.name,
                  true,
                  function(){closePopup()});
        popup.autoSize=true;
        popup.setBackgroundColor('#EBECE3');
        feature.popup=popup; 
         var linkHTML=(building.infoLink!=null && building.infoLink!='')?'<p class="moreInfo"><a href="' +building.infoLink +'"><img class="linkButton" src="content/images/more-info.png"/></a></p>':'';
        
        feature.popup.contentHTML='<h1 class="' + building.category.name.toLowerCase() + '">' + building.name + 
        '</h1><p class="description">' + building.description 
        +linkHTML +'<br\><br\><br\>'
        +'<p class="adres ' 
        + building.category.name.toLowerCase() + '">' + building.location.adress + '</p>';
        
        map.addPopup(feature.popup);
        //console.log(building.category.name.toLowerCase());
        showPopup(feature.popup);
        //array to store all popups in to, stores every popup in the array when they are created.
        markerFeatures[feature.id]=feature;              
    });        
}

/**
 * Show a popup or close it if it was already shown
 * Closes other opened popup
 * Shows or hides corresponding right side buttons.
 * Also saves the popup that is shown in the activePopup variable. This is used thoughout the application
 * to determine which building that is 'active'.
 */
function showPopup(popup){
    if(typeof activePopup == 'undefined') {
        popup.show();
    }
    else {
        if(activePopup.id==popup.id)
            activePopup.toggle();
        else{
            activePopup.hide();
            popup.show();
        }
    }

    activePopup=popup;
    
    map.panTo(popup.lonlat);
    
    if(typeof activePopup == 'undefined' || !activePopup.visible()) {
        $('div#mapButtons').hide();
    }
    else {
        $('div#mapButtons').show();
    }


}

/**
 * Closes the active popup & hides corresponding buttons on the right side of the map.
 */
function closePopup(){
    $('div#mapButtons').hide();
    
    if(typeof activePopup!='undefined'){
        activePopup.hide();
        activePopup=undefined;
    }
}

/**
 * Click event of the route button. Uses the location of the selected building to call
 * the routeTo method. 
 */
function routeToClick(){
    if(routeDrawnToLocation==activePopup.id){
        myRouteVector.destroyFeatures();
        routeDrawnToLocation=-1;
    }
    else $.getJSON(server+'/Building?id=' + activePopup.id, function(building) {  
        routeTo(building.location.longitude, building.location.latitude,building.id);         
        closePopup();
    });
}

function geo_success(position){
    
        myLon=position.coords.longitude;
        myLat=position.coords.latitude;  
        
        if(myLocationMarker!=undefined)
            locationLayer.removeMarker(myLocationMarker);

        lonlat = new OpenLayers.LonLat(myLon, myLat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
        var size = new OpenLayers.Size(25,25);
        var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
        var myLocationIcon = new OpenLayers.Icon('content/images/my-location.png', size, offset);
        myLocationMarker= new OpenLayers.Marker(lonlat,myLocationIcon);
        locationLayer.addMarker(myLocationMarker);
}


//called when watchposition throws an error
function geo_error(){       
     
}

/**
 * Gets the route from external navigation webservice and draws the route to the target location on the map.
 *  
 * @param       lon     longitue of the destination
 * @param       lat     latitude of the destination
 */
function routeTo (lon,lat,locationID) {
    //get route JSON
      var url = server+'/Map/Route?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&'+
        'flat='+myLat+'&'+
        'flon='+myLon+'&'+
        'tlat='+lat+'&'+
        'tlon='+lon+
        '&v=foot&fast=0&layer=mapnik';      
       
    //draw route   
    myRouteVector.destroyFeatures();   
    var routeStyle = { 
            strokeColor: '#0000ff', 
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
                routeDrawnToLocation=locationID;     
            }
         });
    });
}