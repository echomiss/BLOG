<?php
	//命名空间
	namespace home;
	//判断权限
	if(!defined('ACCESS')){
		header('Location:../index.php');
	}
	//视图类
	class View{
		//增加属性，用来保存数据
		private $data = array();
		//加载数据显示模板
		public function display($template){
			//加载模板
			include_once HOME_VIEW . MODULE . '/' . $template . '.htm';
		}
		//给属性赋值
		public function assign($name,$value){
			$this->data[$name] = $value;
		}
	}