<?php
 /*
    file init.php
	作用：框架初始化
 */
 defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
 header('content-type:text/html;charset=utf-8');
 //计算网站根目录
  //echo __FILE__; echo __DIR__;---该两个魔术常量是得到当前文件的路径和当前文件所在目录的路径
  define('ROOT',str_replace('\\','/',dirname((dirname(__FILE__)))));//正斜杠对于window与Linux都适用  Linux不适用反斜杠
 // echo ROOT;  //结果D:/work/phpStudy/WWW/BoolShop  记住我的根目录没有/ 所以在其它地方需要加上/
  //自动加载类文件
   require_once(ROOT.'/include/MySmarty.class.php'); 
  function __autoloadbool($class){
      if(strtolower(substr($class,-5)) == 'model'){
	     require_once(ROOT.'/model/'.$class.'.class.php');
	  }else if(strtolower(substr($class,-4)) == 'tool'){
		require_once(ROOT.'/tool/'.$class.'.class.php');
	  }else{
		require_once(ROOT.'/include/'.$class.'.class.php');
	  }
  }
  spl_autoload_register('__autoloadbool');
  //开启session会话
 
  session_start();
	 //过滤参数
   $_GET = common::_addslashes($_GET);
   $_POST = common::_addslashes($_POST);
   $_COOKIE = common::_addslashes($_COOKIE);
 //设置错误级别
  define('DEBUG',true);
  if(defined('DEBUG')){
      error_reporting(E_ALL & ~E_NOTICE);//如果是开发模式  就尽可能的多报错
  }else{
     error_reporting(0);//如果是生产模式，就禁止错误
  }
   $smarty = new MySmarty();
?>