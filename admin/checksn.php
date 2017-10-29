<?php
/*
ajax验证 商品的货号是否唯一
*/
define('KEYS',true);
require_once('../include/init.php');
$sn = $_POST['goods_sn'];
$sql = "select count(goods_id) from goods where goods_sn = '".$sn."'";
$mysqli = new objmysql();
if($mysqli->getone($sql)){
  echo 1;//表示有  返回1
}else{
  echo 0;
}
?>