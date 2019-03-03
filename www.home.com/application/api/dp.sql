-- nav 导航栏表 --

create table bp_nav(
   id int(10) unsigned auto_increment primary key,
   name varchar(30) default '' comment '导航名称',
   pid int(10) unsigned default 0 comment '父类id',
   url varchar(60) default '#' comment 'url地址'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- ad 广告表 --

create table bp_ad(
  id int(10) unsigned auto_increment primary key,
  name varchar(20) default '' comment '广告名称',
  cate_id enum('1','2','3','4') comment '广告位置 1 首页banner 2 首页其他',
  image_url varchar(60) default '' comment '图片地址',
  url varchar(60) default '#' comment 'url地址'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- f_link 友情链接表 --

create table bp_f_link(
  id int(10) unsigned auto_increment primary key,
  name varchar(20) default '' comment '友情链接名称',
  pid int(10) unsigned default 0 comment '父类id',
  url varchar(60) default '#' comment 'url地址'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- user 用户表 --

create table bp_user(
  id int(10) unsigned auto_increment primary key,
  username varchar(30) default '' comment '用户名称',
  phone char(20) default '0' comment '手机号',
  email char(60) default '' comment '用户邮箱',
  password char(32) default '' comment '密码',
  created_at timestamp default current_timestamp comment '创建时间',
  updated_at timestamp default current_timestamp on update current_timestamp comment '更新时间',
  unique(phone)
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- goods 商品表 --

create table bp_goods(
  id int(10) unsigned auto_increment primary key,
  goods_name varchar(60) default '' comment '商品名称',
  cate_id int(10) default 0 comment '商品分类id',
  type char(20) default '0' comment '型号',
  tags tinyint(4) default 0 comment '商品标签',
  store_num int(10) default 0 comment '商品库存',
  score int(10) default 0 comment '奖励积分',
  shop_price decimal(10,2) default 0.00 comment '市场价格',
  market_price decimal(10,2) default 0.00 comment '本店价格',
  level tinyint(4) default 1 comment '商品热度星星',
  goods_desc text comment '商品描述',
  created_at timestamp default current_timestamp comment '创建时间',
  updated_at timestamp default current_timestamp on update current_timestamp comment '更新时间'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- goods_img 商品图库表 --

create table bp_goods_img(
  id int(10) unsigned auto_increment primary key,
  goods_id int(10) unsigned comment '商品id',
  image_url varchar(60) default '' comment '图片地址',
  source_image_url varchar(60) default '' comment '原图片地址'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- goods_collection 商品收藏记录表 --

create table bp_goods_collection(
  id int(10) unsigned auto_increment primary key,
  user_id int(10) unsigned comment '用户id',
  goods_id int(10) unsigned comment '商品id'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- cart 购物车 --

create table bp_cart(
  id int(10) unsigned auto_increment primary key,
  user_id int(10) unsigned comment '用户id',
  goods_id int(10) unsigned comment '商品id',
  goods_num int(10) unsigned comment '商品数量',
  unit_price decimal(10,2) comment '商品单价',
  total_price decimal(10,2) comment '商品总价'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- order 商品的订单表 --

create table bp_order(
  id int(10) unsigned auto_increment primary key,
  order_no char(32) comment '订单号32位',
  user_id int(10) unsigned comment '用户id',
  link_user char(20) comment '联系人姓名',
  link_phone char(12) comment '联系人手机号',
  address varchar(150) comment '收货人地址',
  esm_code int(10) comment '邮编',
  shipping_type enum('1','2') default '1' comment '货运方式',
  pay_type enum('1','2') default '1' comment '支付方式',
  goods_amount decimal(10,2) default 0.00 comment '商品总额',
  order_fee decimal(10,2) default 0.00 comment '订单费用',
  order_amount decimal(10,2) default 0.00 comment '订单总额',
  created_at timestamp default current_timestamp comment '创建时间',
  updated_at timestamp default current_timestamp on update current_timestamp comment '更新时间',
  unique(order_no)
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;

-- comment 商品评论表 --

create table bp_goods_comment(
  id int(10) unsigned auto_increment primary key,
  user_id int(10) unsigned comment '用户id',
  goods_id int(10) unsigned comment '商品id',
  comment_type enum('1','2') default '1' comment '评论类型',
  content int(10) unsigned comment '评论内容',
  created_at timestamp default current_timestamp comment '创建时间',
  updated_at timestamp default current_timestamp on update current_timestamp comment '更新时间'
)engine=MYISAM default charset=utf8 collate=utf8_general_ci;