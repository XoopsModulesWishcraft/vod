<?php

	//Smarty Template Constants
	define('_AM_VOD_VIDEOS_H1', 'Video Streams');
	define('_AM_VOD_VIDEOS_P', 'These are the video streams in the database.');
	define('_AM_VOD_NEW_VIDEOS_H1', 'New Video Stream');
	define('_AM_VOD_NEW_VIDEOS_P', 'Create a new video stream for block or default display!');
	define('_AM_VOD_EDIT_VIDEOS_H1', 'Edit Video Stream');
	define('_AM_VOD_EDIT_VIDEOS_P', 'Edit your video stream!');
	
	// Table headers
	define('_AM_VOD_TH_ACTIONS', 'Actions');
	define('_AM_VOD_TH_FID', 'Video ID');
	define('_AM_VOD_TH_NAME', 'Video Name');
	define('_AM_VOD_TH_REFERENCE', 'Video Reference');
	define('_AM_VOD_TH_SOURCE', 'Video Source');
	define('_AM_VOD_TH_WIDTH', 'Width');
	define('_AM_VOD_TH_HEIGHT', 'Height');
	define('_AM_VOD_TH_DEFAULT', 'Default');
	define('_AM_VOD_TH_CREATED', 'Created');
	define('_AM_VOD_TH_UPDATED', 'Updated');

	// redirection messages
	define('_AM_VOD_MSG_VIDEOS_SAVEDOKEY', 'Video Saved Fine!');
	define('_AM_VOD_MSG_VIDEOS_FAILEDTOSAVE', 'Failed to save Video!');
	define('_AM_VOD_MSG_VIDEOS_FAILEDTODELETE', 'Failed to Delete video!');
	define('_AM_VOD_MSG_VIDEOS_DELETED', 'Video Deleted Successfully!');
	define('_AM_VOD_MSG_VIDEOS_DELETE', 'Are you sure you wish to delete, "%s%"?');
	
	//VErsion 1.05
	// Dashboard
	define('_AM_VOD_ADMIN_COUNTS', 'Streams and None Streams!!');
	define('_AM_VOD_ADMIN_THEREARE_FLATFILES', 'Total Flat Files on File: %s');
	define('_AM_VOD_ADMIN_THEREARE_RTMPSTREAMS', 'Total RTMP Streams on File: %s');
	define('_AM_VOD_ADMIN_THEREARE_RTSPSTREAMS', 'Total RTSP Streams on File: %s');
	define('_AM_VOD_ADMIN_THEREARE_IOSSTREAMS', 'Total iOS Streams on File: %s');
	define('_AM_VOD_ADMIN_THEREARE_FLASHSTREAMS', 'Total Flash Streams on File: %s');
	define('_AM_VOD_ADMIN_THEREARE_SILVERLIGHTSTREAMS', 'Total Silverlight Streams on File: %s');
	define('_AM_VOD_ADMIN_DEFAULT', 'Default Video');

	// Version 1.10
		//Smarty Template Constants
	define('_AM_VOD_MIMETYPES_H1', 'Mimetype Streams');
	define('_AM_VOD_MIMETYPES_P', 'These are the Mimetype streams in the database.');
	define('_AM_VOD_NEW_MIMETYPES_H1', 'New Mimetype Stream');
	define('_AM_VOD_NEW_MIMETYPES_P', 'Create a new Mimetype stream for block or default display!');
	define('_AM_VOD_EDIT_MIMETYPES_H1', 'Edit Mimetype Stream');
	define('_AM_VOD_EDIT_MIMETYPES_P', 'Edit your Mimetype stream!');
	
	// Table headers
	define('_AM_VOD_TH_MID', 'Mimetype ID');
	define('_AM_VOD_TH_SUPPORT', 'Video Supporting Videos');
	define('_AM_VOD_TH_MIMETYPE', 'Mime-type');
	define('_AM_VOD_TH_CODECS', 'Codecs');
	
	// redirection messages
	define('_AM_VOD_MSG_MIMETYPES_SAVEDOKEY', 'Mimetype Saved Fine!');
	define('_AM_VOD_MSG_MIMETYPES_FAILEDTOSAVE', 'Failed to save Mimetype!');
	define('_AM_VOD_MSG_MIMETYPES_FAILEDTODELETE', 'Failed to Delete Mimetype!');
	define('_AM_VOD_MSG_MIMETYPES_DELETED', 'Mimetype Deleted Successfully!');
	define('_AM_VOD_MSG_MIMETYPES_DELETE', 'Are you sure you wish to delete, "%s%"?');
	
	// Version 1.12
	define('_AM_VOD_USERAGENTS_H1', 'User Agent Spy');
	define('_AM_VOD_USERAGENTS_P', 'This is the useragents encountered and what videos was presented to them at the time.');
	
	// Table headers
	define('_AM_VOD_TH_TIME', 'Time Encountered');
	define('_AM_VOD_TH_VIDEOS', 'Videos Used');
	define('_AM_VOD_TH_AGENTS', 'User Agent');
	define('_AM_VOD_TH_USER', 'XOOPS Username');

	// Version 1.13
	// Table Header
	define('_AM_VOD_TH_RAW', 'Video RAW Path');
	
	// Dashboard
	define('_AM_VOD_ADMIN_THEREARE_HTTPSTREAMS', 'Number of HTTP(s) Streams: %s');
	define('_AM_VOD_ADMIN_THEREARE_MIMETYPES', 'Number of Mimetypes: %s');
	
	// Version 1.15
	// Table headers
	define('_AM_VOD_TH_CID', 'Category');
	define('_AM_VOD_TH_PID', 'Package');
	define('_AM_VOD_TH_TOKENS', 'Viewing Tokens');
	define('_AM_VOD_TH_PRICE', 'Viewing Price');
	define('_AM_VOD_TH_CURRENCY', 'Curreny');
	define('_AM_VOD_TH_PURCHASES', 'Purchases');
	define('_AM_VOD_TH_VIEWS', 'Viewing');
	define('_AM_VOD_TH_HITS', 'Page Hits');
	define('_AM_VOD_TH_PURCHASED', 'Last Purchased');
	define('_AM_VOD_TH_COST_USD', 'Cost (USD)');
	define('_AM_VOD_TH_COST_AUD', 'Cost (AUD)');
	define('_AM_VOD_TH_COST', 'Cost');
	define('_AM_VOD_TH_VID', 'Video');
	define('_AM_VOD_TH_SESSID', 'Cart Session ID');
	define('_AM_VOD_TH_LOG_ID', 'Log ID');
	define('_AM_VOD_TH_CLASS', 'Class');
	define('_AM_VOD_TH_ID', 'Object ID');
	define('_AM_VOD_TH_UID', 'User');
	define('_AM_VOD_TH_STATUS', 'Status');
	define('_AM_VOD_TH_COMMENT', 'Comment');
	define('_AM_VOD_TH_PARENT', 'Parent Category');
	define('_AM_VOD_TH_PREFIX', 'Catalogue Prefix');
	define('_AM_VOD_TH_EARNING_USD', 'Earning (USD)');
	define('_AM_VOD_TH_EARNING_AUD', 'Earning (AUD)');
	define('_AM_VOD_TH_DISCOUNTS_USD', 'Discounts (USD)');
	define('_AM_VOD_TH_TH_DISCOUNTS_AUD', 'Discounts (AUD)');
	define('_AM_VOD_TH_ALIAS', 'Alias');
	define('_AM_VOD_TH_CODE', 'ISO Code');
	define('_AM_VOD_TH_LEFT', 'Left Symbol');
	define('_AM_VOD_TH_RIGHT', 'Right Symbol');
	define('_AM_VOD_TH_RATE', 'Exchange Rate');
	define('_AM_VOD_TH_DECIMALS', 'Decimals');
	define('_AM_VOD_TH_SESSION_ID', 'PHP Session ID');
	define('_AM_VOD_TH_IID', 'Invoice ID');
	define('_AM_VOD_TH_EMAIL', 'Clients Email');
	define('_AM_VOD_TH_IP', 'IP Address');
	define('_AM_VOD_TH_VIDEOS', 'No. Videos');
	define('_AM_VOD_TH_TOTAL_USD', 'Total of Cart (USD)');
	define('_AM_VOD_TH_TOTAL_AUD', 'Total of Cart (AUD)');
	define('_AM_VOD_TH_TOTAL', 'Total of Cart');
	define('_AM_VOD_TH_INVOICED', 'Invoiced On');
	define('_AM_VOD_TH_PAID', 'Paid On');
	define('_AM_VOD_TH_CANCELED', 'Canceled On');
		
	// Page captions
	define('_AM_VOD_LOG_H1', 'Activities Log');
	define('_AM_VOD_LOG_P', 'From here you can browse the activities log, which shows all the changes in the system within the predefined range of the preferences.');
	define('_AM_VOD_EDIT_CATEGORY_H1', 'Edit Existing Category');
	define('_AM_VOD_EDIT_CATEGORY_P', 'From here you can edit your existing category.');
	define('_AM_VOD_CATEGORY_H1', 'Categories');
	define('_AM_VOD_CATEGORY_P', 'These are the categories on the system so far.');
	define('_AM_VOD_NEW_CATEGORY_H1', 'New Category');
	define('_AM_VOD_NEW_CATEGORY_P', 'Create a new category with the form below.');
	define('_AM_VOD_EDIT_CURRENCY_H1', 'Edit Currency');
	define('_AM_VOD_EDIT_CURRENCY_P', 'Edit an existing currency.');
	define('_AM_VOD_CURRENCY_H1', 'Currencies');
	define('_AM_VOD_CURRENCY_P', 'This is all the currencies your system support, remember it will only get the exchange rate for the top 35 currencies automatically.');
	define('_AM_VOD_NEW_CURRENCY_H1', 'New Currency');
	define('_AM_VOD_NEW_CURRENCY_P', 'Create a new currency with the form below.');
	define('_AM_VOD_EDIT_SESSIONS_H1', 'Edit Cart Session');
	define('_AM_VOD_EDIT_SESSIONS_P', 'From here with this form you can edit a shopping carts sessions.');
	define('_AM_VOD_SESSIONS_H1', 'Cart Sessions');
	define('_AM_VOD_SESSIONS_P', 'This is the video on demand shopping cart sessions.');
	define('_AM_VOD_NEW_SESSIONS_H1', 'New Cart Session');
	define('_AM_VOD_NEW_SESSIONS_P', 'From here you can create a new shopping cart session with the form below.');
	define('_AM_VOD_EDIT_CART_H1', 'Edit Cart Item');
	define('_AM_VOD_EDIT_CART_P', 'From here you can edit a cart item.');
	define('_AM_VOD_CART_H1', 'Cart Items');
	define('_AM_VOD_CART_P', 'This is all the items in the sessions cart list.');
	define('_AM_VOD_NEW_CART_H1', 'New Cart Item');
	define('_AM_VOD_NEW_CART_P', 'You can add an item to a cart with the form below.');
	
	// redirection messages
	define('_AM_VOD_MSG_SESSIONS_SAVEDOKEY', 'Session Saved Fine!');
	define('_AM_VOD_MSG_SESSIONS_FAILEDTOSAVE', 'Failed to save Session!');
	define('_AM_VOD_MSG_SESSIONS_FAILEDTODELETE', 'Failed to Delete Session!');
	define('_AM_VOD_MSG_SESSIONS_DELETED', 'Session Deleted Successfully!');
	define('_AM_VOD_MSG_SESSIONS_DELETE', 'Are you sure you wish to delete, "%s%"?');
	define('_AM_VOD_MSG_CURRENCY_SAVEDOKEY', 'Curreny Saved Fine!');
	define('_AM_VOD_MSG_CURRENCY_FAILEDTOSAVE', 'Failed to save Curreny!');
	define('_AM_VOD_MSG_CURRENCY_FAILEDTODELETE', 'Failed to Delete Curreny!');
	define('_AM_VOD_MSG_CURRENCY_DELETED', 'Curreny Deleted Successfully!');
	define('_AM_VOD_MSG_CURRENCY_DELETE', 'Are you sure you wish to delete, "%s%"?');
	define('_AM_VOD_MSG_CART_SAVEDOKEY', 'Cart Item Saved Fine!');
	define('_AM_VOD_MSG_CART_FAILEDTOSAVE', 'Failed to save Cart Item!');
	define('_AM_VOD_MSG_CART_FAILEDTODELETE', 'Failed to Delete Cart Item!');
	define('_AM_VOD_MSG_CART_DELETED', 'Cart Item Deleted Successfully!');
	define('_AM_VOD_MSG_CART_DELETE', 'Are you sure you wish to delete, "%s%"?');
	define('_AM_VOD_MSG_CATEGORY_NEEDTOADD_FIRST', 'Before you can add a video you need to create a category first to put it in!');
	define('_AM_VOD_MSG_CATEGORY_SAVEDOKEY', 'Category Saved Fine!');
	define('_AM_VOD_MSG_CATEGORY_FAILEDTOSAVE', 'Failed to save Category!');
	define('_AM_VOD_MSG_CATEGORY_FAILEDTODELETE', 'Failed to Delete Category!');
	define('_AM_VOD_MSG_CATEGORY_DELETED', 'Category Deleted Successfully!');
	define('_AM_VOD_MSG_CATEGORY_DELETE', 'Are you sure you wish to delete, "%s%"?');
	
?>