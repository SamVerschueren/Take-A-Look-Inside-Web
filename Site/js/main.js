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
    
   // localStorage.setItem("name", "Hello World!");
    
    // alert(localStorage.getItem("name"));
       
    $('.button').click(function(event) {
        // If user clicks on the span with the text, id of parent div will be retrieved
        if(event.target.id=='') {
            var parent = $(event.target).parent();
            
            var id = parent.attr('id');
        }
        else {
            var id = event.target.id;
        }
        
        homeCategory = id.toLowerCase();
       
        $(".smallIcon").removeClass("active");
        $("#" + id + ".smallIcon").addClass('active');
       
        window.location.href = "#home_category"; 
       
        changeContent($(this).attr('data-page'));
    });
    
    $(".scan").click(scanCode);  
    
    $('div#fireFilterSection').click(function(event) {
        $('#filterSection').slideToggle('slow');    
    });
    
    fillHomeCategoryMustSee();
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
    $('h1#home_category_head').html('Favorites');
}

function fillHomeCategoryLookLater() {
    $('h1#home_category_head').html('Look Later');
}

function fillHomeCategorySeen() {
    $('h1#home_category_head').html('Seen');
}
