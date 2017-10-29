<?php
 /************
   YJC php 之路
 ************/
 /*
   PageTool.class.php
   分页类


   分页原理的三个变量！
   总记录数  $rowcount
   每页显示的条数  $pagesize
   当前页  $page
   问：第$page页，显示第几条到第几条？
   答：第$page页，说明前面已经跳过了$page-1页，每页又是$pagesize条，
	   因此跳过了($page-1)*$pagesie条
	   也就是从  ($page-1)*$pagesie+1 开始取，取$pagesize条出来

 可能的URL地址有: test.php
				  test.php?page=1;
				  test.php?id=1;
				  test.php?id=1&page=1;
				  test.php?id=1&act=show&page=1
 分页导航里
[1] [2] 3 [4] [5]
page导航里,应根据页码来生成,但同时不能把其他参数搞丢,如cat_id


所以 我们先把地址栏的获取并保存起来
 */
 ##########
 defined('KEYS') || exit('jing zhi fang wen');
 class PageTool{
      protected $rowcount = 0;
	  protected $pagesize = 4;
	  protected $page = 1;
      public function __construct($total,$page,$pagesize=false){
	       $this->rowcount = $total;
		   $this->page = $page;
		   if($pagesize){//如果不给，就拉倒，用自己默认的，给了就接收
		      $this->pagesize = $pagesize;
		   }
	  }

	  //主要函数就是创建分页导航
	  public function show(){
	  
	      $pagecount = ceil($this->rowcount/$this->pagesize);//向上取整得到总页数
		  $uri = $_SERVER['REQUEST_URI'];
		  //echo $uri;
		  $parse = parse_url($uri);//如果地址无参数 那么就没有  [query]这个单元，返回数组
		  /*echo '<pre>';
		  print_r($parse);
		  echo '</pre>';*/
		  $query = array();
		  if(isset($parse['query'])){
			parse_str($parse['query'],$query);//如果 str 是 URL 传递入的查询字符串（query string），则将它解析为变量并设置到当前作用域
		  }
		  unset($query['page']);//因为page是计算出来，不能保存在哪，因此不管有没有page 都要把它删除
		   /*echo '<pre>';
		  print_r($query);
		  echo '</pre>';
		 [id] => 1
		  [page] => 2*/
		  $url = $parse['path'].'?';///BoolShop/tool/PageTool.class.php?
		  if(!empty($query)){//如果数组不为空 表示URL参数有几个且page前面也有参数 那么page前面就是&  同时如果只有page一个参数那么就是?
		     $query = http_build_query($query);
			 $url .= $query.'&';
		  }
         //echo $url;
		 //计算页面导航
		 //首页
		 echo '<a href="'.$url.'page='. 1 .'">首页</a>';
		 //上一页
		 if($this->page <= 1){
			
		   echo  '<span>上一页</span>';
		 }else{
			 $prepage =  $this->page-1;
		    echo '<a href="'.$url.'page='. $prepage .'">上一页</a>';
		 }
		 
		
		 //最后一页 
		 $this->lastpage = '<a href="'.$url.'page='. $pagecount .'">末页</a>';
		 $nav = array();
		 $nav[0] = '<span class="page_now">'. $this->page. '</span>';
		 for($left = $this->page-1,$right=$this->page+1;($left>=1||$right<=$pagecount)&&count($nav) <= 5;){
		    //条件：当前页能前后走 同时 导航条在规定的5个内
				if($left >=1 ){
				  array_unshift($nav,'<a href="'.$url.'page='.$left.'">['.$left.']</a>');
				  $left-=1;
				}
				if($right <=$pagecount){
				  array_push($nav,'<a href="'.$url.'page='.$right.'">['.$right.']</a>');
				  $right+=1;
				}
		 }

         return implode('',$nav);
	  }
	 
 }
/* $page = $_GET['page']?$_GET['page']:1;
  $pagetool = new PageTool(10,$page,3);
 echo  $pagetool->show();
 */
?>