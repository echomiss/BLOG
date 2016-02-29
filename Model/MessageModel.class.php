<?php 
	//评论模型
	//命名空间
	namespace home\model;
	//权限判断
	if(!defined("ACCESS")){
		header('../index.php');
	}
	//引入公共模型类
	use \home\Model;
	//
	class MessageModel extends Model{
		//定义属性：表名
		protected $table = 'message';
		//通过文章id获取对应的评论
		public function getMessageByArticleID($a_id){
			//组织sql
			$sql = "select * from {$this->getTableName()} where a_id = {$a_id}";
			//执行
			return $this->my_selects($sql);
		}
		//通过文章id获取对应的文章评论数
		public function getMessNumByArticleID($a_id){
			//组织sql
			$sql = "select count(*) as c from {$this->getTableName()} where a_id = {$a_id}";
			//执行
			return $this->my_select($sql)['c'];
		}
	}