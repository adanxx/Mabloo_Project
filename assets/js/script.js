
var timer;

$(function(){
    console.log("Script is Online");

    $(document).on("click", ".result", function(){
    
     var url    =  $(this).attr("href");
     var linkId =  $(this).attr("data-linkId");

     if(!linkId){
        alert("Error: linkId not found!!")
     }

     increaseClickNum(url, linkId);

     console.log(url, linkId);     
     
     return false;
    });

    var grid = $(".imageResults");

    grid.on("layoutComplete", function(){

        $(".gridItem img").css("visibility", "visible")
    });

    grid.masonry({
        // options
        itemSelector: '.gridItem',
        columnWidth: 200,
        gutter:5,
        isInitLayout: false
    });


    $("[data-fancybox]").fancybox({
        caption : function( instance, item ) {
            var caption = $(this).data('caption') || '';
            var siteUrl = $(this).data('siteurl') || '';
    
            if ( item.type === 'image' ) {
                caption = (caption.length ? caption + '<br />' : '') + 
                '<a href="' + item.src + '">View image</a><br>' +
                '<a href="' + siteUrl + '">View Page</a>';
            }
            return caption;
        },
        afterShow : function( instance, item ) {
            increaseImagesClicks( item.src);
        }

    });

    function increaseImagesClicks( url){

        $.post("ajax/imageAction.php",{src: url})
           .done(function(repsonse){
               
                if(repsonse !=1){
                    alert("Ajax_error: " +  repsonse)
                    return;
                }        
           })//end of callback
    }


    function increaseClickNum( url, linkId){

     $.post("ajax/clickAction.php",{id: linkId})
        .done(function(repsonse){
            
            if(repsonse !=1){
               alert("Ajax_error: " +  repsonse)
               return;
            }

            //redirect the page to url-link page
            window.location = url;
        })//end of callback
    }
})

function loadImage(src, className){
   
    var image = $("<img>"); //creating a img-tag


    image.on("load", function(){
      $("." + className + " a").append(image);

      clearTimeout(timer);
      
      timer = setTimeout(() => {
          $(".imageResults").masonry(); 
      }, 500);

     

    });

    image.on("error", function(){

      $("." + className).remove();

      $.post("ajax/brokenlinks.php", {src:src})
       .done(function(reponse){
            if(repsonse !=1){
                alert("Ajax_error: " +  repsonse)
                return;
            }
       })
    });

   image.attr("src", src); 
   
}