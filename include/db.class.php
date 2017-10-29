<?php
 /*
    file db.class.php
	数据库连接类---是一个抽象类，用户定规范的
 */
 defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
  abstract class db{
	  /*
	    数据库连接
	    参数有 
		$host ---服务器地址
		$user ----用户名 
		$pwd -----密码
		return bool
	  */
       public abstract function db_connect($host,$user,$pwd);
	   /*
	   发送查询  参数 sql语句
	   结果  bool 或者  结果集
	   */
	   public abstract function sql_query($sql);
	   /*查询多行数据（参数:$sql;返回值:array或者bool）*/
	   public abstract function getall($sql);
	   /*查询单行数据（参数:$sql;返回值:array或者bool）*/
	   public abstract function getrow($sql);
	   /*查询单个数据（参数:$sql;返回值:array或者bool）----单个记录就是查询单个字段的数据*/
	   public  abstract function getone($sql);
	   /*自动执行增删改语句（参数：表名、数据、dml语句类型、条件）
	      这个函数的作用是可以自动拼接sql语句
		  如：auto_execute_dml(user,array('usernmae'=>'yjc','email'=>'yjc@168.com'),insert,'')
		        insert into user (username,email) values('yjc','yjc@168.com')
	   */
	   public abstract function auto_execute_dml($table,$dataarr,$mode='insert',$where=' 1 and limit 1');
	   public abstract function error($errmsg);//记录数据库等执行的报错
	   public abstract function  WriteLog($cont);//将信息写入日志
	   public abstract function select_db($db);//调用数据库
	   public abstract function SetChar($char);//设置字符集
	   public abstract function affected_rows();	   //返回影响的函数
	   public abstract function insert_id();//	    获得上次插入的id
  }
?>