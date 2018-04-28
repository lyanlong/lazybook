<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/28 0028
 * Time: 18:26
 */
return "CREATE TABLE `<{{tablename}}>` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` INT(11) DEFAULT NULL,
  `updated_at` INT(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:禁用；1:启用',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父节点id',
  `sortid` int(11) NOT NULL COMMENT '排序id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";