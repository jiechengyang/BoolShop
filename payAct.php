<!doctype html>
<html>
<head>
	 <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	 <title>支付处理确认</title>
   </head>
   <body>
		<?php
			if($_POST != null){
				require_once("common.php");
				$p0_Cmd = 'Buy'; //业务类型
				$p1_MerId = '10001126856'; //商品编号---测试号
				$p2_Order = $_POST['p2_Order']; //订单号
				$p3_Amt = $_POST['p3_Amt']; //支付金额
				$p4_Cur = 'CNY';  //支付币种
				$p5_Pid = ''; //商品名称
				$p6_Pcat = ''; //商品种类
				$p7_Pdesc = ''; //商品描述
				$p8_Url = "http://localhost/BoolShop/Res.php";  //商户接收支付成功数据的地址（既给url返回的信息）
				$p9_SAF = "0"; //送货地址
				$pa_MP = '';//商户扩展信息
				$pd_FrpId = $_POST['pd_FrpId']; //支付通道编码
				$pr_NeedResponse = "1";
				//拼接
				$data = JoinHmac($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse); //得到签名串
				/*echo $data;
				exit;*/
				$merchanKey = "69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl"; //秘钥
				$hmac = HmacMd5($data,$merchanKey); //处理算法
				
			
		?>
	   <div style="width:400px;height:300px;border:2px solid #000">
			你的订单号是<?php echo $p2_Order; ?>支付的金额是<?php echo $p3_Amt."<br/>" ?>
		<form action="https://www.yeepay.com/app-merchant-proxy/node" method="post">
		   <!--把要提交的数据用隐藏域接收-->
				<input type="hidden" name="p0_Cmd" value="<?php echo $p0_Cmd;?>"/>
				<input type="hidden" name="p1_MerId" value="<?php echo $p1_MerId; ?>"/>
				<input type="hidden" name="p2_Order" value="<?php echo $p2_Order; ?>"/>
				<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt; ?>"/>
				<input type="hidden" name="p4_Cur" value="<?php echo $p4_Cur; ?>"/>
				<input type="hidden" name="p5_Pid" value="<?php echo $p5_Pid; ?>"/>
				<input type="hidden" name="p6_Pcat" value="<?php echo $p6_Pcat; ?>"/>
				<input type="hidden" name="p7_Pdesc" value="<?php echo $p7_Pdesc; ?>"/>
				<input type="hidden" name="p8_Url" value="<?php echo $p8_Url; ?>"/>
				<input type="hidden" name="p9_SAF" value="<?php echo $p9_SAF; ?>"/>
				<input type="hidden" name="pa_MP" value="<?php echo $pa_MP; ?>"/>
				<input type="hidden" name="pd_FrpId" value="<?php echo $pd_FrpId; ?>"/>
				<input type="hidden" name="pr_NeedResponse" value="<?php echo $pr_NeedResponse; ?>"/>
				<input type="hidden" name="hmac" value="<?php echo $hmac; ?>"/>
				<input type="submit"  value="确认支付"/>
		</form>
	   </div>
	   <?php
		}
		?>
   </body>
   </html>