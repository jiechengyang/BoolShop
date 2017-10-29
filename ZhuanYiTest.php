<?php

  // addslashes — 使用反斜线引用字符串
  /*
     函数说明：
	             返回字符串，该字符串为了数据库查询语句等的需要在某些字符前加上了反斜线。这些字符是单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。 

一个使用 addslashes() 的例子是当你要往数据库中输入数据时。例如，将名字 O'reilly 插入到数据库中，这就需要对其进行转义。大多数据库使用 \ 作为转义符：O\'reilly。这样可以将数据放入数据库中，而不会插入额外的 \。当 PHP 指令 magic_quotes_sybase 被设置成 on 时，意味着插入 ' 时将使用 ' 进行转义。 

默认情况下，PHP 指令 magic_quotes_gpc 为 on，它主要是对所有的 GET、POST 和 COOKIE 数据自动运行 addslashes()。不要对已经被 magic_quotes_gpc 转义过的字符串使用 addslashes()，因为这样会导致双层转义。遇到这种情况时可以使用函数 get_magic_quotes_gpc() 进行检测。 

  */
  $str = "Is your name O'reilly?t''";
  echo addslashes($str);
?>