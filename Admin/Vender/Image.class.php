<?php
	//图片处理类
	//命名空间
	namespace admin\vender;
	//权限判断
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//
	class Image{
		//私有属性
		private $width;
		private $height;
		private $bgcolor;
		private static $mime = array(
			'image/png' => 'png',	
			'image/jpg' => 'jpeg',	
			'image/jpeg' => 'jpeg',	
			'image/pjpeg' => 'jpeg',	
			'image/gif' => 'gif'	
		);
		//初始化方法
		public function __construct($thumb = array()){
			$this->width = isset($thumb['width']) ? $thumb['width'] : 40;
			$this->width = isset($thumb['height']) ? $thumb['height'] : 40;
			$this->width = isset($thumb['bgcolor']) ? $thumb['bgcolor'] : 'white';
		}
		//制作缩略图
		public function makeThumb($file,$path,&$error){
			//验证文件是否存在
			if(!is_file($file)){
				$error = '原文件不存在！';
				return false;
			}
			//获取图片数据
			$src_info = getimagesize($file);
			//确定打开函数
			$open_func = 'imagecreatefrom' . self::$mime[$src_info['mime']];
			//打开图片资源，可变函数
			$src = $open_func($file);
			//缩略图资源
			$dst = imagecreatetruecolor($this->width,$this->height);
			//背景色
			switch($this->bgcolor){
				case 'white':
					$r = 255;
					$g = 255;
					$b = 255;
					break;
				case 'red':
					$r = 255;
					$g = 0;
					$b = 0;
					break;
				case 'green':
					$r = 0;
					$g = 255;
					$b = 0;
					break;
				case 'blue':
					$r = 0;
					$g = 0;
					$b = 255;
					break;
			}
			//背景填充
			$bg_color = imagecolorallocate($dst,$r,$g,$b);
			imagefill($dst,0,0,$bg_color);
			//按比例求缩略图具体宽高
			$src_b = $src_info[0] / $src_info[1];
			$dst_b = $this->width / $this->height;
			//判断宽高比
			if($src_b > $dst_b){
				//缩略图宽占满
				$w = $this->width;
				$h = floor($w / $src_b);
			}else{
				//缩略图高占满
				$h = $this->height;
				$w = floor($h * $src_b);
			}
			//确定起始位置
			$start_x = floor(($this->width - $w) / 2);
			$start_y = floor(($this->height - $h) / 2);
			//采样复制
			if(imagecopyresampled($dst,$src,$start_x,$start_y,0,0,$x,$y,$src_info[0],$src_info[1])){
				//缩略图制作成功
				$thumb_name = 'thumb_' . basename($file);
				imagepng($dst,$path.'/'.$thumb_name);
				//销毁资源
				imagedestroy($dst);
				imagedestroy($src);
				//返回
				return $thumb_name;
			}else{
				//制作失败
				imagedestroy($dst);
				imagedestroy($src);
				$error = '缩略图制作失败！';
				return false;
			}
		}
		public static function makeWatermark($file,$water,$path,&$error,$position = 5,$pct = 50){
			//判断文件是否存在
			if(!is_file($file)){
				$error = '图片文件不存在！';
				return false;
			}
			//判断水印图片是否存在
			if(!is_file($water)){
				$error = '水印图片不存在！';
				return false;
			}
			//获取文件格式，确定操作函数
			$dst_info = getimagesize($file);
			$src_info = getimagesize($water);
			if(!isset(self::$mime[$dst_info['mime']])){
				$error = '当前图片类型不支持制作水印！';
				return false;
			}
			if(!isset(self::$mime[$src_info['mime']])){
				$error = '当前水印图片类型不支持制作水印！';
				return false;
			}
			$dst_open = 'imagecreatefrom' . self::$mime[$dst_info['mime']];
			$src_open = 'imagecreatefrom' . self::$mime[$src_info['mime']];
			$dst_save = 'image' . self::$mime[$src_info['mime']];
			//打开图片：可变函数
			$dst = $dst_open($file);
			$src = $src_open($water);
			//计算位置
			switch($position){
				case 1://左上
					$x = $y = 0;
					break;
				case 2://右上
					$x = $dst_info[0] - $src_info[0];
					$y = 0;
					break;
				case 3://中间
					$x = ceil(($dst_info[0] - $src_info[0])/2);
					$y = ceil(($dst_info[1] - $src_info[1])/2);
					break;
				case 4://左下
					$x = 0;
					$y = $dst_info[1] - $src_info[1];
					break;
				case 5://默认：右下
					default:
					$x = $dst_info[0] - $src_info[0];
					$y = $dst_info[1] - $src_info[1];
					break;
			}
			//采样合并
			if(imagecopymerge($dst,$src,$x,$y,0,0,$src_info[0],$src_info[1],$pct)){
				//成功
				$watermark = 'water_' . basename($file);
				//可变函数
				$dst_save($dst,$path . '/' . $watermark);
				//销毁资源
				imagedestroy($dst);
				imagedestroy($src);
				//返回
				return $watermark;
			}else{
				//失败
				//销毁资源
				imagedestroy($dst);
				imagedestroy($src);
				//返回
				$error = '采样合并失败！';
				return false;	
			}
		}
	}