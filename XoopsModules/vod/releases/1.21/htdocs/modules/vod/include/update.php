<?php


function xoops_module_update_vod(&$module) {
	
	$sql = array();

	$sql[] = "ALTER TABLE `".$GLOBALS['xoopsDB']->prefix('vod_videos')."` ADD COLUMN `storyid` int(10) DEFAULT '0'";
	
	foreach($sql as $id => $question) {
		if ($GLOBALS['xoopsDB']->queryF($question)) {
			xoops_error($question, 'SQL Executed Successfully!!!');
		} else {
			//xoops_error($question, 'Error Number: '.$GLOBALS['xoopsDB']->errno().' - SQL Did Not Executed! ('.$GLOBALS['xoopsDB']->error().'!!!)');
		}
	}
	return true;
	
}

?>