<?php
	//echo "֧���ɹ�";
	/*
	
	  ֧��ҳ��  ���ػ��� Ӧ�������ݿ���Ƿ���֧���ı�ʶ��Ϊ1
	*/
	header('content-type:text/html;charset=utf-8');
	require_once("common.php");
	//��ȡ���ױ�֧�����ط��ص���Ϣ
	$p1_MerId = '10001126856'; //�̻����
	$r0_Cmd = $_REQUEST['r0_Cmd'];//ҵ������
	$r1_Code = $_REQUEST['r1_Code']; //֧�����
	$r2_TrxId = $_REQUEST['r2_TrxId'];//�ױ�֧��������ˮ��
	$r3_Amt = $_REQUEST['r3_Amt'];//֧�����
	$r4_Cur = $_REQUEST['r4_Cur']; //���ױ���
	$r5_Pid = $_REQUEST['r5_Pid'];//��Ʒ����
	$r6_Order = $_REQUEST['r6_Order'];//�̻�������
	$r7_Uid = $_REQUEST['r7_Uid'];//�ױ�֧����ԱID
	$r8_MP = $_REQUEST['r8_MP'];//�̻���չ��Ϣ
	$r9_BType = $_REQUEST['r9_BType']; //���׽����������
	$hmac = $_REQUEST['hmac'];//ǩ����
	$resdata = ResJoinHmac($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType);
	$merchanKey = "69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl"; //��Կ
	if(HmacMd5($resdata,$merchanKey) == $hmac){
	
		if($r1_Code == 1){
			if($r9_BType == 1){
			
				echo "���׳ɹ�";
				echo "������Ϊ:".$r6_Order.'֧���ɹ�'.'�������Ϊ:'.$r3_Amt.'�ױ�֧��������Ϊ'.$r2_TrxId;
				echo "<br/>������ض���";
			}else if($r9_BType == 2){
			
				echo 'success';
				echo "<br/>���׳ɹ�<br/>��������Ե�ͨѶ";
			}
		
		}else{
			echo "ǩ�����۸�";
		}
	}
?>