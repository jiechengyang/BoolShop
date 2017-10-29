<?php

  /*
	file cateupdAct.php
	用于接收修改栏目表单的数据 修改栏目的control
	*/
	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';*/
	define('KEYS',true);
	require_once('../include/init.php');
	if(empty($_POST['catename'])){
	   die('分类名称不能为空');
	}
	$cate['cate_id'] = $_POST['cate_id'] + 0;
	$cate['catename'] = rtrim($_POST['catename'],'');
	$cate['parent_id'] = $_POST['parent_id'] + 0;
	$cate['intro'] = $_POST['intro'];
	//如果这里接收的修改的上级分类$cate['parent_id']是这个id的子孙栏目的话就不能修改了 同级是可以修改的
	$catemodel = new CateModel();
	/*$catelist = $catemodel->select();
	$updflag = $catemodel->is_subtree($catelist,$cate['parent_id'],$cate['cate_id']);
	var_dump($updflag);
	exit;
	if(!$updflag){
		if($catemodel->update($cate,$cate['cate_id']) > 0){
				echo '栏目修改成功';
		}else{
				echo '栏目修改失败';
		}
	}else{
	  echo '对不起你不能讲你的上级栏目修改为子孙栏目';
	  exit;
	}
	 我之前的方法退出历史舞台
	*/
	$p_tree = $catemodel->get_partree($cate['parent_id']);
	$flag = true; //默认是可以修改
	foreach($p_tree as $v){
	   if($v['cate_id'] == $cate['cate_id']){
				
				 $flag = false;
				 break;
	   }
	}
	if(!$flag){
		 echo '所修改的上级栏目的id里存在该栏目';
		 exit;
	}else{
		if($catemodel->update($cate,$cate['cate_id']) > 0){
				echo '栏目修改成功';
		}else{
				echo '栏目修改失败';
		}
			
	}
?>