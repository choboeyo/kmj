DROP TABLE IF EXISTS `wb_attach`;
CREATE TABLE `wb_attach` (
  `att_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `att_target_type` enum('QNA','BOARD','ETC','PRODUCTS','PRODUCTS_REVIEW') NOT NULL DEFAULT 'ETC',
  `att_target` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '파일이 소속된 문서 PK',
  `att_sort` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '이미지 정렬 순서',
  `att_is_image` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '이미지 인지 여부',
  `att_origin` varchar(255) NOT NULL COMMENT '원본 파일명',
  `att_filepath` varchar(255) NOT NULL COMMENT '실제 업로드된 파일 경로',
  `att_downloads` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '해당 파일 다운로드 수',
  `att_ext` varchar(10) NOT NULL DEFAULT '' COMMENT '파일 확장자',
  `att_filesize` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '파일 크기 kb',
  `att_width` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '이미지일경우 너비',
  `att_height` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '이미지일경우 높이',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '등록자 PK',
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록시간',
  PRIMARY KEY (`att_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_banner`;
CREATE TABLE `wb_banner` (
  `ban_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bng_key` varchar(20) NOT NULL DEFAULT '',
  `ban_name` varchar(50) NOT NULL DEFAULT '',
  `ban_filepath` varchar(255) NOT NULL DEFAULT '',
  `ban_link_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `ban_link_url` varchar(50) NOT NULL DEFAULT '',
  `ban_link_type` enum('Y','N') NOT NULL DEFAULT 'N',
  `ban_status` enum('Y','H','N') NOT NULL DEFAULT 'Y',
  `ban_sort` int(10) unsigned NOT NULL DEFAULT '0',
  `ban_timer_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `ban_timer_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ban_timer_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ban_ext1` varchar(255) NOT NULL DEFAULT '',
  `ban_ext2` varchar(255) NOT NULL DEFAULT '',
  `ban_ext3` varchar(255) NOT NULL DEFAULT '',
  `ban_ext4` varchar(255) NOT NULL DEFAULT '',
  `ban_ext5` varchar(255) NOT NULL DEFAULT '',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ban_idx`),
  KEY `bng_key` (`bng_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `wb_banner_group`;
CREATE TABLE `wb_banner_group` (
  `bng_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bng_sort` int(10) unsigned NOT NULL DEFAULT 0,
  `bng_key` varchar(20) NOT NULL DEFAULT '',
  `bng_name` varchar(50) NOT NULL DEFAULT '',
  `bng_width` smallint(6) NOT NULL DEFAULT 0,
  `bng_height` smallint(6) NOT NULL DEFAULT 0,
  `bng_ext1` varchar(255) NOT NULL DEFAULT '',
  `bng_ext2` varchar(255) NOT NULL DEFAULT '',
  `bng_ext3` varchar(255) NOT NULL DEFAULT '',
  `bng_ext4` varchar(255) NOT NULL DEFAULT '',
  `bng_ext5` varchar(255) NOT NULL DEFAULT '',
  `bng_ext1_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `bng_ext2_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `bng_ext3_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `bng_ext4_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `bng_ext5_use` enum('Y','N') NOT NULL DEFAULT 'N',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(11) NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bng_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `wb_board`;
CREATE TABLE `wb_board` (
  `brd_key` varchar(20) NOT NULL DEFAULT '',
  `brd_type` enum('list','gallery','webzine') NOT NULL DEFAULT 'list',
  `brd_title` varchar(30) NOT NULL DEFAULT '',
  `brd_skin_l` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_l_m` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_v` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_v_m` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_w` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_w_m` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_c` varchar(100) NOT NULL DEFAULT '',
  `brd_skin_c_m` varchar(100) NOT NULL DEFAULT '',
  `brd_lv_list` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_lv_read` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_lv_write` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_lv_reply` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_lv_comment` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_lv_download` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_use_anonymous` enum('Y','N','A') NOT NULL DEFAULT 'N',
  `brd_category` varchar(255) NOT NULL DEFAULT '',
  `brd_use_category` enum('Y','N') NOT NULL DEFAULT 'Y',
  `brd_use_secret` enum('Y','N','A') NOT NULL DEFAULT 'Y',
  `brd_use_reply` enum('Y','N') NOT NULL DEFAULT 'Y',
  `brd_use_comment` enum('Y','N') NOT NULL DEFAULT 'Y',
  `brd_display_time` enum('sns','basic','full') NOT NULL DEFAULT 'sns',
  `brd_count_post` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_page_limit` enum('Y','N') NOT NULL DEFAULT 'Y',
  `brd_page_rows` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_page_rows_m` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_fixed_num` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_fixed_num_m` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `brd_point_write` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_point_write_flag` tinyint(4) NOT NULL DEFAULT 1,
  `brd_point_read` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_point_read_flag` tinyint(4) NOT NULL DEFAULT -1,
  `brd_point_comment` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_point_comment_flag` tinyint(4) NOT NULL DEFAULT 1,
  `brd_point_download` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_point_download_flag` tinyint(4) NOT NULL DEFAULT -1,
  `brd_point_reply` int(10) unsigned NOT NULL DEFAULT 0,
  `brd_point_reply_flag` tinyint(4) NOT NULL DEFAULT 1,
  `brd_keywords` varchar(255) NOT NULL,
  `brd_description` text NOT NULL,
  `brd_blind_nickname` enum('Y','N') NOT NULL DEFAULT 'N',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`brd_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_board_comment`;
CREATE TABLE `wb_board_comment` (
  `cmt_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cmt_num` int(10) unsigned NOT NULL DEFAULT 0,
  `cmt_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `cmt_reply` varchar(5) NOT NULL DEFAULT '',
  `brd_key` varchar(20) NOT NULL DEFAULT '',
  `post_idx` int(10) unsigned NOT NULL DEFAULT 0,
  `cmt_nickname` varchar(20) NOT NULL DEFAULT '',
  `cmt_password` char(32) NOT NULL,
  `cmt_content` text NOT NULL,
  `cmt_ip` int(10) unsigned NOT NULL DEFAULT 0,
  `cmt_status` enum('Y','N','B') NOT NULL DEFAULT 'Y',
  `cmt_mobile` enum('Y','N') NOT NULL DEFAULT 'N',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cmt_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_board_post`;
CREATE TABLE `wb_board_post` (
  `post_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_num` int(10) unsigned NOT NULL DEFAULT 0,
  `post_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `post_reply` varchar(10) NOT NULL DEFAULT '',
  `brd_key` varchar(20) NOT NULL DEFAULT '',
  `post_category` varchar(255) NOT NULL DEFAULT '' COMMENT '카테고리',
  `post_title` varchar(255) NOT NULL DEFAULT '',
  `post_thumbnail` varchar(255) NOT NULL DEFAULT '' COMMENT '썸네일 파일',
  `post_content` longtext NOT NULL,
  `post_status` enum('Y','N','B') NOT NULL DEFAULT 'Y',
  `post_nickname` varchar(20) NOT NULL DEFAULT '',
  `post_password` char(32) NOT NULL DEFAULT '',
  `post_count_comment` int(10) unsigned NOT NULL DEFAULT 0,
  `post_recent_comment` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_secret` enum('Y','N') NOT NULL DEFAULT 'N',
  `post_notice` enum('Y','N') NOT NULL DEFAULT 'N',
  `post_hit` int(10) unsigned NOT NULL DEFAULT 0,
  `post_mobile` enum('Y','N') NOT NULL DEFAULT 'N',
  `post_ip` int(10) unsigned NOT NULL DEFAULT 0,
  `post_keywords` varchar(255) NOT NULL,
  `post_ext1` text NOT NULL,
  `post_ext2` text NOT NULL,
  `post_ext3` text NOT NULL,
  `post_ext4` text NOT NULL,
  `post_ext5` text NOT NULL,
  `post_ext6` text NOT NULL,
  `post_ext7` text NOT NULL,
  `post_ext8` text NOT NULL,
  `post_ext9` text NOT NULL,
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`post_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_config`;
CREATE TABLE `wb_config` (
  `cfg_key` varchar(30) NOT NULL,
  `cfg_value` text NOT NULL,
  PRIMARY KEY (`cfg_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

insert  into `wb_config`(`cfg_key`,`cfg_value`) values
  ('agreement_privacy','개인정보 취급방침을 입력해주세요'),
  ('agreement_site','사이트 이용약관을 입력해주세요'),
  ('allow_host','www.youtube.com\nwww.youtube-nocookie.com\nmaps.google.co.kr\nmaps.google.com\nflvs.daum.net\nplayer.vimeo.com\nsbsplayer.sbs.co.kr\nserviceapi.rmcnmv.naver.com\nserviceapi.nmv.naver.com\nwww.mgoon.com\nvideofarm.daum.net\nplayer.sbs.co.kr\nsbsplayer.sbs.co.kr\nwww.tagstory.com\nplay.tagstory.com\nflvr.pandora.tv'),
  ('extra_tag_meta',''),
  ('extra_tag_script',''),
  ('channel_facebook',''),
  ('channel_instagram',''),
  ('channel_itunes',''),
  ('channel_naver_blog',''),
  ('channel_naver_cafe',''),
  ('channel_naver_pholar',''),
  ('channel_naver_post',''),
  ('channel_naver_storefarm',''),
  ('channel_playstore',''),
  ('channel_type','Person'),
  ('default_language','ko'),
  ('deny_id','admin,administrator,webmaster,sysop,manager,root,su,guest,super'),
  ('deny_ip',''),
  ('deny_nickname','admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객'),
  ('deny_word','18아,18놈,18새끼,18년,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ'),
  ('email_send_address','email@address.com'),
  ('icode_userid',''),
  ('icode_userpw',''),
  ('point_member_login','0'),
  ('point_member_register','0'),
  ('point_name','포인트'),
  ('point_use','N'),
  ('site_meta_description','이곳에 사이트의 요약 설명을 입력하세요.'),
  ('site_meta_image',''),
  ('site_meta_keywords','휘파람소프트,휘파람보드'),
  ('site_subtitle','휘파람이 절로 나오는 홈페이지'),
  ('site_title','휘파람 보드'),
  ('sms_send_phone',''),
  ('statics_updated', '0'),
  ('use_localize', 'Y'),
  ('accept_languages', 'ko'),
  ('social_facebook_appid', ''),
  ('social_facebook_appsecret', ''),
  ('social_facebook_use','N'),
  ('social_google_clientid', ''),
  ('social_google_clientsecret', ''),
  ('social_google_use','N'),
  ('social_naver_clientid', ''),
  ('social_naver_clientsecret', ''),
  ('social_naver_use','N'),
  ('social_kakao_clientid', ''),
  ('social_kakao_use','N'),
  ('skin_members', 'basic'),
  ('skin_members_m', 'basic'),
  ('skin_shop', 'basic'),
  ('skin_shop_m', 'basic'),
  ('skin_shop_list', 'basic'),
  ('skin_shop_list_m', 'basic'),
  ('company_name', '휘파람소프트'),
  ('company_ceo', '홍길동'),
  ('company_tel', '02-1234-5678'),
  ('company_fax', '02-1234-5679'),
  ('company_biznum', '123-456-78910'),
  ('company_shopnum', '2022-서울강남-1234'),
  ('company_privacy_name', '김정보'),
  ('company_privacy_email', 'info@naver.com'),
  ('company_address', '(13112) 서울시 강남구 강남대로 123 3층'),
  ('google_recaptcha_site_key', ''),
  ('google_recaptcha_secret_key', ''),
  ('shop_delivery_company','우체국'),
  ('shop_delivery_type','차등'),
  ('shop_delivery_cost','[{"price":20000, "sc_cost":4000},{"price":30000, "sc_cost":3000},{"price":50000,"sc_cost":2000}]'),
  ('shop_delivery_info','상품 배송 안내를 입력해주세요'),
  ('shop_refund_info','교환/반품안내를 입력해주세요'),
  ('shop_pg_service','kcp'),
  ('shop_iche_use','Y'),
  ('shop_card_pay_use','Y'),
  ('shop_hp_pay_use','Y'),
  ('shop_vbank_use','Y'),
  ('shop_bank_account',''),
  ('shop_bank_use','Y'),
  ('shop_kcp_site_code',''),
  ('shop_use_samsungpay','N'),
  ('shop_use_global_naverpay','N'),
  ('shop_sms_order_complete','N'),
  ('shop_sms_bank_info','N'),
  ('shop_sms_pay_complete','N'),
  ('shop_sms_delivery','N'),
  ('shop_sms_type','SMS'),
  ('shop_sms_order_complete_c',''),
  ('shop_sms_bank_info_c',''),
  ('shop_sms_pay_complete_c',''),
  ('shop_sms_delivery_c',''),
  ('shop_sms_order_complete_cc','안녕하세요 #{주문자}님,\r\n소중한 주문이 접수완료되었습니다.\r\n\r\n- 주문번호: #{주문번호}\r\n- 주문상품: #{주문상품}\r\n- 금액: #{주문금액}원\r\n'),
  ('shop_sms_bank_info_cc','안녕하세요 #{주문자}님,\r\n입금계좌 안내드립니다.\r\n감사합니다.\r\n\r\n- 주문번호: #{주문번호}\r\n- 주문상품: #{주문상품}\r\n- 계좌번호: #{계좌번호}\r\n- 금액: #{주문금액}원\r\n'),
  ('shop_sms_pay_complete_cc','안녕하세요 #{주문자}님\r\n\r\n주문하신 상품의 입금확인 되었습니다.\r\n감사합니다.\r\n\r\n- 주문번호: #{주문번호}\r\n- 주문상품: #{주문상품}'),
  ('shop_sms_delivery_cc','안녕하세요 #{주문자}님\r\n\r\n주문하신 상품이 발송처리 되었습니다.\r\n\r\n- 주문번호: #{주문번호}\r\n- 주문상품: #{주문상품}\r\n- 택배사 : #{택배사}\r\n- 운송장번호: #{운송장번호}'),
  ('shop_sms_delivery_button','Y'),
  ('shop_nc_k_plusFriend',''),
  ('shop_nc_k_sid',''),
  ('shop_nc_s_sid',''),
  ('shop_nc_s_callback',''),
  ('shop_nc_k_accessKey',''),
  ('shop_nc_k_accessSecret',''),
  ('shop_inicis_mid',''),
  ('shop_kakaopay_use','N'),
  ('shop_pay_test','N');

DROP TABLE IF EXISTS `wb_faq`;
CREATE TABLE `wb_faq` (
  `faq_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fac_idx` varchar(20) NOT NULL,
  `faq_status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `faq_title` varchar(255) NOT NULL,
  `faq_content` mediumtext DEFAULT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`faq_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `wb_faq_category`;
CREATE TABLE `wb_faq_category` (
  `fac_idx` varchar(20) NOT NULL,
  `fac_title` varchar(255) NOT NULL,
  `fac_status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `fac_count` int(10) unsigned NOT NULL DEFAULT 0,
  `sort` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`fac_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_localize`;
CREATE TABLE `wb_localize` (
  `loc_key` varchar(60) NOT NULL,
  `loc_value_ko` text NOT NULL,
  `loc_value_en` text NOT NULL,
  `loc_value_ja` text NOT NULL,
  `loc_value_zh-hans` text NOT NULL,
  `loc_value_zh-hant` text NOT NULL,
  PRIMARY KEY (`loc_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

insert  into `wb_localize`(`loc_key`,`loc_value_ko`,`loc_value_en`,`loc_value_ja`,`loc_value_zh-hans`,`loc_value_zh-hant`) values
  ('게시판/comment/content_required','댓글 내용을 입력하세요.','Please enter your comment.','コメントの内容を入力してください。','请输入您的评论。','請輸入您的評論。'),
  ('게시판/comment/form_content','댓글 내용.','Comments.','コメントの内容。','评论细节。','評論細節。'),
  ('게시판/comment/nickname_required','작성자 닉네임을 입력하세요.','Please enter author nickname.','著者ニックネームを入力してください。','请输入您的作者昵称。','請輸入您的作者暱稱。'),
  ('게시판/comment/password_required','댓글 비밀번호를 입력하세요.','Please enter post password.','コメントパスワードを入力してください。','请输入您的评论密码','請輸入您的評論密碼'),
  ('게시판/form/mem_nickname','작성자 닉네임','Author Nickname','投稿者ニックネーム','作者昵称','作者暱稱'),
  ('게시판/form/password','비밀번호','Password of the post','パスワード','密码','密碼'),
  ('게시판/form/post_content','게시글 내용','Contents of the post','投稿内容','帖子的内容','帖子的內容'),
  ('게시판/form/post_title','게시글 제목','Title of the post','スレッドタイトル','帖子的标题','帖子的標題'),
  ('게시판/latest/not_exist_skin','지정한 스킨이 존재하지 않습니다.','The specified skin does not exist.','指定されたスキンが存在しません。','指定的外观不存在。','指定的外觀不存在。'),
  ('게시판/latest/not_set_board','게시판이 설정되지 않았습니다.','The Board is not set.','掲示板が設定されていません。','公告板未设置。','公告板未設置。'),
  ('게시판/latest/not_set_skin','스킨이 설정되지 않았습니다.','The Skin is not set.','スキンが設定されていません。','皮肤尚未设置。','皮膚尚未設置。'),
  ('게시판/msg/cannot_delete_guest_comment','비회원이 작성한 댓글은 관리자만 삭제가능합니다.','Only comments made by nonmembers can be deleted by the administrator.','非会員が作成したコメントは、管理者だけが削除できます。','只有非成员发表的评论才能被管理员删除。','只有非成員發表的評論才能被管理員刪除。'),
  ('게시판/msg/cant_delete_because_child','답글이 달린 게시물을 삭제할수 없습니다.','You can not delete posts with replies.','返信が付いた記事を削除することができません。','您无法删除包含回复的帖子。','您無法刪除包含回复的帖子。'),
  ('게시판/msg/comment_delete_success','댓글을 삭제하였습니다.','Your comment has been deleted.','コメントを削除しました。','您的评论已被删除。','您的評論已被刪除。'),
  ('게시판/msg/comment_failed','댓글 작성에 실패하였습니다.','Failed to comment.','コメントの作成に失敗しました。','无法发表评论','無法發表評論'),
  ('게시판/msg/comment_modify_success','댓글 정보를 수정하였습니다.','We have modified your comment.','コメント情報を修正しました。','我们修改了您的评论信息。','我們修改了您的評論信息。'),
  ('게시판/msg/comment_modify_unauthorize','해당 댓글을 수정할 권한이 없습니다.','You do not have permission to edit this comment.','このコメントを変更する権限がありません。','您无权编辑此评论。','您無權編輯此評論。'),
  ('게시판/msg/comment_success','새로운 댓글을 작성하였습니다.','You have created a new comment.','新しいコメントを作成しました。','您已创建新评论。','您已創建新評論。'),
  ('게시판/msg/comment_unauthorize','해당 게시판에서 댓글을 작성할 권한이 없습니다.','You do not have permission to comment on this board.','この掲示板でコメントを作成する権限がありません。','您无权评论此主板。','您無權評論此主板。'),
  ('게시판/msg/delete_failed','게시글 삭제에 실패하였습니다.','Failed to delete post.','スレッドの削除に失敗しました。','无法删除帖子。','無法刪除帖子。'),
  ('게시판/msg/delete_success','게시글 삭제에 성공하였습니다.','The post was deleted successfully.','スレッドの削除に成功しました。','该帖子已成功删除。','該帖子已成功刪除。'),
  ('게시판/msg/download_unauthorize','해당 첨부파일을 다운로드할 권한이 없습니다.','You do not have permission to download the attachment.','添付ファイルをダウンロードする権限がありません。','您无权下载附件。','您無權下載附件。'),
  ('게시판/msg/invalid_access','잘못된 접근입니다.','Invalid request.','不適切なアプローチです。','错误的方法。','錯誤的方法。'),
  ('게시판/msg/invalid_attach_file','존재하지 않는 파일이거나 삭제된 파일입니다.','A file that does not exist or has been deleted.','存在しないファイルであるか、削除されたファイルです。','一个不存在或已被删除的文件。','一個不存在或已被刪除的文件。'),
  ('게시판/msg/invalid_comment','존재하지 않는 댓글이거나 이미 삭제된 댓글입니다.','This comment does not exist or has been deleted.','存在しないコメントまたは既に削除されたコメントです。','此评论不存在或已被删除。','此評論不存在或已被刪除。'),
  ('게시판/msg/invalid_password','비밀번호가 맞지 않습니다.','The password is incorrect.','パスワードが一致しません。','密码不正确。','密碼不正確。'),
  ('게시판/msg/invalid_post','존재하지 않는 게시물이거나 이미 삭제된 게시물입니다.','This post does not exist or is already deleted.','存在しない記事であるか、既に削除された記事です。','这篇文章不存在或已被删除。','這篇文章不存在或已被刪除。'),
  ('게시판/msg/list_unauthorize','해당 게시판에 접근할수있는 권한이 없습니다.','You do not have permission to access on this board.','この掲示板にアクセスできる権限がありません。','您无权访问此主板。','您無權訪問此主板。'),
  ('게시판/msg/modify_failed','게시글 수정에 실패하였습니다.','Post edit failed.','スレッドの修正に失敗しました。','发布编辑失败。','發布編輯失敗。'),
  ('게시판/msg/modify_require_login','글을 수정이나 삭제하려면 로그인이 필요합니다.','Login is required to edit or delete posts.','文を修正や削除するには、ログインが必要です。','需要登录才能编辑或删除帖子。','需要登錄才能編輯或刪除帖子。'),
  ('게시판/msg/modify_unauthorize','해당 글의 수정이나 삭제할 권한이 없습니다.','You do not have permission to edit or delete this post.','この記事を編集や削除する権限がありません。','您无权修改或删除此信息。','您無權修改或刪除此信息。'),
  ('게시판/msg/not_exist','존재하지 않는 게시판입니다.','Invalid Board','存在しない掲示板です。','它不存在。','它不存在。'),
  ('게시판/msg/read_unauthorize','해당 글을 읽을 권한이 없습니다.','You do not have permission to read this post.','この記事を読んで権限がありません。','您无权阅读此文章。','您無權閱讀此文章。'),
  ('게시판/msg/recaptcha_failed','스팸등록 방지 인증에 실패하였습니다.올바른 경로로 글을 작성해주세요','Spam registration prevention verification failed.Please fill in the correct path.','スパム登録対策認証に失敗しました。正しいパスに文を入力してください','垃圾邮件注册预防认证失败，请填写正确的路径','垃圾郵件註冊預防認證失敗，請填寫正確的路徑'),
  ('게시판/msg/reply_unauthorize','해당 게시판에서 답글을 달 권한이 없습니다.','You do not have permission to reply from this board.','この掲示板で返信を月権限がありません。','您无权回复此董事会。','您無權回复此董事會。'),
  ('게시판/msg/write_failed','게시글 등록에 실패하였습니다.','Post registration failed.','スレッドの登録に失敗しました。','帖子注册失败。','帖子註冊失敗。'),
  ('게시판/msg/write_success','게시글 등록에 성공하였습니다.','You have successfully registered your post.','スレッドの登録に成功しました。','你的帖子成功了。','你的帖子成功了。'),
  ('게시판/msg/write_unauthorize','해당 게시판에 글을 작성할 수 있는 권한이 없습니다.','You do not have permission to write on this board.','この掲示板に文を作成する権限がありません。','您无权在此主板上撰写。','您無權在此主板上撰寫。'),
  ('공통/msg/invalid_access','잘못된 접근입니다.','Invalid Access.','不適切なアプローチです。','错误的方法。','錯誤的方法。'),
  ('공통/msg/login_required','로그인이 필요한 페이지입니다.','This page requires login.','ログインが必要なページです。','此页面需要登录。','此頁面需要登錄。'),
  ('공통/msg/server_error','서버 오류가 발생하였습니다.','A server error has occurred.','サーバーエラーが発生しました。','发生服务器错误。','發生服務器錯誤。'),
  ('공통/search/search_more','검색 결과 더보기','More search result','検索結果見る','更多结果','更多結果'),
  ('공통/search/search_placeholder','검색어를 입력하세요','Search value...','検索用語を入力してください','输入您的搜索字词','輸入您的搜索字詞'),
  ('공통/search/search_result','검색 결과','Search result','検索結果','搜索结果','搜索結果'),
  ('공통/search/search_submit','검색','Search','検索','取回','取回'),
  ('공통/search/search_total','통합 검색 결과','Total Result','統合検索結果','统一的搜索结果','統一的搜索結果'),
  ('공통/search/search_txt_empty','검색어를 입력하세요','Search value is empty','検索用語を入力してください','输入您的搜索字词','輸入您的搜索字詞'),
  ('공통/time/days_ago','일 전','days ago','日前','天前','天前'),
  ('공통/time/hour_ago','시간 전','hour ago','時間前','小时前','小時前'),
  ('공통/time/minute_ago','분 전','minutes ago','分前','分钟前','分鐘前'),
  ('공통/time/second_ago','초 전','seconds ago','秒前','秒前','秒前'),
  ('팝업/button/close','닫기','Close','閉じる','关闭','關閉'),
  ('팝업/button/close_with_cookie','오늘 하루 열지 않기','Do not open today','今日一日の間に開かない','今天不要打开','今天不要打開'),
  ('회원/button/link_social','연동하기','Link','連動する','互通','互通'),
  ('회원/info/activation','휴면계정 활성화','Activate account','休眠アカウントの有効化','激活睡眠帐户','激活睡眠帳戶'),
  ('회원/info/change_photo','회원 아이콘 변경','Change Member Photo','会員のアイコンを変更','更改成员图标','更改成員圖標'),
  ('회원/info/email','이메일','E-mail','メール','电子邮件','電子郵件'),
  ('회원/info/gender','성별','Gender','性別','性别','性別'),
  ('회원/info/gender_female','여성','Female','女性','女子','女子'),
  ('회원/info/gender_male','남성','Male','男性','男性','男性'),
  ('회원/info/gender_unknown','알수없음','Unknown','不明','不明','不明'),
  ('회원/info/logcount','로그인 횟수','Sign-in count','ログイン回数','登录数','登錄數'),
  ('회원/info/login_keep','로그인 유지','Keep Sign in.','ログインを維持','保持登录状态','保持登錄狀態'),
  ('회원/info/modify','회원정보 수정','Edit Info','会員情報の変更','编辑会员资讯','編輯會員資訊'),
  ('회원/info/new_password','새 비밀번호','New Password','新しいパスワード','新密码','新密碼'),
  ('회원/info/new_password_confirm','새 비밀번호 확인','New Password Confirm','新しいパスワードの確認','确认新密码','確認新密碼'),
  ('회원/info/nickname','닉네임','Nickname','ニックネーム','绰号','綽號'),
  ('회원/info/old_password','기존 비밀번호','Current Password','既存のパスワード','现有密码','現有密碼'),
  ('회원/info/password','비밀번호','Password','パスワード','密码','密碼'),
  ('회원/info/password_change','비밀번호 변경','Change Password','パスワードの変更','更改密码','更改密碼'),
  ('회원/info/phone','연락처','Phone','コンタクト','往来','往來'),
  ('회원/info/photo','회원 아이콘','Member Photo','会員アイコン','会员图标','會員圖標'),
  ('회원/info/point','포인트','Point','ポイント','点','點'),
  ('회원/info/profile','회원정보','Profile','会員情報','会员信息','會員信息'),
  ('회원/info/recv_email','이메일 수신여부','Receive E-mail','電子メールを受信するかどうか','是否接收电子邮件','是否接收電子郵件'),
  ('회원/info/recv_sms','SMS 수신여부','Receive SMS','SMS受信するかどうか','是否收到短信','是否收到短信'),
  ('회원/info/regtime','가입일자','Sign-up Date','登録年月日','加入日期','加入日期'),
  ('회원/info/social','소셜 정보','Social Info','ソーシャル情報','社交信息','社交信息'),
  ('회원/info/userid','아이디','Username','ユーザ名','用户名','用戶名'),
  ('회원/info/withdrawals','회원 탈퇴','Withdrawals','会員脱退','退出会员','退出會員'),
  ('회원/join/agreement_required','이용약관에 동의를 하셔야 합니다.','You must accept the Terms and Conditions.','利用規約に同意をする必要があります。','您必须同意条款和条件。','您必須同意條款和條件。'),
  ('회원/join/no_valid_email_address','올바른 형식의 이메일주소가 아닙니다.','It is not a valid email address.','正しい形式の電子メールアドレスがありません。','这不是有效的电子邮件地址。','這不是有效的電子郵件地址。'),
  ('회원/join/success','회원가입이 완료되었습니다.','Sign up is complete.','会員登録が完了しました。','您的订阅已完成。','您的訂閱已完成。'),
  ('회원/join/user_email_required','사용하실 E-mail를 입력하셔야 합니다.','Please enter your email.','使用することがE-mailを入力してください。','请输入您的电子邮件地址。','請輸入您的電子郵件地址。'),
  ('회원/join/user_id_already_exists','이미 존재하는 아이디 입니다.','The ID that already exists.','既に存在しているユーザ名です。','这是一个现有的ID。','這是一個現有的ID。'),
  ('회원/join/user_id_available','사용가능한 ID 입니다.','The ID is available.','使用可能なIDです。','可用的ID。','可用的ID。'),
  ('회원/join/user_id_contains_deny_word','아이디에 사용불가능한 단어가 포함되어 있습니다.','ID contains denied words.','IDに使用できない単語が含まれています。','该ID包含不可用的单词。','該ID包含不可用的單詞。'),
  ('회원/join/user_id_required','사용하실 ID를 입력하셔야 합니다.','You must enter your ID.','使用することがIDを入力する必要があります。','你必须输入你的ID。','你必須輸入你的ID。'),
  ('회원/join/user_nickname_already_exists','이미 존재하는 닉네임 입니다.','The Nickname that already exists.','既に存在しているニックネームです。','这个昵称已经存在。','這個暱稱已經存在。'),
  ('회원/join/user_nickname_contains_deny_word','닉네임에 사용불가능한 단어가 포함되어 있습니다.','Nickname contains denied words.','ニックネームに使用できない単語が含まれています。','你的昵称包含不可用的单词。','你的暱稱包含不可用的單詞。'),
  ('회원/join/user_nickname_max_length','사용자 닉네임은 최대 20자까지 설정 가능합니다.','Nickname can be up to 20 digits.','ユーザーニックネームは最大20文字まで設定可能です。','用户昵称最多可以设置20个字符。','用戶暱稱最多可以設置20個字符。'),
  ('회원/join/user_nickname_min_length','사용자 닉네임은 최소 2자 이상 설정 가능합니다.','Nickname must be at least 2 digits.','ユーザーニックネームは、少なくとも2文字以上に設定可能です。','用户昵称可以设置为至少2个字符。','用戶暱稱可以設置為至少2個字符。'),
  ('회원/join/user_nickname_required','사용자 닉네임을 입력하셔야 합니다.','You need to enter your nickname.','ユーザーのニックネームを入力してください。','你需要输入你的昵称。','你需要輸入你的暱稱。'),
  ('회원/join/user_password_confirm_required','비밀번호를 확인해주세요.','Please check your password.','パスワードを確認してください。','请检查您的密码。','請檢查您的密碼。'),
  ('회원/join/user_password_diffrerent','비밀번호와 비밀번호 확인이 서로 다릅니다.','Password and password verification are different.','パスワードとパスワードの確認が異なります。','密码和密码验证是不同的。','密碼和密碼驗證是不同的。'),
  ('회원/join/user_password_max_length','비밀번호는 최대 20자리까지 가능합니다.','Passwords can be up to 20 digits.','パスワードは、最大20桁のまで可能です。','密码可以长达20位数字。','密碼可以長達20位數字。'),
  ('회원/join/user_password_min_length','비밀번호는 최소 6자리이상을 설정하셔야 합니다.','Password must be at least 6 digits.','パスワードは少なくとも6桁以上を設定する必要があります。','密码必须至少有6位数字。','密碼必須至少有6位數字。'),
  ('회원/join/user_password_required','비밀번호를 입력하셔야 합니다.','You must enter your password.','パスワードを入力する必要があります。','您必须输入您的密码。','您必須輸入您的密碼。'),
  ('회원/join/user_password_same','비밀번호와 비밀번호 확인이 서로 같습니다.','Password and password confirm are the same.','パスワードとパスワードの確認が同じです。','密码和密码验证是相同的。','密碼和密碼驗證是相同的。'),
  ('회원/join/user_password_suitable','비밀번호로 적합합니다.','Suitable as a password.','パスワードで適しています。','适合作为密码。','適合作為密碼。'),
  ('회원/login/already','이미 로그인 상태입니다.','You are already signed in.','すでにログイン状態です。','您已经登录。','您已經登錄。'),
  ('회원/login/only','로그인한 사용자만 접근할 수 있습니다.','Only sign-in users can access.','ログインしたユーザーだけがアクセスすることができます。','只有登录的用户才能访问。','只有登錄的用戶才能訪問。'),
  ('회원/login/password_required','로그인 비밀번호를 입력하셔야 합니다.','You must enter your login password.','ログインパスワードを入力する必要があります。','您必须输入您的登录密码。','您必須輸入您的登錄密碼。'),
  ('회원/login/success','로그인이 완료되었습니다.','Sign in is complete.','ログインが完了しました。','您的登录已完成。','您的登錄已完成。'),
  ('회원/login/userid_required','로그인 아이디를 입력하셔야 합니다.','You must enter your login ID.','ログインIDを入力してください。','您必须输入您的登录ID。','您必須輸入您的登錄ID。'),
  ('회원/login/user_denied','해당 사용자는 접근이 거부된 사용자입니다.','This user is a denied user.','そのユーザーはアクセスが拒否されたユーザーです。','此用户是被拒绝的用户。','此用戶是被拒絕的用戶。'),
  ('회원/login/user_not_exist','존재하지 않는 사용자이거나, 잘못된 비밀번호 입니다.','The user does not exist or is an incorrect password.','存在しないユーザーであるか、誤ったパスワードです。','该用户不存在或者是无效的密码。','該用戶不存在或者是無效的密碼。'),
  ('회원/msg/activation_info','회원님의 계정은 현재 장기간 미사용상태로 휴면계정으로 전환된 상태입니다. [휴면상태 해제]버튼을 클릭하여 계정을 활성화 하십시오','Your account is currently in a long-term unused state and has been transitioned to a dormant account. Click the [Activation] button to activate your account','会員のアカウントは、現在の長期間未使用の状態で休眠アカウントに変換されています。【休眠状態を解除]ボタンをクリックして、アカウントを有効にしてください。','您的帐户目前处于长期未使用状态，并已转换为休眠帐户。 点击[禁用睡眠]按钮激活您的帐户。','您的帳戶目前處於長期未使用狀態，並已轉換為休眠帳戶。 點擊[禁用睡眠]按鈕激活您的帳戶。'),
  ('회원/msg/change_photo_required','업로드할 이미지를 선택하세요.','Please select an image to upload.','アップロードする画像を選択します。','请选择一张图片上传','請選擇一張圖片上傳'),
  ('회원/msg/change_photo_success','회원 아이콘 변경을 완료하였습니다.','You have completed changing your photo.','会員のアイコンの変更を完了しました。','您已完成更改您的会员资格图标。','您已完成更改您的會員資格圖標。'),
  ('회원/msg/modify_success','회원정보가 변경되었습니다.','Your Profile has changed.','会員情報を変更しました。','您的会员资格已更改。','您的會員資格已更改。'),
  ('회원/msg/password_change_success','비밀번호 변경이 완료되었습니다. 새 비밀번호로 다시 로그인하시기 바립니다.','Your password change is complete. Please login again with your new password.','パスワードの変更が完了しました。新しいパスワードで再度ログインしてバーます。','您的密码更改已完成。 请使用您的新密码重新登录。','您的密碼更改已完成。 請使用您的新密碼重新登錄。'),
  ('회원/msg/withdrawals_info_message','회원탈퇴를 진행하기 위해서 현재 비밀번호를 입력해주세요.','Please enter your current password to proceed with membership withdrawal.','会員脱退を実行するために現在のパスワードを入力してください。','请输入您当前的密码以取消订阅。','請輸入您當前的密碼以取消訂閱。'),
  ('회원/msg/withdrawals_procced','회원탈퇴를 진행하시겠습니까?','Do you want to continue?','会員脱退を続行しますか？','你确定要取消订阅吗？','你確定要取消訂閱嗎？'),
  ('회원/msg/withdrawals_success','회원탈퇴가 완료되었습니다.','Member withdrawal is complete.','退会が完了しました。','会员退出已完成。','會員退出已完成。'),
  ('회원/outlogin/not_exist_skin','로그인 스킨을 불러올수 없습니다.','Outlogin Skin load failed.','ログインスキンを読み込むことができません。','无法加载登录皮肤。','無法加載登錄皮膚。'),
  ('회원/outlogin/not_set_skin','로그인 스킨이 지정되지 않았습니다.','Outlogin Skin is not set.','ログインスキンが指定されていません。','登录皮肤没有指定。','登錄皮膚沒有指定。'),
  ('회원/point/not_enough','필요한 포인트가 충분하지 않습니다.','Not enough point.','必要なポイントがありません。','没有足够的分数是必要的。','沒有足夠的分數是必要的。'),
  ('회원/register','회원가입','Sign Up','会員登録','报名','報名'),
  ('회원/signin','로그인','Sign In','ログイン','注册','註冊'),
  ('회원/signout','로그아웃','Sign out','ログアウト','退出','退出'),
  ('회원/social/already','해당 소셜계정의 이메일 또는, 소셜계정으로 등록된 계정이 이미 있습니다.','You already have an email with this social account or an account with a social account.','このソーシャルアカウントの電子メールまたは、ソーシャルアカウントに登録されたアカウントが既にあります。','您已经拥有包含此社交帐户的电子邮件或具有社交帐户的帐户。','您已經擁有包含此社交帳戶的電子郵件或具有社交帳戶的帳戶。'),
  ('회원/social/already_another','해당 소셜 계정은 이미 다른 아이디와 연동 되어 있습니다.','This social account is already associated with another ID.','このソーシャルアカウントは、既に他のIDと連動しています。','此社交帐户已与另一个ID关联。','此社交帳戶已與另一個ID關聯。'),
  ('회원/social/already_email','해당 소셜계정의 이메일 주소로 이미 등록된 계정이 있습니다.','You already have an account with an email address for this social account.','このソーシャルアカウントのメールアドレスで既に登録されたアカウントがあります。','您已经拥有一个包含此社交帐户的电子邮件地址的帐户。','您已經擁有一個包含此社交帳戶的電子郵件地址的帳戶。'),
  ('회원/social/already_linked','이미 연결된 소셜 계정입니다.','This is a linked social account already.','既に接続されたソーシャルアカウントです。','这是一个关联的社交帐户。','這是一個關聯的社交帳戶。'),
  ('회원/social/failed','소셜 로그인에 실패하였습니다.','Social sign-in failed.','ソーシャルログインに失敗しました。','社交登录失败。','社交登錄失敗。'),
  ('회원/social/not_set','소셜 로그인 기능이 설정되어 있지 않습니다. 해당 기능을 사용할 수 없습니다.','Social configuration is not set.','ソーシャルログイン機能が設定されていません。この機能を使用することができません。','社交登录未打开。 此功能不可用。','社交登錄未打開。 此功能不可用。'),
  ('회원/social/success_link','소셜 계정 연결에 성공하였습니다.','Your social accounts have been successfully linked.','ソーシャルアカウントの接続に成功しました。','您的社交帐户已成功关联。','您的社交帳戶已成功關聯。'),
  ('회원/status/activate_complete','휴면 상태 해제가 완료되었습니다.','Activation Complete.','休眠状態解除が完了しました。','睡眠状态已经释放。','睡眠狀態已經釋放。'),
  ('회원/status/not_dormant','휴면 상태의 회원이 아닙니다.','You are not a dormant member.','休眠状態のメンバーがありません。','你不是一个休眠会员。','你不是一個休眠會員。'),
  ('회원/status/d','로그인 금지','Banned','ログインを禁止','不要签字','不要簽字'),
  ('회원/status/h','휴면','inActive','休眠','休眠','休眠'),
  ('회원/status/n','탈퇴','withdraw','脱退','分裂国家','分裂國家'),
  ('회원/status/y','정상','Normal','通常','顶','頂');


DROP TABLE IF EXISTS `wb_member`;
CREATE TABLE `wb_member` (
  `mem_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_status` enum('Y','N','D','H') NOT NULL DEFAULT 'Y',
  `mem_userid` varchar(50) NOT NULL DEFAULT '',
  `mem_password` char(32) NOT NULL,
  `mem_nickname` varchar(20) NOT NULL DEFAULT '',
  `mem_email` varchar(50) NOT NULL DEFAULT '',
  `mem_phone` varchar(15) NOT NULL DEFAULT '',
  `mem_auth` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `mem_gender` enum('M','F','U') DEFAULT 'U',
  `mem_verfy_email` enum('Y','N') DEFAULT 'N',
  `mem_point` int(10) unsigned NOT NULL DEFAULT '0',
  `mem_recv_email` enum('Y','N') DEFAULT 'N',
  `mem_recv_sms` enum('Y','N') DEFAULT 'N',
  `mem_regtime` datetime NOT NULL,
  `mem_regip` int(10) unsigned NOT NULL DEFAULT '0',
  `mem_logtime` datetime NOT NULL,
  `mem_logip` int(10) unsigned NOT NULL DEFAULT '0',
  `mem_logcount` int(10) unsigned NOT NULL DEFAULT '0',
  `mem_photo` varchar(255) NOT NULL DEFAULT '',
  `mem_leavetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mem_bantime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mem_htime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`mem_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `wb_member_autologin`;
CREATE TABLE `wb_member_autologin` (
  `aul_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_idx` int(10) unsigned NOT NULL DEFAULT '0',
  `aul_key` char(32) NOT NULL,
  `aul_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `aul_regtime` datetime NOT NULL,
  PRIMARY KEY (`aul_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_member_auth`;
CREATE TABLE `wb_member_auth` (
  `mem_idx` int(11) NOT NULL,
  `ath_type` varchar(30) NOT NULL DEFAULT '',
  `ath_key` varchar(20) NOT NULL DEFAULT '',
PRIMARY KEY (`mem_idx`,`ath_type`,`ath_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_member_log`;
CREATE TABLE `wb_member_log` (
  `mlg_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_idx` int(10) unsigned NOT NULL DEFAULT '0',
  `mlg_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `mlg_regtime` datetime NOT NULL,
  `mlg_browser` varchar(50) NOT NULL DEFAULT '',
  `mlg_version` varchar(50) NOT NULL DEFAULT '',
  `mlg_platform` varchar(100) NOT NULL DEFAULT '',
  `mlg_is_mobile` enum('Y','N') NOT NULL DEFAULT 'N',
  `mlg_mobile` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`mlg_idx`),
  KEY `mem_idx` (`mem_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_member_point`;
CREATE TABLE `wb_member_point` (
  `mpo_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_idx` int(10) unsigned NOT NULL DEFAULT 0,
  `mpo_flag` tinyint(4) NOT NULL DEFAULT 1,
  `mpo_value` int(10) unsigned NOT NULL DEFAULT 0,
  `mpo_description` varchar(255) NOT NULL DEFAULT '',
  `target_type` enum('NONE','POST_READ','POST_WRITE','POST_LIKE','POST_ATTACH_DOWNLOAD','CMT_WRITE','CMT_LIKE','TODAY_LOGIN','JOIN') NOT NULL DEFAULT 'NONE',
  `target_idx` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`mpo_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `wb_member_social`;
CREATE TABLE `wb_member_social` (
  `soc_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `soc_provider` enum('facebook','google','naver','kakao') NOT NULL DEFAULT 'naver',
  `soc_id` varchar(100) NOT NULL DEFAULT '',
  `mem_idx` int(10) unsigned NOT NULL DEFAULT '0',
  `soc_profile` varchar(255) NOT NULL DEFAULT '',
  `soc_gender` enum('M','F','U') NOT NULL DEFAULT 'U',
  `soc_email` varchar(100) NOT NULL,
  `soc_regtime` datetime NOT NULL,
  `soc_content` text NOT NULL,
  PRIMARY KEY (`soc_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_menu`;
CREATE TABLE `wb_menu` (
  `mnu_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mnu_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `mnu_order` int(10) unsigned NOT NULL DEFAULT '0',
  `mnu_name` varchar(30) NOT NULL DEFAULT '',
  `mnu_link` varchar(255) NOT NULL DEFAULT '',
  `mnu_newtab` enum('Y','N') NOT NULL DEFAULT 'N',
  `mnu_desktop` enum('Y','N') NOT NULL DEFAULT 'Y',
  `mnu_mobile` enum('Y','N') NOT NULL DEFAULT 'Y',
  `mnu_active_key` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`mnu_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `wb_popup`;
CREATE TABLE `wb_popup` (
  `pop_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pop_title` varchar(50) NOT NULL,
  `pop_width` smallint(10) unsigned NOT NULL DEFAULT 0,
  `pop_height` smallint(10) unsigned NOT NULL DEFAULT 0,
  `pop_content` text NOT NULL,
  `pop_status` enum('Y','H','N') NOT NULL DEFAULT 'Y',
  `pop_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pop_type` enum('Y','N') NOT NULL DEFAULT 'Y',
  `pop_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pop_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_qna`;
CREATE TABLE `wb_qna` (
  `qna_idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Q&A PK',
  `qna_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'N: 삭제됨',
  `qnc_idx` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Q&A 카테고리 PK',
  `qna_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Q&A 제목',
  `qna_name` varchar(20) NOT NULL DEFAULT '' COMMENT '작성자 이름',
  `qna_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '연락처',
  `qna_email` varchar(50) NOT NULL DEFAULT '' COMMENT 'E-mail',
  `qna_password` char(64) NOT NULL DEFAULT '',
  `qna_content` text NOT NULL COMMENT '작성내용',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '회원이 작성한경우 PK',
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `qna_ans_status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '답변 등록 여부',
  `qna_ans_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '답변자 PK',
  `qna_ans_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '답변 시간',
  `qna_ans_upd_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '답변 수정자 PK',
  `qna_ans_upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '답변 수정 시간',
  `qna_ans_content` text NOT NULL COMMENT '답변 내용',
  PRIMARY KEY (`qna_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_qna_category`;
CREATE TABLE `wb_qna_category` (
  `qnc_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qnc_status` enum('Y','N') DEFAULT NULL,
  `qnc_title` varchar(50) NOT NULL DEFAULT '',
  `sort` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`qnc_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_search`;
CREATE TABLE `wb_search` (
  `sea_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sea_query` varchar(255) NOT NULL DEFAULT '',
  `sea_regtime` datetime NOT NULL,
  PRIMARY KEY (`sea_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `wb_sitemap`;
CREATE TABLE `wb_sitemap` (
  `sit_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sit_loc` varchar(255) NOT NULL DEFAULT '',
  `sit_priority` decimal(1,1) NOT NULL DEFAULT 0.5,
  `sit_changefreq` enum('daily','weekly','monthly') NOT NULL DEFAULT 'daily',
  `sit_memo` varchar(255) NOT NULL DEFAULT '',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sit_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_statics`;
CREATE TABLE `wb_statics` (
  `sta_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sta_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `sta_regtime` datetime NOT NULL,
  `sta_browser` varchar(50) NOT NULL DEFAULT '',
  `sta_version` varchar(50) NOT NULL DEFAULT '',
  `sta_is_mobile` enum('Y','N') NOT NULL DEFAULT 'N',
  `sta_mobile` varchar(50) NOT NULL DEFAULT '',
  `sta_platform` varchar(100) NOT NULL DEFAULT '',
  `sta_referrer` varchar(255) NOT NULL DEFAULT '',
  `sta_referrer_host` varchar(255) NOT NULL DEFAULT '',
  `sta_keyword` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sta_idx`),
  KEY `sta_ip` (`sta_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_statics_date`;
CREATE TABLE `wb_statics_date` (
  `std_date` date NOT NULL,
  `std_count` int(10) unsigned NOT NULL DEFAULT '0',
  `std_mobile` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`std_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_uniqid`;
Create Table `wb_uniqid` (
  `uq_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uq_ip` int(10) unsigned NOT NULL,
PRIMARY KEY (`uq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_history`;
CREATE TABLE `wb_history` (
  `his_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '연혁 PK',
  `his_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 N:삭제',
  `his_year` varchar(4) NOT NULL DEFAULT '' COMMENT '년도',
  `his_month` varchar(2) NOT NULL DEFAULT '' COMMENT '월',
  `his_content` varchar(128) NOT NULL DEFAULT '' COMMENT '내용',
  `reg_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `reg_user` int(10) unsigned NOT NULL,
  `upd_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `upd_user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`his_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_consulting`;
CREATE TABLE `wb_consulting` (
 `cst_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '상담신청 PK',
 `cst_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 N:삭제',
 `cst_step` varchar(20) NOT NULL DEFAULT '' COMMENT '단계:신청,처리중,답변완료',
 `cst_regtime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '상담신청 시간',
 `cst_name` varchar(50) NOT NULL DEFAULT '' COMMENT '신청자 성명',
 `cst_phone` varchar(50) NOT NULL DEFAULT '' COMMENT '신청자 연락처',
 `cst_email` varchar(50) NOT NULL DEFAULT '' COMMENT '신청자 이메일',
 `cst_content` text NOT NULL COMMENT '신청 내용 상세',
 `cst_ip` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '신청자 IP',
 `cst_memo_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '상담신청 메모 등록 수',
 `cst_ext1` varchar(255) NOT NULL DEFAULT '',
 `cst_ext2` varchar(255) NOT NULL DEFAULT '',
 `cst_ext3` varchar(255) NOT NULL DEFAULT '',
 `cst_ext4` varchar(255) NOT NULL DEFAULT '',
 `cst_ext5` varchar(255) NOT NULL DEFAULT '',
 `cst_ext6` varchar(255) NOT NULL DEFAULT '',
 `cst_ext7` varchar(255) NOT NULL DEFAULT '',
 `cst_ext8` varchar(255) NOT NULL DEFAULT '',
 `cst_ext9` varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`cst_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_consulting_memo`;
CREATE TABLE `wb_consulting_memo` (
  `csm_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '상담메모 PK',
  `csm_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 N:삭제',
  `cst_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '상담신청 PK',
  `csm_content` text NOT NULL COMMENT '상담메모 내용',
  `reg_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '등록자',
  `reg_datetime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록시간',
  `upd_user` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '수정자',
  `upd_datetime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '수정시간',
  PRIMARY KEY (`csm_id`),
  KEY `cst_id` (`cst_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products`;
CREATE TABLE `wb_products` (
   `prd_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `prd_status` enum('Y','H','N','T') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 N:삭제 H:숨김 T:임시등록상태',
   `prd_sell_status` enum('Y','O','D') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 O:품절 D:일시판매중지',
   `cat_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '분류 PK',
   `prd_sort` smallint(5) NOT NULL DEFAULT 0 COMMENT '출력 순서',
   `prd_type` enum('H','C') NOT NULL DEFAULT 'H' COMMENT '상품 유형 (H:현물 / C:컨텐츠)',
   `prd_hit` int(11) NOT NULL DEFAULT 0 COMMENT '상품 클릭 수',
   `prd_is_best` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'BEST 상품',
   `prd_is_hit` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '히트 상품',
   `prd_is_new` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '최신 상품',
   `prd_is_sale` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '할인 상품',
   `prd_is_recommend` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '추천 상품',
   `prd_sell_count` int(11) NOT NULL DEFAULT 0 COMMENT '상품 판매 개수',
   `prd_use_options` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '필수 선택 옵션 사용여부',
   `prd_wish_count` int(11) NOT NULL DEFAULT 0 COMMENT '찜한 목록에 담긴 개수',
   `prd_review_count` int(11) NOT NULL DEFAULT 0 COMMENT '상품 리뷰 개수',
   `prd_review_average` decimal(2,1) NOT NULL DEFAULT 0.0 COMMENT '상품 리뷰 평균 점수',
   `prd_price` int(11) NOT NULL DEFAULT 0 COMMENT '상품 가격',
   `prd_cust_price` int(11) NOT NULL DEFAULT 0 COMMENT '시중 가격',
   `prd_name` varchar(255) NOT NULL DEFAULT '' COMMENT '상품명',
   `prd_maker` varchar(255) NOT NULL DEFAULT '' COMMENT '제조원',
   `prd_origin` varchar(255) NOT NULL DEFAULT '' COMMENT '원산지',
   `prd_brand` varchar(255) NOT NULL DEFAULT '' COMMENT '브랜드',
   `prd_model` varchar(255) NOT NULL DEFAULT '' COMMENT '모델명',
   `prd_summary` varchar(255) NOT NULL DEFAULT '' COMMENT '상품에 대한 간단한 설명',
   `prd_thumbnail` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '상품 대표이미지 ID',
   `prd_content` mediumtext NOT NULL COMMENT '상품 상세 설명',
   `prd_mobile_content` mediumtext NOT NULL COMMENT '모바일용 상품 상세 설명',
   `prd_stock_qty` int(11) NOT NULL DEFAULT 0 COMMENT '재고 수량',
   `prd_noti_qty` int(11) NOT NULL DEFAULT 0 COMMENT '재고 통보 수량',
   `prd_buy_min_qty` int(11) NOT NULL DEFAULT 0 COMMENT '최소 구매 수량',
   `prd_buy_max_qty` int(11) NOT NULL DEFAULT 0 COMMENT '최대 구매 수량',
   `prd_extra_info` text NOT NULL,
   `prd_item_group` varchar(50) NOT NULL DEFAULT '' COMMENT '전자상거래 명시를 위한 상품군',
   `prd_item_options` text NOT NULL COMMENT '아이템 옵션',
   `prd_sc_type` enum('','무료','조건부무료','유료','수량별') NOT NULL DEFAULT '' COMMENT '배송비 유형',
   `prd_sc_method` enum('','선불','착불','사용자선택') NOT NULL DEFAULT '선불' COMMENT '배송비 결제',
   `prd_sc_price` int(11) NOT NULL DEFAULT 0 COMMENT '기본배송비',
   `prd_sc_minimum` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '배송비 상세조건 주문금액',
   `prd_sc_qty` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '배송시 상세조건 수량',
   `reg_user` int(11) NOT NULL DEFAULT 0 COMMENT '상품을 올린 회원 PK',
   `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '상품을 입력 시간',
   `upd_user` int(11) NOT NULL DEFAULT 0 COMMENT '상품을 수정한 회원 PK',
   `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '상품 최종 수정 시간',
   `prd_extra_1` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 1',
   `prd_extra_2` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 2',
   `prd_extra_3` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 3',
   `prd_extra_4` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 4',
   `prd_extra_5` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 5',
   `prd_extra_6` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 6',
   `prd_extra_7` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 7',
   `prd_extra_8` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 8',
   `prd_extra_9` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 9',
   `prd_extra_10` varchar(255) NOT NULL DEFAULT '' COMMENT '여분필드 내용 10',
   PRIMARY KEY (`prd_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_category`;
CREATE TABLE `wb_products_category` (
    `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
    `cat_parent_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '상위 카테고리 PK',
    `cat_status` enum('Y','N','H') NOT NULL DEFAULT 'Y' COMMENT 'Y:정상 N:삭제 H:숨김',
    `cat_sort` smallint(5) unsigned NOT NULL DEFAULT 0,
    `cat_title` varchar(64) NOT NULL DEFAULT '' COMMENT '카테고리 이름',
    `cat_skin` varchar(64) NOT NULL DEFAULT '' COMMENT 'PC의 스킨 파일',
    `cat_skin_m` varchar(64) NOT NULL DEFAULT '' COMMENT '모바일의 스킨 파일',
    `cat_use_paging` enum('Y','N','T') NOT NULL DEFAULT 'Y' COMMENT '페이징 사용여부 T:쇼핑몰설정 기본 사용',
    `cat_page_rows` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '한번에 표시할 아이템 수',
    `cat_product_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '등록된 상품 수',
    `reg_user` int(10) unsigned NOT NULL DEFAULT 0,
    `reg_datetime` datetime NOT NULL DEFAULT current_timestamp(),
    `upd_user` int(10) unsigned NOT NULL DEFAULT 0,
    `upd_datetime` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_options`;
CREATE TABLE `wb_products_options` (
   `opt_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `prd_idx` int(11) NOT NULL DEFAULT 0 COMMENT '상품 PK',
   `opt_code` varchar(255) NOT NULL DEFAULT '' COMMENT '정렬 순서',
   `opt_subject` varchar(255) NOT NULL DEFAULT '',
   `opt_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '옵션 출력 여부',
   `opt_type` enum('detail','addition') NOT NULL DEFAULT 'detail' COMMENT '옵션 유형',
   `opt_add_price` int(11) NOT NULL DEFAULT 0 COMMENT '옵션 금액',
   `opt_stock_qty` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '현재 재고',
   `opt_noti_qty` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '알림 재고',
   PRIMARY KEY (`opt_idx`),
   KEY `prd_idx` (`prd_idx`),
   KEY `opt_code` (`opt_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_qa`;
CREATE TABLE `wb_products_qa` (
  `qa_idx` int(11) NOT NULL AUTO_INCREMENT,
  `prd_idx` int(11) NOT NULL DEFAULT 0 COMMENT '상품 PK',
  `mem_idx` int(11) NOT NULL DEFAULT 0 COMMENT '회원 PK',
  `qa_status` enum('Y','N') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Y' COMMENT '문의 출력 여부',
  `qa_secret` enum('Y','N') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'N' COMMENT '비밀글 여부',
  `qa_content` text CHARACTER SET utf8mb4 NOT NULL COMMENT '내용',
  `qa_is_answer` enum('Y','N') NOT NULL DEFAULT 'N',
  `qa_a_content` text CHARACTER SET utf8mb4 NOT NULL COMMENT '답변',
  `qa_a_mem_idx` int(11) NOT NULL DEFAULT 0 COMMENT '답변한 회원 PK',
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '질문 작성 일시',
  `qa_a_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '답변 일시',
  PRIMARY KEY (`qa_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_review`;
CREATE TABLE `wb_products_review` (
  `rev_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `od_id` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '주문번호',
  `prd_idx` int(11) NOT NULL DEFAULT 0 COMMENT '상품 PK',
  `mem_idx` int(11) NOT NULL DEFAULT 0 COMMENT '회원 PK',
  `rev_status` enum('Y','H','D') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Y' COMMENT '리뷰 출력 여부',
  `rev_score` decimal(2,1) NOT NULL DEFAULT 0.0 COMMENT '평점',
  `reg_user` int(11) NOT NULL DEFAULT 0,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '생성 일시',
  `upd_user` int(11) NOT NULL DEFAULT 0,
  `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정 일시',
  `rev_content` text CHARACTER SET utf8mb4 NOT NULL COMMENT '리뷰 작성 내용',
  PRIMARY KEY (`rev_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_wish`;
CREATE TABLE `wb_products_wish` (
    `prd_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mem_idx` int(10) unsigned NOT NULL,
    PRIMARY KEY (`prd_idx`,`mem_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_shop_cart`;
CREATE TABLE `wb_shop_cart` (
    `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '장바구니 PK',
    `od_id` char(16) NOT NULL DEFAULT '' COMMENT '주문번호',
    `mem_idx` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '회원 PK',
    `prd_idx` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '상품 번호',
    `cart_status` enum('주문','쇼핑','입금','준비','취소','품절','반품','배송','완료') NOT NULL DEFAULT '쇼핑' COMMENT '주문 상태',
    `cart_direct` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '바로구매 여부',
    `cart_select` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '결제전 SELECT 여부',
    `cart_use_stock` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '재고 사용',
    `prd_name` varchar(255) NOT NULL DEFAULT '' COMMENT '상품 이름',
    `cart_price` int(11) NOT NULL DEFAULT 0 COMMENT '판매가격',
    `cart_point` int(11) NOT NULL DEFAULT 0 COMMENT '포인트 사용 금액',
    `cart_point_use` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '포인트 결제 사용',
    `cart_coupon` int(11) NOT NULL DEFAULT 0 COMMENT '쿠폰 사용 금액',
    `cart_coupon_use` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '쿠폰 사용',
    `cart_qty` int(11) NOT NULL DEFAULT 0 COMMENT '수량',
    `opt_code` varchar(255) NOT NULL DEFAULT '' COMMENT '선택옵션명',
    `opt_subject` varchar(255) NOT NULL,
    `opt_type` enum('detail','addition','') NOT NULL DEFAULT '' COMMENT '필수옵션/추강보션',
    `opt_price` int(11) NOT NULL DEFAULT 0 COMMENT '옵션 금액',
    `cart_option` varchar(255) NOT NULL DEFAULT '' COMMENT '상품명 또는 옵션명',
    `cart_sc_type` enum('무료','조건부무료','유료','수량별','차등','') NOT NULL DEFAULT '' COMMENT '배송비유형',
    `cart_sc_method` enum('','선불','착불') NOT NULL DEFAULT '' COMMENT '배송비결제',
    `cart_sc_price` int(11) NOT NULL DEFAULT 0 COMMENT '기본 배송비',
    `cart_sc_minimum` int(11) NOT NULL DEFAULT 0 COMMENT '배송비 상세조건 주문금액',
    `cart_sc_qty` int(11) NOT NULL DEFAULT 0 COMMENT '배송비 상세조건 수량',
    `cart_regtime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '장바구니 입력시간',
    `cart_select_time` datetime DEFAULT NULL COMMENT '주문서 작성 시간',
    `cart_ip` int(10) unsigned NOT NULL DEFAULT 0,
    `cart_history` text DEFAULT NULL,
    `cart_send_cost` tinyint(3) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`cart_id`),
    KEY `od_id` (`od_id`),
    KEY `cart_status` (`cart_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_shop_order`;
CREATE TABLE `wb_shop_order` (
 `od_id` bigint(20) unsigned NOT NULL COMMENT '주문번호',
 `mem_idx` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '주문자 PK',
 `imp_uid` varchar(255) NOT NULL DEFAULT '' COMMENT '포트원 결제 PK',
 `od_status` varchar(30) NOT NULL DEFAULT '' COMMENT '주문 상태',
 `od_settle_case` varchar(30) NOT NULL DEFAULT '' COMMENT '결제 수단',
 `od_receipt_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '주문완료 시간',
 `od_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '주문번호 생성시간',
 `od_name` varchar(20) NOT NULL DEFAULT '' COMMENT '주문자',
 `od_email` varchar(100) NOT NULL DEFAULT '' COMMENT '주문자 Email',
 `od_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '주문자 전화번호',
 `od_hp` varchar(20) NOT NULL DEFAULT '' COMMENT '주문자 휴대폰',
 `od_zonecode` varchar(5) NOT NULL DEFAULT '' COMMENT '주문자 우편번호',
 `od_addr1` varchar(100) NOT NULL DEFAULT '' COMMENT '주문자 주소1',
 `od_addr2` varchar(100) NOT NULL DEFAULT '' COMMENT '주문자 주소2',
 `od_title` varchar(255) NOT NULL DEFAULT '' COMMENT '주문 상품 요약',
 `od_memo` text NOT NULL COMMENT '주문시 요청 메모',
 `od_cart_count` int(11) NOT NULL DEFAULT 0 COMMENT '주문 상품 수',
 `od_cart_price` int(11) NOT NULL DEFAULT 0 COMMENT '주문 상품 금액',
 `od_send_cost` int(11) NOT NULL DEFAULT 0 COMMENT '배송비',
 `od_receipt_price` int(11) NOT NULL DEFAULT 0 COMMENT '총 주문 금액',
 `od_cancel_price` int(11) NOT NULL DEFAULT 0 COMMENT '취소 금액',
 `od_refund_price` int(11) NOT NULL DEFAULT 0 COMMENT '환불 금액',
 `od_misu` int(11) NOT NULL DEFAULT 0 COMMENT '미수금',
 `od_shop_memo` text NOT NULL COMMENT '관리자용 메모',
 `od_test` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '테스트 결제 여부',
 `od_mobile` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '모바일 결제 여부',
 `od_pg` varchar(30) NOT NULL DEFAULT '' COMMENT 'PG사',
 `od_delivery_company` varchar(40) NOT NULL DEFAULT '' COMMENT '배송사',
 `od_delivery_num` varchar(255) NOT NULL DEFAULT '' COMMENT '운송장번호',
 `od_ip` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '주문자 IP',
 `od_oc_send` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '주문완료 안내 발송여부',
 `od_ip_send` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '입금계좌 안내 발송여부',
 `od_ic_send` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '입금확인 안내 발송여부',
 `od_sc_send` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '발송완료 안내 발송여부',
 `od_oc_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '주문완료 안내 발송시간',
 `od_ip_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '입금계좌 안내 발송시간',
 `od_ic_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '입금확인 안내 발송시간',
 `od_sc_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '발송완료 안내 발송시간',
 PRIMARY KEY (`od_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_shop_order_address`;
CREATE TABLE `wb_shop_order_address` (
     `ad_id` int(11) NOT NULL AUTO_INCREMENT,
     `mem_idx` int(10) unsigned NOT NULL DEFAULT 0,
     `ad_subject` varchar(255) NOT NULL DEFAULT '',
     `ad_default` enum('Y','N') NOT NULL DEFAULT 'N',
     `ad_name` varchar(255) NOT NULL DEFAULT '',
     `ad_tel` varchar(255) NOT NULL DEFAULT '',
     `ad_hp` varchar(255) NOT NULL DEFAULT '',
     `ad_zonecode` varchar(5) NOT NULL DEFAULT '',
     `ad_addr1` varchar(255) NOT NULL DEFAULT '',
     `ad_addr2` varchar(255) NOT NULL DEFAULT '',
     PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_display`;
CREATE TABLE `wb_products_display` (
   `dsp_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `dsp_key` varchar(30) NOT NULL,
   `dsp_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '진열장 표시 여부',
   `dsp_title` varchar(50) NOT NULL DEFAULT '' COMMENT '진열장 명',
   `dsp_skin` varchar(50) NOT NULL DEFAULT '' COMMENT '진열장 스킨',
   `dsp_skin_m` varchar(50) NOT NULL DEFAULT '' COMMENT '모바일 진열장 스킨',
   `reg_user` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '생성한 회원 PK',
   `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '최초 입력 시간',
   `upd_user` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '수정한 회원 PK',
   `upd_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정한 시간',
   PRIMARY KEY (`dsp_idx`),
   KEY `dsp_key` (`dsp_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_products_display_items`;
CREATE TABLE `wb_products_display_items` (
     `dspi_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
     `dspi_sort` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '진열장 출력 순서',
     `dsp_idx` int(11) unsigned NOT NULL COMMENT '진열장 PK',
     `prd_idx` int(11) unsigned NOT NULL COMMENT '상품 PK',
     `reg_user` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '생성한 회원 PK',
     `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '최초 입력 시간',
     PRIMARY KEY (`dspi_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wb_sms_log`;
CREATE TABLE `wb_sms_log` (
      `sml_idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '문자발송로그 PK',
      `sml_regtime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '발송시간',
      `sml_type` enum('SMS','KAKAO','','LMS') NOT NULL DEFAULT '' COMMENT '구분',
      `sml_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '받는사람 번호',
      `sml_content` text NOT NULL COMMENT '보낸내용',
      `sml_code` varchar(30) NOT NULL DEFAULT '' COMMENT '카카오알림톡일경우 템플릿코드',
      `sml_result` varchar(30) NOT NULL DEFAULT '' COMMENT '전송결과',
      `sml_message` varchar(255) NOT NULL DEFAULT '' COMMENT '전송결과 메시지',
      PRIMARY KEY (`sml_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP VIEW IF EXISTS `wb_products_category_list`;
CREATE VIEW `wb_products_category_list` AS (select  `PC`.`cat_id` AS `cat_id`,  `PC`.`cat_parent_id` AS `cat_parent_id`,  `PC`.`cat_status` AS `cat_status`,  `PC`.`cat_sort` AS `cat_sort`,  `PC`.`cat_title` AS `cat_title`,  `PC`.`cat_skin` AS `cat_skin`,  `PC`.`cat_skin_m` AS `cat_skin_m`,  `PC`.`cat_use_paging` AS `cat_use_paging`,  `PC`.`cat_page_rows` AS `cat_page_rows`,  `PC`.`cat_product_count` AS `cat_product_count`,  `PC`.`reg_user` AS `reg_user`,  `PC`.`reg_datetime` AS `reg_datetime`,  `PC`.`upd_user` AS `upd_user`,  `PC`.`upd_datetime` AS `upd_datetime`,  concat(case when `PC2`.`cat_id` is null then '' else lpad(`PC2`.`cat_id`,3,'0') end,case when `PC1`.`cat_id` is null then '' else lpad(`PC1`.`cat_id`,3,'0') end,lpad(`PC`.`cat_id`,3,'0')) AS `node_path`,  concat(case when `PC2`.`cat_id` is null then '' else concat(`PC2`.`cat_title`,' > ') end,case when `PC1`.`cat_id` is null then '' else concat(`PC1`.`cat_title`,' > ') end) AS `parent_names` from ((`wb_products_category` `PC`  left join `wb_products_category` `PC1`  on (`PC1`.`cat_id` = `PC`.`cat_parent_id`))  left join `wb_products_category` `PC2`  on (`PC2`.`cat_id` = `PC1`.`cat_parent_id`)));