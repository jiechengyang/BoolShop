<?php
 /*
   file goods_show.php 商品的详情页面
 */
 define('KEYS',true);
 require_once('../include/init.php');
 if(empty($_GET['goods_id'])){
    die('you wen  ti');
 }
 $gid =  $_GET['goods_id'] + 0;
 $goodmodel = new GoodsModel();
 $rowone = $goodmodel->find($gid);
 echo '<pre>';
 print_r($rowone);
 echo '</pre>';
 //调用model
?>