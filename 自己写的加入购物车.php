<?php
 /************
   YJC php 之路
 ************/
 /*
   addcart.php
   加入购物车
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
 //加入购物车
  if(!isset($_GET['goods_id']) || !isset($_GET['num'])){
      header('location:index.php');
	  exit;
  }
  $gid = $_GET['goods_id']+ 0;
  $num = $_GET['num'] + 0;
  $goodsmodel = new GoodsModel();
  $grow = $goodsmodel->find($gid);
  //加入购物车
$cart = CartTool::getCart();
$cart->addItem($grow['goods_id'],$grow['goods_name'],$grow['shop_price'],$num);
 $cartlist = $cart->getAll();
 //有缺陷---应该做一个防止页面刷新
if(empty($cartlist)){
  header('location:index.php');
	  exit;
}else{
  $msg = '成功加入购物车';
  require_once(ROOT.'/view/front/msg1.html');
}
?>