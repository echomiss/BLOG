<?php
	//命名空间
	namespace home\controller;
	//权限验证
	if(!defined('ACCESS')){
		header('Location:../index.php');
	}
	use \home\Controller;
	//首页控制器
	class IndexController extends Controller{
		//加载框架
		public function index(){
			//获取文章分类信息
			$category = new \home\model\CategoryModel();
			$categories = $category->getCategory();
			//分配数据给视图
			$this->view->assign('categories',$categories);
			//调用视图类对象加载模板
			$this->view->display('home_index');
		}
	}