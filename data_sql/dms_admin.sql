/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : dms_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-02-12 15:17:19
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='权限分配表';

-- ----------------------------
-- Records of dms_auth_assignment
-- ----------------------------
INSERT INTO `dms_auth_assignment` VALUES ('1', '1', '0', '0');
INSERT INTO `dms_auth_assignment` VALUES ('2', '2', '0', '1545381330');
INSERT INTO `dms_auth_assignment` VALUES ('7', '1', '1545381699', '1545475278');
INSERT INTO `dms_auth_assignment` VALUES ('8', '2', '1545618346', '1545618346');

-- ----------------------------
-- Table structure for dms_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `dms_auth_role`;
CREATE TABLE `dms_auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色组ID',
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '角色名称',
  `auth_ids` varchar(360) CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '角色拥有的权限id组，用逗号分开',
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '角色说明文字',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0禁用， 1启用',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='角色表';

-- ----------------------------
-- Records of dms_auth_role
-- ----------------------------
INSERT INTO `dms_auth_role` VALUES ('1', '超级管理员', '1,6,2,27', '   拥有最高权限   ', '1', '1544687899', '1545388172');
INSERT INTO `dms_auth_role` VALUES ('2', '游客', '1,3,6,7,8,27,2', '   权限最小   ', '1', '1545013914', '1545383542');
INSERT INTO `dms_auth_role` VALUES ('4', '参观者', '1', '  体验参观', '1', '1545618466', '1545618466');

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
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用, 1启用',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='权限（规则）表';

-- ----------------------------
-- Records of dms_auth_rule
-- ----------------------------
INSERT INTO `dms_auth_rule` VALUES ('1', '0', '0', '0', 'admin/index', '1', '', '首页面是最低权限，公共页面', '首页', '1', '0', '0');
INSERT INTO `dms_auth_rule` VALUES ('2', '0', '0', '0', 'admin/admin/index', '99', 'fa-location-arrow', '一般是超级管理员的权限', '后台管理', '1', '0', '1545019126');
INSERT INTO `dms_auth_rule` VALUES ('3', '1', '2', '0', 'admin/sys_user/index', '2', 'fa-user', '后台用户新增修改删除', '用户管理', '1', '0', '1545617359');
INSERT INTO `dms_auth_rule` VALUES ('24', '2', '0', '0', 'admin/auth/forbiddenRole', '99', 'fa-location-arrow', '', '角色禁用', '1', '1544756606', '1544756606');
INSERT INTO `dms_auth_rule` VALUES ('5', '1', '1', '0', 'admin/index/home', '1', 'fa-dashboard', '首页面是最低权限，公共页面', '首页', '1', '0', '0');
INSERT INTO `dms_auth_rule` VALUES ('6', '1', '2', '0', 'admin/auth', '3', 'fa-key', '', '权限管理', '1', '0', '1545617661');
INSERT INTO `dms_auth_rule` VALUES ('7', '1', '2', '6', 'admin/auth/index', '1', 'fa-bars', '', '权限菜单', '1', '0', '1545617937');
INSERT INTO `dms_auth_rule` VALUES ('8', '1', '2', '6', 'admin/auth/role', '2', 'fa-user-circle', '', '角色管理', '1', '0', '1545617955');
INSERT INTO `dms_auth_rule` VALUES ('23', '2', '0', '0', 'admin/auth/editRole', '99', 'fa-location-arrow', '', '角色编辑', '1', '1544756574', '1544756574');
INSERT INTO `dms_auth_rule` VALUES ('16', '2', '0', '0', 'admin/auth/edit', '99', 'fa-location-arrow', '', '权限编辑', '1', '1544608956', '1544608956');
INSERT INTO `dms_auth_rule` VALUES ('17', '2', '0', '0', 'admin/auth/forbidden', '99', 'fa-location-arrow', '', '权限禁用', '1', '1544609077', '1544609077');
INSERT INTO `dms_auth_rule` VALUES ('18', '2', '0', '0', 'admin/auth/delete', '99', 'fa-location-arrow', '', '权限删除', '1', '1544609121', '1544667309');
INSERT INTO `dms_auth_rule` VALUES ('20', '2', '0', '0', 'admin/sys_user/edit', '99', 'fa-location-arrow', '', '用户编辑', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('22', '2', '0', '0', 'admin/sys_user/forbidden', '99', 'fa-location-arrow', '', '用户禁用', '1', '1544688720', '1544688720');
INSERT INTO `dms_auth_rule` VALUES ('26', '2', '0', '0', 'admin/auth/deleteRole', '99', 'fa-location-arrow', '', '角色删除', '1', '1544757075', '1544757075');
INSERT INTO `dms_auth_rule` VALUES ('27', '1', '2', '6', 'admin/auth/authAssignment', '3', 'fa-unlock', '', '权限分配', '1', '1544773624', '1545647211');
INSERT INTO `dms_auth_rule` VALUES ('28', '2', '0', '0', 'admin/auth/add', '99', 'fa-location-arrow', '', '权限新增', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('29', '2', '0', '0', 'admin/sys_user/add', '99', 'fa-location-arrow', '', '用户新增', '1', '1544687899', '1544687899');
INSERT INTO `dms_auth_rule` VALUES ('30', '2', '0', '0', 'admin/auth/addRole', '99', 'fa-location-arrow', '', '角色新增', '1', '1545388137', '1545388137');
INSERT INTO `dms_auth_rule` VALUES ('31', '2', '0', '0', 'admin/auth/roleAssignment', '99', 'fa-location-arrow', '', '按角色进行分配权限', '1', '1545388241', '1545388241');
INSERT INTO `dms_auth_rule` VALUES ('32', '1', '2', '3', 'admin/sys_user/index', '1', 'fa-user-o', '', '用户列表', '1', '1545618813', '1545618905');
INSERT INTO `dms_auth_rule` VALUES ('33', '1', '2', '3', 'admin/sys_user/loginlog', '2', 'fa-book', '', '登录日志', '1', '1545619009', '1545619009');
INSERT INTO `dms_auth_rule` VALUES ('34', '0', '0', '0', 'visitor', '99', 'fa-location-arrow', '', '玩不坏数据', '1', '1545635542', '1545635542');
INSERT INTO `dms_auth_rule` VALUES ('35', '1', '34', '0', 'visitor/fere/index', '1', 'fa-user-plus', '', '伴侣工厂', '1', '1545639089', '1545639089');
INSERT INTO `dms_auth_rule` VALUES ('36', '1', '34', '35', 'visitor/fere/index', '1', 'fa-female', '', '成品管理', '1', '1545639241', '1545639241');
INSERT INTO `dms_auth_rule` VALUES ('37', '1', '34', '35', 'visitor/fere/proTotal', '2', 'fa-database', '', '生产统计', '1', '1545639512', '1545639512');
INSERT INTO `dms_auth_rule` VALUES ('38', '1', '34', '35', 'visitor/fere/maiTotal', '3', 'fa-deafness', '', '维修统计', '1', '1545639715', '1545639715');
INSERT INTO `dms_auth_rule` VALUES ('39', '2', '34', '0', 'visitor/fere/add', '99', 'fa-location-arrow', '', '新增产品', '1', '1545647274', '1545647274');
INSERT INTO `dms_auth_rule` VALUES ('40', '2', '34', '0', 'visitor/fere/edit', '99', 'fa-location-arrow', '', '成品维修', '1', '1545649785', '1545704346');
INSERT INTO `dms_auth_rule` VALUES ('41', '2', '34', '0', 'visitor/fere/forbidden', '99', 'fa-location-arrow', '', '产品状态', '1', '1545649855', '1545649855');
INSERT INTO `dms_auth_rule` VALUES ('42', '1', '34', '35', 'visitor/fere/charts', '4', 'fa-line-chart', '', '成品分析图', '1', '1545878480', '1545878480');

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
  `remark` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台操作日志，记录用户的具体操作，一般是记录会引起数据变化的操作';

-- ----------------------------
-- Records of dms_sys_op_log
-- ----------------------------
INSERT INTO `dms_sys_op_log` VALUES ('1', '1', 'admin/sys_user/edit', '用户编辑', '', '', '1545474178');
INSERT INTO `dms_sys_op_log` VALUES ('2', '1', 'admin/sys_user/edit', '用户编辑', '', '', '1545474184');
INSERT INTO `dms_sys_op_log` VALUES ('3', '1', 'admin/sys_user/edit', '用户编辑', '', '', '1545474294');
INSERT INTO `dms_sys_op_log` VALUES ('4', '1', 'admin/sys_user/edit', '用户编辑', '', '', '1545474300');
INSERT INTO `dms_sys_op_log` VALUES ('5', '1', 'admin/sys_user/edit', '用户编辑', '', '编辑用户ID: 7', '1545475278');
INSERT INTO `dms_sys_op_log` VALUES ('6', '1', 'admin/sys_user/add', '用户新增', '', '新增用户ID: 8', '1545618346');

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台用户表';

-- ----------------------------
-- Records of dms_sys_user
-- ----------------------------
INSERT INTO `dms_sys_user` VALUES ('1', 'dmsadmin', '$2y$10$2iB0j6OXITVccqbAvl3MS.9REuOONq13AtZmi/Pc6Yd8xVS1rP9MG', 'images/portrait/sysUser1.jpg', '18888888888', '168888@qq.com', '2018-10-08 00:00:00', '2019-02-12 15:01:20', '1', '70d5c2b16cb1248f2da9284e9db65394c1599e2b', '1549954880');
INSERT INTO `dms_sys_user` VALUES ('2', 'guest', '$2y$10$.lSVMK8.bBiXlBGNlRhGAeA5lnPPVHDOeV7tn1VizD9aGSZBmfdHu', '', '', '', '2018-10-08 00:00:00', '2018-12-21 12:08:50', '1', '254f9c32f3c1bafc0089e1d1d102ee0451bc8be9', '1');
INSERT INTO `dms_sys_user` VALUES ('7', 'test', '$2y$10$GD6wZaCNU8L2LpEOs2X4puZ2FfEaT0xIoC.dyyjbbzCPxq.Q/BHl2', '', '', '', '2018-12-21 16:41:39', '2018-12-22 18:25:00', '1', '', '0');
INSERT INTO `dms_sys_user` VALUES ('8', 'visitor', '123456', '', '', '', '2018-12-24 10:25:46', '2018-12-24 10:25:46', '1', '', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='后台登录日志';

-- ----------------------------
-- Records of dms_sys_user_log
-- ----------------------------
INSERT INTO `dms_sys_user_log` VALUES ('1', '1', '2', '', '1545446210');
INSERT INTO `dms_sys_user_log` VALUES ('2', '1', '1', '', '1545446212');
INSERT INTO `dms_sys_user_log` VALUES ('3', '2', '2', '', '1545451303');
INSERT INTO `dms_sys_user_log` VALUES ('4', '1', '1', '', '1545451312');
INSERT INTO `dms_sys_user_log` VALUES ('5', '1', '2', '', '1545451326');
INSERT INTO `dms_sys_user_log` VALUES ('6', '1', '1', '', '1545451337');
INSERT INTO `dms_sys_user_log` VALUES ('7', '1', '2', '', '1545451387');
INSERT INTO `dms_sys_user_log` VALUES ('8', '1', '1', '', '1545451408');
INSERT INTO `dms_sys_user_log` VALUES ('9', '1', '2', '', '1545460168');
INSERT INTO `dms_sys_user_log` VALUES ('10', '1', '1', '', '1545460180');
INSERT INTO `dms_sys_user_log` VALUES ('11', '1', '2', '', '1545460363');
INSERT INTO `dms_sys_user_log` VALUES ('12', '1', '1', '', '1545460377');
INSERT INTO `dms_sys_user_log` VALUES ('13', '1', '1', '', '1545461165');
INSERT INTO `dms_sys_user_log` VALUES ('14', '1', '1', '', '1545461678');
INSERT INTO `dms_sys_user_log` VALUES ('15', '1', '1', '', '1545461722');
INSERT INTO `dms_sys_user_log` VALUES ('16', '1', '1', '', '1545461833');
INSERT INTO `dms_sys_user_log` VALUES ('17', '1', '1', '', '1545462404');
INSERT INTO `dms_sys_user_log` VALUES ('18', '1', '1', '', '1545462438');
INSERT INTO `dms_sys_user_log` VALUES ('19', '1', '1', '', '1545462836');
INSERT INTO `dms_sys_user_log` VALUES ('20', '1', '1', '', '1545463227');
INSERT INTO `dms_sys_user_log` VALUES ('21', '1', '1', '', '1545463251');
INSERT INTO `dms_sys_user_log` VALUES ('22', '1', '1', '', '1545463349');
INSERT INTO `dms_sys_user_log` VALUES ('23', '1', '1', '', '1545463511');
INSERT INTO `dms_sys_user_log` VALUES ('24', '1', '1', '', '1545463987');
INSERT INTO `dms_sys_user_log` VALUES ('25', '1', '1', '', '1545464084');
INSERT INTO `dms_sys_user_log` VALUES ('26', '1', '1', '', '1545464105');
INSERT INTO `dms_sys_user_log` VALUES ('27', '1', '1', '', '1545464196');
INSERT INTO `dms_sys_user_log` VALUES ('28', '1', '1', '', '1545464301');
INSERT INTO `dms_sys_user_log` VALUES ('29', '1', '1', '', '1545464485');
INSERT INTO `dms_sys_user_log` VALUES ('30', '1', '1', '', '1545464708');
INSERT INTO `dms_sys_user_log` VALUES ('31', '1', '1', '', '1545464920');
INSERT INTO `dms_sys_user_log` VALUES ('32', '1', '1', '', '1545464974');
INSERT INTO `dms_sys_user_log` VALUES ('33', '1', '1', '', '1545465253');
INSERT INTO `dms_sys_user_log` VALUES ('34', '1', '1', '', '1545474167');
INSERT INTO `dms_sys_user_log` VALUES ('35', '1', '1', '', '1545616530');
INSERT INTO `dms_sys_user_log` VALUES ('36', '1', '1', '', '1546487543');
INSERT INTO `dms_sys_user_log` VALUES ('37', '1', '1', '127.0.0.1', '1549954880');

-- ----------------------------
-- Table structure for dms_visitor_fere
-- ----------------------------
DROP TABLE IF EXISTS `dms_visitor_fere`;
CREATE TABLE `dms_visitor_fere` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_bin NOT NULL COMMENT '名字',
  `nick` varchar(45) COLLATE utf8mb4_bin NOT NULL COMMENT '昵称',
  `sex` tinyint(1) NOT NULL COMMENT '性别，1男，0女，2男女通吃',
  `material` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '材料',
  `style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '动力类型，1充气，2电动',
  `status` tinyint(1) NOT NULL COMMENT '状态，0关闭，1启动',
  `create_time` int(11) NOT NULL COMMENT '出厂日期',
  `update_time` int(11) NOT NULL COMMENT '维修日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='体验数据';

-- ----------------------------
-- Records of dms_visitor_fere
-- ----------------------------
INSERT INTO `dms_visitor_fere` VALUES ('1', '尼姑拉丝', '哦 My 尬', '0', '硅胶', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('2', '妈妈咪呀', '哦 My 尬', '0', '塑料', '1', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('3', '大老粗', '哦 My 尬', '1', '硅胶', '1', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('4', '粗老大', '哦 My 尬', '1', '铁皮', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('5', '老大粗', '哦 My 尬', '2', '木头', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('6', '韩妹妹', '哦 My 尬', '0', '硅胶', '1', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('7', '钨钢侠', '哦 My 尬', '2', '特殊材料', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('8', '尼古拉斯', '哦 My 尬', '1', '塑料', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('9', '拖拉司机', '哦 My 尬', '2', '硅胶', '2', '1', '1545649672', '1545705550');
INSERT INTO `dms_visitor_fere` VALUES ('10', '拉司机拖', '哦 My 尬', '0', '硅胶', '1', '1', '1545649672', '1545705550');
