<?php
 /************
   YJC php 之路
 ************/
 /*
   category.php
 */
 ##########
   define('KEYS',true);
   require_once('./include/init.php');
   $cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;//不存在的就给个0
   $page = isset($_GET['page'])?$_GET['page']+0:1;
   if($page < 1){
     $page = 1;
   }
   $goodsmodel = new GoodsModel();
   $catemodel = new CateModel();
   $rowcount = $goodsmodel->getRowCount($cat_id);//得到总记录数
  // 每页取2条
   $pagesize = 4;
   if($page > ceil($rowcount/$pagesize)){//如果当前页超过了总页数 那就表明页面没记录 我们就回到第一页
     $page = 1;
   }
  $pagetool = new PageTool($rowcount,$page,$pagesize);
  $offset = ($page-1)*$pagesize;//偏移量
   //1.左边的产品分类
    $cats = $catemodel->select();
   $catelist = $catemodel->catetree($cats,0,1);//所有的产品分类
   if(empty($catelist)){//可能有人在地址栏里随意输入
	   header('location:index.php');
     exit;
   }
   //取出当前的栏目名
   $catename = $catemodel->getNowCate($cat_id);
    /*echo '<pre>';
   print_r($catelist);
   echo '</pre>';
   exit;*/
   //2.面包屑导航条
   $nav = array_reverse($catemodel->get_partree($cat_id));
	/*echo '<pre>';
   print_r($parentTree);
   echo '</pre>';
   exit;*/
   //3.右边的商品展示
   
    $goodslist = $goodsmodel->getClothing($cat_id,$offset,$pagesize);
	/*echo '<pre>';
   print_r($goodslist);
   echo '</pre>';
   exit;*/
   //4.浏览历史
    /*
	*/
	
   require_once(ROOT.'/view/front/lanmu.html');
?>