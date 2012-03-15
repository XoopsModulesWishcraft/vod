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
class VodNw_stories extends XoopsObject
{
	var $_exclude = array();
	
    function VodNw_stories($fid = null)
    {
        $this->initVar('storyid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('published', XOBJ_DTYPE_INT, null, false);
        $this->initVar('expired', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hostname', XOBJ_DTYPE_TXTBOX, null, false, 20);
        $this->initVar('nohtml', XOBJ_DTYPE_INT, null, false);
        $this->initVar('nosmiley', XOBJ_DTYPE_INT, null, false);
		$this->initVar('hometext', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('bodytext', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('keywords', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('counter', XOBJ_DTYPE_INT, null, false);
		$this->initVar('topicid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('ihome', XOBJ_DTYPE_INT, null, false);
		$this->initVar('notifypub', XOBJ_DTYPE_INT, null, false);
		$this->initVar('story_type', XOBJ_DTYPE_TXTBOX, 'admin', false, 5);
		$this->initVar('topicdisplay', XOBJ_DTYPE_INT, null, false);
		$this->initVar('topicalign', XOBJ_DTYPE_TXTBOX, 'R', false, 1);
		$this->initVar('comments', XOBJ_DTYPE_INT, null, false);
		$this->initVar('rating', XOBJ_DTYPE_DECIMAL, null, false);
		$this->initVar('votes', XOBJ_DTYPE_INT, null, false);    
		$this->initVar('picture', XOBJ_DTYPE_TXTBOX, null, false, 50);
		$this->initVar('tags', XOBJ_DTYPE_TXTBOX, null, false, 255);
		
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
class VodNw_storiesHandler extends XoopsPersistableObjectHandler
{
	
    function __construct(&$db) 
    {
        parent::__construct($db, "nw_stories", 'VodNw_stories', "storyid", "title");
    }
	
}
?>