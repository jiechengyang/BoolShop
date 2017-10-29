<?php
 /************
   YJC php 之路
 ************/
 /*
   pay.php
   支付页面
 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
  $act = isset($_GET['act'])?$_GET['act']:'buy';
  //echo '<pre>';print_r($_GET);exit;
  //print_r($act);exit;
   $cart = CartTool::getCart();//实例化购物车
     $goodsmodel = new GoodsModel();
  if($act == 'buy'){//将购买的商品加入购物车
      $gid = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;//不存在就是0
	  $num = isset($_GET['num'])?$_GET['num']+0:1;
	  if($gid){//有这个id才说加入
		  $glist = $goodsmodel->find($gid);
		  if(!empty($glist)){//如果有个这商品在加入
			  if($glist['is_on_sale'] == 0 || $glist['is_delete'] == 1){
					$msg = '该商品不能购买';
				   require_once(ROOT.'/view/front/msg1.html');
				   exit;
			  }
		      $cart->addItem($gid,$glist['goods_name'],$glist['shop_price'],$num);
			  $item = $cart->getAll();
		     if($item[$gid]['bnum'] > $glist['goods_number']){
			       $cart->delItem($gid);//取消这次的购买
				   $msg = '库存不足';
				   require_once(ROOT.'/view/front/msg1.html');
				   exit;
			 }
		  }
	  }
	   $item = $cart->getAll();
	   if(empty($item)){//如果购物车是空的，就回到首页
	      header('location:index.php');
		  exit;
	   }
	  //取出购物车商品的详细信息
	  $items = $goodsmodel->getCartGoods($item);
	  //购物车总价
	  $psum = $cart->getGprice();
	  //市场价
	  $msum = 0.0;
	  foreach($items as $v){
	    $msum += $v['market_price']*$v['bnum'];
	  }
	  //节约的钱数
	  $economy = $msum-$psum;
	  //节约比例
	  $ey = round(100*$psum/$msum,2);
	  require_once(ROOT.'/view/front/jiesuan.html');
  }else if($act == 'clear'){
      $cart->clearItem();
	   $msg = '购物车已经清空';
	    require_once(ROOT.'/view/front/msg1.html');
				   exit;
  }else if($act == 'del'){
	  $gid = isset($_GET['gid'])?$_GET['gid']:0;
	  if($gid){
		$cart->delItem($gid);
		$msg = '删除成功';
		require_once(ROOT.'/view/front/msg1.html');
	  }
     
  }else if($act == 'tijiao'){
	   $item = $cart->getAll();
	   if(empty($item)){//如果购物车是空的，就回到首页
	      header('location:index.php');
		  exit;
	   }
	  //取出购物车商品的详细信息
	  $items = $goodsmodel->getCartGoods($item);
	  //购物车总价
	  $psum = $cart->getGprice();
	  //市场价
	  $msum = 0.0;
	  foreach($items as $v){
	    $msum += $v['market_price']*$v['bnum'];
	  }
	  //节约的钱数
	  $economy = $msum-$psum;
	  //节约比例
	  $ey = round(100*$psum/$msum,2);
     require_once(ROOT.'/view/front/tijiao.html');

  }

?>