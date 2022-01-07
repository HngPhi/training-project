/*
 Navicat Premium Data Transfer

 Source Server         : demo
 Source Server Type    : MySQL
 Source Server Version : 100422
 Source Host           : localhost:3306
 Source Schema         : basephp

 Target Server Type    : MySQL
 Target Server Version : 100422
 File Encoding         : 65001

 Date: 07/01/2022 15:24:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_type` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '1/ Super admin, \r\n2/ Admin',
  `ins_id` int NOT NULL,
  `upd_id` int NULL DEFAULT NULL,
  `ins_datetime` datetime(0) NOT NULL,
  `upd_datetime` datetime(0) NULL DEFAULT NULL,
  `del_flag` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '0/ Active, \r\n1/ Deleted',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'Admin', '105dad91250a07b716d6a714a3e676b8', 'admin@gmail.com', 'admin1.png', '1', 0, 2, '2021-12-24 14:04:03', '2022-01-07 15:07:40', '0');
INSERT INTO `admin` VALUES (2, 'SuperAdmin', '67482c6c117a6d3234c681f7fce2e482', 'superadmin@gmail.com', 'superadmin.png', '2', 0, 2, '2021-12-24 15:04:42', '2022-01-07 14:11:15', '0');
INSERT INTO `admin` VALUES (3, 'ABC', 'e99a18c428cb38d5f260853678922e03', 'abc@gmail.com', 'admin2.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (4, 'CDE', '6fa0635a15ddd25ca603cde37f5575aa', 'cde@gmail.com', 'admin3.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (5, 'EFG', 'c4a84c25dc1ff4a3ea8990f4f05657e9', 'efg@gmail.com', 'admin4.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (6, 'GHI', '118e0bfeafaae5544f3f486533cf56db', 'ghi@gmail.com', 'admin5.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (7, 'IJK', 'f9c2bbb07b83e5fa90a3cde38bedcbe2', 'ijk@gmail.com', 'admin6.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (8, 'KLM', '53a7273833bccff8a8430f7b0d1297d8', 'klm@gmail.com', 'admin7.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (9, 'hungphi', '879f21bc39a50b31b2a69ebed07fa1cf', 'hungphi@gmail.com', 'admin8.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (10, 'Vũ Danh Hùng Phi', '767592156ce03838f0b09789d89d3d64', 'vudanhhungphi021@gmail.com', 'admin9.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (11, 'Hùng Phi', '8f1f9c62dc9cf6633d6a09884037c4fa', 'hungphi1010@gmail.com', 'admin9.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (12, 'VDHP', '718f8f67dae0a6355a0ccf27c451df79', 'vdhp@gmail.com', 'admin10.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (13, 'Hngpi', '7407fc9b7a6b0e9221148e10e5afc9ef', 'hp@gmail.com', 'admin11.png', '1', 2, 2, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (14, 'John', '6e0b7076126a29d5dfcbd54835387b7b', 'john@gmail.com', 'admin1.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `facebook_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '1/ Active, 2/ Banned',
  `ins_id` int NOT NULL,
  `upd_id` int NULL DEFAULT NULL,
  `ins_datetime` datetime(0) NOT NULL,
  `upd_datetime` datetime(0) NULL DEFAULT NULL,
  `del_flag` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '0/ Active, 1/ Deleted',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'User1', '1', 'user1@gmail.com', '16721ca310944f51ce47751ab30b726b', 'admin11.png', '1', 0, 2, '2021-12-24 13:58:49', '2022-01-07 04:06:13', '0');
INSERT INTO `user` VALUES (2, 'User2', '2', 'user2@gmail.com', 'c882c86ad5ca2760ea561b929e4b25d5', 'admin1.png', '1', 0, 2, '2021-12-24 14:00:27', '2022-01-07 03:21:59', '0');
INSERT INTO `user` VALUES (3, 'User3', '', 'user3@gmail.com', '5343c678cb1a5147b532fc598f49ff13', 'admin7.png', '1', 2, 2, '0000-00-00 00:00:00', '2022-01-07 03:23:50', '0');
INSERT INTO `user` VALUES (4, 'User4', '', 'user4@gmail.com', 'f029259da141c1cf391b921d3af57f95', 'admin6.png', '1', 2, NULL, '2022-01-07 03:32:12', NULL, '0');

SET FOREIGN_KEY_CHECKS = 1;
