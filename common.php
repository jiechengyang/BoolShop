<?php
	//ƴ��ǩ����
	function JoinHmac($var1,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$var9,$var10,$var11,$var12,$var13){
			$data = '';
			/*
				$p0_Cmd = 'Buy'; //ҵ������
				$p1_MerId = '10001126856'; //��Ʒ���
				$p2_Order = $_POST['p2_Order']; //������
				$p3_Amt = $_POST['p3_Amt']; //֧�����
				$p4_Cur = 'CNY';  //֧������
				$p5_Pid = ''; //��Ʒ����
				$p6_Pcat = ''; //��Ʒ����
				$p7_Pdesc = ''; //��Ʒ����
				$p8_Url = "http://www.y_phpstudy.com.cn/InterestPay/Res.php";  //�̻�����֧���ɹ����ݵĵ�ַ���ȸ�url���ص���Ϣ��
				$p9_SAF = "0"; //�ͻ���ַ
				$pa_MP = '';//�̻���չ��Ϣ
				$pd_FrpId = $_POST['pd_FrpId']; //֧��ͨ������
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

				//��Ҫ���û���֧��iconv���������Ĳ���������������
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

	/*//hmacǩ���������㷨
    function HmacMd5($data,$key){
			//��Ҫ���û���֧��iconv���������Ĳ���������������
			$key = iconv("GB2312","UTF-8",$key);  //����Կ����
			$data = iconv("GB2312","UTF-8",$data);  //��ǩ��������
			$b = 64; //md5 �ֽڳ���Ϊ64
			if(strlen($key) > $b){
			
				$key = pack("H*",md5($key));
			}
					//str_pad() ʹ����һ���ַ�������ַ���Ϊָ������
			$key = str_pad($key,$b,chr(0x00));  //chr() ����ASCII ��ָ�����ַ�
			$ipad = str_pad('',$b,chr(0x36));
			$opad = str_pad('',$b,chr(0x5c));
			$k_ipad = $key ^ $ipad;
			$k_opad = $key ^ $opad;
			return md5($k_opad.pack("H*",md5($k_ipad. $data)));
	}*/
	
?>