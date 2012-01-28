<?php
	require_once (dirname(dirname(dirname(__FILE__))).'/mainfile.php');
		
	xoops_load('xoopsmultimailer');
	
	require_once('include/functions.php');
	require_once('include/formobjects.vod.php');
	require_once('include/forms.vod.php');
	
	xoops_loadLanguage('modinfo', 'vod');
	
	$config_handler = xoops_gethandler('config');
	$module_handler = xoops_gethandler('module');
	
	$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
	$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
	
	xoops_loadLanguage('main', 'vod');
	
	if (isset($_GET['_returned']))
		$GLOBALS['_returned'] = unserialize($_GET['_returned']);
	else 
		$GLOBALS['_returned'] = array();
	$GLOBALS['_done'] = array();
?>