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
    fillHomeCategoryMustSee();
    fillHomeCategoryFavorites();
    fillHomeCategoryLookLater();
    fillHomeCategorySeen();
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
            $.get(result.text, function(data) {
                navigator.notification.confirm('The video is ' + $.trim(data.size) + ' KB big. When do you want to see the video?', function(button) {
                    if(button==1) {
                        $.post(server + '/Seen', function(data) {
                            alert(data);    
                        });
                        
                        alert('Video is saved under the Look Later section.');
                    }
                    else if(button==2) {
                        window.plugins.videoPlayer.play('http://tali.irail.be/mov/kerstballen.3gp');
                    }
                }, 'Confirm', 'Later,Now');
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

function fillHomeCategoryMustSee() {
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
function fillHomeCategoryFavorites() {
    fillLocal('favorites');
}

function fillHomeCategoryLookLater() {
    fillLocal('lookLater');
}

function fillHomeCategorySeen() {
    fillLocal('seen');
}

function fillLocal(name) {
    var list = $("<ul />");
        
    $.each(JSON.parse(localStorage[name]), function(key, building) {
        var li = $('<li />').attr('id', building.id).html(building.name);
        li.addClass('button');

        li.click(function(event) {
            mapDirect = event.target.id;
            
            window.location.href = "#map";
        });
        
        list.append(li);
    });
    
    $('#' + name + '_content').html(list);
}
