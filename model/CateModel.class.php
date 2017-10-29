<?php

  /*
     file CateModel.class.php
	 栏目表的model
  */
  defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
  class CateModel extends Model{
      protected $table = 'cate';
	  protected $pk = 'cate_id';//主键
	  //无限级分类栏目(子孙栏目)找一个id的子孙栏目
	  public function catetree($arr,$id,$jibie=1){
		        $sontree = array();
	            foreach($arr as $v){
				   if($v['parent_id'] == $id){//如果栏目id是某个栏目的父栏目id 则就是 父栏目id=id
					   $v['jibie'] = $jibie;
				       $sontree[] = $v;				   
					   $sontree = array_merge($sontree,$this->catetree($arr,$v['cate_id'],$jibie+1));
				   }
				}
				return $sontree;
	  }
	  //显示栏目表单
	  public function select(){
		    $sql = 'select cate_id,catename,parent_id from '.$this->table;
			return $this->db->getall($sql);
	  }
	  //判断某个栏目是否存在子栏目:解决有子栏目不能删除的问题
	 /* public function  is_son($arr,$id){
		   $flag = false; //默认不存在子栏目
			foreach($arr as $v){
			   if($v['parent_id'] == $id){
					$flag = true;
					//break;
			   }
			}
			return $flag;

	  }
	   //用更好的方法解决
	  */
	  public function exist_son($id){
	     $sql = 'select cate_id,catename,parent_id from '.$this->table.' where parent_id= '.$id;//找子栏目如果这个id是某个栏目的父id
		 return $this->db->getall($sql);
	  }
	  
	  //加一个强制条件就是在提交修改的时候如果修改的上级分类是它的子类就不能修改
	  /*public function is_subtree($arr,$pid,$cid){
	     $sontree = $this->catetree($arr,$cid,1);//找到这个id的子孙树
		 $flag = false;//默认修改的结果不是子孙树
		  if($pid == $cid){//这里是判读
		    return true;
		 }
		 foreach($sontree as $v){
		    if($v['cate_id'] == $pid){
				$flag = true;//表示修改的结果是子孙树
			}
		 }
		 return $flag;
	  }
	  我这个思路没错  但是有个小问题 就是如果分类有很多  那么子孙后代就有很多  那么在改栏目的众多的子孙里看新修改的上级栏目是不是它的子孙 就显得很累赘
	  因此 经18哥的指点 我引入找新修改的上级栏目的家谱树 如果新修改的父辈是该栏目的话就表示 该栏目是新修改的上级栏目的父辈 所以就不能修改
	  */
	  //找新修改的上级栏目的家谱树 
	  public function get_partree($id){
		     $parr = array();
		     $tree = $this->select();
			 while($id > 0){
				 foreach($tree as $v){
					if($v['cate_id'] == $id){
						$parr[] = $v;
						$id = $v['parent_id'];
						break;
					}
				 }
			 }
			 return $parr;//这个结果是 儿子在前，祖辈在后   要上祖辈在前，儿子在后  就使用  array_reverse 转置
	  }
	   //取出当前的栏目名
	   public function getNowCate($cid){
	     $sql = 'select catename from '.$this->table.' where '.$this->pk.' = '.$cid;
		 return $this->db->getone($sql);
	   }
  }
?>