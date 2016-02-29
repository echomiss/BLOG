<?php
	//评论控制器类
	//命名空间
	namespace home\controller;
	//权限判断
	if(!defined('ACCESS')){
		header('location:../index.php');
	}
	//引入公共控制器类
	use \home\Controller;
	//
	class MessageController extends Controller{
		//默认方法
		public function index(){
			//接收文章ID
			$a_id = isset($_GET['id']) ? $_GET['id'] : 0;
			//调用评论模型
			$message = new \home\model\MessageModel();
			$messages = $message->getMessageByArticleID($a_id);
			$messnum = $message->getMessNumByArticleID($a_id);
			//调用文章模型，获取文章信息
			$article = new \home\model\ArticleModel();
			$articles = $article->getArticleByID($a_id);
			//调用分类模型，获取分类数据
			$category = new \home\model\CategoryModel();
			$categories = $category->getCategory();
			//分配数据给视图
			$this->view->assign('messages', $messages);
			$this->view->assign('messnum', $messnum);
			$this->view->assign('articles', $articles);
			$this->view->assign('categories',$categories);
			//调用视图
			$this->view->display('home_article_view');
		}
	}