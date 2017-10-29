<?php
 /************
   YJC php 之路
 ************/
 /*
   logAct.php controller
   登录处理页面
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
  $usermodel = new UserModel();
  if(!$usermodel->__autovalid($_POST)){//自动验证
     $msg = implode('<br/>',$usermodel->geterror());
	 require_once(ROOT.'/view/front/msg.html');
	 exit;
  }
 
  $data = $usermodel->facade($_POST);
  if($usermodel->login($data['username'],$data['passwd'])){
      $msg = '登录成功';
	  if(isset($_POST['remember'])){
		setcookie('username',$_POST['username'],time()+3600);
	}else{
	   setcookie('username','',0);//不存在就把之前已有的销毁
	}
	 header('location:index.php');
  }else{
	$msg = '用户名密码不匹配';
	require_once(ROOT.'/view/front/msg.html');
  }
?>