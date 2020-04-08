/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : dms_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-04-07 10:34:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dms_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `dms_auth_assignment`;
CREATE TABLE `dms_auth_assignment` (
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='权限分配表';

-- ----------------------------
-- Records of dms_auth_assignment
-- ----------------------------
INSERT INTO `dms_auth_assignment` VALUES ('1', '1', '1545381330', '1545381330');
INSERT INTO `dms_auth_assignment` VALUES ('2', '2', '1545381330', '1545381330');

-- ----------------------------
-- Table structure for dms_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `dms_auth_role`;
CREATE TABLE `dms_auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色组ID',
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '角色名称',
  `auth_ids` text CHARACTER SET utf8 NOT NULL COMMENT '角色拥有的权限id组，用逗号分开',
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '角色说明文字',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0禁用， 1启用',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='角色表';

-- ----------------------------
-- Records of dms_auth_role
-- ----------------------------
INSERT INTO `dms_auth_role` VALUES ('1', '超级管理员', '1,6,2,27,76,78,157', '   拥有最高权限   ', '1', '1544687899', '1576569558');
INSERT INTO `dms_auth_role` VALUES ('2', '游客', '1,3,6,7,8,27,2', '   权限最小   ', '1', '1545013914', '1545383542');
INSERT INTO `dms_auth_role` VALUES ('5', '管理员', '5,44,45,46,47,48,49,51,52,53,55,54,56,57,58,59,60,61,63,62,64,65,66,67,68,69,70,71,72,73,74,76,75,78,77,79,80,81,82,83,84,86,87,91,92,94,93,89,90,88,95,96,97,98,99,100,101,102,103,105,104,106,114,108,107,113,109,110,112,115,32,117,119,120,124,122,125,126,128,127,129,130,131,132,133,134,135,136,137,138,139,140,141,142,153,154,155,156,143,1,123,157,121,158,159,162', '  拥有除系统管理的所有权限', '1', '1553935069', '1577157276');

-- ----------------------------
-- Table structure for dms_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `dms_auth_rule`;
CREATE TABLE `dms_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '权限类型，0 顶部导航模块，1 侧边菜单项，2 内容页面具体操作项',
  `module` int(11) NOT NULL DEFAULT '1' COMMENT '所属模块id，即type=0的顶部导航模块',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `link` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '请求地址，模块/控制器/具体方法，如 admin/index/index',
  `sort` int(10) NOT NULL DEFAULT '99' COMMENT '排序',
  `icon` varchar(45) COLLATE utf8mb4_bin NOT NULL DEFAULT 'fa-cog' COMMENT '菜单图标',
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '权限说明',
  `title` varchar(45) COLLATE utf8mb4_bin NOT NULL COMMENT '权限标题，如中文标题：用户列表',
  `en_title` varchar(120) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '权限英文标题，菜单显示，翻译成英文',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用, 1启用',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='权限（规则）表';

-- ----------------------------
-- Records of dms_auth_rule
-- ----------------------------
INSERT INTO `dms_auth_rule` VALUES ('1', '0', '0', '0', 'admin/index', '1', '', '首页面是最低权限，公共页面', '首页', '', '1', '0', '0');
INSERT INTO `dms_auth_rule` VALUES ('2', '0', '0', '0', 'admin/admin/index', '99', 'fa-location-arrow', '一般是超级管理员的权限', '后台管理', '', '1', '0', '1545019126');
INSERT INTO `dms_auth_rule` VALUES ('3', '1', '2', '43', 'admin/sys_user/index', '2', 'fa-user', '后台用户新增修改删除', '系统用户', '', '1', '0', '1553919352');
INSERT INTO `dms_auth_rule` VALUES ('5', '1', '1', '0', 'admin/index/home', '1', 'fa-dashboard', '首页面是最低权限，公共页面', '首页', 'Homepage', '1', '0', '1577169575');
INSERT INTO `dms_auth_rule` VALUES ('6', '1', '2', '43', 'admin/auth', '3', 'fa-key', '', '权限管理', '', '1', '0', '1553919339');
INSERT INTO `dms_auth_rule` VALUES ('7', '1', '2', '6', 'admin/auth/index', '1', 'fa-bars', '', '权限菜单', '', '1', '0', '1545617937');
INSERT INTO `dms_auth_rule` VALUES ('8', '1', '2', '6', 'admin/auth/role', '2', 'fa-user-circle', '', '角色管理', '', '1', '0', '1545617955');
INSERT INTO `dms_auth_rule` VALUES ('16', '2', '0', '0', 'admin/auth/edit', '99', 'fa-location-arrow', '', '权限编辑', '', '1', '1544608956', '1544608956');
INSERT INTO `dms_auth_rule` VALUES ('17', '2', '0', '0', 'admin/auth/forbidden', '99', 'fa-location-arrow', '', '权限禁用', '', '1', '1544609077', '1544609077');
INSERT INTO `dms_auth_rule` VALUES ('18', '2', '0', '0', 'admin/auth/delete', '99', 'fa-location-arrow', '', '权限删除', '', '1', '1544609121', '1544667309');
INSERT INTO `dms_auth_rule` VALUES ('20', '2', '0', '0', 'admin/sys_user/edit', '99', 'fa-location-arrow', '', '用户编辑', '', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('22', '2', '0', '0', 'admin/sys_user/forbidden', '99', 'fa-location-arrow', '', '用户禁用', '', '1', '1544688720', '1544688720');
INSERT INTO `dms_auth_rule` VALUES ('23', '2', '0', '0', 'admin/auth/editRole', '99', 'fa-location-arrow', '', '角色编辑', '', '1', '1544756574', '1544756574');
INSERT INTO `dms_auth_rule` VALUES ('24', '2', '0', '0', 'admin/auth/forbiddenRole', '99', 'fa-location-arrow', '', '角色禁用', '', '1', '1544756606', '1544756606');
INSERT INTO `dms_auth_rule` VALUES ('26', '2', '0', '0', 'admin/auth/deleteRole', '99', 'fa-location-arrow', '', '角色删除', '', '1', '1544757075', '1544757075');
INSERT INTO `dms_auth_rule` VALUES ('27', '1', '2', '6', 'admin/auth/authAssignment', '3', 'fa-unlock', '', '权限分配', '', '1', '1544773624', '1545647211');
INSERT INTO `dms_auth_rule` VALUES ('28', '2', '0', '0', 'admin/auth/add', '99', 'fa-location-arrow', '', '权限新增', '', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('29', '2', '0', '0', 'admin/sys_user/add', '99', 'fa-location-arrow', '', '用户新增', '', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('30', '2', '0', '0', 'admin/auth/addRole', '99', 'fa-location-arrow', '', '角色新增', '', '1', '1545388137', '1545388137');
INSERT INTO `dms_auth_rule` VALUES ('31', '2', '0', '0', 'admin/auth/roleAssignment', '99', 'fa-location-arrow', '', '按角色进行分配权限', '', '1', '1545388241', '1545388241');
INSERT INTO `dms_auth_rule` VALUES ('32', '1', '2', '3', 'admin/sys_user/index', '1', 'fa-user-o', '', '用户列表', '', '1', '1545618813', '1545618905');
INSERT INTO `dms_auth_rule` VALUES ('33', '1', '2', '3', 'admin/sys_user/loginlog', '2', 'fa-book', '', '登录日志', '', '1', '1545619009', '1545619009');
INSERT INTO `dms_auth_rule` VALUES ('43', '1', '0', '0', 'admin/system/index', '1', 'fa-location-arrow', '', '系统管理', '', '1', '1553919271', '1553919271');

-- ----------------------------
-- Table structure for dms_sys_op_log
-- ----------------------------
DROP TABLE IF EXISTS `dms_sys_op_log`;
CREATE TABLE `dms_sys_op_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '操作时间',
  `user_id` int(11) NOT NULL COMMENT '后台用户id',
  `op_link` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '操作连接，即具体url, 如admin/auth/add',
  `op_title` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '操作标题，如用户新增',
  `ip` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '访问IP',
  `remark` text COLLATE utf8mb4_bin NOT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台操作日志，记录用户的具体操作，一般是记录会引起数据变化的操作';

-- ----------------------------
-- Records of dms_sys_op_log
-- ----------------------------

-- ----------------------------
-- Table structure for dms_sys_user
-- ----------------------------
DROP TABLE IF EXISTS `dms_sys_user`;
CREATE TABLE `dms_sys_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统用户ID',
  `username` varchar(45) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '用户账号（用户名）',
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '登录密码',
  `portrait` varchar(60) COLLATE utf8mb4_bin NOT NULL COMMENT '头像',
  `phone` varchar(11) COLLATE utf8mb4_bin NOT NULL COMMENT '手机号',
  `email` varchar(45) COLLATE utf8mb4_bin NOT NULL COMMENT '邮箱',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用，1正常',
  `token` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '登录token,每次登录更新',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登入时间 ',
  `desc` varchar(125) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '备注说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台用户表';

-- ----------------------------
-- Records of dms_sys_user
-- ----------------------------
INSERT INTO `dms_sys_user` VALUES ('1', 'admin', '$2y$10$Qt8pogMonPLr6VByKev3jOC1b8LjTwlyvLxRPkqnzruYtA/qOkD2S', 'images/portrait/sysUser1.jpg', '18888888888', '168888@qq.com', '2018-10-08 00:00:00', '2020-04-07 05:32:54', '1', '0bc617d881126e39c1d6230321f1c8689d757e56', '1586226774', '');
INSERT INTO `dms_sys_user` VALUES ('2', 'guest', '$2y$10$Y8ZrflI/peaZ85QrceUJMeW2MCtoA7.SxAMDPcaub56OYO79gsOhi', 'images/portrait/user3.jpg', '', '', '2019-12-21 18:04:07', '2020-04-02 11:12:07', '1', '9579f65263159fc9645eeef259f84ff441456836', '1577168916', '  ');

-- ----------------------------
-- Table structure for dms_sys_user_log
-- ----------------------------
DROP TABLE IF EXISTS `dms_sys_user_log`;
CREATE TABLE `dms_sys_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL COMMENT '后台用户id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '登录类型，1登入，2退出',
  `ip` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '用户的IP地址',
  `create_time` int(11) NOT NULL COMMENT '日志记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台登录日志';

-- ----------------------------
-- Records of dms_sys_user_log
-- ----------------------------
INSERT INTO `dms_sys_user_log` VALUES ('1', '1', '1', '', '1585885555');
INSERT INTO `dms_sys_user_log` VALUES ('2', '1', '1', '', '1586226774');
