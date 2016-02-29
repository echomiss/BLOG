<?php
	//命名空间
	namespace home;
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
	}