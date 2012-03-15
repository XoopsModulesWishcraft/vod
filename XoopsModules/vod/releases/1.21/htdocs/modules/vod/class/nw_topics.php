<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Spiders
 * @author Simon Roberts (simon@xoops.org)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class VodNw_topics extends XoopsObject
{
	var $_exclude = array();
	
    function VodNw_topics($fid = null)
    {
        $this->initVar('topic_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('topic_pid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('topic_imgurl', XOBJ_DTYPE_TXTBOX, null, false, 20);
		$this->initVar('topic_title', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('menu', XOBJ_DTYPE_INT, null, false);
		$this->initVar('topic_frontpage', XOBJ_DTYPE_INT, null, false);    
		$this->initVar('topic_rssurl', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('topic_description', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('topic_color', XOBJ_DTYPE_TXTBOX, null, false, 6);
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
class VodNw_topicsHandler extends XoopsPersistableObjectHandler
{
	
    function __construct(&$db) 
    {
        parent::__construct($db, "nw_topics", 'VodNw_topics', "topic_id", "topic_title");
    }
	
}
?>