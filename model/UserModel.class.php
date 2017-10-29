<?php
 /************
   YJC php 之路
 ************/
 /*
   UserModel.class.php model
   user表的处理
 */
 ##########
 defined('KEYS')||exit('jing zhi fang wen');
 class UserModel extends Model{
    protected $table = 'user';
	protected $pk = 'user_id';
    protected $fileds = array('user_id','username','passwd','email','loginnum','regtime','lastlogin');//表单的字段 
	protected $auto = array(
						array('regtime','function','time')
					);//需要填充的字段所组成的特殊数组
	protected $validate = array(
	                    array('username',1,'用户名必须存在','require'),
						array('username',3,'用户名的长度4-16个字符','length','4,16'),
						array('email',2,'电子邮箱格式不正确','email'),
						array('passwd',1,'密码必须填','require'),
						array('agreement',2,'你还没接收用户协议','agree',0)
	);//将要验证的字段的规范组成一个数组
   //注册用户
   public function reg($data){
		 $data['passwd'] = $this->encPwd($data['passwd']);
		 return $this->add($data);
   }
   //加密密码
   protected function encPwd($pwd){
         return md5($pwd);
   }
   //检查用户是否被注册
   public function checkUser($user){
       $sql = 'select count('.$this->pk.') from '.$this->table." where username='".$user."'";
	   return $this->db->getone($sql);
   }
   //用户的登录
   public function login($user,$pwd){
		$sql = 'select username,user_id from '.$this->table.' where passwd = '."'".$this->encPwd($pwd)."'";
		$row = $this->db->getrow($sql);
		if($row['username'] === $user){
		 $this->updLast($row['user_id']);
		 $_SESSION['username'] = $user;
		 $_SESSION['user_id'] = $row['user_id'];
		   return true;
		}else{
		   return false;
		}
   }
   //修改登录时间lastlogin
   protected function updLast($uid){
        $data = array('lastlogin'=>time());
		return $this->update($data,$uid);
   }
 }
  
?>