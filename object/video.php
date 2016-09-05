<?php
class video{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $db->where('id',8);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
        $this->lang=$lang;
        //$this->db=$db;
    }
    function heading(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'video_banner.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>     
    ﻿   <div class="bk_video">
            <div>
                <h3 class="white">'.video_title.'</h3>
            </div>
        </div>';
        return $str;
    }
    function categories($db,$pId=0){
        $str='
        <div class="row" style="padding-bottom:10px">';
        $db->where('active',1)->orderBy('id','ASC');
        $list=$db->get('video_cate',null,'id,title,e_title,icon');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'].'.html';
            if($item['id']==$pId){
                $cls=' active';
            }else{
                $cls='';
            }
            $str.='
            <div class="accordion-group">
                <div class="accordion-heading  accordion_sp menu_hotro'.$cls.'">
                    <a class="accordion-toggle accordion_sp_link" href="'.$lnk.'">
                        <span class="icon_menu_left">
                            <img src="'.webPath.$item['icon'].'" class="img1">
                        </span>
                            '.$title.'
                        <span class="icon_menu right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>';
        }
        $str.='
        </div>';
        return $str;
    }
    function video_cate($db,$pId=0){
        $str='
        <div class="player_content">
        <div class="container">
        <div class="row">
            <div class="col-md-3">
                '.$this->categories($db,$pId).'
            </div>';
        if($pId!=0) $db->where('pId',$pId);
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('video','id,video');
        $str.='
            <div class="col-md-9">';
        $str.='
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>';
        $str.='
            </div>
        </div>
        </div>
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        if($pId!=0) $db->where('pId',$pId);
        $db->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=$lim=8;
        $list=$db->paginate('video',$page);
        $count=$db->totalCount;
        $str.='
        <div class="white listvideo" style="background:#fff">
            <div class="container">
            <div class="row">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <div class="col-xs-6 col-sm-3 list_row">
                <a href="'.$lnk.'" title="Video">
                    <img src="http://img.youtube.com/vi/'.$item['video'].'/2.jpg" class="img-responsive img-video" />
                    <span class="bk_player"></span>
                </a>
                <a href="'.$lnk.'" title="Video" class="titleVideo">'.$title.'</a>
            </div>';  
        }
        $pg=new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = $lim;
        $pg->totalrecords = $count;
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'.html';
        if($pId==0){
        $pg->paginationUrl = myWeb.$this->lang.'/[p]/'.$this->view.'.html';  
        }else{
        $db->where('id',$pId);
        $cate=$db->getOne('video_cate','id,title,e_title');
        if($this->lang=='en') $cate_title=$cate['e_title'];else $cate_title=$cate['title'];
        $pg->paginationUrl = myWeb.$this->lang.'/'.$this->view.'/[p]/'.common::slug($cate_title).'-p'.$cate['id'].'.html';  
        }
        $str.= '<div class="col-md-12 text-center">'.$pg->process().'</div>';
        $str.='
            </div>
            </div>
        </div>';
        return $str;  
    }
    function video_one($db,$id=0){
        $db->where('active',1)->where('id',$id)->orderBy('id');
        $item=$db->getOne('video','id,video,pId');
        $str='
        <div class="player_content">
        <div class="container">
            <div class="col-md-3">
                '.$this->categories($db,$item['pId']).'
            </div>';
        $str.='
            <div class="col-md-9">';
        $str.='
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>';
        $str.='
            </div>
        </div>
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('pId',$item['pId']);
        $db->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=$lim=8;
        $list=$db->paginate('video',$page);
        $count=$db->totalCount;
        $str.='
        <div class="white listvideo" style="background:#fff">
            <div class="container">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <div class="col-sm-3 list_row">
                <a href="'.$lnk.'" title="Video">
                    <img src="http://img.youtube.com/vi/'.$item['video'].'/2.jpg" class="img-responsive img-video" />
                    <span class="bk_player"></span>
                </a>
                <a href="'.$lnk.'" title="Video" class="titleVideo">'.$title.'</a>
            </div>';  
        }
        $this->
        $str.='
            </div>
        </div>';
        return $str;  
    }
    function ind_video($db){
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('video','video,id');
        $str='
        <div class="col-md-4">
            <a href="'.myWeb.$this->lang.'/'.$this->view.'.html'.'"><h3 class="ind-title">'.video.'</h3></a>
            <hr class="hr_title" />
            <div>
                <iframe width="100%" height="250" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>
            </div>
            <div>
                <ul class="listNews">';
        $db->where('active',1)->where('id',$item['id'],'<>')->orderBy('id');
        $list=$db->get('video',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>';
        }
        $str.='
                </ul>
            </div>
        </div>';
        return $str;
    }
}


?>