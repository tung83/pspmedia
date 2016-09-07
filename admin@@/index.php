<?php include "../config.php";?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrator Panel - LCA</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="css/style.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<?php
if(isset($_SESSION["ad_user"]))
{
	header("location:main.php",true);	
}
global $msg;
$msg='';
if(isset($_POST["Sign"]))
{
	if(isset($_POST["Sign"]))
	{
		$email=$_POST["email"];
		$pwd=$_POST["password"];
        $sql="select email,pwd from ad_user where email='$email'";
		$rows=$db->rawQuery($sql);
		if($db->count==0)
		{
			$msg='Tài khoản này không tồn tại!';	
		}
		else
		{
			if(md5($pwd)!=$rows[0]['pwd'])
			{
				$msg='Mật khẩu không đúng!';	
			}
			else
			{
				$_SESSION["ad_user"]=$email;	
				mysql_query("update ad_user set lastOnl=now() where email='$email'");		
				header("location:main.php",true);	
			}
		}
	}
}
?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-uppercase">Đăng nhập hệ thống</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="">
                            <fieldset>
                                <div class="form-group">
							        <label for="name" class="control-label text-uppercase">Tên đăng nhập</label>
                                    <input class="form-control" type="text" id="name" placeholder="Nhập tên" name="name" autofocus>
                                </div>
                                <div class="form-group">
							        <label for="name" class="control-label text-uppercase">Mật khẩu</label>                                    
						            <div class="input-group">
                                        <input class="form-control" placeholder="Nhập mật khẩu" id="password" name="password" type="password" value="">
                                        <label for="password" class="input-group-addon glyphicon glyphicon-eye-open"></label>
                                    </div> 
                                </div>
                                <div class="form-group">
							        <label for="name" class="control-label text-uppercase">Ngôn ngữ</label>
                                    <select class="form-control" id="language" name="language">
                                        <option>Tiếng Việt</option>
                                        <option>Tiếng Anh</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="checkbox col-sm-6">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Lưu mật khẩu
                                        </label>                               
                                    </div>   
                                    <div class="col-sm-6 help-block text-right"><a href="">Quên mật khẩu?</a></div>                                        
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="Sign" value="Đăng nhập"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
	if($msg!='')
	{
		echo '<div class="alert alert-danger" role="alert" style="margin-top:10px"><b>Thông báo!</b> '.$msg.'</div>';	
	}
	?>
    </div>

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
