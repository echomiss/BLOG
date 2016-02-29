<?php 
	//评论控制器类
	//命名空间
	namespace admin\controller;
	//权限控制
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入公共控制器类
	use \admin\Controller;
	//
	class MessageController extends Controller{
		//默认首页方法
		public function index(){
			//调用模型，获取数据
			$message = new \admin\model\MessageModel;
			$messages = $message->getMessages();
			//分配数据
			$this->view->assign('messages',$messages);
			//加载模板
			$this->view->display('admin_message_list');
		}
		//删除评论
		public function deleteMess($id){
			
		}
	}