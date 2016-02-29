<?php
	//命名空间
	namespace home;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//公共模型类
	class Model extends Mysql{
		//只给子类继承用，生成表名
		protected function getTableName($table = ''){
			//如果用户有传入表名: 获取表名,否则使用系统自带(子类模型)
			if($table != '') return $this->prefix . $table;
			//连起来父类的前缀, 子类的表名
			return $this->prefix . $this->table;
		}
	}