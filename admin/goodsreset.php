<?php
 /*
   file goodsreset.php
   还原商品
 */
  define('KEYS',true);
  require_once('../include/init.php');
  if(empty($_GET['goods_id'])){
     die('dao lian ');
  }
  $gid = $_GET['goods_id'] + 0;
  $goodsmodel = new GoodsModel();
  if($goodsmodel->resets($gid)){
    $res = true;
  }else{
     $res = false;
  }
  require_once(ROOT.'/view/admin/templates/resetres.html');
?>