var homeCategory = "";
var mapLoaded = false;
var markerArray = new Array();

$(function() {    
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
       
        changeContent();
    });
    
    $('div#fireFilterSection').click(function(event) {
        $('#filterSection').slideToggle('slow');    
    });
});


var changeContent = function() {
    $('h1#home_category_head').html("");
    $('p#home_category_content').html(""); 
    
    if(homeCategory=='mustsee') {
        fillHomeCategoryMustSee();
    } 
    else if(homeCategory=='looklater') {
        
    }
    else if(homeCategory=='seen') {
        
    }
    else if(homeCategory=='favorites') {
        
    }
}

function fillHomeCategoryMustSee() { 
    $('home_category_text').text("Must See"); 
    
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
         
        $('h1#home_category_head').append('Must See'); 
        $('p#home_category_content').append(list);
    });
};