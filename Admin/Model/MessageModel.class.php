<?php
	//评论模型
	//命名空间
	namespace admin\model;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入公共模型
	use \admin\Model;
	//
	class MessageModel extends Model{
		//属性：表名
		protected $table = 'message';
		//获取所有评论方法
		public function getMessages(){
			//组织sql
			$sql = "select * from {$this->getTableName()}";
			//执行
			return $this->my_selects($sql);
		}
	}