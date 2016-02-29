<?php
	//命名空间
	namespace admin\model;
	//判断合法性
	if(!defined('ACCESS')){
		header('Location:../admin.php');
	}
	use \admin\Model;
	//模型类，继承Mysql类
	class AdminModel extends Model{
		//属性：表名
		protected $table = 'admin';
		//通过用户名获取用户信息
		public function getUserbyUsername($account){
			//SQL语句
			$sql = "select * from {$this->getTableName()} where a_account = '$account'";
			//执行SQL语句
			return $this->my_select($sql);
		}
		//通过用户Id获取用户信息
		public function getRecordById($id){
			//SQL语句
			$sql = "select * from {$this->getTableName()} where a_id = {$id}";
			//执行SQL语句
			return $this->my_select($sql);
		}
		//更新用户登录信息
		public function updateLoginInfo($id){
			//获取当前时间
			$now = time();
			//获取IP地址
			$ip = $_SERVER['REMOTE_ADDR'];
			//SQL语句
			$sql = "update {$this->getTableName()} set a_last_time = {$now},a_last_ip = '{$ip}' where a_id = {$id}";
			//执行SQL语句
			return $this->my_write($sql);
		}
	}
