<?php
function mainProcess($db)
{
    $type=$_GET['type'];
    switch($type){
        case 'video_cate': return video_cate($db,$type);break;
        case 'video': return video($db,$type);break;
        default:return video_cate($db,$type);break;
    }
}
function video_cate($db,$type)
{
	$msg='';
    $act='video';
    $table='video_cate';
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
        $title=htmlspecialchars($_POST['title']);	   
        $e_title=htmlspecialchars($_POST['e_title']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $active=$_POST['active']=="on"?1:0;
        $icon=time().$_FILES['icon']['name'];
        $ind=intval($_POST['ind']);
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
                    'title'=>$title,'e_title'=>$e_title,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'ind'=>$ind,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
                );
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['icon'])){
                WideImage::load('icon')->resize(14,16, 'fill')->saveToFile(myPath.$icon);
                $db->where('id',$recent);
                $db->update($table,array('icon'=>$icon));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
        $update=array(
                    'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
                );
        if($form->file_chk($_FILES['icon'])){
            WideImage::load('icon')->resize(14,16, 'fill')->saveToFile(myPath.$icon);
            $update = array_merge($update,array('icon'=>$icon));
            $form->img_remove($_POST['idLoad'],$db,$table,'icon');
        }
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
                    array('#','Loại video clip')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Icon','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $item_content = array(
                $item['title'],
                '<img src="'.myPath.$item['icon'].'" class="img-thumbnail img-admin"/>',
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
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
    $pg->totalrecords = $db->totalCount;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act&type=$type";
    $pg->paginationUrl = "main.php?act=$act&type=$type&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title','Tiêu đề').'
                    '.$form->text('meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('meta_description','Description <code>SEO</code>').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tiêu đề').'
                    '.$form->text('e_meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('e_meta_description','Description <code>SEO</code>').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->number('ind','Thứ tự').'
            '.$form->file('icon','Icon <code>( 14 x 16 )</code>').'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
function video($db,$type)
{
	$msg='';
    $act='video';
    $table='video';
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
        $title=htmlspecialchars($_POST['title']);	   
        $e_title=htmlspecialchars($_POST['e_title']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $video=htmlspecialchars($_POST['video']);
        $active=$_POST['active']=="on"?1:0;
        $pId=intval($_POST['pId']);
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
            'title'=>$title,'e_title'=>$e_title,
            'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
            'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc,
            'active'=>$active,'video'=>$video,'pId'=>$pId
        );
		try{
            $recent = $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=mysql_error();
        }			
	}
	if(isset($_POST["update"]))	{
        $update=array(
            'title'=>$title,'e_title'=>$e_title,
            'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
            'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc,
            'active'=>$active,'video'=>$video,'pId'=>$pId
        );
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
    $page_head= array(
                    array('#','Danh sách video clip')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Loại','Hình ảnh','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $cate=$db->where('id',$item['pId'])->getOne('video_cate','title');
            $item_content = array(
                $item['title'],
                $cate['title'],
                '<img src="http://img.youtube.com/vi/'.$item['video'].'/3.jpg" class="img-thumbnail img-admin"/>',
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
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
    $pg->totalrecords = $db->totalCount;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act&type=$type";
    $pg->paginationUrl = "main.php?act=$act&type=$type&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title','Tiêu đề').'
                    '.$form->text('meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('meta_description','Description <code>SEO</code>').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tiêu đề').'
                    '.$form->text('e_meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('e_meta_description','Description <code>SEO</code>').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-6">
            '.$form->select_table('pId','Loại video clip:','video_cate',$db,true).'
            '.$form->text('video','Youtube Video<code>https://www.youtube.com/embed/<i style="color:#000">60g__iiYDPo</i></code>','',true).'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
?>