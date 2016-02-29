<?php
	//分类模型
	//命名空间
	namespace admin\model;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入Mysql类
	use \admin\Model;
	//分类模型类
	class CategoryModel extends Model{
		//属性：表名
		protected $table = 'category';
		//获取所有分类数据方法
		public function getCategory($stop_id=0){
			$sql = "select * from {$this->getTableName()} order by c_sort";
			$categories = $this->my_selects($sql);
			//无限极分类
			return $this->noLimitCategories($categories,$stop_id);
		}
		//无限极分类方法
		private function noLimitCategories($categories,$stop_id=0,$parent_id=0,$level=0){
			//创建数组保存数据
			static $lists = array();
			//遍历数组
			foreach($categories as $cate){
				//匹配条件，父分类ID等于当前要找的ID
				if($cate['c_parent_id'] == $parent_id && $cate['c_id'] != $stop_id){
					//将当前级别加入到元素本身
					$cate['level'] = $level;
					//存放到目标数组
					$lists[] = $cate;
					//递归
					$this->noLimitCategories($categories,$stop_id,$cate['c_id'],$level+1);
				}
			}
			return $lists;
		}
		//新增验证同名方法：验证新得分类在目标父分类下是否同名
		public function checkCategoryByNameAndParentID($name,$parentID){
			//组织sql语句
			$sql = "select c_id from {$this->getTableName()} where c_parent_id = {$parentID} and c_name = '{$name}'";
			//执行		
			return $this->my_select($sql);
		}
		//将数据添加到数据库
		public function insertCategory($data){
			//调用父类自动新增方法
			return $this->autoInsert($data);
		}
		//将数据从数据库中删除
		public function deleteCategory($id){
			//调用父类自动删除方法
			return $this->autoDelete($id);
		}
		//判断父类不能删除，有文章不能删除
		public function checkCategoryByID($id){
			//组织sql语句
			$sql = "select c_id from {$this->getTableName()} where c_parent_id = {$id} or (c_id = {$id} and c_article_num > 0)";
			//执行，return
			return (boolean)$this->my_select($sql);
		}
		//通过ID获取当前记录
		public function getRecordByID($id){
			//组织sql
			$sql = "select * from {$this->getTableName()} where c_id = {$id}";
			//执行
			return $this->my_select($sql);
		}
		//更新分类
		public function updateCategory($id,$data){
			//调用父类自动修改方法
			return $this->autoUpdate($id,$data);
		}
	}