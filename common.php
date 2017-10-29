<?php
	//拼接签名串
	function JoinHmac($var1,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11,$var12,$var13){
			$data = '';
			/*
				$p0_Cmd = 'Buy'; //业务类型
				$p1_MerId = '10001126856'; //商品编号
				$p2_Order = $_POST['p2_Order']; //订单号
				$p3_Amt = $_POST['p3_Amt']; //支付金额
				$p4_Cur = 'CNY';  //支付币种
				$p5_Pid = ''; //商品名称
				$p6_Pcat = ''; //商品种类
				$p7_Pdesc = ''; //商品描述
				$p8_Url = "http://www.y_phpstudy.com.cn/InterestPay/Res.php";  //商户接收支付成功数据的地址（既给url返回的信息）
				$p9_SAF = "0"; //送货地址
				$pa_MP = '';//商户扩展信息
				$pd_FrpId = $_POST['pd_FrpId']; //支付通道编码
				$pr_NeedResponse = "1";
			*/
		$data .= $var1;
		$data .= $var2;
		$data .= $var3;
		$data .= $var4;
		$data .= $var5;
		$data .= $var6;
		$data .= $var7;
		$data .= $var8;
		$data .= $var9;
		$data .= $var10;
		$data .= $var11;
		$data .= $var12;
		$data .= $var13;
		return $data;
	}
	function ResJoinHmac($var1,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11){
		$data = '';
		$data .= $var1;
		$data .= $var2;
		$data .= $var3;
		$data .= $var4;
		$data .= $var5;
		$data .= $var6;
		$data .= $var7;
		$data .= $var8;
		$data .= $var9;
		$data .= $var10;
		$data .= $var11;
		return $data;
	}
	function HmacMd5($data,$key)
		{
				// RFC 2104 HMAC implementation for php.
				// Creates an md5 HMAC.
				// Eliminates the need to install mhash to compute a HMAC
				// Hacked by Lance Rushing(NOTE: Hacked means written)

				//需要配置环境支持iconv，否则中文参数不能正常处理
				$key = iconv("GB2312","UTF-8",$key);
				$data = iconv("GB2312","UTF-8",$data);

				$b = 64; // byte length for md5
				if (strlen($key) > $b) {
				$key = pack("H*",md5($key));
				}
				$key = str_pad($key, $b, chr(0x00));
				$ipad = str_pad('', $b, chr(0x36));
				$opad = str_pad('', $b, chr(0x5c));
				$k_ipad = $key ^ $ipad ;
				$k_opad = $key ^ $opad;

				return md5($k_opad . pack("H*",md5($k_ipad . $data)));
		}

	/*//hmac签名串处理算法
    function HmacMd5($data,$key){
			//需要配置换件支持iconv，否则中文参数不能正常处理
			$key = iconv("GB2312","UTF-8",$key);  //对秘钥编码
			$data = iconv("GB2312","UTF-8",$data);  //对签名串编码
			$b = 64; //md5 字节长度为64
			if(strlen($key) > $b){
			
				$key = pack("H*",md5($key));
			}
					//str_pad() 使用另一个字符串填充字符串为指定长度
			$key = str_pad($key,$b,chr(0x00));  //chr() 返回ASCII 码指定的字符
			$ipad = str_pad('',$b,chr(0x36));
			$opad = str_pad('',$b,chr(0x5c));
			$k_ipad = $key ^ $ipad;
			$k_opad = $key ^ $opad;
			return md5($k_opad.pack("H*",md5($k_ipad. $data)));
	}*/
	
?>