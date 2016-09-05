<?php
class cart{
    private $cookie;
    public function __construct($db,$table){
        $this->db=$db;
        $this->table=$table;
        if(!isset($_COOKIE['cart'])) $_COOKIE['cart']=array();
        $this->cookie=$_COOKIE['cart'];    
    }
    private function set_cookie($res){
        $_COOKIE['cart']=$this->cookie=$res;
    }
    function cart_add($id,$qty){
        $k=0;
        $tmp=array();        
        foreach($this->cookie as $item){
            if($item['id']==$id){
                $qty=$item['qty']+$qty;
                array_push($tmp,array('id'=>$item['id'],'qty'=>$qty));        
                $k++;  
            }else{
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty'])); 
            }
        }
        if($k==0){            
            array_push($tmp,array('id'=>$id,'qty'=>$qty));
        }
        $this->set_cookie($tmp);
        return $this;
    }
    function cart_remove($id){
        $tmp=array();
        foreach($this->cookie as $item){
            if($item['id']==$id){
                continue;
            }else{
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty']));
            }
        }    
        $this->set_cookie($tmp);
        return $this;
    }
    function cart_update($id,$qty){
        $tmp=array();
        foreach($this->cookie as $item){
            if($item['id']==$id){
                array_push($tmp,array('id'=>$item['id'],'qty'=>$qty));
            } else {
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty']));
            }
        }
        $this->set_cookie($tmp);
        return $this;
    }
    function cart_update_multi($arr=array()){
        foreach($arr as $cart){
            $this->cart_update($cart['id'],$cart['qty']);
        }    
        return $this;
    }
    function cart_output(){
        var_dump($this->cookie);
    }
}
?>