<?php
	
	require_once($GLOBALS['xoops']->path('class/xoopsformloader.php'));
	require_once($GLOBALS['xoops']->path('class/pagenav.php'));
	
	require_once('formselectvideos.php');
	require_once('formselectsupport.php');
	require_once('formselectmimetype.php');
	require_once('formselectpackageid.php');
	require_once('formselectcurrency.php');
	require_once('formselectcategory.php');
	
	if (file_exists($GLOBALS['xoops']->path('/modules/tag/include/formtag.php')) && $GLOBALS['vodModuleConfig']['tags'])
		include_once $GLOBALS['xoops']->path('/modules/tag/include/formtag.php');
?>