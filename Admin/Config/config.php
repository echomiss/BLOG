<?php
	//将后台一些常用，但是有可能修改的选项进行配置。如：数据库

	//返回一个数组
	return array(
		//数据库类型
		'type' => 'mysql',
		//数据库环境
		'mysql' => array(
			'host' => 'sqld.duapp.com',
			'port' => '4050',
			'user' => '0ece4c406c0a4f3d8239d2c9c2bf937e',
			'pass' => '658d7460814c470d9be4cbd5dac525eb',
			'dbname' => 'lVXMaZtBuKihJQRDrYkl',
			'charset' => 'utf8',
			'prefix' => 'my_'
		),//修改验证码属性
		'captcha' => array(
			'width' => 100,
			'height' => 30,
			'star' => 50,
			'lines' => 5,
			'length' => 4,
			'fonts' => 6
		),//后台头像上传类型
		'admin_head' => 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif',
		//后台分页每页显示数据量
		'admin_pagecount' => 5
	);