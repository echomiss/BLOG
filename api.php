<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
//注释验证函数
//$wechatObj->valid();
//开启自动回复函数
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
	//自动回复功能
    public function responseMsg()
    {
		//与$_POST类似，专门用于接收xml数据
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//判断接收的xml数据是否为空
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                //解析xml时不解析实体，防止XXE攻击
				libxml_disable_entity_loader(true);
				//把接收到的xml数据以simplexml模式进行解析
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                //手机端微信
              	$fromUsername = $postObj->FromUserName;
                //微信开发平台
              	$toUsername = $postObj->ToUserName;
                //获取用户发送的文本数据
              	$keyword = trim($postObj->Content);
              	//定义一个变量接收语音识别消息
              	$rec = $postObj->Recognition;
                //获取当前时间戳
              	$time = time();
                //定义一个变量接收MsgType节点
                $msgType = $postObj->MsgType;
                //定义一个变量接收订阅状态
                $event = $postObj->Event;
                //定义两个变量接收用户发送的地理位置经纬度信息
                $latitude = $postObj->Location_X;
                $longitude = $postObj->Location_Y;
                //定义文本回复模板
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
                //定义音乐回复模板
                $musicTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Music>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								<MusicUrl><![CDATA[%s]]></MusicUrl>
								<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							</Music>
							</xml>";
                //定义图文消息回复模板
                $newsTpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<ArticleCount>%s</ArticleCount>
                				%s
							</xml> ";
                //判断用户发送的关键词是否为空             
				if($msgType == 'text'){
					//判断用户输入的内容是否为音乐
					if($keyword == '音乐'){
						//定义回复类型，‘music’以音乐文件形式回复
						$msgType = "music";
						//音乐相关属性
						$title = '现在开始';
						$desc = '我可能不会爱你电视剧原声大碟！';
						$url = 'http://lawliet.duapp.com/music/xianzaikaishi.mp3';
						$hqurl = 'http://lawliet.duapp.com/music/xianzaikaishi.mp3';
						//使用sprintf函数讲数据放入xml模板中
						$resultStr = sprintf($musicTpl,$fromUsername,$toUsername,$time,$msgType,$title,$desc,$url,$hqurl);
						//输出
						echo $resultStr;
					}elseif($keyword == '单图文'){
						//定义回复类型，‘news’为图文形式回复
						$msgType = 'news';
						//定义文章数量
						$count = 1;
						//定义图文节点
						$str = '<Articles>';
						for($i=1;$i<=$count;$i++){
							$str .= "<item>
										<Title><![CDATA[猫]]></Title> 
										<Description><![CDATA[奇怪的猫]]></Description>
										<PicUrl><![CDATA[http://lawliet.duapp.com/images/mao{$i}.jpg]]></PicUrl>
										<Url><![CDATA[http://lawliet.duapp.com/index.php]]></Url>
									</item>";
						}
						$str .= '</Articles>';
						//使用sprintf函数讲数据放入xml模板中
						$resultStr = sprintf($newsTpl,$fromUsername,$toUsername,$time,$msgType,$count,$str);
						//输出
						echo $resultStr;
					}elseif($keyword == '多图文'){
						//定义回复类型
						$msgType = 'news';
						//定义文章数量
						$count = 4;
						//定义图文节点
						$str = '<Articles>';
						for($i=1;$i<=$count;$i++){
							$str .= "<item>
							<Title><![CDATA[猫]]></Title>
							<Description><![CDATA[奇怪的猫]]></Description>
							<PicUrl><![CDATA[http://lawliet.duapp.com/images/mao{$i}.jpg]]></PicUrl>
							<Url><![CDATA[http://lawliet.duapp.com/index.php]]></Url>
							</item>";
						}
						$str .= '</Articles>';
						//使用sprintf函数讲数据放入xml模板中
						$resultStr = sprintf($newsTpl,$fromUsername,$toUsername,$time,$msgType,$count,$str);
						//输出
						echo $resultStr;
					}else{
						//定义回复模式为‘text’文本回复模式
						$msgType = 'text';
						//图文机器人接口地址
						$url = "http://www.tuling123.com/openapi/api?key=601cdca37afe99c263980e431a80accc&info={$keyword}";
						//模拟get请求
						$json = file_get_contents($url);
						//解析json数据
						$str = json_decode($json);
						$contentStr = $str->text;
						//使用sprintf函数将数据放入xml模板中
						$resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
						echo $resultStr;
					}
				}elseif($msgType == 'image'){
					//定义回复类型，‘text’以文本形式回复
					$msgType = "text";
					//回复的文本内容
					$contentStr = "您发送的是图片消息！";
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					//输出
					echo $resultStr;
				}elseif($msgType == 'video' || $msgType == 'shortvideo'){
					//定义回复类型，‘text’以文本形式回复
					$msgType = "text";
					//回复的文本内容
					$contentStr = "您发送的是视频消息！";
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					//输出
					echo $resultStr;
				}elseif($msgType == 'link'){
					//定义回复类型，‘text’以文本形式回复
					$msgType = "text";
					//回复的文本内容
					$contentStr = "您发送的是链接消息！";
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					//输出
					echo $resultStr;
				}elseif($msgType == 'location'){
					//定义回复类型，‘news’以图文形式回复
					$msgType = "news";
					//定义变量加密数据
					$addr = urlencode('酒店');
					//定义变量保存地址
					$url = "http://api.map.baidu.com/telematics/v3/local?location={$longitude},{$latitude}&keyWord={$addr}&output=xml&ak=3olz33zlI5GGwZyj65DvwkcP";
					//模拟http中的get的请求
					$xml = file_get_contents($url);
					//调式输出xml数据
					$com = simplexml_load_string($xml);
					//定义回复数量
					$count = (integer)$com->count > 5 ? 5 : (integer)$com->count;
					//定义回复节点
					$str = '<Articles>';
						for($i=1;$i<=$count;$i++){
							$str .= "<item>
							<Title><![CDATA[".$com->poiList->point[$i]->name."]]></Title>
							<Description><![CDATA[".$com->poiList->point[$i]->address."]]></Description>
							<PicUrl><![CDATA[http://lawliet.duapp.com/images/jd{$i}.jpg]]></PicUrl>
							<Url><![CDATA[".$com->poiList->point[$i]->additionalInfo->link[2]->url."]]></Url>
							</item>";
						}
					$str .= '</Articles>';
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $count,$str);
					//输出
					echo $resultStr;
				}elseif($msgType == 'voice'){
					//定义回复类型，‘text’以文本形式回复
					$msgType = "text";
					//图灵机器人接口地址
					$url = "http://www.tuling123.com/openapi/api?key=601cdca37afe99c263980e431a80accc&info={$rec}";
					//模拟发送get请求
					$json = file_get_contents($url);
					//解析json数据
					$str = json_decode($json);
					//回复的文本内容
					$contentStr = str_replace('<br>','\n',$str->text);
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					//输出
					echo $resultStr;
				}elseif($msgType == 'event' && $event == 'subscribe'){
					//定义回复类型，‘text’以文本形式回复
					$msgType = "text";
					//回复的文本内容
					$contentStr = "感谢您的订阅！~~\n您可以通过发送您的位置获取您所在位置附近的五家酒店！";
					//使用sprintf函数讲数据放入xml模板中
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					//输出
					echo $resultStr;
				}
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>