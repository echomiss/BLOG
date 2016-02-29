<?php
	//文章模型类
	//命名空间
	namespace home\model;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../index.php');
	}
	//引入Model类
	use \home\Model;
	//
	class ArticleModel extends Model{
		protected $table = "article";
		//通过分类id获取对应文章信息
		public function getArticleByCategoryID($id){
			//组织sql
			$sql = "select a.*,m.c_name from {$this->getTableName()} as a left join {$this->getTableName('category')} as m on a.c_id = m.c_id where a.c_id = {$id}";
			//执行
			return $this->my_selects($sql);
		}
		//通过文章id获取对应文章信息
		public function getArticleByID($id){
			//组织sql
			$sql = "select a.*,c.c_name from {$this->getTableName()} as a left join {$this->getTableName('category')} as c on a.c_id = c.c_id where a.a_id = {$id}";
			//执行
			return $this->my_select($sql);
		}
	}