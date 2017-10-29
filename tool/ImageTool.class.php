<?php
 /************
   YJC php 之路
 ************/
 /*
  图片处理类
  功能：获取图片的信息、图片的水印、缩略、验证码
 */
 ##########
   class ImageTool{
    //获取图片的信息
	protected static $errno = 0;
	protected static $error = array(
		0=>'ok',
		1=>'文件不存在',
	    2=>'图片获取信息失败',
		3=>'水印比待操作图片大',
		4=>'使用水印函数结果为false',
		5=>'使用缩略函数结果为false',
		6=>'该函数不存在'
		);
	protected static function getinfo($img){
			//判读图片时候存在，不存在
			if(!file_exists($img)){
			   self::$errno = 1;
			   return false;
			}
			$info = getimagesize($img);
			//print_r($info);
			if(!$info){//这个原因可能是由于非图片造成的
			   $this->errno = 2;
			   return false;
			}
			$img = array();
			$img['width'] = $info[0];
			$img['height'] = $info[1];
			$img['ext'] = substr($info['mime'],stripos($info['mime'],'/')+1);
			
			return $img;
	} 
	/*
		  水印方法：parm string $dst 待操作的图片
		    Parma string $src 源图片
		    parm string $save 保存的路径 不填的话就默认替换原图片
		    parm string $pos 水印的位置
			parm int $pct 不透明度
	  1、保证两个图片存在
	  2、保证水印不能比待操作图片大
	  3、读取两个图片
	  4、根据水印的位置粘贴的坐标
	  5、加水印
	  6、保存
	 imagecopymerge — 拷贝并合并图像的一部分  ----图片的水印
   bool imagecopymerge( resource $dst_im  , resource $src_im  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $src_w  , int $src_h  , int $pct )
	*/
	  public static function water($dst,$src,$save=null,$pos=0,$pct=50){
		  //保证两个图片存在
		  if(!file_exists($dst) || !file_exists($src)){
			  
				self::$errno = 1;
				return false;
		  }
		  $dinfo = self::getinfo($dst);//得到目标图片的信息
		  $sinfo = self::getinfo($src);//得到源图片的信息
		  //保证水印不能比待操作图片大
		  if($dinfo['width'] < $sinfo['width'] || $dinfo['height'] < $sinfo['height']){
			  self::$errno = 3;
			  return false;
		  }
		  //读取两个图片
		  $dfunc = 'imagecreatefrom'.$dinfo['ext'];
		  $sfunc = 'imagecreatefrom'.$sinfo['ext'];
		  if(!function_exists($dfunc) || !function_exists($sfunc)){
			self::$errno = 6;
			return false;
		  }
		  $dim = $dfunc($dst);
		  $sim = $sfunc($src);
		  //根据水印的位置粘贴的坐标 0--左上角  1---右上角 2--右下角 3---左下角
		  switch($pos){
		  
				case 0:
					$posx = 0;
					$posy = 0;
					break;
				case 1:
					$posx = $dinfo['width']-$sinfo['width'];
					$posy = 0;
					break;
				case 2:
					$posx = $dinfo['width']-$sinfo['width'];
					$posy = $dinfo['height']-$sinfo['height'];
					break;
				case 3:
					$posx = 0;
					$posy = $dinfo['height']-$sinfo['height'];
					break;
				
		  }
		//加水印
		 if(!imagecopymerge($dim , $sim ,$posx , $posy,0,0,$sinfo['width'],$sinfo['height'],$pct)){
			self::$errno  = 4;
			return false;
		 }
		 //保存
		 $savefunc = 'image'.$dinfo['ext'];
		 if(!$save){
			 $savefunc($dim,$dst);
			 unlink($dst);
		 }
		 $savefunc($dim,$save);
		 return true;
		}
		/*
		   缩略图thumb: Parma string $dst 待处理图片
                       $save 保存路径
					   设置成多大的缩略图因此需要参数 $width  $height
	     1、判断待处理的图片是否存在
	     2、计算缩放比例  percent 比例
	     3、创建原始图的画布
	     4、创建缩略画布
	     5、创建白色填充缩略画布
	     6、填充缩略画布
	     7、复制并缩略
	     8、保存图片
	     9、销毁
 imagecopyresampled  (resource $dst_image  , resource $src_image  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $dst_w  , int $dst_h  , int $src_w  , int $src_h )
 将源图像资源从左上角的(0,0)截取全部 复制到目标图像资源上 在$dst从左上角(0,0)开始接收宽为$dw 高为$dh的图像资源
		*/
	   public static function thumb($src,$save=null,$width,$height){
		       //判断待处理的图片是否存在
			   if(!file_exists($src)){
			     self::$errno = 1;
			   }
			   $sinfo = self::getinfo($src);
	            if(!$sinfo){
					self::$errno = 2;
				   return false;
				}
				//等比例缩放 计算缩放比例 
				$percent = min($width/$sinfo['width'],$height/$sinfo['height']);//算出缩略图在画布上显示的比例
				//echo $percent,'<br/>';
				//创建原始图的画布
				$dst_im = imagecreatetruecolor($width,$height);
				//创建白色填充缩略画布
				$white = imagecolorallocate($dst_im,255,255,255);
				imagefill($dst_im,0,0,$white);
			   // 创建缩略画布
			   $createfunc = 'imagecreatefrom'.$sinfo['ext'];
			   $src_im = $createfunc($src);
			   //复制并缩略
			   $factWid =  (int)$sinfo['width']*$percent;//实际宽度 将要缩略的图片按比例压缩
			  // echo '实际宽度',$factWid,'<br/>';
			   $factHig =  (int)$sinfo['height']*$percent;//实际高度将要缩略的图片按比例压缩
			    //echo '实际高度',$factHig,'<br/>';
			   $paddingx = (int)(($width-$factWid)/2); //两边留白 白边的宽
               $paddingy = (int)(($height-$factHig)/2);//两边留白 白边的高
			  // echo $paddingx,'<br/>';
			   if(!imagecopyresampled($dst_im,$src_im,$paddingx,$paddingy,0,0,$factWid,$factHig,$sinfo['width'],$sinfo['height'])){
					 self::$errno = 5;
					 return false;
			   }
			   //保存图片
			   $savefunc = 'image'.$sinfo['ext'];
			   if(!$save){
				   $save = $src;
			       $savefunc($dst_im,$save);
				   unlink($src);//删除原来的图片
			   }
             $savefunc($dst_im,$save);
			 //销毁
			 //imagedestory($dst_im);
			 //imagedestory($src_im);
			 self::$errno = 0;
			 return true;
	   }
	   //返回错误信息
	   public static function getErr(){
		return  self::$error[self::$errno];
   }
   //验证码  parm int width int height 这样就可以拉伸画布  chose 选择不同的验证码 0 表示 纯数字 1表示 字母数字组合 2表示中文
   public static function checkCode($width=60,$height=30,$chose=1){
				//创建画布
				$im = imagecreatetruecolor($width,$height);
				//填充
				$bgcolor = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
				imagefill($im,0,0,$bgcolor);
				//绘制操作
				$str = '';
				$linecolor = imagecolorallocate($im,mt_rand(100,150),mt_rand(100,150),mt_rand(100,150));
				$fontcolor = imagecolorallocate($im,mt_rand(1,50),mt_rand(1,50),mt_rand(1,50));
				switch($chose){
				     case 0: $str = mt_rand(1000,9999);break;
					 case 1: $str = substr(str_shuffle("ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789"),0,4);break;
					 case 2:
							$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借";
							  $preg = "/[\x{4e00}-\x{9fa5}]/u";
							  preg_match_all($preg,$chars,$res);
							  $arr = $res[0];
							  shuffle($arr);
							  $str = implode('',array_slice($arr,0,4));
							  break;
						}
			   //花干扰线
			   for($i=1;$i<=15;$i++){
			     imageline($im,0,mt_rand(0,$height),$width,mt_rand(0,$height),$linecolor);
			   }
			   //写字
			   if($chose == 2){
			       imagettftext($im,18,0,8,20,$fontcolor,ROOT.'/tool/ttf/FZSTK.TTF',$str);
			   }else{
					imagestring($im,5,rand(0,(int)$width/2),rand(0,(int)$height/2),$str,$fontcolor);
			   }
				
			   //输出
			   header('content-type:image/png');
			   imagepng($im);
			   //销毁
			   imagedestory($im);
			  }
  }
?>