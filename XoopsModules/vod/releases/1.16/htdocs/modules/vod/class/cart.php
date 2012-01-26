<?php

if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}

include_once $GLOBALS['xoops']->path('modules/vod/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/formobjects.vod.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/forms.vod.php');

class VodCart extends XoopsObject 
{
	var $_objects = array();
		
    function __construct()
    {
        $this->initVar('crtid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('mode', XOBJ_DTYPE_ENUM, '_ENUM_VOD_UNPAID', false, false, false, array('_ENUM_VOD_UNPAID','_ENUM_VOD_PAID','_ENUM_VOD_CANCELED'));
        $this->initVar('sessid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('vid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tokens', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cost_usd', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('cost_aud', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('cost', XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('currency', XOBJ_DTYPE_INT, null, false, 3);
        $this->initVar('added', XOBJ_DTYPE_INT, null, false);
        $this->initVar('paid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('notified', XOBJ_DTYPE_INT, null, false);
        $this->initVar('claimed', XOBJ_DTYPE_INT, null, false);
        $this->initVar('closed', XOBJ_DTYPE_INT, null, false);
        $this->initVar('expires', XOBJ_DTYPE_INT, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, null, false);
        
    }
    
	function toArray($xpayment=false) {
		$ret = parent::toArray();
		$form = $this->getForm(false, true);
		foreach($form as $key => $element) {
			$ret['form'][$key] = $form[$key]->render();	
		}
		foreach(array('added', 'paid', 'notified', 'claimed', 'expires', 'created', 'updated') as $key) {
			if ($this->getVar($key)>0) {
				$ret['form'][$key] = date(_DATESTRING, $this->getVar($key)); 
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			}
		}
    	$videos_handler = xoops_getmodulehandler('videos', 'vod');
    	$category_handler = xoops_getmodulehandler('category', 'vod');
    	$video = $videos_handler->get($this->getVar('vid'));
    	$category = $category_handler->get($this->getVar('cid'));
    	if (is_object($category)) {
    		$ret['prefix'] = $category->getVar('prefix');
    		$ret['category'] = $category->getVar('name');
    	}
		if (is_object($video)) {
    		$ret['catno'] = $video->getVar('catno');
    		$ret['item'] = $video->getVar('name');
    	}
    	if ($xpayment==false) {
			$currency_handler = xoops_getmodulehandler('currency', 'vod');
			$ret['cost_usd'] = $currency_handler->toFormat($ret['cost_usd'], 'USD');
			$ret['cost_aud'] = $currency_handler->toFormat($ret['cost_aud'], 'AUD');
			$ret['cost'] = $currency_handler->toFormat($ret['cost'], $ret['currency']);
			$ret['tax_usd'] = $currency_handler->toFormat($ret['cost_usd']*($GLOBALS['vodModuleConfig']['tax']/100), 'USD');
			$ret['tax_aud'] = $currency_handler->toFormat($ret['cost_aud']*($GLOBALS['vodModuleConfig']['tax']/100), 'AUD');
			$ret['tax'] = $currency_handler->toFormat($ret['cost']*($GLOBALS['vodModuleConfig']['tax']/100), $ret['currency']);
			$ret['quantity'] = 1;
			$ret['grand'] = $currency_handler->toFormat(($this->getVar('cost')+($this->getVar('cost')*($GLOBALS['vodModuleConfig']['tax']/100)))*$ret['quantity'], $ret['currency']);
		} else {
			$ret['tax_usd'] = $GLOBALS['vodModuleConfig']['tax'];
			$ret['tax_aud'] = $GLOBALS['vodModuleConfig']['tax'];
			$ret['tax'] = $GLOBALS['vodModuleConfig']['tax'];
			$ret['quantity'] = 1;
			$ret['grand'] = ($this->getVar('cost')+($this->getVar('cost')*($GLOBALS['vodModuleConfig']['tax']/100)))*$ret['quantity'];
		}
    	
       	return $ret;
    }
    
	function getForm($title=true, $as_array = false) {
		return vod_cart_get_form($this, false);
	}
    
}

class VodCartHandler extends XoopsPersistableObjectHandler
{

    function VodCartHandler($db, $type) {
	    parent::__construct($db, 'vod_cart', 'VodCart', 'crtid', 'mode');
    }   
       
    function getTable() {
    	return $this->table;
    }
    
 	function insert($object, $force=true) {
    	if ($object->isNew()) {
    		$object->setVar('created', time());
    	} else {
    		$object->setVar('updated', time());
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