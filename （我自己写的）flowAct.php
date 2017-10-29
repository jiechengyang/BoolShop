<?php
 /************
   YJC php 之路
 ************/
 /*
   flowAct.php 
   提交订单的处理页面
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
 /*echo '<pre>';
  print_r($_POST);
  echo '</pre>';*/
  $oi = new OiModel();
  $og = new OgModel();
  $cart =  CartTool::getCart();
  if(!$oi->__autovalid($_POST)){//自动验证
     $msg = reset($oi->getError());
	 require_once(ROOT.'/view/front/msg1.html');
	 exit;
  }
 //自动过滤 orderinfo 表
 $oilist = $oi->facade($_POST);
  /*echo '<pre>';
  print_r($oilist);
  echo '</pre>';*/
  //自动填充orderinfo 表
  $oilist = $oi->__autofill($oilist);
  //手动填充订单号orderinfo 表
  $oilist['order_sn'] = $oi->auto_sn();
  //把用户id和用户名入库
  $oilist['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
  $oilist['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';
  if(!$oi->add($oilist)){
      $msg = '订单写入失败';
	  require_once(ROOT.'/view/front/msg1.html');
	 exit;
  }
  $prev_id = $oi->getIns_id();
   /*echo '<pre>';
  print_r($oilist);
  echo '</pre>';
   var_dump($flag);
 /* echo '<pre>';
  print_r($oglist);
  echo '</pre>';*/
  //自动过滤 ordergoods 表
 $oglist = $og->facade($_POST);
   //购买成功后相应的goods表的库存应该减少
  
  if(!$og->add_og($oglist,$prev_id,$oilist['order_sn'])){
	  $msg = '订单写入失败';
	  require_once(ROOT.'/view/front/msg1.html');
	 exit;
 }
// 购买成功后，应该要在购物车里清空购物车
 $cart->clearItem();

 $msg = '订单成功';
 require_once(ROOT.'/view/front/msg1.html');

 
  