<?php
 /*
   MupTool.class.php
   多文件上传
   $pic = $_FILES['pic'];
   foreach($pic as $v){
    for($i=0;$i<count($v);$i++){
	  $arr[$i][] = $v[$i];
	 }
   }
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
  exit;
 */
 defined('KEYS') || exit('jing zhi fang wen');
 class MupTool{
	  protected $extensions = 'jpg,jpeg,png,bmp,gif,txt';//允许上传文件的后缀名
	  protected $maxsize = 1;//允许上传文件的大小
	  protected $errno = 0;//文件上传的错误号
	  protected $error =array(
					-1=>'出现了bug',
					0=>'成功',
		            1=>'文件超过系统大小',
					2=>'文件大小超过了表单的大小',
					3=>'文件只有部分被上传',
					4=>'没有文件被上传',
					6=>'找不到临时文件夹',
					7=>'文件写入失败',
					8=>'文件的后缀不符合',
					9=>'文件的太大',
					10=>'文件的目录不存在',
					11=>'文件移动错误'
		
	  );//上传文件的错误信息数组
	  //几次操作
	  public function HowUp($arr=array()){
		  $res = array();
	      if(count($arr) == 1){
			   //var_dump( key($arr));
		       $arr = $this->ArrayKey(key($arr));
			   for($i=0;$i<count($arr);$i++){
			      $res[] = $this->up($arr[$i]);
				  echo $this->getErr();
			   }
		  }else{
			  foreach($arr as $k=>$v){
				$res[$k] = $this->up($v);
				echo $this->getErr();
			 }
		  }
			return $res;
	  }
	  //根据$key的参数来判断 如果在多文件上传的时候传过来的参数只有一个表示是key是数组名
	  public function ArrayKey($key){
			$pic = $_FILES[$key];
			   foreach($pic as $v){
				for($i=0;$i<count($v);$i++){
				  $arr[$i][] = $v[$i];
				 }
			   }
			  // print_r($arr);
			 return $arr;
	  }
	  //上传
	  public function up($F){
		  if(is_int(key($F))){
		     if($F[3] != 0){
					$this->errno = $F[3];
					return false;
			  }
			  $ext = $this->getExt($F[0]);
			  if(!$this->isAllowExt($ext)){
				$this->errno = 8;
				return false;
			  } 
			  if(!$this->isAllowSize($F[4])){
				$this->errno = 9;
				return false;
			  }
			  if($dir=($this->mk_dir())){
				 if(!$dir){
				   $this->errno = 10;
				   return false;
				 }
			  }
			  $newfile = $dir.'/'.$this->rand_name(6).'.'.$ext;
			  if(!move_uploaded_file($F[2],$newfile)){
					$this->errno = 11;
					return false;
			  }
			  $this->errno = 0;
			  return str_replace(ROOT.'/','',$newfile);//为什么要取相对路径，因为在我的服务器上时D： E: 但是在其他人上也是的话 那么在我们这就没
		   
		  }else{
				if($F['error'] != 0){
				$this->errno = $F['error'];
				return false;
		  }
		  $ext = $this->getExt($F['name']);
		  if(!$this->isAllowExt($ext)){
		    $this->errno = 8;
			return false;
		  } 
		  if(!$this->isAllowSize($F['size'])){
		    $this->errno = 9;
			return false;
		  }
		  if($dir=($this->mk_dir())){
		     if(!$dir){
			   $this->errno = 10;
			   return false;
			 }
		  }
		  $newfile = $dir.'/'.$this->rand_name(6).'.'.$ext;
		  if(!move_uploaded_file($F['tmp_name'],$newfile)){
				$this->errno = 11;
				return false;
		  }
		  $this->errno = 0;
		  return str_replace(ROOT.'/','',$newfile);//为什么要取相对路径，因为在我的服务器上时D： E: 但是在其他人上也是的话 那么在我们这就没
		  
		  } 
	  }
	  //取文件后缀
	  public function getExt($file){
			$tmp = explode('.',$file);
			return end($tmp);
	  }
	  //检验文件后缀
	  public function isAllowExt($ext){
			$exts = explode(',',$this->extensions);
			return in_array(strtolower($ext),$exts);
	  }
	  //检验文件的大小
	  public function isAllowSize($size){
	      return $size <= $this->maxsize * 1024 * 1024;
	  }
	  //按日期创建目录
	  public function mk_dir(){
		$dir = ROOT.'/data/uploadfile/'.date('Y-m-d');
		if(is_dir($dir) || mkdir($dir,'0700',true)){
				return $dir;
		}else{
				return false;
			}
	  }
	  //创建随机文件名
	  public function rand_name($length=6){
				$rstr = substr(str_shuffle('abcdefghijklomnopqrstuvwxyz1234567890'),0,$length);
				return time().$rstr;
	  }
	  //返回错误信息
	  public function getErr(){
	    return $this->error[$this->errno];
	  }
 }
?>