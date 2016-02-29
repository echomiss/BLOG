<?php
	//命名空间
	namespace admin\controller;
	//判断权限
	if(!defined('ACCESS')){
		header('Location:../admin.php');
		exit;
	}
	use \admin\Controller;
	//权限控制器
	class PrivilegeController extends Controller{
		//給用户加载登录表单
		public function index(){
			$this->view->display('admin_login');
		}
		//验证用户信息
		public function verify(){
			//接收数据
			$account = trim($_POST['username']);
			$password = trim($_POST['password']);
			$captcha = trim($_POST['captcha']);
			//合法性验证
			if(empty($account) || empty($password)){
				$this->redirect_fail('','用户名和密码不能为空！');
			}
			//验证码
			if(empty($captcha)){
				$this->redirect_fail('','验证码不能为空！');
			}
			//调用验证方法
			if(!\admin\vender\Captcha::checkCaptcha($captcha)){
				$this->redirect_fail('','验证码错误！');
			}
			//当用户登录失败，刷新验证码
			\admin\vender\Captcha::generateStr();
			//调用模型
			$admin = new \admin\model\AdminModel();
			$user = $admin->getUserbyUsername($account);
			//判断用户名是否存在
			if(!$user){
				$this->redirect_fail('','用户不存在！');
			}
			//判断密码是否一致
			if(md5($password) != $user['a_password']){
				$this->redirect_fail('','密码错误！');
			}
			//更新用户登录信息
			if(!$admin->updateLoginInfo($user['a_id'])){
				//失败，与用户无关；用户无需知道
			}
			//判断用户是否选择了保持登录
			if(isset($_POST['chkRemember'])){
				//获取自动登录的时间
				$savedate = isset($_POST['savedate']) ? $_POST['savedate'] : 7;
				//将用户信息保存到浏览器
				setcookie('admin_user_id',$user['a_id'],time() + $savedate*24*3600);
			}
			$_SESSION['user'] = $user;
			$this->redirect_success('module=Index','登陆成功！');
		}
		//退出登录
		public function logout(){
			session_destroy();
			//清除cookie
			setcookie('admin_user_id','',time() - 1);
			//返回登录界面
			$this->redirect_success('','退出成功！');
		}
		//用户使用cookie自动登录
		public function autologin(){
			//获取用户id
			$id = $_COOKIE['admin_user_id'];
			//通过ID获取用户数据
			$admin = new \admin\model\AdminModel();
			if($user = $admin->getRecordById($id)){
				//即使用户选择自动登录，也需要更新登录信息
				$admin->updateLoginInfo($id);
				//成功，将用户保存到session
				$_SESSION['user'] = $user;
				$this->redirect_success('module=Index','欢迎进入BLOG系统！');
			}else{
				//失败，清除cookie
				setcookie('admin_user_id','',time() - 1);
				$this->redirect_fail('','保持登录已过期，请重新登录！');
			}
		}
		//获取验证码图片
		public function getCaptcha(){
			$cap = new \admin\vender\Captcha();
			$cap->generateCap();
		}
	}