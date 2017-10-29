<?php

 /*
   file  paging.class.php
   这是一个分页类  用于实现页面的分页
 */
 defined('KEYS') || exit('jing zhi fang wen');
 class paging{
     public $pagesize = 2; //每页显示的记录数
	 public $res_list = array();//分页的表的记录
	 public $pagenow = 1;//当前页
	 public $prepage = null;//上一页
	 public $nextpage = null;//下一页
	 public $pagecount = null;//总页数
	 public $rowcount = null; //总记录数
	 public $res = array();//分页sql返回的结果集
	 public $url = ''; //需要分页的页面的地址
	 public $keyword = ''; //带搜索关键字的URL的分页
	 public $navigate = '';//导航条
	 public $page_whole; //每$page_whole页翻页----通俗点在 1到10页  无论点哪一页 1到10页的连接不变
	 public $where = ''; //分页sql语句的条件
 }
?>