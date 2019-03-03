/**
 * 登录表
 */
create table ks_admin_user(
  id int(10) unsigned auto_increment primary key,
  username char(30) default '' comment '用户名',
  password char(32) default '' comment '密码',
  token char(32) default '' comment 'token效验',
  expired_at timestamp comment 'token过期时间',
  index(username),
  unique(token)
)ENGINE=MYISAM default charset=utf8 collate=utf8_general_ci;

/**
 * 展示表
 */
CREATE table ks_user(
  id int(10) unsigned auto_increment primary key,
  username char(30) default '' comment '用户名',
  password char(32) default '' comment '密码',
  realname char(40) default '' comment '用户真实姓名',
  sex enum('男','女','未知') default '男' comment '用户性别'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

#唯一联合索引
alter table ks_user add unique(username,realname);

#普通索引
alter table ks_user add index(sex);