<?php
	//文章管理模型类
	//命名空间
	namespace admin\model;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入Model类
	use \admin\Model;
	//
	class ArticleModel extends Model{
		//属性：表名
		protected $table = 'article';
		//获取所有文章数据
		public function getArticle($offset,$pagecount,$a_recover=1){
			//组织sql
			$sql = "select a.*,m.c_name from {$this->getTableName()} as a left join {$this->getTableName('category')} as m on a.c_id = m.c_id";
			$wherelimit = '';
			//是否放入回收站
			$wherelimit .= " where a_recover = {$a_recover} limit {$offset},{$pagecount}";
			$sql .= $wherelimit;
			//执行
			return $this->my_selects($sql);
		}
		//搜索文章
		public function searchArticle($c_id=0,$a_status=0,$name='',$a_recover=1,$offset,$pagecount){
			//组织sql
			$sql = "select a.*,m.c_name from {$this->getTableName()} as a left join {$this->getTableName('category')} as m on a.c_id = m.c_id";
			//判断条件
			$where = ' where';
			//类型条件
			if($c_id != 0){
				$where .= " a.c_id = {$c_id}";
			}else{
				$where .= " a.c_id = a.c_id";
			}
			//状态条件
			if($a_status != 0){
				$where .= " and a_status = {$a_status}";
			}else{
				$where .= " and a_status = a_status";
			}
			//判断是否被回收
			$where .= " and a_recover = {$a_recover}";
			//名字条件
			if($name != ''){
				$where .= " and a_name like '%{$name}%'";
			}else{
				$where .= " and a_name = a_name";
			}
			$limit = " limit {$offset},{$pagecount}";
			$sql .= $where.$limit;
			//执行
			return $this->my_selects($sql);
		}
		//新增文章
		public function insertArticle($data){
			//调用父类自动新增方法
			return $this->autoInsert($data);
		}
		//放入回收站
		public function putinRecycled($id){
			//组织sql
			$sql = "update {$this->getTableName()} set a_recover = 2 where a_id = {$id}";
			//执行
			return $this->my_write($sql);
		}
		//删除文章
		public function deleteArticle($id){
			//调用父类自动删除方法
			return $this->autoDelete($id);
		}
		//通过id获取文章信息
		public function getArticleByID($id){
			//组织sql
			$sql = "select a.*,m.c_name from {$this->getTableName()} as a left join {$this->getTableName('category')} as m on a.c_id = m.c_id where a_id = {$id}";
			//执行
			return $this->my_select($sql);
		}
		//更新文章数量
		public function updateArticleNum($c_id,$bool = true){
			//组织sql,判断$bool
			if($bool){
				$sql = "update {$this->getTableName('category')} set c_article_num = c_article_num + 1 where c_id = {$c_id}";
			}else{
				$sql = "update {$this->getTableName('category')} set c_article_num = c_article_num - 1 where c_id = {$c_id}";
			}
			//返回
			return $this->my_write($sql);
		}
		//修改文章入库
		public function updateArticle($id,$data){
			//调用父类自动修改方法
			return $this->autoUpdate($id,$data);
		}
		//从回收站中还原文章
		public function restoreArticle($id){
			//组织sql
			$sql = "update {$this->getTableName()} set a_recover = 1 where a_id = {$id}";
			//执行
			return $this->my_write($sql);
		}
		//分类：获取文章总记录数方法
		public function getArticleCounts($c_id=0,$a_status=0,$name='',$a_recover=1){
			//组织sql
			$sql = "select count(*) as c from {$this->getTableName()} as a left join {$this->getTableName('category')} as m on a.c_id = m.c_id";
			//判断条件
			$where = ' where';
			//类型条件
			if($c_id != 0){
				$where .= " a.c_id = {$c_id}";
			}else{
				$where .= " a.c_id = a.c_id";
			}
			//状态条件
			if($a_status != 0){
				$where .= " and a_status = {$a_status}";
			}else{
				$where .= " and a_status = a_status";
			}
			//判断是否被回收
			$where .= " and a_recover = {$a_recover}";
			//名字条件
			if($name != ''){
				$where .= " and a_name like '%{$name}%'";
			}else{
				$where .= " and a_name = a_name";
			}
			$sql .= $where;
			//执行
			return $this->my_select($sql)['c'];
		}
	}