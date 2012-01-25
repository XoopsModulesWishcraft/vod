<?php
require_once(dirname(dirname(__FILE__)).'/include/functions.php');

function b_vod_block_videos_show( $options )
{
	if (!isset($GLOBALS['_done']))
		$GLOBALS['_done'] = array();
		
	$videos_handler = xoops_getmodulehandler('videos', 'vod');
	if (!$videos = $videos_handler->get($options[0]))
		return false;
	
	$mode = $videos->getModeWithUserAgent();
	$block = array();
	$block['width'] = $options[1]; 
	$block['height'] = $options[2];
	$block['id'] = $options[3];
	
	if (!isset($GLOBALS['_done'][$block['id']])) {
		
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
			$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey'] = sha1(XOOPS_LICENSE_KEY.$GLOBALS['vodModuleConfig']['salt'].$videos->getIP().date('Ymdhis'));
			$_SESSION['vod'][$videos->getIP()][$block['id']][$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey']]['vid'] = $videos->getVar('vid');
			$_SESSION['vod'][$videos->getIP()][$block['id']][$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey']][$videos->getVar('vid')]['block'] = '1';
			$_SESSION['vod'][$videos->getIP()][$block['id']][$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey']][$videos->getVar('vid')]['width'] = $block['width'];
			$_SESSION['vod'][$videos->getIP()][$block['id']][$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey']][$videos->getVar('vid')]['height'] = $block['height'];
			$_SESSION['vod'][$videos->getIP()][$block['id']][$_SESSION['vod'][$videos->getIP()][$block['id']]['passkey']][$videos->getVar('vid')]['resolve'] = $block['id'];
			
			$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), str_replace('%resolve%', $block['id'], $videos->getJS(true)));
			$block['html'] = false;
		} else {
			$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getInsecureJS(true));
			$block['html'] = $videos->getHTML(true, $block['width'], $block['height']);
		}
		$GLOBALS['_done'][$block['id']]=true;
		return $block;
	}
	return false;
}


function b_vod_block_videos_edit( $options )
{
	xoops_loadLanguage('blocks', 'vod');
	
	include_once($GLOBALS['xoops']->path('/modules/vod/include/formobjects.vod.php'));

	$vid = new VodFormSelectVideos('', 'options[0]', $options[0]);
	$width = new XoopsFormText('', 'options[1]', 15, 10, $options[1]);
	$height = new XoopsFormText('', 'options[2]', 15, 10, $options[2]);
	$reference = new XoopsFormText('', 'options[3]', 25, 40, $options[3]);
	$form = constant('_BL_VOD_BLOCK_FID').$vid->render()."<br/>".constant('_BL_VOD_BLOCK_WIDTH').$width->render()."<br/>".constant('_BL_VOD_BLOCK_HEIGHT').$height->render().'<br/>'.constant('_BL_VOD_BLOCK_REFERENCE').$reference->render();
	return $form ;
}
?>