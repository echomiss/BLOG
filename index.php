<?php

	//前台入口

	//定义入口常量: 用来保护其他文件不让用户直接访问
	define('ACCESS',true);

	//调用初始化类
	include_once 'Core/Application.class.php';

	//进入初始化类
	\home\Application::run();