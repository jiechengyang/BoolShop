<?php

  /**
	file cateaddAct.php
	作用：首先我们先说明的是这是一个控制器
	       它负责调用Model与view
  **/
  #第一步：取钥匙 加载初始化页面
  define('KEYS',true);
  require_once('../include/init.php');
  #第二步验证数据
  /*echo '<pre>';
  print_r($_POST);
  echo '</pre>';*/
 if(empty($_POST['catename'])) {
		exit('栏目名称不能为空');
 }
 //对于$_POST['parent_id']) $_POST['intro']暂时不确定用什么规范来验证 所以先暂定
 //对于$_POST['mode'])可以用作判断  因为一个控制器可以处理增 改等操作
 #第三步接收数据
  $catdata['catename'] = $_POST['catename'];
  $catdata['intro'] = $_POST['intro'];
  $catdata['parent_id'] = $_POST['parent_id'] + 0;
  //$mode = $_POST['mode'];
 #第四步调用 cateModel
 $catmodel = new CateModel();
 if($catmodel->add($catdata)){
     $res = true;
 }else{
	$res = false;
 }
 #第五步将结果信息打印都view里
 require(ROOT.'/view/admin/templates/cateaddres.html');
?>