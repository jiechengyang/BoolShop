<?php
  /*
     file upact.php
	 测试 文件上传类
  */
  define('KEYS',true);
 
  require_once('include/init.php');
  $uptool = new UpTool();
  if( ($file=$uptool->up('pic')) == false){
	 echo 'Fail','<br/>';
		  echo $uptool->getErr();
  }else{
        ///echo '文件：',$file,'<br/>';
		  echo $uptool->getErr();
  }
?>