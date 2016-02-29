<?php
	//命名空间
	namespace admin;
	//判断权限
	if(!defined('ACCESS')){
		header('Location:../admin.php');
	}
	//公共控制器
	class Controller{
		//定义属性，保存视图类
		protected $view;
		//构造方法
		public function __construct(){
			//初始化view属性
			$this->view = new View();
		}
		//公共跳转提示方法
		//验证失败
		protected function redirect_fail($url,$msg,$time=3){
			//调用模板
			include_once ADMIN_VIEW . 'Public/admin_redirect_fail.htm';
			exit;
		}
		//验证成功
		protected function redirect_success($url,$msg,$time=3){
			//调用模板
			include_once ADMIN_VIEW . 'Public/admin_redirect_success.htm';
			exit;
		}
	}