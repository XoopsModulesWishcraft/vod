<?php

if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}


include_once $GLOBALS['xoops']->path('modules/vod/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/formobjects.vod.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/forms.vod.php');

class VodCategory extends XoopsObject 
{
	var $_objects = array();
		
    function __construct()
    {
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('parent', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('prefix', XOBJ_DTYPE_TXTBOX, null, false, 32);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('summary', XOBJ_DTYPE_TXTBOX, null, false, 500);
        $this->initVar('description', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('avata', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('earning_usd', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('earning_aud', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('discounts_usd', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('discounts_aud', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('purchases', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('views', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('purchased', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }

    function getForm($title=true, $as_array = false) {
		return vod_category_get_form($this, $as_array);
	}
	
	function toArray() {
		$ret = parent::toArray();
		$form = $this->getForm(false, true);
		foreach($form as $key => $element) {
			$ret['form'][$key] = $form[$key]->render();	
		}
		foreach(array('invoiced', 'paid', 'purchased', 'canceled', 'created', 'updated') as $key) {
			if ($this->getVar($key)>0) {
				$ret['form'][$key] = date(_DATESTRING, $this->getVar($key)); 
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			}
		}
		if (strlen($this->getVar('avata'))>0&&file_exists($GLOBALS['xoops']->path($this->getVar('path').$this->getVar('avata')))) {
    		$ret['avata_url'] = $this->getImage('avata');
    		$ret['hasavata'] = true;
		} else 
    		$ret['hasavata'] = false;
    		
		$currency_handler = xoops_getmodulehandler('currency', 'vod');
		foreach(array('earning_usd', 'discounts_usd') as $key) {
			$ret['form'][$key] = $currency_handler->toFormat($this->getVar($key), 'USD'); 
			$ret[$key] = $ret['form'][$key];
		}
		foreach(array('earning_aud', 'discounts_aud') as $key) {
			$ret['form'][$key] = $currency_handler->toFormat($this->getVar($key), 'AUD'); 
			$ret[$key] = $ret['form'][$key];
		}
		
		return $ret;
	}
    
	function getImage($field = 'avata') {
    	return XOOPS_URL.'/'.str_replace(DS, '/', $this->getVar('path')).$this->getVar($field);
    }
	
}

class VodCategoryHandler extends XoopsPersistableObjectHandler
{

	function filterFields() {
		return array('cid', 'parent', 'prefix', 'name', 'earning_usd', 'earning_aud', 'discounts_usd', 'discounts_aud', 'purchases', 'views', 'hits', 'purchased', 'canceled', 'created', 'updated');
	}
	
    function VodCategoryHandler($db) {
	    parent::__construct($db, 'vod_category', 'VodCategory', 'cid', 'name');
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