<?php
	include ('header.php');
	
	if (empty($agent))
    	$agent = $_SERVER['HTTP_USER_AGENT'];
    		
	$vid = isset($_GET['vid'])?intval($_GET['vid']):0;
	$cid = isset($_GET['cid'])?intval($_GET['cid']):0;
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"videos";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	$uri = !empty($_REQUEST['uri'])?''.urldecode($_REQUEST['uri']).'':$_SERVER['REQUEST_URI'];
	$state = isset($_GET['state'])?$_GET['state']:'list';
	$preview = ($GLOBALS['vodModuleConfig']['preview_by_default']==false?isset($_GET['preview'])?intval($_GET['preview']):(isset($_SESSION['vod'][$vid]['main'])&&$state=='main'?false:true):(isset($_SESSION['vod'][$vid]['main'])&&$state=='main'?false:true));
	$width = isset($_GET['width'])?intval($_GET['width']):($preview==false?'':$GLOBALS['vodModuleConfig']['video_width_'.$state]);
	$height = isset($_GET['height'])?intval($_GET['height']):($preview==false?'':$GLOBALS['vodModuleConfig']['video_height_'.$state]);
	
	if ((!isset($_GET['iframe'])&&$_GET['iframe']!=true)&&($fct!='view'&&$op=='videos')) {
		
		$videos_handler =& xoops_getmodulehandler('videos', 'vod');
		$category_handler =& xoops_getmodulehandler('category', 'vod');
		$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
		
		$_SESSION['vod']['cart'] = $sessions_handler->intialiseCart();
		if (is_array($_SESSION['vod']['cart'])&&$_SESSION['vod']['cart']!=false) {
			setcookie('cart', $_SESSION['vod']['cart'], 3600*24*30);
		} else {
			setcookie('cart', false, 3600*24*30);
		}
		
		$xoopsOption['template_main'] = 'vod_index.html';
		include($GLOBALS['xoops']->path('/header.php'));
		$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
		$GLOBALS['xoopsTpl']->assign('back_url', $uri);
		$GLOBALS['xoopsTpl']->assign('uri', urlencode($_SERVER['REQUEST_URI']));
		if ($vid>0) {
			$video = $videos_handler->get($vid);
			$GLOBALS['xoopsTpl']->assign('video', $video->toArray($preview, 'main'));
		}
		if ($cid>0) {
			$category = $category_handler->get($cid);
			$GLOBALS['xoopsTpl']->assign('category', $category->toArray());
		}
		$categories = array();
		$criteria = new Criteria('parent', $cid);
		$i = 0;
		foreach($category_handler->getObjects($criteria, true) as $cidb => $categoryb) {
			if ($i=0) {
				$categories[$cidb]['tr'] = true;
			} else {
				$categories[$cidb]['tr'] = false;
			}
			$categories[$cidb] = $categoryb->toArray();
			$criteriab = new Criteria('parent', $cidb);
			if ($category_handler->getCount($criteriab)>0) {
				foreach($category_handler->getObjects($criteriab, true) as $cidc => $categoryc) {
					$categories[$cidb]['subcategories'][$cidc] = $categoryc->toArray();
				}
			}
			$i++;
			if ($i>$GLOBALS['vodModuleConfig']['cat_per_row'])
				$i = 0;
		}
		$GLOBALS['xoopsTpl']->assign('categories', $categories);
		switch ($op) {
		case "videos":	
			switch ($fct)
			{
				case "details":
					break;
				default:
				case "list":				
						
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					
					$criteria = $videos_handler->getFilterCriteria($filter);
					$ttl = $videos_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
					
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&cid='.$cid.'&filter='.$filter.'&fct='.$fct.'&op='.$op);
					$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
					
					foreach ($videos_handler->filterUserFields() as $id => $key) {
						$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'&cid='.$cid.'">'.(defined('_MN_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_MN_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_MN_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $videos_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['xoopsTpl']->assign('limit', $limit);
					$GLOBALS['xoopsTpl']->assign('start', $start);
					$GLOBALS['xoopsTpl']->assign('order', $order);
					$GLOBALS['xoopsTpl']->assign('sort', $sort);
					$GLOBALS['xoopsTpl']->assign('filter', $filter);
					$GLOBALS['xoopsTpl']->assign('cid', $cid);
					$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
					
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
					
					$videos = $videos_handler->getObjects($criteria, true);
					foreach($videos as $vid => $video) {
						if (is_object($video))
							$GLOBALS['xoopsTpl']->append('videos', $video->toArray($preview, 'list'));
					}
					
					break;		
			}
		case 'cart':
			switch ($fct) {
				case 'add':
					if (!is_array($_SESSION['vod']['cart'])||!isset($_SESSION['vod']['cart'])) {
						$_SESSION['vod']['cart'] = $sessions_handler->createCart();
						setcookie('cart', $_SESSION['vod']['cart'], 3600*24*30);
					}
					$sessions = $sessions_handler->get($_SESSION['vod']['cart']['sessid']);
					$sessions->addVideo($video);
					redirect_header($uri, 10, _MN_VOD_MSG_ITEMADDEDTOCART);
					exit;
					break;
				case 'remove':
					if (!is_array($_SESSION['vod']['cart'])||!isset($_SESSION['vod']['cart'])) {
						redirect_header($uri, 10, _NOPERM);
						exit;
					}
					$sessions = $sessions_handler->get($_SESSION['vod']['cart']['sessid']);
					$sessions->removeVideo($video);
					redirect_header($uri, 10, _MN_VOD_MSG_ITEMREMOVEDFROMCART);
					exit;
					break;
				case 'view':
					$xoopsOption['template_main'] = 'vod_index_cart_view.html';
					if (!is_array($_SESSION['vod']['cart'])||!isset($_SESSION['vod']['cart'])) {
						redirect_header($uri, 10, _NOPERM);
						exit;
					}
					$sessions = $sessions_handler->get($_SESSION['vod']['cart']['sessid']);
					$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
					$GLOBALS['xoopsTpl']->assign('cart', $sessions->getCartArray(false));
					$GLOBALS['xoopsTpl']->assign('session', $sessions->toArray(false));
					$GLOBALS['xoopsTpl']->assign('set_email', (strlen($sessions->getVar('email'))==0));
					$GLOBALS['xoopsTpl']->assign('set_name', (strlen($sessions->getVar('name'))==0));
					$GLOBALS['xoopsTpl']->assign('set_pass', (strlen($sessions->getVar('pass'))!=32));
					break;
				case 'checkout':
					$xoopsOption['template_main'] = 'vod_index_cart_xpayment_form.html';
					if (!is_array($_SESSION['vod']['cart'])||!isset($_SESSION['vod']['cart'])) {
						redirect_header($uri, 10, _NOPERM);
						exit;
					}
					$sessions = $sessions_handler->get($_SESSION['vod']['cart']['sessid']);
					
					if (strlen($session->getVar('email'))==0) {
						if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9-]+)$", $_POST['email'])) {
							redirect_header($uri, 10, _MN_VOD_MSG_EMAILADDRESS_NOTVALID);
							exit;
						}
						$session->setVar('email', $_POST['email']);
						$save=true;
					}
					if (strlen($session->getVar('name'))==0) {
						if (empty($_POST['name'])) {
							redirect_header($uri, 10, _MN_VOD_MSG_NAME_EMPTY);
							exit;
						}
						$session->setVar('name', $_POST['name']);
						$save=true;
					}
					if (strlen($session->getVar('pass'))<>32) {
						if (empty($_POST['pass'])) {
							redirect_header($uri, 10, _MN_VOD_MSG_PASSWORD_EMPTY);
							exit;
						}
						$session->setVar('pass', md5($_POST['pass']));
						$save=true;
					}
					if ($save==true)
						$sessions = $sessions_handler->get($sessions_handler->insert($session, true));
					
					$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
					$GLOBALS['xoopsTpl']->assign('cart', $sessions->getCartArray(true));
					$GLOBALS['xoopsTpl']->assign('session', $sessions->toArray(true));
					break;
			}
		}
		include($GLOBALS['xoops']->path('/footer.php'));
	} elseif ((!isset($_GET['iframe'])&&$_GET['iframe']!=true)&&($fct=='view'&&$op=='videos')) {
		$videos_handler = xoops_getmodulehandler('videos', 'vod');
		if ($vid!=0) {
			$videos = $videos_handler->get($vid);
		}
		$cart_handler = xoops_getmodulehandler('cart', 'vod');
		if (isset($_REQUEST['crtid'])) {
			$cart = $cart_handler->get($_REQUEST['crtid']);
			$_SESSION['vod'][$vid]['crtid'] = $_REQUEST['crtid'];
		} elseif (isset($_SESSION['vod'][$vid]['crtid'])) {
			$cart = $cart_handler->get($_REQUEST['crtid']);
		}
		$sessions_handler = xoops_getmodulehandler('sessions', 'vod');
		if (isset($_REQUEST['sessid'])) {
			$session = $sessions_handler->get($_REQUEST['sessid']);
			$_SESSION['vod'][$vid]['sessid'] = $_REQUEST['sessid'];
		} elseif (isset($_SESSION['vod'][$vid]['sessid'])) {
			$session = $sessions_handler->get($_SESSION['vod'][$vid]['sessid']);
		}
		if (!is_object($videos)||!is_object($cart)||!is_object($session)) {
			redirect(XOOPS_URL, 10, _NOPERM);
			exit;
		}
		
		if ($_REQUEST['token']!=sha1($session->getVar('paidkey').$videos->getVar('token'))&&!isset($_SESSION['vod'][$vid]['main'])) {
			redirect(XOOPS_URL, 10, _NOPERM);
			exit;
		} else {
			$_SESSION['vod'][$vid]['main'] = time();
		}
		
		if ($cart->getVar('expires')<time()) {
			unset($_SESSION['vod'][$vid]['main']);
			redirect(XOOPS_URL, 10, _NOPERM);
			exit;
		}
		
		$cart->getVar('claimed', time());
		$cart_handler->insert($cart);
		
		$mode = $videos->getModeWithUserAgent();
		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
			$xoopsOption['template_main'] = 'vod_index_'.$mode.'_videos.html';
			include($GLOBALS['xoops']->path('/header.php'));
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			$data = array();
			$data = $videos->toArray(false, 'main');
			$data['mode'] = $mode;
			$data['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_width'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_width'):($videos->getVar('width')))));
    		$data['height'] = (!empty($height)&&!empty($height)&&$width&&$height?$height:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_height'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_height'):($videos->getVar('height')))));
    		$data['source'] = $videos->getSource($mode, false);
			$data['id'] = $videos->getReference(false, false);
			$GLOBALS['xoopsTpl']->assign('videos', $data);
			$GLOBALS['xoopsTpl']->assign('preview', false);
			if (isset($data['mimetype']))
	    		$GLOBALS['xoopsTpl']->assign('mimetype', $data['mimetype']);
			$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height)), false, 'main');
			include($GLOBALS['xoops']->path('/footer.php'));
		} else {
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
			$html = $videos->getHTML(false, $data['width'], $data['height'], '', '', false, 'main');
			$GLOBALS['xoopsTpl']->assign('videos', $data);
			$GLOBALS['xoopsTpl']->assign('preview', false);
			$html .= "\n<script type='text/javascript'>\n".$videos->getInsecureJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height), false, 'main')."\n</script>";
			$GLOBALS['xoopsTpl']->assign('contents', $html);
    		include($GLOBALS['xoops']->path('/footer.php'));
		}
	} else {
		$videos_handler = xoops_getmodulehandler('videos', 'vod');
		if ($vid!=0) {
			$videos = $videos_handler->get($vid);
		} else {
			$videoss = $videos_handler->getObjects(new Criteria('`default`', '1'), false);
			if (is_object($videoss[0]))
				$videos = $videoss[0];
		}
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
			$data = $videos->toArray($preview, $state);
			$data['mode'] = $mode;
			$data['width'] = (!empty($height)&&!empty($width)&&$width&&$height?$width:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_width'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_width'):($videos->getVar('width')))));
    		$data['height'] = (!empty($height)&&!empty($height)&&$width&&$height?$height:($videos->getSpecialWithUserAgent($agent)=='A'?$videos->getVar('speciala_height'):($videos->getSpecialWithUserAgent($agent)=='B'?$videos->getVar('specialb_height'):($videos->getVar('height')))));
			$data['source'] = $videos->getSource($mode, $preview);
			$data['id'] = $videos->getReference(false, $preview);
			$html = $videos->getHTML(false, $data['width'], $data['height'], '', '', $preview, $state);
			$GLOBALS['xoopsTpl']->assign('videos', $data);
			$GLOBALS['xoopsTpl']->assign('preview', $preview);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			if (isset($data['mimetype']))
    			$GLOBALS['xoopsTpl']->assign('mimetype', $data['mimetype']);
    		if ($GLOBALS['vodModuleConfig'][$mode.'_secure']==true) {
    			$GLOBALS['xoopsTpl']->assign('contents', '');
				$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $videos->getJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height), $preview, $state));
    		} else { 
				$html .= "\n<script type='text/javascript'>\n".$videos->getInsecureJS(false, (empty($width)?$videos->getVar('width'):$width), (empty($height)?$videos->getVar('height'):$height), $preview, $state)."\n</script>";
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