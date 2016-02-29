<?php
	//命名空间
	namespace home\model;
	//权限验证
	if(!defined('ACCESS')){
		header('Location:../index.php');
	}
	use \home\Model;
	//分类模型
	class CategoryModel extends Model{
		//属性：表名
		protected $table = 'category';
		//获取数据方法
		public function getCategory(){
			//组织sql语句
			$sql = "select * from {$this->getTableName()}";
			//执行
			$categories = $this->my_selects($sql);
			//无限极分类
			return $this->noLimitCategories($categories);
		}
		//无限极分类方法
		private function noLimitCategories($categories,$id=0,$level=0){
			static $lists = array();
			//遍历数组
			foreach($categories as $k => $v){
				//匹配
				if($v['c_parent_id'] == $id){
					$v['level'] = $level;
					//保存到数组
					$lists[] = $v;
					//删除原数组中已获取过的元素
					unset($categories[$k]);
					//递归
					$this->noLimitCategories($categories,$v['c_id'],$level+1);
				}
			}
			return $lists;
		}
	}
	