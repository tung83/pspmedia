<?php include 'function.php';?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?=pageHeader($db,$view,$lang)?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta content="utf-8" http-equiv="encoding"/>
<meta name="language" content="vi" />
<link rel="icon" href="/logo.ico" type="image/x-icon"/>
<link rel="stylesheet" href="<?=myWeb?>css/bootstrap.min.css"/>
<link rel="stylesheet" href="<?=myWeb?>css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="<?=myWeb?>css/component.css" />
<link rel="stylesheet" href="<?=myWeb?>css/magnific-popup.css"/>
<link rel="stylesheet" href="<?=myWeb?>css/bxslider.css"/>
<link rel="stylesheet" type="text/css" href="<?=myWeb?>css/isotope.css" media="screen" />
<link rel="stylesheet" href="<?=myWeb?>js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=myWeb?>css/prettyPhoto.css"/>
<link rel="stylesheet" href="<?=myWeb?>css/slick.css"/>
<link rel="stylesheet" href="<?=myWeb?>css/slick-theme.css"/>
<link rel="stylesheet" href="<?=myWeb?>self.css" />
<!-- insert less -->
<!--link rel="stylesheet/less" type="text/css" href="<?=myWeb?>styles.less"/>
<script src="<?=myWeb?>js/less.js"></script-->
<script src="<?=myWeb?>js/modernizr.custom.js"></script>
<script src="<?=myWeb?>js/jquery-2.1.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?=myWeb?>js/slick.js"></script>
<script src="<?=myWeb?>js/jquery.magnific-popup.js"></script>
<script type="text/javascript" src="<?=myWeb?>js/jquery.mapit.js"></script>
</head>
<body>
<form action="<?=myWeb.$lang?>/search.html" method="post" name="frm_search_all">
<div class="content_search">
        <span style="width:200px">
            <input type="text" class="form-control" id="hint" placeholder="Search" name="hint" onchange="document.frm_search_all.submit()">
        </span>
        <span>
            <a href="" class="submib_search">
                <img src="<?=selfPath?>search_box.png" />
            </a>
        </span>
    </div>
</form>
<script>
    function search(prefix){
       var frm=document.frm_search_all;
       if(frm.hint.value==''){
            frm.hint.focus();
            return;
       } else {
            var lnk=prefix+'/'+frm.hint.value+'/search.html';
            location.href=lnk;
       }
    }  
</script>
<div id="fb-root"></div>

<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&appId=297645530337906";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?=new_menu($db,$lang,$view);?>
<?=menu($db,$lang,$view);?>
 <?php
 function new_slide($db, $lang)
{
    $db->orderBy('ind', 'asc');
    $db->orderBy('id', 'asc');
    $db->where('active', 1);
    $list = $db->get('slider');
    $temp = '';
    $tmp = '';
    $i=1;
    foreach ($list as $item) {
        if ($lang == 'vi') {
            $title = $item['title'];
            $lnk = $item['lnk'];
        } else {
            $title = $item['e_title'];
            $lnk = $item['e_lnk'];
        }
        $temp .= '
        <li>
            <a href="' . $lnk . '"  target="_blank">
                <img src="'.webPath.$item['img'].'" alt="mekogas.com.vn" title="'.$title.'" />
            </a>
        </li>';
        $tmp.='<a href="#" title=""><span>'.$i.'</span></a>';
        $i++;
    }
    $str = '
    <!-- Start WOWSlider.com HEAD section -->
    <link rel="stylesheet" type="text/css" href="'.myWeb.'engine1/style.css" />
   
    <!-- Start WOWSlider.com BODY section -->
    <div id="wowslider-container1">
    <div class="ws_images"><ul>
    		'.$temp.'
    	</ul></div>
    	<div class="ws_bullets"><div>
    		'.$tmp.'
    	</div></div>
        <div class="ws_script" style="position:absolute;left:-99%"></div>
    <div class="ws_shadow"></div>
    </div>	
    <script type="text/javascript" src="'.myWeb.'engine1/wowslider.js"></script>
    <script type="text/javascript" src="'.myWeb.'engine1/script.js"></script>
    <!-- End WOWSlider.com BODY section -->
    ';
    return $str;
}
echo new_slide($db,$lang);
 ?>






<div class="main-feature  bk_footer1"></div>
<footer>
          <div class="footer">
        <div class="container">
            <?=footer_article($db,$lang)?>           
        </div>
    </div>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6" style="font-size: 12px;">
                    &copy; 2016 <a title="Mekong Group" href="#" target="_blank">Mekong Group</a>. All Rights Reserved.
                                         <span style="margin-left: 40px; padding-right: 5px; border-right: 1px solid rgb(173, 173, 173);">
                      Đang online:1</span>

          <span style="padding-left: 7px;">Tổng truy cập: 633</span></div>

                    <div class="col-md-6">

                    <ul class="pull-right">
                        <li><a href="http://demo2.emsvn.net/mekogas/" target="_blank"><img src='<?=selfPath?>icon-fb.png' title='Icon facebook'/></a></li>

                        <li><a href="" target="_blank"><img src='<?=selfPath?>icon-rss.png' title='twite'/></a></li>

                        <li><a href="" target="_blank"><img src='<?=selfPath?>icon-like.png' title='twite'/></a></li>

                        <li><a href="" target="_blank"><img src='<?=selfPath?>icon-g.png' title='twite'/></a></li>

                        <li><a href="" target="_blank"><img src="<?=selfPath?>icon-pinterest.png" title='twite'/></a></li>



                    </ul>
                    </div>
                </div>
            <div class="pull-right">
                <a href="#home" class="scrollup" style="display: block;">
                    <img src="<?=selfPath?>top.png" />
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?=myWeb?>js/validator.min.js"></script>
<script src="<?=myWeb?>js/bootstrap.min.js"></script>
<script src="<?=myWeb?>js/fancybox/jquery.fancybox.pack.js"></script>
<!--script src="<?=myWeb?>js/jquery.easing.1.3.js"></script-->
<script src="<?=myWeb?>js/bxslider.js"></script>
<script src="<?=myWeb?>js/jquery.prettyPhoto.js"></script>
<script src="<?=myWeb?>js/jquery.isotope.min.js"></script>
<script src="<?=myWeb?>self.js"></script>
<script src="<?=myWeb?>js/classie.js"></script>
<script src="<?=myWeb?>js/uisearch.js"></script>
<script>
    var maxHeight=0;    
    $(".product-menu>li").each(function(){
        if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
    })
    $(".product-menu>li").height(maxHeight);
    new UISearch( document.getElementById( 'sb-search' ) );
</script>
<script>
    $(".search_index").click(function () {
        $('.content_search').toggle('show');
        $('#hint').focus();
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
    $(function(){
        $(".regular").slick({
            dots: false,
            infinite: true,
            slidesToShow: 6,
            slidesToScroll: 1,
            responsive: 
            [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    infinite: true
                  }
                },
                {
                  breakpoint: 600,
                  settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true
                  }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
      });
    })
</script>
</html>
