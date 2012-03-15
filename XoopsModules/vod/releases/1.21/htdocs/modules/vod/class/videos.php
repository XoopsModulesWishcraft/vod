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
class VodVideos extends XoopsObject
{
	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
    function VodVideos($id = null)
    {
        $this->initVar('vid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('cid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('storyid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('token', XOBJ_DTYPE_TXTBOX, vod_getToken(), false, 32);
        $this->initVar('catno', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('reference', XOBJ_DTYPE_TXTBOX, 'video_%vid%', false, 128);
		$this->initVar('length', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('producedby', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('staring', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('year', XOBJ_DTYPE_TXTBOX, null, false, 5);
		$this->initVar('summary', XOBJ_DTYPE_TXTBOX, null, false, 500);
		$this->initVar('description', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('raw', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('rtmp_server', XOBJ_DTYPE_TXTBOX, '', false, 500);		
		$this->initVar('rtmp', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('flash', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('ios', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('silverlight', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('rtsp', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('raw_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('rtmp_server_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);		
		$this->initVar('rtmp_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('flash_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('ios_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('silverlight_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('rtsp_preview', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('default', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('stream', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('path', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('poster', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('avata', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('width', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('height', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('speciala_width', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('speciala_height', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('specialb_width', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('specialb_height', XOBJ_DTYPE_TXTBOX, '', false, 64);
		$this->initVar('level', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('autoplay', XOBJ_DTYPE_INT, true, false);
		$this->initVar('speciala_autoplay', XOBJ_DTYPE_INT, true, false);
		$this->initVar('specialb_autoplay', XOBJ_DTYPE_INT, true, false);
		$this->initVar('controls', XOBJ_DTYPE_INT, true, false);
		$this->initVar('speciala_controls', XOBJ_DTYPE_INT, true, false);
		$this->initVar('specialb_controls', XOBJ_DTYPE_INT, true, false);
		$this->initVar('muted', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('play', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('volume', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('mute', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('stop', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('fullscreen', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('scrubber', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('purchases', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('price', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('currency', XOBJ_DTYPE_TXTBOX, $GLOBALS['vodModuleConfig']['currency'], false, 3);
		$this->initVar('tokens', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tags', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('views', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('discounted', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('earned', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('purchased', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('vod');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
    
    }
    
    function getViewingURL($session, $cart) {
    	$url = XOOPS_URL . '/modules/vod/index.php?op=videos&fct=view&vid='.$this->getVar('vid').'&crtid='.$cart->getVar('crtid').'&sessid='.$session->getVar('sessid').'&token='.sha1($session->getVar('paidkey').$this->getVar('token'));
    	return $url;
    }
    
    function getIP() {
    	static $ret = array();
    	if (empty($ret))
    		$ret = vod_getIPData(false);
    	return $ret['ip'];
    }
    
    function getForm() {
    	return vod_videos_get_form($this, false);
    }
    
    function getVar($field, $mode = '') {
    	$fields = array('width', 'height', 'autoplay', 'control');
    	if (in_array($field, $fields)&&$mode!='no') {
	    	switch ($this->getSpecialWithUserAgent("")) {
	    		case "A":
	    			return parent::getVar('speciala_'.$field, $mode);
	    			break;
	    		case "B":
	    			return parent::getVar('specialb_'.$field, $mode);
	    			break;
	    		default:
	    			return parent::getVar($field, $mode);
	    			break;
	    	}
    	}
    	return parent::getVar($field, ($mode!='no' ? $mode : ''));
    }
    
    function toArray($preview=false, $state='list') {
    	$ret = parent::toArray();
    	
    	if ($this->getVar('mid')>0) {
    		$mimetypes_handler = xoops_getmodulehandler('mimetypes', 'vod');
    		$mimetype = $mimetypes_handler->get($this->getVar('mid'));
    		if (is_object($mimetype))
    			$ret['mimetype'] = $mimetype->toArray();
    	}
    	
    	$fields = array('width', 'height', 'autoplay', 'control');
    	switch ($this->getSpecialWithUserAgent($_SERVER["HTTP_USER_AGENT"])) {
    		case "A":
    			foreach($fields as $field) {
    				$ret[$field] = $ret['speciala_'.$field];
    			}
    			break;
    		case "B":
    			foreach($fields as $field) {
    				$ret[$field] = $ret['specialb_'.$field];
    			}
    			break;
    		default:
    			break;
    	}
    	
    	$ele = vod_videos_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	
    	static $_cids  = array();
    	$category_handler = xoops_getmodulehandler('category', 'vod');
    	if (!isset($_cids[$this->getVar('cid')])&&$this->getVar('cid')!=0) {
    		$_cids[$this->getVar('cid')] = $category_handler->get($this->getVar('cid')); 
    	}
    	if ($this->getVar('cid')!=0) {
    		$ret['cid'] = $_cids[$this->getVar('cid')]->getVar('name');
    		$ret['prefix'] = $_cids[$this->getVar('cid')]->getVar('prefix');
    	}
    	
    	static $_currency = array();
    	$currency_handler = xoops_getmodulehandler('currency', 'vod');
    	if (!isset($_currency[$this->getVar('currency')])&&$this->getVar('currency')!='') {
    		$criteria = new Criteria('code', $this->getVar('currency'));
    		$currencies = $currency_handler->getObjects($criteria);
    		if (isset($currencies[0])) 
    			$_currency[$this->getVar('currency')] = $currencies[0]; 
    	}
    	if (isset($_currency[$this->getVar('currency')])&&$this->getVar('currency')!='') {
    		$ret['price'] = $_currency[$this->getVar('currency')]->toFormat($this->getVar('price'));
    	}
    	
    	if ($this->getVar('price')>0||$this->getVar('tokens')>0)
    		$ret['cost'] = true;
    	else 
    		$ret['cost'] = false;
    		
    	$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
    	static $_session = null;
    	if (isset($_SESSION['vod']['cart']['sessid'])) {
    		if (!is_object($_session))
				$_session = $sessions_handler->get($_SESSION['vod']['cart']['sessid']);
			$ret['incart'] = $_session->inCart($this);
    	} else {
	    	$ret['incart'] = false;
		} 
    	
		if (strlen($this->getVar('avata'))>0&&file_exists($GLOBALS['xoops']->path($this->getVar('path').$this->getVar('avata')))) {
    		$ret['avata_url'] = $this->getImage('avata', true);
    		$ret['hasavata'] = true;
    	} else 
    		$ret['hasavata'] = false;

    	$ret['image'] = $this->getImage('poster', true);
    	
    	$mode = $this->getModeWithUserAgent($_SERVER['HTTP_USER_AGENT']);
    	$prev = array('width' => $this->getVar('width'), 'height' => $this->getVar('height'), 'id' => $this->getReference(false, true), 'source' => $this->getSource($mode, $preview));
    	if (strlen($this->getSource($mode, true))>0) {
    		$ret['haspreview'] = true;
	    	$prev['mode'] = $mode;
	    	switch ($state) {
	    		default:
	    		case '':
	    		case 'admin':
	    			$ret['haspreview'] = false;
	    			break;
	    		case 'main':
	    		case 'list':
		    		$prev['width'] = $GLOBALS['vodModuleConfig']['video_width_'.$state];
		    		$prev['height'] = $GLOBALS['vodModuleConfig']['video_height_'.$state];
					$prev['source'] = $this->getSource($mode, true);
					$prev['id'] = $this->getReference(false, true);
					$html = $this->getHTML(false, $prev['width'], $prev['height'], '', '', true, $state);
		    		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
		    			$prev['contents'] = '';
						$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $this->getJS(false, $prev['width'], $prev['height'], true, $state));
		    		} else { 
						$html .= "\n<script type='text/javascript'>\n".$this->getInsecureJS(false, $prev['width'], $prev['height'], true, $state)."\n</script>";
						$prev['contents'] = $html;
		    		}		
	    			break;
	    		case 'block':
	    			$prev['width'] = $GLOBALS['vodModuleConfig']['video_width_'.$state];
		    		$prev['height'] = $GLOBALS['vodModuleConfig']['video_height_'.$state];
					$prev['source'] = $this->getSource($mode, true);
					$prev['id'] = $this->getReference(true, true);
					$html = $this->getHTML(true, $prev['width'], $prev['height'], '', '', true, $state);
		    		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
		    			$prev['contents'] = '';
						$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $this->getJS(true, $prev['width'], $prev['height'], true, $state));
		    		} else { 
						$html .= "\n<script type='text/javascript'>\n".$this->getInsecureJS(true, $prev['width'], $prev['height'], true, $state)."\n</script>";
						$prev['contents'] = $html;
		    		}		
	    			break;
	    	}
    	} else {
    		$ret['haspreview'] = false;
    	}
    	
    	if ($ret['haspreview']==true) {
    		foreach($prev as $key => $value)
    			$ret['preview_data_'.$key] = $value;
    	}
    	
    	if ($GLOBALS['vodModuleConfig']['tags']&&file_exists($GLOBALS['xoops']->path("/modules/tag/include/tagbar.php"))) {
			include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
			$ret['tagbar'] = tagBar($this->getVar('vid'), $this->getVar('cid'));
		}
		
    	return $ret;
    }   
    
    function getHTML($block = false, $width=0, $height=0, $agent = '', $ip = '', $preview = false, $state = 'list') {
    	
    	if (empty($ip))
    		$ip = $this->getIP();
    	if (empty($agent))
    		$agent = $_SERVER['HTTP_USER_AGENT'];
    	
    	$mode = $this->getModeWithUserAgent($agent);
    	include_once ($GLOBALS['xoops']->path('class/template.php'));
		$GLOBALS['vodTpl'] = new XoopsTpl();
    	
    	$videos = array();
    	$videos = parent::toArray();
    	$videos['image'] = $this->getImage('poster', true);
    	$videos['avata'] = $this->getImage('avata', true);
    	$videos['mode'] = $mode;
    	$videos['source'] = $this->getSource($mode, $preview);
    	$videos['id'] = $this->getReference($block, $preview);
    	$videos['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($this->getSpecialWithUserAgent($agent)=='A'?$this->getVar('speciala_width'):($this->getSpecialWithUserAgent($agent)=='B'?$this->getVar('specialb_width'):($this->getVar('width')))));
    	$videos['height'] = (!empty($height)&&!empty($height)&&$height&&$height?$height:($this->getSpecialWithUserAgent($agent)=='A'?$this->getVar('speciala_height'):($this->getSpecialWithUserAgent($agent)=='B'?$this->getVar('specialb_height'):($this->getVar('height')))));
    	
    	$GLOBALS['vodTpl']->assign('videos', $videos);
    	if (isset($videos['mimetype']))
    		$GLOBALS['vodTpl']->assign('mimetype', $videos['mimetype']);
    	$GLOBALS['vodTpl']->assign('xoConfig', $this->_ModConfig);
    	$GLOBALS['vodTpl']->assign('iframe', isset($_REQUEST['iframe']));
    	$GLOBALS['vodTpl']->assign('preview', $preview);
    	$GLOBALS['vodTpl']->assign('state', $state);
    	
    	ob_start();
    	if ($block == false)
    		$GLOBALS['vodTpl']->display('db:vod_json_'.$mode.'_videos.html');
    	else 
    		$GLOBALS['vodTpl']->display('db:vod_json_block_'.$mode.'_videos.html');
    	$data = ob_get_contents();
    	ob_end_clean();
    	
    	return $data;
    }
    
    function getJS($block=false, $width = '', $height = '', $preview = false, $state = 'list') {
    	static $_loadedJS = false;
    	xoops_loadLanguage('modinfo', 'vod');
		$mode = $this->getModeWithUserAgent($_SERVER['HTTP_USER_AGENT']);
		if (is_object($GLOBALS['xoTheme'])&&$_loadedJS==false) {
			if ($this->_ModConfig['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['loaded_jquery']=true;
			}
			if (in_array($mode, $this->_ModConfig['load_vod'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_VOD, array('type'=>'text/javascript'));
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_VOD_VOD_STYLE, $GLOBALS['xoopsConfig']['language']), array('type'=>'text/css'));
			}
			if (in_array($mode, $this->_ModConfig['load_videojs'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_VIDEOJS, array('type'=>'text/javascript'));
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_VOD_VIDEOJS_STYLE, $GLOBALS['xoopsConfig']['language']), array('type'=>'text/css'));
			}	
			$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_CORE, array('type'=>'text/javascript'));
			$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_JSON_FUNCTIONS, array('type'=>'text/javascript'));
			
			$_loadedJS = true;
		}
		if (empty($width))
			$width = ($this->getSpecialWithUserAgent($_SERVER['HTTP_USER_AGENT'])=='A'?$this->getVar('speciala_width'):($this->getSpecialWithUserAgent($_SERVER['HTTP_USER_AGENT'])=='B'?$this->getVar('specialb_width'):($this->getVar('width'))));
		if (empty($height))
			$height = ($this->getSpecialWithUserAgent($_SERVER['HTTP_USER_AGENT'])=='A'?$this->getVar('speciala_height'):($this->getSpecialWithUserAgent($_SERVER['HTTP_USER_AGENT'])=='B'?$this->getVar('specialb_height'):($this->getVar('height'))));
		$uid = 0;
		if (is_object($GLOBALS['xoopsUser']))
			$uid = $GLOBALS['xoopsUser']->getVar('uid');
		
		if ($block==false) {
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey'] = sha1(XOOPS_LICENSE_KEY.$this->_ModConfig['salt'].$this->getIP().date('Ymdhis'));
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']]['vid'] = $this->getVar('vid');
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['block'] = ($block==true?'1':'0');
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['width'] = $width;
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['height'] = $height;
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['preview'] = (isset($_SESSION['vod'][$this->getVar('vid')]['main'])?false:$preview);
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['state'] = $state;
			$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['resolve'] = 'div_'.$this->getReference($block, $preview);
		}
		return 'vod_dojson_videos("'.XOOPS_URL.'","'._MI_VOD_DIRNAME.'","'.($block==false?$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)][$_SESSION['vod'][$this->getIP()]['div_'.$this->getReference($block, $preview)]['passkey']][$this->getVar('vid')]['resolve']:'%resolve%').'", "'.$preview.'");';
    }

    function getInsecureJS($block=false, $width = '', $height = '', $preview=false, $state = 'list') {
    	static $_loadedJS = false;
    	xoops_loadLanguage('modinfo', 'vod');
		$mode = $this->getModeWithUserAgent($_SERVER['HTTP_USER_AGENT']);
		if (is_object($GLOBALS['xoTheme'])&&$_loadedJS==false) {
			if ($this->_ModConfig['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['loaded_jquery']=true;
			}
			if (in_array($mode, $this->_ModConfig['load_vod'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_VOD, array('type'=>'text/javascript'));
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_VOD_VOD_STYLE, $GLOBALS['xoopsConfig']['language']), array('type'=>'text/css'));
			}
			if (in_array($mode, $this->_ModConfig['load_videojs'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_VIDEOJS, array('type'=>'text/javascript'));
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_VOD_VIDEOJS_STYLE, $GLOBALS['xoopsConfig']['language']), array('type'=>'text/css'));
			}	
			$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_JSON_FUNCTIONS, array('type'=>'text/javascript'));
			$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_CORE, array('type'=>'text/javascript'));
			
			$_loadedJS = true;
		}
		$out = array();
		if (in_array($mode, $GLOBALS['vodModuleConfig']['load_vod'])) {
			if ($this->getVar('stream')==true&&$mode=='rtmp') {
				$out[0] = XOOPS_URL."/modules/vod/swf/vod-3.2.7.swf";
				$out[1] = ($this->getVar('autoplay')==true||$preview==false?'true':'false');
				$out[2] = ($this->getVar('muted')==true||$preview==false?0:$this->getVar('level'));
				if ($preview==false||isset($_SESSION['vod'][$this->getVar('vid')]['main'])) {
					$out[3] = $this->getVar('rtmp_server');
					$out[4] = $this->getVar('rtmp');
				} else {
					$out[3] = $this->getVar('rtmp_server_preview');
					$out[4] = $this->getVar('rtmp_preview');
				}
				$out[5] = XOOPS_URL."/modules/vod/swf/vod.controls-3.2.5.swf";
				$out[6] = ($this->getVar('play')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[7] = ($this->getVar('volume')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[8] = ($this->getVar('mute')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[9] = ($this->getVar('time')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[10] = ($this->getVar('stop')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[11] = ($this->getVar('fullscreen')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[12] = ($this->getVar('scrubber')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[13] = XOOPS_URL."/modules/vod/swf/vod.rtmp-3.2.3.swf";
				$action = 'vod_rtmp';
			} else {
				$out[0] = XOOPS_URL."/modules/vod/swf/vod-3.2.7.swf";
				$out[1] = ($this->getVar('autoplay')==true||$preview==false?'true':'false');
				$out[2] = ($this->getVar('muted')==true||$preview==false?0:$this->getVar('level'));
				$out[3] = $this->getSource($mode, $preview);
				$out[5] = XOOPS_URL."/modules/vod/swf/vod.controls-3.2.5.swf";
				$out[6] = ($this->getVar('play')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[7] = ($this->getVar('volume')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[8] = ($this->getVar('mute')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[9] = ($this->getVar('time')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[10] = ($this->getVar('stop')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[11] = ($this->getVar('fullscreen')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$out[12] = ($this->getVar('scrubber')==true&&$this->getVar('controls')==true||$preview==true?'true':'false');
				$action = 'vod_file';
			}
		} elseif (in_array($mode, $GLOBALS['vodModuleConfig']['load_videojs'])) {
			if ($this->getVar('stream')==false) {
				$out[0] = $this->getVar('controls')==true&&$preview==true?'true':'false';
				$out[1] = $this->getVar('level')/100;
				$out[2] = $this->getVar('autoplay')==true&&$preview==false?'true':'false';
				$action = 'videojs_file'; 		
		 	} else {
				$out[0] = $this->getVar('controls')==true&&$preview==true?'true':'false';
				$out[1] = $this->getVar('level')/100;
				$out[2] = $this->getVar('autoplay')==true&&$preview==false?'true':'false';
				$action = 'videojs_stream';
		 	}
		} else {
			if ($this->getVar('stream')==false) {
				$action = 'other_file'; 		
		 	} else {
				$action = 'other_stream';
		 	}
		}
		
		switch ($state) {
			case 'main':
				$out[20] = ($preview==false?$this->getVar('width'):$GLOBALS['vodModuleConfig']['video_width_main']);
				$out[21] = ($preview==false?$this->getVar('height'):$GLOBALS['vodModuleConfig']['video_height_main']);
				break;
			case 'list':
				$out[20] = $GLOBALS['vodModuleConfig']['video_width_list'];
				$out[21] = $GLOBALS['vodModuleConfig']['video_height_list'];
				break;			
			case 'block':
				$out[20] = $width;
				$out[21] = $height;
				break;			
		}
		
		switch ($action) {
			case 'vod_file':
			return "vod('".$this->getReference($block, $preview)."', {src: '".$out[0]."', wmode: 'opaque', width: '".$out[20]."', height: '".$out[21]."'}, {
	                clip: {
	                    url: '".$out[3]."',
	            	    autoPlay: ".$out[1].",
	            	    onBegin: function () {
	            	        this.setVolume(".$out[2].");
	            	    }
	                },
	                plugins: {
	                	controls: {
	                        url: '".$out[5]."', 
	                        play:".$out[6].",
		                	volume:".$out[7].",
		                	mute:".$out[8].",
		                	time:".$out[9].",
		                	stop:".$out[10].",
		                	playlist:false,
		                	fullscreen:".$out[11].",
		                 	scrubber: ".$out[12]."
		                }
	                }
});";
			break;
		case 'videojs_file':
		case 'videojs_stream':
			return "var myVideos = VideoJS.setup('".$this->getReference($block, $preview)."', {
					controlsBelow: true, // Display control bar below video instead of in front of
					controlsHiding: ".$out[0].", // Hide controls when mouse is not over the video
					defaultVolume: ".$out[1].", // Will be overridden by user's last volume if available
					flashVersion: 9, // Required flash version for fallback
					linksHiding: true // Hide download links when video is supported
				});
				if (".$out[2].")
					myVideos.play();";
			break;
		case 'vod_rtmp':
			return "vod('".$this->getReference($block, $preview)."', {src: '".$out[0]."', wmode: 'opaque', width: '".$out[20]."', height: '".$out[21]."'}, {
	                clip: {
	                    url: '".$out[3]."',
	                    provider: 'rtmp',
	            	    autoPlay: ".$out[1].",
	            	    onBegin: function () {
	            	        this.setVolume(".$out[2].");
	            	    }
	                },
	                plugins: {
	                	controls: {
	                        url: '".$out[5]."', 
	                        play:".$out[6].",
		                	volume:".$out[7].",
		                	mute:".$out[8].",
		                	time:".$out[9].",
		                	stop:".$out[10].",
		                	playlist:false,
		                	fullscreen:".$out[11].",
		                 	scrubber: ".$out[12]."
		                },
	                	rtmp: {
	                		url: '".$out[13]."',
	                		netConnectionUrl: '".$out[4]."'
	                	}
	                }
});";
			break;				
		case 'other_file':
			return "";
			break;						
		case 'other_stream':
			return "";
			break;				
			
		}
	}
    
    function getReference($block=false, $preview = false) {
    	if ($preview==true && $block==false)
			return str_replace('%vid%', $this->getVar('vid'), 'preview_'.$this->getVar('reference'));
		elseif ($preview==true && $block==true)
			return str_replace('%vid%', $this->getVar('vid'), 'preview_block_'.$this->getVar('reference'));
		elseif ($preview==false && $block==true)
    		return str_replace('%vid%', $this->getVar('vid'), 'block_'.$this->getVar('reference'));
    	else
    		return str_replace('%vid%', $this->getVar('vid'), $this->getVar('reference'));
    }
    
    function getSource($mode='flash', $preview=false) {
    	if (isset($_SESSION['vod'][$this->getVar('vid')]['main']))
    		$preview = false;
    	if ($this->getVar('stream')==false)
    		return ($preview==true?(strlen($this->getVar('raw_preview'))>0?$this->getVar('raw_preview'):''):$this->getVar('raw'));
    	switch ($mode) {
    		case 'rtmp':
    			return '#';
    			break;
    		case 'rtsp':
    			return ($preview==true?(strlen($this->getVar('rtsp_preview'))>0&&$this->getVar('stream')==true?$this->getVar('rtsp_preview'):$this->getVar('raw_preview')):(strlen($this->getVar('rtsp'))>0&&$this->getVar('stream')==true?$this->getVar('rtsp'):$this->getVar('raw')));
    			break;
    		case 'flash':
    			return ($preview==true?(strlen($this->getVar('flash_preview'))>0&&$this->getVar('stream')==true?$this->getVar('flash_preview'):$this->getVar('raw_preview')):(strlen($this->getVar('flash'))>0&&$this->getVar('stream')==true?$this->getVar('flash'):$this->getVar('raw')));
    			break;
    		case 'silverlight':
    			return ($preview==true?(strlen($this->getVar('silverlight_preview'))>0&&$this->getVar('stream')==true?$this->getVar('silverlight_preview'):$this->getVar('silverlight_preview')):(strlen($this->getVar('silverlight'))>0&&$this->getVar('stream')==true?$this->getVar('silverlight'):$this->getVar('raw')));
    			break;
    		case 'ios':
    			return ($preview==true?(strlen($this->getVar('ios_preview'))>0&&$this->getVar('stream')==true?$this->getVar('ios_preview'):$this->getVar('raw_preview')):(strlen($this->getVar('ios'))>0&&$this->getVar('stream')==true?$this->getVar('ios'):$this->getVar('raw')));
    			break;
			case 'http':
    			return ($preview==true?(strlen($this->getVar('http_preview'))>0&&$this->getVar('stream')==true?$this->getVar('http_preview'):$this->getVar('http_preview')):(strlen($this->getVar('http'))>0&&$this->getVar('stream')==true?$this->getVar('http'):$this->getVar('raw')));
    			break;    			
    		case 'other':
    			return ($preview==true?(strlen($this->getVar('raw_preview'))>0?$this->getVar('raw_preview'):$this->getVar('raw')):$this->getVar('raw'));
    			break;
    	}
    }
    
    function getSpecialWithUserAgent($agent = '') {
    	if (empty($agent)) {
    		$agent = $_SERVER['HTTP_USER_AGENT'];
    	}
    	$components = array('A' => 'speciala', 'B' => 'specialb');
    	foreach($components as $mode => $component) {
    		foreach(explode('|', $this->_ModConfig[$component.'_agents']) as $useragent) {
				if (!empty($useragent)) {
		    		if (strpos(strtolower(' '.$agent), strtolower($useragent))>0) {
		    			return $mode;
		    		}
				}
	    	}
    	}
    	return false;
    }
    
    function getModeWithUserAgent($agent='') {
    	if (empty($agent)) {
    		$agent = $_SERVER['HTTP_USER_AGENT'];
    	}
    	$components = array('ios', 'rtmp', 'http', 'html5', 'rtsp', 'flash', 'silverlight', 'other');
    	for($pos=1;$pos<=8;$pos++) {
    		foreach($components as $component) {
    			if (isset($this->_ModConfig['order_'.$component])) {
	    			if ($this->_ModConfig['order_'.$component]==$pos) {
	    				foreach(explode('|', $this->_ModConfig[$component.'_agents']) as $useragent) {
				    		if (!empty($useragent))
					    		if (strpos(strtolower(' '.$agent), strtolower($useragent))>0||strpos(strtolower($useragent), strtolower(' '.$agent))>0) {
					    			return $this->_ModConfig[$component.'_videos'];
					    		}
				    	}			
	    			}
    			}
    		}
    	}
    	if ($this->getVar('mid')>0) {
    		$mimetypes_handler = xoops_getmodulehandler('mimetypes', 'vod');
    		$mimetype = $mimetypes_handler->get($this->getVar('mid'));
    		if (is_object($mimetype))
    			switch( $mimetype->getVar('support') ) {
    				case '_MI_VOD_FLASH':
    					return $this->_ModConfig['flash_videos'];
    					break;
    				case '_MI_VOD_HTML5':
    					return $this->_ModConfig['html5_videos'];
    					break;
    				case '_MI_VOD_IOS':
    					return $this->_ModConfig['ios_videos'];
    					break;
    				case '_MI_VOD_RTMP':
    					return $this->_ModConfig['rtmp_videos'];
    					break;
    				case '_MI_VOD_RTSP':
    					return $this->_ModConfig['rstp_videos'];
    					break;
    				case '_MI_VOD_SILVERLIGHT':
    					return $this->_ModConfig['silverlight_videos'];
    					break;
    				case '_MI_VOD_HTTP':
    					return $this->_ModConfig['http_videos'];
    					break;
    				case '_MI_VOD_OTHER':
    					return $this->_ModConfig['other_videos'];
    					break;
    			}
    	}
    	return $this->_ModConfig['default_videos'];
    }

    function getImage($field = 'avata', $uri = true) {
    	if (strlen($this->getVar($field))==0)
    		return false;
    	if (file_exists(XOOPS_ROOT_PATH.DS.$this->getVar('path').$this->getVar($field))==false)
    		return false;
    	if ($uri==true)
    		return XOOPS_URL.'/'.str_replace(DS, '/', $this->getVar('path')).$this->getVar($field);
    	else 
    		return XOOPS_ROOT_PATH.DS.$this->getVar('path').$this->getVar($field);
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
class VodVideosHandler extends XoopsPersistableObjectHandler
{

	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
	function filterFields()
	{
		return array(	'vid','cid','pid','catno','tokens','name','price','reference','height','width','default','currency',
						'purchases','views','hits','purchased','created','updated');
	}

	function filterUserFields()
	{
		return array(	'vid','cid','avata','catno','name','producedby','staring','length','year','tokens','price','currency',
						'views','hits','purchased','purchases','tags','preview','created','updated');
	}
	
	function __construct(&$db) 
    {
    	$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('vod');
		if (is_object($this->_Mod))
			$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));

		$this->db = $db;
        parent::__construct($db, 'vod_videos', 'VodVideos', "vid", "name");
    	
    }

	private function resetDefault() {
		$sql = "UPDATE " . $GLOBALS['xoopsDB']->prefix('vod_videos') . ' SET `default` = 0 WHERE 1 = 1';
		return $GLOBALS['xoopsDB']->queryF($sql);
	}
    
    function insert($object, $force=true, $run_plugin = false) {
    	if ($object->isNew()) {
    		$object->setVar('created', time());
    	} else {
    		$object->setVar('updated', time());
    	}
    	if ($object->vars['default']['changed']==true&&$object->getVar('default')==true) {
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
    
	function getIP() {
    	static $ret = array();
    	if (empty($ret))
    		$ret = vod_getIPData(false);
    	return $ret['ip'];
	}
	
	function getBasicModeWithUserAgent($agent='') {
    	if (empty($agent)) {
    		$agent = $_SERVER['HTTP_USER_AGENT'];
    	}
    	$components = array('ios', 'rtmp', 'http', 'html5', 'rtsp', 'flash', 'silverlight', 'other');
    	for($pos=1;$pos<=7;$pos++) {
    		foreach($components as $component) {
    			if (isset($this->_ModConfig['order_'.$component])) {
	    			if ($this->_ModConfig['order_'.$component]==$pos) {
	    				foreach(explode('|', $this->_ModConfig[$component.'_agents']) as $useragent) {
				    		if (!empty($useragent))
					    		if (strpos(strtolower(' '.$agent), strtolower($useragent))>0) {
					    			return $this->_ModConfig[$component.'_videos'];
					    		}
				    	}			
	    			}
    			}
    		}
    	}
    	return $this->_ModConfig['default_videos'];
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
}

?>