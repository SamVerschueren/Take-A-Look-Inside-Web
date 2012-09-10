var myLat;
var myLon; 
var buildingLayer;
var markerFeatures;
var activePopup;
var iconSize = new OpenLayers.Size(25,41);
var iconOffset = new OpenLayers.Pixel(-(iconSize.w/2), -iconSize.h);

var server = "http://localhost/tali";

$(function() {
    if($("div#map").length > 0) {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(loadMap, function() {
                alert("Could not load your location. Your location is set to the Korenmarkt in Ghent.");
            });    
        }
        
        loadFilter();
        
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
            var li = $("<li />").attr('class', category.name.toLowerCase()).append(checkbox).append(' ').append(label); 
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
            //if it is checked, display it
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
    if(mapDirect === undefined || markerFeatures[mapDirect] === 'undefined') {
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
            new OpenLayers.Control.Zoom()
        ],
        layers: [mapBoxTiles]                   /* Change this in [openStreetMapTiles] to change the tileset to default */
    });
    
    if(navigator.geolocation){
        //set center to the users geolocation
        myLon=position.coords.longitude;
        myLat=position.coords.latitude;
    }
    else{
        //set center to korenmarkt coordinates
        myLon=3.7219830;
        myLat=51.0546200;
    }
    
    lonlat = new OpenLayers.LonLat(myLon, myLat).transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
    
    map.setCenter(lonlat);
    map.zoomTo(16);
    
    var locationLayer = new OpenLayers.Layer.Markers('LocationLayer');
    locationLayer.id = 'LocationLayer';
    
    buildingLayer = new OpenLayers.Layer.Markers('BuildingLayer');
    buildingLayer.id = 'BuildingLayer';
    
    var ol = new OpenLayers.Layer.OSM(); 
    myRouteVector = new OpenLayers.Layer.Vector();
    map.addLayers([ol,myRouteVector]);   
    
    map.addLayer(locationLayer);
    map.addLayer(buildingLayer);
    
    // Adding the markers to the layer
    var size = new OpenLayers.Size(25,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
    var icon = new OpenLayers.Icon('content/images/my-location.png', size, offset);
    locationLayer.addMarker(new OpenLayers.Marker(lonlat,icon));
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
        feature.popup.contentHTML='<h1 class="' + building.category.name.toLowerCase() + '">' + building.name + '</h1><p class="description">' + building.description + '<br /><br /><br /></p><p class="adres ' + building.category.name.toLowerCase() + '">' + building.location.adress + '</p>';
        
        map.addPopup(feature.popup);
        
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
    if(activePopup === undefined) {
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
    
    if(activePopup === undefined || !activePopup.visible()) {
        $('div#mapButtons').hide();
    }
    else {
        $('div#mapButtons').show();
    }    
}