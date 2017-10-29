<?php
 /*
   file mysql.class.php
   针对mysql数据库的操作
 */
 defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
 class mysql extends db{
      private static $SqlIns = null;
	  private $conn= null;
	  private $conf = array();
	  public function __construct(){
		  $this->conf = conf::getInstance();
	      $this->db_connect($this->conf->host,$this->conf->user,$this->conf->pwd);
		  $this->select_db($this->conf->db);
		  $this->SetChar($this->conf->char);
	  }
	  //数据库的连接
	  public function db_connect($host,$db_user,$db_pwd){
	           $this->conn = mysql_connect($host,$db_user,$db_pwd);
			   if(!$this->conn){
			        $err = new Exception('数据库连接失败');//捕获异常
					$this->error($err);
					throw $err;  //抛出异常
			   }
			   
	  }
	  //析构方法
	  public function __destruct(){
	  
	  }
	  //实现单例
	  public static function getIns(){
	     if(!(self::$SqlIns instanceof self)){
		       self::$SqlIns = new self();
		 }
		 return self::$SqlIns;
	  }
	  //记录错误
	  public function error($errmsg){
	           log::writelog($errmsg);
			   die($errmsg);
	  }
	  //执行sql语句(不用于查询)
	  public function sql_query($sql){
		  //var_dump($this->conn);
	        $res = mysql_query($sql,$this->conn);
			if(!$res){
			    $this->error(mysql_error()."\r\n错误的sql语句".$sql);
			}
			$this->WriteLog($sql);
			return $res;
	  }
	  //将sql语句写入日志
	  public function WriteLog($cont){
	      log::writelog($cont);
	  }
	  //调用数据库
	  public function select_db($db){
	       $sql = 'use '.$db;
		   $this->sql_query($sql);
	  }
	  //设置字符集
	  public function SetChar($char){
	      $sql = 'set names '.$char;
		  $this->sql_query($sql);
	  }
	   public  function getall($sql){
	          $res = $this->sql_query($sql);
			  $arr = array();
			  while($row = mysql_fetch_assoc($res))
				  {
						$arr[] = $row;
				  }
			  return $arr;
	   }
	   //返回一行记录
	   public  function getrow($sql){
		 $res = $this->sql_query($sql);
		 return mysql_fetch_assoc($res);
	   }
	   //返回一个字段的值
	   public   function getone($sql){
		 $res = $this->sql_query($sql);
		 $row = mysql_fetch_row($res);
		 return $row[0];
	   }
	   public function affected_rows(){
	       return mysql_affected_rows($this->conn);
	   }
	   public function insert_id(){
	      return mysql_insert_id();
	   }
	   public  function auto_execute_dml($table,$dataarr,$mode='insert',$where=' 1 and limit 1'){
	       //insert into table () values ()
		   //update table  
		   //echo $mode;
			//array('usernmae'=>'yjc','email'=>'yjc@168.com')
		    if(!is_array($dataarr)){
			       return false;
			}
			$sql = '';
			if($mode == 'update'){
			    $sql = 'update '.$table. ' set ';
				foreach($dataarr as $k=>$v){
				    $sql .= $k.'='."'".$v."'".',';
				}
				$sql = rtrim($sql,',');//去掉最右边的,号
				$sql .= $where;//写条件的时候带上where
				// echo $sql;
				return $this->sql_query($sql);
			}
	         $fieldname = implode(',',array_keys($dataarr));//取得字段名
			 $fieldval = implode("','",array_values($dataarr));//取得字段值
			 $sql = 'insert into '.$table.'( '.$fieldname.' )';
			 $sql .= 'values(\'';
			 $sql .= $fieldval;
			 $sql .= '\')';
			
			 return $this->sql_query($sql);
	   }
 }
?> 