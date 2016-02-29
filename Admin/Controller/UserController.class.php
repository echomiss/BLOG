<?php
	//用户控制器
	//命名空间
	namespace admin\controller;
	//权限验证
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入Controller类
	use \admin\Controller;
	//
	class UserController extends Controller{
		//默认方法：显示用户详细信息
		public function index(){
			//接收ID
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//验证
			if(!$id){
				$this->redirect_fail('','当前用户名不存在！');
			}
			//获取用户数据
			$admin = new \admin\model\AdminModel();
			$user = $admin->getRecordById($id);
			//分配数据给视图
			$this->view->assign('user',$user);
			//调用试图模板
			$this->view->display('admin_user');
		}
		//编辑方法
		public function edit(){
			//接收ID
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//验证
			if(!$id){
				$this->redirect_fail('','当前用户名不存在！');
			}
			//获取用户数据
			$admin = new \admin\model\AdminModel();
			$user = $admin->getRecordById($id);
			//分配数据给视图
			$this->view->assign('user',$user);
			//调用试图模板
			$this->view->display('admin_edit');
		}
		//更新入库
		public function update(){
			//接收数据
			$id = isset($_POST['id']) ? (integer)$_POST['id'] : 0;
			$nickname = trim($_POST['nickname']);
			//合法性验证
			if(!$id){
				$this->redirect_fail('module=user&action=edit&id='.$id,'当前用户不存在！');
			}
			//接收文件$_FILES
			$head = $_FILES['head'];
			//文件上传
			$filename = \admin\vender\FileUpload::uploadFile($head,ADMIN_UPLOAD,$GLOBALS['config']['admin_head']);
			//更新入库
			$admin = new \admin\model\adminModel();
			//判断
			if($admin->updateUser($id,$nickname,$filename)){
				if($filename){
					$this->redirect_success('module=user&id='.$id,'用户更新成功！');
				}else{
					$this->redirect_success('module=user&id='.$id,'更新成功，但是头像上传失败！失败原因是：' . \admin\model\FileUpload::$error);
				}
			}else{
				$this->redirect_fail('module=edit&id='.$id,'用户更新失败！');
			}
		}
	}