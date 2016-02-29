<?php
	//命名空间
	namespace admin;
	//判断是否合法访问
	if(!defined('ACCESS')){
		//跳转
		header("location:../admin.php");
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
			define("ADMIN_ROOT",str_replace('Core','',str_replace('\\','/',__DIR__)));
			//其他目录
			define("ADMIN_CORE",ADMIN_ROOT.'Core/');
			define("ADMIN_CONFIG",ADMIN_ROOT.'Config/');
			define("ADMIN_CONT",ADMIN_ROOT.'Controller/');
			define("ADMIN_MODEL",ADMIN_ROOT.'Model/');
			define("ADMIN_PUBLIC",ADMIN_ROOT.'Public/');
			define("ADMIN_UPLOAD",ADMIN_ROOT.'Upload/');
			define("ADMIN_VIEW",ADMIN_ROOT.'View/');
			define("ADMIN_VENDER",ADMIN_ROOT.'Vender/');
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
			$core = ADMIN_CORE."{$class}.class.php";
			if(is_file($core)){
				include_once($core);
			}
		}
		public static function autoloadCont($class){
			//加载控制器
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];
			$controller = ADMIN_CONT."{$class}.class.php";
			if(is_file($controller)){
				include_once($controller);
			}
		}
		public static function autoloadModel($class){
			//加载Model文件
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];
			$model = ADMIN_MODEL."{$class}.class.php";
			if(is_file($model)){
				include_once($model);
			}
		}
		public static function autoloadVender($class){
			//加载Vender文件
			//$class = basename($class);
			$arr = explode('\\',$class);
			$class = $arr[2];	
			$model = ADMIN_VENDER."{$class}.class.php";
			if(is_file($model)){
				include_once($model);
			}
		}
		private static function setConfig(){
			//加载配置文件：全局化
			$GLOBALS['config'] = include_once ADMIN_CONFIG . 'config.php';
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
			$module = isset($_REQUEST['module']) ? $_REQUEST['module'] : 'Privilege';
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
			//让module首字母大写，action全部小写
			$module = ucfirst(strtolower($module));
			$action = strtolower($action);
			//判断用户是否有权限，是否有登录(session)
			if(!isset($_SESSION['user'])){
				//判断用户是否存在cookie:admin_user_id
				if(isset($_COOKIE['admin_user_id'])){
					//用户曾经保存了信息: 帮助用户登录
					$module = 'Privilege';
					$action = 'autologin';
				}else{
					//如果模块是Privilege,那么直接访问
					if($module != 'Privilege'){
						//用户没有登录
						header('Location:admin.php');
					}
				}
			}else{
				//有session: 需要排除退出功能
				//echo $module,$action;exit;
				if($module == 'Privilege' && $action != 'logout'){
					$module = 'Index';
				}
			}			
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
			$class = "\\admin\\controller\\{$module}";
			$controller = new $class();
			$controller->$action();
		} 
		//修改session机制
		private static function setSession(){
			//实例化
			$class = "\\admin\\vender\\Session";
			$session = new $class();
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
			//修改session机制
			self::setSession();
			//获取参数数据
			self::setUrl();
			//分发控制器
			self::setDispatch();
		}
	}