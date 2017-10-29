<?php
 /*
    file index.php
   所有用户访问的页面都必须通过init.php页面的加载
*/
  define('KEYS',true);
  require_once('./include/init.php'); 
  if(!$smarty->isCached('index_1.html')){
	  $goodsmodel = new GoodsModel();
	  $newlist = $goodsmodel->getNew();
	 /* print_r($newlist);
	  exit;*/
	  $cate_id = 4;//取出女装
	  $womanlist = $goodsmodel->getClothing($cate_id );
	 /* print_r($womanlist);
	  exit;*/
	  $cate_id = 1;
	  $manlist = $goodsmodel->getClothing($cate_id);
	   /*print_r($manlist);
	  exit;*/
	 //require_once(ROOT.'/view/front/index.html');

	 $smarty->assign('newlist',$newlist);
	 $smarty->assign('womanlist',$womanlist);
	 $smarty->assign('manlist',$manlist);
  }
 $smarty->display('index_1.html');
  
 
 