var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();
var page = 0;

var server = 'http://tali.irail.be/REST'

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
      */  
        var seen = new Array();
        seen[0] = new Building(2, 'Stadhuis');
        seen[1] = new Building(4, 'Sint-Baafskathedraal'); 
        localStorage['seen'] = JSON.stringify(seen);
    
    
   
    //localStorage['information'] = 'undefined';
   
    if(localStorage['information'] == 'closed') {
        $('#information').hide();   
    }
       
    $('.button').click(function(event) {
        // If user clicks on the span with the text, id of parent div will be retrieved
        if(event.target.id=='') {
            var parent = $(event.target).parent();
            
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
        
        $('#information').fadeOut('slow');    
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
        $('#filterSection').slideToggle('slow');    
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
            $.getJSON(result.text, function(data) {
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
                        
                        var array;
                        if(localStorage['seen'] == null) {
                            array = new Array(); 
                        }
                        else {
                            array = JSON.parse(localStorage['seen']);                            
                        }

                        array.push(building);
                        
                        localStorage['seen'] = JSON.stringify(array);
                        updateIcon(data.buildingID,data.categoryID);
                        updateRightSideButtons(data.buildingID);
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
            var li = $('<li />').attr('id', val.buildingID).html(val.name); 
            li.addClass('button');
            list.append(li); 
            
            li.click(function(event) {
                mapDirect = event.target.id;
                
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
    // http://tali.irail.be/REST/Movie/qrID/MjAxMjA3MTYxNTQ4LXRlc3QubXA0.gp3
    //MjAxMjA3MTYxNTQ4LXRlc3QubXA0
    //alert('http://tali.irail.be/REST/Movie/qrID/' + building.token + '.gp3');
    window.plugins.videoPlayer.play('http://tali.irail.be/REST/Movie/qrID/' + building.token + '.gp3');  
}