<?php
	//命名空间
	namespace admin\vender;
	//判断权限
	if(!defined('ACCESS')){
		header('Location:../admin.php');
	}
	//引入Mysql类
	use \admin\Model;
	//定义修改Session机制类
	class Session extends Model{
		//属性：表名
		protected $table = 'session';
		//构造方法
		public function __construct(){
			//调用父类的构造方法
			parent::__construct();
			//注册
			session_set_save_handler(array($this,'s_open'),array($this,'s_close'),array($this,'s_read'),array($this,'s_write'),array($this,'s_destroy'),array($this,'s_gc'));
			//启动session机制
			session_start();
		}
		//初始化数据库
		public function s_open(){
			//继承的时候已经连接上
		}
		//关闭数据库
		public function s_close(){}
		//读取Session数据
		public function s_read($id){
			$sql = "select s_content from {$this->getTableName()} where s_id = '{$id}'";
			$res = $this->my_select($sql);
			//判断返回
			return $res ? $res['s_content'] : '';
		}
		//将Session数据写入数据库
		public function s_write($id,$cont){
			$time = time();
			$sql = "replace into {$this->getTableName()} values('{$id}','{$cont}',{$time})";
			return $this->my_write($sql);
		}
		//删除Session数据
		public function s_destroy($id){
			$sql = "delete from {$this->getTableName()} where s_id = '{$id}'";
			return $this->my_write($sql);
		}
		//垃圾回收
		public function s_gc(){
			$now = time();
			$lifetime = ini_get('session.gc_maxlifetime');
			$sql = "delete from {$this->getTableName()} where s_last < {$now} - {$lifetime}";
			return $this->my_write($sql);
		}
	}