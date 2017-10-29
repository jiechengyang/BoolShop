<?php
   /*
        file common.class.php
		用于存放基本的公用方法
   */
   defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
   class common{
        //过滤数组参数($_GET、$_POST、$_COOKIE....)
        public static function _addslashes($arr){
		
		      foreach($arr as $key=>$val){
			      if(is_string($val)){
				           $arr[$key] = addslashes($val);//给单双引号加一个转义字符\
				  }else if(is_array($val)){
				       $arr[$key] = self :: _addslashes($val);
				  }
			  }
			  return $arr;
		}
		//锁定下拉菜单被selected的值
		public static function opt_seld($v1,$v2){
				return ($v1 == $v2)?'selected':'';
		}
   }
?>