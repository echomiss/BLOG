<?php
	//文件上传工具类
	//命名空间
	namespace admin\vender;
	//判断权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//
	class FileUpload{
		//静态属性：错误信息
		public static $error;
		//单文件上传
		public static function uploadFile($file,$path,$allow,$maxsize=1000000){
			//判断文件是否有效
			if(!is_array($file) || count($file) != 5 || !isset($file['name']) || !isset($file['tmp_name']) || !isset($file['type']) || !isset($file['size']) || !isset($file['error'])){
				//文件不合法
				self::$error = '文件不合法';
				return false;
			}
			//判断系统错误信息
			switch($file['error']){
				case 1:
					self::$error = '文件超过服务允许上传的大小！';
					return false;
				case 2:
					self::$error = '文件超过表单允许的大小！';
					return false;
				case 3:
					self::$error = '文件只有部分上传！';
					return false;
				case 4:
					self::$error = '用户没有选中要上传的文件！';
					return false;
				case 6:
					self::$error = '找不到临时目录！';
					return false;
				case 7:
					self::$error = '没有权限将文件放到临时目录！';
					return false;
			}
			//判断文件类型是否合法
			if(strpos($allow,$file['type']) === false){
				self::$error = '当前文件类型不符合规格!';
				return false;
			}
			//判断文件大小
			if($file['size'] > $maxsize){
				self::$error = '当前文件超过允许上传的大小!文件允许的最大值为：'.($maxsize/1000).'KB';
				return false;
			}
			//移动文件
			$newname = self::getRandomName($file['name']);
			if(move_uploaded_file($file['tmp_name'],$path.'/'.$newname)){
				return $newname; //成功
			}else{
				self::$error = '文件移动失败！'; //失败	
				return false;
			}
		}
		//生成随机名名方法
		private static function getRandomName($filename){
			$randomname = date('YmdHis');
			//生成随机6个字符
			for($i=0;$i<6;$i++){
				switch(mt_rand(1,3)){
					case 1:	//生成随机小写
						$randomname .= chr(mt_rand(97,122));
						break;
					case 2:	//生成随机大写
						$randomname .= chr(mt_rand(65,90));
						break;
					case 3: //生成随机数字
						$randomname .= chr(mt_rand(49,57));
						break;
				}
			}
			//返回
			return $randomname . strrchr($filename,'.');
		}
	}