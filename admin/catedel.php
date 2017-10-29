<?php
   /*
    file catedel.php 
	用于栏目表的删除
   */
   define("KEYS",true);
   require_once('../include/init.php');
   $cateid = $_GET['cate_id'] + 0; //防止sql语句注入   例子:$id = '1 or 1' 加0后 结果就是1 因为算术运算符有一个字符串就要转化成int
   $catemodel = new CateModel();
  /* $catelist = $catemodel->select();
   $delflag = $catemodel->is_son($catelist,$cateid);
   var_dump($delflag);
   exit;
   if(!$delflag){
		if($catemodel->delete($cateid) > 0){
			echo '删除成功';
		}else{
			 echo '删除失败';
		   }
   }else{
		echo "该栏目不能删除，它有子栏目了";
		exit;
   }
   我之前的方案退出历史舞台
   */
   $son = $catemodel->exist_son($cateid);
   //echo count($son);
   if(empty($son)){
		if($catemodel->delete($cateid) > 0){
			echo '删除成功';
		}else{
			 echo '删除失败';
		   }
   }else{
		echo "该栏目不能删除，它有子栏目了";
		exit;
   }
?>