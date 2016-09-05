<?php
class news{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->lang=$lang;
        $db->where('id',6);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
    }
    function news_head(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'tintuc_banner.png" alt="Tin tức" class="img_full" />
            </div>
        </div>

        <div class="bk_tintuc">
            <div>
                <h3 class="white">'.news.'</h3>
            </div>
        </div>';
        return $str;
    }
    function news_all($db){
        $str='
        <div class="container">';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $db->pageLimit=10;
        $list=$db->paginate('news',$page);
        $str.='
            <div class="col-md-9">
                '.$this->news_list($list).'
            </div>';
        $str.=$this->left_module($db);;
        $str.='
        </div>';
        return $str;
    }
    function news_list($list){
        $str='';
        $i=1;
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
                $sum=$item['e_sum'];
            }else{
                $title=$item['title'];
                $sum=$item['sum'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            if(trim($title)!=''){
                $str.='<div class="row">';
                if($i==1){
                    $str.='
                    <div class="col-md-6 padding10">';
                }else{
                    $str.='
                    <div class="col-md-4 padding10">';
                }
                $str.='
                    <a class="" href="'.$lnk.'" title="'.$title.'">
                        <img src="'.webPath.$item['img'].'" class="img-responsive" alt="'.$title.'" />
                    </a>
                </div>';
                if($i==1){
                    $str.='
                    <div class="col-md-6 padding10">';
                }else{
                    $str.='
                    <div class="col-md-8 padding10">';
                }
                $str.='
                    <a href="'.$lnk.'" title="'.$title.'" class="title_tintuc">
                    '.$title.'
                    </a>
                    <p style="word-wrap: break-word; text-align: justify;">
                    '.nl2br($sum).'
                    </p>
                </div>';
                $str.='</div>';
                $i++;    
            }
        }
        return $str;
    }
    function left_module($db,$pId=0){
        $db->where('active',1)->orderBy('id');
        $list=$db->get('news_cate',null,'id,title,e_title');
        $str.='
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            if($item['id']==$pId) $cls=' active';else $cls='';
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'].'.html';
            if(trim($title)!=''){
                $str.='
                <div class="dropbtn tintuc'.$cls.'">
                    <span class="caret-2">
                        <img src="'.selfPath.'icon-tin-con.png">
                    </span>
                    <span class="title">
                        <a href="'.$lnk.'">'.$title.'</a>
                    </span>
                </div>
                ';   
            }
        }
        $str.='
                </div>
            </div>';
        $str.='
        <div class="row">
            <div class="col-md-12"> 
            <h5 class="page-header">'.most_view_news.'</h5>';
        $page=1;
        $db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $db->pageLimit=10;
        $list=$db->paginate('news',$page);
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
                $sum=$item['e_sum'];
            }else{
                $title=$item['title'];
                $sum=$item['sum'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            if(trim($title)!=''){
                $str.='
                <div class="row most-view-news" style="">
                <a href="'.$lnk.'">
                    <div class="col-xs-5">
                    <img src="'.webPath.$item['img'].'" class="img-responsive"/>
                    </div>
                    <div class="col-xs-7">
                        <h4>'.$title.'</h4>
                        <span>'.nl2br(common::str_cut($sum,80)).'</span>
                    </div>
                </a>
                </div>
                ';   
            }
        }
        $str.='
            </div>
        </div>';
        $str.='
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
            if(trim($title)!=''){
                $str.='
                <div class="col-sm-6 list_row">
                    <a class="" href="'.$lnk.'" title="'.$title.'">
                        <img src="'.webPath.$item['img'].'" class="img-responsive img_full" />
                        <p class="titleblock_blue">'.$title.'</p>
                    </a>
                 </div>';   
            }
        }
        $str.='
        </div>
        ';
        return $str;
    }
    function news_cate($db,$pId){
        $str='
        <div class="container">';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1)->where('pId',$pId)->orderBy('ind','ASC')->orderBy('id');
        $db->pageLimit=10;
        $list=$db->paginate('news',$page);
        $str.='
            <div class="col-md-9">
                '.$this->news_list($list).'
            </div>
            '.$this->left_module($db,$pId).'
        </div>';
        return $str;
    }
    function cate($db,$cate_id){
        $str='
         <div class="lstspsub">
            <div class="container lstspsub ">
                <div class="col-md-3">
                    '.$this->left_categories($db,$cate_id).'
                    <div class="muahangonline white"><span><img src="'.selfPath.'shop.png" /></span><span>Mua hàng online</span></div>
                </div>
                <div class="col-md-9">
                    <div class="row">';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $cate_child_list=$this->cate_child_list($db,$cate_id);
        $db->where('pId',$cate_child_list,'in')->where('active',1)->orderBy('id');
        $db->pageLimit=15;
        $count=$db->totalCount;
        $list=$db->paginate('product',$page);
        foreach($list as $item){
            $str.=$this->product_list_one($db,$item['id']);
        }
        $str.='
                    </div>
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
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $str='
        <div class="col-sm-4 list_row sp_list">
        <a class="item_sp" href="'.$lnk.'" title="'.$title.'">
        <div class="icon_tk">
        <!--img src="" title="Tiết kiệm điện năng" /-->
        </div>
        <img src="'.webPath.$img['img'].'" class="img-responsive img_full" />
        </a>
        <a class="title_sp title" href="'.$lnk.'">'.$title.'</a>
        </div> ';
        return $str;
    }
    function news_one($db,$id){
        $db->where('id',$id);
        $item=$db->getOne('news');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $content=$item['e_content'];
        }else{
            $title=$item['title'];
            $content=$item['content'];
        }
        $str='
        <div class="container">';
        $str.='
            <div class="col-md-8">
                <h1 class="" style="margin-top:0px;font-size:20px;line-height:20px;font-weight:bold">'.$title.'</h1>
                <p>'.$content.'</p>
            </div>
            '.$this->left_module($db,$item['pId']).'
        </div>';
        return $str;
    }
    function ind_news($db){
        $this->lang=='en'?$db->where('e_title','','<>'):$db->where('title','','<>');
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('news','id,title,e_title,sum,e_sum,img');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $sum=$item['e_sum'];
        }else{
            $title=$item['title'];
            $sum=$item['sum'];
        }
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $str='
        <div class="col-md-4">
            <a href="'.myWeb.$this->lang.'/'.$this->view.'.html'.'"><h3 class="ind-title">'.news.'</h3></a>
            <hr class="hr_title" />
            <div class="ind_news">
              <div class="row">
                <div class="col-sm-5">
                    <a href="'.$lnk.'" class="">
                        <img src="'.webPath.$item['img'].'" alt="'.$title.'" class="img-responsive" />
                    </a>
                </div>
                <div class="col-sm-7 padding0" style="padding:4px">
                    <a href="'.$lnk.'" style="">
                        <h4 style="margin-top:0px">'.$title.'</h4>
                        <p>'.nl2br($sum).'</p>
                    </a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                    <ul class="listNews">';
        $this->lang=='en'?$db->where('e_title','','<>'):$db->where('title','','<>');
        $db->where('active',1)->where('id',$item['id'],'<>')->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('news',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
                $sum=$item['e_sum'];
            }else{
                $title=$item['title'];
                $sum=$item['sum'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>';
        }
        $str.='
                    </ul>
                </div>
              </div>
            </div>
        </div>';
        return $str;
    }
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }

}
?>
