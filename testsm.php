<?php

/*
	测试 smarty是否引入成功
*/
define('KEYS',true);
require('./lib/smarty3/MySmarty.php');
$smarty = new MySmarty();
echo '<pre>';
print_r($smarty);
echo '</pre>';
?>