<?php

 /*
   file UpTool.class.php
   文件上传类
 */
   defined('KEYS') || exit('jing zhi fang wen');
   class UpTool{
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
	  //上传
	  public function up($key){
		 //print_r($_FILES[$key]);
		 if(!isset($_FILES[$key])){//有可能乱点
			$this->errno = -1;
		    return false;
		 }
	     $F = $_FILES[$key];
         if($F['error']){//上传的错误
		    $this->errno = $F['error'];
			return false;
		 }
		 //获得后缀
		 $ext = $this->getExten($F['name']);
		 //检查后缀名
		 if(!$this->isAllowExt($ext)){
		    $this->errno = 8;
			return false;
		 }
		 //创建目录
		 if($dir = $this->mk_dir()){
		    if($dir == false){
			    $this->errno = 10;
				return false;
			}
		 }
		 //检查大小
		 if(!$this->isAllowSize($F['size'])){
			  $this->errno = 9;
			  return false;
		 }
		 $newfile = $dir.'/'.$this->rand_name().'.'.$ext;
		 //移动
		 if(!move_uploaded_file($F['tmp_name'],$newfile)){
				$this->errno = 11;
				return false;
		 }
		$this->errno = 0;
		return str_replace(ROOT.'/','',$newfile);//这里不能使用绝对路径，因为在生产中使用者的电脑里的文件路径不一样
	  }
	  //获取文件的后缀名
	  public function getExten($file){
	      $tmp = explode('.',$file);
		  return end($tmp);
	  }
	  //检查文件的后缀
	  public function isAllowExt($ext){
	      $earr = explode(',',$this->extensions);
		  return in_array(strtolower($ext),$earr);//解决大小写不一致的问题
	  }
	  //检查文件的大小
	  public function isAllowSize($fileszie){
	     return $fileszie <= ($this->maxsize)*1024*1024;//小于1m 
	  }
	  //以日期时间创建目录
	  public function mk_dir(){
	     $dir = ROOT.'/data/uploadfile/'.date('Y-m-d');
		 if(is_dir($dir) || mkdir($dir,'0700',true)){
		        return $dir;
		 }else{
		      return false;
		 }
	  }
	  //生出随机的文件名
	  public function rand_name($length=6){
	      $rstr = 'abcdefghijklomnopqrstuvwxyz1234567890';
		  $rstr = substr(str_shuffle($rstr),0,$length);
		  return time().$rstr;
	 }
	 //获取错误信息
	 public function getErr(){
	    return $this->error[$this->errno];
	 }
	 #为了更好的扩展动态设置后缀与大小
	 public function setSize($size){//设置大小
	    $this->maxsize = $size;
	 }
	 public function setExt($ext){//设置后缀
			$this->extensions = $ext;
	 }
	 public function delExt($ext){ //删除某个后缀
			$this->extensions = str_replace($ext,'',$this->extensions );
	 }
   }
?>