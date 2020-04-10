/*
 Navicat Premium Data Transfer

 Source Server         : phpstudy
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : cmsos

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 14/02/2020 17:29:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `tb_system_admin`;
CREATE TABLE `tb_system_admin`  (
  `id` smallint(5) NOT NULL AUTO_INCREMENT COMMENT '后台管理员表ID',
  `account` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '后台管理员账号',
  `pwd` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '后台管理员密码',
  `real_name` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '后台管理员姓名',
  `roles` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '后台管理员权限(menus_id)',
  `last_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '后台管理员最后一次登录的ip',
  `last_time` int(10) NULL DEFAULT NULL COMMENT '后台管理员最后一次登录时间',
  `add_time` int(10) NOT NULL COMMENT '后台管理员添加时间',
  `login_count` int(10) NOT NULL DEFAULT 0 COMMENT '登录次数',
  `level` tinyint(3) NOT NULL DEFAULT 1 COMMENT '后台管理员级别：1为高级，2为普通',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '后台管理员状态： 1有效 ，0无效',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '无',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_system_admin
-- ----------------------------
INSERT INTO `tb_system_admin` VALUES (22, 'test1', '123456', 'wzm', '2', NULL, 1581568277, 1581567666, 1, 2, 1, 0);
INSERT INTO `tb_system_admin` VALUES (8, 'admin', '123456', '吴志明', '1', NULL, 1581672493, 1580818444, 19, 1, 1, 0);

-- ----------------------------
-- Table structure for tb_system_admin_groups
-- ----------------------------
DROP TABLE IF EXISTS `tb_system_admin_groups`;
CREATE TABLE `tb_system_admin_groups`  (
  `gid` int(4) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `rights` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '角色权限,json',
  PRIMARY KEY (`gid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_system_admin_groups
-- ----------------------------
INSERT INTO `tb_system_admin_groups` VALUES (1, '系统管理员', '[1,4,14,15,3]');
INSERT INTO `tb_system_admin_groups` VALUES (2, '开发人员', '[2,15]');

-- ----------------------------
-- Table structure for tb_system_admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `tb_system_admin_menus`;
CREATE TABLE `tb_system_admin_menus`  (
  `mid` int(10) NOT NULL AUTO_INCREMENT COMMENT '菜单主键',
  `pid` int(10) NOT NULL DEFAULT 0 COMMENT '子菜单键',
  `ord` int(10) NOT NULL DEFAULT 0 COMMENT '排序',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单名称',
  `controller` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单操作器',
  `method` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单方法',
  `ishidden` tinyint(1) NOT NULL DEFAULT 1 COMMENT '菜单是否隐藏：1正常，0隐藏',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '菜单状态：1正常，0禁用',
  PRIMARY KEY (`mid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_system_admin_menus
-- ----------------------------
INSERT INTO `tb_system_admin_menus` VALUES (1, 0, 0, '管理员管理', '', '', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (2, 0, 0, '权限管理', '', '', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (3, 0, 0, '系统设置', '', '', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (4, 1, 0, '管理员列表', 'AdminManage', 'index', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (8, 0, 0, '文章管理', '', '', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (10, 8, 0, '文章列表', 'Article', 'index', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (14, 2, 0, '菜单管理', 'menu', 'index', 1, 1);
INSERT INTO `tb_system_admin_menus` VALUES (15, 2, 0, '角色管理', 'roles', 'index', 1, 1);

SET FOREIGN_KEY_CHECKS = 1;
