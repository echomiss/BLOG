<?php
	//分类控制器
	//命名空间
	namespace admin\Controller;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入控制器类
	use \admin\Controller;
	//分类
	class CategoryController extends Controller{
		//列表显示
		public function index(){
			//调用Category模型类
			$category = new \admin\model\CategoryModel();
			$categories = $category->getCategory();
			//缓存
			$_SESSION['categories'] = $categories;
			//分配数据给视图
			$this->view->assign('categories',$_SESSION['categories']);
			//调用视图
			$this->view->display('admin_category_list');
		}
		//增加分类方法
		public function addCate(){
			//获取所有的分类		
			if(!$_SESSION['categories']){
				$category = new \admin\model\CategoryModel();
				$categories = $category->getCategory();
				//缓存
				$_SESSION['categories'] = $categories;
			}
			//分配给模板显示
			$this->view->assign('categories',$_SESSION['categories']);
			//调用视图
			$this->view->display('admin_category_add');
		}
		//分类入库
		public function insertCate(){
			$data = array();
			//接收数据
			$data['c_name'] = trim($_POST['Name']);
			$data['c_nickname'] = trim($_POST['Alias']);
			$data['c_sort'] = trim($_POST['Order']);
			$data['c_parent_id'] = trim($_POST['ParentID']);
			//名称不能为空
			if(empty($data['c_name'])){
				$this->redirect_fail('module=category&action=addCate');
			}
			//排序必须为大于0的整形
			if(!is_numeric($data['c_sort']) || (integer)$data['c_sort'] != $data['c_sort'] || $data['c_sort'] < 0){
				$this->redirect_fail('module=category&action=addCate','排序必须为大于0的整数!');
			}
			//合理性验证，验证新得分类在目标父分类下是否同名
			$category = new \admin\model\CategoryModel();
			if($category->checkCategoryByNameAndParentID($data['c_name'],$data['c_parent_id'])){
				$this->redirect_fail('module=category&action=addCate','当前父类下存在同名！');
			}
			//数据入库
			if($category->insertCategory($data)){
				$this->redirect_success('module=category','新增分类成功！');
			}else{
				$this->redirect_fail('module=category&action=addCate','新增分类失败!');
			}
		}
		//删除方法
		public function deleteCate(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//合法性验证
			if(!$id){
				$this->redirect_fail('module=category','当前要删除的分类不存在！');
			}
			//合理性验证
			$category = new \admin\model\CategoryModel();
			if($category->checkCategoryByID($id)){
				//有子分类或有文章
				$this->redirect_fail('module=category','有子分类或有文章的分类不能删除！');
			}
			//删除
			if($category->deleteCategory($id)){
				$this->redirect_success('module=category','删除成功！');
			}else{
				$this->redirect_fail('module=category','删除失败！');
			}
		}
		//修改方法
		public function editCate(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//合法性判断
			if(!$id){
				$this->redirect_fail('module=category&action=edit','当前要编辑的分类不存在！');
			}
			//获取分类信息以及所有分类
			$category = new \admin\model\CategoryModel();
			$categories = $category->getCategory($id);
			$cate = $category->getRecordByID($id);
			//分配数据
			$this->view->assign('categories',$categories);
			$this->view->assign('cate',$cate);
			//调用视图
			$this->view->display('admin_category_edit');
		}
		//更新数据入库
		public function updateCate(){
			//接收id
			$id = isset($_POST['id']) ? (integer)$_POST['id'] : 0;
			//合法性验证
			if(!$id){
				$this->redirect_fail('module=category','当前要编辑的分类不存在！');
			}
			//定义数组
			$data = array();
			//接收数据
			$data['c_name'] = trim($_POST['Name']);
			$data['c_nickname'] = trim($_POST['Alias']);
			$data['c_sort'] = trim($_POST['Order']);
			$data['c_parent_id'] = trim($_POST['ParentID']);
			//名称不能为空
			if(empty($data['c_name'])){
				$this->redirect_fail('module=category&action=edit&id='.$id);
			}
			//排序必须为大于0的整形
			if(!is_numeric($data['c_sort']) || (integer)$data['c_sort'] != $data['c_sort'] || $data['c_sort'] < 0){
				$this->redirect_fail('module=category&action=edit&id='.$id,'排序必须为大于0的整数!');
			}
			//合理性验证，验证新得分类在目标父分类下是否同名
			$category = new \admin\model\CategoryModel();
			if($cate = $category->checkCategoryByNameAndParentID($data['c_name'],$data['c_parent_id'])){
				//排除自己
				if($cate['c_id']!=$id){
					$this->redirect_fail('module=category&action=edit&id='.$id,'当前父类下存在同名！');
				}
			}
			//更新入库
			if($category->updateCategory($id,$data)){
				$this->redirect_success('module=category','更新分类成功！');
			}else{
				$this->redirect_fail('module=category&action=edit&id='.$id,'没有要更新的数据！');
			}
		}
	}