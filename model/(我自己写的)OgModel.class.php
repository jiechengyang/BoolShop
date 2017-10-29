<?php
 /************
   YJC php 之路
 ************/
 /*
   OrderGoods = Og
   订单表与商品对象的表model
 */
 ##########
  defined('KEYS') || exit('jing zhi fang wen');
  class OgModel extends Model{
     protected $table = 'ordergoods';
	 protected $pk = 'og_id';
	 protected $fileds = array('og_id','order_id','order_sn','goods_id','goods_name','goods_number','shop_price','subtotal');
	 public function add_og($goods,$order_id,$order_sn){
		$sql = '';
		foreach($goods as $k=>$v){
			 for($i=0;$i<count($v);$i++){
				$arr[$i][$k] = $v[$i];
			 }
		}
		foreach($arr as $k=> $v){
		  $arr[$k]['order_id'] = $order_id;
		  $arr[$k]['order_sn'] = $order_sn;
		}
		  $flag = true; 
		foreach($arr as $v){
		    if(!$this->add($v) || !GoodsModel::setnumber($v['goods_number'],$v['goods_id'])){
			  $flag = false;
			}
		}
		return $flag;
	 }
  }
?>