<?php
/*
  file MySmarty.php
  用途：引入smarty类  配置参数
*/
defined('KEYS') or die('Blooking Access');
require_once(ROOT.'/lib/smarty3/Smarty.class.php');
class MySmarty extends Smarty{
    public function __construct(){
		parent::__construct();
		$this->compile_dir = ROOT.'/data/comp';//编译目录
		$this->template_dir = ROOT.'/view/front';//前台模板目录
		$this->config_dir = ROOT.'/data/conf';//配置文件目录
		$this->caching = true;//开启缓存
		$this->cache_dir = ROOT.'/data/cache';//缓存目录
	}	
}

?>