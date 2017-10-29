<?php
 /*
   file goodsaddAct.php
   This is controller function:接收数据   校验数据  调用 GoodsModel.class.php model  以及 view

 */
 define('KEYS',true);
 require_once('../include/init.php');
 $goodsmodel = new GoodsModel();
 $uptool = new UpTool();
 $_POST['goods_weight']*= $_POST['weight_unit'];
  $_POST['goods_name'] = trim($_POST['goods_name']);
  $data = $goodsmodel->facade($_POST);//自动过滤字段
  $data = $goodsmodel->__autofill($data);//自动填充字段
   if(!$goodsmodel->__autovalid($data)){//自动验证
	   $error = $goodsmodel->getError();
		echo implode(',',$error);
		exit;
   }
 $ori_img = $uptool->up('ori_img');
 if(!$ori_img){
 echo   $uptool->getErr();
   exit;
 }
 if(empty($data['goods_sn'])){
  $data['goods_sn'] = $goodsmodel->createSn();
 }
 $data['ori_img'] = $ori_img;
 //通过图片处理类来获取中等图片 300x400
 if($ori_img){
   $ori_img = ROOT.'/'.$ori_img;//取绝对路径
   $goods_img = dirname($ori_img).'/goods_'.basename($ori_img);
   if(ImageTool::thumb($ori_img,$goods_img,300,400)){
		   $data['goods_img'] = str_replace(ROOT.'/','',$goods_img);
   }else{
		echo ImageTool::getErr(),'<br/>';
   }
   $thumb_img = dirname($ori_img).'/thumb_'.basename($ori_img);
   if(ImageTool::thumb($ori_img,$thumb_img,160,220)){
       $data['thumb_img'] = str_replace(ROOT.'/','',$thumb_img);
   }else{
	  echo ImageTool::getErr(),'<br/>';
   }

 }
 //echo"";
  if($goodsmodel->add($data)){
     echo '商品添加成功';
	 echo "<script>window.location.href='goodsadd.php'</script>";
  }else{
    echo '商品添加失败';
  }
?>