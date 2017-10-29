<?php
 /************
   YJC php 之路
 ************/
 /*
   goods.php 单个商品的详细页面
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
  $goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
  //echo "<pre>";print_r($_SESSION);exit;
  if(!$smarty->isCached('goods.html',$goods_id)){
	   $goodsmodel = new GoodsModel();
	  //取得某一商品的记录
	   $goods = $goodsmodel->find($goods_id);
	   if(empty($goods)){//有可能是有人随便在地址栏上输入的id 那么就会存在没有记录的情况，因此为空就退出
		   header('location:index.php');
		   exit;
	   }
	   //取得某一商品所在的栏目
		$catename = $goodsmodel->getCate($goods['cat_id']);
	   //面包屑导航
	   $catemodel = new CateModel();
	   $nav = array_reverse($catemodel->get_partree($goods['cat_id']));
	   /*浏览历史*/
		if(strrpos($_SERVER['QUERY_STRING'],'goods_id') !== false){
			$url = $_SERVER['QUERY_STRING'];
		 }
		 if(!isset($_COOKIE['his'])){
			$his[] = $url;
		 }else{
			$his = explode('|',$_COOKIE['his']);
			array_unshift($his,$url);
			$his = array_unique($his);
			if(count($his) > 2){
			   array_pop($his);
			}
		 }
		setcookie('his',implode('|',$his),time()+3600);
		$smarty->assign('nav',$nav);
	  $smarty->assign('goods',$goods);
	  $smarty->assign('catename',$catename);
	  $username =  isset($_SESSION['username'])?:'';
	 $smarty->assign('username',$username);
  }
  //require_once(ROOT.'/view/front/shangpin.html');
  $smarty->display('goods.html',$goods_id);
?>