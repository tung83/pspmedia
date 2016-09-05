<?php
class about{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->lang=$lang;
        $db->where('id',2);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
    }
    function ind_about($db){
      $db->where('active',1);
      $db->orderBy('ind','ASC')->orderBy('id','DESC');
      $item=$db->getOne('about');
      if($this->lang=='vi'){
          $title=$item['title'];
          $sum=$item['sum'];
      }else{
          $title=$item['e_title'];
          $sum=$item['e_sum'];
      }
      $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
      $str='
      <div class="container gt_content_in">
          <div class="col-md-6 ">
               <a href="'.$lnk.'">
                  <img src="'.webPath.$item['img'].'" class="img-responsive" />
              </a>
          </div>
          <div class="col-md-6 ">
              <a href="'.$lnk.'">
              <h3 style="text-transform: uppercase; color:red;">'.$title.'</h3></a>
              <p>'.nl2br($sum).'</p>
          </div>
      </div>
      ';
      return $str;
    }
    function about_all($db){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1);
        $db->orderBy('ind','ASC');
        $db->orderBy('id');
        $db->pageLimit=10;
        $list=$db->paginate('about',$page);
        $count=$db->totalCount;
        $str='
        <div class="container">';
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
            $str.='
            <div class="col-md-12 list_gioithieu" style="padding-left:0px">';
            if($i%2==1){
                $str.='
                <div class="col-sm-5">
                    <p>
                        <a href="'.$lnk.'">
                           <img src="'.webPath.$item['img'].'" class="img-responsive" />
                        </a>
                    </p>
                </div>
                <div class="col-sm-7 text-right">
                    <p>
                        <a class="TileBaoHanh" title="'.$title.'" href="'.$lnk.'">'.$title.'</a>
                            '.nl2br($sum).'
                        <span>
                            <!--a href="'.$lnk.'" class="redmeko">'.more.'</a-->
                        </span>
                    </p>
                </div>';

            }else{
                $str.='
                <div class="col-sm-7 ">
                    <p>
                        <a class="TileBaoHanh" title="'.$title.'" href="'.$lnk.'">'.$title.'</a>
                            '.nl2br($sum).'
                        <span>
                            <!--a href="'.$lnk.'" class="redmeko">'.more.'</a-->
                        </span>
                    </p>
                </div>
                <div class="col-sm-5 ">
                    <p>
                        <a href="'.$lnk.'">
                           <img src="'.webPath.$item['img'].'" class="img-responsive" />
                        </a>
                    </p>
                </div>';
            }
            $str.='
            </div>';
            $i++;
        }
        $str.='</div>';
        return $str;
    }
    function about_head(){
        $str='
        <div class="slider" style="">
            <div class="img-responsive">
                        <img src="'.selfPath.'gioithieubanner.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>
    ﻿   <div class="bk_baoHanh1 gioithieu_1">
            <div>
                <h3 class="white" style="text-transform:uppercase">'.about_title.'</h3>
            </div>
        </div>';
        return $str;
    }
    function about_one($db){
        $id=intval($_GET['id']);
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1)->where('id',$id,'<>');
        $db->orderBy('id');
        $db->pageLimit=10;
        $list=$db->paginate('about',$page);
        $count=$db->totalCount;
        $str='
        <div class="container">
            <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            if($item['id']==$pId) $cls=' active';else $cls='';
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <div class="dropbtn tintuc'.$cls.'">
                <span class="caret-2">
                </span>
                <span class="title">
                    <a href="'.$lnk.'">'.$title.'</a>
                </span>
            </div>
            ';
        }
        $str.='
                </div>
            </div>
            </div>';
        $db->where('id',$id);
        $item=$db->getOne('about','title,e_title,content,e_content');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $content=$item['e_content'];
        }else{
            $title=$item['title'];
            $content=$item['content'];
        }
        $str.='
            <div class="col-md-8">
                <h3 style="text-transform:uppercase" class="art_title">'.$title.'</h3>
                <p>
                    '.$content.'
                </p>
            </div>
        </div>';
        return $str;
    }
}


?>
