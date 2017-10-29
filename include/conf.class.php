<?php
 /*
    file config.class.php
	作用：让多个类都能读取配置信息(比如  cache smarty mysql)
 */
 //这是一个单例模式
 defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
  class conf{
	protected $data = array();
	protected static $instance  = null; 
    final protected function __construct(){//用来获取配置文件的内容
	        require_once(ROOT.'/'.'include/config.inc.php');
			//require_once('./config.inc.php');
			$this->data = $_CFG;
	}
	final protected function __clone(){
	
	}
	public static function getInstance(){
	    if(self::$instance instanceof self){
		    //如果自己的静态属性（每次使用上一次保存的值）是自己的实例
			return self::$instance;
		}else{
		    self::$instance = new self();
			return self::$instance;
		}
	}
	//读取配置信息
	public function __get($key){
	    if(array_key_exists($key,$this->data)){//isset($this->data[$key])
		    return $this->data[$key];
		}else{
		   return null;
		}
	}
	//添加修改删除配置信息
	public function __set($key,$val){
	      $this->data[$key] = $val;
	}
  }
  /*
    $conf = conf ::getInstance();//实例化对象
  调试成功
  echo '<pre>';
  print_r($conf);
  echo '</pre>';
   echo $conf->host;
    $conf->smartydir = 'D:dir';
  echo $conf->smartydir;
  */
 
?>