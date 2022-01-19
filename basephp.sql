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

 Date: 18/01/2022 23:35:38
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
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'Admin', '0192023a7bbd73250516f069df18b500', 'admin@gmail.com', 'admin1.png', '1', 0, 2, '2021-12-24 14:04:03', '2022-01-18 13:38:23', '1');
INSERT INTO `admin` VALUES (2, 'Superadmin', 'ac497cfaba23c4184cb03b97e8c51e0a', 'superadmin@gmail.com', 'superadmin.png', '2', 0, 2, '2021-12-24 15:04:42', '2022-01-18 13:38:39', '0');
INSERT INTO `admin` VALUES (3, 'ABC', '5f1970f5bf52909ee77cd8f8c50bdd9f', 'abc@gmail.com', 'admin2.png', '1', 2, 2, '0000-00-00 00:00:00', '2022-01-18 23:19:40', '0');
INSERT INTO `admin` VALUES (4, 'CDE', '6fa0635a15ddd25ca603cde37f5575aa', 'cde@gmail.com', 'admin3.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (5, 'EFG', 'c4a84c25dc1ff4a3ea8990f4f05657e9', 'efg@gmail.com', 'admin4.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (6, 'GHI', '118e0bfeafaae5544f3f486533cf56db', 'ghi@gmail.com', 'admin5.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (7, 'IJK', 'f9c2bbb07b83e5fa90a3cde38bedcbe2', 'ijk@gmail.com', 'admin6.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (8, 'KLM', '53a7273833bccff8a8430f7b0d1297d8', 'klm@gmail.com', 'admin7.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (9, 'Hung Phi', '879f21bc39a50b31b2a69ebed07fa1cf', 'hungphi@gmail.com', 'admin8.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (10, 'Vu Danh Hung Phi', '7dd771634eafd458387057c3e0e54980', 'vudanhhungphi021@gmail.com', 'admin9.png', '1', 2, 2, '0000-00-00 00:00:00', '2022-01-18 16:15:43', '0');
INSERT INTO `admin` VALUES (11, 'Hung Phi 1010', '8f1f9c62dc9cf6633d6a09884037c4fa', 'hungphi1010@gmail.com', 'admin10.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (12, 'VDHP', '718f8f67dae0a6355a0ccf27c451df79', 'vdhp@gmail.com', 'admin11.png', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (13, 'HP', '7407fc9b7a6b0e9221148e10e5afc9ef', 'hp@gmail.com', '14.jpg', '1', 2, 2, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (14, 'John', '6e0b7076126a29d5dfcbd54835387b7b', 'john@gmail.com', '15.jpg', '1', 2, NULL, '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `admin` VALUES (32, 'ABC123', '827ccb0eea8a706c4c34a16891f84e7b', 'abc123@gmail.com', 'istockphoto-1279460648-170667a.jpg', '2', 2, 2, '2022-01-18 14:29:43', '2022-01-18 14:30:29', '0');
INSERT INTO `admin` VALUES (33, 'superSL', '418718ec880824fe79f1ce6a99f561ed', 'super@yopmail.com', 'img24.jpg', '2', 2, 2, '2022-01-18 16:47:17', '2022-01-18 17:16:17', '0');
INSERT INTO `admin` VALUES (34, 'test', '25d55ad283aa400af464c76d713c07ad', 'admin@test.com', 'admin2.png', '1', 2, NULL, '2022-01-18 16:52:14', NULL, '0');
INSERT INTO `admin` VALUES (35, 'testSL', '25d55ad283aa400af464c76d713c07ad', 'admin@gmail.com', 'admin2.png', '1', 2, NULL, '2022-01-18 17:05:26', NULL, '0');
INSERT INTO `admin` VALUES (42, '123wdad1', '827ccb0eea8a706c4c34a16891f84e7b', '123wdad1@gmail.com', 'superadmin.png', '1', 2, NULL, '2022-01-18 23:34:00', NULL, '0');

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
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'User1', '', 'user1@gmail.com', '16721ca310944f51ce47751ab30b726b', 'admin11.png', '1', 0, 1, '2021-12-24 13:58:49', '2022-01-18 13:42:37', '1');
INSERT INTO `user` VALUES (2, 'User2', '', 'user2@gmail.com', 'c882c86ad5ca2760ea561b929e4b25d5', 'admin1.png', '1', 0, 2, '2021-12-24 14:00:27', '2022-01-07 03:21:59', '1');
INSERT INTO `user` VALUES (3, 'User3', '', 'user3@gmail.com', '5343c678cb1a5147b532fc598f49ff13', 'admin7.png', '1', 2, 2, '0000-00-00 00:00:00', '2022-01-07 03:23:50', '1');
INSERT INTO `user` VALUES (4, 'User4', '', 'user4@gmail.com', '79cd9963829b5336bff35f88076b0339', 'admin6.png', '1', 2, 2, '2022-01-07 03:32:12', '2022-01-18 13:33:29', '1');
INSERT INTO `user` VALUES (26, 'User5', '107281121843740', 'user5@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', 'fb-profilepic-107281121843740.jpg', '1', 0, 2, '2022-01-12 16:39:40', '2022-01-18 13:33:56', '1');
INSERT INTO `user` VALUES (27, 'Hùng Phi', '1652502878424485', 'vudanhhungphi021@gmail.com', '', 'fb-profilepic-1652502878424485.jpg', '1', 0, NULL, '2022-01-12 16:40:56', NULL, '1');
INSERT INTO `user` VALUES (31, 'Demo', '', 'demo@gmail.com', '14e1b600b1fd579f47433b88e8d85291', 'istockphoto-1279460648-170667a.jpg', '1', 2, 2, '2022-01-18 14:32:02', '2022-01-18 14:34:10', '1');
INSERT INTO `user` VALUES (32, 'Demo1', '', 'demo1@gmail.com', '1f32aa4c9a1d2ea010adcf2348166a04', 'superadmin.png', '2', 2, 2, '2022-01-18 14:35:11', '2022-01-18 14:35:59', '1');
INSERT INTO `user` VALUES (33, 'User6', '', 'user6@gmail.com', '14e1b600b1fd579f47433b88e8d85291', 'user3.jpg', '2', 1, 1, '2022-01-18 14:37:39', '2022-01-18 14:39:03', '1');
INSERT INTO `user` VALUES (34, 'User7', '', 'user7@gmail.com', '1f32aa4c9a1d2ea010adcf2348166a04', '2.jpg', '1', 1, 2, '2022-01-18 14:38:06', '2022-01-18 15:13:22', '1');

SET FOREIGN_KEY_CHECKS = 1;
