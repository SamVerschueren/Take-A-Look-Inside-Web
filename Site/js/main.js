var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();

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
        buildings[0] = 2;
        localStorage['favorites'] = JSON.stringify(buildings);
       
        var lookLaters = new Array();
        lookLaters[0] = 3;
        localStorage['lookLater'] = JSON.stringify(lookLaters);
        
        var seen = new Array();
        seen[0] = 2;
        seen[1] = 4; 
        localStorage['seen'] = JSON.stringify(seen);
    */
       
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
        
        var page = $(this).attr('data-page');
        
        if($(this).attr('data-from') == 'home') {
            $('#carrousel').css('margin-left', '-' + page*windowWidth + 'px'); 
            
            window.location.href = "#home_category";  
        }        
        else {
            changeContent($(this).attr('data-page'));
        }
    });
    
    $(".scan").click(scanCode);  
    
    $('div#fireFilterSection').click(function(event) {
        $('#filterSection').slideToggle('slow');    
    });
    
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
        alert("Scanned Code: " + result.text);
        
        // @seealso result.format, result.cancelled
    }, function(error) {
        alert("Scan failed: " + error);
    });
}

/**
 * Change the content of the page.
 * 
 * @param {Object} goToPage The pagenumber to animate to.
 */
var changeContent = function(goToPage) {
    // The position of the current page
    var page = (-1*parseInt($('.page').css('margin-left'), 10))/windowWidth;       
    var animateWidth = (page-goToPage)*windowWidth;
    
    $('#carrousel').animate({ 'marginLeft' : animateWidth });
}

function fillHomeCategoryMustSee() { 
    $.getJSON('/REST/Building.json?orderby=mustSee&select=name;buildingID', function(data) {
        var list = $("<ol />"); 
        
        $.each(data.Building, function(key, val) {
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
    fill('favorites');
}

function fillHomeCategoryLookLater() {
    fill('lookLater');
}

function fillHomeCategorySeen() {
    fill('seen');
}

function fill(name) {
    var list = $("<ul />");
    
    $.each(JSON.parse(localStorage[name]), function(key, val) {
        $.getJSON('/REST/Building/buildingID/' + val +'.json?select=name;buildingID', function(data) {            
            var li = $('<li />').attr('id', data.Building[0].buildingID).html(data.Building[0].name); 
            li.addClass('button');
            list.append(li); 
            
            li.click(function(event) {
                mapDirect = event.target.id;
                
                window.location.href = "#map";
            });
            
            list.append(li);
        });
    });
    
    $('#' + name + '_content').html(list);
}
