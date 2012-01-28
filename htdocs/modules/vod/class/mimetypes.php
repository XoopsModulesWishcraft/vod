<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once $GLOBALS['xoops']->path('modules/vod/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/formobjects.vod.php');
include_once $GLOBALS['xoops']->path('modules/vod/include/forms.vod.php');
/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class VodMimetypes extends XoopsObject
{
	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
    function VodMimetypes($id = null)
    {
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('support', XOBJ_DTYPE_ENUM, null, false, false, false, array('_MI_VOD_FLASH', '_MI_VOD_HTTP', '_MI_VOD_HTML5', '_MI_VOD_IOS', '_MI_VOD_RTMP', '_MI_VOD_RTSP', '_MI_VOD_SILVERLIGHT', '_MI_VOD_OTHER'));
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, 128);
		$this->initVar('mimetype', XOBJ_DTYPE_TXTBOX, '', false, 128);
		$this->initVar('codecs', XOBJ_DTYPE_TXTBOX, '', false, 500);		
		$this->initVar('default', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('vod');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
    
    }
    
    function toArray() {
    	$ret = parent::toArray();
    	$ele = vod_mimetypes_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	return $ret;
    }   
    
}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class VodMimetypesHandler extends XoopsPersistableObjectHandler
{

	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
	function filterFields()
	{
		return array(	'mid','support','name','mimetype','codecs','default','created','updated') ;
	}
	
	function __construct(&$db) 
    {
    	
    	$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('vod');
		if (is_object($this->_Mod))
			$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
		
		$this->db = $db;
        parent::__construct($db, 'vod_mimetypes', 'VodMimetypes', "mid", "name");
    	
    }

	private function resetDefault() {
		$sql = "UPDATE " . $GLOBALS['xoopsDB']->prefix('vod_mimetypes') . ' SET `default` = 0 WHERE 1 = 1';
		return $GLOBALS['xoopsDB']->queryF($sql);
	}
    
    function insert($obj, $force=true, $run_plugin = false) {
    	if ($obj->isNew()) {
    		$obj->setVar('created', time());
    	} else {
    		$obj->setVar('updated', time());
    	}
    	if ($obj->vars['default']['changed']==true&&$obj->getVar('default')==true) {
    		$this->resetDefault();
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
    			$criteria->add(new Criteria("'".$var[0]."'", $var[1]));
    		}
    	}
    	return $criteria;
    }
        
	function getFilterForm($filter, $field, $sort='created', $op = '', $fct = '') {
    	$ele = vod_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }
    
}

?>