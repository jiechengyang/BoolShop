<?php
  /*
    file goodslist.php
    controller 商品列表
  */
  define('KEYS',true);
  require_once('../include/init.php');
  $goodsmodel = new GoodsModel();
  //$goodslist = $goodsmodel->select();
  $paging = new paging();
  $paging->pagenow = (isset($_GET['pagenow']))?($_GET['pagenow']):1;
  $paging->url = $_SERVER['PHP_SELF'];
  $paging->where = ' where is_delete=0';
  $goodsmodel->FenYe($paging); 
  require_once(ROOT.'/view/admin/templates/goodslist.html');
?>