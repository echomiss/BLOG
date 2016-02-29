<?php
	//命名空间
	namespace admin;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//公共模型类
	class Model extends Mysql{
		//定义属性
		protected $fields = array();
		//构造方法
		public function __construct($arr = array()){
			//调用父类构造方法
			parent::__construct($arr);
			//调用获取表字段方法
			$this->getFields();
		}
		//只给子类继承用，生成表名
		protected function getTableName($table = ''){
			//如果用户有传入表名: 获取表名,否则使用系统自带(子类模型)
			if($table != '') return $this->prefix . $table;
			//连起来父类的前缀, 子类的表名
			return $this->prefix . $this->table;
		}
		//制作方法获取所有表字段信息
		protected function getFields(){
			//组织sql
			$sql = "desc {$this->getTableName()}";
			//执行
			$res = $this->my_selects($sql);
			//遍历
			foreach($res as $v){
				//将字段保存到fields属性中
				$this->fields[$v['Field']] = $v['Field'];
				//判断字段是否为主键
				if($v['Key'] == "PRI"){
					//主键
					$this->fields['pk'] = $v['Field'];
				}
			}
		}
		//自动新增方法
		protected function autoInsert($data){
			$fields = $values = '';
			foreach($data as $k => $v){
				$fields .= $k.',';
				$values .= "'{$v}'".',';
			}
			$fields = rtrim($fields,',');
			$values = rtrim($values,',');
			//组织sql语句
			$sql = "insert into {$this->getTableName()}($fields) values($values)";
			//执行
			return $this->my_write($sql);
		}
		//自动修改方法
		protected function autoUpdate($id,$data){
			$update = '';
			foreach($data as $k => $v){
				$update .= "$k = '{$v}',";
			}
			$update = rtrim($update,',');
			//组织sql
			$sql = "update {$this->getTableName()} set {$update} where {$this->fields['pk']} = {$id}";
			//返回
			return $this->my_write($sql); 
		}
		//自动删除方法
		protected function autoDelete($id,$first){
			//组织sql语句
			$sql = "delete from {$this->getTableName()} where {$this->fields['pk']} = {$id}";
			//执行
			return $this->my_write($sql);			
		}
	}