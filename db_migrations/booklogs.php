<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/28 0028
 * Time: 18:26
 * `<{{tablename}}>`
 */
return "
CREATE TABLE `<{{tablename}}>` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` int(11) DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:禁用；1:启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_url` (`url`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";