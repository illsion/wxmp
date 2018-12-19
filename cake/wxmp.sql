/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.1.29-MariaDB : Database - wxmp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`wxmp` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `wxmp`;

/*Table structure for table `miniapps` */

DROP TABLE IF EXISTS `miniapps`;

CREATE TABLE `miniapps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL COMMENT '小程序名称',
  `appid` varchar(64) NOT NULL,
  `secret` varchar(64) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `miniapps` */

/*Table structure for table `mp_events` */

DROP TABLE IF EXISTS `mp_events`;

CREATE TABLE `mp_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) unsigned NOT NULL COMMENT '关联公众号',
  `mp_rule_id` int(11) NOT NULL COMMENT '关联规则',
  `name` varchar(50) NOT NULL COMMENT '事件类型',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '1:开启,2:关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公众号事件列表';

/*Data for the table `mp_events` */

/*Table structure for table `mp_member_openid` */

DROP TABLE IF EXISTS `mp_member_openid`;

CREATE TABLE `mp_member_openid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) NOT NULL,
  `openid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid_index` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='公众号关注用户openid表';

/*Data for the table `mp_member_openid` */

insert  into `mp_member_openid`(`id`,`mp_id`,`openid`) values 
(1,1,'ov7aY1EDed2YYDDmbFAr1GsBp2Ag');

/*Table structure for table `mp_menus` */

DROP TABLE IF EXISTS `mp_menus`;

CREATE TABLE `mp_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  `mp_id` int(11) NOT NULL COMMENT '关联公众号',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `type` varchar(50) DEFAULT NULL COMMENT '菜单类型',
  `content` varchar(255) DEFAULT NULL COMMENT '菜单内容',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `mp_menus` */

insert  into `mp_menus`(`id`,`parent_id`,`mp_id`,`name`,`type`,`content`,`sort`) values 
(11,0,1,'今日歌曲','click','{\"key\":\"test23\"}',0),
(12,0,1,'菜单',NULL,NULL,0),
(13,12,1,'搜索','view','{\"url\":\"http:\\/\\/www.soso.com\\/\"}',0),
(14,0,1,'ces','view','{\"url\":\"http:\\/\\/www.baidu.com\"}',0);

/*Table structure for table `mp_messages` */

DROP TABLE IF EXISTS `mp_messages`;

CREATE TABLE `mp_messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `url` varchar(120) DEFAULT NULL COMMENT '链接地址',
  `media_url` varchar(120) DEFAULT NULL COMMENT '图片链接/其他资源链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='公众号回复消息';

/*Data for the table `mp_messages` */

insert  into `mp_messages`(`id`,`title`,`description`,`content`,`url`,`media_url`) values 
(1,'测试标题1','测试描述1','这里是内容','链接1','files/20181211/btfvpw3m.gif'),
(2,'测试','测试1','测试测试测试','http://www.baidu.com','files/20181214/ype59pjj.gif'),
(3,'测试2','测试2','123123',NULL,'files/20181214/y1bm1ahb.gif');

/*Table structure for table `mp_rules` */

DROP TABLE IF EXISTS `mp_rules`;

CREATE TABLE `mp_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) unsigned NOT NULL COMMENT '关联公众号',
  `mp_message_id` int(11) NOT NULL COMMENT '关联信息id',
  `keywords` varchar(50) NOT NULL COMMENT '关键字',
  `type` varchar(24) NOT NULL COMMENT '类型,text/video/...',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态,1:开启,2:关闭',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keywords_index` (`keywords`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='回复规则';

/*Data for the table `mp_rules` */

insert  into `mp_rules`(`id`,`mp_id`,`mp_message_id`,`keywords`,`type`,`status`,`created`) values 
(2,1,2,'test23','image',1,'2018-12-11 16:58:50'),
(3,1,3,'test2','text',1,'2018-12-11 17:02:27');

/*Table structure for table `mps` */

DROP TABLE IF EXISTS `mps`;

CREATE TABLE `mps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户Id',
  `name` varchar(32) NOT NULL COMMENT '公众号名称',
  `appid` varchar(50) NOT NULL COMMENT 'appid',
  `secret` varchar(50) NOT NULL COMMENT 'appsecret',
  `token` varchar(50) DEFAULT NULL COMMENT 'token',
  `origin_id` varchar(50) NOT NULL COMMENT '公众号原始id',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '公众号类型,1:订阅号,2:服务号',
  `description` text COMMENT '描述',
  `created` datetime DEFAULT NULL,
  `qrcode` varchar(80) DEFAULT NULL COMMENT '二维码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `mps` */

insert  into `mps`(`id`,`user_id`,`name`,`appid`,`secret`,`token`,`origin_id`,`type`,`description`,`created`,`qrcode`) values 
(1,1,'测试号','wx13a4b20f15bb25bc','7243b21892ce9d8f203a221db91e9085','qDZEMrupw77Mb3mTnl0kkEBsp0rnv9NK','gh_13c83584091a',1,'这是描述','2018-12-05 10:09:20',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1:正常,2:冻结',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_index` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`status`) values 
(1,'admin','$2y$10$v5bE3wc3AASZSK05CLUvf.hhjWxWEfXZGz.1LAVtNn/70n6DsVFOi',1),
(2,'test','$2y$10$TrH5a349cqDAOfvz.oLNqeX0rV5Me1ht7ZDsA5tqTfl70WbDOA1Vu',1),
(3,'test1111','$2y$10$VLzASwmMIYNxW2E2P7bl.OjCTcYrGLZ2vLeqdkH1EOruRJLyfxRGK',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
