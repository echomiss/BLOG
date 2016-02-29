<?php
	//分页工具类
	//命名空间
	namespace admin\vender;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//生成分页
	class Paging{
		//生成分页链接1：首页 上一页 下一页 末页
		public function getPageStr($module,$action,$counts,$pagecount,$page,$data=array()){
			//分页信息提示
			$page_tips = "当前一共有{$counts}条记录，每页显示{$pagecount}条记录，当前为第{$page}页";
			//遍历数组：提交数据
			$lists = '';
			foreach($data as $k => $v){
				//将数据保存到变量
				$lists .= '&' . $k . '=' . $v;
			}
			$pages = ceil($counts / $pagecount);		//总页数
			$next = $page < $pages ? $page + 1 : $pages;//下一页
			$prev = $page > 1 ? $page - 1 : 1;			//上一页
			$page_click = <<<END
				<a href="admin.php?module={$module}&action={$action}&page=1{$lists}">首页</a>&nbsp;&nbsp;
				<a href="admin.php?module={$module}&action={$action}&page={$prev}{$lists}">上一页</a>&nbsp;&nbsp;
				<a href="admin.php?module={$module}&action={$action}&page={$next}{$lists}">下一页</a>&nbsp;&nbsp;
				<a href="admin.php?module={$module}&action={$action}&page={$pages}{$lists}">末页</a>&nbsp;&nbsp;
END;
			//返回
			return $page_tips . $page_click;
		}
		//生成分页链接2：数字型点击分页		1  2  3  4
		public function getPageNum($module,$action,$counts,$pagecount,$page,$data=array()){
			$lists = '';
			foreach($data as $k => $v){
				//将数据保存到变量
				$lists .= '&' . $k . '=' . $v;
			}
			$pages = ceil($counts / $pagecount);			//总页数
			$num = '';
			for($i=1;$i<=$pages;$i++){
				$num .= "<a href='admin.php?module={$module}&action={$action}&page={$i}{$lists}'>{$i}</a>&nbsp;&nbsp;";
			}
			return $num;
		}
		//生成分页链接3：下拉框
		public function getPageSelect($module,$action,$counts,$pagecount,$page,$data=array()){
			$lists = '';
			foreach($data as $k => $v){
				//将数据保存到变量
				$lists .= '&' . $k . '=' . $v;
			}
			$pages = ceil($counts / $pagecount);			//总页数
			//组合
			$select = "<select onchange=\"location.href='admin.php?module={$module}&action={$action}{$lists}&page='+this.value\">";
			for($i=1;$i<=$pages;$i++){
				if($page == $i){
					$select .= "<option value='{$i}' selected = 'selected'>第{$i}页</option>";
				}else{
					$select .= "<option value='{$i}'>第{$i}页</option>";
				}
			}
			$select .= "</select>";
			//返回
			return $select;
		}
		//生成分页链接4：输入框
		public function getPageInput($module,$action,$counts,$pagecount,$page,$data=array()){
			$pages = ceil($counts / $pagecount);			//总页数
			//组合：输入框
			if(empty($data)){
			$input = <<<END
			<form action="admin.php" method="GET">
				<input type="hidden" name="module" value="{$module}"/>
				<input type="hidden" name="action" value="{$action}"/>
				<input type="text" name="page" value="{$page}" id="page" onblur="check(this)"/>
				<input type="submit" value="GO"/>
				<script>
					var max = {$pages};
					var min = 1;

					function check(e){	
						var v = e.value;
						//alert(typeof(v));
						//判断: 类型判断, 范围判断
						if(parseInt(v) < min || parseInt(v) > max){
							e.value = e.defaultValue;
	
							//获得焦点
							e.focus;
						}
					}
				</script>
			</form>
END;
			}else{
				$input = <<<END
				<form action="admin.php" method="GET">
				<input type="hidden" name="module" value="{$module}"/>
				<input type="hidden" name="action" value="{$action}"/>
				<input type="hidden" name="category" value="{$data['category']}"/>
				<input type="hidden" name="status" value="{$data['status']}"/>
				<input type="hidden" name="search" value="{$data['search']}"/>
				<input type="text" name="page" value="{$page}" id="page" onblur="check(this)"/>
				<input type="submit" value="GO"/>
				<script>
					var max = {$pages};
					var min = 1;

					function check(e){	
						var v = e.value;
						//alert(typeof(v));
						//判断: 类型判断, 范围判断
						if(parseInt(v) < min || parseInt(v) > max){
							e.value = e.defaultValue;
	
							//获得焦点
							e.focus;
						}
					}
				</script>
			</form>
END;
			}
			return $input;
		}
	}