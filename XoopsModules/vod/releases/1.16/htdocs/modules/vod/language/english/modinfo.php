<?php

	// XOOPS Version
	define('_MI_VOD_NAME', 'Video on Demand Cart');
	define('_MI_VOD_DESCRIPTION', 'VOD is for display a stream or block for a stream video in previews and purchase of viewing of video files or streams.');
	define('_MI_VOD_DIRNAME', 'vod');
	
	// Form langauges
	define('_MI_VOD_NONE', 'None');
	
	// Javascripts and style sheets
	define('_MI_VOD_JQUERY', '/browse.php?Frameworks/jquery/jquery.js');
	define('_MI_VOD_VOD', '/modules/'._MI_VOD_DIRNAME.'/js/vod-3.2.6.min.js');
	define('_MI_VOD_CORE', '/modules/'._MI_VOD_DIRNAME.'/js/core.js');
	define('_MI_VOD_JSON_FUNCTIONS', '/modules/'._MI_VOD_DIRNAME.'/js/json_functions.js');
	define('_MI_VOD_VOD_STYLE', '/modules/'._MI_VOD_DIRNAME.'/language/%s/style.css');
	
	//Preferences
	define('_MI_VOD_SALT','Salt for Encryption');
	define('_MI_VOD_SALT_DESC','This is the salt for encryption (do not change on production machines!)');
	define('_MI_VOD_FORCE_JQUERY','Force Jquery');
	define('_MI_VOD_FORCE_JQUERY_DESC','Forces the loading of jquery if not included in theme!');
	
	//Version 1.03
	//Preferences
	define('_MI_VOD_IFRAME','Display Default as IFrame in Index');
	define('_MI_VOD_IFRAME_DESC','Display the default iFrame in Index of module for this type of content!');
	
	//Version 1.06
	//Preferences
	$module_handler =& xoops_gethandler('module');
	$GLOBALS['vodModule'] =& XoopsModule::getByDirname(_MI_VOD_DIRNAME);
	if (is_object($GLOBALS['vodModule'])) {
		$GLOBALS['vodImageAdmin'] = $GLOBALS['vodModule']->getInfo('icons32');
	
		// Admin menu
		define('_MI_VOD_TITLE_ADMENU0', 'Video\'s Dashboard');
		define('_MI_VOD_ICON_ADMENU0', '../../'.$GLOBALS['vodImageAdmin'].'/home.png');
		define('_MI_VOD_LINK_ADMENU0', 'admin/index.php?op=dashboard');	
		define('_MI_VOD_TITLE_ADMENU1', 'Video\'s List');
		define('_MI_VOD_ICON_ADMENU1', '../../'.$GLOBALS['vodImageAdmin'].'/vod.video.png');
		define('_MI_VOD_LINK_ADMENU1', 'admin/index.php?op=videos&fct=list');
		define('_MI_VOD_TITLE_ADMENU2', 'Video\'s Categories');
		define('_MI_VOD_ICON_ADMENU2', '../../'.$GLOBALS['vodImageAdmin'].'/vod.category.png');
		define('_MI_VOD_LINK_ADMENU2', 'admin/index.php?op=category&fct=list');
		define('_MI_VOD_TITLE_ADMENU3', 'Cart Sessions');
		define('_MI_VOD_ICON_ADMENU3', '../../'.$GLOBALS['vodImageAdmin'].'/vod.cart.png');
		define('_MI_VOD_LINK_ADMENU3', 'admin/index.php?op=sessions&fct=list');
		define('_MI_VOD_TITLE_ADMENU4', 'Mimetype\'s List');
		define('_MI_VOD_ICON_ADMENU4', '../../'.$GLOBALS['vodImageAdmin'].'/vod.mimetypes.png');
		define('_MI_VOD_LINK_ADMENU4', 'admin/index.php?op=mimetypes&fct=list');		
		define('_MI_VOD_TITLE_ADMENU5', 'Currencies');
		define('_MI_VOD_ICON_ADMENU5', '../../'.$GLOBALS['vodImageAdmin'].'/vod.currency.png');
		define('_MI_VOD_LINK_ADMENU5', 'admin/index.php?op=mimetypes&fct=list');		
		define('_MI_VOD_TITLE_ADMENU6', 'User-agent Spy');
		define('_MI_VOD_ICON_ADMENU6', '../../'.$GLOBALS['vodImageAdmin'].'/vod.useragents.png');
		define('_MI_VOD_LINK_ADMENU6', 'admin/index.php?op=agents');
		define('_MI_VOD_TITLE_ADMENU7', 'Log');
		define('_MI_VOD_ICON_ADMENU7', '../../'.$GLOBALS['vodImageAdmin'].'/vod.log.png');
		define('_MI_VOD_LINK_ADMENU7', 'admin/index.php?op=log');
		define('_MI_VOD_TITLE_ADMENU8', 'Permissions');
		define('_MI_VOD_ICON_ADMENU8', '../../'.$GLOBALS['vodImageAdmin'].'/permissions.png');
		define('_MI_VOD_LINK_ADMENU8', 'admin/index.php?op=permissions');
		define('_MI_VOD_TITLE_ADMENU9', 'About Video On Demand');
		define('_MI_VOD_ICON_ADMENU9', '../../'.$GLOBALS['vodImageAdmin'].'/about.png');
		define('_MI_VOD_LINK_ADMENU9', 'admin/index.php?op=about');
	}
	
	// Version 1.03
	// Preferences
	define('_MI_VOD_PASSKEY_DIFF', 'Number of seconds of difference to weigh passkey by');
	define('_MI_VOD_PASSKEY_DIFF_DESC', 'Total number of seconds a passkey to load a video session');
	define('_MI_VOD_PASSKEY_WEIGHT', 'Number of seconds of weight variance to weigh passkey by');
	define('_MI_VOD_PASSKEY_WEIGHT_DESC', 'Total number of seconds a weighted variance is added to difference to load a video session');

	// Version 1.10
	// Preferences
	define('_MI_VOD_RTMP_USERAGENTS', 'RTMP Videos User-agents');
	define('_MI_VOD_RTMP_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_FLASH_USERAGENTS', 'Flash Videos User-agents');
	define('_MI_VOD_FLASH_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_IOS_USERAGENTS', 'iOS (Apple) Videos User-agents');
	define('_MI_VOD_IOS_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_SILVERLIGHT_USERAGENTS', 'Silverlight Videos Useragents');
	define('_MI_VOD_SILVERLIGHT_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_RTSP_USERAGENTS', 'RTSP Videos Useragents');
	define('_MI_VOD_RTSP_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_OTHER_USERAGENTS', 'Other Videos Useragents');
	define('_MI_VOD_OTHER_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_HTML5_USERAGENTS', 'HTML5 Videos Useragents');
	define('_MI_VOD_HTML5_USERAGENTS_DESC', 'Seperate with a pipe, ie. |');
	define('_MI_VOD_VIDEOS_FLASH', 'Flash Video Videos');
	define('_MI_VOD_VIDEOS_IOS', 'iOS HTML5 Videos');
	define('_MI_VOD_VIDEOS_RTSP', 'RTSP HTML5 Videos');
	define('_MI_VOD_VIDEOS_HTML5', 'HTML5 Videos');
	define('_MI_VOD_VIDEOS_RTMP', 'RTMP Videos');
	define('_MI_VOD_VIDEOS_SILVERLIGHT', 'Silverlight Videos');
	define('_MI_VOD_VIDEOS_OTHER', 'Other Videos');
	define('_MI_VOD_DEFAULT_VIDEOS', 'Default Videos');
	define('_MI_VOD_DEFAULT_VIDEOS_DESC', 'This is the default videos selected when nothing with a useragent is specified');
	define('_MI_VOD_RTMP_VIDEOS', 'RTMP Videos');
	define('_MI_VOD_RTMP_VIDEOS_DESC', 'This is the RTMP videos selected when a useragent is specified');
	define('_MI_VOD_FLASH_VIDEOS', 'Flash Video Videos');
	define('_MI_VOD_FLASH_VIDEOS_DESC', 'This is the Flash Video videos selected when a useragent is specified');
	define('_MI_VOD_IOS_VIDEOS', 'iOS (Apple) Videos');
	define('_MI_VOD_IOS_VIDEOS_DESC', 'This is the iOS videos selected when a useragent is specified');
	define('_MI_VOD_SILVERLIGHT_VIDEOS', 'Silverlight Videos');
	define('_MI_VOD_SILVERLIGHT_VIDEOS_DESC', 'This is the Silverlight videos selected when a useragent is specified');
	define('_MI_VOD_RTSP_VIDEOS', 'RTSP Videos');
	define('_MI_VOD_RTSP_VIDEOS_DESC', 'This is the RTSP videos selected when a useragent is specified');
	define('_MI_VOD_HTML5_VIDEOS', 'HTML5 Videos');
	define('_MI_VOD_HTML5_VIDEOS_DESC', 'This is the HTML5 videos selected when a useragent is specified');
	define('_MI_VOD_OTHER_VIDEOS', 'Other Videos');
	define('_MI_VOD_OTHER_VIDEOS_DESC', 'This is the Other videos selected when a useragent is specified');
	define('_MI_VOD_LOAD_VOD', 'Load Vod in these videos sessions');
	define('_MI_VOD_LOAD_VOD_DESC', 'This is the intances that load vod (Do not change unless you know what you are doing)');
	define('_MI_VOD_LOAD_VIDEOJS', 'Load Video-js in these videos sessions');
	define('_MI_VOD_LOAD_VIDEOJS_DESC', 'This is the intances that load HTML5 Video-js (Do not change unless you know what you are doing)');
	
	//Enumerators
	define('_MI_VOD_FLASH', 'Flash Videos (Vod)');
	define('_MI_VOD_HTML5', 'HTML5 Videos (Video-js)'); 
	define('_MI_VOD_IOS', 'Apple OS Videos (Video-js)');
	define('_MI_VOD_RTMP', 'RTMP Stream Videos (Vod)');
	define('_MI_VOD_RTSP', 'RTSP Stream Videos (Video-js)');
	define('_MI_VOD_OTHER', 'Other Videos');
	define('_MI_VOD_SILVERLIGHT', 'Silverlight Videos');
	
	// HTML5 Scripts URL
	define('_MI_VOD_VIDEOJS', '/modules/'._MI_VOD_DIRNAME.'/js/video.js');
	define('_MI_VOD_VIDEOJS_STYLE', '/modules/'._MI_VOD_DIRNAME.'/language/%s/video-js.css');
	
	// Version 1.11
	// Preferences
	define('_MI_VOD_FILESIZEUPLD', 'File Size Upload');
	define('_MI_VOD_FILESIZEUPLD_DESC', 'File size allowed to be uploaded of images');
	define('_MI_VOD_ALLOWEDMIMETYPE', 'Allowed Mimetypes');
	define('_MI_VOD_ALLOWEDMIMETYPE_DESC', 'Allowed mimetypes for file upload of images');
	define('_MI_VOD_ALLOWEDEXTENSIONS', 'Allowed Extensions');
	define('_MI_VOD_ALLOWEDEXTENSIONS_DESC', 'Allowed extensions for uploading images');
	define('_MI_VOD_UPLOADAREAS', 'Upload Area');
	define('_MI_VOD_UPLOADAREAS_DESC', 'Area to be uploaded to');
	define('_MI_VOD_UPLOADAREAS_UPLOADS', 'uploads/');
	define('_MI_VOD_UPLOADAREAS_UPLOADS_UITABS', 'uploads/vod/');
	define('_MI_VOD_ORDER_RTMP', 'Order RTMP User-agents is checked for');
	define('_MI_VOD_ORDER_RTMP_DESC', 'This is the order which the useragents are stepped through for a RTMP videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_FLASH', 'Order which Flash Video User-agents is check for');
	define('_MI_VOD_ORDER_FLASH_DESC', 'This is the order which the useragents are stepped through for a Flash Video videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_IOS', 'Order which iOS (Apple) User-agents is checked for');
	define('_MI_VOD_ORDER_IOS_DESC', 'This is the order which the useragents are stepped through for a iOS videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_SILVERLIGHT', 'Order which Silverlight User-agents is checked for');
	define('_MI_VOD_ORDER_SILVERLIGHT_DESC', 'This is the order which the useragents are stepped through for a Silverlight videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_RTSP', 'Order which RTSP User-agents is Checked for');
	define('_MI_VOD_ORDER_RTSP_DESC', 'This is the order which the useragents are stepped through for a RTSP videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_HTML5', 'Order which HTML5 User-agents is checked for');
	define('_MI_VOD_ORDER_HTML5_DESC', 'This is the order which the useragents are stepped through for a HTML5 videos is selected when a useragent is found.');
	define('_MI_VOD_ORDER_OTHER', 'Order which Other User-agents is checked for');
	define('_MI_VOD_ORDER_OTHER_DESC', 'This is the order which the useragents are stepped through for a Other videos is selected when a useragent is found.');
	
	//Enumerators
	define('_MI_VOD_VIDEOS_FLASH', 'Flash Videos (Vod)');
	define('_MI_VOD_VIDEOS_HTML5', 'HTML5 Videos (Video-js)'); 
	define('_MI_VOD_VIDEOS_IOS', 'Apple OS Videos (Video-js)');
	define('_MI_VOD_VIDEOS_RTMP', 'RTMP Stream Videos (Vod)');
	define('_MI_VOD_VIDEOS_RTSP', 'RTSP Stream Videos (Video-js)');
	define('_MI_VOD_VIDEOS_OTHER', 'Other Videos');
	define('_MI_VOD_VIDEOS_SILVERLIGHT', 'Silverlight Videos');
	define('_MI_VOD_ORDER_1ST', 'Check Useragent Type First');
	define('_MI_VOD_ORDER_2ND', 'Check Useragent Type Second'); 
	define('_MI_VOD_ORDER_3RD', 'Check Useragent Type Third');
	define('_MI_VOD_ORDER_4TH', 'Check Useragent Type Forth');
	define('_MI_VOD_ORDER_5TH', 'Check Useragent Type Fifth');
	define('_MI_VOD_ORDER_6TH', 'Check Useragent Type Sixth');
	define('_MI_VOD_ORDER_7TH', 'Check Useragent Type Seventh');
	
	// Version 1.13
	define('_MI_VOD_RTMP_VIDEOS_SECURE', 'RTMP Videos HTML is Secure?');
	define('_MI_VOD_RTMP_VIDEOS_SECURE_DESC', ' RTMP Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_FLASH_VIDEOS_SECURE','Flash Video Videos HTML is Secure?');
	define('_MI_VOD_FLASH_VIDEOS_SECURE_DESC', ' Flash Video Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_IOS_VIDEOS_SECURE', 'iOS (Apple) Videos HTML is Secure?');
	define('_MI_VOD_IOS_VIDEOS_SECURE_DESC', ' iOS Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_SILVERLIGHT_VIDEOS_SECURE', 'Silverlight Videos HTML is Secure?');
	define('_MI_VOD_SILVERLIGHT_VIDEOS_SECURE_DESC', ' Silverlight Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_RTSP_VIDEOS_SECURE', 'RTSP Videos HTML is Secure?');
	define('_MI_VOD_RTSP_VIDEOS_SECURE_DESC', ' RTSP Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_HTML5_VIDEOS_SECURE', 'HTML5 Videos HTML is Secure?');
	define('_MI_VOD_HTML5_VIDEOS_SECURE_DESC', ' HTML5 Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_OTHER_VIDEOS_SECURE', 'Other Videos HTML is Secure?');
	define('_MI_VOD_OTHER_VIDEOS_SECURE_DESC', ' Other Videos HTML is Secure when selected when a useragent is specified');
	
	// Version 1.14
	define('_MI_VOD_SPECIALA_USERAGENTS', 'Special Functions Useragent Functions A');
	define('_MI_VOD_SPECIALA_USERAGENTS_DESC', 'Functions for special function A when selected when a useragent is specified');
	define('_MI_VOD_SPECIALB_USERAGENTS', 'Special Functions Useragent - Functions B');
	define('_MI_VOD_SPECIALB_USERAGENTS_DESC', 'Functions for special function B when selected when a useragent is specified');
	define('_MI_VOD_HTTP_USERAGENTS', 'HTTP(s) Video Useragent');
	define('_MI_VOD_HTTP_USERAGENTS_DESC', 'Functions for HTTP Sourced video when selected when a useragent is specified');
	define('_MI_VOD_HTTP_VIDEOS_SECURE', 'HTTP(s) Videos HTML is Secure?');
	define('_MI_VOD_HTTP_VIDEOS_SECURE_DESC', ' HTTP(s) Videos HTML is Secure when selected when a useragent is specified');
	define('_MI_VOD_ORDER_8TH', 'Check Useragent Type Eighth');
	define('_MI_VOD_VIDEOS_HTTP', 'HTTP(s) Videos');
	define('_MI_VOD_ORDER_HTTP', 'Order which HTTP(s) User-agents is checked for');
	define('_MI_VOD_ORDER_HTTP_DESC', 'This is the order which the useragents are stepped through for a HTTP(s) videos is selected when a useragent is found.');
	define('_MI_VOD_HTTP', 'HTTP(s) Videos');
	define('_MI_VOD_HTTP_VIDEOS', 'HTTP(s) Videos');
	define('_MI_VOD_HTTP_VIDEOS_DESC', 'This is the HTTP(s) videos selected when a useragent is specified');
	
	// Version 1.15
	//Preferences
	define('_MI_VOD_EDITORS', 'Default Editor');
	define('_MI_VOD_EDITORS_DESC', 'This is the editor that will be used for HTML');
	define('_MI_VOD_CHECK_RATES', 'Check Exchange Rates');
	define('_MI_VOD_CHECK_RATES_DESC', 'This is how oftern exchange rates are checked and updated.');
	define('_MI_VOD_TIMES_15MINUTES', '15 Minutes');
	define('_MI_VOD_TIMES_30MINUTES', '30 Minutes');
	define('_MI_VOD_TIMES_45MINUTES', '45 Minutes');
	define('_MI_VOD_TIMES_60MIUNTES', '60 Minutes');
	define('_MI_VOD_TIMES_85MINUTES', '85 Minutes');
	define('_MI_VOD_TIMES_110MINUTES', '110 Minutes');
	define('_MI_VOD_TIMES_135MINUTES', '135 Minutes');
	define('_MI_VOD_TIMES_150MINUTES', '150 Minutes');
	define('_MI_VOD_TIMES_180MINUTES', '180 Minutes');
	define('_MI_VOD_TIMES_360MINUTES', '360 Minutes');
	define('_MI_VOD_TIMES_540MINUTES', '540 Minutes');
	define('_MI_VOD_TIMES_900MINUTES', '900 Minutes');
	define('_MI_VOD_TIMES_24HOURS', '24 Hours');
	define('_MI_VOD_TIMES_48HOURS', '48 Hours');
	define('_MI_VOD_TIMES_72HOURS', '72 Hours');
	define('_MI_VOD_TIMES_96HOURS', '96 Hours');
	define('_MI_VOD_TIMES_1WEEK', '1 Week');
	define('_MI_VOD_TIMES_FORTNIGHT', 'A Fortnight');
	define('_MI_VOD_TIMES_1MONTH', '1 Month');
	define('_MI_VOD_TIMES_2MONTHS', '2 Months');
	define('_MI_VOD_TIMES_3MONTHS', '3 Months');
	define('_MI_VOD_TIMES_4MONTHS', '4 Months');
	define('_MI_VOD_TIMES_5MONTHS', '5 Months');
	define('_MI_VOD_TIMES_6MONTHS', '6 Months');
	define('_MI_VOD_TIMES_12MONTHS', '1 Year');
	define('_MI_VOD_TIMES_24MONTHS', '2 Years');
	define('_MI_VOD_TIMES_36MONTHS', '3 Years');
	define('_MI_VOD_HTACCESS', 'Support htaccess SEO');
	define('_MI_VOD_HTACCESS_DESC', 'Whether .htaccess SEO is turned on (see /docs)');
	define('_MI_VOD_HTACCESS_BASEOFURL', 'SEO Base of URL');
	define('_MI_VOD_HTACCESS_BASEOFURL_DESC', 'Base of SEO URL (modify /docs)');
	define('_MI_VOD_HTACCESS_ENDOFURL', 'End of URL');
	define('_MI_VOD_HTACCESS_ENDOFURL_DESC', 'End of SEO URL (modify /docs)');
	define('_MI_VOD_MATRIXSTREAM', 'Support Matrixstream Module');
	define('_MI_VOD_MATRIXSTREAM_DESC', 'Whether Matrixstream 1.06 or later is supported!');
	define('_MI_VOD_TAGS', 'Support Tags Module');
	define('_MI_VOD_TAGS_DESC', 'Whether Tags 2.3 or later is supported');
	define('_MI_VOD_LOG_CACHE', 'Log Record is Kept For');
	define('_MI_VOD_LOG_CACHE_DESC', 'This is how long a Log Record is Kept For in the database before being deleted.');
	define('_MI_VOD_PURCHASE_EXPIRES', 'Purchase Expires In');
	define('_MI_VOD_PURCHASE_EXPIRES_DESC', 'This is how long a purchase is held from payment to watch the video then it expires and the links for the viewing go dead!');
	define('_MI_VOD_CURRENCY', 'Default Currency');
	define('_MI_VOD_CURRENCY_DESC', 'This is the default currency for billing and transactions (You have to update the module if you add a currency to make it appear here.)');
	
	// Version 1.16
	// Preferences
	define('_MI_VOD_CAT_PER_ROW', 'Categories Per Row');
	define('_MI_VOD_CAT_PER_ROW_DESC', 'Number of Categories to Display per row on User Side');
	define('_MI_VOD_CART_EXPIRES', 'Cart Expires');
	define('_MI_VOD_CART_EXPIRES_DESC', 'This is how long it takes for a cart to expire');
	define('_MI_VOD_CART_DELETED', 'Cart Deleted');
	define('_MI_VOD_CART_DELETED_DESC', 'This is how long it takes for a card to be deleted');
	define('_MI_VOD_TAX', 'Tax Percentile');
	define('_MI_VOD_TAX_DESC', 'Tax percentile to charge on items.');
	define('_MI_VOD_CRONTYPE', 'Cron Execution Type');
	define('_MI_VOD_CRONTYPE_DESC', 'This is how the cron is executed');
	define('_MI_VOD_CRONTYPE_PRELOADER', 'Preloader');
	define('_MI_VOD_CRONTYPE_CRONTAB', 'Cron job');
	define('_MI_VOD_CRONTYPE_SCHEDULER', 'Windows Scheduler');
	define('_MI_VOD_INTERVAL', 'Cron Interval');
	define('_MI_VOD_INTERVAL_DESC', 'Number of seconds between cron execution');
	define('_MI_VOD_NOTIFY', 'Notifications per cron session');
	define('_MI_VOD_NOTIFY_DESC', 'Number of notification to execute per cron session');
	define('_MI_VOD_PREVIEW_BY_DEFAULT', 'Preview by default');
	define('_MI_VOD_PREVIEW_BY_DEFAULT_DESC', 'Whether it display a preview by default.');
	define('_MI_VOD_VIDEO_WIDTH_MAIN', 'Default Main Video Width');
	define('_MI_VOD_VIDEO_WIDTH_MAIN_DESC', '');
	define('_MI_VOD_VIDEO_HEIGHT_MAIN', 'Default Main Video Height');
	define('_MI_VOD_VIDEO_HEIGHT_MAIN_DESC', '');
	define('_MI_VOD_VIDEO_WIDTH_LIST', 'Default Listview Video Width');
	define('_MI_VOD_VIDEO_WIDTH_LIST_DESC', '');
	define('_MI_VOD_VIDEO_HEIGHT_LIST', 'Default Listview Video Height');
	define('_MI_VOD_VIDEO_HEIGHT_LIST_DESC', '');
	define('_MI_VOD_VIDEO_WIDTH_BLOCK', 'Default Block Video Width');
	define('_MI_VOD_VIDEO_WIDTH_BLOCK_DESC', '');
	define('_MI_VOD_VIDEO_HEIGHT_BLOCK', 'Default Block Video Height');
	define('_MI_VOD_VIDEO_HEIGHT_BLOCK_DESC', '');

	
?>