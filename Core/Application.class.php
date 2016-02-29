<?php
	//命名空间
	namespace home;
	//判断是否合法访问
	if(!defined('ACCESS')){
		//跳转
		header("location:../index.php");
	}
	//入口类
	class Application{
		//设置字符集
		private static function setCharset(){
			header('Content-type:text/html;charset=utf-8');
		}
		//定义目录常量
		private static function getContents(){
			//结构目录
			define("HOME_ROOT",str_replace('Core','',str_replace('\\','/',__DIR__)));
			//其他目录
			define("HOME_CORE",HOME_ROOT.'Core/');
			define("HOME_CONFIG",HOME_ROOT.'Config/');
			define("HOME_CONT",HOME_ROOT.'Controller/');
			define("HOME_MODEL",HOME_ROOT.'Model/');
			define("HOME_PUBLIC",HOME_ROOT.'Public/');
			define("HOME_UPLOADS",HOME_ROOT.'Uploads/');
			define("HOME_VIEW",HOME_ROOT.'View/');
			define("HOME_VENDER",HOME_ROOT.'VENDER/');
		}
		//项目错误处理设置
		private static function setIni(){
			@ini_set('error_reporting',E_ALL);
			@ini_set('display_errors',1);
		}
		//修改自动加载机制，实现自动加载
		public static function autoloadCore($class){
			//加载核心文件
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[1];
			$core = HOME_CORE."{$class}.class.php";
			if(is_file($core)){
				include_once($core);
			}
		}
		public static function autoloadCont($class){
			//加载控制器
			//phpinfo();
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];
			$controller = HOME_CONT."{$class}.class.php";
			if(is_file($controller)){
				include_once($controller);
			}
		}
		public static function autoloadModel($class){
			//加载Model文件
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];
			$model = HOME_MODEL."{$class}.class.php";
			if(is_file($model)){
				include_once($model);
			}
		}
		public static function autoloadVender($class){
			//加载Vender文件
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];
			$model = HOME_VENDER."{$class}.class.php";
			if(is_file($model)){
				include_once($model);
			}
		}
		private static function setConfig(){
			//加载配置文件：全局化
			$GLOBALS['config'] = include_once HOME_CONFIG . 'config.php';
		}
		//注册自动加载机制
		private static function setAutoload(){
			//注册
			spl_autoload_register(array(__CLASS__,'autoloadCore'));
			spl_autoload_register(array(__CLASS__,'autoloadCont'));
			spl_autoload_register(array(__CLASS__,'autoloadModel'));
			spl_autoload_register(array(__CLASS__,'autoloadVender'));
		}
		//初始化URL：获取参数数据
		private static function setUrl(){
			//获取控制器
			$module = isset($_REQUEST['module']) ? $_REQUEST['module'] : 'Index';
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
			//让module首字母大写，action全部小写
			$module = ucfirst(strtolower($module));
			$action = strtolower($action);	
			//将$module和$action定义成常量
			define('MODULE',$module);
			define('ACTION',$action);			
		}
		//分发控制器
		private static function setDispatch(){
			//找到控制器
			$module = MODULE.'Controller';
			$action = ACTION;
			//实例化
			$class = "\\home\\controller\\{$module}";
			$controller = new $class();
			$controller->$action();
		} 
		//入口方法
		public static function run(){
			//设置字符集
			self::setCharset();
			//获取目录
			self::getContents();
			//项目错误处理设置
			self::setIni();
			//加载配置文件
			self::setConfig();
			//自动加载
			self::setAutoload();
			//获取参数数据
			self::setUrl();
			//分发控制器
			self::setDispatch();
		}
	}