<?php
function mainProcess($db)
{
    if(isset($_GET['pdId'])) return product_image($db);
    else return product($db);
}
function product($db){
    $msg='';
    $act='product';
    $table='product';
    $hint=htmlspecialchars($_GET['hint']);
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->get($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $pId=intval($_POST['frm_cate_2']);
        $title=htmlspecialchars($_POST['title']);
        $video=htmlspecialchars($_POST['video']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $feature=str_replace("'","",$_POST['feature']);
        $content=str_replace("'","",$_POST['content']);
        $detail=str_replace("'","",$_POST['detail']);
        $teach=str_replace("'","",$_POST['teach']);
        $lnk=htmlspecialchars($_POST['lnk']);
        $e_lnk=htmlspecialchars($_POST['e_lnk']);
        $e_title=htmlspecialchars($_POST['e_title']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $e_feature=str_replace("'","",$_POST['e_feature']);
        $e_content=str_replace("'","",$_POST['e_content']);
        $e_detail=str_replace("'","",$_POST['e_detail']);
        $e_teach=str_replace("'","",$_POST['e_teach']);
        $price=intval($_POST['price']);
        $ind=intval($_POST['ind']);
        $price_reduce=intval($_POST['price_reduce']);
        $in_stock=$_POST['in_stock']=="on"?1:0;
        $active=$_POST['active']=="on"?1:0;
        $home=$_POST['home']=='on'?1:0;
        if(count($_POST['product_option'])>0){
            $product_option=array_unique($_POST['product_option']);
            $product_option=implode(',',$product_option);    
        }else $product_option='';
        
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=mysql_error();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'feature'=>$feature,'detail'=>$detail,
                    'content'=>$content,'teach'=>$teach,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'lnk'=>$lnk,'e_lnk'=>$e_lnk,
                    'e_title'=>$e_title,'e_feature'=>$e_feature,'e_detail'=>$e_detail,
                    'e_content'=>$e_content,'e_teach'=>$e_teach,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc,
                    'pd_option'=>$product_option,'ind'=>$ind,
                    'pId'=>$pId,'video'=>$video,
                    'price_reduce'=>$price_reduce,'home'=>$home,
                    'active'=>$active,'price'=>$price,'in_stock'=>$in_stock
                );
		try{
            $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'feature'=>$feature,'detail'=>$detail,
                    'content'=>$content,'teach'=>$teach,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'lnk'=>$lnk,'e_lnk'=>$e_lnk,
                    'e_title'=>$e_title,'e_feature'=>$e_feature,'e_detail'=>$e_detail,
                    'e_content'=>$e_content,'e_teach'=>$e_teach,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc,
                    'pd_option'=>$product_option,'ind'=>$ind,
                    'pId'=>$pId,'video'=>$video,
                    'price_reduce'=>$price_reduce,'home'=>$home,
                    'active'=>$active,'price'=>$price,'in_stock'=>$in_stock
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}

    $page_head= array(
                    array('#','Danh sách SP')
                );

	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);

    $str.=$form->search_area($db,$act,$hint);

    $head_title=array('Tiêu đề','Đơn giá / KM(VNĐ)','Danh mục','Tính năng','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);

    if(intval($_GET['cate_lev_2'])!=0) $db->where('pId',intval($_GET['cate_lev_2']));
    else if(intval($_GET['cate_lev_1'])!=0) {
        $db_tmp=$db;
        $db_tmp->where('lev',2)->where('pId',intval($_GET['cate_lev_1']));
        $list=$db_tmp->get('category',null,'id');
        foreach($list as $item){
            $list_tmp[]=$item['id'];
        }
        $db->where('pId',$list_tmp,'in');
    }
    if(trim($hint)!='') $db->where('title','%'.$hint.'%','like');
	  $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);
    $count=$db->totalCount;
    if($db->count!=0){
        $db_sub=$db;
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $db_sub->where('id',$item['pId']);
            $cate_sub=$db_sub->getOne('category','pId,title');
            $db_sub->where('id',$cate_sub['pId']);
            $cate=$db_sub->getOne('category',null,'title');
            if($item['pd_option']!=''){
                $option=explode(',',$item['pd_option']);
            }else{
                $option=array();   
            }            
            $opt_list='';
            if(count($option)>0){
                foreach((array)$option as $opt){
                    $opt_item=$db->where('id',$opt)->getOne('product_option','icon');
                    $opt_list.='<img src="'.myPath.$opt_item['icon'].'" class=""/> ';
                }   
            }           
            $item_content = array(
                $item['title'],
                number_format($item['price'],0,'.',',').' / '.number_format($item['price_reduce'],0,'.',','),
                '<dl><dt><a>'.$cate['title'].'</a></dt><dd><a><i class="glyphicon glyphicon-forward"></i>'.$cate_sub['title'].'</a></dd></dl>',
                $opt_list,
                $item['ind'],
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $addition=array(
                array('direction'=>'main.php?act='.$act.'&pdId='.$item_id,'icon'=>'upload')
            );
            $str.=$form->table_body($item_id,$item_content,$change,$_SERVER['REQUEST_URI'],$addition);
        }
    }
	$str.='
					</tbody>
				</table>
				</div>';

    $str.=$form->del_list();
    $pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = ad_lim;
    $pg->totalrecords = $count;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act";
    $pg->paginationUrl = "main.php?act=$act&page=[p]";
    $str.= $pg->process();
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" class="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title','Tên SP').'
                    '.$form->text('lnk','Liên Kết').'
                    '.$form->text('meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('meta_description','Description <code>SEO</code>').'
                    '.$form->ckeditor('feature','Nổi bật').'
                    '.$form->ckeditor('content','Mô tả').'
                    '.$form->ckeditor('detail','Thông số kỹ thuật').'
                    '.$form->ckeditor('teach','Hướng dẫn sử dụng').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tên SP').'
                     '.$form->text('e_lnk','Liên Kết').'
                    '.$form->text('e_meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('e_meta_description','Description <code>SEO</code>').'
                    '.$form->ckeditor('e_feature','Nổi bật').'
                    '.$form->ckeditor('e_content','Mô tả').'
                    '.$form->ckeditor('e_detail','Thông số kỹ thuật').'
                    '.$form->ckeditor('e_teach','Hướng dẫn sử dụng').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-6">
            '.$form->text('video','Youtube Video<code>https://www.youtube.com/embed/<i style="color:#000">60g__iiYDPo</i></code>').'
            '.$form->number('price','Đơn giá<code> VNĐ </code>','',true).'
            '.$form->number('price_reduce','Giá khuyến mãi<code> VNĐ </code>').'
            '.$form->number('ind','Thứ tự').'
            '.$form->checkbox('active','Hiển Thị','',true).'
            '.$form->checkbox('home','Trang chủ','',true).'
            '.$form->checkbox('in_stock','Còn Hàng',true).'
		</div>
        <div class="col-lg-6">
            '.$form->category_group($db).'
            '.$form->product_option($db).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
function product_image($db){
    $msg='';
    $act='product';
    $table='product_image';
    $pId=intval($_GET['pdId']);
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->get($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=mysql_error();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array('ind'=>$ind,'active'=>$active,'pId'=>$pId);
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['file'])){
                WideImage::load('file')->resize(800, 600, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array('ind'=>$ind,'active'=>$active);
       if($form->file_chk($_FILES['file'])){
            WideImage::load('file')->resize(800, 600, 'fill')->saveToFile(myPath.$file);
            $update = array_merge($update,array('img'=>$file));
            $db->where('id',$_POST['idLoad']);
            $last_img = $db->getOne($table,'img');
            if($last_img['img']!='') unlink(myPath.$last_img['img']);
        }
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg = $e->getErrorMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
    $db->where('id',$pId);
    $pd=$db->getOne('product','id,title,pId');
    $db->where('id',$pd['pId']);
    $cate_sub=$db->getOne('category','id,title,pId');
    $db->where('id',$cate_sub['pId']);
    $cate=$db->getOne('category','id,title');
    $page_head= array(
                    array('main.php?act='.$act,'Danh mục SP'),
                    array('main.php?act='.$act,$cate['title']),
                    array('main.php?act='.$act,$cate_sub['title']),
                    array('#',$pd['title'])
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Hình ảnh','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);
	$s="select * from $table where pId=$pId";
	$list = $db->rawQuery($s);
	$count= $db->count;
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$list = $db->rawQuery($s);
    if($db->count!=0){
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $item_content = array(
                '<img src="'.myPath.$item['img'].'" class="img-thumbnail" style="max-height:100px"/>',
                $item['ind'],
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $str.=$form->table_body($item_id,$item_content,$change,$_SERVER['REQUEST_URI']);
        }
    }
	$str.='
					</tbody>
				</table>
				</div>';
    $str.=$form->del_list();
    $pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = $lim;
    $pg->totalrecords = $count;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act&pdId=$pId";
    $pg->paginationUrl = "main.php?act=$act&pdId=$pId&page=[p]";
    $str.= $pg->process();
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->file('file','Hình ảnh <code>( 800 x 600 )</code>').'
            '.$form->number('ind','Thứ tự','',true).'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
?>
