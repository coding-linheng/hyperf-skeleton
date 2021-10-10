# Introduction

This is a skeleton application using the Hyperf framework. This application is meant to be used as a starting place for those looking to get their feet wet with Hyperf Framework.

# Requirements

Hyperf has some requirements for the system environment, it can only run under Linux and Mac environment, but due to the development of Docker virtualization technology, Docker for Windows can also be used as the running environment under Windows.

The various versions of Dockerfile have been prepared for you in the [hyperf/hyperf-docker](https://github.com/hyperf/hyperf-docker) project, or directly based on the already built [hyperf/hyperf](https://hub.docker.com/r/hyperf/hyperf) Image to run.

When you don't want to use Docker as the basis for your running environment, you need to make sure that your operating environment meets the following requirements:  

 - PHP >= 7.3
 - Swoole PHP extension >= 4.5，and Disabled `Short Name`
 - OpenSSL PHP extension
 - JSON PHP extension
 - PDO PHP extension （If you need to use MySQL Client）
 - Redis PHP extension （If you need to use Redis Client）
 - Protobuf PHP extension （If you need to use gRPC Server of Client）

# Installation using Composer

The easiest way to create a new Hyperf project is to use Composer. If you don't have it already installed, then please install as per the documentation.

To create your new Hyperf project:

$ composer create-project hyperf/hyperf-skeleton path/to/install

Once installed, you can run the server immediately using the command below.

$ cd path/to/install
$ php bin/hyperf.php start

This will start the cli-server on port `9501`, and bind it to all network interfaces. You can then visit the site at `http://localhost:9501/`

which will bring up Hyperf default home page.


#新增sql
ALTER TABLE dczg_shouling ADD COLUMN `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url';
ALTER TABLE dczg_shouling ADD COLUMN `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id';
ALTER TABLE dczg_shouling ADD COLUMN `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id';
ALTER TABLE dczg_shouling ADD COLUMN `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等';
ALTER TABLE dczg_shouling ADD COLUMN `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间';

ALTER TABLE dczg_shouimg ADD COLUMN `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url';
ALTER TABLE dczg_shouimg ADD COLUMN `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id';
ALTER TABLE dczg_shouimg ADD COLUMN `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id';
ALTER TABLE dczg_shouimg ADD COLUMN `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等';
ALTER TABLE dczg_shouimg ADD COLUMN `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间';

