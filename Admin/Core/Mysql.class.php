<?php
	//PDO类
	//命名空间
	namespace admin;
	//设置权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//
	class Mysql{
		//定义属性
		private $type;
		private $host;
		private $port;
		private $user;
		private $pass;
		private $dbname;
		private $charset;
		protected $prefix;
		//对象保存PDO类
		private $pdo;
		//构造方法    初始化数据
		public function __construct($arr=array()){
			$this->type = isset($arr['type']) ? $arr['type'] : $GLOBALS['config']['type'];
			$this->host = isset($arr['host']) ? $arr['host'] : $GLOBALS['config'][$this->type]['host'];
			$this->port = isset($arr['port']) ? $arr['port'] : $GLOBALS['config'][$this->type]['port'];
			$this->user = isset($arr['user']) ? $arr['user'] : $GLOBALS['config'][$this->type]['user'];
			$this->pass = isset($arr['pass']) ? $arr['pass'] : $GLOBALS['config'][$this->type]['pass'];
			$this->dbname = isset($arr['dbname']) ? $arr['dbname'] : $GLOBALS['config'][$this->type]['dbname'];
			$this->charset = isset($arr['charset']) ? $arr['charset'] : $GLOBALS['config'][$this->type]['charset'];
			$this->prefix = isset($arr['prefix']) ? $arr['prefix'] : $GLOBALS['config'][$this->type]['prefix'];
			//连接认证
			$this->my_connect();
		}
		//连接认证，设置字符集，使用数据库
		private function my_connect(){
			//PDO对象
			try{
				$this->pdo = @new \PDO("$this->type:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}",$this->user,$this->pass);
				//开启异常
				$this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
			}catch(\PDOException $e){
				echo '错误：数据库连接失败！<br />';
				echo '错误文件是：' . $e->getFile() . '<br />';
				echo '错误行号是：' . $e->getLine() . '<br />';
				echo '错误编号是：' . $e->getCode() . '<br />';
				echo '错误信息是：' . iconv('GBK','UTF-8',$e->getMessage()) . '<br />';
				exit;
			}
		}
		//写操作
		public function my_write($sql){
			//执行sql：PDO对象的类
			try{
				//执行结果得到的是受影响行数
				$res = $this->pdo->exec($sql);
				//判断是否有自增长
				$id = $this->pdo->lastInsertid();
				//返回
				return $id ? $id : $res;
			}catch(\PDOException $e){
				echo 'SQL语句错误！<br />';
				echo '错误文件是：' . $e->getFile() . '<br />';
				echo '错误行号是：' . $e->getLine() . '<br />';
				echo '错误编号是：' . $e->getCode() . '<br />';
				echo '错误信息是：' . iconv('GBK','UTF-8',$e->getMessage()) . '<br />';
				exit;				
			}
		}
		//公共查询方法错误处理
		private function my_query($sql){
			//检查错误
			try{
				//成功，返回PDOStatement类对象
				return $this->pdo->query($sql);
			}catch(\PDOException $e){
				echo 'SQL语句错误！<br />';
				echo '错误文件是：' . $e->getFile() . '<br />';
				echo '错误行号是：' . $e->getLine() . '<br />';
				echo '错误编号是：' . $e->getCode() . '<br />';
				echo '错误信息是：' . iconv('GBK','UTF-8',$e->getMessage()) . '<br />';
				exit;		
			}
		}
		//读取一条记录
		public function my_select($sql){
			//调用query方法
			$stmt = $this->my_query($sql);
			//返回获取到的数据
			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}
		//读取多条数据
		public function my_selects($sql){
			//调用query方法
			$stmt = $this->my_query($sql);
			//返回获取到的数据
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
	}