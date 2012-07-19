var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();
var page = 0;

var siteUrl = 'http://tali.irail.be';
var server = 'http://tali.irail.be/REST'

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

document.addEventListener("deviceready", onDeviceReady, false);

/**
 * JQuery $(document).ready() method
 * The short way! 
 */
$(function() {
    windowWidth = $(window).width();
    
    initCarrousel();
    

    //localStorage.clear();
    
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
    
    if(localStorage['information'] == 'closed') {
        $('#information, #triangle').hide();   
    }
       
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
        
        $(".smallIcon").removeClass("active");
        $("#" + id + ".smallIcon").addClass('active');
        
        page = $(this).attr('data-page');
        
        if($(this).attr('data-from') == 'home') {
            $('#carrousel').css('margin-left', '-' + page*windowWidth + 'px'); 
            
            window.location.href = "#home_category";  
        }        
        else {
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

function onDeviceReady() {
    deviceUUID=device.uuid;
    
    $.post(server + "/Device", {device: deviceUUID}, function (data){
        // doe niets   
    });      
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
            
            if(url[0] != siteUrl) {
                navigator.notification.alert('You scanned a QR-code that not belongs to Take A Look Inside.', function() { }, 'Wrong QR-code', 'Ok');
                
                return;       
            }
            
            $.getJSON(server + '/Movie/qrID/' + url[1] + '.json?device=' + deviceUUID, function(data) {                
                navigator.notification.confirm('The video is ' + $.trim(data.size) + ' KB big. When do you want to see the video?', function(button) {
                    
                    var building = new Building(data.buildingID, data.buildingName, data.token);
                    
                    if(button==1) {
                        var array;
                        if(localStorage['lookLater'] == null) {
                            array = new Array();    
                        }
                        else {
                            array = JSON.parse(localStorage['lookLater']);
                        }
                        
                        array.push(building);
                        
                        localStorage['lookLater'] = JSON.stringify(array);
                        
                        navigator.notification.alert('The video is saved. You can find it under the Look Later section', function(evt) { }, 'Look Later', 'Ok')
                    }
                    else if(button==2) {
                        playMovie(building);
                    }
                    
                    initHomeContent(false);
                    
                }, data.buildingName, 'Later,Now');
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

function initHomeContent(loadMustSee) {
    if(loadMustSee)
        fillHomeCategoryMustSee();
        
    fillCategory('favorites');
    fillCategory('lookLater');
    fillCategory('seen');
}

function fillHomeCategoryMustSee() {
    $('#mustSee_content').empty();
    
    $.getJSON('http://tali.irail.be/REST/Building.json?top=3', function(data) {
        var list = $("<ol />"); 
        
        $.each(data.building, function(key, val) {
            var categorySpan = $('<span />').addClass('category').addClass(val.catName.toLowerCase());
            var starSpan = $('<span />').addClass('star').append('<br />').append(val.mustSee);
            var buildingSpan = $('<span />').append(val.name).append('<br />');
            var adresSpan = $('<span />').addClass('adresSpan').append(val.adres);
            
            var middleSpan = $('<div />').addClass('middleDiv').append(buildingSpan).append(adresSpan);
            
            var li = $('<li />').attr('id', val.buildingID).append(categorySpan).append(middleSpan).append(starSpan); 
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

function checkBuildingInArray(array, buildingID){
    var result=false;
    
    $.each(array, function(key, building) {
        if(building!=null)       
            if(building.id==buildingID)
               result=true;        
    });
    return result;
}

function fillCategory(name) {
    $('#' + name + '_content').empty();
        
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

function playMovie(building) {    
    $.getJSON(server + '/Device.json?device='+deviceUUID,function(data) {
        if(data.exists==true) {
            var seenArray = getLocalStorageArray('seen');
            
            var lookLaterArray = getLocalStorageArray('lookLater');
            lookLaterArray.remove(building);
            
            localStorage['lookLater'] = JSON.stringify(lookLaterArray);
            
            if(!checkBuildingInArray(seenArray, building.id)) {
                seenArray.push(building);    
                
                localStorage['seen'] = JSON.stringify(seenArray);
            } 
            
            window.plugins.videoPlayer.play('http://tali.irail.be/REST/Movie/qrID/' + building.token + '.gp3?device=' + deviceUUID);
       
            initHomeContent(false);
            $.getJSON('http://tali.irail.be/REST/building.json?select=category.name&join=category&buildingID='+building.id, function(data){
                updateIcon(building.id,data.building[0].category)
                mapDirect = building.id;
                window.location.href = '#map'; 
            });

        }
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