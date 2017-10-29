<?php
  /*
     file cateupd.php
 
  */
  define('KEYS',true);
  require_once('../include/init.php');
  if(empty($_GET['cate_id'])){
		exit;
  }
  $cid = $_GET['cate_id'] + 0;
  $catemodel = new CateModel();
  $rowone =  $catemodel->find($cid);
  $catelist = $catemodel->select();
  $tree = $catemodel->catetree($catelist,0,1);
  require_once(ROOT.'/view/admin/templates/cateupd.html');
?>