create table ks_admin_user(
  id int(10) unsigned auto_increment primary key,
  username char(30) default '' comment '用户名',
  password char(30) default '' comment '密码',
  token char(32) default '' comment 'token效验',
  expired_at timestamp comment 'token过期时间'
)ENGINE=MYISAM default charset=utf8 collate=utf8_general_ci;