<?php
 /************
   YJC php 之路
 ************/
 /*
   CartTool.class.php 
   购物车类
   满足购物车的条件：1、全局有效 保证在每个能看购物车的页面都可以看到自己的购物车
				     2、既然是全局有效，暗示着购物车的实例只能是3个，不能说3个页面，买了买了三个商品，就形成了3个购物车实例，
						这显然是不合理的-------------解决之道：单例模式
   技术选型：session+单例
   功能分析：
     判断某个商品是否存在
	 添加商品
	 删除商品
	 修改商品的数量

	 某商品数量加1
	 某商品数量减1

	 查询购物车的商品种类数目
	 查询购物车的商品数量
	 查询购物车的商品总金额
	 返回购物车的所有商品
 */
 ##########
defined('KEYS') || exit('jing zhi fang wen');
  class CartTool{
    private static $ins = null;//存储自身的实例
	protected $item = array();//使用数组来存储商品（商品号 商品名  商品价格 购买商品的数量）
	protected $sn = 0;
	 protected function __construct(){
		$this->sn = mt_rand(1,10000);
	}
	final protected function __clone(){
	}
	//开个口 单例
	protected static function getIns(){
		if(!(self :: $ins instanceof self)){
		    self::$ins =  new self();
		}
		return self::$ins;
	}
	//存取session里的购物车实例
	public static function getCart(){
	    if ((!isset($_SESSION['cart'])) || (!$_SESSION['cart'] instanceof self)){
		    $_SESSION['cart'] = self::getIns();
		}
		return $_SESSION['cart'];
	}
	//添加商品
	public function addItem($gid,$gname,$gprice,$bnum=1){
		if($this->is_Gexist($gid)){
		  $this->item[$gid]['bnum'] += $bnum;
		}else{
		  $this->item[$gid] = array('gid'=>$gid,'gname'=>$gname,'gprice'=>$gprice,'bnum'=>$bnum);
		}
	}
	//清空商品
	public function clearItem(){
	  $this->item = array();
	}
	//删除某件商品
	public function delItem($id){
	 if(!$this->is_Gexist($id)){
	     return ;
	 }
	   unset($this->item[$id]);
	}
	
	// 某商品数量加1
	public function add_gone($id){
	    $this->item[$id]['bnum'] += 1;
	}
	 //某商品数量减1
	public function der_gone($id){
	  if($this->item[$id]['bnum'] == 0){
	     return;
	  }
	  $this->item[$id]['bnum'] -= 1;
	}
	//判断某个商品是否存在
	protected function is_Gexist($id){
		if(array_key_exists($id,$this->item)){
		   return true; //如果某商品的id是存放商品数组的索引值 那么就说明该商品存在于购物车
		}else{
		  return false;
		}
	}
	//修改购买商品的数量
	public function modItem($id,$num=1){
	   if(!$this->is_Gexist($id)){
			return ;
	   }
	   $this->item[$id]['bnum'] = $num;
	}
    //查询购物车的商品种类数目
	public function getVariety(){
	   return count($this->item);
	}
    //查询购物车的商品数量
	public function getGnum(){
	   $sum = 0;
	   if($this->getVariety() == 0){//如果种类为0的话，那么数量自然为0
	       return 0;
	   }
	   foreach($this->item as $v){
	        $sum += $v['bnum'];
	   }
	   return $sum;
	}
    // 查询购物车的商品总金额
	public function getGprice(){
		 $price = 0;
	   if($this->getVariety() == 0){//如果种类为0的话，那么数量自然为0
	       return 0;
	   }
	   foreach($this->item as $v){
	        $price += $v['bnum']*$v['gprice'];
	   }
	   return $price;
	
	}
    //返回购物车的所有商品
	public function getAll(){
	  return $this->item;
	}
  }
  $cart = CartTool::getCart(); 
?>