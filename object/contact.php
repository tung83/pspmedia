<?php
class contact{
    private $db,$view,$lang;
    function __construct($db,$view,$lang='vi'){
        $this->db=$db;
        $this->lang=$lang;
        $this->view=$view;
    }
    function contact_head(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'lienhe_banner.png" alt="Liên hệ" class="img_full" />
            </div>
        </div>
    ﻿    <div class="bk_lienhe">
            <div>
                <h3 class="white">'.contact.'</h3>
                <p class="white">'.contact_desc.'</p>

            </div>
        </div>';
        return $str;
    }
    function contact_under_head(){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',2)->orderBy('ind','ASC')->orderBy('id');
        $list=$this->db->get('for_footer_and_contact');
        $str='
        <div style="background: #F1F1F1">
            <div class="container" style="background: #F1F1F1">';
        foreach($list as $item){
            if ($this->lang == 'en') {
                $title=$item['e_title'];
                $content = $item['e_content'];
            } else {
                $title=$item['title'];
                $content = $item['content'];
            }
            $str.='
            <div class="col-sm-4 ">
                <p><span class="tp_">'.$title.'</span><p>
                <p>'.$content.'</p>
            </div>';
        }
        $str.='
            </div>
        </div>';
        return $str;
    }
    function contact_insert(){
        $this->db->reset();
        if(isset($_POST['contact_send'])){
            $name=htmlspecialchars($_POST['name']);
            $adds=htmlspecialchars($_POST['adds']);
            $phone=htmlspecialchars($_POST['phone']);
            $email=htmlspecialchars($_POST['email']);
            $subject=htmlspecialchars($_POST['subject']);
            $content=htmlspecialchars($_POST['content']);
            $insert=array(
                'name'=>$name,'adds'=>$adds,'phone'=>$phone,
                'email'=>$email,'fax'=>$subject,'content'=>$content,
                'dates'=>date("Y-m-d H:i:s")
            );
            try{
                //$this->send_mail($insert);
                $this->db->insert('contact',$insert);                
                //header('Location:'.$_SERVER['REQUEST_URI']);
                echo '<script>alert("Thông tin của bạn đã được gửi đi, BQT sẽ phản hồi sớm nhất có thể, Xin cám ơn!");
                    location.href="'.$_SERVER['REQUEST_URI'].'"
                </script>';
            }catch(Exception $e){
                echo $e->errorInfo();
            }
        }
    }
    function contact_content(){
        $this->contact_insert();
        $str='
        <div class="contact-form bk_white">
            <div class="container bk_white">
                <p class="ghichu1">
                    Hoặc vui lòng gởi thông tin liên hệ cho chúng tôi theo form dưới đây:

                </p>
                <div class="mess_error"><ul></ul></div>
                <form class="form" role="form" method="post" action=""  enctype="multipart/form-data" data-toggle="validator">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên (*)" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Điện thoại (*)" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email (*)" required/>
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="adds" name="adds" placeholder="Địa chỉ (*)" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <!--div class="form-group">
                        <input type="text" class="form-control" id="exampleInputPhone" name="phongban" placeholder="Phòng ban" value="">
                        <div class="help-block with-errors"></div>
                    </div-->
                    <p>
                        <span class="redmeko"> Chú ý : </span>

                        <span class="ghichu">
                            Dấu (*) các trường bắt buộc phải nhập vào. Quý vị có thể gõ chữ tiếng Việt không dấu hoặc chữ tiếng Việt có dấu theo font UNICODE (UTF-8).
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <textarea rows="13" class="form-control" style="padding: 5px ! important;" name="content" placeholder="Ghi Chú..." required></textarea>
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group">
                       <button type="submit" class="btn btn-primary" name="contact_send" value="Submit">'.contact_send.'</button>
                       <button type="reset" class="btn btn-primary" value="reset">'.contact_reset.'</button>
                    </div>
                </div>
                </form>
            </div>
        </div>';
        return $str;
    }
    function send_mail($item){
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        $mail->setFrom('info@quangdung.com.vn', 'Website administrator');
        //Set an alternative reply-to address
        $mail->addReplyTo($item['email'], $item['name']);
        //Set who the message is to be sent to
        $mail->addAddress('czanubis@gmail.com');
        //Set the subject line
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Contact sent from website';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->Body = '
        <html>
        <head>
        	<title>'.$mail->Subject.'</title>
        </head>
        <body>
        	<p>Full Name: '.$item['name'].'</p>
        	
        	<p>Address: '.$item['adds'].'</p>
        	<p>Phone: '.$item['phone'].'</p>
        	
        	<p>Email: '.$item['email'].'</p>
            <p>Tiêu Đề: '.$item['fax'].'</p>
        	<p>Content: '.nl2br($item['content']).'</p>
        </body>
        </html>
        ';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        
        //send the message, check for errors
        //$mail->send();
        if ($mail->send()) {
            echo "Message sent!";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
