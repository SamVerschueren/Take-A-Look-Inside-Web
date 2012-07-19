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

//Add deviceready event listetener from Phonegap
document.addEventListener("deviceready", onDeviceReady, false);

/**
 * JQuery $(document).ready() method
 * The short way! 
 */
$(function() {
    windowWidth = $(window).width();
    
    initCarrousel();
    
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
    
    //Don't show information screen if application isn't opened for the first time
    if(localStorage['information'] == 'closed') {
        $('#information, #triangle').hide();   
    }
    
    //Eventhandler for clicks in homescreen.
    $('.button').click(function(event) {
        // If user clicks on the span with the text, id of parent div will be retrieved
        if(event.target.id=='') {
            var parent = $(event.target).parent();
            Belfor
            var id = parent.attr('id');
        }
        else {
            var id = event.target.id;
        }
        //set Active page icon
        $(".smallIcon").removeClass("active");
        $("#" + id + ".smallIcon").addClass('active');
        
        page = $(this).attr('data-page');
        
        //Go to home page
        if($(this).attr('data-from') == 'home') {
            $('#carrousel').css('margin-left', '-' + page*windowWidth + 'px'); 
            
            window.location.href = "#home_category";  
        }        
        else {
            //Go to other page
            changeContent(page);
        }
    });
    
    /**
     * Clicking the 'X' (close) button on the information screen.
     * It sets a value in the localstorage.
     */
    $('#closeInformation').click(function(event) {
        localStorage['information'] = 'closed';
        
        $('#information, #triangle').fadeOut('slow');    
    });
    
    /**
     * Clicking the question mark button on the home screen
     */
    $('#questionMark').click(function(event) {
        $('#information').fadeIn('slow'); 
    });
    
    $('#routeToButton').click(function(event){
        routeToClick();
    })
    
    $('#mustSeeButton').click(function(event){
        mustSeeClick();
    })
    
    /**
     * Action fired when clicking the 'scan' button 
     */
    $('.scan').click(scanCode);  
    
    $('div#fireFilterSection').click(function(event) {
        $('div#filter').slideToggle('slow'); 
		$('#legendarrow').addClass('rotate');   
    });
    
    /**
     * Swipe with your finger from right to left 
     */
    $('#home_category').live('swipeleft', function(event) {
        changeContent(parseInt(page)+1);
    });
    
    /**
     * Swipe with your finger from left to right
     */
    $('#home_category').live('swiperight', function(event) {
        changeContent(parseInt(page)-1);
    });
    
    /**
     * Fill the different categories with content 
     */
    initHomeContent(true);
});

/**
 * Phonegap onDeviceRead()
 */
function onDeviceReady() {
    deviceUUID=device.uuid;
    alert(checkNetworkState());
    $.post(server + "/Device", {device: deviceUUID}, function (data){
        // do nothing  
    });      
}
/**
 * Checks if the device is connected to the internet
 * @return      boolean     true if connected
 */
function isConnected(){
   return networkState ==
        NetworkStatus.REACHABLE_VIA_CARRIER_DATA_NETWORK ||
        NetworkStatus.REACHABLE_VIA_WIFI_NETWORK;
}


/**
 * Handles the resizing of the window. 
 */
$(window).resize(function() {
    windowWidth = $(window).width();
    
    initCarrousel();
});

/**
 * Initializes the carrousel. Just scales the width of the window. 
 */
function initCarrousel() {    
    $('#carrousel .page').each(function(){
        $(this).css({ width: windowWidth});
    }); 
}

/**
 * Method that calls the barcodescanner plugin of phonegap. 
 */
var scanCode = function() {
    window.plugins.barcodeScanner.scan(function(result) {        
        if(result.text != '') {
            var url = result.text.split('#');
            //check if QR-code is a TALI QR-code
            if(url[0] != siteUrl) {
                navigator.notification.alert('You scanned a QR-code that not belongs to Take A Look Inside.', function() { }, 'Wrong QR-code', 'Ok');
                
                return;       
            }
            //get movie
            $.getJSON(server + '/Movie/qrID/' + url[1] + '.json?device=' + deviceUUID, function(data) {                
                navigator.notification.confirm('The video is ' + $.trim(data.size) + ' KB big. When do you want to see the video?', function(button) {
                    //save building in var
                    var building = new Building(data.buildingID, data.buildingName, data.token);
                    
                    //look later
                    if(button==1) {
                        //load looklater array from localstorage
                        var array;
                        if(localStorage['lookLater'] == null) {
                            array = new Array();    
                        }
                        else {
                            array = JSON.parse(localStorage['lookLater']);
                        }
                        
                        //push current bulding to looklater array
                        array.push(building);
                        
                        //store looklater array in localstorage
                        localStorage['lookLater'] = JSON.stringify(array);
                        
                        navigator.notification.alert('The video is saved. You can find it under the Look Later section', function(evt) { }, 'Look Later', 'Ok')
                    }
                    //look now
                    else if(button==2) {
                        playMovie(building);
                    }
                    
                    //update looklater screen
                    initHomeContent(false);
                    
                }, data.buildingName, 'Later,Now');
            });
        }

    }, function(error) {
        alert("Could not scan the code. Please try again.");
    });
}

/**
 * Change the content of the page.
 * 
 * @param {Object} goToPage The pagenumber to animate to.
 */
function changeContent(goToPage) {
    if(goToPage >= 0 && goToPage < $('.smallIcon').length) {        
        $('.smallIcon').removeClass('active');
        $('.smallIcon[data-page=' + goToPage + ']').addClass('active');
        
        $('#carrousel').animate({ 'marginLeft' : -1*goToPage*windowWidth });
        //change content of homepage
        page = goToPage;
    }
}

/**
 * Set up home page with different categories
 * 
 * @param   boolean     loadMustSee     Determines if the must sees should be loaded again. If it doesn't need to update,
 * then it improves the performance by not reloading it.
 * 
 */
function initHomeContent(loadMustSee) {
    if(loadMustSee)
        fillHomeCategoryMustSee();
        
    fillCategory('favorites');
    fillCategory('lookLater');
    fillCategory('seen');
}

/**
 * Fill must sees in the home screen
 * Must sees are loaded from the webservice so a different implementation than the fillCategory() should be used.
 * 
 */
function fillHomeCategoryMustSee() {
    //Clear current content
    $('#mustSee_content').empty();
    
    //Get JSON of top favorited location from webservice
    $.getJSON('http://tali.irail.be/REST/Building.json?top=3', function(data) {
        var list = $("<ol />"); 
        //add html elements for each location
        $.each(data.building, function(key, val) {
            var categorySpan = $('<span />').addClass('category').addClass(val.catName.toLowerCase());
            var starSpan = $('<span />').addClass('star').append('<br />').append(val.mustSee);
            var buildingSpan = $('<span />').append(val.name).append('<br />');
            var adresSpan = $('<span />').addClass('adresSpan').append(val.adres);
            
            var middleSpan = $('<div />').addClass('middleDiv').append(buildingSpan).append(adresSpan);
            
            var li = $('<li />').attr('id', val.buildingID).append(categorySpan).append(middleSpan).append(starSpan); 
            li.addClass('button');
            list.append(li); 
            //make html buttons clickabl and link to the map
            li.click(function(event) {
                //configure mapDirect to the id of the selected location. By doing this,
                //the map will show the popup of and set center to this building when it is shown.
                mapDirect = event.target.id;
                
                if(mapDirect == '') {
                    mapDirect = $(this).closest("li").attr('id');
                }
                //move to Map page
                window.location.href = "#map";
            });
        });     
        list.append("</ol>");
        
        //add content to Must See page
        $('#mustSee_content').append(list);
    });
};

/**
 * Function which checks if a specific building is in an array. 
 * 
 * @param   boolean     result      returns if building is present in the array. 
 */
function checkBuildingInArray(array, buildingID){
    var result=false;
    //loop through all buildings in the array
    $.each(array, function(key, building) {
        if(building!=null)       
            if(building.id==buildingID)
                //found building
                result=true;        
    });
    
    return result;
}

/**
 * Fills the home categories 'seen', 'looklater' and 'favorites'
 * All these categories can be filled from the localstorage and use the same implementation.
 * 
 */
function fillCategory(name) {
    //clear current content
    $('#' + name + '_content').empty();
    
    //check if data about the category is present    
    if(localStorage[name] != null) {
        var list = $("<ul />");
        //loop thourgh array and fill list with locations
        $.each(JSON.parse(localStorage[name]), function(key, building) {
            if(building!=null){
                var li = $('<li />').attr('id', building.id).html(building.name);
                li.addClass('button');
                //add click event to buttons
                li.click(function(event) {
                    //if in the favorites manu
                    if(name == 'favorites') {
                        //set mapdirect to show corresponding popup when redirected to map
                        mapDirect = event.target.id;
                        //redirect to the map
                        window.location.href = "#map";    
                    }
                    //else --> if in the seen or look later menu
                    else { 
                        //replay movie
                        playMovie(building);
                    }
                });
                
                list.append(li);
            }
        });
        
        $('#' + name + '_content').html(list);
    }
}

/**
 * Plays the movie using a native videoplayer.
 * 
 */
function playMovie(building) {    
    $.getJSON(server + '/Device.json?device='+deviceUUID,function(data) {
        /**
         * Check if device exists in database
         * This is a security concern:
         * 
         * Users should not be able to watch the movie from outside our application
         * In order to achieve that, we store a deviceID in our database when our application is launche for the
         * first time on a specific device.
         * 
         * This JSON returns an array with the information whether the device is in the database or not.
         * 
         * If the device is not in the database, this means that the url is not requested from our device, 
         * which means it should not be playable.
         */
        if(data.exists==true) {
            //check if the video was added to the looklater
            var seenArray = getLocalStorageArray('seen');
            
            var lookLaterArray = getLocalStorageArray('lookLater');
            //remove video from looklater array
            lookLaterArray.remove(building);
            
            //store new looklater array in localstorage
            localStorage['lookLater'] = JSON.stringify(lookLaterArray);
            
            //check if user already saw current movie
            if(!checkBuildingInArray(seenArray, building.id)) {
                //if not, add it to the seen array
                seenArray.push(building);                    
                localStorage['seen'] = JSON.stringify(seenArray);
            } 
            
            //play the movie, using native movieplayer
            window.plugins.videoPlayer.play('http://tali.irail.be/REST/Movie/qrID/' + building.token + '.gp3?device=' + deviceUUID);
       
            //update home categories except for must sees (these didnt change anyway)    
            initHomeContent(false);

            //get JSON in order to get category ID of the current building in order to be able to 
            //update the icon properly (from unseen to seen).
            $.getJSON('http://tali.irail.be/REST/building.json?select=category.name as catName&join=category&buildingID='+building.id, function(data){
                var catName = data.building[0].catName;
                //update the icon
                updateIcon(building.id, catName.toLowerCase());
                mapDirect = building.id;
                window.location.href = '#map'; 
            });
        }
        //video not asked from a device stored in the DB: something went wrong, people are trying to get the movie
        //in ways we would not like them to get it.
        else{
            navigator.notification.alert("Video is only playable from a mobile device.", null, "Device not registered", "OK");;          
        }
    });  
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