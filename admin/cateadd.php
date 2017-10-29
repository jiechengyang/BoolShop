<?php

   /*
      file   cateadd.php----control控制器
   */
   define('KEYS',true);
   require_once('../include/init.php');//调用了model
   $catemodel = new CateModel();
   $rows = $catemodel->select();
   $tree = $catemodel->catetree($rows,0,1);
   require_once(ROOT.'/view/admin/templates/cateadd.html');//调用view
?>