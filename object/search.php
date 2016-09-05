<?php
class search{
    private $db,$lang,$hint;
    function __construct($db,$hint,$lang='vi'){
        $this->lang=$lang;
        $this->db=$db;
        $this->hint=$hint;
    }
    function heading(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'htbanner.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>     
    ﻿   <div class="bk_HoTro">
            <div>
                <h3 class="white">'.search.'</h3>
            </div>
        </div>';
        return $str;
    }
    function about_search($db){
        if($this->lang=='en'){
            $db->where('e_title','%'.$this->hint.'%','like'); 
        }else{
            $db->where('title','%'.$this->hint.'%','like'); 
        }        
        return $db->get('about',null,'id');
    }
    function pd_search($db){
        if($this->lang=='en'){
            $db->where('e_title','%'.$this->hint.'%','like'); 
        }else{
            $db->where('title','%'.$this->hint.'%','like'); 
        }        
        return $db->get('product',null,'id');
    }
    function news_search($db){
        if($this->lang=='en'){
            $db->where('e_title','%'.$this->hint.'%','like'); 
        }else{
            $db->where('title','%'.$this->hint.'%','like'); 
        }        
        return $db->get('news');
    }
    function sp_search($db){
        if($this->lang=='en'){
            $db->where('e_title','%'.$this->hint.'%','like'); 
        }else{
            $db->where('title','%'.$this->hint.'%','like'); 
        }        
        return $db->get('sp',null,'id');
    }
    function video_search($db){
        if($this->lang=='en'){
            $db->where('e_title','%'.$this->hint.'%','like'); 
        }else{
            $db->where('title','%'.$this->hint.'%','like'); 
        }        
        return $db->get('video',null,'id');
    }
    private function count(){
        $count=0;
        //$count+=count($this->about_search());
        $count+=count($this->pd_search($this->db));
        $count+=count($this->news_search($this->db));
        //$count+=count($this->sp_search());
        //$count+=count($this->video_search());
        return $count;
    }
    function output(){
        $count=$this->count();
        include_once 'products.php';
        $pd=new products($this->db,'san-pham',$this->lang);
        $str='
        <div class="container">
        <div class="row">
            <div class="col-md-3">
            '.$pd->left_categories($this->db,0).'
            </div>
            <div class="col-md-9">
            <h3 class="page-heading" style="margin-top:0px;color:#f00">
                Có '.$count.' kết quả được tìm thấy với từ khoá "'.$this->hint.'"
            </h3>';
        //for product
        $str.='
        <div class="row">';
        foreach($this->pd_search($this->db) as $item){
            $str.=$pd->product_list_one($this->db,$item['id']);
        }
        $str.='
        </div>';
        //for news
        include_once 'news.php';
        $news=new news($this->db,$this->lang);
        $str.=$news->news_list($this->news_search($this->db));
        $str.='
            </div>
        </div>
        </div>';
        return $str;
    }
}
?>