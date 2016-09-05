<?php
class  form{
    private $lang;
    public function __construct($lang){
        $this->lang=$lang;
    }

    private function required( $required ) {
		return $required ? ' required' : '';
	}

	private function disabled( $disabled ) {
		return $disabled ? ' disabled' : '';
	}
    function input($type, $name, $label, $desc='', $required = FALSE, $disabled = FALSE){
        $str='
        <div class="form-group">
            <label for="'.$name.'">'.$label.' :</label>';
        $moreOption='';
        if($type!='file') $moreOption=' value="'.$this->get($name).'"';  
        $str.=' 
        <input type="'.$type.'" name="'.$name.'" id="'.$name.'"'.$moreOption.''.$this->required($required).$this->disabled($disabled).' class="form-control" />
        ';
        $str.='
            <div class="help-block with-errors">'.$desc.'</div>
        </div>
        ';
        return $str;
    }
    public function file($name,$label,$desc='',$required=false,$disabled=false){
        return $this->input('file',$name,$label,$desc,$required,$disabled);
    }
	public function text( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
	   return $this->input('text', $name, $label , $desc, $required, $disabled);
	}

	public function number( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
	   return $this->input('number', $name, $label, $desc, $required, $disabled);
	}

	public function email( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
		return $this->input('email', $name, $label, $desc, $required, $disabled);
	}

	public function password( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
		return $this->input('password', $name, $label, $desc, $required, $disabled);
	}
    public function confirm_password($name,$name_match,$label,$desc,$required=false,$disable=false){
        $str='
        <div class="form-group">
            <label for="'.$name.'">'.$label.' :</label>
            <input type="password" data-match="#'.$name_match.'" name="'.$name.'" id="'.$name.'" value="'.$this->get($name).'"'.$this->required($required).$this->disabled($disabled).' class="form-control" data-match-error="Xác nhận mật khẩu không đúng.">
            <div class="help-block with-errors">'.$desc.'</div>
        </div>
        ';
        return $str;
    }
	public function textarea( $name, $label ,$desc='' ) {
		$str.='
		<div class="form-group">
			<label for="'.$name.'">'.$label.' :</label>
			<textarea name="'.$name.'" id="'.$name.'" class="form-control">'.$this->get($name).'</textarea>
            <div class="help-block with-errors">'.$desc.'</div>
		</div>
		';
        return $str;
	}
    function ckeditor($name,$label,$desc=''){
        $str='
        <div class="form-group">
    		<label>'.$label.' :</label>
            <textarea name='.$name.' id='.$name.' class="ckeditor">'.$this->get($name).'</textarea>
            '.$desc.'
        </div>';
        return $str;
    }
    function checkbox($name,$label,$checked=false){
        $active = '';
        if($this->get($name)==1||($this->get($name)===''&&$checked==true)) $active = "checked='checked'";
        $str.='
        <div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" name="'.$name.'" '.$active.'>'.$label.'
			</label>
		</div>';
        return $str;	
    }
    function breadcumb($param){
        $str='
        <!-- Page Heading -->
    	<div class="row">
    		<div class="col-lg-12">
        <ol class="breadcrumb">';
        for($i=0;$i<count($param);$i++)
        {
            if(($i+1)==count($param)) $active='active';
            else $active='';
            $str.='
            <li class="'.$active.'">
      		    '.($i==0?'<i class="fa fa-dashboard"></i>':'').' <a href="'.$param[$i][0].'">'.$param[$i][1].'</a>
        	</li>';
        }
        $str.='</ol>
            </div>
    	</div>';
        return $str;
    }
    function file_chk($file,$type='image'){
        if($file['error']>0) return false;
		else if(strstr($file['type'],$type)!=NULL) return true;
		else return false;
    }
    function category_group($db){
        $id_lev_2=$this->get('pId');        
        $table='category';
        if(intval($id_lev_2)!=0){
            $db->where('id',$id_lev_2);
            $id_lev_1 = $db->getOne($table,'pId');
            $id_lev_1 = $id_lev_1['pId'];                
        } else {
            $id_lev_1 = 0;
        }
        $str='
        <div class="form-group">
            <label>Danh mục :</label>
        
        ';
        $param=array(
            'table' => 'category',
            'lev'=>1,
            'name'=>'frm_cate_1',
            'id'=>intval($id_lev_1),
            'control'=>'frm_cate_2'
        );
        $str.=$this->cate_select($db,$param,true);
        $param=array(
            'table' => 'category',
            'lev'=>2,
            'name'=>'frm_cate_2',
            'pId'=>$id_lev_1,
            'id'=>$id_lev_2
        );
        $str.=$this->cate_select($db,$param,true);
        $str.='</div>';
        return $str;
    }
    function cate_select($db,$param,$required=false){
        if(array_key_exists('id',$param)) $id=$param['id']; else $id=0;
        if($param['lev']>1){
            $pId=array_key_exists('pId',$param)?intval($param['pId']):0;
            $db->where('pId',$param['pId']);    
        }             
        $db->where('lev',$param['lev'])->where('active',1);
        $list=$db->get($param['table'],null,'id,title');        
        $str='
        
            <select'.$this->required($required).' class="form-control" name="'.$param['name'].'" id="'.$param['name'].'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        ';
        if($param['control']!=''){
            $str.='
            <script>
            $(\'#'.$param['name'].'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "ajax.php",
                  data: { act: "category", table: "'.$param['table'].'", lev: '.intval($param['lev']+1).', pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#'.$param['control'].'\').html(msg);
                });
            });
            </script>
            ';    
        }
        return $str;
    }
    function select($name,$label,$list=array(),$required=false){
        $id=intval($this->get($name));
        $str='
        <div class="form-group">
            <label>'.$label.'</label>
            <select'.$this->required($required).' class="form-control" name="'.$name.'" id="'.$name.'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        </div>
        ';  
        return $str;
    }
    function select_table($name,$label,$table,$db,$required=false){
        $id=intval($this->get($name));
        $list=$db->where('active',1)->orderBy('id')->get($table,null,'id,title');
        $str='
        <div class="form-group">
            <label>'.$label.'</label>
            <select'.$this->required($required).' class="form-control" name="'.$name.'" id="'.$name.'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        </div>
        ';  
        return $str;
    }
    
    function location($db){
        $district=$this->get('district');        
        $table='quanhuyen';
        if(intval($district)!=0){
            $db->where('id',$district);
            $city = $db->getOne($table,'pId');
            $city = $city['pId'];                
        } else {
            $city = 0;
        }
        $str='
        <div class="form-group">
            <label>Địa điểm :</label>
        
        ';
        $param=array(
            'lev'=>1,
            'table' => 'tinhthanh',
            'name'=>'city',
            'id'=>intval($city),
            'control'=>'district',
            'control_table'=>'quanhuyen'
        );
        $str.=$this->location_select($db,$param,true);
        $param=array(
            'lev'=>2,
            'table' => 'quanhuyen',
            'name'=>'district',
            'pId'=>$city,
            'id'=>$district
        );
        $str.=$this->location_select($db,$param,true);
        $str.='</div>';
        return $str;
    }
    function location_select($db,$param,$required=false){
        if(array_key_exists('id',$param)) $id=$param['id']; else $id=0;
        if($param['lev']>1){
            $pId=array_key_exists('pId',$param)?intval($param['pId']):0;
            $db->where('pId',$pId);  
        }             
        $db->orderBy('title','ASC');
        $list=$db->get($param['table'],null,'id,title');        
        $str='
        
            <select'.$this->required($required).' class="form-control" name="'.$param['name'].'" id="'.$param['name'].'" style="text-align-last:center">
            <option value="">'.$param['desc'].'</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
        ';
        if($param['control']!=''){
            $str.='
            <script>
            $(\'#'.$param['name'].'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "/'.phpLib.'ajax.php",
                  data: {   act: "location", table: "'.$param['control_table'].'", desc: "'.$param['control_desc'].'" ,
                            pId: $(this).val(), lang: "'.$this->lang.'" }
                }).done(function( msg ) {
                    $(\'#'.$param['control'].'\').html(msg);
                });
            });
            </script>
            ';    
        }
        return $str;
    }
    
    function get_options($db,$table,$first='<option value="">-----o0o-----</option>',$id=0){
        $str=$first;
        $db->where('active',1);
        $list=$db->get($table,null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='vi') $title=$item['title'];
            else $title=$item['e_title'];
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$title.'</option>';
        }
        return $str;
    }
}
?>