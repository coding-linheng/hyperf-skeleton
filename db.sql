/*
Navicat MySQL Data Transfer

Source Server         : 119.23.59.3
Source Server Version : 80026
Source Host           : 119.23.59.3:23306
Source Database       : dc10000

Target Server Type    : MYSQL
Target Server Version : 80026
File Encoding         : 65001

Date: 2021-10-24 15:03:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dczg_action_log
-- ----------------------------
DROP TABLE IF EXISTS `dczg_action_log`;
CREATE TABLE `dczg_action_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `member_id` int unsigned NOT NULL DEFAULT '0' COMMENT '执行会员id',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` char(30) NOT NULL DEFAULT '' COMMENT '执行行为者ip',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '行为名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '执行的URL',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3420 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='行为日志表';

-- ----------------------------
-- Table structure for dczg_addon
-- ----------------------------
DROP TABLE IF EXISTS `dczg_addon`;
CREATE TABLE `dczg_addon` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text NOT NULL COMMENT '配置',
  `author` varchar(40) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='插件表';

-- ----------------------------
-- Table structure for dczg_adminmoney
-- ----------------------------
DROP TABLE IF EXISTS `dczg_adminmoney`;
CREATE TABLE `dczg_adminmoney` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `score` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '共享分',
  `dc` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '地产币',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb3 COMMENT='管理员操作账户余额';

-- ----------------------------
-- Table structure for dczg_adminvip
-- ----------------------------
DROP TABLE IF EXISTS `dczg_adminvip`;
CREATE TABLE `dczg_adminvip` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1灵感；2文库',
  `endtime` int NOT NULL DEFAULT '0' COMMENT '到期时间',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb3 COMMENT='管理员操作流水';

-- ----------------------------
-- Table structure for dczg_advertisement
-- ----------------------------
DROP TABLE IF EXISTS `dczg_advertisement`;
CREATE TABLE `dczg_advertisement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL,
  `img` int NOT NULL DEFAULT '0',
  `url` varchar(1020) DEFAULT NULL COMMENT '链接',
  `time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COMMENT='广告位管理';

-- ----------------------------
-- Table structure for dczg_advertising
-- ----------------------------
DROP TABLE IF EXISTS `dczg_advertising`;
CREATE TABLE `dczg_advertising` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT NULL COMMENT '标题',
  `img` int NOT NULL DEFAULT '0' COMMENT '图片ID',
  `url` varchar(2000) DEFAULT NULL COMMENT '链接',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8mb3 COMMENT='广告位';

-- ----------------------------
-- Table structure for dczg_alabel
-- ----------------------------
DROP TABLE IF EXISTS `dczg_alabel`;
CREATE TABLE `dczg_alabel` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `aid` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7407 DEFAULT CHARSET=utf8mb3 COMMENT='专辑标签表';

-- ----------------------------
-- Table structure for dczg_album
-- ----------------------------
DROP TABLE IF EXISTS `dczg_album`;
CREATE TABLE `dczg_album` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unnum` bigint unsigned NOT NULL DEFAULT '0' COMMENT '唯一编号',
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '专辑名称',
  `fenlei` int NOT NULL DEFAULT '0' COMMENT '专辑分类',
  `brandscenes` int NOT NULL DEFAULT '0' COMMENT '品牌行业分类',
  `brandname` int NOT NULL DEFAULT '0' COMMENT '品牌名称分类',
  `branduse` int NOT NULL DEFAULT '0' COMMENT '品牌用途分类',
  `paintcountry` int NOT NULL DEFAULT '0' COMMENT '绘画国家分类',
  `paintname` int NOT NULL DEFAULT '0' COMMENT '绘画名字分类',
  `paintstyle` int NOT NULL DEFAULT '0' COMMENT '绘画风格分类',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1未传图片；2已传图片',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '传图的张数',
  `baocun` int unsigned NOT NULL DEFAULT '0' COMMENT '保存数',
  `guanzhu` int unsigned NOT NULL DEFAULT '0' COMMENT '关注数量',
  `jing` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未设置；2已设置',
  `looknum` int unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `fid` int unsigned NOT NULL DEFAULT '0' COMMENT '封面ID',
  `fengmian` varchar(300) DEFAULT NULL COMMENT '封面',
  `tui` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '2首页推荐',
  `tuitime` int NOT NULL DEFAULT '0' COMMENT '精品推荐时间',
  `yesterday` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '昨天热门',
  `ltui` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '2灵感推荐',
  `isoriginal` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否原创 1否2是',
  `isopensale` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否开放售卖1不开放2开放',
  `t_time` int NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `week` int unsigned NOT NULL DEFAULT '0' COMMENT '那一周',
  `weekguanzhu` int unsigned NOT NULL DEFAULT '0' COMMENT '第几周的关注量',
  `daynum` int unsigned NOT NULL DEFAULT '0' COMMENT '今日关注量',
  `daytime` int unsigned NOT NULL DEFAULT '0' COMMENT '哪一天',
  `del` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1正常；2删除',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  `yid` int unsigned NOT NULL DEFAULT '0' COMMENT '原ID',
  `g_time` int unsigned NOT NULL DEFAULT '0' COMMENT '关注时间',
  `preview_imgs` varchar(1000) DEFAULT NULL COMMENT '四个小预览图的json串，存放图片地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unnum` (`unnum`),
  KEY `status` (`status`),
  KEY `del` (`del`),
  KEY `fenlei` (`fenlei`),
  KEY `isoriginal` (`isoriginal`)
) ENGINE=InnoDB AUTO_INCREMENT=44871 DEFAULT CHARSET=utf8mb3 COMMENT='专辑表';

-- ----------------------------
-- Table structure for dczg_album_collect
-- ----------------------------
DROP TABLE IF EXISTS `dczg_album_collect`;
CREATE TABLE `dczg_album_collect` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `img_id` bigint DEFAULT '0' COMMENT '灵感图片Id',
  `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url',
  `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id',
  `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id',
  `type` int NOT NULL DEFAULT '0' COMMENT '默认0是图片，1是专辑',
  `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `img_id` (`img_id`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44871 DEFAULT CHARSET=utf8mb3 COMMENT='收藏表';

-- ----------------------------
-- Table structure for dczg_albumlist
-- ----------------------------
DROP TABLE IF EXISTS `dczg_albumlist`;
CREATE TABLE `dczg_albumlist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `aid` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `scid` int unsigned NOT NULL DEFAULT '0' COMMENT '原创图片素材ID',
  `path` varchar(300) DEFAULT NULL COMMENT '路径',
  `suffix` varchar(20) DEFAULT NULL COMMENT '文件后缀名',
  `size` int NOT NULL DEFAULT '0' COMMENT '文件大小',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `shoucang` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `shoucolor` int unsigned NOT NULL DEFAULT '0' COMMENT '配色数量',
  `colorfenlei` int unsigned NOT NULL DEFAULT '0' COMMENT '色彩分类',
  `caiji` int unsigned NOT NULL DEFAULT '0' COMMENT '采集数量',
  `share` int unsigned NOT NULL DEFAULT '0' COMMENT '原创求分享',
  `sell` int unsigned NOT NULL DEFAULT '0' COMMENT '原创求售卖',
  `cid` int unsigned NOT NULL DEFAULT '0' COMMENT '0自己上传的；其余的素材ID',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `fenlei` int NOT NULL DEFAULT '0' COMMENT '分类',
  `laiyuan` varchar(2040) DEFAULT NULL COMMENT '来源',
  `jtui` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '作品精选',
  `jtuitime` int NOT NULL DEFAULT '0' COMMENT '作品精选时间',
  `tui` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '首页推荐',
  `tuitime` int NOT NULL DEFAULT '0' COMMENT '首页推荐时间',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  `dtime` int NOT NULL DEFAULT '0' COMMENT '采集操作时间',
  `colortime` int NOT NULL DEFAULT '0' COMMENT '配色收藏时间',
  `yesterday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '昨天最新',
  `coloryesterday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配色昨天最新',
  `lastweek` tinyint(1) NOT NULL DEFAULT '0' COMMENT '上周最新',
  `colorlastweek` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配色上周最新',
  `del` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常；2删除',
  `yid` int unsigned NOT NULL DEFAULT '0' COMMENT '原来的ID',
  `looknum` int unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `downnum` int unsigned NOT NULL DEFAULT '0' COMMENT '下载图片数量',
  `height` int unsigned NOT NULL DEFAULT '0' COMMENT '图片高度px',
  `color` varchar(50) DEFAULT NULL COMMENT '主颜色',
  `r` int NOT NULL DEFAULT '0',
  `g` int NOT NULL DEFAULT '0',
  `b` int NOT NULL DEFAULT '0',
  `count` int NOT NULL DEFAULT '0' COMMENT '占比数量',
  `sum` int NOT NULL DEFAULT '0' COMMENT '总数量',
  `percent` int NOT NULL DEFAULT '0' COMMENT '主颜色占比',
  `is_color` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有颜色',
  `color_id` int NOT NULL DEFAULT '0' COMMENT '相同图片关联颜色ID',
  `g_time` int NOT NULL DEFAULT '0' COMMENT '修改时间',
  `label` varchar(500) DEFAULT NULL COMMENT '标签，使用逗号隔开多个',
  `status` tinyint(1) DEFAULT '0' COMMENT '标签状态，0默认，1审核中，2审核通过，3通过限制，4审核驳回，5删除',
  PRIMARY KEY (`id`),
  KEY `aid_cid` (`aid`,`cid`) USING BTREE,
  KEY `yid` (`yid`) USING BTREE,
  KEY `del` (`del`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `shoucang` (`shoucang`) USING BTREE,
  KEY `caiji` (`caiji`) USING BTREE,
  KEY `looknum` (`looknum`) USING BTREE,
  KEY `index_search_id` (`id`,`aid`,`dtime`,`del`,`g_time`) USING BTREE,
  KEY `index_search_caiji` (`id`,`aid`,`caiji`,`del`,`g_time`,`dtime`),
  KEY `index_search_shoucang` (`id`,`aid`,`shoucang`,`dtime`,`del`,`g_time`) USING BTREE,
  KEY `index_search_looknum` (`id`,`aid`,`looknum`,`g_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1604310 DEFAULT CHARSET=utf8mb3 COMMENT='专辑列表'
/*!50100 PARTITION BY RANGE (`id`)
(PARTITION p0 VALUES LESS THAN (0) ENGINE = InnoDB,
 PARTITION p1 VALUES LESS THAN (100000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (200000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (300000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (400000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (600000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (700000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (800000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (900000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (1100000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (1200000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (1300000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION pm VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Table structure for dczg_albumlistcolor
-- ----------------------------
DROP TABLE IF EXISTS `dczg_albumlistcolor`;
CREATE TABLE `dczg_albumlistcolor` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '图片ID',
  `color` varchar(10) DEFAULT NULL COMMENT '颜色',
  `r` int NOT NULL DEFAULT '0',
  `g` int NOT NULL DEFAULT '0',
  `b` int NOT NULL DEFAULT '0',
  `count` int NOT NULL DEFAULT '0' COMMENT '占比数量',
  `percent` int NOT NULL DEFAULT '0' COMMENT '百分比',
  `num` int NOT NULL DEFAULT '0' COMMENT '排行',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `r` (`r`),
  KEY `g` (`g`),
  KEY `b` (`b`)
) ENGINE=InnoDB AUTO_INCREMENT=8871597 DEFAULT CHARSET=utf8mb3 COMMENT='图片颜色表'
/*!50100 PARTITION BY RANGE (`id`)
(PARTITION p0 VALUES LESS THAN (0) ENGINE = InnoDB,
 PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION pm VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Table structure for dczg_api
-- ----------------------------
DROP TABLE IF EXISTS `dczg_api`;
CREATE TABLE `dczg_api` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` char(150) NOT NULL DEFAULT '' COMMENT '接口名称',
  `group_id` int unsigned NOT NULL DEFAULT '0' COMMENT '接口分组',
  `request_type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '请求类型 0:POST  1:GET',
  `api_url` char(50) NOT NULL DEFAULT '' COMMENT '请求路径',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '接口描述',
  `describe_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接口富文本描述',
  `is_request_data` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否需要请求数据',
  `request_data` text NOT NULL COMMENT '请求数据',
  `response_data` text NOT NULL COMMENT '响应数据',
  `is_response_data` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否需要响应数据',
  `is_user_token` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否需要用户token',
  `is_response_sign` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否返回数据签名',
  `is_request_sign` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否验证请求数据签名',
  `response_examples` text NOT NULL COMMENT '响应栗子',
  `developer` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '研发者',
  `api_status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '接口状态（0:待研发，1:研发中，2:测试中，3:已完成）',
  `is_page` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否为分页接口 0：否  1：是',
  `sort` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb3 COMMENT='API表';

-- ----------------------------
-- Table structure for dczg_api_group
-- ----------------------------
DROP TABLE IF EXISTS `dczg_api_group`;
CREATE TABLE `dczg_api_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` char(120) NOT NULL DEFAULT '' COMMENT 'aip分组名称',
  `sort` tinyint unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb3 COMMENT='api分组表';

-- ----------------------------
-- Table structure for dczg_article
-- ----------------------------
DROP TABLE IF EXISTS `dczg_article`;
CREATE TABLE `dczg_article` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `member_id` int unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '文章名称',
  `category_id` int unsigned NOT NULL DEFAULT '0' COMMENT '文章分类',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text NOT NULL COMMENT '文章内容',
  `cover_id` int unsigned NOT NULL DEFAULT '0' COMMENT '封面图片id',
  `file_id` int unsigned NOT NULL DEFAULT '0' COMMENT '文件id',
  `img_ids` varchar(200) NOT NULL DEFAULT '',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='文章表';

-- ----------------------------
-- Table structure for dczg_article_category
-- ----------------------------
DROP TABLE IF EXISTS `dczg_article_category`;
CREATE TABLE `dczg_article_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `icon` char(20) NOT NULL DEFAULT '' COMMENT '分类图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='分类表';

-- ----------------------------
-- Table structure for dczg_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `dczg_auth_group`;
CREATE TABLE `dczg_auth_group` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `describe` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `member_id` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='权限组表';

-- ----------------------------
-- Table structure for dczg_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `dczg_auth_group_access`;
CREATE TABLE `dczg_auth_group_access` (
  `member_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `group_id` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户组授权表';

-- ----------------------------
-- Table structure for dczg_banner
-- ----------------------------
DROP TABLE IF EXISTS `dczg_banner`;
CREATE TABLE `dczg_banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `img` int unsigned NOT NULL COMMENT '图片',
  `url` varchar(2000) NOT NULL COMMENT '图片链接',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1首页；2素材；3文库',
  `time` int unsigned NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COMMENT='首页banner图';

-- ----------------------------
-- Table structure for dczg_bannerindex
-- ----------------------------
DROP TABLE IF EXISTS `dczg_bannerindex`;
CREATE TABLE `dczg_bannerindex` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL COMMENT '标题',
  `img` int NOT NULL DEFAULT '0' COMMENT '图片',
  `url` varchar(1020) DEFAULT NULL COMMENT '跳转链接',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COMMENT='首页轮播图';

-- ----------------------------
-- Table structure for dczg_baocun
-- ----------------------------
DROP TABLE IF EXISTS `dczg_baocun`;
CREATE TABLE `dczg_baocun` (
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lid` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `newid` int unsigned NOT NULL DEFAULT '0' COMMENT '新ID',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='保存专辑';

-- ----------------------------
-- Table structure for dczg_baocunzhuanji
-- ----------------------------
DROP TABLE IF EXISTS `dczg_baocunzhuanji`;
CREATE TABLE `dczg_baocunzhuanji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lid` varchar(2000) DEFAULT NULL COMMENT '专辑ID',
  `num` int NOT NULL DEFAULT '0' COMMENT '时间',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COMMENT='每天保存专辑';

-- ----------------------------
-- Table structure for dczg_bi
-- ----------------------------
DROP TABLE IF EXISTS `dczg_bi`;
CREATE TABLE `dczg_bi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '地产币个数',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `money` (`money`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='地产币配置表';

-- ----------------------------
-- Table structure for dczg_blogroll
-- ----------------------------
DROP TABLE IF EXISTS `dczg_blogroll`;
CREATE TABLE `dczg_blogroll` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL DEFAULT '' COMMENT '链接名称',
  `img_id` int unsigned NOT NULL DEFAULT '0' COMMENT '链接图片封面',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3 COMMENT='友情链接表';

-- ----------------------------
-- Table structure for dczg_brand_name
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_name`;
CREATE TABLE `dczg_brand_name` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int unsigned NOT NULL COMMENT '场景ID',
  `name` varchar(255) NOT NULL COMMENT '品牌名称',
  `lists` int NOT NULL COMMENT '排序',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COMMENT='品牌名称表';

-- ----------------------------
-- Table structure for dczg_brand_name_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_name_irelation`;
CREATE TABLE `dczg_brand_name_irelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '品牌名称ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '专辑ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑--名称关系表';

-- ----------------------------
-- Table structure for dczg_brand_scenes
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_scenes`;
CREATE TABLE `dczg_brand_scenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '场景标题',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COMMENT='场景表';

-- ----------------------------
-- Table structure for dczg_brand_scenes_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_scenes_irelation`;
CREATE TABLE `dczg_brand_scenes_irelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '场景ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '专辑ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑-场景关系表';

-- ----------------------------
-- Table structure for dczg_brand_use
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_use`;
CREATE TABLE `dczg_brand_use` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '标题',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='用途表';

-- ----------------------------
-- Table structure for dczg_brand_use_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_brand_use_irelation`;
CREATE TABLE `dczg_brand_use_irelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '用途ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '专辑ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑-用途关系表';

-- ----------------------------
-- Table structure for dczg_caiji
-- ----------------------------
DROP TABLE IF EXISTS `dczg_caiji`;
CREATE TABLE `dczg_caiji` (
  `cid` int unsigned NOT NULL DEFAULT '0' COMMENT '素材ID',
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '采集的次数',
  UNIQUE KEY `cid` (`cid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='采集表';

-- ----------------------------
-- Table structure for dczg_city
-- ----------------------------
DROP TABLE IF EXISTS `dczg_city`;
CREATE TABLE `dczg_city` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '地区名称',
  `parent_id` int DEFAULT NULL COMMENT '父id',
  `level` tinyint(1) DEFAULT '0' COMMENT '地区等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3524 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Table structure for dczg_color_change
-- ----------------------------
DROP TABLE IF EXISTS `dczg_color_change`;
CREATE TABLE `dczg_color_change` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `color` varchar(255) DEFAULT NULL COMMENT '渐变颜色',
  `img` int unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `download` int unsigned NOT NULL DEFAULT '0' COMMENT '下载文件',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb3 COMMENT='渐变配色';

-- ----------------------------
-- Table structure for dczg_color_fenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_color_fenlei`;
CREATE TABLE `dczg_color_fenlei` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COMMENT='配色推荐分类';

-- ----------------------------
-- Table structure for dczg_color_fluid
-- ----------------------------
DROP TABLE IF EXISTS `dczg_color_fluid`;
CREATE TABLE `dczg_color_fluid` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `color` varchar(255) DEFAULT NULL COMMENT '颜色逗号分隔',
  `img` int unsigned NOT NULL DEFAULT '0' COMMENT '列表图',
  `download` int unsigned NOT NULL DEFAULT '0' COMMENT '下载文件',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb3 COMMENT='流体配色';

-- ----------------------------
-- Table structure for dczg_color_hot
-- ----------------------------
DROP TABLE IF EXISTS `dczg_color_hot`;
CREATE TABLE `dczg_color_hot` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) DEFAULT NULL COMMENT '颜色名称',
  `color` varchar(255) DEFAULT NULL COMMENT '色值',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb3 COMMENT='推荐配色';

-- ----------------------------
-- Table structure for dczg_conf
-- ----------------------------
DROP TABLE IF EXISTS `dczg_conf`;
CREATE TABLE `dczg_conf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1关于我们；2用户协议；3版权声明；4支付协议；5出售协议；6我要供稿；7上传奖励；8联系我们；9广告合作',
  `title` varchar(30) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `time` int NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COMMENT='网站设置';

-- ----------------------------
-- Table structure for dczg_config
-- ----------------------------
DROP TABLE IF EXISTS `dczg_config`;
CREATE TABLE `dczg_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `group` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置选项',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3 COMMENT='配置表';

-- ----------------------------
-- Table structure for dczg_daydown
-- ----------------------------
DROP TABLE IF EXISTS `dczg_daydown`;
CREATE TABLE `dczg_daydown` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `lid` varchar(2000) DEFAULT NULL COMMENT 'id集群',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=2572 DEFAULT CHARSET=utf8mb3 COMMENT='下载表';

-- ----------------------------
-- Table structure for dczg_daydownsucai
-- ----------------------------
DROP TABLE IF EXISTS `dczg_daydownsucai`;
CREATE TABLE `dczg_daydownsucai` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COMMENT='下载素材表';

-- ----------------------------
-- Table structure for dczg_daywaterdc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_daywaterdc`;
CREATE TABLE `dczg_daywaterdc` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `dc` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '天',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=11541 DEFAULT CHARSET=utf8mb3 COMMENT='天收益';

-- ----------------------------
-- Table structure for dczg_driver
-- ----------------------------
DROP TABLE IF EXISTS `dczg_driver`;
CREATE TABLE `dczg_driver` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `service_name` varchar(40) NOT NULL DEFAULT '' COMMENT '服务标识',
  `driver_name` varchar(20) NOT NULL DEFAULT '' COMMENT '驱动标识',
  `config` text NOT NULL COMMENT '配置',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COMMENT='插件表';

-- ----------------------------
-- Table structure for dczg_fenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_fenlei`;
CREATE TABLE `dczg_fenlei` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int unsigned NOT NULL DEFAULT '0' COMMENT '素材目录',
  `name` varchar(30) DEFAULT NULL COMMENT '目录名',
  `img` int unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb3 COMMENT='分类';

-- ----------------------------
-- Table structure for dczg_fenleirelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_fenleirelation`;
CREATE TABLE `dczg_fenleirelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '分类ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='素材分类关系表';

-- ----------------------------
-- Table structure for dczg_file
-- ----------------------------
DROP TABLE IF EXISTS `dczg_file`;
CREATE TABLE `dczg_file` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '保存名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='文件表';

-- ----------------------------
-- Table structure for dczg_geshi
-- ----------------------------
DROP TABLE IF EXISTS `dczg_geshi`;
CREATE TABLE `dczg_geshi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '目录名',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COMMENT='格式';

-- ----------------------------
-- Table structure for dczg_geshirelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_geshirelation`;
CREATE TABLE `dczg_geshirelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '格式ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='素材格式关系表';

-- ----------------------------
-- Table structure for dczg_guanzhu
-- ----------------------------
DROP TABLE IF EXISTS `dczg_guanzhu`;
CREATE TABLE `dczg_guanzhu` (
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lid` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '专辑图片封面预览url',
  `album_uid` varchar(255) NOT NULL DEFAULT '' COMMENT '专辑所属的用户id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '收藏备注，来源等',
  `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='关注专辑';

-- ----------------------------
-- Table structure for dczg_guanzhutime
-- ----------------------------
DROP TABLE IF EXISTS `dczg_guanzhutime`;
CREATE TABLE `dczg_guanzhutime` (
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `aid` int unsigned NOT NULL DEFAULT '0' COMMENT '灵感ID',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '关注时间',
  UNIQUE KEY `uid` (`uid`,`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='关注时间表';

-- ----------------------------
-- Table structure for dczg_guanzhuuser
-- ----------------------------
DROP TABLE IF EXISTS `dczg_guanzhuuser`;
CREATE TABLE `dczg_guanzhuuser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `bid` int unsigned NOT NULL DEFAULT '0' COMMENT '被关注的用户',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`bid`),
  KEY `uid_2` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1328 DEFAULT CHARSET=utf8mb3 COMMENT='关注用户表';

-- ----------------------------
-- Table structure for dczg_heji
-- ----------------------------
DROP TABLE IF EXISTS `dczg_heji`;
CREATE TABLE `dczg_heji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `fid` int NOT NULL DEFAULT '0' COMMENT '分类ID',
  `ids` varchar(1020) DEFAULT NULL COMMENT '图片id集',
  `des` varchar(255) DEFAULT NULL COMMENT '合集描述',
  `collection` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '总数量',
  `lebel` varchar(1020) DEFAULT NULL COMMENT '标签合集',
  `is_tui` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常；1推荐',
  `is_shou` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0自己创建的；1收藏的',
  `sid` int unsigned NOT NULL DEFAULT '0' COMMENT '被收藏的ID',
  `datetui` int NOT NULL DEFAULT '0' COMMENT '推荐月份',
  `updatetime` int unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `time` int NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2791 DEFAULT CHARSET=utf8mb3 COMMENT='合集表';

-- ----------------------------
-- Table structure for dczg_hook
-- ----------------------------
DROP TABLE IF EXISTS `dczg_hook`;
CREATE TABLE `dczg_hook` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `describe` varchar(255) NOT NULL COMMENT '描述',
  `addon_list` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COMMENT='钩子表';

-- ----------------------------
-- Table structure for dczg_img
-- ----------------------------
DROP TABLE IF EXISTS `dczg_img`;
CREATE TABLE `dczg_img` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unnum` bigint unsigned NOT NULL DEFAULT '0' COMMENT '唯一编号',
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `path` varchar(300) DEFAULT NULL COMMENT '图片路径',
  `suffix` varchar(10) DEFAULT NULL COMMENT '文件后缀名',
  `size` int unsigned NOT NULL DEFAULT '0' COMMENT '文件字节数',
  `name` varchar(200) DEFAULT NULL COMMENT '文件名',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0待处理；1审核中；2未通过；3已通过;4需调整',
  `del` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0正常；1删除',
  `img` varchar(200) DEFAULT NULL COMMENT '预览图ID',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `leixing` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1共享素材；2原创素材',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `ttime` int unsigned NOT NULL DEFAULT '0' COMMENT '通过时间',
  `dtime` int unsigned NOT NULL DEFAULT '0' COMMENT '下载操作时间',
  `text` varchar(50) DEFAULT NULL COMMENT '不通过原因',
  `shoucang` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏量',
  `downnum` int unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `tui` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2推荐',
  `t_time` int NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  `week` int unsigned NOT NULL DEFAULT '0' COMMENT '周数',
  `weekguanzhu` int NOT NULL DEFAULT '0' COMMENT '下载数',
  `looknum` int unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `height` int unsigned NOT NULL DEFAULT '0' COMMENT '高度',
  `yesterday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '昨日热门',
  `ll` tinyint(1) NOT NULL DEFAULT '0',
  `guanjianci` varchar(1020) DEFAULT NULL COMMENT '关键词',
  `g_time` int NOT NULL DEFAULT '0' COMMENT '修改时间',
  `mulu_id` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `geshi_id` int NOT NULL DEFAULT '0' COMMENT '格式id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unnum` (`unnum`),
  KEY `status` (`status`),
  KEY `uid_2` (`uid`),
  KEY `uid_status_del` (`uid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=104116 DEFAULT CHARSET=utf8mb3 COMMENT='用户图片';

-- ----------------------------
-- Table structure for dczg_imglabel
-- ----------------------------
DROP TABLE IF EXISTS `dczg_imglabel`;
CREATE TABLE `dczg_imglabel` (
  `aid` int NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `name` varchar(20) DEFAULT NULL COMMENT '标签'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='图片标签';

-- ----------------------------
-- Table structure for dczg_invite_profit
-- ----------------------------
DROP TABLE IF EXISTS `dczg_invite_profit`;
CREATE TABLE `dczg_invite_profit` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `tui` varchar(50) DEFAULT NULL COMMENT '推荐人',
  `tui_name` varchar(50) NOT NULL DEFAULT '0' COMMENT '推荐人个数（每天归零）',
  `tuitime` int unsigned NOT NULL DEFAULT '0' COMMENT '推荐注册时间',
  `viptime` int unsigned NOT NULL DEFAULT '0' COMMENT 'vip开通时间',
  `chargetime` int unsigned NOT NULL DEFAULT '0' COMMENT '充值原创币时间',
  `total` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未提交；1已提交；2已通过；3未通过',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=14258 DEFAULT CHARSET=utf8mb3 COMMENT='用户资料表';

-- ----------------------------
-- Table structure for dczg_iplook
-- ----------------------------
DROP TABLE IF EXISTS `dczg_iplook`;
CREATE TABLE `dczg_iplook` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) DEFAULT NULL COMMENT '用户IP',
  `lip` varchar(300) DEFAULT NULL COMMENT '灵感IP',
  `num` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '查看数量',
  `time` int NOT NULL DEFAULT '0' COMMENT '当天时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=212240 DEFAULT CHARSET=utf8mb3 COMMENT='ip查看灵感数量';

-- ----------------------------
-- Table structure for dczg_jubao
-- ----------------------------
DROP TABLE IF EXISTS `dczg_jubao`;
CREATE TABLE `dczg_jubao` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL COMMENT '谁举报的',
  `type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1灵感；2文库；2素材',
  `bid` int NOT NULL COMMENT '项目ID',
  `text` varchar(200) NOT NULL COMMENT '举报内容',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='举报';

-- ----------------------------
-- Table structure for dczg_keywords
-- ----------------------------
DROP TABLE IF EXISTS `dczg_keywords`;
CREATE TABLE `dczg_keywords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL COMMENT '类型',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COMMENT='关键词';

-- ----------------------------
-- Table structure for dczg_keywords_type
-- ----------------------------
DROP TABLE IF EXISTS `dczg_keywords_type`;
CREATE TABLE `dczg_keywords_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `must` int NOT NULL DEFAULT '0' COMMENT '是否必选',
  `time` int NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='关键词类别';

-- ----------------------------
-- Table structure for dczg_lingfenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_lingfenlei`;
CREATE TABLE `dczg_lingfenlei` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '标题',
  `lists` int NOT NULL DEFAULT '0' COMMENT '越小越往前',
  `img` int unsigned NOT NULL COMMENT '图片',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COMMENT='灵感分类';

-- ----------------------------
-- Table structure for dczg_linglebel
-- ----------------------------
DROP TABLE IF EXISTS `dczg_linglebel`;
CREATE TABLE `dczg_linglebel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标签',
  `fid` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb3 COMMENT='灵感标签';

-- ----------------------------
-- Table structure for dczg_looksay
-- ----------------------------
DROP TABLE IF EXISTS `dczg_looksay`;
CREATE TABLE `dczg_looksay` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `sid` int unsigned NOT NULL COMMENT '消息ID',
  UNIQUE KEY `uid` (`uid`,`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户是不是查看过消息';

-- ----------------------------
-- Table structure for dczg_member
-- ----------------------------
DROP TABLE IF EXISTS `dczg_member`;
CREATE TABLE `dczg_member` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `username` char(16) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `leader_id` int unsigned NOT NULL DEFAULT '1' COMMENT '上级会员ID',
  `is_share_member` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否共享会员',
  `is_inside` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否为后台使用者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COMMENT='会员表';

-- ----------------------------
-- Table structure for dczg_menu
-- ----------------------------
DROP TABLE IF EXISTS `dczg_menu`;
CREATE TABLE `dczg_menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `img` int unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `is_hide` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `is_shortcut` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否快捷操作',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=utf8mb3 COMMENT='菜单表';

-- ----------------------------
-- Table structure for dczg_monthwaterdc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_monthwaterdc`;
CREATE TABLE `dczg_monthwaterdc` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `dc` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '天',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=1747 DEFAULT CHARSET=utf8mb3 COMMENT='月收益';

-- ----------------------------
-- Table structure for dczg_mulu
-- ----------------------------
DROP TABLE IF EXISTS `dczg_mulu`;
CREATE TABLE `dczg_mulu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '目录名',
  `time` int NOT NULL COMMENT '操作时间',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COMMENT='目录';

-- ----------------------------
-- Table structure for dczg_mulurelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_mulurelation`;
CREATE TABLE `dczg_mulurelation` (
  `mid` int NOT NULL COMMENT '目录ID',
  `iid` int NOT NULL COMMENT '素材ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='素材目录关系表';

-- ----------------------------
-- Table structure for dczg_notice
-- ----------------------------
DROP TABLE IF EXISTS `dczg_notice`;
CREATE TABLE `dczg_notice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `des` varchar(1000) NOT NULL COMMENT '描述',
  `time` int NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COMMENT='系统公告';

-- ----------------------------
-- Table structure for dczg_noticelook
-- ----------------------------
DROP TABLE IF EXISTS `dczg_noticelook`;
CREATE TABLE `dczg_noticelook` (
  `uid` int unsigned NOT NULL,
  `nid` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='系统公告查看';

-- ----------------------------
-- Table structure for dczg_num
-- ----------------------------
DROP TABLE IF EXISTS `dczg_num`;
CREATE TABLE `dczg_num` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `num` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COMMENT='生成唯一的账号';

-- ----------------------------
-- Table structure for dczg_order
-- ----------------------------
DROP TABLE IF EXISTS `dczg_order`;
CREATE TABLE `dczg_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_sn` varchar(30) DEFAULT NULL COMMENT 'ordersn',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未支付；1已支付',
  `pay_time` int unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1支付宝；2微信',
  `classify` tinyint unsigned DEFAULT '1' COMMENT '1-4灵感；5-8文库；9-12素材；13地产币；17-20素材时间',
  `num` int NOT NULL DEFAULT '0' COMMENT '增加的天数或者分数',
  `time` int NOT NULL DEFAULT '0' COMMENT '创建订单时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn` (`order_sn`)
) ENGINE=InnoDB AUTO_INCREMENT=3913 DEFAULT CHARSET=utf8mb3 COMMENT='用户订单表';

-- ----------------------------
-- Table structure for dczg_paint_country
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_country`;
CREATE TABLE `dczg_paint_country` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '国家名称',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb3 COMMENT='国籍表';

-- ----------------------------
-- Table structure for dczg_paint_country_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_country_irelation`;
CREATE TABLE `dczg_paint_country_irelation` (
  `mid` int NOT NULL,
  `iid` int NOT NULL,
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑-国家关系表';

-- ----------------------------
-- Table structure for dczg_paint_name
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_name`;
CREATE TABLE `dczg_paint_name` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int NOT NULL COMMENT '国家ID',
  `name` varchar(255) NOT NULL COMMENT '标题',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8mb3 COMMENT='画师名字表';

-- ----------------------------
-- Table structure for dczg_paint_name_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_name_irelation`;
CREATE TABLE `dczg_paint_name_irelation` (
  `mid` int NOT NULL,
  `iid` int NOT NULL,
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑-画师名字关系表';

-- ----------------------------
-- Table structure for dczg_paint_style
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_style`;
CREATE TABLE `dczg_paint_style` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '标题',
  `lists` int NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COMMENT='绘画风格表';

-- ----------------------------
-- Table structure for dczg_paint_style_irelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_paint_style_irelation`;
CREATE TABLE `dczg_paint_style_irelation` (
  `mid` int NOT NULL,
  `iid` int NOT NULL,
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='专辑-风格关系表';

-- ----------------------------
-- Table structure for dczg_picture
-- ----------------------------
DROP TABLE IF EXISTS `dczg_picture`;
CREATE TABLE `dczg_picture` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142937 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='图片表';

-- ----------------------------
-- Table structure for dczg_qiandao
-- ----------------------------
DROP TABLE IF EXISTS `dczg_qiandao`;
CREATE TABLE `dczg_qiandao` (
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '签到时间',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户签到表';

-- ----------------------------
-- Table structure for dczg_question
-- ----------------------------
DROP TABLE IF EXISTS `dczg_question`;
CREATE TABLE `dczg_question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL DEFAULT '0' COMMENT '类型id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `contact` varchar(255) DEFAULT NULL COMMENT '联系方式',
  `create_time` int NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COMMENT='问题管理';

-- ----------------------------
-- Table structure for dczg_question_type
-- ----------------------------
DROP TABLE IF EXISTS `dczg_question_type`;
CREATE TABLE `dczg_question_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  `create_time` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COMMENT='问题类型';

-- ----------------------------
-- Table structure for dczg_request_log
-- ----------------------------
DROP TABLE IF EXISTS `dczg_request_log`;
CREATE TABLE `dczg_request_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(64) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `uri` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT 'uri',
  `ip` varchar(64) DEFAULT '' COMMENT 'uri',
  `ip_location` varchar(500) DEFAULT '' COMMENT 'ip 地址位置',
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0 http请求 风控日志',
  `refer` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '来源',
  `user_agent` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '浏览器类型',
  `request_params` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '请求参数',
  `request_method` varchar(30) DEFAULT NULL COMMENT '请求方式，get,post..',
  `create_time` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uri` (`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=1291 DEFAULT CHARSET=utf8mb3 COMMENT='请求日志表单';

-- ----------------------------
-- Table structure for dczg_say
-- ----------------------------
DROP TABLE IF EXISTS `dczg_say`;
CREATE TABLE `dczg_say` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户消息',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `des` varchar(1000) DEFAULT NULL COMMENT '描述',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1未看；2已看',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='信息通知';

-- ----------------------------
-- Table structure for dczg_sclabel
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sclabel`;
CREATE TABLE `dczg_sclabel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标签',
  `fid` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb3 COMMENT='素材标签';

-- ----------------------------
-- Table structure for dczg_scthreefenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_scthreefenlei`;
CREATE TABLE `dczg_scthreefenlei` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` varchar(30) DEFAULT NULL COMMENT '分类名',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb3 COMMENT='素材三级分类';

-- ----------------------------
-- Table structure for dczg_scthreefenleirelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_scthreefenleirelation`;
CREATE TABLE `dczg_scthreefenleirelation` (
  `mid` int NOT NULL DEFAULT '0' COMMENT '目录ID',
  `iid` int NOT NULL DEFAULT '0' COMMENT '文库ID',
  UNIQUE KEY `mid` (`mid`,`iid`),
  KEY `iid` (`iid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='素材三级分类关系表';

-- ----------------------------
-- Table structure for dczg_searchkeyword
-- ----------------------------
DROP TABLE IF EXISTS `dczg_searchkeyword`;
CREATE TABLE `dczg_searchkeyword` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL COMMENT '关键词',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COMMENT='搜索关键词';

-- ----------------------------
-- Table structure for dczg_sell
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sell`;
CREATE TABLE `dczg_sell` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `lid` int unsigned NOT NULL COMMENT '专辑ID',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='求售卖';

-- ----------------------------
-- Table structure for dczg_share
-- ----------------------------
DROP TABLE IF EXISTS `dczg_share`;
CREATE TABLE `dczg_share` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `lid` int unsigned NOT NULL COMMENT '专辑ID',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='求分享';

-- ----------------------------
-- Table structure for dczg_shoucolor
-- ----------------------------
DROP TABLE IF EXISTS `dczg_shoucolor`;
CREATE TABLE `dczg_shoucolor` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `lid` int unsigned NOT NULL COMMENT '图片ID',
  `cid` int unsigned NOT NULL COMMENT '颜色ID',
  `time` int unsigned NOT NULL COMMENT '时间',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='收藏配色';

-- ----------------------------
-- Table structure for dczg_shouimg
-- ----------------------------
DROP TABLE IF EXISTS `dczg_shouimg`;
CREATE TABLE `dczg_shouimg` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `iid` int unsigned NOT NULL COMMENT '素材ID',
  `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url',
  `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id',
  `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id',
  `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等',
  `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  UNIQUE KEY `uid` (`uid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='收藏素材';

-- ----------------------------
-- Table structure for dczg_shouling
-- ----------------------------
DROP TABLE IF EXISTS `dczg_shouling`;
CREATE TABLE `dczg_shouling` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `lid` int unsigned NOT NULL COMMENT '灵感ID',
  `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url',
  `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id',
  `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id',
  `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等',
  `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  UNIQUE KEY `uid` (`uid`,`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户收藏灵感表';

-- ----------------------------
-- Table structure for dczg_shouwen
-- ----------------------------
DROP TABLE IF EXISTS `dczg_shouwen`;
CREATE TABLE `dczg_shouwen` (
  `uid` int unsigned NOT NULL COMMENT '用户ID',
  `wid` int unsigned NOT NULL COMMENT '文库ID',
  UNIQUE KEY `uid` (`uid`,`wid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='收藏文库';

-- ----------------------------
-- Table structure for dczg_signin
-- ----------------------------
DROP TABLE IF EXISTS `dczg_signin`;
CREATE TABLE `dczg_signin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户id',
  `days` int NOT NULL COMMENT '连续签到天数',
  `total_days` int NOT NULL COMMENT '累计签到天数',
  `last_signin_time` int NOT NULL COMMENT '最后一次签到时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COMMENT='签到活动表';

-- ----------------------------
-- Table structure for dczg_signin_log
-- ----------------------------
DROP TABLE IF EXISTS `dczg_signin_log`;
CREATE TABLE `dczg_signin_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户id',
  `sign_gift` int NOT NULL COMMENT '签到奖励',
  `sign_time` int NOT NULL COMMENT '签到时间',
  `type` int NOT NULL COMMENT '奖品类型 1-积分 2-素材vip  3-文库vip',
  `create_time` int NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COMMENT='签到日志表';

-- ----------------------------
-- Table structure for dczg_sms
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sms`;
CREATE TABLE `dczg_sms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mobile` char(20) NOT NULL COMMENT '手机号',
  `code` char(20) NOT NULL COMMENT '验证码',
  `event` char(20) NOT NULL COMMENT '事件',
  `count` int NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(255) NOT NULL COMMENT '请求ip',
  `create_time` int NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COMMENT='短信验证码';

-- ----------------------------
-- Table structure for dczg_sucai
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sucai`;
CREATE TABLE `dczg_sucai` (
  `id` tinyint(1) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `img` int NOT NULL,
  `time` int NOT NULL COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='素材ID';

-- ----------------------------
-- Table structure for dczg_sucaidown
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sucaidown`;
CREATE TABLE `dczg_sucaidown` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  `ids` varchar(255) DEFAULT NULL COMMENT '素材ID',
  `time` int NOT NULL DEFAULT '0' COMMENT '今天的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=5993 DEFAULT CHARSET=utf8mb3 COMMENT='素材下载';

-- ----------------------------
-- Table structure for dczg_sucaidowndc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sucaidowndc`;
CREATE TABLE `dczg_sucaidowndc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  `ids` varchar(255) DEFAULT NULL COMMENT '素材ID',
  `time` int NOT NULL DEFAULT '0' COMMENT '今天的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=10512 DEFAULT CHARSET=utf8mb3 COMMENT='素材下载';

-- ----------------------------
-- Table structure for dczg_sucaiguanggao
-- ----------------------------
DROP TABLE IF EXISTS `dczg_sucaiguanggao`;
CREATE TABLE `dczg_sucaiguanggao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL COMMENT '标题',
  `img` int NOT NULL DEFAULT '0' COMMENT '图片',
  `url` varchar(255) DEFAULT NULL COMMENT '链接',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COMMENT='素材广告位';

-- ----------------------------
-- Table structure for dczg_tixian
-- ----------------------------
DROP TABLE IF EXISTS `dczg_tixian`;
CREATE TABLE `dczg_tixian` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `zhi` varchar(200) DEFAULT NULL COMMENT '支付宝账号',
  `money` decimal(10,2) unsigned DEFAULT NULL COMMENT '金额',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0待审核；1已通过；2已退回',
  `dotime` int unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `text` varchar(1000) DEFAULT NULL COMMENT '原因',
  `time` int NOT NULL DEFAULT '0' COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3 COMMENT='提现列表';

-- ----------------------------
-- Table structure for dczg_top
-- ----------------------------
DROP TABLE IF EXISTS `dczg_top`;
CREATE TABLE `dczg_top` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img1` int NOT NULL DEFAULT '0' COMMENT '图片1',
  `url1` varchar(1020) DEFAULT NULL COMMENT '链接1',
  `img2` int NOT NULL DEFAULT '0' COMMENT '图片2',
  `url2` varchar(1020) DEFAULT NULL COMMENT '链接2',
  `img3` int NOT NULL DEFAULT '0',
  `url3` varchar(1020) DEFAULT NULL,
  `img4` int NOT NULL DEFAULT '0',
  `url4` varchar(1020) DEFAULT NULL,
  `img5` int NOT NULL DEFAULT '0',
  `url5` varchar(1020) DEFAULT NULL,
  `img6` int NOT NULL DEFAULT '0',
  `url6` varchar(1020) DEFAULT NULL,
  `img7` int NOT NULL DEFAULT '0',
  `url7` varchar(1020) DEFAULT NULL,
  `img8` int NOT NULL DEFAULT '0',
  `url8` varchar(1020) DEFAULT NULL,
  `img9` int NOT NULL DEFAULT '0',
  `url9` varchar(1020) DEFAULT NULL,
  `img10` int NOT NULL DEFAULT '0',
  `url10` varchar(1020) DEFAULT NULL,
  `time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COMMENT='头部广告位';

-- ----------------------------
-- Table structure for dczg_user
-- ----------------------------
DROP TABLE IF EXISTS `dczg_user`;
CREATE TABLE `dczg_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户openid',
  `unionid` varchar(50) DEFAULT NULL COMMENT 'unionid',
  `parent_id` int unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '用户名',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '用户昵称',
  `imghead` varchar(1000) DEFAULT NULL COMMENT '用户头像',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1男;2女；',
  `subscribe` tinyint(1) NOT NULL DEFAULT '1' COMMENT '微信关注状态',
  `usertype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1微信2小程序',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `address` varchar(1000) DEFAULT NULL COMMENT '地址',
  `content` varchar(255) DEFAULT NULL COMMENT '简介',
  `vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0没有支付过的，1支付过灵感；2支付过文库；3两个都支付过',
  `score` int unsigned NOT NULL DEFAULT '0' COMMENT '共享分',
  `dc` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '地产币',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `sheng` int NOT NULL DEFAULT '0' COMMENT '省',
  `city` int NOT NULL DEFAULT '0' COMMENT '城市',
  `qu` int NOT NULL DEFAULT '0' COMMENT '区',
  `qi` int unsigned NOT NULL DEFAULT '0' COMMENT '人气',
  `fans` int unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `guan` int unsigned NOT NULL DEFAULT '0' COMMENT '关注',
  `isarr` tinyint(1) DEFAULT '1' COMMENT '工作组权限',
  `lvip` tinyint(1) DEFAULT '1',
  `isview` int unsigned DEFAULT '20' COMMENT '查看张数',
  `isbrand` tinyint(1) DEFAULT '1' COMMENT '品牌馆权限 1否2是',
  `ispaint` tinyint(1) DEFAULT '1' COMMENT '绘画馆权限 1否2是',
  `iszj` tinyint(1) DEFAULT '1' COMMENT '普通专辑 是否禁止 1否2是',
  `isyczj` tinyint(1) DEFAULT '1' COMMENT '原创专辑 是否禁止 1否2是',
  `qq` varchar(15) DEFAULT NULL COMMENT 'QQ号',
  `wx` varchar(200) DEFAULT NULL COMMENT '微信',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `logintime` int NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `jinzhi` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1正常；2禁止登陆',
  `get_score` int unsigned NOT NULL DEFAULT '10' COMMENT '每次签到给10个积分',
  `scoretime` int NOT NULL DEFAULT '0' COMMENT '积分到期时间',
  `invitecode` varchar(255) DEFAULT '' COMMENT '邀请者',
  `mobile` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '手机号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `openid` (`openid`),
  UNIQUE KEY `unionid` (`unionid`)
) ENGINE=InnoDB AUTO_INCREMENT=33334 DEFAULT CHARSET=utf8mb3 COMMENT='用户表';

-- ----------------------------
-- Table structure for dczg_userdata
-- ----------------------------
DROP TABLE IF EXISTS `dczg_userdata`;
CREATE TABLE `dczg_userdata` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `tel` varchar(20) DEFAULT NULL COMMENT '手机号',
  `cardnum` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `zhi` varchar(20) DEFAULT NULL COMMENT '支付宝',
  `qq` varchar(20) DEFAULT NULL COMMENT 'QQ',
  `email` varchar(200) DEFAULT NULL COMMENT '邮箱',
  `cardimg` varchar(300) DEFAULT NULL COMMENT '身份证照片',
  `cardimg1` varchar(300) DEFAULT NULL COMMENT '反面',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未提交；1已提交；2已通过；3未通过',
  `text` varchar(1000) DEFAULT NULL COMMENT '不通过原因',
  `shoucang` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `shoucolor` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏配色',
  `shouling` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏灵感',
  `shouwen` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏文库',
  `shousucai` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏素材',
  `zhuanji` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑数量',
  `zuopin` int unsigned NOT NULL DEFAULT '0' COMMENT '作品数量',
  `sucainum` int unsigned NOT NULL DEFAULT '0' COMMENT '素材数量',
  `wenkunum` int unsigned NOT NULL DEFAULT '0' COMMENT '文库数量',
  `tui` int unsigned NOT NULL DEFAULT '0' COMMENT '推荐人个数（每天归零）',
  `tuitime` int unsigned NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `total` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  `sucai_tui` int NOT NULL DEFAULT '0' COMMENT '素材推荐用户',
  `album_tui` int NOT NULL DEFAULT '0' COMMENT '灵感推荐用户',
  `cover_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '个人主页封面图',
  `cover_img_tmp` varchar(255) NOT NULL DEFAULT '' COMMENT '个人主页封面图临时图，待审核通过',
  `cover_img_msg` varchar(255) NOT NULL DEFAULT '' COMMENT '未审核通过原因',
  `cover_img_status` int NOT NULL DEFAULT '0' COMMENT '封面状态，0无需审核，1有待审核，2审核拒绝，3审核通过',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=33335 DEFAULT CHARSET=utf8mb3 COMMENT='用户资料表';

-- ----------------------------
-- Table structure for dczg_userlookling
-- ----------------------------
DROP TABLE IF EXISTS `dczg_userlookling`;
CREATE TABLE `dczg_userlookling` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lip` varchar(2000) DEFAULT NULL COMMENT '查看的灵感图片',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '查看数量',
  `time` int NOT NULL DEFAULT '0' COMMENT '当天的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14263 DEFAULT CHARSET=utf8mb3 COMMENT='用户查看灵感';

-- ----------------------------
-- Table structure for dczg_userlooklingyuan
-- ----------------------------
DROP TABLE IF EXISTS `dczg_userlooklingyuan`;
CREATE TABLE `dczg_userlooklingyuan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lid` varchar(2000) DEFAULT NULL COMMENT '查看原图的列表',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '查看个数',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=2344 DEFAULT CHARSET=utf8mb3 COMMENT='查看原图';

-- ----------------------------
-- Table structure for dczg_uservip
-- ----------------------------
DROP TABLE IF EXISTS `dczg_uservip`;
CREATE TABLE `dczg_uservip` (
  `uid` int unsigned NOT NULL,
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1灵感；2文库；3素材共享时间；4素材地产币时间；5免费文库时间；6原创文库时间',
  `vip` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `paytime` int NOT NULL DEFAULT '0' COMMENT '支付时间',
  `vip1num` int NOT NULL DEFAULT '0',
  `vip2num` int NOT NULL DEFAULT '0',
  `vip3num` int NOT NULL DEFAULT '0',
  `vip4num` int NOT NULL DEFAULT '0',
  `vip5num` int NOT NULL DEFAULT '0',
  `vip6num` int NOT NULL DEFAULT '0',
  `vip7num` int NOT NULL DEFAULT '0',
  `vip8num` int NOT NULL DEFAULT '0',
  `vip9num` int NOT NULL DEFAULT '0',
  `vip10num` int NOT NULL DEFAULT '0',
  `vip11num` int NOT NULL DEFAULT '0',
  `vip12num` int NOT NULL DEFAULT '0',
  `vip13num` int NOT NULL DEFAULT '0',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT 'vip到期时间',
  UNIQUE KEY `uid` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户权限表';

-- ----------------------------
-- Table structure for dczg_uservipuse
-- ----------------------------
DROP TABLE IF EXISTS `dczg_uservipuse`;
CREATE TABLE `dczg_uservipuse` (
  `uid` int unsigned NOT NULL,
  `type` tinyint unsigned NOT NULL COMMENT '1灵感；2文库',
  `paytime` int unsigned NOT NULL COMMENT '第一次支付的时间',
  `vip1num` int unsigned NOT NULL COMMENT 'vip1天数',
  `vip2num` int unsigned NOT NULL COMMENT 'vip2天数',
  `vip3num` int unsigned NOT NULL COMMENT 'vip3天数',
  `vip4num` int unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户vip天数';

-- ----------------------------
-- Table structure for dczg_vip
-- ----------------------------
DROP TABLE IF EXISTS `dczg_vip`;
CREATE TABLE `dczg_vip` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ordinary` decimal(10,2) unsigned NOT NULL COMMENT '普通',
  `super` decimal(10,2) unsigned NOT NULL COMMENT '超级',
  `platinum` decimal(10,2) unsigned NOT NULL COMMENT '黄金',
  `diamonds` decimal(10,2) unsigned NOT NULL COMMENT '钻石',
  `title` varchar(10) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COMMENT='充值页面';

-- ----------------------------
-- Table structure for dczg_waterdc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_waterdc`;
CREATE TABLE `dczg_waterdc` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `score` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '地产币',
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1审核通过增加积分；2签到；3被下载增加积分;4下载减少的地产币',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1素材；2文库',
  `wid` int unsigned NOT NULL DEFAULT '0' COMMENT '文库或者素材ID',
  `bid` int NOT NULL DEFAULT '0' COMMENT '关联ID',
  `name` varchar(300) DEFAULT NULL COMMENT '关联名称',
  `is_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常；1vip下载',
  `time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30716 DEFAULT CHARSET=utf8mb3 COMMENT='共享分流水表';

-- ----------------------------
-- Table structure for dczg_waterdo
-- ----------------------------
DROP TABLE IF EXISTS `dczg_waterdo`;
CREATE TABLE `dczg_waterdo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `doid` int unsigned NOT NULL DEFAULT '0' COMMENT '操作人ID',
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1关注专辑；2保存专辑；3关注我；4采集图片；5收藏文库；6收藏灵感；7收藏素材；8取消收藏素材；9取消收藏文库；10取消收藏灵感；11取消关注人',
  `original` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1非原创2原创',
  `cid` int unsigned NOT NULL DEFAULT '0' COMMENT '采集图片ID',
  `aid` int unsigned NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1未看，2已看',
  `zid` int NOT NULL DEFAULT '0' COMMENT '采集图片到哪个专辑',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `name` varchar(255) DEFAULT NULL COMMENT '相关名称',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=111426 DEFAULT CHARSET=utf8mb3 COMMENT='操作流水表';

-- ----------------------------
-- Table structure for dczg_waterdown
-- ----------------------------
DROP TABLE IF EXISTS `dczg_waterdown`;
CREATE TABLE `dczg_waterdown` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bid` int unsigned NOT NULL DEFAULT '0' COMMENT '谁的东西',
  `wid` int unsigned NOT NULL DEFAULT '0' COMMENT '文库ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1文库；2素材',
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '谁下载的',
  `score` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '下载使用的积分',
  `dc` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '花的地产币',
  `vip` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0正常下载；1vip下载',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=49610 DEFAULT CHARSET=utf8mb3 COMMENT='文库下载列表';

-- ----------------------------
-- Table structure for dczg_waterdui
-- ----------------------------
DROP TABLE IF EXISTS `dczg_waterdui`;
CREATE TABLE `dczg_waterdui` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `dc` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '地产币',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COMMENT='兑换地产币';

-- ----------------------------
-- Table structure for dczg_watermoney
-- ----------------------------
DROP TABLE IF EXISTS `dczg_watermoney`;
CREATE TABLE `dczg_watermoney` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '用户ID',
  `paytype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1微信充值；2支付宝；3邀请两个好友',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-4灵感；5-8文库；9-12素材；13地产币；14邀请好友;17-20素材时间;21-23文库时间；24-26文库时间',
  `day` int unsigned NOT NULL DEFAULT '0',
  `score` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分数',
  `daiqitime` int unsigned NOT NULL DEFAULT '0' COMMENT '到期时间',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1954 DEFAULT CHARSET=utf8mb3 COMMENT='vip充值流水';

-- ----------------------------
-- Table structure for dczg_waterscore
-- ----------------------------
DROP TABLE IF EXISTS `dczg_waterscore`;
CREATE TABLE `dczg_waterscore` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `score` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '共享分',
  `type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1审核通过增加积分；2签到；3被下载增加积分；4下载扣除的积分',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1素材；2文库',
  `wid` int unsigned NOT NULL DEFAULT '0' COMMENT '文库或者素材ID',
  `bid` int NOT NULL DEFAULT '0' COMMENT '关联ID',
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129358 DEFAULT CHARSET=utf8mb3 COMMENT='共享分流水表';

-- ----------------------------
-- Table structure for dczg_webconf
-- ----------------------------
DROP TABLE IF EXISTS `dczg_webconf`;
CREATE TABLE `dczg_webconf` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(2000) NOT NULL COMMENT '标题',
  `des` varchar(500) NOT NULL COMMENT '描述',
  `wx` varchar(20) NOT NULL COMMENT '微信',
  `qq` varchar(10) NOT NULL COMMENT 'qq号',
  `qrcode` int NOT NULL COMMENT '公众号二维码',
  `webqrcode` int NOT NULL COMMENT '网站二维码',
  `qun` int NOT NULL COMMENT '群二维码',
  `logo` int unsigned NOT NULL COMMENT 'logo',
  `buttonlogo` int unsigned NOT NULL COMMENT '底部logo',
  `banquan` varchar(500) NOT NULL COMMENT '版权',
  `time` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COMMENT='网站配置';

-- ----------------------------
-- Table structure for dczg_weekwaterdc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_weekwaterdc`;
CREATE TABLE `dczg_weekwaterdc` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `dc` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `time` int unsigned NOT NULL DEFAULT '0' COMMENT '天',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=4336 DEFAULT CHARSET=utf8mb3 COMMENT='周收益';

-- ----------------------------
-- Table structure for dczg_wenku
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenku`;
CREATE TABLE `dczg_wenku` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unnum` bigint unsigned NOT NULL DEFAULT '0' COMMENT '唯一编号',
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `path` varchar(300) DEFAULT NULL COMMENT '图片路径',
  `suffix` varchar(10) DEFAULT NULL COMMENT '文件后缀名',
  `size` int unsigned NOT NULL DEFAULT '0' COMMENT '文件字节数',
  `geshi` varchar(25) DEFAULT NULL COMMENT '文件格式',
  `name` varchar(200) DEFAULT NULL COMMENT '文件名',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0待处理；1审核中；2未通过；3已通过',
  `del` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0正常；1删除',
  `img` varchar(200) DEFAULT NULL COMMENT '预览图ID',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `des` varchar(1000) DEFAULT NULL COMMENT '描述',
  `leixing` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1共享素材；2原创素材',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `ttime` int unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `dtime` int unsigned NOT NULL DEFAULT '0' COMMENT '下载时间',
  `yesterday` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '昨日最新',
  `text` varchar(300) DEFAULT NULL COMMENT '原因',
  `shoucang` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `downnum` int unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `tui` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2推荐',
  `t_time` int NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `pdf` varchar(300) DEFAULT NULL COMMENT '本地的pdf文件',
  `pdfimg` varchar(300) DEFAULT NULL COMMENT '第一页pdf',
  `pagenum` int unsigned NOT NULL DEFAULT '0' COMMENT '页数',
  `step` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未处理；1已下载；',
  `pdfpath` varchar(300) DEFAULT NULL COMMENT '远程pdfpath',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  `week` int unsigned NOT NULL DEFAULT '0' COMMENT '周数',
  `weekguanzhu` int unsigned NOT NULL DEFAULT '0' COMMENT '本周关注',
  `looknum` int unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `num` int unsigned NOT NULL DEFAULT '0' COMMENT '页数',
  `g_time` int NOT NULL DEFAULT '0' COMMENT '操作时间',
  `free_num` int NOT NULL DEFAULT '0' COMMENT '免费页数',
  `guanjianci` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unnum` (`unnum`),
  KEY `uid` (`uid`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2523 DEFAULT CHARSET=utf8mb3 COMMENT='用户图片';

-- ----------------------------
-- Table structure for dczg_wenkudown
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkudown`;
CREATE TABLE `dczg_wenkudown` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  `ids` varchar(255) DEFAULT NULL COMMENT '素材ID',
  `time` int NOT NULL DEFAULT '0' COMMENT '今天的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3 COMMENT='素材下载';

-- ----------------------------
-- Table structure for dczg_wenkudowndc
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkudowndc`;
CREATE TABLE `dczg_wenkudowndc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL DEFAULT '0' COMMENT '素材ID',
  `ids` varchar(255) DEFAULT NULL COMMENT '素材ID',
  `time` int NOT NULL DEFAULT '0' COMMENT '今天的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`time`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COMMENT='素材下载';

-- ----------------------------
-- Table structure for dczg_wenkufenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkufenlei`;
CREATE TABLE `dczg_wenkufenlei` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int NOT NULL COMMENT '目录ID',
  `name` varchar(30) NOT NULL COMMENT '分类名',
  `lists` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `time` int NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COMMENT='文库分类';

-- ----------------------------
-- Table structure for dczg_wenkufenleirelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkufenleirelation`;
CREATE TABLE `dczg_wenkufenleirelation` (
  `mid` int unsigned NOT NULL COMMENT '目录ID',
  `iid` int unsigned NOT NULL COMMENT '文库ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='文库分类关系表';

-- ----------------------------
-- Table structure for dczg_wenkulebel
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkulebel`;
CREATE TABLE `dczg_wenkulebel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标签',
  `time` int NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COMMENT='灵感标签';

-- ----------------------------
-- Table structure for dczg_wenkumulu
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkumulu`;
CREATE TABLE `dczg_wenkumulu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '目录名',
  `time` int NOT NULL COMMENT '操作时间',
  `lists` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COMMENT='文库目录';

-- ----------------------------
-- Table structure for dczg_wenkumulurelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkumulurelation`;
CREATE TABLE `dczg_wenkumulurelation` (
  `mid` int unsigned NOT NULL COMMENT '目录ID',
  `iid` int unsigned NOT NULL COMMENT '文库ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='文库目录关系表';

-- ----------------------------
-- Table structure for dczg_wenkuthreefenlei
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkuthreefenlei`;
CREATE TABLE `dczg_wenkuthreefenlei` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mid` int NOT NULL COMMENT '上级ID',
  `name` varchar(30) NOT NULL COMMENT '分类名',
  `lists` int unsigned NOT NULL COMMENT '排序',
  `time` int NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='文库三级分类';

-- ----------------------------
-- Table structure for dczg_wenkuthreefenleirelation
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wenkuthreefenleirelation`;
CREATE TABLE `dczg_wenkuthreefenleirelation` (
  `mid` int unsigned NOT NULL COMMENT '目录ID',
  `iid` int unsigned NOT NULL COMMENT '文库ID',
  UNIQUE KEY `mid` (`mid`,`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='文库三级分类关系表';

-- ----------------------------
-- Table structure for dczg_wx_session
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wx_session`;
CREATE TABLE `dczg_wx_session` (
  `unionid` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `session_key` varchar(255) NOT NULL,
  PRIMARY KEY (`unionid`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='小程序登录';

-- ----------------------------
-- Table structure for dczg_wxmenu
-- ----------------------------
DROP TABLE IF EXISTS `dczg_wxmenu`;
CREATE TABLE `dczg_wxmenu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL COMMENT '上级ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `type` varchar(255) NOT NULL COMMENT '类型',
  `lists` int NOT NULL COMMENT '排序',
  `url` varchar(1020) NOT NULL COMMENT '链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COMMENT='微信菜单设置';

-- ----------------------------
-- Table structure for dczg_zidong
-- ----------------------------
DROP TABLE IF EXISTS `dczg_zidong`;
CREATE TABLE `dczg_zidong` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '用户发的文字',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0文字；1图片',
  `media_id` varchar(255) NOT NULL,
  `img` int NOT NULL DEFAULT '0',
  `txt` varchar(255) NOT NULL COMMENT '回复文字',
  `time` int NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COMMENT='自动回复列表';
