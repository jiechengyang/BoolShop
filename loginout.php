<?php
 /************
   YJC php 之路
 ************/
 /*
   loginout.php  退出

 */
 ##########
  define('KEYS',true);
  require_once('./include/init.php');
  unset($_SESSION['username']);
  session_destroy();
  echo "<script>window.location.href='index.php'</script>";
?>