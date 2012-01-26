<?php
	function PaidVodHook($invoice) {
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		$session = $sessions_handler->getByKey($invoice->getVar('key'));
		if (is_a($session, 'VodSessions')) {
			$session->setVar('mode', '_ENUM_VOD_PAID');
			$session->setVar('url', $invoice->getURL());
			$session->setVar('iid', $invoice->getVar('iid'));
			$sessions_handler->insert($session, true);
		}
		
		include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');		
		return PaidXPaymentHook($invoice);
		
	}
	
	function UnpaidVodHook($invoice) {
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		$session = $sessions_handler->getByKey($invoice->getVar('key'));
		if (is_a($session, 'VodSessions')) {
			$session->setVar('mode', '_ENUM_VOD_INVOICED');
			$session->setVar('url', $invoice->getURL());
			$session->setVar('iid', $invoice->getVar('iid'));
			$sessions_handler->insert($session, true);
		}
		include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
		return UnpaidXPaymentHook($invoice);		
	}
	
	function CancelVodHook($invoice) {
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		$session = $sessions_handler->getByKey($invoice->getVar('key'));
		if (is_a($session, 'VodSessions')) {
			$session->setVar('mode', '_ENUM_VOD_CANCELED');
			$session->setVar('url', $invoice->getURL());
			$session->setVar('iid', $invoice->getVar('iid'));
			$sessions_handler->insert($session, true);
		}
		
		include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
		return CancelXPaymentHook($invoice);
	}
	
	?>