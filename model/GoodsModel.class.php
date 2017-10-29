<?php
 /*
   file GoodsModel.class.php
   This is a model function:商品表的model 用于对该表的增删改查....
 */
   defined('KEYS') || exit('jing zhi fang wen');
   class GoodsModel extends Model{
	   protected $table = 'goods';
	   protected $pk = 'goods_id';//主键
	   protected $fileds = array(
		   'goods_id','goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','click_count','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time','last_update');
	   protected $auto = array(array('is_best','value','0'),
								array('is_new','value','0'),
								array('is_hot','value','0'),
								array('last_update','value','000000'),
								array('add_time','function','time')
							   ); //需要填充的字段所组成的特殊数组
	    protected $validate = array(
									  array('goods_name',1,'商品名不能为空','require'),
									  array('cat_id',1,'分类必须是大于0的整数','number'),
									  array('shop_price',1,'价格不能为空，必须是大于0的数','number2'),
									  array('goods_weight',1,'重量不能为空，必须是大于0的数','number2'),
									  array('goods_number',1,'库存不能为空，必须大于0,必须是整数','number1'),
									  array('is_best',2,'只能是1和0','in','0,1'),
									  array('is_now',2,'只能是1和0','in','0,1'),
									  array('is_hot',2,'只能是1和0','in','0,1'),
									  array('goods_brief',3,'10到100个字符的内容','length','10,100')
									);
	 
	   //加入回收站功能
	   public function trash($gid){
	      return $this->update(array('is_delete'=>1),$gid);
	   }
	   //还原商品
	   public function resets($gid){
	       return $this->update(array('is_delete'=>0),$gid);
	   }
	   //自动创建商品货号
	   public function createSn(){
	      $sn = 'BL'.date('Ymd').mt_rand(10000,99999);
		  $sql = 'select count('.$this->pk.') from '.$this->table."  where goods_sn= '".$sn."'";
		  if($this->db->getone($sql)){
		    return $this->createSn();//如果存在 进递归创建新的货号
		  }else{
		    return $sn;
		  }
	   }
	   //取出新品
	   public function getNew(){
	     $sql = 'select goods_id,goods_name,shop_price,market_price,goods_number,thumb_img from '.$this->table.' where is_new=1 and is_delete=0 and is_on_sale =1 and goods_number>0 order by add_time desc limit 5';
		 return $this->db->getall($sql);
	   }
	   //取出女装，男装
	    //由于 它们属于顶级栏目 因此自身是没有商品的 所以必须找他们的子孙  Clothing 服装的意思
		public function getClothing($cat_id,$offset=0,$pagesize=5){//默认 偏移量offset为0，显示的条数为5是因为首页的得到男装女装都调用了同样的方法
			$catemodel = new CateModel();
			$category = $catemodel->select();//取出所有的栏目
			$sons = array($cat_id);//先把自己也放进去
		    if($cat_id == 1){//男装的子孙树
			   $tree = $catemodel->catetree($category,$cat_id,1);//无限级分类 找子孙栏目
			  
			}else if($cat_id == 4){//女装的子孙树
			  $tree = $catemodel->catetree($category,$cat_id,1);
			}
			if(!empty($tree)){//如果子孙栏目为空，说明它是叶子节点 那么它就有商品
			  foreach($tree as $v){
			    $sons[] = $v['cate_id']; 
			   }
			}
			    $sonstr = implode(',',$sons);
			   $sql = 'select goods_id,goods_name,shop_price,market_price,goods_number,thumb_img from '.$this->table.' where cat_id in( '.$sonstr.' ) and is_delete=0 and is_on_sale =1 and goods_number>0 order by add_time desc limit '.$offset.' , '.$pagesize;
			return $this->db->getall($sql);
		}
	 //获得总记录数
	 public function getRowCount($cat_id){
		$catemodel = new CateModel();
			$category = $catemodel->select();//取出所有的栏目
			$sons = array($cat_id);//先把自己也放进去
		    if($cat_id == 1){//男装的子孙树
			   $tree = $catemodel->catetree($category,$cat_id,1);//无限级分类 找子孙栏目
			  
			}else if($cat_id == 4){//女装的子孙树
			  $tree = $catemodel->catetree($category,$cat_id,1);
			}
			if(!empty($tree)){//如果子孙栏目为空，说明它是叶子节点 那么它就有商品
			  foreach($tree as $v){
			    $sons[] = $v['cate_id']; 
			   }
			}
		   $sonstr = implode(',',$sons);
		   $sql = 'select count( '.$this->pk.') from '.$this->table.' where cat_id in( '.$sonstr.' ) and is_delete=0 and is_on_sale =1 and goods_number>0 ';
		   return $this->db->getone($sql);
	 
	 }
	//取出历史记录
	public function getHis($arr){
	  for($i=0;$i<count($arr);$i++){
	     $arr[$i] = substr($arr[$i],strripos($arr[$i],'=')+1);
		 $grows[] = $this->find($arr[$i]);
	  }
	  return $grows;
	}
	//取得某一商品所在的栏目
	public function getCate($cid){
	  $sql = 'select catename from cate where cate_id='.$cid;
	  return $this->db->getone($sql);
	}
	//取得某一商品的数量
	public function getnumber($gid){
	  $sql = 'select goods_number from '.$this->table.' where '.$this->pk.' = '.$gid;
	  return $this->db->getone($sql);
	}
	//修改商品的数量
	public static function setnumber($num,$gid){
	   $sql = 'update '.$this->table.' set goods_number =  goods_number - '.$num.' where '.$this->pk.' = '.$gid;
	   $this->db->sql_query($sql);
	   return $this->db->affected_rows();
	}
	//加入购物车
	public function addCart($id,$name,$price,$num){
			 $cart = CartTool::getCart();
			 $cart->addItem($id,$name,$price,$num);
			 return $cart->getall();
	}
	//取出购物车里的商品的详细信息
	public function getCartGoods($item){
	    foreach($item as $k=>$v){
		  $sql = 'select thumb_img,market_price from '.$this->table.' where '.$this->pk.' = '.$k;
		  $row = $this->db->getrow($sql);
		  $item[$k]['thumb_img'] = $row['thumb_img'];
		  $item[$k]['market_price'] = $row['market_price'];
		}
		return $item;
	}
   }
?>