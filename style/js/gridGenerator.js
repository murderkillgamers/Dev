var windowWidth = 0;
$(document).ready(function(){
    generateGrid();
});

$(window).resize(function() {
    generateGrid();
});

function generateGrid(){
    var mobile = 740;
    var tablet = 841;
    
    windowWidth  = $(window).width();
    if(windowWidth < mobile){  
        //mobile
    }
    else if(windowWidth < tablet){
        //table
    }
    else
    {
        //desktop
    }
    var margin = 10;
    
    
}