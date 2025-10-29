/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : audit

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-07-28 07:16:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audits`
-- ----------------------------
DROP TABLE IF EXISTS `audits`;
CREATE TABLE `audits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of audits
-- ----------------------------
INSERT INTO `audits` VALUES ('1', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"cGDrFUERmc0VQwVUD2yZkA7O0C9dI1lcbnTvTK2kOFVXnAYn2eBf7rUpLcbm\"}', '{\"remember_token\":\"iGGirNeS604Xh3gaCwxAh5phgJ4NF96dKmaJZTSgbsYcHXoeAc0fBkOotPEo\"}', 'http://localhost/auditportal/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', null, '2023-07-21 02:02:24', '2023-07-21 02:02:24');
INSERT INTO `audits` VALUES ('2', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"iGGirNeS604Xh3gaCwxAh5phgJ4NF96dKmaJZTSgbsYcHXoeAc0fBkOotPEo\"}', '{\"remember_token\":\"xj3gkmWiy47jsqsgu24uSU859vJoEj3LIiJ5Q6sHRylxP4gXR6d6N15FcVya\"}', 'http://localhost/auditportal/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', null, '2023-07-21 03:01:11', '2023-07-21 03:01:11');

-- ----------------------------
-- Table structure for `companies`
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES ('1', 'W GROUP INC', 'WGI', 'company_images/wgroup.png', 'company_images/wgroup_icon.png', '2023-07-21 09:05:47', '2023-07-21 09:05:47', null);

-- ----------------------------
-- Table structure for `consequences`
-- ----------------------------
DROP TABLE IF EXISTS `consequences`;
CREATE TABLE `consequences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of consequences
-- ----------------------------
INSERT INTO `consequences` VALUES ('1', 'INSIGNIFICANT', '1', null, null);
INSERT INTO `consequences` VALUES ('2', 'MINOR', '2', null, null);
INSERT INTO `consequences` VALUES ('3', 'MODERATE', '3', null, null);
INSERT INTO `consequences` VALUES ('4', 'MAJOR', '4', null, null);
INSERT INTO `consequences` VALUES ('5', 'CATASTROPHIC/VERY SIGNIFICANT', '5', null, null);

-- ----------------------------
-- Table structure for `departments`
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of departments
-- ----------------------------

-- ----------------------------
-- Table structure for `likelihoods`
-- ----------------------------
DROP TABLE IF EXISTS `likelihoods`;
CREATE TABLE `likelihoods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of likelihoods
-- ----------------------------
INSERT INTO `likelihoods` VALUES ('1', 'RARE', '1', null, null);
INSERT INTO `likelihoods` VALUES ('2', 'UNLIKELY', '2', null, null);
INSERT INTO `likelihoods` VALUES ('3', 'POSSIBLE', '3', null, null);
INSERT INTO `likelihoods` VALUES ('4', 'LIKELY', '4', null, null);
INSERT INTO `likelihoods` VALUES ('5', 'ALMOST CERTAIN', '5', null, null);

-- ----------------------------
-- Table structure for `matrices`
-- ----------------------------
DROP TABLE IF EXISTS `matrices`;
CREATE TABLE `matrices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of matrices
-- ----------------------------
INSERT INTO `matrices` VALUES ('1', 'LOW', '1', '4', null, null);
INSERT INTO `matrices` VALUES ('2', 'MODERATE', '5', '9', null, null);
INSERT INTO `matrices` VALUES ('3', 'SIGNIFICANT', '10', '12', null, null);
INSERT INTO `matrices` VALUES ('4', 'HIGH', '15', '25', null, null);

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('3', '2023_07_21_002651_create_audits_table', '2');

-- ----------------------------
-- Table structure for `password_resets`
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin', 'admin@admin.com', null, '$2y$10$G9OPSMDrtn1IUK/eCM.uX.zUOsnBRJAn/ia82z3SfEShC8bAj5MKm', 'xj3gkmWiy47jsqsgu24uSU859vJoEj3LIiJ5Q6sHRylxP4gXR6d6N15FcVya', null, '2023-07-20 00:06:34', 'Admin');
