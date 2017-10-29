<?php
	//echo "支付成功";
	/*
	
	  支付页面  返回回来 应该这数据库吧是否已支付的标识改为1
	*/
	header('content-type:text/html;charset=utf-8');
	require_once("common.php");
	//获取从易宝支付网关返回的信息
	$p1_MerId = '10001126856'; //商户编号
	$r0_Cmd = $_REQUEST['r0_Cmd'];//业务类型
	$r1_Code = $_REQUEST['r1_Code']; //支付结果
	$r2_TrxId = $_REQUEST['r2_TrxId'];//易宝支付交易流水号
	$r3_Amt = $_REQUEST['r3_Amt'];//支付金额
	$r4_Cur = $_REQUEST['r4_Cur']; //交易币种
	$r5_Pid = $_REQUEST['r5_Pid'];//商品名称
	$r6_Order = $_REQUEST['r6_Order'];//商户订单号
	$r7_Uid = $_REQUEST['r7_Uid'];//易宝支付会员ID
	$r8_MP = $_REQUEST['r8_MP'];//商户扩展信息
	$r9_BType = $_REQUEST['r9_BType']; //交易结果返回类型
	$hmac = $_REQUEST['hmac'];//签名串
	$resdata = ResJoinHmac($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType);
	$merchanKey = "69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl"; //秘钥
	if(HmacMd5($resdata,$merchanKey) == $hmac){
	
		if($r1_Code == 1){
			if($r9_BType == 1){
			
				echo "交易成功";
				echo "订单号为:".$r6_Order.'支付成功'.'所付金额为:'.$r3_Amt.'易宝支付订单号为'.$r2_TrxId;
				echo "<br/>浏览器重定向";
			}else if($r9_BType == 2){
			
				echo 'success';
				echo "<br/>交易成功<br/>服务器点对点通讯";
			}
		
		}else{
			echo "签名被篡改";
		}
	}
?>