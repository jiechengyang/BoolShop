<?php
 /************
   YJC php 之路
 ************/
 /*
   regAct.php controller
   注册处理页面
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');  
  $usermodel = new UserModel();
  if(!$usermodel->__autovalid($_POST)){//自动验证
      $msg = implode('<br/>',$usermodel->getError());
	  require_once(ROOT.'/view/front/msg.html');
	  exit;
	  
  }

  $data = $usermodel->facade($_POST);  //自动过滤
  $data = $usermodel->__autofill($data);//自动填充
  if($usermodel->checkUser($data['username'])){//如果存在是返回的是真
     $msg = '对不起，此用户已经存在';
	require_once(ROOT.'/view/front/msg.html');
	  exit;
  }
  if($data['passwd'] != $_POST['repasswd']){
     $msg = '两次输入密码不一致';
	 require_once(ROOT.'/view/front/msg.html');
	 exit;
  }
  if($usermodel->reg($data)){
    $msg = '注册成功';
    require_once(ROOT.'/view/front/msg.html');
  }
?>