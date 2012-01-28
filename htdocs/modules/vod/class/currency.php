<?php

if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}


include_once $GLOBALS['xoops']->path('modules/vod/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/formobjects.vod.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/forms.vod.php');

class VodCurrency extends XoopsObject 
{
	var $_objects = array();
		
    function __construct()
    {
        $this->initVar('currency_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('alias', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('left', XOBJ_DTYPE_TXTBOX, '$', false, 3);
        $this->initVar('right', XOBJ_DTYPE_TXTBOX, '', false, 3);
        $this->initVar('decimals', XOBJ_DTYPE_INT, 2, false);
        $this->initVar('code', XOBJ_DTYPE_TXTBOX, 'AUD', false, 3);
        $this->initVar('default', XOBJ_DTYPE_INT, false, false);
        $this->initVar('rate', XOBJ_DTYPE_DECIMAL, 1, false);
        $this->initVar('rate_set', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('rate_aud', XOBJ_DTYPE_DECIMAL, 1, false);
        $this->initVar('rate_usd', XOBJ_DTYPE_DECIMAL, 1, false);
        $this->initVar('rate_default', XOBJ_DTYPE_DECIMAL, 1, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
        
    }
    
    function toFormat($amount=0) {
    	return (strlen($this->getVar('left'))>0?$this->getVar('left').'&nbsp;':'').number_format($amount, $this->getVar('decimals')).(strlen($this->getVar('right'))>0?'&nbsp;'.$this->getVar('right'):'');	
    }
    
	function getForm($titles = true, $as_array = false) {
		return vod_currency_get_form($this, $as_array);
	}
	
	function toArray() {
		$ret = parent::toArray();
		$form = $this->getForm(false, true);
		foreach($form as $key => $element) {
			$ret['form'][$key] = $form[$key]->render();	
		}
		foreach(array('created', 'actioned', 'updated', 'fought') as $key) {
			if ($this->getVar($key)>0) {
				$ret['form'][$key] = date(_DATESTRING, $this->getVar($key)); 
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			}
		}
		return $ret;
	}
    
}

class VodCurrencyHandler extends XoopsPersistableObjectHandler
{

	function filterFields() {
		return array('currency_id', 'name', 'alias', 'code', 'left', 'right', 'decimals', 'rate', 'rate_aud', 'rate_usd', 'default', 'created', 'updated');
	}
	
	var $_xml_file = "www.ecb.int/stats/eurofxref/eurofxref-daily.xml";
	
    function VodCurrencyHandler($db) {
	    parent::__construct($db, 'vod_currency', 'VodCurrency', 'currency_id', 'name');
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
    
    function getByCode($code) {
    	static $_currencies = array();
    	if (!isset($_currencies[$code])) {
	    	$criteria = new Criteria('`code`', $code);
	    	$currencies = $this->getObjects($criteria, false);
	    	if (is_object($currencies[0])) {
	    		$_currencies[$code] = $currencies[0];
	    		return $currencies[0]; 
	    	}
    		return false;
    	} elseif (isset($_currencies[$code])) {
    		return $_currencies[$code];
    	} else {
    		return false;
    	}
    }
    
    function toFormat($amount, $code) {
    	static $_currencies = array();
    	if (!isset($_currencies[$code])) {
	    	$criteria = new Criteria('`code`', $code);
	    	$currencies = $this->getObjects($criteria, false);
	    	if (is_object($currencies[0])) {
	    		$_currencies[$code] = $currencies[0];
	    		return $currencies[0]->toFormat($amount); 
	    	}
    		return $amount;
    	} elseif (isset($_currencies[$code])) {
    		return $_currencies[$code]->toFormat($amount);
    	} else {
    		return $amount;
    	}
    }
    
    function getAmountRate($from, $to, $amount) {
    	if ($from==$to)
    		return $amount;
    		
  		$this->pollRates();
  		 
    	$objects = parent::getObjects(new Criteria('`code`', $from), false);
    	if (is_object($objects[0]))
    		$fromobj = $objects[0];
    		
    	$objects = parent::getObjects(new Criteria('`code`', $to), false);
    	if (is_object($objects[0]))
    		$toobj = $objects[0];
    		 
   		return $this->convert($amount, $fromobj->getVar('rate'), $toobj->getVar('rate'));
    }
    
   	private function convert($amount=1,$from_rate=1.00,$to_rate=1.00) {
   		return (($amount/$from_rate)*$to_rate);
   	}
  
  	function pollRates() {
	    $criteria = new CriteriaCompo(new Criteria('rate_set', time()-$GLOBALS['vodModuleConfig']['check_rates'], '<'));
	    $objects = parent::getObjects($criteria, true);
  		if (count($objects)>0) {
	  		$currency_domain = substr($this->_xml_file,0,strpos($this->_xml_file,"/"));
	    	$currency_file = substr($this->_xml_file,strpos($this->_xml_file,"/"));
	    	$fp = @fsockopen($currency_domain, 80, $errno, $errstr, 10);
	    	if($fp) {
	 
	    		$out = "GET ".$currency_file." HTTP/1.1\r\n";
	        	$out .= "Host: ".$currency_domain."\r\n";
	        	$out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20051111 Firefox/1.5\r\n";
	        	$out .= "Connection: Close\r\n\r\n";
	        	fwrite($fp, $out);
	        	while (!feof($fp)) {
	           		$buffer .= fgets($fp, 128);
	        	}
	        	fclose($fp);
	 
	        	$pattern = "{<Cube\s*currency='(\w*)'\s*rate='([\d\.]*)'/>}is";
	        	preg_match_all($pattern,$buffer,$xml_rates);
	        	array_shift($xml_rates);
	 
	         	for($i=0;$i<count($xml_rates[0]);$i++) {
	            	$exchange_rate[$xml_rates[0][$i]] = $xml_rates[1][$i];
	         	}
	         	
	         	$criteria = new CriteriaCompo(new Criteria('`default`', 1, '='));
		    	$default = parent::getObjects($criteria, false);
		    	
		    	foreach($objects as $currency_id => $currency) {
		    		if (!empty($exchange_rate[strtoupper($currency->getVar('code'))])) {
		    			$currency->setVar('rate', $exchange_rate[strtoupper($currency->getVar('code'))]);
		    			if (is_object($default[0]))
		    				$currency->setVar('rate_default', $this->convert(1, $exchange_rate[strtoupper($currency->getVar('code'))], $exchange_rate[strtoupper($default[0]->getVar('code'))]));
		    			$currency->setVar('rate_aud', $this->convert(1, $exchange_rate[strtoupper($currency->getVar('code'))], $exchange_rate['AUD']));
		    			$currency->setVar('rate_usd', $this->convert(1, $exchange_rate[strtoupper($currency->getVar('code'))], $exchange_rate['USD']));
		    			$currency->setVar('rate_set', time());
		    			parent::insert($currency, true);
		    		}
		    	}
		   	}
	    }
  	}
}
?>