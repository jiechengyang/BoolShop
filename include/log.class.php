<?php
   /*
       file   log.class.php
	   用途:用于写入 备份 日志
	   规定：大于10M的日志文件就备份
   */
   defined('KEYS') || exit('jin zhi fang wen');//方穿墙设置
     class log{
		   const LOGFILE = 'curr.log';//用一个常量去保存日志文件的名称
		   //将内容写入日志
	       public static function writelog($contents){
			       $log = self::isBack();
				   $contents .= "\r\n";
				   $fp = fopen($log,'a+');
                   fwrite($fp,$contents);
				   fclose($fp);
		   
		   }
		   //备份日志文件
		   protected static function backup(){
		           
				   $log = ROOT.'/data/log/'.self::LOGFILE;
				   $newpath = ROOT.'/data/log/'.date("ymd").mt_rand(100,10000).'.bak';  //mt_rand — 生成更好的随机数
				   return rename($log,$newpath);
		   }
		   //判断是否备份 并计算日志文件的地址
		   protected static function isBack(){
		          $log = ROOT.'/data/log/'.self::LOGFILE;//取得日志文件
				  if(!file_exists($log)){
				     //如果文件不存在 就创建日志文件
					   touch($log);//touch — 设定文件的访问和修改时间;如果文件不存在，则会被创建。成功时返回 TRUE， 或者在失败时返回 FALSE. 
					   return $log;
				  }
				  $FileSize = filesize($log);
				  //如果日志文件小于1M就返回继续使用该日志文件写入
				  // 要是存在,则判断大小
				 // 清除缓存
				   clearstatcache(true,$log);
				  if($FileSize <= (1024*1024)){
				    //1M = 1024KB 1KB = 1024B
					   return $log;
				  }
				  //走到这里的时候 就表示 日志文件已将大于 1M
				  $backflag = self::backup();
				  if($backflag){//如果备份成功就创建新的日志文件
				      touch($log);
					  return $log;
				  }else{
				     return $log;
				  }
		   }
	 }
?>