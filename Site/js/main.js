var homeCategory = "";

$(function() {
   $('.button').click(function(event) {
       var id = event.target.id; 
       
       homeCategory = id.toLowerCase();
       
       $(".smallIcon").removeClass("active");
       $("#" + id + ".smallIcon").addClass('active');
       
       window.location.href = "#home_category"; 
       
       changeContent();
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
            var li = $('<li />').attr('id', val.buildingID).html('<a href=map.html/buildingID='+val.buildingID+'>'+val.name+'</a>'); 
            li.attr('href',"map.html"); 
            list.append(li); 
        });
     
        list.append("</ol>");
         
        $('h1#home_category_head').append('Must See'); 
        $('p#home_category_content').append(list);
    });
};