alter table cy_article add uid int not null default 0 comment'所属用户' after cid;
alter table cy_article add recycle tinyint(1) not null default 0 comment'回收站' after status;
alter table cy_admin add group_id tinyint(1) not null default 0 COMMENT'用户分组' after path;
alter table cy_category add share tinyint(1) not null default 0 comment'会员共享' after isShow;