/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 100116
Source Host           : localhost:3306
Source Database       : karmel

Target Server Type    : MYSQL
Target Server Version : 100116
File Encoding         : 65001

Date: 2017-06-15 14:30:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ks_solve
-- ----------------------------
DROP TABLE IF EXISTS `ks_solve`;
CREATE TABLE `ks_solve` (
  `a_index` int(10) NOT NULL AUTO_INCREMENT,
  `a_reqkey` varchar(10) DEFAULT NULL,
  `a_answer` varchar(500) DEFAULT NULL,
  `a_requesttime` int(10) DEFAULT '0',
  `a_solvetime` int(10) DEFAULT '0',
  `a_googlekey` varchar(99) DEFAULT NULL,
  `a_api` varchar(40) DEFAULT NULL,
  `a_ref` varchar(99) DEFAULT NULL,
  PRIMARY KEY (`a_index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ks_solve
-- ----------------------------

-- ----------------------------
-- Table structure for ks_users
-- ----------------------------
DROP TABLE IF EXISTS `ks_users`;
CREATE TABLE `ks_users` (
  `a_index` int(1) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) DEFAULT NULL,
  `a_pass` varchar(50) DEFAULT NULL,
  `a_ip` varchar(15) DEFAULT NULL,
  `a_createdate` datetime DEFAULT NULL,
  `a_api` varchar(50) DEFAULT NULL,
  `a_credits` int(11) DEFAULT NULL,
  PRIMARY KEY (`a_index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ks_users
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
