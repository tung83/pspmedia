<?php
class products{
    private $db,$view,$lang;
    function __construct($db,$view='san-pham',$lang='vi'){
        $this->db=$db;
        $this->lang=$lang;
        $item=$db->where('id',3)->getOne('menu','view,e_view');
        if($this->lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
        $this->view=$view;
    }
    function product_head(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'sp_banner.png" alt="Sản phẩm" class="img_full" />
            </div>
        </div>

        <div class="bk_sp  " style="transform:uppercase">
            <div>
                <h3 class="white">'.product_title.'</h3>
            </div>
        </div>';
        return $str;
    }
    function categories($db){
        $db->where('active',1)->where('lev',1);
        $db->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('category',null,'id,title,e_title,img');

        $str='
        <div class="container sp_cate" style="padding-left:0px">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-cate'.$item['id'].'.html';
            $str.='
            <div class="col-sm-6 list_row">
                <a class="" href="'.$lnk.'" title="'.$title.'">
                    <img src="'.webPath.$item['img'].'" class="img-responsive img_full" />
                    <p class="titleblock_blue">'.$title.'</p>
                </a>
             </div>';
        }
        $str.='
        </div>
        ';
        return $str;
    }
    function left_categories($db,$cate_id,$pId=0){
        $db->where('active',1)->where('lev',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('category',null,'id,title,e_title,icon');
        $str.='
        <ul id="accordion" class="accordion">';
        foreach($list as $item){
          if($this->lang=='en'){
              $title=$item['e_title'];
          }else{
              $title=$item['title'];
          }
          $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-cate'.$item['id'].'.html';
          if($item['id']==$cate_id) $cls='active';
          else $cls='';
          $str.='
          <li id="'.$cls.'">
            <div class="link"><i><img src="'.webPath.$item['icon'].'"/></i>'.$title.'<i class="fa fa-chevron-right"></i></div>';
          $db->where('active',1)->where('lev',2)->where('pId',$item['id'])->orderBy('ind','ASC')->orderBy('id');
          $sub_list=$db->get('category',null,'id,title,e_title');
          if($db->count>0){
              $str.='
              <ul class="submenu">';
              foreach($sub_list as $sub_item){
                if($this->lang=='en'){
                    $sub_title=$sub_item['e_title'];
                }else{
                    $sub_title=$sub_item['title'];
                }
                $sub_lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($sub_title).'-p'.$sub_item['id'].'.html';
                $str.='
                <li><a href="'.$sub_lnk.'">'.$sub_title.'</a></li>';
              }
              $str.='
                <li><a href="'.$lnk.'">'.all.'</a></li>
              </ul>';
          }
          $str.='
          </li>';
        }
        $str.='
        </ul>';
        $str.='
        <script>
        $(function() {
        	var Accordion = function(el, multiple) {
        		this.el = el || {};
        		this.multiple = multiple || false;

        		// Variables privadas
        		var links = this.el.find(".link");
        		// Evento
        		links.on("click", {el: this.el, multiple: this.multiple}, this.dropdown)
        	}

        	Accordion.prototype.dropdown = function(e) {
        		var $el = e.data.el;
        			$this = $(this),
        			$next = $this.next();

        		$next.slideToggle();
        		$this.parent().toggleClass("open");

        		if (!e.data.multiple) {
        			$el.find(".submenu").not($next).slideUp().parent().removeClass("open");
        		};
        	}

        	var accordion = new Accordion($("#accordion"), false);
        });
        $("#active").toggleClass("open");
        $("#active").find(".submenu").slideToggle();
        </script>';
        return $str;
    }
    function cate_sub($db,$pId){
        $cate_sub=$db->where('id',$pId)->getOne('category','pId,id,title,e_title');
        $cate_id=$cate_sub['pId'];
        $str='
         <div class="lstspsub">
            <div class="container lstspsub ">
                <div class="col-md-3">
                    '.$this->left_categories($db,$cate_id,$pId).'
                    <div class="muahangonline white">
                        <a href="http://kiwa.com.vn" target="_blank">
                            <span><img src="'.selfPath.'shop.png" /></span><span>'.pd_button.'</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('pId',$pId)->where('active',1)->orderBy('id');
        $db->pageLimit=$lim=15;
        $list=$db->paginate('product',$page);
        $count=$db->totalCount;
        foreach($list as $item){
            $str.=$this->product_list_one($db,$item['id']);
        }
        $str.='
                    </div>';
        if($this->lang=='en'){
            $cate_sub_title=$cate_sub['e_title'];
        }else{
            $cate_sub_title=$cate_sub['title'];
        }
        $pg=new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = $lim;
        $pg->totalrecords = $count;
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate_sub_title).'-p'.$cate_sub['id'].'.html';
        $pg->paginationUrl = myWeb.$this->lang.'/'.$this->view.'/pg[p]/'.common::slug($cate_sub_title).'-p'.$cate_sub['id'].'.html';
        $str.= '<div class="row text-center">'.$pg->process().'</div>';
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
    function cate($db,$cate_id){
        $str='
         <div class="lstspsub">
            <div class="container lstspsub ">
                <div class="col-md-3">
                    '.$this->left_categories($db,$cate_id).'
                    <div class="muahangonline white">
                        <a href="http://kiwa.com.vn" target="_blank">
                            <span><img src="'.selfPath.'shop.png" /></span><span>'.pd_button.'</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $cate_child_list=$this->cate_child_list($db,$cate_id);
        if(count($cate_child_list)<=0){
            $cate_child_list=array(0);
        }
        $db->where('pId',$cate_child_list,'in')->where('active',1)->orderBy('id');
        $db->pageLimit=$lim=15;
        $list=$db->paginate('product',$page);
        $count=$db->totalCount;
        foreach($list as $item){
            $str.=$this->product_list_one($db,$item['id']);
        }
        $str.='
                    </div>';
        $cate=$db->where('id',$cate_id)->getOne('category','id,title,e_title');
        if($this->lang=='en'){
            $cate_title=$cate['e_title'];
        }else{
            $cate_title=$cate['title'];
        }
        $pg=new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = $lim;
        $pg->totalrecords = $count;
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate_title).'-cate'.$cate['id'].'.html';
        $pg->paginationUrl = myWeb.$this->lang.'/'.$this->view.'/pg[p]/'.common::slug($cate_title).'-cate'.$cate['id'].'.html';
        $str.= '<div class="row text-center">'.$pg->process().'</div>';
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
    function cate_child_list($db,$cate_id){
        $db->where('pId',$cate_id)->where('active',1)->where('lev',2)->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('category',null,'id');
        $tmp=array();
        foreach($list as $item){
            $tmp[]=$item['id'];
        }
        return $tmp;
    }
    function product_list_one($db,$id){
        $db->where('id',$id);
        $item=$db->getOne('product');
        $db->where('pId',$item['id'])->orderBy('ind','ASC')->orderBy('id');
        $img=$db->getOne('product_image','img');
        if($this->lang=='en'){
            $title=$item['e_title'];
        }else{
            $title=$item['title'];
        }
        $price=$item['price']==0?contact_sp:number_format($item['price'],0,',','.').' VNĐ';
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $op_list='';
        $str='
        <div class="col-sm-4 list_row sp_list">
        <a class="item_sp" href="'.$lnk.'" title="'.$title.'">
          <div>
            <img src="'.webPath.$img['img'].'" class="img-responsive img_full" />
            <p>'.$title.'</p>';
        //if(isset($_GET['id'])){
            $str.='<span>'.$price.'</span>';   
        //}
        $str.='
            <div class="attention">'.$this->product_option($db,$item['pd_option']).'</div>
          </div>
        </a>
        </div> ';
        return $str;
    }
    function product_option($db,$pd_option,$type=1){
        if($pd_option!=''){
            $option=explode(',',$pd_option);
        }else{
            $option=array();   
        }            
        $opt_list=$opt_img_list='';
        if(count($option)>0){
            foreach((array)$option as $opt){
                $opt_item=$db->where('id',$opt)->getOne('product_option','icon,img,title,e_title');
                if($this->lang=='en'){
                    $title=$opt_item['e_title'];
                }else{
                    $title=$opt_item['title'];
                }
                if($opt_item!=null){
                    $opt_list.='<img src="'.webPath.$opt_item['icon'].'" class="" title="'.$title.'"/> ';
                    $opt_img_list.='<img src="'.webPath.$opt_item['img'].'" class="" title="'.$title.'"/> ';   
                }
            }   
        }    
        if($type==1) return $opt_list;
        else return $opt_img_list; 
    }
    function old_img(){
         $str.='
            <div class="col-sm-6 list_row">
                <div id="product-single">
                    <!-- Product -->
                    <a href="'.webPath.$this->product_image_first($db,$item['id']).'" class="test-popup-link">
                      <img src="'.webPath.$this->product_image_first($db,$item['id']).'" class="img-responsive" id="img-for-change"/>
                    </a>
                    <script>
                    $(".test-popup-link").magnificPopup({
                      type: "image",
                      zoom: {
                        enabled: true,
                        duration: 300
                      }
                    });
                    </script>
                    <!-- /Product -->
                    <link href="'.myWeb.'css/owl.carousel.css" rel="stylesheet">
                    <div class="row">
                    <div class="col-xs-2">
                      <a class="prev">
                      <i class="fa fa-caret-left"></i>
                      </a>
                    </div>
                    <div class="col-xs-8">
                      <div id="owl-demo" class="owl-carousel">';
        $db->where('active',1)->where('pId',$item['id'])->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('product_image',null,'id,img');
        foreach((array)$list as $img_pd){
          $str.='
          <div class="item"><img src="'.webPath.$img_pd['img'].'" class="img-responsive" onclick="change_own($(this).attr(\'src\'))"/></div>';
        }
        $str.='
                      </div>
                    </div>
                    <div class="col-xs-2">
                      <a class="next">
                        <i class="fa fa-caret-right"></i>
                      </a>
                    </div>
                    </div>
                    <script src="'.myWeb.'js/owl.carousel.js"></script>
                    <script>
                      function change_own(val){
                        $("#img-for-change").attr("src",val);
                        $(".test-popup-link").attr("href",val);
                      }
                      var owl = $("#owl-demo");
                      owl.owlCarousel({
                      items : 3, //10 items above 1000px browser width
                      itemsDesktop : [1000,5], //5 items between 1000px and 901px
                      itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
                      itemsTablet: [600,3], //2 items between 600 and 0;
                      itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
                      });
                      // Custom Navigation Events
                      $(".next").click(function(){
                        owl.trigger("owl.next");
                      })
                      $(".prev").click(function(){
                        owl.trigger("owl.prev");
                      })
                      $(".play").click(function(){
                        owl.trigger("owl.play",1000);
                      })
                      $(".stop").click(function(){
                        owl.trigger("owl.stop");
                      })
                    </script>
                </div>
            </div>';
    }
    function flex_show($db,$id){
        $db->reset();
        $str.='
        <link rel="stylesheet" href="'.myWeb.'css/flexslider.css" type="text/css" media="screen" />';
        $db->where('pId',$id)->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('product_image',null,'id,img');
        $tmp='';
        $temp='';
        foreach($list as $item){
            $temp.='<li><a href="'.webPath.$item['img'].'" class="">
                            <img src="'.webPath.$item['img'].'" alt="" title=""/></a></li>';
            $tmp.='
            <li><img src="'.webPath.$item['img'].'" alt="" title=""/></li>';
        }
        $str.='
        
            <div id="slider" class="flexslider">
              <ul class="slides popup-gallery">
                    '.$temp.'
              </ul>
            </div>
            <div id="carousel" class="flexslider">
              <ul class="slides">
                    '.$tmp.'
              </ul>
            </div>
        ';
        $str.='
        <script defer src="'.myWeb.'js/jquery.flexslider.js"></script>
        <script type="text/javascript">
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: false,
            itemWidth: 94,
            itemMargin: 5,
            asNavFor: "#slider"
          });
         
          $("#slider").flexslider({
            animation: "fade",
            controlNav: false,
            animationLoop: true,
            slideshow: false,
            sync: "#carousel"
          });
        });
      </script>';
        return $str;
    }
    function product_detail($db,$id){
        $db->where('id',$id);
        $item=$db->getOne('product');

        if($this->lang=='en'){
            $title=$item['e_title'];
            $feature=$item['e_feature'];
            $detail=$item['e_detail'];
            $content=$item['e_content'];
            $teach=$item['e_teach'];
            $pd_lnk=$item['e_lnk'];
        }else{
            $title=$item['title'];
            $feature=$item['feature'];
            $detail=$item['detail'];
            $content=$item['content'];
            $teach=$item['teach'];
            $pd_lnk=$item['lnk'];
        }
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $cate_sub=$db->where('id',$item['pId'])->getOne('category','pId');
        $cate_id=$cate_sub['pId'];
        $str='
         <div class="lstspsub">
            <div class="container lstspsub ">
                <div class="col-md-3">
                    '.$this->left_categories($db,$cate_id,$item['pId']).'
                    <div class="muahangonline white">
                        <a href="http://kiwa.com.vn" target="_blank">
                            <span><img src="'.selfPath.'shop.png" /></span><span>'.pd_button.'</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">';
        $str.='
        <div class="col-sm-6">'.$this->flex_show($db,$item['id']).'</div>';
        $str.='
        <div class="col-sm-6 list_row">
            <h1 class="tensp">'.$title.'</h1>
            <p class="giasp">'.detail_price.' : '.($item['price']>0?number_format($item['price'],0,',','.').'VNĐ':contact_sp).'</p>
            <p class="sptin">'.feature.'</p>
            <p>'.$feature.'</p>
            '.$this->product_option($db,$item['pd_option'],2).'
            <hr />
            <div class="social">
                <div class="fb-like" data-href="'.$lnk.'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                <div class="g-plusone" data-href="'.$lnk.'" data-size="medium"></div>
            </div>
            <hr />
            <p><a href="'.$pd_lnk.'" class="muangay" target="_blank" >
                <img src="'.selfPath.'muangay.png" />
            </a></p>
            <hr />
        </div>';

        $str.='
                    </div>';
        $str.='
        <div class="row">
            <div class="panel with-nav-tabs panel-default chitietsp">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1default" data-toggle="tab">'.content.'</a></li>
                        <li><a href="#tab2default" data-toggle="tab">'.detail.'</a></li>
                        <li><a href="#tab3default" data-toggle="tab">'.teach.'</a></li>
                        <li><a href="#tab4default" data-toggle="tab">'.video.'</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active sizec table-responsive" id="tab1default">
                            '.$content.'
                        </div>
                        <div class="tab-pane fade sizec table-responsive" id="tab2default">
                            '.$detail.'
                        </div>
                        <div class="tab-pane fade sizec table-responsive" id="tab3default">
                            '.$teach.'
                        </div>
                        <div class="tab-pane fade sizec table-responsive" id="tab4default" style="text-align:center">
                            '.($item['video']!=''?'<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>':'').'
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1)->where('pId',$item['pId'])->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=6;
        $list=$db->paginate('product',$page);
        if($db->totalCount>0){
            $str.='
            <div class="row">
                <div class="sp_khac">
                    SẢN PHẨM KHÁC
                </div>';
            foreach($list as $item){
                $str.=$this->product_list_one($db,$item['id']);
            }
            $str.='
            </div>';
        }
        $str.='
                </div>
            </div>
        </div>
        ';
        return $str;
    }
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }
}
?>
