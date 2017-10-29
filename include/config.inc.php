<?php
   /*
      file config.inc.php
	  作用：配置文件
   */
   defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
   $_CFG = array();//定义一个存放配置信息的空数组
   $_CFG['host'] = '127.0.0.1';
   $_CFG['user'] = 'root';
   $_CFG['pwd'] = 'root';
   $_CFG['db'] = 'boolshop';
   $_CFG['char'] = 'utf8';
   //$_CFG['server'] = $_SERVER['SERVER_NAME'];
   /*echo "<pre>";
   print_r($_CFG);
   echo "</pre>";*/
   //$_SERVER['SERVER_NAME'] #当前运行脚本所在服务器主机的名称。
?>