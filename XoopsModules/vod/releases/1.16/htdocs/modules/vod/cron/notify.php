<?php

	include_once dirname(dirname(__FILE__)).'/header.php';
	
	xoops_loadLanguage('email', 'vod');
	
	$videos_handler = xoops_getmodulehandler('videos', 'vod');
	$category_handler = xoops_getmodulehandler('category', 'vod');
	$cart_handler = xoops_getmodulehandler('cart', 'vod');
	$sessions_handler = xoops_getmodulehandler('sessions', 'vod');

	$criteria = new CriteriaCompo(new Criteria('`paid`', '0', '>'));
	$criteria->add(new Criteria(`notified`, '0', '='));
	$criteria->setSort('RAND()');
	$criteria->setOrder('ASC');
	$criteria->setLimit(($GLOBALS['vodModuleConfig']['notify']>0?$GLOBALS['vodModuleConfig']['notify']:30));
	
	$sessions = array();
	$categories = array();
	$videos = array();
	foreach($cart_handler->getObjects($criteria, true) as $crtid => $cart) {
		if (!isset($sessions[$cart->getVar('sessid')])) {
			$sessions[$cart->getVar('sessid')] = $sessions_handler->get($cart->getVar('sessid')); 
		}
		if (!isset($categories[$cart->getVar('cid')])) {
			$categories[$cart->getVar('cid')] = $category_handler->get($cart->getVar('cid')); 
		}
		if (!isset($videos[$cart->getVar('vid')])) {
			$videos[$cart->getVar('vid')] = $videos_handler->get($cart->getVar('vid')); 
		}
		if (is_object($sessions[$cart->getVar('sessid')]) &&
			is_object($videos[$cart->getVar('vid')]) &&
			is_object($categories[$cart->getVar('cid')])) {
				
			$xoopsMailer =& getMailer();
			$xoopsMailer->setHTML(true);
			$xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/vod/language/'.$GLOBALS['xoopsConfig']['language'].'/mail_templates/'));
			$xoopsMailer->setTemplate('vod_video_available_cart.html');
			$xoopsMailer->setSubject(sprintf(_VOD_EMAIL_VIDEO_AVAILABLE_SUBJECT, $videos[$cart->getVar('vid')]->getVar('name'), date(_DATESTRING, time().$GLOBALS['vodModuleConfig']['purchase_expires'])));
			
			$xoopsMailer->setToEmails($sessions[$cart->getVar('sessid')]->getVar('email'));
			
			$xoopsMailer->assign("SITEURL", XOOPS_URL);
			$xoopsMailer->assign("SITENAME", $GLOBALS['xoopsConfig']['sitename']);
			$xoopsMailer->assign("AVATA", XOOPS_URL.$videos[$cart->getVar('vid')]->getVar('path').$videos[$cart->getVar('vid')]->getVar('avata'));
			$xoopsMailer->assign("VIDEO", $videos[$cart->getVar('vid')]->getVar('name'));
			$xoopsMailer->assign("PRODUCEDBY", $videos[$cart->getVar('vid')]->getVar('producedby'));
			$xoopsMailer->assign("STARING", $videos[$cart->getVar('vid')]->getVar('staring'));
			$xoopsMailer->assign("YEAR", $videos[$cart->getVar('vid')]->getVar('year'));
			$xoopsMailer->assign("LENGTH", $videos[$cart->getVar('vid')]->getVar('length'));
			$xoopsMailer->assign("SUMMARY", $videos[$cart->getVar('vid')]->getVar('summary'));
			$xoopsMailer->assign("CATEGORY", $categories[$cart->getVar('cid')]->getVar('name'));
			$xoopsMailer->assign("URL", $videos[$cart->getVar('vid')]->getViewingURL($sessions[$cart->getVar('sessid')], $cart));
			$xoopsMailer->assign("EXPIRES", date(_DATESTRING, time().$GLOBALS['vodModuleConfig']['purchase_expires']));
			$xoopsMailer->assign("INVURL", $sessions[$cart->getVar('sessid')]->getVar('url'));
			
			if ($GLOBALS['vodModuleConfig']['matrixstream']==true&&$sessions[$cart->getVar('sessid')]->getVar('uid')>0&&$cart->getVar('pid')>0) {
				$subscriber_handler = xoops_getmodulehandler('subscriber', 'matrixstream');
				$subscriber = $subscriber_handler->getSubscriberWithUID($sessions[$cart->getVar('sessid')]->getVar('uid'));
				$packages_handler=xoops_getmodulehandler('packages', 'matrixstream');
				$package=$packages_handler->get($cart->getVar('pid'));	
				$subscribed_handler = xoops_getmodulehandler('subscribed', 'matrixstream');
				$subscription = $subscribed_handler->create();
				$subscription->setVar('sub_id', $subscriber->getVar('sub_id'));
				$subscription->setVar('isp_id', $subscriber->getVar('isp_id'));
				$subscription->setVar('pid', $package->getVar('pid'));
				$subscription->setVar('packageid', $package->getVar('packageid'));
				$subscription->setVar('packagetype', $package->getVar('packagetype'));
				if ($subscribed_handler->insert($subscription, true)) {
					if($xoopsMailer->send() ){
						$cart->setVar('notified', time());
						$cart->setVar('expires', time().$GLOBALS['vodModuleConfig']['purchase_expires']);
						$cart_handler->insert($cart);
					}
				}
			} else {
				if($xoopsMailer->send() ){
					$cart->setVar('notified', time());
					$cart->setVar('expires', time().$GLOBALS['vodModuleConfig']['purchase_expires']);
					$cart_handler->insert($cart);
				}
			}
		}
	}
	
	$criteria = new CriteriaCompo(new Criteria('`paid`', '0', '>'));
	$criteria->add(new Criteria(`notified`, '0', '>'));
	$criteria->add(new Criteria(`claimed`, '0', '='));
	$criteria->add(new Criteria(`closed`, '0', '='));
	$criteria->add(new Criteria(`expires`, time(), '<'));
	$criteria->setSort('RAND()');
	$criteria->setOrder('ASC');
	$criteria->setLimit(($GLOBALS['vodModuleConfig']['notify']>0?$GLOBALS['vodModuleConfig']['notify']:30));
	
	$sessions = array();
	$categories = array();
	$videos = array();
	foreach($cart_handler->getObjects($criteria, true) as $crtid => $cart) {
		if (!isset($sessions[$cart->getVar('sessid')])) {
			$sessions[$cart->getVar('sessid')] = $sessions_handler->get($cart->getVar('sessid')); 
		}
		if (!isset($categories[$cart->getVar('cid')])) {
			$categories[$cart->getVar('cid')] = $category_handler->get($cart->getVar('cid')); 
		}
		if (!isset($videos[$cart->getVar('vid')])) {
			$videos[$cart->getVar('vid')] = $videos_handler->get($cart->getVar('vid')); 
		}
		if (is_object($sessions[$cart->getVar('sessid')]) &&
			is_object($videos[$cart->getVar('vid')]) &&
			is_object($categories[$cart->getVar('cid')])) {
				
			$xoopsMailer =& getMailer();
			$xoopsMailer->setHTML(true);
			$xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/vod/language/'.$GLOBALS['xoopsConfig']['language'].'/mail_templates/'));
			$xoopsMailer->setTemplate('vod_video_expired_cart.html');
			$xoopsMailer->setSubject(sprintf(_VOD_EMAIL_VIDEO_EXPIRED_SUBJECT, $videos[$cart->getVar('vid')]->getVar('name'), date(_DATESTRING, $cart->getVar('expires'))));
			
			$xoopsMailer->setToEmails($sessions[$cart->getVar('sessid')]->getVar('email'));
			
			$xoopsMailer->assign("SITEURL", XOOPS_URL);
			$xoopsMailer->assign("SITENAME", $GLOBALS['xoopsConfig']['sitename']);
			$xoopsMailer->assign("AVATA", XOOPS_URL.$videos[$cart->getVar('vid')]->getVar('path').$videos[$cart->getVar('vid')]->getVar('avata'));
			$xoopsMailer->assign("VIDEO", $videos[$cart->getVar('vid')]->getVar('name'));
			$xoopsMailer->assign("SUMMARY", $videos[$cart->getVar('vid')]->getVar('summary'));
			$xoopsMailer->assign("CATEGORY", $categories[$cart->getVar('cid')]->getVar('name'));
			$xoopsMailer->assign("EXPIRES", date(_DATESTRING, $cart->getVar('expires')));
			
			if ($GLOBALS['vodModuleConfig']['matrixstream']==true&&$sessions[$cart->getVar('sessid')]->getVar('uid')>0&&$cart->getVar('pid')>0) {
				$subscriber_handler = xoops_getmodulehandler('subscriber', 'matrixstream');
				$subscriber = $subscriber_handler->getSubscriberWithUID($sessions[$cart->getVar('sessid')]->getVar('uid'));
				$packages_handler=xoops_getmodulehandler('packages', 'matrixstream');
				$package=$packages_handler->get($cart->getVar('pid'));	
				$subscribed_handler = xoops_getmodulehandler('subscribed', 'matrixstream');
				$criteria = new CriteriaCompo(new Criteria('unsubscribed', '0'));
				$criteria->add(new Criteria('sub_id', $subscriber->getVar('sub_id')));
				$criteria->add(new Criteria('isp_id', $subscriber->getVar('isp_id')));
				$criteria->add(new Criteria('pid', $package->getVar('pid')));
				if ($subscribed_handler->getCount($criteria)>0) {
					foreach($subscribed_handler->getObjects($criteria, true) as $pack_id => $subscription) {
						if ($subscribed_handler->delete($subscription)) {
							if($xoopsMailer->send() ){
								$cart->setVar('closed', time());
								$cart_handler->insert($cart);
							}
						}
					}
				}
			} else {
				if($xoopsMailer->send() ){
					$cart->setVar('closed', time());
					$cart_handler->insert($cart);
				}
			}
		}
	}
	
	$criteria = new CriteriaCompo(new Criteria('`paid`', '0', '>'));
	$criteria->add(new Criteria(`notified`, '0', '>'));
	$criteria->add(new Criteria(`claimed`, '0', '>'));
	$criteria->add(new Criteria(`closed`, '0', '='));
	$criteria->add(new Criteria(`expires`, time(), '<'));
	$criteria->setSort('RAND()');
	$criteria->setOrder('ASC');
	$criteria->setLimit(($GLOBALS['vodModuleConfig']['notify']>0?$GLOBALS['vodModuleConfig']['notify']:30));
	
	$sessions = array();
	$categories = array();
	$videos = array();
	foreach($cart_handler->getObjects($criteria, true) as $crtid => $cart) {
		if (!isset($sessions[$cart->getVar('sessid')])) {
			$sessions[$cart->getVar('sessid')] = $sessions_handler->get($cart->getVar('sessid')); 
		}
		if (!isset($categories[$cart->getVar('cid')])) {
			$categories[$cart->getVar('cid')] = $category_handler->get($cart->getVar('cid')); 
		}
		if (!isset($videos[$cart->getVar('vid')])) {
			$videos[$cart->getVar('vid')] = $videos_handler->get($cart->getVar('vid')); 
		}
		if (is_object($sessions[$cart->getVar('sessid')]) &&
			is_object($videos[$cart->getVar('vid')]) &&
			is_object($categories[$cart->getVar('cid')])) {
				
			$xoopsMailer =& getMailer();
			$xoopsMailer->setHTML(true);
			$xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/vod/language/'.$GLOBALS['xoopsConfig']['language'].'/mail_templates/'));
			$xoopsMailer->setTemplate('vod_video_expired_viewing_cart.html');
			$xoopsMailer->setSubject(sprintf(_VOD_EMAIL_VIDEO_EXPIRED_SUBJECT, $videos[$cart->getVar('vid')]->getVar('name'), date(_DATESTRING, $cart->getVar('expires'))));
			
			$xoopsMailer->setToEmails($sessions[$cart->getVar('sessid')]->getVar('email'));
			
			$xoopsMailer->assign("SITEURL", XOOPS_URL);
			$xoopsMailer->assign("SITENAME", $GLOBALS['xoopsConfig']['sitename']);
			$xoopsMailer->assign("AVATA", XOOPS_URL.$videos[$cart->getVar('vid')]->getVar('path').$videos[$cart->getVar('vid')]->getVar('avata'));
			$xoopsMailer->assign("VIDEO", $videos[$cart->getVar('vid')]->getVar('name'));
			$xoopsMailer->assign("SUMMARY", $videos[$cart->getVar('vid')]->getVar('summary'));
			$xoopsMailer->assign("CATEGORY", $categories[$cart->getVar('cid')]->getVar('name'));
			$xoopsMailer->assign("EXPIRES", date(_DATESTRING, $cart->getVar('expires')));
			
			if ($GLOBALS['vodModuleConfig']['matrixstream']==true&&$sessions[$cart->getVar('sessid')]->getVar('uid')>0&&$cart->getVar('pid')>0) {
				$subscriber_handler = xoops_getmodulehandler('subscriber', 'matrixstream');
				$subscriber = $subscriber_handler->getSubscriberWithUID($sessions[$cart->getVar('sessid')]->getVar('uid'));
				$packages_handler=xoops_getmodulehandler('packages', 'matrixstream');
				$package=$packages_handler->get($cart->getVar('pid'));	
				$subscribed_handler = xoops_getmodulehandler('subscribed', 'matrixstream');
				$criteria = new CriteriaCompo(new Criteria('unsubscribed', '0'));
				$criteria->add(new Criteria('sub_id', $subscriber->getVar('sub_id')));
				$criteria->add(new Criteria('isp_id', $subscriber->getVar('isp_id')));
				$criteria->add(new Criteria('pid', $package->getVar('pid')));
				if ($subscribed_handler->getCount($criteria)>0) {
					foreach($subscribed_handler->getObjects($criteria, true) as $pack_id => $subscription) {
						if ($subscribed_handler->delete($subscription)) {
							if($xoopsMailer->send() ){
								$cart->setVar('closed', time());
								$cart_handler->insert($cart);
							}
						}
					}
				}
			} else {
				if($xoopsMailer->send() ){
					$cart->setVar('closed', time());
					$cart_handler->insert($cart);
				}
			}
		}
	}
?>