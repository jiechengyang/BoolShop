<?php
 /************
   YJC php 之路
 ************/
 /*
   Oi = OrderInfo
   订单表model
 */
 ##########
  defined('KEYS') || exit('jing zhi fang wen');
  class OiModel extends Model{
     protected $table = 'orderinfo';
	 protected $pk = 'order_id';
	 protected $fileds = array('order_id','order_sn','zone','address','zipcode','reciver','email','tel','mobile','building','best_time','add_time' ,'order_amout','pay');
	 protected $auto = array(
		 array('add_time','function','time')
		 );
	 protected $validate = array(
				array('zone',1,'配送区域必须填','require'),
			    array('reciver',1,'收货人的姓名必须填','require'),
				array('address',1,'详细地址必须填','require'),
				array('email',1,'email必须填正确的邮箱','email'),
		        array('mobile',1,'手机号必须填','require'),
			    array('pay',1,'支付方式必须填','in','0,1')
		 );
	 //自动填充无重复订单号
	 public function auto_sn(){
	  $sn = 'OSN'.date('Ymd').mt_rand(1000,9999);
	  $sql = 'select count('. $this->pk.') from '.$this->table.' where order_sn = '."'".$sn."'";
	  if($this->db->getone($sql)){
	      return $this->auto_sn();
	  }else{
	      return $sn;
	   }
	 }
	 //下订单失败应该取消订单 也就是 删除刚刚入库的记录
	 public function oi_invate($id){
	   $this->delete($id);
	   return $this->db->affected_rows();
	 }
	 //修改订单状态
	 public function upd_pay($id){

	     $sql = 'update '.$this->table.' set is_pay = 1 where '.$this->pk.'= '.$id;
	     return $this->db->sql_query($sql);
	 }
  }
?>