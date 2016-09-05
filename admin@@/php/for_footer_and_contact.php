<?php
function mainProcess($db)
{
	return for_footer_and_contact($db);	
}
function for_footer_and_contact($db)
{
	$msg='';
    $act='for_footer_and_contact';
    $pId=intval($_GET['id']);
    $tip=$pId!=1?'Bài viết liên hệ':'Bài viết footer';
    $table='for_footer_and_contact';
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
        $content=str_replace("'","",$_POST['content']);
        $e_content=str_replace("'","",$_POST['e_content']);
        $active=$_POST['active']=="on"?1:0;
        $ind=intval($_POST['ind']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $form->img_remove(intval($item),$db,$table);
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
            'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
            'content'=>$content,'e_content'=>$e_content,
            'active'=>$active,'pId'=>$pId
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
            'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
            'content'=>$content,'e_content'=>$e_content,
            'active'=>$active
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
        try{
            $db->where('id',$_POST['idLoad']);
            $db->delete($table);        
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getErrorMessage();
        }
	}
    $page_head= array(
                    array('#',$tip)
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề( Vi / <b>En</b> )','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=$lim=ad_lim;
    $db->where('pId',$pId);
    $db->orderBy('id');
	$list=$db->paginate($table,$page);    
	$count= $db->totalCount;
	
    if($count!=0){
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $item_content = array(
                $item['title'].'<br/><b>'.$item['e_title'].'</b>',
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
    $pg->defaultUrl = "main.php?act=$act";
    $pg->paginationUrl = "main.php?act=$act&page=[p]";
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
                  
                    '.$form->ckeditor('content','Nội dung :').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tiêu đề').'
                    '.$form->ckeditor('e_content','Nội dung :').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->text('ind','Thứ tự').'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}

?>		