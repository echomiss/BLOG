# MySQL-Front 5.1  (Build 3.62)

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;


# Host: localhost    Database: project
# ------------------------------------------------------
# Server version 5.5.27-log

#
# Source for table my_admin
#

DROP TABLE IF EXISTS `my_admin`;
CREATE TABLE `my_admin` (
  `a_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `a_account` varchar(50) NOT NULL,
  `a_password` char(32) NOT NULL,
  `a_nickname` varchar(50) DEFAULT NULL,
  `a_last_time` int(10) unsigned DEFAULT '10',
  `a_last_ip` char(15) DEFAULT NULL,
  `a_head_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `a_account` (`a_account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Source for table my_article
#

DROP TABLE IF EXISTS `my_article`;
CREATE TABLE `my_article` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) NOT NULL COMMENT '文章名字',
  `a_nickname` varchar(50) DEFAULT NULL COMMENT '文章别名',
  `a_author` varchar(20) DEFAULT NULL COMMENT '作者名字',
  `c_id` int(11) NOT NULL COMMENT '文章分类',
  `a_content` text NOT NULL COMMENT '文章内容',
  `a_publish_time` int(10) unsigned DEFAULT NULL COMMENT '文章发布时间',
  `a_status` enum('公开','草稿','隐私') DEFAULT '公开' COMMENT '文章状态',
  `a_clicks` int(10) unsigned DEFAULT '0' COMMENT '文章点击数',
  `a_sort` int(10) unsigned DEFAULT '50' COMMENT '文章排序',
  `a_recover` enum('否','是') DEFAULT '否',
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

#
# Source for table my_category
#

DROP TABLE IF EXISTS `my_category`;
CREATE TABLE `my_category` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(10) NOT NULL,
  `c_nickname` varchar(20) DEFAULT NULL,
  `c_sort` int(10) unsigned DEFAULT '50',
  `c_article_num` int(10) unsigned DEFAULT '0',
  `c_parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

#
# Source for table my_message
#

DROP TABLE IF EXISTS `my_message`;
CREATE TABLE `my_message` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `m_visitor_id` varchar(20) NOT NULL,
  `m_visitor_ip` varchar(15) NOT NULL,
  `m_parent_id` int(11) DEFAULT '0',
  `a_name` varchar(20) DEFAULT NULL,
  `m_content` text NOT NULL,
  `a_id` int(11) NOT NULL,
  `m_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Source for table my_session
#

DROP TABLE IF EXISTS `my_session`;
CREATE TABLE `my_session` (
  `s_id` char(32) NOT NULL COMMENT 'sessionID主键',
  `s_content` text COMMENT 'session数据：序列化数据',
  `s_last` int(10) unsigned DEFAULT NULL COMMENT '最后修改时间',
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
