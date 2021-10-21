### 素材王国开发记录
[toc]

---

#### **1.待完成逻辑**

* [ ] **上传60天未通过的素材和文库删除**
* [ ] **签到**
* [ ] **活动处理**
* [ ] **微信扫码登录**
* [ ] **支付对接**
* [ ] **右上角消息通知整合**
* [ ] **下载记录**

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
```


