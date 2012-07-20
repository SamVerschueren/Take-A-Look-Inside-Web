//declare variables
var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();
var page = 0;

//Link to the site
var siteUrl = 'http://tali.irail.be';

//Link to the restfull webservice
var server = 'http://tali.irail.be/REST'

/**
 * Extension to arrays:
 * now possible to use syntax like: some_array.remove(building);
 */
Array.prototype.remove = function (building) {
    for (var i = 0; i < this.length; ) {
        if (this[i].id === building.id) {
            this.splice(i, 1);
        } 
        else {
           ++i;
        }
    }
}

/**
 * JQuery $(document).ready() method
 * The short way! 
 */
$(function() {
    windowWidth = $(window).width();
    

    //Testing purposes:
   // localStorage.clear();
    
    /*
        // Adding some localstorage dummy data
        var buildings = new Array();
        buildings[0] = new Building(2, 'Stadhuis');
        
        localStorage['favorites'] = JSON.stringify(buildings);
        
        
        var lookLaters = new Array();
        lookLaters[0] = new Building(3, 'Belfort');
        localStorage['lookLater'] = JSON.stringify(lookLaters);
     
        var seen = new Array();
        seen[0] = new Building(2, 'Stadhuis');
        seen[1] = new Building(4, 'Sint-Baafskathedraal'); 
        localStorage['seen'] = JSON.stringify(seen);
        */
    

    
    /**
     * Clicking the 'X' (close) button on the information screen.
     * It sets a value in the localstorage.
     */
    /*$('#closeInformation').click(function(event) {
        localStorage['information'] = 'closed';
        
        $('#information, #triangle').fadeOut('slow');    
    });*/
    

    
    /**
     * Action fired when clicking the 'scan' button 
     */
    //$('.scan').click(scanCode);  
    
    $('#routeToButton').click(function(event){
        routeToClick();
    })
    
    $('div#fireFilterSection').click(function(event) {
        
        $('div#filter').slideToggle('slow', function() {
            if($('div#filter').is(':visible')) {
                $('#legendarrow').addClass('rotate');   
            }
            else {
                $('#legendarrow').removeClass('rotate');   
            }
        }); 
    });    

});

/**
 * Handles the resizing of the window. 
 */
$(window).resize(function() {
    windowWidth = $(window).width();
});


/**
 * Check's if the building is in the specified array
 * 
 * @param   array       The array that should be iterated.
 * @param   buildingID  The id of the building that should be checked.
 */
function checkBuildingInArray(array, buildingID){
    var result=false;
    
    $.each(array, function(key, building) {
        if(building!=null)       
            if(building.id==buildingID)
               result=true;        
    });
    return result;
}

/**
 * Get the localStorage array
 *  
 * @param   string  The key of the array
 */
function getLocalStorageArray(string) {
    var array;
    
    /**
     * If the localStorage array does not exists, create a new array.
     * If the localStorage array exists, parse the localStorage json string to an array 
     */
    if(localStorage[string] == null) {
        array = new Array();
    }
    else {
        array = JSON.parse(localStorage[string]);
    }
    
    return array;
}