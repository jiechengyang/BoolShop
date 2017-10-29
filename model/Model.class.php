<?php
  /*
      file Model.class.php --所有数据表的父亲model层--这里它完成了数据库的连接 以及接收数据库类的所有可用方法与属性
  */
    //echo 1;
	defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
    class Model{
		  protected $table = ''; //数据表
	      protected $db = null;//数据库实例
		  protected $pk = ''; //保存primary key
		  protected $fileds = array();//表单的字段 
		  protected $auto = array();//需要填充的字段所组成的特殊数组
		  protected $validate = array();//将要验证的字段的规范组成一个数组
		  protected $error = array(); //存放错误的信息
		  public function __construct(){
		      $this->db = mysql::getIns();
		  }
		  //m方法  对于那些没有model的表也可以组织sql语句来操作数据库
		  public function SetTable($table){
		      $this->table = $table;
		  }
		  //自动过滤post数组中不需要的字段
		  public function facade($arr=array()){
			  $data = array();
			  foreach($arr as $k=>$v){
			        if(in_array($k,$this->fileds)){
					    $data[$k] = $v;
					}
			  }
			  return $data;
		  }
		  //自动填充post数组没有接收到 但是表里又得有的字段 例如  is_best。。。
		  public function __autofill($data){
		       foreach($this->auto as $v){
			       if(!array_key_exists($v[0],$data)){
					   switch($v[1]){
					           case 'value':
											$data[$v[0]] = $v[2];
											break;
							   case  'function':
								           
											$data[$v[0]] = call_user_func($v[2]);//或者 $v[2]() --就是变量函数 回调了函数
											 break;
					   }
				   }
			   }
			   return $data;
		  }
		 
		  //自动检验数据
		  /*
				 array(
									  array('goods_name',1,'商品名不能为空','require'),
									  array('goods_sn',1,'商品序列号不能为空','require'),
									  array('cat_id',1,'分类必须是大于0的整数','number'),
									  array('shop_price',1,'价格不能为空，必须是大于0的数','number2'),
									  array('goods_weight',1,'重量不能为空，必须是大于0的数','number2'),
									  array('goods_number',1,'库存不能为空，必须大于0,必须是整数','number1'),
									  array('is_best',2,'只能是1和0','in','0,1'),
									  array('is_now',2,'只能是1和0','in','0,1'),
									  array('is_hot',2,'只能是1和0','in','0,1'),
									  array('goods_brief',3,'10到100个字符的内容','length','10,100')
									);
							0-字段 1-验证的类型 2-错误信息 3-验证的规则  4-值的许可范围
		  */
		   public function __autovalid($data){
					if(empty($this->validate)){
					     return true; //如果验证数组是空的 就说明没有可验证的内容
					}
					foreach($this->validate as $v){
						switch($v[1]){
							 case 1: 
									if(!isset($data[$v[0]])){
										$this->error[] = $v[2];
										return false; 
									}
									if(!isset($v[4])){//如果第四个参数不存在，就值为空
									   $v[4] = '';
									}
									if(!$this->checkrule($data[$v[0]],$v[3],$v[4])){//检验失败的话
										$this->error[] = $v[2];
										return false; 
									}
									break;
							 case 2:
									if(isset($data[$v[0]])){
										if(!$this->checkrule($data[$v[0]],$v[3],$v[4])){//检验失败的话
										$this->error[] = $v[2];
										return false; 
										}  
									}
								     break;
							 case 3: if(isset($data[$v[0]]) && !empty($data[$v[0]])){
											if(!$this->checkrule($data[$v[0]],$v[3],$v[4])){//检验失败的话
											$this->error[] = $v[2];
											return false; 
									}
								
									}
									
									break;						
						
						} 
					}
						return true;//如果都和法就返回真
		   }
		   
		   public function checkrule($dval,$rule,$parm=''){
					switch($rule){
							case 'require':
									    return !empty($dval);
									//验证的是必填字段，goods_name goods_sn  这里的话 goods_name goods_sn 就是不为空 empty返回的是false 取反返回true $this->checkrule 就不会走这步了
							case 'number':
										//var_dump($dval);
										if(is_numeric((int)$dval) &&  $dval > 0 ){
										    return true;
										}else{
										    return false;
										}
						    case  'number1':
										 //var_dump(is_string($dval));
										if(is_numeric($dval) && is_int((int)$dval) && $dval > 0){
											//echo $dval;
										    return true;
										}else{
										    return false;
										}
							case 'number2':
									   if(is_numeric($dval) && $dval > 0){
										    return true;
										}else{
										    return false;
										}
							case 'in':
										 $tmp = explode(',',$parm);
							             return in_array($dval,$tmp);
							case 'length':
										 $tmp = explode(',',$parm);
										 list($min,$max) = $tmp;
										 
										 return mb_strlen($dval,'utf-8') >= $min && strlen($dval) <= $max;
							case 'email':
								        $preg = '/^([\w-]+)@([a-z0-9A-Z]+\.)+(com|cn|net|org)$/i';
										//var_dump(preg_match($preg,$dval,$res));
										if(preg_match($preg,$dval,$res)){ //或者使用系统函数filter_var ( 'bob@example.com' ,  FILTER_VALIDATE_EMAIL ));
										   return true;
										}else{
											return false;
										}
							case 'agree':
										 if($dval == $parm){
												return false;
											}else{
											   return true;
											}
							default:
										 return false;
					}
		   
		   }
		   //反馈错误信息
		   public function getError(){
					return $this->error;
		   }
		  //增加记录
		  public function add($data){
		     return $this->db->auto_execute_dml($this->table,$data);
		  }
		  //取得上一次添加成功的id号
		  public function getIns_id(){
		     return $this->db->insert_id();
		  }
		  //删除记录(条件为单个)参数有一个主键id 根据18哥的经验 一般删除和修改都是通过主键
		  public function delete($id){
			   
		        $sql = 'delete from '.$this->table.' where '.$this->pk.' = '.$id;
				 if($this->db->sql_query($sql)){
				    return $this->db->affected_rows();
				 }else{
				    return false;
				 }
				
		  }
		  //修改记录 参数有一个主键id 根据18哥的经验 一般删除和修改都是通过主键
		  public function update($data,$id){
		      if($this->db->auto_execute_dml($this->table,$data,'update',' where '.$this->pk.' = '.$id)){
				  return $this->db->affected_rows();
			  }else{
			     return false;
			  }
			
		  }
		  //查询记录 暂时取出全部 
		  public function select(){
		      $sql = 'select * from '.$this->table;
			  return $this->db->getall($sql);
		  }
		  //取出一行记录
		  public function find($id){
				$sql = 'select * from '.$this->table.' where '.$this->pk.' = '.$id;
				return $this->db->getrow($sql);
		  }
		  //表的分页
		  public function FenYe($paging){
			   if(empty($paging->where)){
					 $sql = "select count($this->pk) ".' from '.$this->table;
			   }else{
				    $sql = "select count($this->pk) ".' from '.$this->table.$paging->where;
			   }
			   $paging->rowcount = $this->db->getone($sql);
			   $paging->pagesize = 4;
               $paging->pagecount = ceil($paging->rowcount/$paging->pagesize);
			   $paging->page_whole = 8;
			   if(empty($paging->where)){
					$list_sql = 'select * from '.$this->table.' limit '.($paging->pagenow-1)*$paging->pagesize.' , '.$paging->pagesize;
			   }else{
			       $list_sql = 'select * from '.$this->table.$paging->where.' limit '.($paging->pagenow-1)*$paging->pagesize.' , '.$paging->pagesize;
			   }
			   $paging->res_list = $this->db->getall($list_sql);//得到带分页的记录
			   $paging->prepage = ($paging->pagenow>1)?$paging->pagenow-1:$paging->pagenow;
			   $paging->nextpage = ($paging->pagenow < $paging->pagecount)?$paging->pagenow+1:$paging->pagecount;
			   $paging->navigator .= "<table id='page-table' cellspacing='0'><tr><td align='right' nowrap='true'>";
			   $paging->navigator .= "<font color='#BBDDE5'>共{$paging->rowcount}条记录&nbsp;&nbsp;{$paging->pagenow}/{$paging->pagecount}&nbsp;&nbsp;</font>";
			   if($paging->pagenow == 1){
					$paging->navigator .= "<b>上页&nbsp;&nbsp;</b>";
			   }else{
					$paging->navigator .= "<a href='".$paging->url."?pagnow={$paging->prepage}' title='上一页'>[上一页]</a>";
			   }
			   //每$page_whole页翻页----通俗点在 1到10页  无论点哪一页 1到10页的连接不变
			    $start = floor(($paging->pagenow-1)/$paging->page_whole)*$paging->page_whole+1;
				$index = $start;
			    //整体每$page_whole页向前翻页如果$pagenow 在 1-$page_whole页内就不会有向前翻的情况
				  if($paging->pagenow > $paging->page_whole)
					{
						 $paging->navigator .= "&nbsp;&nbsp;<a href='{$paging->url}?pagenow=".($start-1)."'>&nbsp;&lt;&lt;&nbsp;</a>";
					}
				for($start;$start<$index+$paging->page_whole;$start++){
						$paging->navigator .= "<a href='{$paging->url}?pagenow={$start}'>[{$start}]</a>";				
				}
				//整体每$page_whole页向后翻页
				$paging->navigator .= "&nbsp;&nbsp;&nbsp;<a href='{$paging->url}?pagenow={$start}'>&nbsp;&lt;&lt;&nbsp;</a>";
				if($paging->pagenow == $paging->pagecount){
					$paging->navigator .= "&nbsp;<b>下页&nbsp;&nbsp;</b>";
				}else{
					$paging->navigator .= "&nbsp;<a href='".$paging->url."?pagenow={$paging->nextpage}"."' title='下一页'>[下一页]</a>";
				}
			   $paging->navigator .= "&nbsp;&nbsp;&nbsp;<a href='{$paging->url}?pagenow={$paging->pagecount}'>末页</a></td></tr></table>";
		  }
	}
?>