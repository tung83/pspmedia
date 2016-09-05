<?php
include_once 'front.php';
function pageHeader($db,$view,$lang)
{
    switch ($view) {
        case 'san-pham':
        case 'product':
            if (isset($_GET['id'])) {
                $db->where('id', intval($_GET['id']));
                $item = $db->getOne('product', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } elseif (isset($_GET['pId'])) {
                $db->where('id', intval($_GET['pId']));
                $item = $db->getOne('category', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } elseif(isset($_GET['cate_id'])) {
                $db->where('id', intval($_GET['cate_id']));
                $item = $db->getOne('category', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            }            
        case 'he-thong-phan-phoi':
        case 'distribution-channel':
            if (isset($_GET['id'])) {
                $db->where('id', intval($_GET['id']));
                $item = $db->getOne('sys', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            }
        case 'bao-hanh':
        case 'warranty':
            if (isset($_GET['id'])) {
                $db->where('id', intval($_GET['id']));
                $item = $db->getOne('guarantee', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
                break;
            }
        case 'tin-tuc':
        case 'news':
            if (isset($_GET['id'])) {
                $db->where('id', intval($_GET['id']));
                $item = $db->getOne('news', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } elseif (isset($_GET['pId'])) {
                $db->where('id', intval($_GET['pId']));
                $item = $db->getOne('news_cate', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } 
        case 'trang-chu':
        case 'lien-he':
        default:
            $db->where('view', $view);
            $item = $db->getOne('menu', 'title,meta_keyword,meta_description');
            $param = array(
                'title' => $item['title'].' | MeKong Trading CO.,LTD',
                'keyword' => $item['meta_keyword'],
                'description' => $item['meta_description']);
            break;
    }
    $param['title'] = (trim($param['title']) === '') ? head_title : $param['title'];
    $param['meta_keyword'] = (trim($param['meta_keyword']) === '') ? head_keyword : $param['meta_keyword'];
    $param['meta_description'] = (trim($param['meta_description']) === '') === ''?head_descript : $param['meta_description'];
    common::page_head($param);
}
function home($db, $lang, $view)
{
    $str = new_slide($db, $lang);
    include_once phpLib . 'about.php';
    $obj = new about($db, $lang);
    $str .= $obj->ind_about($db);

    $str .= ind_cate($db, $lang);

    include_once phpLib . 'sys.php';
    $obj = new sys($db, $lang);
    $str .= $obj->ind_sys($db);

    $str .= '
    <div class="main-feature  bk_tintuc_index"></div>
    <div class="tintuc_listHome">
        <div class="container">';
    include_once phpLib . 'guarantee.php';
    $obj = new guarantee($db, $lang);
    $str .= $obj->ind_guarantee($db);

    include_once phpLib . 'news.php';
    $obj = new news($db, $lang);
    $str .= $obj->ind_news($db);

    include_once phpLib . 'video.php';
    $obj = new video($db, $lang);
    $str .= $obj->ind_video($db);
    $str .= '
        </div>
    </div>';
    return $str;
}
function new_menu($db,$lang,$view){
    $db->where('active', 1);
    $db->orderBy('ind', 'ASC');
    $db->orderBy('id');
    $list = $db->get('menu');
    $str .= '
  <header class="hidden-xs hidden-sm hidden-md navigation clearfix">
    <div class="container">
    <div class="logo">
    <a href="'.myWeb.$lang.'">
        <img src="' . selfPath .'logo.png" class="img-responsive"/>
    </a>
    </div>
    <div class="menu">
        <ul class="nav nav-tabs" role="tablist">';
    foreach ($list as $item) {
        if ($lang == 'vi') {
            $title = $item['title'];
            $flag = 'en_lan.png';
            $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'en');
            $item_view = $item['view'];
        } else {
            $title = $item['e_title'];
            $flag = 'vnflag.png';
            $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'vi');
            $item_view = $item['e_view'];
        }
        $lnk = myWeb . $lang . '/' . $item_view . '.html';
        if($item['view']=='bao-hanh') $lnk= myWeb . $lang . '/' . $item_view . '/chinh-sach-bao-hanh-p15.html';
        if ($view == $item['view'] || $view == $item['e_view'])
            $cls = ' class="active"';
        else
            $cls = '';
        $str .= '
    <li><a href="' . $lnk . '"' . $cls . '>' . $title . '</a>';
        if ($item['view'] == 'san-pham') {
            $db->where('active', 1)->where('lev', 1)->orderBy('ind', 'ASC')->orderBy('id');
            $sub_list = $db->get('category', null, 'id,title,e_title,img');
            if ($db->count > 0) {
                $f_img=$sub_list[0]['img'];
                $str .= '
        <div class="pd-menu">
        <ul class="product-menu-new">
            <li class="menu-img">
                <img src="'.webPath.$f_img.'" title="" class="img-responsive"/>
            </li>
            <li class="sub-menu">
                <ul>';
                $temp='';
                foreach($sub_list as $sub_item){
                    if ($lang == 'vi') {
                        $sub_title = $sub_item['title'];
                    } else {
                        $sub_title = $sub_item['e_title'];
                    }
                    $sub_lnk = myWeb . $lang . '/' . $item_view . '/' . common::slug($sub_title) .'-cate' . $sub_item['id'] . '.html';
                    $str.='
                    <li><a href="'.$sub_lnk.'" data="'.$sub_item['id'].'" value="'.webPath.$sub_item['img'].'">'.$sub_title.'</a></li>';
                    $db->where('active', 1)->where('lev', 2)->where('pId',$sub_item['id'])->orderBy('ind', 'ASC')->orderBy('id');
                    $child_list = $db->get('category', null, 'id,title,e_title');
                    $temp.='
                        <div id="sub-menu-'.$sub_item['id'].'">';
                    if($db->count>0){ 
                        foreach($child_list as $child_item){
                            if ($lang == 'vi') {
                                $child_title = $child_item['title'];
                            } else {
                                $child_title = $child_item['e_title'];
                            }
                            $child_lnk=myWeb.$lang.'/'.$item_view.'/'.common::slug($child_title).'-p'.$child_item['id'].'.html';
                            $temp.='
                            <a href="'.$child_lnk.'">'.$child_title.'</a>';
                        }                                                
                    }
                    $temp.='
                        </div>';   
                    
                }
                $str.='
                </ul>
            </li>
            <li class="sub-menu-show">';
            $str.=$temp;
            $str.='
            </li>';
                
                $str .= '
        </ul>
        </div>';
            }
        }
        $str .= '
    </li>';
    }
    $str .= '
            <li>
                <div class="search_index">
                    <img src="' . selfPath . 'icon-search-index.png" />
                </div>
                <div class="dropdown_lag">
                     <a href="' . $flag_lnk . '">
                        <img src="' . selfPath . $flag . '" />
                     </a>
                </div>
            </li>
        </ul>
    </div>
    </div>
  </header>';
    return $str;
}
function big_menu($db, $lang, $view)
{
    $db->where('active', 1);
    $db->orderBy('ind', 'ASC');
    $db->orderBy('id');
    $list = $db->get('menu');
    $str .= '
  <header class="hidden-xs hidden-sm navigation clearfix">
    <div class="container">
    <div class="logo"><img src="' . selfPath .'logo.png" class="img-responsive"/></div>
    <div class="menu">
        <ul class="nav nav-tabs" role="tablist">';
    foreach ($list as $item) {
        if ($lang == 'vi') {
            $title = $item['title'];
            $flag = 'en_lan.png';
            $flag_lnk = myWeb . 'en/home.html';
            $item_view = $item['view'];
        } else {
            $title = $item['e_title'];
            $flag = 'vnflag.png';
            $flag_lnk = myWeb . 'vi/trang-chu.html';
            $item_view = $item['e_view'];
        }
        $lnk = myWeb . $lang . '/' . $item_view . '.html';
        if($item['view']=='bao-hanh') $lnk= myWeb . $lang . '/' . $item_view . '/chinh-sach-bao-hanh-p15.html';
        if ($view == $item['view'] || $view == $item['e_view'])
            $cls = ' class="active"';
        else
            $cls = '';
        $str .= '
    <li><a href="' . $lnk . '"' . $cls . '>' . $title . '</a>';
        if ($item['view'] == 'san-pham') {
            $db->where('active', 1)->where('lev', 1)->orderBy('ind', 'ASC')->orderBy('id');
            $sub_list = $db->get('category', null, 'id,title,e_title');
            if ($db->count > 0) {
                $str .= '
        <ul class="product-menu">';
                foreach ($sub_list as $sub_item) {
                    if ($lang == 'vi') {
                        $sub_title = $sub_item['title'];
                    } else {
                        $sub_title = $sub_item['e_title'];
                    }
                    $sub_lnk = myWeb . $lang . '/' . $item_view . '/' . common::slug($sub_title) .'-cate' . $sub_item['id'] . '.html';
                    $str .= '
                    <li><a href="' . $sub_lnk . '">' . $sub_title .'</a>';
                    $db->where('active', 1)->where('lev', 2)->where('pId',$sub_item['id'])->orderBy('ind', 'ASC')->orderBy('id');
                    $child_list = $db->get('category', null, 'id,title,e_title');
                    $str.='
                        <ul>';
                    if($db->count>0){                        
                        foreach($child_list as $child_item){
                            if ($lang == 'vi') {
                                $child_title = $child_item['title'];
                            } else {
                                $child_title = $child_item['e_title'];
                            }
                            $child_lnk=myWeb.$lang.'/'.$item_view.'/'.common::slug($child_title).'-p'.$child_item['id'].'.html';
                            $str.='
                            <li><a href="'.$child_lnk.'">'.$child_title.'</a></li>';
                        }                           
                    }
                    $str.='
                        </ul>
                    </li>';
                }
                $str .= '
        </ul>';
            }
        }
        $str .= '
    </li>';
    }
    $str .= '
            <li>
                <div class="search_index">
                    <img src="' . selfPath . 'icon-search-index.png" />
                </div>
                <div class="dropdown_lag">
                     <a href="' . $flag_lnk . '">
                        <img src="' . selfPath . $flag . '" />
                     </a>
                </div>
            </li>
        </ul>
    </div>
    </div>
  </header>';
    return $str;
}
function menu($db, $lang, $view)
{
    $db->where('active', 1);
    $db->orderBy('ind', 'ASC');
    $db->orderBy('id');
    $list = $db->get('menu');
    $temp = '';
    $tmp = '';
    foreach ($list as $item) {
        if ($lang == 'vi') {
            $title = $item['title'];
            $lnk = myWeb . $lang . '/' . $item['view'] . '.html';
            $flag = 'en_lan.png';
            $flag_lnk = myWeb . 'en/home.html';
            if($item['view']=='bao-hanh') $lnk=myWeb . $lang . '/' . $item['view'].'/chinh-sach-bao-hanh-p15.html';
        } else {
            $title = $item['e_title'];
            $lnk = myWeb . $lang . '/' . $item['e_view'] . '.html';
            $flag = 'vnflag.png';
            $flag_lnk = myWeb . 'vi/trang-chu.html';
            if($item['view']=='bao-hanh') $lnk=myWeb . $lang . '/' . $item['e_view'].'/chinh-sach-bao-hanh-p15.html';
        }
        if ($view == $item['view'] || $view == $item['e_view'])
            $cls = 'active';
        else
            $cls = '';
        $tmp .= '
        <li class="' . $cls . $drop . '"><a href="' . $lnk . '">' . $title .
            '</a></li>';
    }
    $str .= '
    <header class="hidden-lg">
    <nav class="navbar navbar-default navbar-fixed-top" style="z-index:99999">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand">
                <a href="' . myWeb . '" title="logo">
                    <img src="'.selfPath.'logo.png" class="logo" alt="logo" title="logo" style="margin-top:-7px"/>
                </a>
            </div>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
          <ul class="nav navbar-nav">
                ' . $tmp . '
                <li><a href="' . $flag_lnk . '">
                    <img src="' . selfPath . $flag . '" />
                 </a></li>
          </ul>
          <form class="navbar-form navbar-right" role="search">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search">
            </div>
          </form>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    </header>
    ';
    return $str;
}
function slide($db, $lang)
{
    $db->orderBy('ind', 'asc');
    $db->orderBy('id', 'asc');
    $db->where('active', 1);
    $list = $db->get('slider');
    $temp = '';
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
    }
    $str = '
    <div class="slider">
        <div class="img-responsive">
            <ul class="bxslider">
                ' . $temp . '
            </ul>
        </div>
    </div>
    ';
    return $str;
}
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
    <!-- End WOWSlider.com HEAD section -->
    <!-- Start WOWSlider.com BODY section -->
    <div id="wowslider-container1" class="clearfix">
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
function left_module($db)
{
    $str .= category($db);
    return $str;
}
function category($db)
{
    $str = '
    <div class="col-sm-3">
    	<div class="left-sidebar">
    		<h2>Danh Mục</h2>
    		<div class="panel-group category-products" id="accordian"><!--category-productsr-->';
    $db->where('active', 1);
    $db->where('lev', 1);
    $list = $db->get('category', null, 'id,title');
    foreach ($list as $item) {
        $db->where('pId', $item['id']);
        $db->where('lev', 2);
        $child_list = $db->get('category', null, 'id,title');
        if ($db->count > 0) {
            $plus = '<span class="pull-right"><i class="fa fa-plus"></i></span>';
            $tmp = '
            <div id="cate_sub' . $item['id'] .
                '" class="panel-collapse collapse">
				<div class="panel-body">
					<ul>';
            foreach ($child_list as $child_item) {
                $lnk = myWeb . 'san-pham/' . common::slug($child_item['title']) . '-p' . $child_item['id'] .
                    '.html';
                $tmp .= '<li><a href="' . $lnk . '">' . $child_item['title'] . ' </a></li>';
            }
            $tmp .= '
					</ul>
				</div>
			</div>
            ';
        } else {
            $plus = '';
            $tmp = '';
        }
        $str .= '
        <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordian" href="#cate_sub' . $item['id'] .
            '">
						' . $plus . '
						' . $item['title'] . '
					</a>
				</h4>
			</div>
			' . $tmp . '
		</div>
        ';
    }
    $str .= '
    		</div><!--/category-products-->
    		<div class="shipping text-center"><!--shipping-->
    			<img src="/images/home/shipping.jpg" alt="" />
    		</div><!--/shipping-->

    	</div>
    </div>
    ';
    return $str;
}
function feature_product($db)
{
    $db->where('active', 1)->where('home', 1);
    $list = $db->get('product', null, 'id');
    $str .= '
    <div class="features_items"><!--features_items-->
		<h2 class="title text-center">Sản Phẩm Nổi Bật</h2>
    ';
    foreach ($list as $item) {
        $pd = new product($db, 'product');
        $pd->set_id($item['id']);
        $str .= $pd->feature_item();
    }
    $str .= '
	</div><!--features_items-->
    ';
    return $str;
}
function contact($db, $lang, $view)
{
    include_once phpLib . 'contact.php';
    $obj = new contact($db, $view, $lang);
    $str = $obj->contact_head();
    $str .= $obj->contact_under_head();
    $str .= $obj->contact_content();
    return $str;
}

function breadcrumb($db)
{
    $breadcrumb = new breadcrumb();
    $breadcrumb->add('Trang Chủ', 'trang-chu.html')->add('Sản Phẩm', 'san-pham.html');
    $breadcrumb->add('Liên Hệ', '#');
    return $breadcrumb->bootstrap();
}
function ind_cate($db, $lang)
{
    $db->where('active', 1)->where('lev', 1)->orderBy('ind', 'ASC')->orderBy('id');
    $list = $db->get('category', null, 'id,title,e_title,icon');
    $str = '
    <div class="main-feature">
        <div class="bk_danhmuc text-center">
            <h2 class="white">
               ' . cate . '
            </h2>
        </div>
        <div style="background:#354b9c;padding:32px 0px">
            <div class="container" style=""> 
            <div class="row">               
                <section class="regular" id="slick-slider">';
    foreach($list as $item){
        if ($lang=='vi') {
            $title=$item['title'];
        } else {
            $title=$item['e_title'];
        }
        $lnk=myWeb.$lang.'/'.pd_view.'/'.common::slug($title).'-cate'.$item['id'].'.html';
        $str.='
        <div>
            <a href="' . $lnk . '">
                <img src="' . webPath . $item['icon'] . '" />            
                <h3>' . $title . '</h3>
            </a>
        </div>';
    }
    $str.='
                </section>';
   /* $i = 0;
    foreach ($list as $item) {
        $i++;
        if ($i % 4 == 1) {
            $str .= '
            <div class="col-md-6 col-sm-6">
                <div class="row">
            ';
        }
        if ($lang == 'vi') {
            $title = $item['title'];
        } else {
            $title = $item['e_title'];
        }
        $lnk = myWeb . $lang . '/' . pd_view . '/' . common::slug($title) . '-cate' . $item['id'] .
            '.html';
        $str .= '
        <div class="col-md-3 col-xs-6 item_dm">
            <div>
                <a href="' . $lnk . '">
                    <img src="' . webPath . $item['icon'] . '" />
                </a>
            </div>
            <h3>
                <a href="' . $lnk . '">' . $title . '</a>
            </h3>
        </div>';
        if ($i % 4 == 0) {
            $str .= '
                </div>
            </div>
            ';
        }
    }
    if ($i % 4 != 0) {
        $str .= '
            </div>
        </div>
        ';
    }*/
    $str .= '
            </div>     
            </div>
        </div>
    </div>
    ';
    return $str;
}
function about($db, $lang, $view)
{
    include_once phpLib . 'about.php';
    $obj = new about($db, $lang);
    $str = $obj->about_head();
    if (!isset($_GET['id'])) {
        $str .= $obj->about_all($db);
    } else {
        $str .= $obj->about_one($db);
    }
    return $str;
}
function product($db, $lang, $view)
{
    include_once phpLib . 'products.php';
    $obj = new products($db, $view, $lang);
    $str = $obj->product_head();
    if (isset($_GET['id'])) {
        $str .= $obj->product_detail($db, intval($_GET['id']));
    } elseif (isset($_GET['pId'])) {
        $str .= $obj->cate_sub($db, intval($_GET['pId']));
    } elseif (isset($_GET['cate_id'])) {
        $str .= $obj->cate($db, intval($_GET['cate_id']));
    } else {
        $str .= $obj->categories($db);
    }
    return $str;
}
function sitemap($db, $lang, $view)
{
    $db->where('id', 1);
    $item = $db->getOne('qtext');
    if ($lang == 'en') {
        $content = $item['e_content'];
    } else {
        $content = $item['content'];
    }
    $str = '
    <div class="slider">
        <div class="img-responsive">
            <img src="' . selfPath .
        'lienhe_banner.png" alt="Banner đẹp" class="img_full" />
        </div>
    </div>
﻿   <div class="bk_video">
        <div>
            <h3 class="white">Sitemap</h3>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12">
            <div class="col-sm-3 list_row">
                <p style="word-wrap: break-word;">
                    ' . $content . '
                </p>
            </div>

        </div>
    </div>
    ';
    return $str;
}
function news($db, $lang, $view)
{
    include_once phpLib . 'news.php';
    $obj = new news($db, $lang);
    $str = $obj->news_head();
    if (isset($_GET['id'])) {
        $str .= $obj->news_one($db, intval($_GET['id']));
    } elseif (isset($_GET['pId'])) {
        $str .= $obj->news_cate($db, intval($_GET['pId']));
    } else {
        $str .= $obj->news_all($db);
    }
    return $str;
}
function support($db, $lang, $view)
{
    include_once phpLib . 'support.php';
    $obj = new support($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->support_one($db, intval($_GET['id']));
    } elseif (isset($_GET['pId'])) {
        $str .= $obj->support_cate($db, intval($_GET['pId']));
    } else {
        $str .= $obj->support_all($db);
    }
    return $str;
}
function video($db, $lang, $view)
{
    include_once phpLib . 'video.php';
    $obj = new video($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->video_one($db, intval($_GET['id']));
    } else {
        $str .= $obj->video_cate($db, intval($_GET['pId']));
    }
    return $str;
}
function sys($db, $lang, $view)
{
    include_once phpLib . 'sys.php';
    $obj = new sys($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->sys_one($db, intval($_GET['id']));
    } else {
        $str .= $obj->sys_cate($db, intval($_GET['pId']));
    }
    return $str;
}
function guarantee($db, $lang, $view)
{
    include_once phpLib . 'guarantee.php';
    $obj = new guarantee($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->guarantee_one($db, intval($_GET['id']));
    } else {
        $str .= $obj->guarantee_cate($db, intval($_GET['pId']));
    }
    return $str;
}
function search($db,$lang,$view){
    $hint=$_POST['hint'];
    include_once phpLib . 'search.php';
    $obj = new search($db,$hint,$lang);
    $str=$obj->heading();
    $str.=$obj->output();
    return $str;
}
function footer_article($db,$lang){
    $db->reset();
    $db->where('active',1)->where('pId',1)->orderBy('ind','ASC')->orderBy('id');
    $list=$db->get('for_footer_and_contact');
    foreach($list as $item){
        if ($lang == 'en') {
            $title=$item['e_title'];
            $content = $item['e_content'];
        } else {
            $title=$item['title'];
            $content = $item['content'];
        }
        $str.='
        <div class="col-md-4">
            <p><span><b>'.$title.'</b></span></p>
            <p>'.$content.'</p>
        </div>';
    }
    return $str;
}
?>
