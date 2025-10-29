/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : edms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-08-08 17:53:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `archives`
-- ----------------------------
DROP TABLE IF EXISTS `archives`;
CREATE TABLE `archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permit_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `accountable_person` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT '',
  `expiration_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of archives
-- ----------------------------
INSERT INTO `archives` VALUES ('1', '2', 'Permit', 'Permit', '1', '1', null, '/permits_attachments/1690883630_bg.jpg', '2024-04-30', '2', '2023-08-01 10:24:01', '2023-08-01 10:24:01', 'Permit');
INSERT INTO `archives` VALUES ('2', '1', 'Title', 'Sample Only', '1', '1', null, '/permits_attachments/1690881906_bg.jpg', '2023-08-31', null, '2023-08-01 10:24:19', '2023-08-01 10:24:19', 'License');
INSERT INTO `archives` VALUES ('3', '5', 'bp', 'wfa', '1', '1', null, '/permits_attachments/1690978870__$Company Masterlist.xlsx', '2023-11-02', '2', '2023-08-02 12:21:39', '2023-08-02 12:21:39', 'License');
INSERT INTO `archives` VALUES ('4', '5', 'bp', 'wfa', '1', '1', null, '/permits_attachments/1690978899__$Company Masterlist.xlsx', '2023-11-01', '2', '2023-08-04 16:05:15', '2023-08-04 16:05:15', 'License');

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
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of audits
-- ----------------------------
INSERT INTO `audits` VALUES ('1', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$G9OPSMDrtn1IUK\\/eCM.uX.zUOsnBRJAn\\/ia82z3SfEShC8bAj5MKm\",\"remember_token\":\"xj3gkmWiy47jsqsgu24uSU859vJoEj3LIiJ5Q6sHRylxP4gXR6d6N15FcVya\"}', '{\"password\":\"$2y$10$5emgCCxK\\/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC\",\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', 'http://localhost/edms/public/password/reset?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-26 21:29:20', '2023-07-26 21:29:20');
INSERT INTO `audits` VALUES ('2', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', '{\"remember_token\":\"VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-27 19:48:22', '2023-07-27 19:48:22');
INSERT INTO `audits` VALUES ('3', 'App\\User', '1', 'created', 'App\\User', '2', '[]', '{\"name\":\"Renz Christian Cabato\",\"email\":\"cabato.renz.renz@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Administrator\",\"password\":\"$2y$10$N63xmV\\/1JnPBML7c2trO6.a7QPd5CzfqoDi.LXDQCqXq56C1OC8za\",\"id\":2}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-30 20:27:56', '2023-07-30 20:27:56');
INSERT INTO `audits` VALUES ('4', 'App\\User', '1', 'created', 'App\\User', '3', '[]', '{\"name\":\"Filomena Agnes Cabulong\",\"email\":\"f.cabulong@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Business Process Manager\",\"password\":\"$2y$10$eB.PWNqyUG7jDZjsVPyjxO7DMskPYzi78CESFeaADl\\/XMKGtqCScG\",\"id\":3}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-30 20:44:22', '2023-07-30 20:44:22');
INSERT INTO `audits` VALUES ('5', 'App\\User', '1', 'created', 'App\\User', '4', '[]', '{\"name\":\"Department head\",\"email\":\"dep_head@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Department Head\",\"password\":\"$2y$10$B6\\/3as\\/8CO5gyX6UUecCXOnimsDmKEwBPzwvQhDsygg.PT4Ezle\\/2\",\"id\":4}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:36:01', '2023-08-01 20:36:01');
INSERT INTO `audits` VALUES ('6', 'App\\User', '1', 'created', 'App\\Department', '2', '[]', '{\"code\":\"0001\",\"name\":\"crm-vortex\",\"user_id\":\"4\",\"id\":2}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:41:14', '2023-08-01 20:41:14');
INSERT INTO `audits` VALUES ('7', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:50:45', '2023-08-01 20:50:45');
INSERT INTO `audits` VALUES ('8', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:52:46', '2023-08-01 20:52:46');
INSERT INTO `audits` VALUES ('9', 'App\\User', '1', 'created', 'App\\Department', '3', '[]', '{\"code\":\"SAMPLE\",\"name\":\"SAMPLE DEPARTMENT\",\"user_id\":\"4\",\"id\":3}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:55:26', '2023-08-01 20:55:26');
INSERT INTO `audits` VALUES ('10', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:57:40', '2023-08-01 20:57:40');
INSERT INTO `audits` VALUES ('11', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 20:57:43', '2023-08-01 20:57:43');
INSERT INTO `audits` VALUES ('12', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:02:36', '2023-08-01 21:02:36');
INSERT INTO `audits` VALUES ('13', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:03:03', '2023-08-01 21:03:03');
INSERT INTO `audits` VALUES ('14', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$5emgCCxK\\/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC\"}', '{\"password\":\"$2y$10$z6sSNR\\/Nvz33kfvKWbJlq.Ce\\/TKtEEca3AWj.GeLloUXv7Z9fZRhy\"}', 'http://localhost/edms/public/change-password/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:03:58', '2023-08-01 21:03:58');
INSERT INTO `audits` VALUES ('15', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$z6sSNR\\/Nvz33kfvKWbJlq.Ce\\/TKtEEca3AWj.GeLloUXv7Z9fZRhy\"}', '{\"password\":\"$2y$10$PbGAH7o7P6JM9V22Erv0NuORq18yRmz58IL7x9YdFKauS6UmBes7W\"}', 'http://localhost/edms/public/change-password/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:04:07', '2023-08-01 21:04:07');
INSERT INTO `audits` VALUES ('16', 'App\\User', '1', 'updated', 'App\\User', '3', '{\"password\":\"$2y$10$eB.PWNqyUG7jDZjsVPyjxO7DMskPYzi78CESFeaADl\\/XMKGtqCScG\"}', '{\"password\":\"$2y$10$kpSHGrSFdn4f3F.Qs4d\\/5u5XV0Y8UaDz4KQGkfq251mbF8M0k4Z82\"}', 'http://localhost/edms/public/change-password/3?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:04:16', '2023-08-01 21:04:16');
INSERT INTO `audits` VALUES ('17', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"name\":\"Admin\"}', '{\"name\":\"Admin1\"}', 'http://localhost/edms/public/edit-user/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:08:41', '2023-08-01 21:08:41');
INSERT INTO `audits` VALUES ('18', 'App\\User', '1', 'updated', 'App\\User', '4', '{\"password\":\"$2y$10$B6\\/3as\\/8CO5gyX6UUecCXOnimsDmKEwBPzwvQhDsygg.PT4Ezle\\/2\",\"status\":null}', '{\"password\":\"\",\"status\":1}', 'http://localhost/edms/public/deactivate-user?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:13:00', '2023-08-01 21:13:00');
INSERT INTO `audits` VALUES ('19', 'App\\User', '1', 'updated', 'App\\User', '4', '{\"status\":\"1\"}', '{\"status\":\"\"}', 'http://localhost/edms/public/activate-user?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:13:03', '2023-08-01 21:13:03');
INSERT INTO `audits` VALUES ('20', 'App\\User', '1', 'created', 'App\\Company', '2', '[]', '{\"code\":\"sasd\",\"name\":\"123\",\"id\":2}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:59:10', '2023-08-01 21:59:10');
INSERT INTO `audits` VALUES ('21', 'App\\User', '1', 'created', 'App\\Company', '3', '[]', '{\"code\":\"sasd\",\"name\":\"123\",\"id\":3}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:59:21', '2023-08-01 21:59:21');
INSERT INTO `audits` VALUES ('22', 'App\\User', '1', 'created', 'App\\Company', '4', '[]', '{\"code\":\"0002\",\"name\":\"asda 123\",\"id\":4}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 21:59:44', '2023-08-01 21:59:44');
INSERT INTO `audits` VALUES ('23', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI\"}', '{\"remember_token\":\"xfr6WFmk8Pp4ghqNqZDlu8ZtVnJKdl76IFCqzFObFCVg396n2A8cvjOJfH3E\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 22:18:06', '2023-08-01 22:18:06');
INSERT INTO `audits` VALUES ('24', 'App\\User', '2', 'updated', 'App\\Company', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 23:07:15', '2023-08-01 23:07:15');
INSERT INTO `audits` VALUES ('25', 'App\\User', '2', 'updated', 'App\\Company', '3', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 23:07:18', '2023-08-01 23:07:18');
INSERT INTO `audits` VALUES ('26', 'App\\User', '2', 'created', 'App\\Permit', '1', '[]', '{\"title\":\"Title\",\"description\":\"Sample Only\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"License\",\"file\":\"\\/permits\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"id\":1}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 00:25:06', '2023-08-02 00:25:06');
INSERT INTO `audits` VALUES ('27', 'App\\User', '2', 'created', 'App\\Permit', '2', '[]', '{\"title\":\"Permit\",\"description\":\"Permit\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"Permit\",\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\",\"user_id\":2,\"id\":2}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 00:53:50', '2023-08-02 00:53:50');
INSERT INTO `audits` VALUES ('28', 'App\\User', '2', 'created', 'App\\Archive', '1', '[]', '{\"permit_id\":\"2\",\"title\":\"Permit\",\"description\":\"Permit\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\",\"user_id\":2,\"type\":\"Permit\",\"id\":1}', 'http://localhost/edms/public/upload-permit/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:24:01', '2023-08-02 01:24:01');
INSERT INTO `audits` VALUES ('29', 'App\\User', '2', 'updated', 'App\\Permit', '2', '{\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\"}', '{\"file\":\"\\/permits_attachments\\/1690885441_bg.jpg\",\"expiration_date\":\"2024-04-24\"}', 'http://localhost/edms/public/upload-permit/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:24:01', '2023-08-02 01:24:01');
INSERT INTO `audits` VALUES ('30', 'App\\User', '2', 'created', 'App\\Archive', '2', '[]', '{\"permit_id\":\"1\",\"title\":\"Title\",\"description\":\"Sample Only\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"user_id\":null,\"type\":\"License\",\"id\":2}', 'http://localhost/edms/public/upload-permit/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:24:19', '2023-08-02 01:24:19');
INSERT INTO `audits` VALUES ('31', 'App\\User', '2', 'updated', 'App\\Permit', '1', '{\"file\":\"\\/permits_attachments\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"user_id\":null}', '{\"file\":\"\\/permits_attachments\\/1690885459_bg.jpg\",\"expiration_date\":\"2024-06-12\",\"user_id\":2}', 'http://localhost/edms/public/upload-permit/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:24:19', '2023-08-02 01:24:19');
INSERT INTO `audits` VALUES ('32', 'App\\User', '2', 'created', 'App\\Department', '4', '[]', '{\"code\":\"HRD\",\"name\":\"Human Resource Department\",\"user_id\":\"4\",\"permit_accountable\":\"3\",\"id\":4}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:34:13', '2023-08-02 01:34:13');
INSERT INTO `audits` VALUES ('33', 'App\\User', '2', 'updated', 'App\\Permit', '1', '{\"department_id\":1}', '{\"department_id\":\"4\"}', 'http://localhost/edms/public/change-department/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 01:36:07', '2023-08-02 01:36:07');
INSERT INTO `audits` VALUES ('34', 'App\\User', '2', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 17:23:04', '2023-08-02 17:23:04');
INSERT INTO `audits` VALUES ('35', 'App\\User', '2', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 17:23:11', '2023-08-02 17:23:11');
INSERT INTO `audits` VALUES ('36', 'App\\User', '2', 'created', 'App\\User', '5', '[]', '{\"name\":\"DCO\",\"email\":\"dco@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Document Control Officer\",\"password\":\"$2y$10$RVhKuyWDgCIqkUbvO\\/QKXe0k9j2cdxBmOwDX2U2BY45Bel2ulaGoe\",\"id\":5}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 17:31:53', '2023-08-02 17:31:53');
INSERT INTO `audits` VALUES ('37', 'App\\User', '2', 'created', 'App\\DepartmentDco', '1', '[]', '{\"user_id\":\"5\",\"department_id\":\"2\",\"id\":1}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 17:59:09', '2023-08-02 17:59:09');
INSERT INTO `audits` VALUES ('38', 'App\\User', '2', 'created', 'App\\DepartmentDco', '2', '[]', '{\"user_id\":\"5\",\"department_id\":\"4\",\"id\":2}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 17:59:09', '2023-08-02 17:59:09');
INSERT INTO `audits` VALUES ('39', 'App\\User', '2', 'created', 'App\\User', '6', '[]', '{\"name\":\"DCO 1\",\"email\":\"dco1@gmail.com\",\"company_id\":\"1\",\"department_id\":\"4\",\"role\":\"Document Control Officer\",\"password\":\"$2y$10$P6t2bxfRaeZd7gZcjaUhH.aCOGmaFLV35klCPf\\/6s2QIV0XLftSYe\",\"id\":6}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 18:00:45', '2023-08-02 18:00:45');
INSERT INTO `audits` VALUES ('40', 'App\\User', '2', 'created', 'App\\DepartmentDco', '3', '[]', '{\"user_id\":\"6\",\"department_id\":\"2\",\"id\":3}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 18:00:55', '2023-08-02 18:00:55');
INSERT INTO `audits` VALUES ('41', 'App\\User', '2', 'created', 'App\\DepartmentDco', '4', '[]', '{\"user_id\":\"6\",\"department_id\":\"4\",\"id\":4}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 18:00:55', '2023-08-02 18:00:55');
INSERT INTO `audits` VALUES ('42', 'App\\User', '2', 'created', 'App\\DepartmentDco', '5', '[]', '{\"user_id\":\"6\",\"department_id\":\"1\",\"id\":5}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 18:01:07', '2023-08-02 18:01:07');
INSERT INTO `audits` VALUES ('43', 'App\\User', '2', 'created', 'App\\DepartmentDco', '6', '[]', '{\"user_id\":\"6\",\"department_id\":\"4\",\"id\":6}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 18:01:07', '2023-08-02 18:01:07');
INSERT INTO `audits` VALUES ('44', 'App\\User', '2', 'created', 'App\\DepartmentDco', '7', '[]', '{\"user_id\":\"5\",\"department_id\":\"2\",\"id\":7}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:29:47', '2023-08-02 19:29:47');
INSERT INTO `audits` VALUES ('45', 'App\\User', '2', 'created', 'App\\DepartmentDco', '8', '[]', '{\"user_id\":\"5\",\"department_id\":\"4\",\"id\":8}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:29:47', '2023-08-02 19:29:47');
INSERT INTO `audits` VALUES ('46', 'App\\User', '2', 'created', 'App\\DepartmentDco', '9', '[]', '{\"user_id\":\"5\",\"department_id\":\"1\",\"id\":9}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:30:05', '2023-08-02 19:30:05');
INSERT INTO `audits` VALUES ('47', 'App\\User', '2', 'created', 'App\\DepartmentDco', '10', '[]', '{\"user_id\":\"5\",\"department_id\":\"2\",\"id\":10}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:30:05', '2023-08-02 19:30:05');
INSERT INTO `audits` VALUES ('48', 'App\\User', '2', 'created', 'App\\DepartmentDco', '11', '[]', '{\"user_id\":\"5\",\"department_id\":\"3\",\"id\":11}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:30:05', '2023-08-02 19:30:05');
INSERT INTO `audits` VALUES ('49', 'App\\User', '2', 'created', 'App\\DepartmentDco', '12', '[]', '{\"user_id\":\"5\",\"department_id\":\"4\",\"id\":12}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:30:05', '2023-08-02 19:30:05');
INSERT INTO `audits` VALUES ('50', 'App\\User', '2', 'created', 'App\\DepartmentDco', '13', '[]', '{\"user_id\":\"5\",\"department_id\":\"1\",\"id\":13}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:30:38', '2023-08-02 19:30:38');
INSERT INTO `audits` VALUES ('51', 'App\\User', '2', 'created', 'App\\Permit', '3', '[]', '{\"title\":\"Nearly Expired\",\"description\":\"Expiration\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"License\",\"file\":\"\\/permits_attachments\\/1690951898_icon-72x72.png\",\"expiration_date\":\"2023-08-16\",\"user_id\":2,\"id\":3}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 19:51:38', '2023-08-02 19:51:38');
INSERT INTO `audits` VALUES ('52', 'App\\User', '2', 'created', 'App\\User', '7', '[]', '{\"name\":\"DRC\",\"email\":\"drc@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Documents and Records Controller\",\"password\":\"$2y$10$G4GY8YJYXO1EyxLigaMPVeggdclv5\\/J9oPkfjL1ThITyjuzbEjcn2\",\"id\":7}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-02 23:55:51', '2023-08-02 23:55:51');
INSERT INTO `audits` VALUES ('53', 'App\\User', '2', 'created', 'App\\Permit', '4', '[]', '{\"title\":\"Business Permit\",\"description\":\"for WCC\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"Permit\",\"file\":\"\\/permits_attachments\\/1690978804__$Company Masterlist.xlsx\",\"expiration_date\":\"2024-06-05\",\"user_id\":2,\"id\":4}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 03:20:04', '2023-08-03 03:20:04');
INSERT INTO `audits` VALUES ('54', 'App\\User', '2', 'created', 'App\\Permit', '5', '[]', '{\"title\":\"bp\",\"description\":\"wfa\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"License\",\"file\":\"\\/permits_attachments\\/1690978870__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-02\",\"user_id\":2,\"id\":5}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 03:21:10', '2023-08-03 03:21:10');
INSERT INTO `audits` VALUES ('55', 'App\\User', '2', 'created', 'App\\Archive', '3', '[]', '{\"permit_id\":\"5\",\"title\":\"bp\",\"description\":\"wfa\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690978870__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-02\",\"user_id\":2,\"type\":\"License\",\"id\":3}', 'http://localhost/edms/public/upload-permit/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 03:21:39', '2023-08-03 03:21:39');
INSERT INTO `audits` VALUES ('56', 'App\\User', '2', 'updated', 'App\\Permit', '5', '{\"file\":\"\\/permits_attachments\\/1690978870__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-02\"}', '{\"file\":\"\\/permits_attachments\\/1690978899__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-01\"}', 'http://localhost/edms/public/upload-permit/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 03:21:39', '2023-08-03 03:21:39');
INSERT INTO `audits` VALUES ('57', 'App\\User', '2', 'created', 'App\\Document', '1', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":1}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 21:56:39', '2023-08-03 21:56:39');
INSERT INTO `audits` VALUES ('58', 'App\\User', '2', 'created', 'App\\Document', '2', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":2}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 21:57:04', '2023-08-03 21:57:04');
INSERT INTO `audits` VALUES ('59', 'App\\User', '2', 'created', 'App\\Document', '1', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":1}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 21:58:54', '2023-08-03 21:58:54');
INSERT INTO `audits` VALUES ('60', 'App\\User', '2', 'created', 'App\\Document', '2', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":2}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 21:59:47', '2023-08-03 21:59:47');
INSERT INTO `audits` VALUES ('61', 'App\\User', '2', 'created', 'App\\Document', '3', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":3}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 22:00:30', '2023-08-03 22:00:30');
INSERT INTO `audits` VALUES ('62', 'App\\User', '2', 'created', 'App\\Document', '4', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":4}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 22:00:53', '2023-08-03 22:00:53');
INSERT INTO `audits` VALUES ('63', 'App\\User', '2', 'created', 'App\\Document', '1', '[]', '{\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-03\",\"user_id\":2,\"version\":\"1\",\"id\":1}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-03 22:05:54', '2023-08-03 22:05:54');
INSERT INTO `audits` VALUES ('64', 'App\\User', '2', 'created', 'App\\Document', '2', '[]', '{\"control_code\":\"FR-BPD-016\",\"title\":\"DOCUMENT COPY REQUEST\",\"company_id\":\"1\",\"department_id\":\"1\",\"category\":\"FORM\",\"other_category\":null,\"effective_date\":\"2023-08-04\",\"user_id\":2,\"version\":\"1\",\"id\":2}', 'http://localhost/edms/public/upload-document?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 04:44:44', '2023-08-05 04:44:44');
INSERT INTO `audits` VALUES ('65', 'App\\User', '2', 'updated', 'App\\User', '7', '{\"name\":\"DRC\"}', '{\"name\":\"DRC - IT\"}', 'http://localhost/edms/public/edit-user/7?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 04:45:07', '2023-08-05 04:45:07');
INSERT INTO `audits` VALUES ('66', 'App\\User', '2', 'created', 'App\\Department', '5', '[]', '{\"code\":\"BPD\",\"name\":\"Business Process Department\",\"user_id\":\"4\",\"permit_accountable\":null,\"id\":5}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 06:46:58', '2023-08-05 06:46:58');
INSERT INTO `audits` VALUES ('67', 'App\\User', '2', 'created', 'App\\DepartmentDco', '14', '[]', '{\"user_id\":\"5\",\"department_id\":\"1\",\"id\":14}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 06:47:20', '2023-08-05 06:47:20');
INSERT INTO `audits` VALUES ('68', 'App\\User', '2', 'created', 'App\\DepartmentDco', '15', '[]', '{\"user_id\":\"5\",\"department_id\":\"5\",\"id\":15}', 'http://localhost/edms/public/edit-dco/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 06:47:20', '2023-08-05 06:47:20');
INSERT INTO `audits` VALUES ('69', 'App\\User', '2', 'created', 'App\\User', '8', '[]', '{\"name\":\"DRC BPD\",\"email\":\"drcbpd@gmail.com\",\"company_id\":\"1\",\"department_id\":\"5\",\"role\":\"Documents and Records Controller\",\"password\":\"$2y$10$b3lrg6ZW8Yl5m.mksLkj3esJYaXVZVgdmThFIknhlNkMg85cHHJ4W\",\"id\":8}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 06:48:14', '2023-08-05 06:48:14');
INSERT INTO `audits` VALUES ('70', 'App\\User', '2', 'updated', 'App\\User', '2', '{\"remember_token\":null}', '{\"remember_token\":\"lhoaDr8SJWFXqSO6MFfAP3OAAjYMQhqlMhtrEullYlzbZp0Em9PZOBkA1K8m\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 06:56:52', '2023-08-05 06:56:52');
INSERT INTO `audits` VALUES ('71', 'App\\User', '2', 'created', 'App\\DepartmentDco', '16', '[]', '{\"user_id\":\"6\",\"department_id\":\"1\",\"id\":16}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:02:50', '2023-08-05 07:02:50');
INSERT INTO `audits` VALUES ('72', 'App\\User', '2', 'created', 'App\\DepartmentDco', '17', '[]', '{\"user_id\":\"6\",\"department_id\":\"2\",\"id\":17}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:02:50', '2023-08-05 07:02:50');
INSERT INTO `audits` VALUES ('73', 'App\\User', '2', 'created', 'App\\DepartmentDco', '18', '[]', '{\"user_id\":\"6\",\"department_id\":\"4\",\"id\":18}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:02:50', '2023-08-05 07:02:50');
INSERT INTO `audits` VALUES ('74', 'App\\User', '2', 'created', 'App\\DepartmentDco', '19', '[]', '{\"user_id\":\"6\",\"department_id\":\"1\",\"id\":19}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:02:56', '2023-08-05 07:02:56');
INSERT INTO `audits` VALUES ('75', 'App\\User', '2', 'created', 'App\\DepartmentDco', '20', '[]', '{\"user_id\":\"6\",\"department_id\":\"2\",\"id\":20}', 'http://localhost/edms/public/edit-dco/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:02:56', '2023-08-05 07:02:56');
INSERT INTO `audits` VALUES ('76', 'App\\User', '2', 'created', 'App\\Archive', '4', '[]', '{\"permit_id\":\"5\",\"title\":\"bp\",\"description\":\"wfa\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690978899__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-01\",\"user_id\":2,\"type\":\"License\",\"id\":4}', 'http://localhost/edms/public/upload-permit/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:05:15', '2023-08-05 07:05:15');
INSERT INTO `audits` VALUES ('77', 'App\\User', '2', 'updated', 'App\\Permit', '5', '{\"file\":\"\\/permits_attachments\\/1690978899__$Company Masterlist.xlsx\",\"expiration_date\":\"2023-11-01\"}', '{\"file\":\"\\/permits_attachments\\/1691136315_1690978899__$Company Masterlist (1).xlsx\",\"expiration_date\":\"2024-03-06\"}', 'http://localhost/edms/public/upload-permit/5?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-05 07:05:15', '2023-08-05 07:05:15');
INSERT INTO `audits` VALUES ('78', 'App\\User', '2', 'created', 'App\\Department', '6', '[]', '{\"code\":\"123\",\"name\":\"asda asd a\",\"user_id\":\"4\",\"permit_accountable\":\"2\",\"id\":6}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('79', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '1', '[]', '{\"department_id\":6,\"user_id\":\"1\",\"level\":1,\"id\":1}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('80', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '2', '[]', '{\"department_id\":6,\"user_id\":\"2\",\"level\":2,\"id\":2}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('81', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '3', '[]', '{\"department_id\":6,\"user_id\":\"3\",\"level\":3,\"id\":3}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('82', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '4', '[]', '{\"department_id\":6,\"user_id\":\"5\",\"level\":4,\"id\":4}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('83', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '5', '[]', '{\"department_id\":6,\"user_id\":\"8\",\"level\":5,\"id\":5}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('84', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '6', '[]', '{\"department_id\":6,\"user_id\":\"7\",\"level\":6,\"id\":6}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 09:34:47', '2023-08-08 09:34:47');
INSERT INTO `audits` VALUES ('85', 'App\\User', '2', 'updated', 'App\\Department', '2', '{\"permit_accountable\":null}', '{\"permit_accountable\":\"1\"}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:01:30', '2023-08-08 10:01:30');
INSERT INTO `audits` VALUES ('86', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '7', '[]', '{\"department_id\":2,\"user_id\":\"1\",\"level\":1,\"id\":7}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:09:26', '2023-08-08 10:09:26');
INSERT INTO `audits` VALUES ('87', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '8', '[]', '{\"department_id\":2,\"user_id\":\"2\",\"level\":2,\"id\":8}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:09:26', '2023-08-08 10:09:26');
INSERT INTO `audits` VALUES ('88', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '9', '[]', '{\"department_id\":2,\"user_id\":\"3\",\"level\":3,\"id\":9}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:09:26', '2023-08-08 10:09:26');
INSERT INTO `audits` VALUES ('89', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '10', '[]', '{\"department_id\":2,\"user_id\":\"1\",\"level\":1,\"id\":10}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:09:32', '2023-08-08 10:09:32');
INSERT INTO `audits` VALUES ('90', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '11', '[]', '{\"department_id\":2,\"user_id\":\"7\",\"level\":2,\"id\":11}', 'http://localhost/edms/public/edit-department/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 10:09:32', '2023-08-08 10:09:32');
INSERT INTO `audits` VALUES ('91', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '12', '[]', '{\"department_id\":6,\"user_id\":\"1\",\"level\":1,\"id\":12}', 'http://localhost/edms/public/edit-department/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `audits` VALUES ('92', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '13', '[]', '{\"department_id\":6,\"user_id\":\"2\",\"level\":2,\"id\":13}', 'http://localhost/edms/public/edit-department/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `audits` VALUES ('93', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '14', '[]', '{\"department_id\":6,\"user_id\":\"3\",\"level\":3,\"id\":14}', 'http://localhost/edms/public/edit-department/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `audits` VALUES ('94', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '15', '[]', '{\"department_id\":6,\"user_id\":\"5\",\"level\":4,\"id\":15}', 'http://localhost/edms/public/edit-department/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `audits` VALUES ('95', 'App\\User', '2', 'created', 'App\\DepartmentApprover', '16', '[]', '{\"department_id\":6,\"user_id\":\"8\",\"level\":5,\"id\":16}', 'http://localhost/edms/public/edit-department/6?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `audits` VALUES ('96', 'App\\User', '2', 'updated', 'App\\User', '5', '{\"password\":\"$2y$10$RVhKuyWDgCIqkUbvO\\/QKXe0k9j2cdxBmOwDX2U2BY45Bel2ulaGoe\",\"status\":null}', '{\"password\":\"\",\"status\":1}', 'http://localhost/edms/public/deactivate-user?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 13:18:10', '2023-08-08 13:18:10');
INSERT INTO `audits` VALUES ('97', 'App\\User', '2', 'updated', 'App\\User', '2', '{\"remember_token\":\"lhoaDr8SJWFXqSO6MFfAP3OAAjYMQhqlMhtrEullYlzbZp0Em9PZOBkA1K8m\"}', '{\"remember_token\":\"TJ5xS30MmILAb8Yp9buLWOBla6AgqYBJVgcjZoewOqBuwxvygT9AIZHStHHO\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 13:18:14', '2023-08-08 13:18:14');
INSERT INTO `audits` VALUES ('98', 'App\\User', '2', 'created', 'App\\CopyRequest', '1', '[]', '{\"type_of_document\":\"Hard Copy\",\"document_id\":\"1\",\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"revision\":\"1\",\"user_id\":null,\"copy_count\":\"1\",\"status\":\"Pending\",\"level\":1,\"id\":1}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26');
INSERT INTO `audits` VALUES ('99', 'App\\User', '2', 'created', 'App\\CopyApprover', '1', '[]', '{\"copy_request_id\":1,\"user_id\":\"1\",\"status\":\"Pending\",\"start_date\":\"2023-08-08\",\"level\":1,\"id\":1}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26');
INSERT INTO `audits` VALUES ('100', 'App\\User', '2', 'created', 'App\\CopyApprover', '2', '[]', '{\"copy_request_id\":1,\"user_id\":\"8\",\"status\":\"Pending\",\"level\":2,\"id\":2}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26');
INSERT INTO `audits` VALUES ('101', 'App\\User', '2', 'created', 'App\\CopyApprover', '3', '[]', '{\"copy_request_id\":1,\"user_id\":\"4\",\"status\":\"Pending\",\"level\":3,\"id\":3}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26');
INSERT INTO `audits` VALUES ('102', 'App\\User', '2', 'created', 'App\\CopyRequest', '2', '[]', '{\"type_of_document\":\"E-Copy\",\"document_id\":\"1\",\"control_code\":\"FR-BPD-001\",\"title\":\"Documented Information Change Request Form\",\"revision\":\"1\",\"user_id\":2,\"copy_count\":\"1\",\"date_needed\":\"2023-08-15\",\"status\":\"Pending\",\"level\":1,\"id\":2}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06');
INSERT INTO `audits` VALUES ('103', 'App\\User', '2', 'created', 'App\\CopyApprover', '4', '[]', '{\"copy_request_id\":2,\"user_id\":\"1\",\"status\":\"Pending\",\"start_date\":\"2023-08-08\",\"level\":1,\"id\":4}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06');
INSERT INTO `audits` VALUES ('104', 'App\\User', '2', 'created', 'App\\CopyApprover', '5', '[]', '{\"copy_request_id\":2,\"user_id\":\"8\",\"status\":\"Waiting\",\"level\":2,\"id\":5}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06');
INSERT INTO `audits` VALUES ('105', 'App\\User', '2', 'created', 'App\\CopyApprover', '6', '[]', '{\"copy_request_id\":2,\"user_id\":\"4\",\"status\":\"Waiting\",\"level\":3,\"id\":6}', 'http://localhost/edms/public/copy-request?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06');

-- ----------------------------
-- Table structure for `change_requests`
-- ----------------------------
DROP TABLE IF EXISTS `change_requests`;
CREATE TABLE `change_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_type` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_of_document` varchar(255) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `change_request` text DEFAULT NULL,
  `indicate_clause` text DEFAULT NULL,
  `indicate_changes` text DEFAULT NULL,
  `link_draft` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of change_requests
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES ('1', 'W GROUP INC', 'WGI', 'company_images/wgroup.png', 'company_images/wgroup_icon.png', '2023-07-21 09:05:47', '2023-07-21 09:05:47', null);
INSERT INTO `companies` VALUES ('2', 'W HYDROCOLLOIDS, INC', 'WHI', null, null, '2023-08-01 06:59:10', '2023-08-01 08:07:15', null);
INSERT INTO `companies` VALUES ('3', 'W LANDMARK, INC', 'WLI', null, null, '2023-08-01 06:59:21', '2023-08-01 08:07:18', null);
INSERT INTO `companies` VALUES ('4', 'PORTUGUESE REALTY, INC.', 'PRI', null, null, '2023-08-01 06:59:44', '2023-08-01 06:59:44', null);
INSERT INTO `companies` VALUES ('5', 'W HYDROCOLLOIDS, INC - CARMONA PLANT', 'WHI-C', null, null, null, null, null);
INSERT INTO `companies` VALUES ('6', 'CEBU CARRAGEENAN CORPORATION', 'CCC', null, null, null, null, null);
INSERT INTO `companies` VALUES ('7', 'MARINE RESOURCES DEVELOPMENT CORPORATION', 'MRDC', null, null, null, null, null);
INSERT INTO `companies` VALUES ('8', 'STAFFERS PROVIDER OF ASIA INC', 'SPAI', null, null, null, null, null);
INSERT INTO `companies` VALUES ('9', 'FIRST MARCEL TOWER CONDOMINIUM CORPORATION & FIRST MARCEL PROPERTIES INC.', 'FMPI/FMTCC', null, null, null, null, null);
INSERT INTO `companies` VALUES ('10', 'PHILIPPINE BIO INDUSTRIES, INC', 'PBI', null, null, null, null, null);

-- ----------------------------
-- Table structure for `copy_approvers`
-- ----------------------------
DROP TABLE IF EXISTS `copy_approvers`;
CREATE TABLE `copy_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `copy_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of copy_approvers
-- ----------------------------
INSERT INTO `copy_approvers` VALUES ('1', '1', '2', 'Pending', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26', '2023-08-08', '1');
INSERT INTO `copy_approvers` VALUES ('2', '1', '8', 'Waiting', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26', null, '2');
INSERT INTO `copy_approvers` VALUES ('3', '1', '4', 'Waiting', null, '2023-08-08 14:33:26', '2023-08-08 14:33:26', null, '3');
INSERT INTO `copy_approvers` VALUES ('4', '2', '1', 'Pending', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06', '2023-08-08', '1');
INSERT INTO `copy_approvers` VALUES ('5', '2', '8', 'Waiting', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06', null, '2');
INSERT INTO `copy_approvers` VALUES ('6', '2', '4', 'Waiting', null, '2023-08-08 16:33:06', '2023-08-08 16:33:06', null, '3');

-- ----------------------------
-- Table structure for `copy_requests`
-- ----------------------------
DROP TABLE IF EXISTS `copy_requests`;
CREATE TABLE `copy_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_of_document` varchar(255) DEFAULT NULL,
  `date_needed` date DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `control_code` varchar(255) DEFAULT NULL,
  `revision` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `copy_count` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of copy_requests
-- ----------------------------
INSERT INTO `copy_requests` VALUES ('1', 'Hard Copy', '2023-08-08', '1', 'FR-BPD-001', '1', '1', '2023-08-08 14:33:26', '2023-08-08 14:33:26', 'Pending', '1', null, '1', 'Documented Information Change Request Form', '1', '1');
INSERT INTO `copy_requests` VALUES ('2', 'E-Copy', '2023-08-15', '1', 'FR-BPD-001', '1', '2', '2023-08-08 16:33:06', '2023-08-08 16:33:06', 'Pending', '1', null, '1', 'Documented Information Change Request Form', '1', '1');

-- ----------------------------
-- Table structure for `departments`
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `permit_accountable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES ('1', 'ITD', 'Information Technology Department', null, null, null, '1', '1');
INSERT INTO `departments` VALUES ('2', '0001', 'crm-vortex', '2023-08-01 05:41:14', '2023-08-08 10:01:30', null, '4', '1');
INSERT INTO `departments` VALUES ('3', 'SAMPLE', 'SAMPLE DEPARTMENT', '2023-08-01 05:55:26', '2023-08-01 05:55:26', null, '4', null);
INSERT INTO `departments` VALUES ('4', 'HRD', 'Human Resource Department', '2023-08-01 10:34:13', '2023-08-01 10:34:13', null, '4', '3');
INSERT INTO `departments` VALUES ('5', 'BPD', 'Business Process Department', '2023-08-04 15:46:58', '2023-08-04 15:46:58', null, '4', null);
INSERT INTO `departments` VALUES ('6', '123', 'asda asd a', '2023-08-08 09:34:47', '2023-08-08 09:34:47', null, '4', '2');

-- ----------------------------
-- Table structure for `department_approvers`
-- ----------------------------
DROP TABLE IF EXISTS `department_approvers`;
CREATE TABLE `department_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of department_approvers
-- ----------------------------
INSERT INTO `department_approvers` VALUES ('10', '2', '1', '1', '2023-08-08 10:09:32', '2023-08-08 10:09:32');
INSERT INTO `department_approvers` VALUES ('11', '2', '7', '2', '2023-08-08 10:09:32', '2023-08-08 10:09:32');
INSERT INTO `department_approvers` VALUES ('12', '6', '1', '1', '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `department_approvers` VALUES ('13', '6', '2', '2', '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `department_approvers` VALUES ('14', '6', '3', '3', '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `department_approvers` VALUES ('15', '6', '5', '4', '2023-08-08 11:38:44', '2023-08-08 11:38:44');
INSERT INTO `department_approvers` VALUES ('16', '6', '8', '5', '2023-08-08 11:38:44', '2023-08-08 11:38:44');

-- ----------------------------
-- Table structure for `department_dcos`
-- ----------------------------
DROP TABLE IF EXISTS `department_dcos`;
CREATE TABLE `department_dcos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of department_dcos
-- ----------------------------
INSERT INTO `department_dcos` VALUES ('14', '1', '5', '2023-08-04 15:47:20', '2023-08-04 15:47:20');
INSERT INTO `department_dcos` VALUES ('15', '5', '5', '2023-08-04 15:47:20', '2023-08-04 15:47:20');
INSERT INTO `department_dcos` VALUES ('19', '1', '6', '2023-08-04 16:02:56', '2023-08-04 16:02:56');
INSERT INTO `department_dcos` VALUES ('20', '2', '6', '2023-08-04 16:02:56', '2023-08-04 16:02:56');

-- ----------------------------
-- Table structure for `documents`
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_code` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `other_category` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `old_control_code` varchar(255) DEFAULT NULL,
  `last_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of documents
-- ----------------------------
INSERT INTO `documents` VALUES ('1', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', null, '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, null, null);
INSERT INTO `documents` VALUES ('2', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', null, '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, null, null);
INSERT INTO `documents` VALUES ('3', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'POLICY', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('4', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'POLICY', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('5', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'POLICY', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('6', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('7', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('8', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('9', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('10', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('11', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'POLICY', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('12', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'POLICY', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('13', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('14', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('15', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'POLICY', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('16', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('17', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('18', 'FR-BPD-016', '1', '1', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('19', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('20', 'FR-BPD-016', '1', '1', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('21', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('22', 'FR-BPD-016', '1', '1', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('23', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('24', 'FR-BPD-016', '1', '1', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('25', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('26', 'FR-BPD-016', '1', '1', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('27', 'FR-BPD-001', '1', '1', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('28', 'FR-BPD-016', '1', '2', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('29', 'FR-BPD-001', '1', '2', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('30', 'FR-BPD-016', '1', '2', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('31', 'FR-BPD-001', '1', '2', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('32', 'FR-BPD-016', '1', '2', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('33', 'FR-BPD-001', '1', '2', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('34', 'FR-BPD-016', '1', '2', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('35', 'FR-BPD-001', '1', '2', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('36', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('37', 'FR-BPD-001', '1', '3', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('38', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('39', 'FR-BPD-001', '1', '3', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('40', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('41', 'FR-BPD-001', '1', '3', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('42', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('43', 'FR-BPD-001', '1', '3', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('44', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('45', 'FR-BPD-001', '1', '2', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('46', 'FR-BPD-016', '1', '3', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('47', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('48', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);
INSERT INTO `documents` VALUES ('49', 'FR-BPD-001', '1', '5', 'Documented Information Change Request Form', 'FORM', '', '2023-08-03', '2', '1', '2023-08-03 07:05:54', '2023-08-03 07:05:54', null, '', null);
INSERT INTO `documents` VALUES ('50', 'FR-BPD-016', '1', '5', 'DOCUMENT COPY REQUEST', 'FORM', '', '2023-08-04', '2', '1', '2023-08-04 13:44:44', '2023-08-04 13:44:44', null, '', null);

-- ----------------------------
-- Table structure for `document_accesses`
-- ----------------------------
DROP TABLE IF EXISTS `document_accesses`;
CREATE TABLE `document_accesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `action_by` int(11) DEFAULT NULL,
  `stamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of document_accesses
-- ----------------------------

-- ----------------------------
-- Table structure for `document_attachments`
-- ----------------------------
DROP TABLE IF EXISTS `document_attachments`;
CREATE TABLE `document_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of document_attachments
-- ----------------------------
INSERT INTO `document_attachments` VALUES ('1', '1', '/document_attachments/1691046354_acknowledgement-receipt-01.doc', '\'soft_copy\'', '2023-08-03 07:05:54', '2023-08-03 07:05:54');
INSERT INTO `document_attachments` VALUES ('2', '1', '/document_attachments/1691046354_FR-IAD-001 Audit Compliance Report Form (2).pdf', '\'pdf_copy\'', '2023-08-03 07:05:54', '2023-08-03 07:05:54');
INSERT INTO `document_attachments` VALUES ('3', '1', '/document_attachments/1691046354_TP-IAD-007 Initial Report (5).docx', '\'fillable_copy\'', '2023-08-03 07:05:54', '2023-08-03 07:05:54');
INSERT INTO `document_attachments` VALUES ('4', '2', '/document_attachments/1691135084_acknowledgement-receipt-01.doc', '\'soft_copy\'', '2023-08-04 13:44:44', '2023-08-04 13:44:44');
INSERT INTO `document_attachments` VALUES ('5', '2', '/document_attachments/1691135084_FR-IAD-001 Audit Compliance Report Form (2).pdf', '\'pdf_copy\'', '2023-08-04 13:44:44', '2023-08-04 13:44:44');
INSERT INTO `document_attachments` VALUES ('6', '2', '/document_attachments/1691135084_invoice dp.pdf', '\'fillable_copy\'', '2023-08-04 13:44:44', '2023-08-04 13:44:44');

-- ----------------------------
-- Table structure for `document_types`
-- ----------------------------
DROP TABLE IF EXISTS `document_types`;
CREATE TABLE `document_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of document_types
-- ----------------------------
INSERT INTO `document_types` VALUES ('1', 'FORM', 'FR', null, null, '#d4afb9');
INSERT INTO `document_types` VALUES ('2', 'POLICY', 'PL', null, null, '#d1cfe2');
INSERT INTO `document_types` VALUES ('3', 'PROCEDURE', 'PR', null, null, '#9cadce');
INSERT INTO `document_types` VALUES ('4', 'FORM', 'FR', null, null, '#7ec4cf');
INSERT INTO `document_types` VALUES ('5', 'WORK INSTRUCTION', 'WI', null, null, '#daeaf6');
INSERT INTO `document_types` VALUES ('6', 'TEMPLATE', 'TP', null, null, '#e8dff5');

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('3', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('4', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('5', '2023_07_26_055300_create_audits_table', '1');

-- ----------------------------
-- Table structure for `obsoletes`
-- ----------------------------
DROP TABLE IF EXISTS `obsoletes`;
CREATE TABLE `obsoletes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `control_code` varchar(255) DEFAULT '',
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `category` varchar(255) DEFAULT '',
  `other_category` varchar(255) DEFAULT '',
  `effective_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of obsoletes
-- ----------------------------

-- ----------------------------
-- Table structure for `obsolete_attachments`
-- ----------------------------
DROP TABLE IF EXISTS `obsolete_attachments`;
CREATE TABLE `obsolete_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of obsolete_attachments
-- ----------------------------

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
INSERT INTO `password_resets` VALUES ('cabato.renz.renz@gmail.com', '$2y$10$vuHdKsP9HMPxCsv9B5xltOZmaSwxc/wiepDVwISeS53i4gx/1nJyi', '2023-08-01 22:19:22');

-- ----------------------------
-- Table structure for `permits`
-- ----------------------------
DROP TABLE IF EXISTS `permits`;
CREATE TABLE `permits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `accountable_person` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of permits
-- ----------------------------
INSERT INTO `permits` VALUES ('1', 'Title', 'Sample Only', '1', '4', null, '/permits_attachments/1690885459_bg.jpg', '2024-06-12', '2', '2023-08-01 09:25:06', '2023-08-01 10:36:07', 'License');
INSERT INTO `permits` VALUES ('2', 'Permit', 'Permit', '1', '1', null, '/permits_attachments/1690885441_bg.jpg', '2024-04-24', '2', '2023-08-01 09:53:50', '2023-08-01 10:24:01', 'Permit');
INSERT INTO `permits` VALUES ('3', 'Nearly Expired', 'Expiration', '1', '1', null, '/permits_attachments/1690951898_icon-72x72.png', '2023-08-16', '2', '2023-08-02 04:51:38', '2023-08-02 04:51:38', 'License');
INSERT INTO `permits` VALUES ('4', 'Business Permit', 'for WCC', '1', '1', null, '/permits_attachments/1690978804__$Company Masterlist.xlsx', '2024-06-05', '2', '2023-08-02 12:20:04', '2023-08-02 12:20:04', 'Permit');
INSERT INTO `permits` VALUES ('5', 'bp', 'wfa', '1', '1', null, '/permits_attachments/1691136315_1690978899__$Company Masterlist (1).xlsx', '2024-03-06', '2', '2023-08-02 12:21:10', '2023-08-04 16:05:15', 'License');

-- ----------------------------
-- Table structure for `request_approvers`
-- ----------------------------
DROP TABLE IF EXISTS `request_approvers`;
CREATE TABLE `request_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `change_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `additional` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of request_approvers
-- ----------------------------

-- ----------------------------
-- Table structure for `request_logs`
-- ----------------------------
DROP TABLE IF EXISTS `request_logs`;
CREATE TABLE `request_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `change_request_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of request_logs
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
  `department_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin1', 'admin@admin.com', '0000-00-00 00:00:00', '$2y$10$PbGAH7o7P6JM9V22Erv0NuORq18yRmz58IL7x9YdFKauS6UmBes7W', 'xfr6WFmk8Pp4ghqNqZDlu8ZtVnJKdl76IFCqzFObFCVg396n2A8cvjOJfH3E', '0000-00-00 00:00:00', '2023-08-01 21:08:41', '1', '1', 'Administrator', null);
INSERT INTO `users` VALUES ('2', 'Renz Christian Cabato', 'cabato.renz.renz@gmail.com', null, '$2y$10$N63xmV/1JnPBML7c2trO6.a7QPd5CzfqoDi.LXDQCqXq56C1OC8za', 'TJ5xS30MmILAb8Yp9buLWOBla6AgqYBJVgcjZoewOqBuwxvygT9AIZHStHHO', '2023-07-30 20:27:56', '2023-07-30 20:27:56', '1', '1', 'Administrator', null);
INSERT INTO `users` VALUES ('3', 'Filomena Agnes Cabulong', 'f.cabulong@gmail.com', null, '$2y$10$kpSHGrSFdn4f3F.Qs4d/5u5XV0Y8UaDz4KQGkfq251mbF8M0k4Z82', null, '2023-07-30 20:44:22', '2023-08-01 21:04:16', '1', '1', 'Business Process Manager', null);
INSERT INTO `users` VALUES ('4', 'Department head', 'dep_head@gmail.com', null, '', null, '2023-08-01 20:36:01', '2023-08-01 21:13:03', '1', '1', 'Department Head', null);
INSERT INTO `users` VALUES ('5', 'DCO', 'dco@gmail.com', null, '', null, '2023-08-02 17:31:53', '2023-08-08 13:18:10', '1', '1', 'Document Control Officer', '1');
INSERT INTO `users` VALUES ('6', 'DCO 1', 'dco1@gmail.com', null, '$2y$10$P6t2bxfRaeZd7gZcjaUhH.aCOGmaFLV35klCPf/6s2QIV0XLftSYe', null, '2023-08-02 18:00:45', '2023-08-02 18:00:45', '4', '1', 'Document Control Officer', null);
INSERT INTO `users` VALUES ('7', 'DRC - IT', 'drc@gmail.com', null, '$2y$10$G4GY8YJYXO1EyxLigaMPVeggdclv5/J9oPkfjL1ThITyjuzbEjcn2', null, '2023-08-02 23:55:51', '2023-08-05 04:45:07', '1', '1', 'Documents and Records Controller', null);
INSERT INTO `users` VALUES ('8', 'DRC BPD', 'drcbpd@gmail.com', null, '$2y$10$b3lrg6ZW8Yl5m.mksLkj3esJYaXVZVgdmThFIknhlNkMg85cHHJ4W', null, '2023-08-05 06:48:14', '2023-08-05 06:48:14', '5', '1', 'Documents and Records Controller', null);
