<?php

if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}

include_once $GLOBALS['xoops']->path('modules/vod/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/formobjects.vod.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/forms.vod.php');

class VodSessions extends XoopsObject 
{
	var $_objects = array();
		
    function __construct()
    {
        $this->initVar('sessid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('mode', XOBJ_DTYPE_ENUM, '_VOD_ENUM_UNINVOICED', false, false, false, array('_VOD_ENUM_UNINVOICED','_VOD_ENUM_INVOICED','_VOD_ENUM_PAID','_VOD_ENUM_CANCELED'));
		$this->initVar('salt', XOBJ_DTYPE_TXTBOX, vod_getToken(), false, 64);
        $this->initVar('session_id', XOBJ_DTYPE_TXTBOX, session_id(), false, 64);
        $this->initVar('iid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 198);
        $this->initVar('pass', XOBJ_DTYPE_TXTBOX, null, false, 32);
        $this->initVar('attempts', XOBJ_DTYPE_INT, null, false, 3);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('netaddy', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('videos', XOBJ_DTYPE_INT, 2, false);
        $this->initVar('discounted', XOBJ_DTYPE_INT, false, false);
        $this->initVar('discount', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('discount_usd', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('discount_aud', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('tokens', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('total_usd', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('total_aud', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('total', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('currency', XOBJ_DTYPE_TXTBOX, 0, false, 3);
        $this->initVar('paidkey', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('invoiced', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('paid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('canceled', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('expires', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
        
    }
    
    function inCart($object) {
    	$cart_handler = xoops_getmodulehandler('cart', 'vod');
    	$criteria = new CriteriaCompo(new Criteria('`mode`', '_VOD_ENUM_UNPAID'));
    	$criteria->add(new Criteria('sessid', $this->getVar('sessid')));
    	$criteria->add(new Criteria('vid', $object->getVar('vid')));
    	if ($cart_handler->getCount($criteria)>0)
    		return true;
    	else
    		return false;	
    }

    function addVideo($object) {
    	$cart_handler = xoops_getmodulehandler('cart', 'vod');
    	$currency_handler = xoops_getmodulehandler('currency', 'vod');
    	$criteria = new CriteriaCompo(new Criteria('`mode`', '_VOD_ENUM_UNPAID'));
    	$criteria->add(new Criteria('sessid', $this->getVar('sessid')));
    	$criteria->add(new Criteria('vid', $object->getVar('vid')));
    	if ($cart_handler->getCount($criteria)==0) {
    		$item = $cart_handler->create();
    		$item->setVar('mode', '_VOD_ENUM_UNPAID');
    		$item->setVar('sessid', $this->getVar('sessid'));		
    		$item->setVar('cid', $object->getVar('cid'));
    		$item->setVar('vid', $object->getVar('vid'));
    		$item->setVar('pid', $object->getVar('pid'));
    		$item->setVar('tokens', $object->getVar('tokens'));
    		$item->setVar('cost_usd', $currency_handler->getAmountRate($object->getVar('currency'), 'USD', $object->getVar('price')));
    		$item->setVar('cost_aud', $currency_handler->getAmountRate($object->getVar('currency'), 'AUD', $object->getVar('price')));
    		$item->setVar('cost', $currency_handler->getAmountRate($object->getVar('currency'), $GLOBALS['vodModuleConfig']['currency'], $object->getVar('price')));
    		$item->setVar('currency', $GLOBALS['vodModuleConfig']['currency']);
    		$item->setVar('added', time());
    		
    		$this->setVar('videos', $this->getVar('videos')+1);
    		$this->setVar('tokens', $this->getVar('tokens')+$item->getVar('tokens'));
    		$this->setVar('total_usd', $this->getVar('total_usd')+$item->getVar('cost_usd'));
    		$this->setVar('total_aud', $this->getVar('total_aud')+$item->getVar('cost_aud'));
    		$this->setVar('total', $this->getVar('total')+$currency_handler->getAmountRate($item->getVar('currency'), $this->getVar('currency'), $item->getVar('cost')));
    		
    		$session_handler = xoops_getmodulehandler('sessions', 'vod');
    		if ($session_handler->insert($this, true)) {
    			return $cart_handler->insert($item, true);
    		} else 
    			return false;
    	}
    	return false;	
    }
    
	function removeVideo($object) {
    	$cart_handler = xoops_getmodulehandler('cart', 'vod');
    	$currency_handler = xoops_getmodulehandler('currency', 'vod');
    	$criteria = new CriteriaCompo(new Criteria('`mode`', '_VOD_ENUM_UNPAID'));
    	$criteria->add(new Criteria('sessid', $this->getVar('sessid')));
    	$criteria->add(new Criteria('vid', $object->getVar('vid')));
    	if ($cart_handler->getCount($criteria)>0) {
    		$items = $cart_handler->getObjects($criteria, false);
    		if (is_object($items[0])) {
	    		$this->setVar('videos', $this->getVar('videos')-1);
	    		$this->setVar('tokens', $this->getVar('tokens')-$items[0]->getVar('tokens'));
	    		$this->setVar('total_usd', $this->getVar('total_usd')-$items[0]->getVar('cost_usd'));
	    		$this->setVar('total_aud', $this->getVar('total_aud')-$items[0]->getVar('cost_aud'));
	    		$this->setVar('total', $this->getVar('total')-$currency_handler->getAmountRate($items[0]->getVar('currency'), $this->getVar('currency'), $items[0]->getVar('cost')));
	    		$session_handler = xoops_getmodulehandler('sessions', 'vod');
	    		if ($session_handler->insert($this, true)) {
	    			return $cart_handler->delete($items[0], true);
	    		} else 
	    			return false;
	    	} else 
    			return false;
    	}
    	return false;	
    }
    
	function getForm($title=true, $as_array = false) {
		return vod_sessions_get_form($this, false);
	}
	
	function toArray($xpayment=false) {
		$ret = parent::toArray();
		
		xoops_loadLanguage('enum', 'vod');
		foreach($ret as $field => $value) {
			if (defined($value))
				$ret[$field] = constant($value);
		}
		$form = $this->getForm(false, true);
		if (is_array($form)&&count($form)>0)
			foreach($form as $key => $element) {
				$ret['form'][$key] = $form[$key]->render();	
			}
		foreach(array('invoiced', 'paid', 'canceled', 'expires', 'created', 'updated') as $key) {
			if ($this->getVar($key)>0) {
				$ret['form'][$key] = date(_DATESTRING, $this->getVar($key)); 
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			}
		}
		if ($xpayment==false) {
			$currency_handler = xoops_getmodulehandler('currency', 'vod');
			$ret['total_usd'] = $currency_handler->toFormat($ret['total_usd'], 'USD');
			$ret['total_aud'] = $currency_handler->toFormat($ret['total_aud'], 'AUD');
			$ret['total'] = $currency_handler->toFormat($ret['total'], $ret['currency']);
			$ret['tax_usd'] = $currency_handler->toFormat($ret['total_usd']*($GLOBALS['vodModuleConfig']['tax']/100), 'USD');
			$ret['tax_aud'] = $currency_handler->toFormat($ret['total_aud']*($GLOBALS['vodModuleConfig']['tax']/100), 'AUD');
			$ret['tax'] = $currency_handler->toFormat($ret['total']*($GLOBALS['vodModuleConfig']['tax']/100), $ret['currency']);
			$ret['grand'] = $currency_handler->toFormat($this->getVar('total')+($this->getVar('total')*($GLOBALS['vodModuleConfig']['tax']/100)), $ret['currency']);
		} else {
			$ret['key'] = md5($this->getVar('sessid').$this->getVar('salt').$GLOBALS['vodModuleConfig']['salt']);
		}
		return $ret;
	}
    
	function getCartArray($xpayment=false) {
		$cart_handler = xoops_getmodulehandler('cart', 'vod');
		$criteria = new Criteria('sessid', $this->getVar('sessid'));
		$ret = array();
		foreach($cart_handler->getObjects($criteria, true) as $id => $item) {
			$ret[$id] = $item->toArray($xpayment);	
		}
		return $ret;
	}
}

class VodSessionsHandler extends XoopsPersistableObjectHandler
{
	
	function filterFields() {
		return array('sessid', 'mode', 'session_id', 'iid', 'uid', 'name', 'email', 'ip', 'videos', 'total_usd', 'total_aud', 'total', 'currency', 'invoiced', 'paid', 'canceled', 'created', 'updated');
	}

    function VodSessionsHandler($db) {
	    parent::__construct($db, 'vod_cart_sessions', 'VodSessions', 'sessid', 'mode');
    }   
    
    function getByKey($key) {
    	$sql = "SELECT * FROM `" . $this->getTable() . "` WHERE MD5(CONCAT(`sessid`, `salt`, '" . $GLOBALS['vodModuleConfig']['salt'] . "')) = '" . $key . "'";
    	$result = $GLOBALS['xoopsDB']->query($sql);
    	if ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
    		$ret = new VodSessions();
    		$ret->assignVars($row);
    		return $ret;
    	}
    	return false;
    }
    
    function getTable() {
    	return $this->table;
    }
    
    function createCart() {
    	$session = $this->create(true);
    	if (is_object($GLOBALS['xoopsUser'])) {
    		$session->setVar('uid', $GLOBALS['xoopsUser']->getVar('uid'));
    		$session->setVar('name', $GLOBALS['xoopsUser']->getVar('name'));
    		$session->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
    		$session->setVar('pass', $GLOBALS['xoopsUser']->getVar('pass'));
    	}
    	$session->setVar('expires', time()+$GLOBALS['vodModuleConfig']['cart_expires']);
    	$session->setVar('salt', vod_getToken());
    	$session->setVar('session_id', session_id());
    	$user = vod_getIPData();
    	$session->setVar('ip', $user['ip']);
    	$session->setVar('netaddy', $user['network-addy']);
    	$session = $this->get($this->insert($session, true));
    	$_SESSION['vod']['cart'] = $session->toArray(true);
    	setcookie('cart', serialize($_SESSION['vod']['cart']), 3600*24*30);
    	return $session->toArray(true); 
    }
    
    function intialiseCart() {
    	if (isset($_SESSION['vod']['cart']['sessid'])&&$_SESSION['vod']['cart']['mode']=='_VOD_ENUM_UNINVOICED') {
    		$session = $this->get($_SESSION['vod']['cart']['sessid']);
    		if ($session->getVar('mode')!='_VOD_ENUM_UNINVOICED') {
    			unset($session);
    			unset($_SESSION['vod']['cart']);
    		}
    	} elseif (isset($_COOKIE['cart'])&&strlen($_COOKIE['cart'])>0&&is_array($cookie = unserialize($_COOKIE['cart']))&&$cookie['mode']=='_VOD_ENUM_UNINVOICED') {
    		$session = $this->get($cookie['sessid']);
    		if ($session->getVar('mode')!='_VOD_ENUM_UNINVOICED') {
    			unset($session);
    			unset($_COOKIE['cart']);
    		}
    	} 
    	if (is_object($GLOBALS['xoopsUser'])&&!isset($session)) {
    		$criteria = new CriteriaCompo(new Criteria('uid', $GLOBALS['xoopsUser']->getVar('uid')));
    		$criteria->add(new Criteria('`mode`', '_VOD_ENUM_UNINVOICED'));
    		if ($this->getCount($criteria)>0) {
    			$sessions = $this->getObjects($criteria, false);
    			if (isset($sessions[0]))
    				$session = $sessions[0];
    		}
    	}
    	$user = vod_getIPData();
    	if (!isset($session)) {
    		$criteria = new CriteriaCompo(new Criteria('`ip`', $user['ip']));
    		$criteria->add(new Criteria('netaddy', $user['network-addy']));
    		$criteria->add(new Criteria('`mode`', '_VOD_ENUM_UNINVOICED'));
    		if ($this->getCount($criteria)>0) {
    			$sessions = $this->getObjects($criteria, false);
    			if (isset($sessions[0]))
    				$session = $sessions[0];
    		}
    		
    	}
    	if (!isset($session)) {
    		unset($session);
    		unset($_SESSION['vod']['cart']);
    		unset($_COOKIE['cart']);
    		return false;
    	}
    	
    	if ($session->getVar('mode')=='_VOD_ENUM_INVOICED') {
    		redirect_header($session->getVar('url'), 10, _MN_VOD_MSG_YOUHAVETO_PAY_INVOICE_BEFORE_CONTINUING);
    		exit;
    	} elseif ($session->getVar('mode')=='_VOD_ENUM_UNINVOICED') {
    		if (is_object($GLOBALS['xoopsUser'])) {
	    		$session->setVar('uid', $GLOBALS['xoopsUser']->getVar('uid'));
	    		$session->setVar('name', $GLOBALS['xoopsUser']->getVar('name'));
	    		$session->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
	    		$session->setVar('pass', $GLOBALS['xoopsUser']->getVar('pass'));
	    	}
	    	$session->setVar('expires', time()+$GLOBALS['vodModuleConfig']['cart_expires']);
	    	$session->setVar('session_id', session_id());
	    	$session->setVar($user['ip'], $user['ip']);
	    	$session->setVar($user['network-addy'], $user['network-addy']);
	    	$session = $this->get($this->insert($session, true));
	    	$_SESSION['vod']['cart'] = $session->toArray(true);
    		setcookie('cart', serialize($_SESSION['vod']['cart']), 3600*24*30);
    		return $session; 
	    	
    	}
    	if (is_object($session)) {
	    	$_SESSION['vod']['cart'] = $session->toArray(true);
    		setcookie('cart', serialize($_SESSION['vod']['cart']), 3600*24*30);
    		return $session; 
    	}
    	return false;
    }
    
 	function insert($object, $force=true) {
    	if ($object->isNew()) {
    		$object->setVar('created', time());
    	} else {
    		$object->setVar('updated', time());
    	}
    	if ($object->vars['mode']['changed']==true) {
    		$sql = array();
    		switch ($object->getVar('mode')) {
    			case '_VOD_ENUM_UNINVOICED':
    				$object->setVar('invoiced', 0);
    				$object->setVar('paid', 0);
    				$object->setVar('canceled', 0);
    				$object->setVar('paidkey', '');
    				$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_cart').'` SET `mode` = "_VOD_ENUM_UNPAID", `paid` = 0, `notified` = 0, `expires` = 0, `claimed` = 0 WHERE `sessid` = "'.$object->getVar('sessid').'"';
    				break;
    			case '_VOD_ENUM_INVOICED':
    				$object->setVar('invoiced', time());
    				$object->setVar('paidkey', '');
    				$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_cart').'` SET `mode` = "_VOD_ENUM_UNPAID", `paid` = 0, `notified` = 0, `expires` = 0, `claimed` = 0 WHERE `sessid` = "'.$object->getVar('sessid').'"';
    				break;
    			case '_VOD_ENUM_PAID':
    				$object->setVar('paid', time());
    				$object->setVar('paidkey', vod_getToken());
    				$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_cart').'` SET `mode` = "_VOD_ENUM_PAID", `paid` = UNIX_TIMESTAMP() WHERE `sessid` = "'.$object->getVar('sessid').'"';
    				$cart_handler = xoops_getmodulehandler('cart', 'vod');
    				$criteria = new Criteria('sessid', $object->getVar('sessid'));
    				$cids = array();
    				$vids = array();
    				foreach($cart_handler->getObjects($criteria, true) as $crtid => $cart) {
    					$cids[$cart->getVar('cid')]['USD'] = $cids[$cart->getVar('cid')]['USD'] + $cart->getVar('cost_usd');
    					$cids[$cart->getVar('cid')]['AUD'] = $cids[$cart->getVar('cid')]['AUD'] + $cart->getVar('cost_aud');
    					$cids[$cart->getVar('cid')]['NUM']++;
    					$vids[$cart->getVar('vid')]++;
    					$num++;
    				}
    				foreach($vids as $vid => $number) {
    					$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_videos').'` SET `purchases` = `purchases` + "' . ($number) .'", `purchased` = UNIX_TIMESTAMP() WHERE `vid` = "'.$vid.'"';
    				}
    				foreach($cids as $cid => $number) {
    					$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_category').'` SET `earning_usd` = `earning_usd` + "' . ($number['USD']) .'", `earning_aud` = `earning_aud` + "' . ($number['AUD']) .'", `discounts_usd` = `discounts_usd` + "' . (($object->getVar('discount_usd')/$num)*$number['NUM']) .'", `discounts_aud` = `discounts_aud` + "' . (($object->getVar('discount_aud')/$num)*$number['NUM']) .'", `purchased` = UNIX_TIMESTAMP() WHERE `cid` = "'.$cid.'"';
    				}
    				setcookie('cart', '');
    				break;
    			case '_VOD_ENUM_CANCELED':
    				$object->setVar('canceled', time());
    				$object->setVar('paidkey', '');
    				$sql[] = 'UPDATE `'.$GLOBALS['xoopsDB']->prefix('vod_cart').'` SET `mode` = "_VOD_ENUM_CANCELED", `expires` = UNIX_TIMESTAMP(), `claimed` = UNIX_TIMESTAMP() WHERE `sessid` = "'.$object->getVar('sessid').'"';
    				break;		
    		}
    		if (is_array($sql)>0&&count($sql)>0) {
    			foreach($sql as $question) {
    				$GLOBALS['xoopsDB']->queryF($question);
    			}
    		}
    	}
    	$id = parent::insert($object, $force);
    	// Creates Log
    	$log_handler = xoops_getmodulehandler('log', 'vod');
    	$log_handler->createLog(__FILE__, __LINE__, $object, ($object->getVar('updated')==0?_VOD_LOG_REMARK_RECORDCREATED:_VOD_LOG_REMARK_RECORDUPDATED), $id, 0);
    	return $id;   	
     }
    
    function delete($object, $force) {
    	$log_handler = xoops_getmodulehandler('log', 'vod');
    	$log_handler->createLog(__FILE__, __LINE__, $object, _VOD_LOG_REMARK_DELETED, $object->getVar($this->keyName), 0);
    	return parent::delete($object, $force);
    }
    
    function getFilterCriteria($filter) {
    	$parts = explode('|', $filter);
    	$criteria = new CriteriaCompo();
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (!empty($var[1])&&!is_numeric($var[0])) {
    			$object = $this->create();
    			if (		$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTBOX || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTAREA) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%'.$var[1].'%', (isset($var[2])?$var[2]:'LIKE')));
    			} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_INT || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_DECIMAL || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_FLOAT ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));			
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ENUM ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));    				
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ARRAY ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%"'.$var[1].'";%', (isset($var[2])?$var[2]:'LIKE')));    				
				}
    		} elseif (!empty($var[1])&&is_numeric($var[0])) {
    			$criteria->add(new Criteria($var[0], $var[1]));
    		}
    	}
    	return $criteria;
    }
        
    function getFilterForm($filter, $field, $sort='created', $op = 'dashboard', $fct='list') {
    	$ele = vod_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }   
}
?>