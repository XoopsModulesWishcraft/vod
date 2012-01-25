<?php
	include ('header.php');
	
	if (empty($agent))
    	$agent = $_SERVER['HTTP_USER_AGENT'];
    		
	$vid = isset($_GET['vid'])?intval($_GET['vid']):0;
	$width = isset($_GET['width'])?intval($_GET['width']):'';
	$height = isset($_GET['height'])?intval($_GET['height']):'';
	
	$videos_handler = xoops_getmodulehandler('videos', 'vod');
	if ($vid!=0) {
		$videos = $videos_handler->get($vid);
	} else {
		$videoss = $videos_handler->getObjects(new Criteria('`default`', '1'), false);
		if (is_object($videoss[0]))
			$videos = $videoss[0];
	}
	$mode = $videos->getModeWithUserAgent();
	if (!$GLOBALS['vodModuleConfig']['iframe']&&(!isset($_GET['iframe'])&&$_GET['iframe']!=true)) {
		if (is_object($videos)) {
			if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
				$xoopsOption['template_main'] = 'vod_index_'.$mode.'_videos.html';
				include($GLOBALS['xoops']->path('/header.php'));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$data = array();
				$data = $videos->toArray();
				$data['mode'] = $mode;
				$data['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_width'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_width'):($videos->getVar('width')))));
    			$data['height'] = (!empty($height)&&!empty($height)&&$width&&$height?$height:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_height'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_height'):($videos->getVar('height')))));
    			$data['source'] = $videos->getSource($mode);
				$data['id'] = $videos->getReference(false);
				$GLOBALS['xoopsTpl']->assign('videos', $data);
				if (isset($data['mimetype']))
	    			$GLOBALS['xoopsTpl']->assign('mimetype', $data['mimetype']);
				$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height)));
				include($GLOBALS['xoops']->path('/footer.php'));
			} else {
				$xoopsOption['template_main'] = 'vod_json_'.$mode.'_videos.html';
				include($GLOBALS['xoops']->path('/header.php'));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$data = array();
				$data = $videos->toArray();
				$data['mode'] = $mode;
				$data['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_width'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_width'):($videos->getVar('width')))));
    			$data['height'] = (!empty($height)&&!empty($height)&&$width&&$height?$height:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_height'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_height'):($videos->getVar('height')))));
				$data['source'] = $videos->getSource($mode);
				$data['id'] = $videos->getReference(false);
				$GLOBALS['xoopsTpl']->assign('videos', $data);
				if (isset($data['mimetype']))
	    			$GLOBALS['xoopsTpl']->assign('mimetype', $data['mimetype']);
				$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getInsecureJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height)));
				include($GLOBALS['xoops']->path('/footer.php'));
			}
		} else {
			$xoopsOption['template_main'] = 'vod_index.html';
			include($GLOBALS['xoops']->path('/header.php'));
			xoops_error(_MN_VOD_NO_DEFAULT, _MN_VOD_NO_DEFAULT_TITLE);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			include($GLOBALS['xoops']->path('/footer.php'));
		}
	} else {
		if (is_object($videos)) {
			$mode = $videos->getModeWithUserAgent();
			$xoopsOption['template_main'] = 'vod_index_iframe_'.$mode.'_videos.html';
			global $xoopsOption, $xoopsConfig, $vodModule;
			
		    // include Smarty template engine and initialize it
		    require_once $GLOBALS['xoops']->path('class/template.php');
		    require_once $GLOBALS['xoops']->path('class/theme.php');
		    require_once $GLOBALS['xoops']->path('class/theme_blocks.php');
			
		    if (@$xoopsOption['template_main']) {
		        if (false === strpos($xoopsOption['template_main'], ':')) {
		            $xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
		        }
		    }
			
		    $xoopsThemeFactory = null;
		    $xoopsThemeFactory = new xos_opal_ThemeFactory();
		    $xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
		    $xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];
			
		    /**
		     * @var xos_opal_Theme
		     */
		    $xoTheme  =& $xoopsThemeFactory->createInstance(array('contentTemplate' => @$xoopsOption['template_main']));
		    $GLOBALS['xoopsTpl'] =& $xoTheme->template;
			
		    $GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_VOD_JQUERY, array('type'=>'text/javascript'));
			$GLOBALS['loaded_jquery']=true;
			
			$data = array();
			$data = $videos->toArray();
			$data['mode'] = $mode;
			$data['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_width'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_width'):($videos->getVar('width')))));
    		$data['height'] = (!empty($height)&&!empty($height)&&$width&&$height?$height:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_height'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_height'):($videos->getVar('height')))));
			$data['source'] = $videos->getSource($mode);
			$data['id'] = $videos->getReference(false);
			$html = $videos->getHTML(false, $data['width'], $data['height']);
			$GLOBALS['xoopsTpl']->assign('videos', $data);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			if (isset($data['mimetype']))
    			$GLOBALS['xoopsTpl']->assign('mimetype', $data['mimetype']);
    		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
    			$GLOBALS['xoopsTpl']->assign('contents', '');
				$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height)));
    		} else { 
				$html .= "\n<script type='text/javascript'>\n".$videos->getInsecureJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height))."\n</script>";
				$GLOBALS['xoopsTpl']->assign('contents', $html);
    		}
			$GLOBALS['xoTheme']->addStylesheet('', array('type'=>'text/css'), 'body { margin: 0 0 0 0; padding: 0 0 0 0;}
html { margin: 0 0 0 0; padding: 0 0 0 0;}');

			$xoopsPreload =& XoopsPreload::getInstance();
			$xoopsPreload->triggerEvent('core.header.addmeta');
			
		    $old = array(
	            'robots',
	            'keywords',
	            'description',
	            'rating',
	            'author',
	            'copyright');
	        
	        foreach ($GLOBALS['xoTheme']->metas['meta'] as $name => $value) {
	            if (in_array($name, $old)) {
	                $GLOBALS['xoopsTpl']->assign("xoops_meta_$name", htmlspecialchars($value, ENT_QUOTES));
	                unset($GLOBALS['xoTheme']->metas['meta'][$name]);
	            }
	        }
	        
	        // We assume no overlap between $GLOBALS['xoopsOption']['xoops_module_header'] and $GLOBALS['xoopsTpl']->get_template_vars( 'xoops_module_header' ) ?
	        $header = empty($GLOBALS['xoopsOption']['xoops_module_header']) ? $GLOBALS['xoopsTpl']->get_template_vars('xoops_module_header') : $GLOBALS['xoopsOption']['xoops_module_header'];
	        $GLOBALS['xoopsTpl']->assign('xoops_module_header', $GLOBALS['xoTheme']->renderMetas(null, true) . "\n" . $header);
	        $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $videos->getVar('name'));
		    $GLOBALS['xoopsTpl']->xoops_setCaching(0);
            $GLOBALS['xoopsTpl']->display($xoopsOption['template_main']);
            
		} else {
			$xoopsOption['template_main'] = 'vod_index_iframe.html';
			global $xoopsOption, $xoopsConfig, $vodModule;
	
		    $xoopsOption['theme_use_smarty'] = 0;
		
		    // include Smarty template engine and initialize it
		    require_once $GLOBALS['xoops']->path('class/template.php');
		    require_once $GLOBALS['xoops']->path('class/theme.php');
		    require_once $GLOBALS['xoops']->path('class/theme_blocks.php');
		
		    if (@$xoopsOption['template_main']) {
		        if (false === strpos($xoopsOption['template_main'], ':')) {
		            $xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
		        }
		    }
		
		    $xoopsThemeFactory = null;
		    $xoopsThemeFactory = new xos_opal_ThemeFactory();
		    $xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
		    $xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];
		
		    /**
		     * @var xos_opal_Theme
		     */
		    $xoTheme  =& $xoopsThemeFactory->createInstance(array('contentTemplate' => @$xoopsOption['template_main']));
		    $GLOBALS['xoopsTpl'] =& $xoTheme->template;
		
		    
			xoops_error(_MN_VOD_NO_DEFAULT, _MN_VOD_NO_DEFAULT_TITLE);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			
            $GLOBALS['xoopsTpl']->xoops_setCaching(0);
            $GLOBALS['xoopsTpl']->display($xoopsOption['template_main']);
		}		
	}