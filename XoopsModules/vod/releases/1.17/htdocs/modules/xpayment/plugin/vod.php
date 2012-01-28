<?php
	function PaidVodHook($invoice) {
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
		$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		$currency_handler = xoops_getmodulehandler('currency', 'vod');
		$session = $sessions_handler->getByKey($invoice->getVar('key'));
		if (is_a($session, 'VodSessions')) {
			if ($invoice->getVar('discount')>0) {
				$session->setVar('discounted', true);
				$session->setVar('discount', $invoice->getVar('discount_amount'));
				$session->setVar('discount_usd', $currency_handler->getAmountRate($invoice->getVar('currency'), 'USD', $invoice->getVar('discount_amount')));
				$session->setVar('discount_aud', $currency_handler->getAmountRate($invoice->getVar('currency'), 'AUD', $invoice->getVar('discount_amount')));
			}
			$session->setVar('mode', '_VOD_ENUM_PAID');
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
			$session->setVar('mode', '_VOD_ENUM_INVOICED');
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
			$session->setVar('mode', '_VOD_ENUM_CANCELED');
			$session->setVar('url', $invoice->getURL());
			$session->setVar('iid', $invoice->getVar('iid'));
			$sessions_handler->insert($session, true);
		}
		
		include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
		return CancelXPaymentHook($invoice);
	}
	
	?>