<?php
	//文章控制器
	//命名空间
	namespace home\controller;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../index.php');
	}
	//引入公共控制器
	use \home\Controller;
	//
	class ArticleController extends Controller{
		//首页方法
		public function index(){
			//接收id
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//通过分类id获取对应文章信息
			$article = new \home\model\ArticleModel();
			$articles = $article->getArticleByCategoryID($id);
			//获取文章分类信息
			$category = new \home\model\CategoryModel();
			$categories = $category->getCategory();
			//分配数据给视图
			$this->view->assign('categories',$categories);
			$this->view->assign('articles',$articles);
			//调用视图
			$this->view->display('home_article');
		}
	}