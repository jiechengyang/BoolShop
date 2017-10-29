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
	 public function add_og($data){//把一个订单里的商品加入到ordergoods表里，同时应该减少商品表里的库存
	   if($this->add($data)){//一个订单里的商品加入到ordergoods表
		   $sql = 'update goods set goods_number = goods_number - '.$data['goods_number'].' where goods_id = '.$data['goods_id'];
		   /*
			  这个写法是违背了mvc开发模式的，因为不好在一个表的操作model里用一个表
			  因此可以使用我自己写的setnumber
		   */
		   $this->db->sql_query($sql);//减少商品表里的库存
		   return $this->db->affected_rows();
	   }
	   return false;
	 }
	//下订单失败应该取消订单 也就是 删除刚刚入库的记录
	public function og_invate($id){
	   $sql = 'delete from '.$this->table.' where order_id = '.$id;
	   return $this->db->affected_rows();
	}
  }
?>