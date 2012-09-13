//declare variables
var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();
var page = 0;

//Link to the webservice
var server = 'http://tali.irail.be';

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
    //localStorage.clear();
    
    
        // Adding some localstorage dummy data
        /*var buildings = new Array();
        buildings[0] = new Building(2, 'Stadhuis');*/
        
        //localStorage['favorites'] = JSON.stringify(buildings);*/
        
        
        /*var lookLaters = new Array();
        lookLaters[0] = new Building(3, 'Belfort');
        localStorage['lookLater'] = JSON.stringify(lookLaters);*/
     
        var seen = new Array();
        seen[0] = new Building(2, 'Stadhuis');
        seen[1]=new Building(5,'Hotel Clemmen');
        localStorage['seen'] = JSON.stringify(seen);
		
    
    //Don't show information screen if application isn't opened for the first time
    if(localStorage['information'] == 'closed') {
        $('#informationOverlay').hide();   
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
        
        $('#informationOverlay').fadeOut('slow');    
    });
    
    /**
     * Clicking the question mark button on the home screen
     */
    $('#questionMark').click(function(event) {
        $('#informationOverlay').fadeIn('slow'); 
    });
    
    $('#routeToButton').click(function(event){
        routeToClick();
    });
    
    $('#mustSeeButton').click(function(event){
        mustSeeClick();
    });
    
    /**
     * Action fired when clicking the 'scan' button 
     */
    $('.scan').click(scanCode);  
    
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
    
    onDeviceReady();
});

/**
 * Posts the device id to the server when the device is ready.
 */
function onDeviceReady() {
    // If the device has no deviceUUID, generate a new deviceUUID
    if(localStorage['device'] == null) {
        saveGenerate(); 
    }
    else {
        deviceUUID=localStorage['device'];
    }
}

/**
 * This method safely generates a unique id. As long is the id exists, he will generate a new one.
 * Normally, a double generated id will not occur because it is based on the timestamp and a random number. 
 */
function saveGenerate() {
    var generated = generateId();
    
    $.get(server+'/Device?id=' + generated, function(data) {
        if(data.exists) {
            saveGenerate();
        }
        else {
            deviceUUID=generated;
                    
            localStorage['device']=deviceUUID;
            
            $.post(server + "/Device", {device: deviceUUID});    
        }
    });
}

/**
 * Generates a random deviceUUID
 * 
 * @return  string      a randomly generated deviceUUID 
 */
function generateId() {
    var time = new Date().getTime();
    
    return time + '' + parseInt(Math.random()*100000);
}

/**
 * Checks the network state of the phone.
 * 
 * @return  boolean     true if network is OK�, otherwhise false 
 */
function checkNetworkState(){
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
            var pattern = new RegExp(server + "\\?token=([a-zA-Z0-9]+)");
            
            if(!pattern.test(result.text)) {
                navigator.notification.alert('You scanned a QR-code that not belongs to Take A Look Inside.', function() { }, 'Wrong QR-code', 'Ok');
                
                return;       
            }
            
            var match = pattern.exec(result.text);
            
            $.getJSON(server + '/Movie/Size/' + match[1] + '?device=' + deviceUUID, function(data) { 
                navigator.notification.confirm('The video is ' + data.size + ' KB big. When do you want to see the video?', function(button) {
                    
                    var building = new Building(data.building.id, data.building.name, data.token);
                    
                    if(button==1) {                        
                        var array;
                        if(localStorage['lookLater'] == null) {
                            array = new Array();    
                        }
                        else {
                            array = JSON.parse(localStorage['lookLater']);
                        }
                        
                        array.remove(building);
                        array.push(building);
                        
                        localStorage['lookLater'] = JSON.stringify(array);
                        
                        navigator.notification.alert('The video is saved. You can find it under the Look Later section', function(evt) { }, 'Look Later', 'Ok');
                    }
                    else if(button==2) {
                        playMovie(building);
                    }
                    
                    initHomeContent(false);
                    
                }, data.building.name, 'Later,Now');
            });
        }

        // @seealso result.format, result.cancelled
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
        
        page = goToPage;
    }
}

/**
 * Initialize the content of the homepage
 * 
 * @param   loadMustSee     true if the mustsee content should also be reloaded 
 */
function initHomeContent(loadMustSee) {
    if(loadMustSee)
        fillHomeCategoryMustSee();
        
    fillCategory('favorites');
    fillCategory('lookLater');
    fillCategory('seen');
}

/**
 * Fill the home category. 
 */
function fillHomeCategoryMustSee() {
    $('#mustSee_content').empty();
    
    $.getJSON(server + '/Building/Top/3', function(buildings) {
        var list = $("<ol />"); 
        
        $.each(buildings, function(key, building) {
            var categorySpan = $('<span />').addClass('category').addClass(building.category.name.toLowerCase());
            var starSpan = $('<span />').addClass('star').append('<br />').append(building.mustSee);
            var buildingSpan = $('<span />').append(building.name).append('<br />');
            var adresSpan = $('<span />').addClass('adresSpan').append(building.location.adress);
            
            var middleSpan = $('<div />').addClass('middleDiv').append(buildingSpan).append(adresSpan);
            
            var li = $('<li />').attr('id', building.id).append(categorySpan).append(middleSpan).append(starSpan); 
            li.addClass('button');
            list.append(li); 
            
            li.click(function(event) {
                mapDirect = event.target.id;
                
                if(mapDirect == '') {
                    mapDirect = $(this).closest("li").attr('id');
                }
                
                window.location.href = "#map";
            });
        });
     
        list.append("</ol>");
         
        $('#mustSee_content').append(list);
    });
};

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
 * Fill a category at the home page
 * 
 * @param   name    The name of the category that should be filled 
 */
function fillCategory(name) {
    $('#' + name + '_content').empty();
        
    /**    
     * If the localstorage[name] exists, fill the home content 
     */
    if(localStorage[name] != null) {
        var list = $("<ul />");
   
        $.each(JSON.parse(localStorage[name]), function(key, building) {
            if(building!=null){
                var li = $('<li />').attr('id', building.id).html(building.name);
                li.addClass('button');
        
                li.click(function(event) {
                    if(name == 'favorites') {
                        mapDirect = event.target.id;
                    
                        window.location.href = "#map";    
                    }
                    else {
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
 * Play the movie with the phonegap plugin
 * 
 * @param   building    The building object of which the movie should be played 
 */
function playMovie(building) {    
    $.getJSON(server + '/Device?id='+deviceUUID,function(data) {
        if(data.exists==true) {
            var seenArray = getLocalStorageArray('seen');
            
            /**
             * If you see a movie that is in the look later array, remove it from the 'look later' local storage array. 
             */
            var lookLaterArray = getLocalStorageArray('lookLater');
            lookLaterArray.remove(building);
            
            localStorage['lookLater'] = JSON.stringify(lookLaterArray);
            
            /**
             * If the building is NOT in the 'seen' local storage, add him to the array.
             */
            if(!checkBuildingInArray(seenArray, building.id)) {
                seenArray.push(building);    
                
                localStorage['seen'] = JSON.stringify(seenArray);
            } 
            
            /**
             * The phonegap plugin is used to play the movie 
             */
            window.plugins.videoPlayer.play(server + '/Movie/Play/' + building.token + '?device=' + deviceUUID);
       
            /**
             * Reload the content of the homepage.
             * false    the mustsee should not be loaded 
             */
            initHomeContent(false);
       
            $.getJSON(server + '/Building?id='+building.id, function(building) {
                var catName = building.category.name;
                
                updateIcon(building.id, catName.toLowerCase());
                mapDirect = building.id;
                window.location.href = '#map'; 
            });
        }
        else {
            /**
             * Alertbox if the mobile device is not registered in the database
             */
            navigator.notification.alert("Video is only playable from a mobile device.", null, "Error", "Ok");;          
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