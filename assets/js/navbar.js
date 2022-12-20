let navBarOpened = false;

function openNav()
{
    document.getElementById("sidebar").style.width = "250px";
    navBarOpened = true;
}

function closeNav() 
{
    document.getElementById("sidebar").style.width = "0";
    navBarOpened = false;
}

$(document).ready(function(){
    /*$('body').mousemove(function( event ) {
        // Width == X
        // Height == Y
        if((event.pageX/16) <= 2 && (event.pageY/16) <= ($('body').outerHeight()/16)-10 && navBarOpened === false){
            openNav();
        }else if((event.pageX/16) > 16 && navBarOpened === true){
            closeNav();
        }
    });*/
});
