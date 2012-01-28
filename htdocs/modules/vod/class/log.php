<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once(dirname(dirname(__FILE__)).'/include/functions.php');
xoops_loadLanguage('log', 'vod');

/**
 * Class for Spiders
 * @author Simon Roberts (simon@xoops.org)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class VodLog extends XoopsObject
{
	var $_exclude = array();
	var $_comments = array();
	
    function VodLog($fid = null)
    {
        $this->initVar('log_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('class', XOBJ_DTYPE_ENUM, 'unknown', false, false, false, array('cart','sessions','currency','category','mimetypes','video','external','unknown'));
		$this->initVar('file', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('line', XOBJ_DTYPE_INT, null, false);
		$this->initVar('id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('status', XOBJ_DTYPE_ENUM, null, false, false, false, array('_VOD_ENUM_UNINVOICED','_VOD_ENUM_INVOICED','_VOD_ENUM_PAID','_VOD_ENUM_UNPAID','_VOD_ENUM_CANCELED'));
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);				
		$this->initVar('comment', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('created', XOBJ_DTYPE_DECIMAL, null, false);
	}

	function assignVar($key, $value) {
		if ($key=='comment') {
			$this->_comments = vod_obj2array(json_decode($value));
		}
		return parent::assignVar($key, $value);
	}
	
	function runPluginInsert() {
		xoops_loadLanguage('enum', 'vod');
		$cause = 'PluginInsert';
		$class = explode('.',basename(__FILE__));
		unset($class[sizeof($class)-1]);
		$class = implode('.',$class);
		include_once ($GLOBALS['xoops']->path('modules/vod/plugins/'.basename(__FILE__)));
		$func = constant(str_replace('_ENUM', '_ENUM_PLUGINS', $this->getVar('status'))).ucfirst($class).$cause;
		if (function_exists($func)) {
			return $func($this, $class);
		}		
		return $this;
	}

	function runPluginGet() {
		xoops_loadLanguage('enum', 'vod');
		$cause = 'PluginGet';
		$class = explode('.',basename(__FILE__));
		unset($class[sizeof($class)-1]);
		$class = implode('.',$class);
		include_once ($GLOBALS['xoops']->path('modules/vod/plugins/'.basename(__FILE__)));
		$func = constant(str_replace('_ENUM', '_ENUM_PLUGINS', $this->getVar('status'))).ucfirst($class).$cause;
		if (function_exists($func)) {
			return $func($this, $class);
		}		
		return $this;
	}
	
	function toArray() {
		xoops_loadLanguage('enum', 'vod');
		$ret = parent::toArray();
		foreach ($ret as $key => $value) {
			if (defined($value))
				$ret[$key] = constant($value);
		}
		foreach(array('created') as $key) {
			if ($this->getVar($key)>0) {
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			} 
		}
		$ret['comment'] = '<pre style="max-height:85px;">'.print_r($this->_comments, true).'</pre>';
		return $ret;
	}
}


/**
* XOOPS Spider handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@xoops.org>
* @package kernel
*/
class VodLogHandler extends XoopsPersistableObjectHandler
{
	
	function filterFields() {
		return array(	'log_id', 'class', 'id', 'uid', 'status', 'comment',
						'created', 'name');
	}
	
    function __construct(&$db) 
    {
    	xoops_loadLanguage('log', 'vod');
		if (!isset($GLOBALS['vodModuleConfig'])) {
			$module_handler = xoops_gethandler('module');
			$config_handler = xoops_gethandler('config');
			$GLOBALS['vodModule'] = $module_handler->getByDirname('vod');
			$GLOBALS['vodModuleConfig'] = $config_handler->getConfigList($GLOBALS['vodModule']->getVar('mid'));
		}     	
        parent::__construct($db, "vod_log", 'VodLog', "log_id", "name");
    }

    function createLog($_file, $_line, $_object, $_comment = '', $_id = 0, $_uid = 0) 
    {
       	if (is_string($_comment)) {
    		$_comment = array('remark'=>$_comment);
    	} elseif (empty($_comment)) {
    		$_comment = array();
    	}
    	
    	$path = str_replace('\\', '/',str_replace('\\\\', '/', dirname($_file)));
    	$file = basename($_file);
    	if (strpos($path, 'modules/vod/class')) {
			$class = explode('.',$file);
			unset($class[sizeof($class)-1]);
			$class = implode('.',$class);
			if (is_object($_object)&&$_id==0) {
				$handler = xoops_getmodulehandler($class, 'vod');
				$_id = $_object->getVar($handler->keyName);
			} 
	   	} elseif (strpos($path, 'modules/vod')) {
	   		$class = 'file';
	   	} else {
		   	$class = 'external';
	   	}
	   	
	   	if ($_uid==0&&is_object($GLOBALS['xoopsUser'])) {
	   		$_uid = $GLOBALS['xoopsUser']->getVar('uid');
	   	}
	   	
		$status = null;
		$mode = null;
		$type = null;
		$name = null;
	   	if (is_object($_object)&&isset($_object->vars)) {
	   		$name = @$_object->vars['name']['value'];
	   		if ($_comment['remark']==_VOD_LOG_REMARK_DELETED) {
	   			foreach($_object->vars as $field => $data) {
		   			$_comment['field'][$field]['deleted']=_YES;
					$_comment['field'][$field]['value']=$data['value'];	   				
		   		}
	   		} else { 
		   		foreach($_object->vars as $field => $data) {
		   			if ($data['changed']==true&&!$_object->isNew()) {
						$_comment['field'][$field]['changed']=_YES;
						$_comment['field'][$field]['value']=$data['value'];
		   			} elseif ($_object->isNew()) {
						$_comment['field'][$field]['changed']=_YES;
						$_comment['field'][$field]['value']=$data['value'];
		   			} else {
		   				$_comment['field'][$field]['changed']=_NO;
		   				$_comment['field'][$field]['value']=$data['value'];
		   			}
		   		}
	   		}
	   		if (isset($_object->vars['status'])) {
	   			$status = $_object->vars['status']['value'];
	   		}
	   		if (isset($_object->vars['mode'])) {
	   			$status = $_object->vars['mode']['value'];
	   		}
	   		if (isset($_object->vars['type'])) {
	   			$status = $_object->vars['type']['value'];
	   		}
	   	}
		
	   	$log = new VodLog();
	   	$log->setNew();
	   	$log->setDirty();
	   	$log->setVar('class', $class);
	   	$log->setVar('path', str_replace(XOOPS_ROOT_PATH, '', $path));
	   	$log->setVar('file', $file);
	   	$log->setVar('line', $_line);
	   	$log->setVar('id', $_id);
	   	$log->setVar('uid', $_uid);
	   	$log->setVar('status', $status);
	   	$log->setVar('name', $name);
	   	$log->setVar('created', microtime(true));
	   	$log->setVar('comment', json_encode($_comment));
	   	
	    return $this->insert($log, true, true);	
    }
    
	function runPluginGetArray($object) {
		xoops_loadLanguage('enum', 'vod');
		$cause = 'PluginGetArray';
		$class = explode('.',basename(__FILE__));
		unset($class[sizeof($class)-1]);
		$class = implode('.',$class);
		include_once ($GLOBALS['xoops']->path('modules/vod/plugins/'.basename(__FILE__)));
		$func = constant(str_replace('_ENUM', '_ENUM_PLUGINS', $object['type'])).constant(str_replace('_ENUM', '_ENUM_PLUGINS', $object['mode'])).ucfirst($class).$cause;
		if (function_exists($func)) {
			return $func($object, $class);
		}		
		return $object;
	}
    
    function getTable() {
    	return $this->table;
    }
        
	function insert($object, $force=true, $run_plugin=false) {
		// Deletes Old Records
		$criteria = new Criteria('`created`', microtime(true)-$GLOBALS['vodModuleConfig']['log_cache'], '<=');
	    @parent::deleteAll($criteria, true, false);
		if ($object->isNew()) {
    		$object->setVar('created', time());
    	}
    	if (isset($object->vars['class']))
	    	if ($object->vars['class']['changed']==true) {
	    		$run_plugin = true;
	    	}
    	if (isset($object->vars['mode']))
	    	if ($object->vars['mode']['changed']==true) {
	    		$run_plugin = true;
	    	}
	    if (isset($object->vars['type']))
	    	if ($object->vars['type']['changed']==true) {
	    		$run_plugin = true;
	    	}
	    if (isset($object->vars['status']))
	    	if ($object->vars['status']['changed']==true) {
	    		$run_plugin = true;
	    	}	    	
    	if ($run_plugin==true) {
    		return parent::insert($object->runPluginInsert(), $force);
    	} else {
    		return parent::insert($object, $force);
    	}
    }
    
	function get($id, $run_plugin=true) {
    	$object = parent::get($id);
    	if (is_object($object)&&$run_plugin==true) {
    		return $object->runPluginGet();
    	} else {
    		return $object;
    	}
    }
    
	function getObjects($criteria, $id_as_key=false, $as_object= true, $run_plugin=true) {
    	$objects = parent::getObjects($criteria, $id_as_key, $as_object);
    	if (count($objects)>0) {
    		foreach($objects as $key => $object) {
		    	if (is_object($object)&&$run_plugin==true&&$as_object=true) {
		    		if (!$objects[$key] = $object->runPluginGet())
		    			unset($objects[$key]);
		    	} elseif (is_array($object)&&$run_plugin==true&&$as_object=false) {
		    		if (!$objects[$key] = $this->runPluginGetArray($object))
		    			unset($objects[$key]);
		    	}
	    	}
		    return $objects;
    	}
    	return false;
    }
    
	function getSum($field, $criteria = null) {
    	$sql = 'SELECT SUM(`'.$field.'`) FROM `' . $this->table . '` ';
       	$limit = null;
    	$start = null;
    	if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
    		$sql .= $criteria->renderWhere();
    	     if ($groupby = $criteria->groupby) {
                $sql .= " GROUP BY $groupby";
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
    	} 
    	$result = $GLOBALS['xoopsDB']->query($sql, $limit, $start);
    	list($sum) = $GLOBALS['xoopsDB']->fetchRow($result);
    	return $sum;
    }
    
    function getAverage($field, $criteria = null) {
    	$sql = 'SELECT AVG(`'.$field.'`) FROM `' . $this->table . '` ';
       	$limit = null;
    	$start = null;
    	if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
    		$sql .= $criteria->renderWhere();
    	     if ($groupby = $criteria->groupby) {
                $sql .= " GROUP BY $groupby";
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
    	} 
    	$result = $GLOBALS['xoopsDB']->query($sql, $limit, $start);
    	list($average) = $GLOBALS['xoopsDB']->fetchRow($result);
    	return $average;
    }

    function getMaximum($field, $criteria = null) {
    	$sql = 'SELECT MAX(`'.$field.'`) FROM `' . $this->table . '` ';
       	$limit = null;
    	$start = null;
    	if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
    		$sql .= $criteria->renderWhere();
    	     if ($groupby = $criteria->groupby) {
                $sql .= " GROUP BY $groupby";
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
    	} 
    	$result = $GLOBALS['xoopsDB']->query($sql, $limit, $start);
    	list($maximum) = $GLOBALS['xoopsDB']->fetchRow($result);
    	return $maximum;
    }
    
    function getMinimum($field, $criteria = null) {
    	$sql = 'SELECT MIN(`'.$field.'`) FROM `' . $this->table . '` ';
       	$limit = null;
    	$start = null;
    	if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
    		$sql .= $criteria->renderWhere();
    	     if ($groupby = $criteria->groupby) {
                $sql .= " GROUP BY $groupby";
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
    	} 
    	$result = $GLOBALS['xoopsDB']->query($sql, $limit, $start);
    	list($minimum) = $GLOBALS['xoopsDB']->fetchRow($result);
    	return $minimum;
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
        
    function getFilterForm($filter, $field, $sort='created', $op = 'log', $fct='list') {
    	$ele = vod_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }
}
?>