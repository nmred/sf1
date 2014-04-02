--  vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker:
-- 
-- Current Database: `sf_unit`-- 

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sf_unit` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sf_unit`;

-- {{{  table unit_host

-- 
-- 单元测试 host 表
-- 
-- host_id
-- 	主机 id
-- group_id
-- 	主机组 ID
-- host_name
-- 	主机名称

DROP TABLE IF EXISTS `unit_host`;
CREATE TABLE `unit_host` (
	`host_id` int(11) UNSIGNED NOT NULL ,
	`group_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`host_name` varchar(32) NOT NULL ,
	PRIMARY KEY (`host_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
