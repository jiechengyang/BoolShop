<?php
 /*
  file goodstrash.php
  商品回收站
 */
  define('KEYS',true);
  require_once('../include/init.php');
  $goodsmodel = new GoodsModel();
  if(isset($_GET['act']) && $_GET['act'] == 'show'){
	  $paging = new paging();
	  $paging->pagenow = (isset($_GET['pagenow']))?($_GET['pagenow']):1;
	  $paging->url = $_SERVER['PHP_SELF'];
      $paging->where = ' where is_delete=1';
       $goodsmodel->FenYe($paging); 
	 require_once(ROOT.'/view/admin/templates/goodstrash.html');
  }else{
      if(empty($_GET['goods_id'])){
	     die('怎么回事');
	  }
	  $gid = $_GET['goods_id'] + 0;
	  if($goodsmodel->trash($gid) > 0){
	      $res = true;
	  }else{
	     $res = false;
	  }
	  include(ROOT.'/view/admin/templates/trashres .html');
  }
 
?>