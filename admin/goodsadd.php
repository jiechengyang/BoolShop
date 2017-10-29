<?php
 /*
   file goodsadd.php
   This is a controller   function:引入视图
 */
   define('KEYS',true);
   require_once('../include/init.php');
    $catemodel = new CateModel();
   $rows = $catemodel->select();
   $tree = $catemodel->catetree($rows,0,1);
   require_once(ROOT.'/view/admin/templates/goodsadd.html');
?>