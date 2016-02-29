<?php
	//验证码工具类
	//命名空间
	namespace admin\vender;
	//验证权限
	if(!defined('ACCESS')){
		header('location:../admin.php');
	}
	//验证码类
	class Captcha{
		//定义属性
		private $width;
		private $height;
		private $star;
		private $lines;
		private static $length;
		private $fonts;
		//构造方法：初始化属性值
		public function __construct($info = array()){
			$this->width = isset($info['width']) ? $info['width'] : $GLOBALS['config']['captcha']['width'];
			$this->height = isset($info['height']) ? $info['height'] : $GLOBALS['config']['captcha']['height'];
			$this->star = isset($info['star']) ? $info['star'] : $GLOBALS['config']['captcha']['star'];
			$this->lines = isset($info['lines']) ? $info['lines'] : $GLOBALS['config']['captcha']['lines'];
			self::$length = isset($info['length']) ? $info['length'] : $GLOBALS['config']['captcha']['length'];
			$this->fonts = isset($info['fonts']) ? $info['fonts'] : $GLOBALS['config']['captcha']['fonts'];
		}
		//生成验证码图片
		public function generateCap(){
			//生成图片
			$img = imagecreatetruecolor($this->width,$this->height);
			//分配背景颜色
			$bgcolor = imagecolorallocate($img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			//填充背景颜色
			imagefill($img,0,0,$bgcolor);
			//随机生成‘*’符号
			for($i=0;$i < $this->star;$i++){
				//分配颜色
				$starcolor = imagecolorallocate($img,mt_rand(150,200),mt_rand(150,200),mt_rand(150,200));
				//填充‘*’
				imagestring($img,mt_rand(1,6),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$starcolor);
			}
			//随机生成干扰线
			for($j=0;$j < $this->lines;$j++){
				//分配颜色
				$linecolor = imagecolorallocate($img,mt_rand(100,150),mt_rand(100,150),mt_rand(100,150));
				//增加干扰线
				imageline($img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$linecolor);
			}
			//将字符填入
			$str = self::generateStr();
			for($k=0;$k < self::$length;$k++){
				//分配颜色
				$strcolor = imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
				//位置顺移
				imagestring($img,mt_rand(5,6),5+$k*15,mt_rand(6,10),$str[$k],$strcolor);
			}
			//输出图片
			header('content-type:image/png');
			imagepng($img);
			//关闭资源
			imagedestroy($img);
		}
		//随机生成字符
		public static function generateStr(){
			$str = '';
			for($i=0;$i < self::$length;$i++){
				switch(mt_rand(1,3)){
					case 1 : //生成随机小写字母
						$str .= chr(mt_rand(97,122));
						break;
					case 2 : //生成随机大写字母
						$str .= chr(mt_rand(65,90));
						break;
					case 3 : //生成随机数字
						$str .= chr(mt_rand(49,57));
						break;
				}
			}
			//将验证码保存到session中
			$_SESSION['captcha'] = $str;
			//返回字符串
			return $str;
		}
		//验证验证码
		public static function checkCaptcha($captcha){
			//不区分大小写
			return (strtolower($captcha) === strtolower($_SESSION['captcha']));
		}
	}