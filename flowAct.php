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
  //自动填充orderinfo 表
  $oilist = $oi->__autofill($oilist);
  //手动填充订单号orderinfo 表
  $order_sn = $oilist['order_sn'] = $oi->auto_sn();
  //从购物车得到总钱数
  $total = $oilist['order_amount'] = $cart->getGprice();
  //把用户id和用户名入库
  $oilist['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
  $oilist['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';
  if(!$oi->add($oilist)){
      $msg = '订单写入失败';//这个时候都没入库，因此不用撤销订单
	  $oi->oi_invate();
	  require_once(ROOT.'/view/front/msg1.html');
	 exit;
  }
  $prev_id = $oi->getIns_id();
  //取得购物车里所有的物品
  /*
  [6] => Array
        (
            [gid] => 6
            [gname] => 男士短袖衬衫A52D/纯白暗竖纹/莫代尔棉
            [gprice] => 45.00
            [bnum] => 1
        )
  */
  $items  = $cart->getAll();
  $cnt = 0 ;//用来记录没一件商品都成功入库
  foreach($items as $k=>$it){
     $data = array();
	 $data['order_id'] = $prev_id;
	 $data['order_sn'] = $order_sn;
	 $data['goods_id'] = $k;
	 $data['goods_name'] = $it['gname'];
	 $data['shop_price'] = $it['gprice'];
	 $data['goods_number'] = $it['bnum'];
	 $data['subtotal'] = $it['bnum']*$it['gprice'];
	 if($og->add_og($data)){
	   $cnt++;
	 }
  }
  if(count($items) !== $cnt){
      $msg = '订单写入失败';
	  if($oi->oi_invate($prev_id) && $og->og_invate($prev_id))
		  require_once(ROOT.'/view/front/msg1.html');
		  exit;
  }
// 购买成功后，应该要在购物车里清空购物车
 $cart->clearItem();
//购买成功后，应该修改订单的状态值  0 该订单未支付 1 该订单已支付
 $oi->upd_pay($prev_id);
 $msg = '订单成功';
 require_once(ROOT.'/view/front/order.html');

 
  