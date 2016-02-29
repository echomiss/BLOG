<?php
	//文章管理类
	//命名空间
	namespace admin\controller;
	//权限判断
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入Controller类
	use \admin\Controller;
	//
	class ArticleController extends Controller{
		//首页方法
		public function index(){
			//接收数据
			$page = isset($_GET['page']) ? $_GET['page'] : 1;		//当前页码数
			//获取所有的分类		
			$category = new \admin\model\CategoryModel();
			$categories = $category->getCategory();			
			//调用模型获取总记录数
			$article = new \admin\model\ArticleModel();
			$counts = $article->getArticleCounts();
			//每页显示记录数
			$pagecount = $GLOBALS['config']['admin_pagecount'];
			$offset = ($page - 1) * $pagecount;		//获取总记录数
			//调用分页工具类
			$paging = new \admin\vender\Paging();
			//调用分页方法
			$pagings1 = $paging->getPageStr('article','index',$counts,$pagecount,$page);
			$pagings2 = $paging->getPageNum('article','index',$counts,$pagecount,$page);
			$pagings3 = $paging->getPageSelect('article','index',$counts,$pagecount,$page);
			//缓存
			$_SESSION['categories'] = $categories;
			//获取文章数据
			$articles = $article->getArticle($offset,$pagecount);
			//分配数据给视图显示
			$this->view->assign('pagings',$pagings1 . $pagings2 . $pagings3);
			$this->view->assign('offset',$offset);
			$this->view->assign('categories',$_SESSION['categories']);
			$this->view->assign('articles',$articles);
			$this->view->display('admin_article_list');
		}
		//搜索文章查询
		public function searchArti(){
			//接收数据
			$page = isset($_GET['page']) ? $_GET['page'] : 1;	//当前页码数
			$c_id = isset($_REQUEST['category']) ? (integer)$_REQUEST['category'] : 0;
			$a_status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
			$a_name = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
			//获取所有的分类		
			if(!$_SESSION['categories']){
				$category = new \admin\model\CategoryModel();
				$categories = $category->getCategory();
				//缓存
				$_SESSION['categories'] = $categories;
			}
			//将接收的数据保存到数组
			$data = array('category' => $c_id,'status' => $a_status,'search' => $a_name);
			//每页显示记录数
			$pagecount = $GLOBALS['config']['admin_pagecount'];
			//求出起始位置
			$offset = ($page - 1) * $pagecount;
			//获取文章数据
			$article = new \admin\model\ArticleModel();
			//获取总记录数
			$counts = $article->getArticleCounts($c_id,$a_status,$a_name);
			//调用分页工具类
			$paging = new \admin\vender\Paging();
			$pagings1 = $paging->getPageStr('article','searchArti',$counts,$pagecount,$page,$data);
			$pagings3 = $paging->getPageSelect('article','searchArti',$counts,$pagecount,$page,$data);
			$pagings4 = $paging->getPageInput('article','searchArti',$counts,$pagecount,$page,$data);
			//获取文章信息
			$articles = $article->searchArticle($c_id,$a_status,$a_name,1,$offset,$pagecount);
			//分配数据给视图显示，查询数据
			$this->view->assign('c_id',$c_id);
			$this->view->assign('a_status',$a_status);
			$this->view->assign('a_name',$a_name);
			//分页数据
			$this->view->assign('offset',$offset);
			$this->view->assign('pagings',$pagings1 . $pagings3 . $pagings4);
			//分类，文章数据
			$this->view->assign('categories',$_SESSION['categories']);
			$this->view->assign('articles',$articles);
			//调用模板
			$this->view->display('admin_article_search');
		}
		//查看文章信息
		public function browseArti(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			if(!$id){
				$this->redirect_fail('module=article','当前要查看的文章不存在！');
			}
			//通过ID获取文章信息
			$article = new \admin\model\ArticleModel();
			$articles = $article->getArticleByID($id);
			//将数据分配给视图
			$this->view->assign('articles',$articles);
			//调用模板
			$this->view->display('admin_article_browse');
		}
		//新建文章
		public function addArti(){
			//获取所有的分类		
			if(!$_SESSION['categories']){
				$category = new \admin\model\CategoryModel();
				$categories = $category->getCategory();
				//缓存
				$_SESSION['categories'] = $categories;
			}
			//将数据分配给视图
			$this->view->assign('categories',$_SESSION['categories']);
			//调用视图模板
			$this->view->display('admin_article_add');
		}
		//新增数据入库
		public function insertArti(){
			//定义数组用来接收数据
			$data = array();
			$data['a_name'] = htmlspecialchars(trim($_POST['Title']));
			$data['a_nickname'] = htmlspecialchars(trim($_POST['Alias']));
			$data['a_content'] = htmlspecialchars(trim($_POST['Content']));
			$data['c_id'] = trim($_POST['CateID']);
			$data['a_status'] = trim($_POST['Status']);
			$data['a_publish_time'] = strtotime(trim($_POST['PostTime']));
			//增加作者
			$data['a_author'] = $_SESSION['user']['a_nickname'];
			//合法性验证
			if(!$data['a_name']){
				//文章名字不能为空
				$this->redirect_fail('module=article&action=addArti','文章名不能为空！');
			}
			if(!$data['a_content']){
				//内容不能为空
				$this->redirect_fail('module=article&action=addArti','文章内容不能为空！');
			}
			//数据入库
			$article = new \admin\model\ArticleModel();
			$id = $article->insertArticle($data);
			if($id){
				//文章数+1
				if($article->updateArticleNum($data['c_id'])){
					//成功
					$this->redirect_success("module=article&action=browseArti&id={$id}",'新增文章成功！');					
				}
			}else{
				//失败
				$this->redirect_fail('module=article&action=addArti','新增文章失败！');
			}
		}
		//删除文章，将文章放入回收站
		public function deleteArti(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//合法性验证
			if(!$id){
				//不存在的id
				$this->redirect_fail('module=article','当前要删除的文章不存在！');
			}
			//数据入库
			$article = new \admin\model\ArticleModel();
			$c_id = $article->getArticleByID($id)['c_id'];
			if($article->putinRecycled($id)){
				if($article->updateArticleNum($c_id,false)){
					//成功
					$this->redirect_success('module=article','文章已放入回收站！');
				}else{
					//失败
					$this->redirect_fail('module=article','文章删除失败！');
				}
			}else{
				//失败
				$this->redirect_fail('module=article','文章删除失败！');
			}
		}
		//编辑文章方法
		public function editArti(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			//合法性验证
			if(!$id){
				$this->redirect_fail('module=article','当前要编辑的文章不存在！');
			}
			//获取所有的分类		
			if(!$_SESSION['categories']){
				$category = new \admin\model\CategoryModel();
				$categories = $category->getCategory();
				//缓存
				$_SESSION['categories'] = $categories;
			}
			//获取文章信息
			$article = new \admin\model\ArticleModel();
			$articles = $article->getArticleByID($id);
			//分配数据给视图
			$this->view->assign('categories',$_SESSION['categories']);
			$this->view->assign('articles',$articles);
			//调用视图
			$this->view->display('admin_article_edit');
		}
		//数据入库
		public function updateArti(){
			//接收id
			$id = isset($_POST['id']) ? (integer)$_POST['id'] : 0;
			//通过ID获取文章数据
			$article = new \admin\model\ArticleModel();
			$articles = $article->getArticleByID($id);
			//定义数组用来接收数据
			$data = array();
			//判断文章标题是否更改
			$data['a_name'] = htmlspecialchars(trim($_POST['Title']));
			//判断文章别名是否更改
			if($articles['a_nickname'] != htmlspecialchars(trim($_POST['Alias']))){
				$data['a_nickname'] = htmlspecialchars(trim($_POST['Alias']));
			}
			//判断文章内容是否更改
			$data['a_content'] = htmlspecialchars(trim($_POST['Content']));
			$data['c_id'] = trim($_POST['CateID']);
			//判断文章状态是否更改
			if($articles['a_status'] != trim($_POST['Status'])){
				$data['a_status'] = trim($_POST['Status']);
			}
			$data['a_publish_time'] = strtotime(trim($_POST['PostTime']));
			//合法性判断
			if(!$data['a_name']){
				$this->redirect_fail('module=article&action=editArti&id={$id}','文章标题不能为空！');
			}
			if(!$data['a_content']){
				$this->redirect_fail('module=article&action=editArti&id={$id}','文章内容不能为空！');
			}
			//数据入库
			if($article->updateArticle($id,$data)){
				//判断是否更新了文章分类
				if($data['c_id'] != $articles['c_id']){
					$article->updateArticleNum($articles['c_id'],false);
					$article->updateArticleNum($data['c_id']);
				}
				//成功
				$this->redirect_success("module=article&action=browseArti&id={$id}",'修改文章成功！');
			}else{
				//失败
				$this->redirect_fail('module=article','文章没有要更新的内容！');
			}
		}
	}