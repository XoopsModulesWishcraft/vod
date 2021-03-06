CREATE TABLE `vod_cart` (
  `crtid` int(35) unsigned NOT NULL AUTO_INCREMENT,
  `mode` enum('_VOD_ENUM_UNPAID','_VOD_ENUM_PAID','_VOD_ENUM_CANCELED') DEFAULT '_VOD_ENUM_UNPAID',
  `sessid` int(30) unsigned DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0',
  `vid` int(10) unsigned DEFAULT '0',
  `pid` int(10) unsigned DEFAULT '0',
  `tokens` int(5) unsigned DEFAULT '1',
  `cost_usd` decimal(15,2) unsigned DEFAULT '0.00',
  `cost_aud` decimal(15,2) unsigned DEFAULT '0.00',
  `cost` decimal(15,2) unsigned DEFAULT '0.00',
  `currency` varchar(3) DEFAULT 'AUD',
  `added` int(13) unsigned DEFAULT '0',
  `paid` int(13) unsigned DEFAULT '0',
  `notified` int(13) unsigned DEFAULT '0',
  `claimed` int(13) unsigned DEFAULT '0',
  `closed` int(13) unsigned DEFAULT '0',
  `expires` int(13) unsigned DEFAULT '0',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`crtid`),
  KEY `SEARCH` (`mode`,`vid`,`tokens`),
  KEY `TIMES` (`mode`,`vid`,`added`,`paid`,`notified`,`claimed`,`expires`,`created`,`updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vod_cart_sessions` (
  `sessid` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `mode` enum('_VOD_ENUM_UNINVOICED','_VOD_ENUM_INVOICED','_VOD_ENUM_PAID','_VOD_ENUM_CANCELED') DEFAULT '_VOD_ENUM_UNINVOICED',
  `salt` varchar(32) DEFAULT NULL,
  `session_id` varchar(64) DEFAULT NULL,
  `iid` int(18) unsigned DEFAULT '0',
  `uid` int(13) unsigned DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(198) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL,
  `attempts` tinyint(4) unsigned DEFAULT '0',
  `ip` varchar(64) DEFAULT NULL,
  `netaddy` varchar(255) DEFAULT NULL,
  `videos` int(6) unsigned DEFAULT '0',
  `discounted` tinyint(2) unsigned DEFAULT '0',
  `discount` decimal(15,4) unsigned DEFAULT '0.0000',
  `discount_usd` decimal(15,4) unsigned DEFAULT '0.0000',
  `discount_aud` decimal(15,4) unsigned DEFAULT '0.0000',
  `tokens` int(10) unsigned DEFAULT '0',
  `total_usd` decimal(20,2) unsigned DEFAULT '0.00',
  `total_aud` decimal(20,2) unsigned DEFAULT '0.00',
  `total` decimal(20,2) unsigned DEFAULT '0.00',
  `currency` varchar(3) DEFAULT 'AUD',
  `paidkey` varchar(32) DEFAULT NULL,
  `invoiced` int(13) unsigned DEFAULT '0',
  `paid` int(13) unsigned DEFAULT '0',
  `canceled` int(13) unsigned DEFAULT '0',
  `expires` int(13) unsigned DEFAULT '0',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`sessid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vod_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned DEFAULT '0',
  `prefix` varchar(32) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `description` mediumtext,
  `path` varchar(255) DEFAULT NULL,
  `avata` varchar(255) DEFAULT NULL,
  `earning_usd` decimal(20,2) unsigned DEFAULT '0.00',
  `earning_aud` decimal(20,2) unsigned DEFAULT '0.00',
  `discounts_usd` decimal(20,2) unsigned DEFAULT '0.00',
  `discounts_aud` decimal(20,2) unsigned DEFAULT '0.00',
  `purchases` int(20) unsigned DEFAULT '0',
  `hits` int(20) unsigned DEFAULT '0',
  `views` int(20) unsigned DEFAULT '0',
  `purchased` int(20) unsigned DEFAULT '0',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `SEARCH` (`parent`,`prefix`,`name`(32),`summary`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vod_currency` (
  `currency_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `alias` varchar(128) DEFAULT NULL,
  `code` varchar(3) DEFAULT 'AUD',
  `left` varchar(2) DEFAULT '$',
  `right` varchar(2) DEFAULT NULL,
  `decimals` int(4) unsigned DEFAULT '2',
  `rate` decimal(16,8) DEFAULT '1.00000000',
  `rate_default` decimal(16,8) DEFAULT '1.00000000',
  `rate_usd` decimal(16,8) DEFAULT '1.00000000',
  `rate_aud` decimal(16,8) DEFAULT '1.00000000',
  `rate_set` int(12) unsigned DEFAULT '0',
  `default` tinyint(2) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

insert  into `vod_currency` (`currency_id`,`name`,`alias`,`code`,`left`,`right`,`decimals`,`rate`,`rate_default`,`rate_usd`,`rate_aud`,`rate_set`,`default`,`created`,`updated`) values (1,'Australian Dollar','Dollar','AUD','$','',2,'1.00000000','1.00000000','1.00000000','1.00000000',0,1,1327500494,0);
insert  into `vod_currency` (`currency_id`,`name`,`alias`,`code`,`left`,`right`,`decimals`,`rate`,`rate_default`,`rate_usd`,`rate_aud`,`rate_set`,`default`,`created`,`updated`) values (2,'US Dollar','Greenback','USD','$','',2,'1.00000000','1.00000000','1.00000000','1.00000000',0,0,1327500494,0);
insert  into `vod_currency` (`currency_id`,`name`,`alias`,`code`,`left`,`right`,`decimals`,`rate`,`rate_default`,`rate_usd`,`rate_aud`,`rate_set`,`default`,`created`,`updated`) values (3,'Great British Pounds','Pounds','GBP','£','',2,'1.00000000','1.00000000','1.00000000','1.00000000',0,0,1327500494,0);
insert  into `vod_currency` (`currency_id`,`name`,`alias`,`code`,`left`,`right`,`decimals`,`rate`,`rate_default`,`rate_usd`,`rate_aud`,`rate_set`,`default`,`created`,`updated`) values (4,'Euro','European Dollar','EUR','','€',2,'1.00000000','1.00000000','1.00000000','1.00000000',0,0,1327500494,0);

CREATE TABLE `vod_mimetypes` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `support` enum('_MI_VOD_FLASH','_MI_VOD_HTML5','_MI_VOD_HTTP','_MI_VOD_IOS','_MI_VOD_RTMP','_MI_VOD_RTSP','_MI_VOD_SILVERLIGHT','_MI_VOD_OTHER') DEFAULT '_MI_VOD_FLASH',
  `name` varchar(128) DEFAULT '',
  `mimetype` varchar(128) DEFAULT '',
  `codecs` varchar(500) DEFAULT '',
  `default` int(1) DEFAULT '0',
  `created` int(13) DEFAULT '0',
  `updated` int(13) DEFAULT '0',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

insert  into `vod_mimetypes` (`mid`,`support`,`name`,`mimetype`,`codecs`,`default`,`created`,`updated`) values (1,'_MI_VOD_HTML5','MP4','video/mp4','avc1.42E01E, mp4a.40.2',0,1327497974,0);
insert  into `vod_mimetypes` (`mid`,`support`,`name`,`mimetype`,`codecs`,`default`,`created`,`updated`) values (2,'_MI_VOD_HTML5','WEBM','video/webm','vp8, vorbis',0,1327497974,0);
insert  into `vod_mimetypes` (`mid`,`support`,`name`,`mimetype`,`codecs`,`default`,`created`,`updated`) values (3,'_MI_VOD_HTML5','OGG','video/ogg','theora, vorbis',0,1327497974,0);
insert  into `vod_mimetypes` (`mid`,`support`,`name`,`mimetype`,`codecs`,`default`,`created`,`updated`) values (4,'_MI_VOD_FLASH','FLASH VIDEO','video/x-flv','',1,1327497974,0);

CREATE TABLE `vod_videos` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) DEFAULT '0',
  `mid` int(10) DEFAULT '0',
  `pid` int(10) DEFAULT '0',
  `token` varchar(32) DEFAULT NULL,
  `catno` varchar(32) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `reference` varchar(128) DEFAULT 'video_%vid%',
  `length` varchar(64) DEFAULT NULL,
  `producedby` varchar(128) DEFAULT NULL,
  `staring` varchar(255) DEFAULT NULL,
  `year` varchar(5) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `description` mediumtext,
  `raw` varchar(500) DEFAULT NULL,
  `rtmp_server` varchar(500) DEFAULT '',
  `rtmp` varchar(500) DEFAULT '',
  `flash` varchar(500) DEFAULT '',
  `ios` varchar(500) DEFAULT '',
  `silverlight` varchar(500) DEFAULT '',
  `rtsp` varchar(500) DEFAULT '',
  `http` varchar(500) DEFAULT '',
  `raw_preview` varchar(500) DEFAULT NULL,
  `rtmp_server_preview` varchar(500) DEFAULT '',
  `rtmp_preview` varchar(500) DEFAULT '',
  `flash_preview` varchar(500) DEFAULT '',
  `ios_preview` varchar(500) DEFAULT '',
  `silverlight_preview` varchar(500) DEFAULT '',
  `rtsp_preview` varchar(500) DEFAULT '',
  `http_preview` varchar(500) DEFAULT '',
  `width` varchar(64) DEFAULT NULL,
  `height` varchar(64) DEFAULT NULL,
  `speciala_width` varchar(64) DEFAULT NULL,
  `speciala_height` varchar(64) DEFAULT NULL,
  `specialb_width` varchar(64) DEFAULT NULL,
  `specialb_height` varchar(64) DEFAULT NULL,
  `default` tinyint(2) DEFAULT '0',
  `stream` tinyint(2) unsigned DEFAULT '0',
  `path` varchar(255) DEFAULT '',
  `poster` varchar(255) DEFAULT '',
  `avata` varchar(255) DEFAULT '',
  `level` int(5) unsigned DEFAULT '100',
  `autoplay` tinyint(2) unsigned DEFAULT '0',
  `speciala_autoplay` tinyint(2) unsigned DEFAULT '0',
  `specialb_autoplay` tinyint(2) unsigned DEFAULT '0',
  `controls` tinyint(2) unsigned DEFAULT '0',
  `speciala_controls` tinyint(2) unsigned DEFAULT '0',
  `specialb_controls` tinyint(2) unsigned DEFAULT '0',
  `muted` tinyint(2) unsigned DEFAULT '0',
  `play` tinyint(2) unsigned DEFAULT '0',
  `volume` tinyint(2) unsigned DEFAULT '0',
  `mute` tinyint(2) unsigned DEFAULT '0',
  `time` tinyint(2) unsigned DEFAULT '0',
  `stop` tinyint(2) unsigned DEFAULT '0',
  `fullscreen` tinyint(2) unsigned DEFAULT '0',
  `scrubber` tinyint(2) unsigned DEFAULT '0',
  `purchases` int(10) unsigned DEFAULT '0',
  `price` decimal(15,2) unsigned DEFAULT '0.00',
  `currency` varchar(3) DEFAULT 'AUD',
  `tokens` int(5) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT '',
  `hits` int(20) unsigned DEFAULT '0',
  `view` int(20) unsigned DEFAULT '0',
  `purchased` int(20) unsigned DEFAULT '0',
  `earned_usd` decimal(20,2) unsigned DEFAULT '0.00',
  `earned_aud` decimal(20,2) unsigned DEFAULT '0.00',
  `dicounted_aud` decimal(20,4) unsigned DEFAULT '0.0000',
  `dicounted_usd` decimal(20,4) unsigned DEFAULT '0.0000',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`vid`),
  KEY `SEARCH` (`token`,`catno`(8),`name`(32),`producedby`(20),`staring`(20),`year`(4),`summary`(30),`default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vod_log` (
  `log_id` bigint(25) unsigned NOT NULL AUTO_INCREMENT,
  `class` enum('cart','sessions','currency','category','mimetypes','videos','external','unknown') DEFAULT 'unknown',
  `file` varchar(64) DEFAULT NULL,
  `path` varchar(128) DEFAULT NULL,
  `line` int(15) unsigned DEFAULT '0',
  `id` bigint(20) unsigned DEFAULT '0',
  `uid` int(20) unsigned DEFAULT '0',
  `status` enum('_VOD_ENUM_UNINVOICED','_VOD_ENUM_INVOICED','_VOD_ENUM_PAID','_VOD_ENUM_UNPAID','_VOD_ENUM_CANCELED') DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `comment` mediumtext,
  `created` decimal(18,4) unsigned DEFAULT '0.000',
  PRIMARY KEY (`log_id`),
  KEY `SEARCH` (`class`, `status`, `name`(32), `id`, `created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
