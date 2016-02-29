<?php
	//命名空间
	namespace admin\controller;
	//权限验证
	if(!defined('ACCESS')){
		header('Location:../admin.php');
	}
	use \admin\Controller;
	//首页控制器
	class IndexController extends Controller{
		//加载框架
		public function index(){
			//调用视图类对象加载模板
			$this->view->display('admin_index');
		}
		public function header(){
			//分配给视图对象
			$this->view->assign('username', $_SESSION['user']['a_nickname']);
			$this->view->assign('last_time',date('Y-m-d H:i:s',$_SESSION['user']['a_last_time']));
			$this->view->assign('last_ip',$_SESSION['user']['a_last_ip']);
			//调用模板
			$this->view->display('admin_header');
		}
		public function aside(){
			$this->view->display('admin_aside');
		}
		public function main(){
			$this->view->display('admin_main');
		}
	}