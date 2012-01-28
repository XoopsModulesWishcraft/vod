<?php
	
	$module_handler = xoops_gethandler('module');
	$config_handler = xoops_gethandler('config');
	if (!isset($GLOBALS['vodModule']))
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
	if (!isset($GLOBALS['vodModuleConfig']))
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid')); 
	
	require_once($GLOBALS['xoops']->path('class/xoopsformloader.php'));
	require_once($GLOBALS['xoops']->path('class/pagenav.php'));
	
	require_once('formselectvideos.php');
	require_once('formselectsupport.php');
	require_once('formselectmimetype.php');
	if ($GLOBALS['vodModuleConfig']['matrixstream']) {
		require_once('formselectpackageid.php');
	}
	require_once('formselectcurrency.php');
	require_once('formselectcategory.php');
	
	if (file_exists($GLOBALS['xoops']->path('/modules/tag/include/formtag.php')) && $GLOBALS['vodModuleConfig']['tags'])
		include_once $GLOBALS['xoops']->path('/modules/tag/include/formtag.php');
?>