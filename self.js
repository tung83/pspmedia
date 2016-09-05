/*price range*/
$(function(){
    $('.image-popup-no-margins').magnificPopup({
      type: 'image',
      closeOnContentClick: true,
      mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
      image: {
        verticalFit: true
      },
      zoom: {
        enabled: true,
        duration: 300 // don't foget to change the duration also in CSS
      }
    });
    $('.popup-gallery').magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
      },
      image: {
        verticalFit:true
      }
    });
})


var li_width=250;
var a=li_width*$(".product-menu-new>li").length;
$(".product-menu-new").width(a);
$(".product-menu-new").parent('div').css("left",-(a/2 - 110));
var maxHeight=0;    
$(".product-menu-new>li").each(function(){
    if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
})
var moveTop=-(maxHeight+20);
$(".product-menu-new>li").height(maxHeight);
$(".sub-menu-show div").height(maxHeight-10);
$(".sub-menu-show div").css("top",moveTop);
$(".sub-menu>ul>li>a").on("mouseover",function(){
    $(this).parent().parent().find(".current").removeClass("current");
    $(this).parent().toggleClass("current");
    $(".menu-img img").attr("src",$(this).attr("value"));
    $(".sub-menu-show").find("div.sub-menu-active").animate({top:moveTop},0,"",function(){
        $(this).removeClass("sub-menu-active");
    })
    $(".sub-menu-show").find("div#sub-menu-"+$(this).attr("data")).animate({top:10},500,"",function(){
        $(this).toggleClass("sub-menu-active");
    });
});

(function ($) {

    $('.bxslider').bxSlider();
			
	//$('.bxslider').bxSlider({
	//  mode: 'vertical',
	//  slideMargin: 5
	//});

	 

	
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollup').fadeIn();
			} else {
				$('.scrollup').fadeOut();
			}
		});
		$('.scrollup').click(function(){
			$("html, body").animate({ scrollTop: 0 }, 1000);
				return false;
		});
	
	
	// portfolio filter
	$(window).load(function(){'use strict';
		var $portfolio_selectors = $('.portfolio-filter >li>a');
		var $portfolio = $('.portfolio-items');
		$portfolio.isotope({
			itemSelector : '.portfolio-item',
			layoutMode : 'fitRows'
		});
		
		$portfolio_selectors.on('click', function(){
			$portfolio_selectors.removeClass('active');
			$(this).addClass('active');
			var selector = $(this).attr('data-filter');
			$portfolio.isotope({ filter: selector });
			return false;
		});
	});


	//Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});	


})(jQuery);

function dumpObj(obj, name='', indent='', depth=2) {

      if (depth > MAX_DUMP_DEPTH) {

             return indent + name + ": <Maximum Depth Reached>\n";

      }

      if (typeof obj == "object") {

             var child = null;

             var output = indent + name + "\n";

             indent += "\t";

             for (var item in obj)

             {

                   try {

                          child = obj[item];

                   } catch (e) {

                          child = "<Unable to Evaluate>";

                   }

                   if (typeof child == "object") {

                          output += dumpObj(child, item, indent, depth + 1);

                   } else {

                          output += indent + item + ": " + child + "\n";

                   }

             }

             return output;

      } else {

             return obj;

      }

}




