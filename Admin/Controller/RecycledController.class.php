<?php
	//回收站控制器
	//命名空间
	namespace admin\controller;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//引入公共控制器类
	use \admin\Controller;
	//
	class RecycledController extends Controller{
		//首页方法
		public function index(){
			//接收数据
			$page = isset($_GET['page']) ? $_GET['page'] : 1;		//当前页码数
			//调用模型
			$article = new \admin\model\ArticleModel();
			//获取文章总记录数
			$counts = $article->getArticleCounts(0,0,'',2);
			//每页显示记录数
			$pagecount = $GLOBALS['config']['admin_pagecount'];
			//起始位置
			$offset = ($page - 1) * $pagecount;
			//调用分页工具类
			$paging = new \admin\vender\Paging();
			$pagings = $paging->getPageStr('recycled','index',$counts,$pagecount,$page);
			//获取文章信息
			$articles = $article->getArticle($offset,$pagecount,2);
			//分配数据给视图
			$this->view->assign('pagings',$pagings);
			$this->view->assign('offset',$offset);
			$this->view->assign('articles',$articles);
			//调用视图模板
			$this->view->display('admin_recycled');
		}
		//从回收站中删除
		public function deleteArti(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			if(!$id){
				$this->redirect_fail('module=recycled','当前要删除的文章不存在！');
			}
			//数据入库
			$delete = new \admin\model\ArticleModel();
			if($delete->deleteArticle($id)){
				$this->redirect_success('module=recycled','文章删除成功！');
			}else{
				$this->redirect_fail('module=recycled','文章删除失败！');
			}
		}
		//还原文章
		public function restoreArti(){
			//接收数据
			$id = isset($_GET['id']) ? (integer)$_GET['id'] : 0;
			if(!$id){
				$this->redirect_fail('module=recycled','当前要还原的文章不存在！');
			}
			//数据入库
			$restore = new \admin\model\ArticleModel();
			$article = $restore->getArticleByID($id);
			if($restore->restoreArticle($id)){
				if($restore->updateArticleNum($article['c_id'])){
					//还原成功，对应分类文章数+1
					$this->redirect_success('module=article','文章还原成功！');
				}else{
					$this->redirect_fail('module=recycled','文章还原失败！');
				}
			}else{
				$this->redirect_fail('module=recycled','文章还原失败！');
			}
		}
	}