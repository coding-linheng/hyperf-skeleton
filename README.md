### 素材王国开发记录

[toc]

---

#### **1.待完成逻辑**

* [ ] **上传60天未通过的素材和文库删除**
* [X] **签到 2021年10月24日19:12:10**
* [ ] **活动处理**
* [ ] **微信扫码登录**
* [ ] **支付对接**
* [X] **右上角消息通知整合 2021年10月22日17:38:50**
* [X] **下载记录 2021年10月22日17:38:50**
* [ ] **举报投诉**

#### **2.后台新增待完成逻辑**

* [ ] **用户主页封面审核**
* [ ] **首页用户推荐设置-素材，灵感类**
* [ ] **后台效果图变更相关项目**

#### **2.素材王国数据表修改记录**

- 新增sql

```sql
ALTER TABLE dczg_shouling
    ADD COLUMN `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url';
ALTER TABLE dczg_shouling
    ADD COLUMN `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id';
ALTER TABLE dczg_shouling
    ADD COLUMN `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id';
ALTER TABLE dczg_shouling
    ADD COLUMN `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等';
ALTER TABLE dczg_shouling
    ADD COLUMN `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间';
```

---

```sql
ALTER TABLE dczg_shouimg
    ADD COLUMN `img_url` varchar(100) DEFAULT NULL COMMENT '图片预览url';
ALTER TABLE dczg_shouimg
    ADD COLUMN `img_uid` int NOT NULL DEFAULT '0' COMMENT '图片所属的用户id';
ALTER TABLE dczg_shouimg
    ADD COLUMN `album_id` int NOT NULL DEFAULT '0' COMMENT '专辑Id';
ALTER TABLE dczg_shouimg
    ADD COLUMN `remark` varchar(100) DEFAULT NULL COMMENT '收藏备注，来源等';
ALTER TABLE dczg_shouimg
    ADD COLUMN `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间';
```

```sql
ALTER TABLE dczg_img
    ADD COLUMN `mulu_id` int NOT NULL COMMENT '分类目录id';
ALTER TABLE dczg_img
    ADD COLUMN `geshi_id` int NOT NULL COMMENT '分类目录id';
```

```sql
ALTER TABLE dczg_wenku
    ADD COLUMN `free_num` int NOT NULL DEFAULT '0' COMMENT '免费页数';
ALTER TABLE dczg_wenku
    ADD COLUMN `guanjianci` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词';
```

```sql
ALTER TABLE dczg_uservip
    ADD COLUMN `id` int NOT NULL AUTO_INCREMENT;
```

---

- 新增数据表

```sql
CREATE TABLE `dczg_keywords_type`
(
    `id`   int          NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL COMMENT '名称',
    `must` int          NOT NULL COMMENT '是否必选',
    `time` int          NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3 COMMENT ='关键词类别';
```

---

```sql
CREATE TABLE `dczg_keywords`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `type`        int          NOT NULL COMMENT '类型',
    `name`        varchar(255) NOT NULL COMMENT '名称',
    `create_time` int          NOT NULL COMMENT '创建时间',
    `update_time` int          NOT NULL COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3 COMMENT ='关键词';
```

```sql
ALTER TABLE dczg_userdata
    ADD COLUMN `sucai_tui` int NOT NULL DEFAULT '0' COMMENT '素材推荐用户';
ALTER TABLE dczg_userdata
    ADD COLUMN `album_tui` int NOT NULL DEFAULT '0' COMMENT '灵感推荐用户';
ALTER TABLE dczg_userdata
    ADD COLUMN `cover_img` varchar(255) NOT NULL DEFAULT '' COMMENT '个人主页封面图';
ALTER TABLE dczg_userdata
    ADD COLUMN `cover_img_tmp` varchar(255) NOT NULL DEFAULT '' COMMENT '个人主页封面图临时图，待审核通过';
ALTER TABLE dczg_userdata
    ADD COLUMN `cover_img_msg` varchar(255) NOT NULL DEFAULT '' COMMENT '未审核通过原因';
ALTER TABLE dczg_userdata
    ADD COLUMN `cover_img_status` int NOT NULL DEFAULT '0' COMMENT '封面状态，0无需审核，1有待审核，2审核拒绝，3审核通过';

```

```sql
ALTER TABLE dczg_guanzhu
    ADD COLUMN `img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '专辑图片封面预览url';
ALTER TABLE dczg_guanzhu
    ADD COLUMN `album_uid` varchar(255) NOT NULL DEFAULT '' COMMENT '专辑所属的用户id';
ALTER TABLE dczg_guanzhu
    ADD COLUMN `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '收藏备注，来源等';
ALTER TABLE dczg_guanzhu
    ADD COLUMN `c_time` int unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间';
```

```sql
CREATE TABLE `dczg_signin`
(
    `id`               int NOT NULL AUTO_INCREMENT,
    `user_id`          int NOT NULL COMMENT '用户id',
    `days`             int NOT NULL COMMENT '连续签到天数',
    `total_days`       int NOT NULL COMMENT '累计签到天数',
    `last_signin_time` int NOT NULL COMMENT '最后一次签到时间',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3 COMMENT ='签到活动表';
```

```sql
CREATE TABLE `dczg_signin_log`
(
    `id`          int NOT NULL AUTO_INCREMENT,
    `user_id`     int NOT NULL COMMENT '用户id',
    `sign_gift`   int NOT NULL COMMENT '签到奖励',
    `sign_time`   int NOT NULL COMMENT '签到时间',
    `type`        int NOT NULL COMMENT '1-签到  2-补签',
    `create_time` int NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3 COMMENT ='签到日志表';
```
