<?php

// $Id: search.inc.php,v 4.04 2008/06/05 15:35:33 wishcraft Exp $

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

function vod_search($queryarray, $andor, $limit, $offset, $userid)
{
	
	$uid = (is_object($GLOBALS['xoopsUser'])&&$GLOBALS['xoopsUser']->isactive())?$GLOBALS['xoopsUser']->getVar('uid'):0;

	if ($uid>0&&$uid==$userid) {
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		$sessids = $sessions_handler->getSessIDFromUID($uid);
		$cart_handler = xoops_getmodulehandler('cart', 'vod');
		$criteria = new CriteriaCompo(new Criteria('`mode`', '_VOD_ENUM_PAID'));
		$criteria->add(new Criteria('expires', time(), '>='));
		$criteria->add(new Criteria('sessid', '('.implode(',', $sessids).')', 'IN'));
		if ($carts = $cart_handler->getObjects($criteria, true)) {
			$videos_handler = xoops_getmodulehandler('videos', 'vod');
			foreach($carts as $crtid => $cart) {
				$video = $videos_handler->get($cart->getVar('vid'));
				$ret[$i]['link'] = XOOPS_URL.'/modules/vod/'."index.php?op=videos&fct=view&vid=".$cart->getVar('vid')."&cid=".$cart->getVar('cid')."&crtid=".$cart->getVar('crtid')."&sessid=".$cart->getVar('sessid');
				$ret[$i]['title'] = 'Watch Now: '.$video->getVar('name');
				$ret[$i]['time'] = $cart->getVar('paid');
				$ret[$i]['uid'] = $uid;
				$i++;
			}	
		} 
	}
	
	if ($userid>0&&$userid!=$uid) {
		
	}
 	
	return $ret;
}
?>