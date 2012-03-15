<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'vod');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"dashboard";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	case "dashboard":
	default:
		vod_adminMenu(0, 'index.php?op=dashboard');
		
		$videos_handler = xoops_getmodulehandler('videos', 'vod');
		$mimetypes_handler = xoops_getmodulehandler('mimetypes', 'vod');
	 	$indexAdmin = new ModuleAdmin();
	    $indexAdmin->addInfoBox(_AM_VOD_ADMIN_COUNTS);
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_FLATFILES."</label>", $videos_handler->getCount(new Criteria('stream', '0', '=')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_RTMPSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`rtmp`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_RTSPSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`rtsp`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_FLASHSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`flash`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_SILVERLIGHTSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`silverlight`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_IOSSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`ios`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_HTTPSTREAMS."</label>", $videos_handler->getCount(new Criteria('LENGTH(`http`)', '0', '>')), 'Green');
	    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_COUNTS, "<label>"._AM_VOD_ADMIN_THEREARE_MIMETYPES."</label>", $mimetypes_handler->getCount(), 'Purple');
	    $videos = $videos_handler->getObjects(new Criteria('`default`', '1'), false);
		if (isset($videos[0])) {
			$videos = $videos[0];
			$_SESSION['vod'][$videos->getVar('vid')]['main'] = true;
		    $indexAdmin->addInfoBox(_AM_VOD_ADMIN_DEFAULT);
		    $indexAdmin->addInfoBoxLine(_AM_VOD_ADMIN_DEFAULT, "<iframe src='".XOOPS_URL.'/modules/vod/?&state=admin&preview=0&vid='.$videos->getVar('vid')."&iframe=1&width=320px&height=200px' style='width:320px;height:200px;'></iframe>", '', 'Green');
		}
	    echo $indexAdmin->renderIndex();
		break;	
	case "about":
		echo vod_adminMenu(9, 'index.php?op=about');
		$paypalitemno='VOD106';
		$aboutAdmin = new ModuleAdmin();
		$about = $aboutAdmin->renderabout($paypalitemno, false);
		$donationform = array(	0 => '<form name="donation" id="donation" action="http://www.chronolabs.coop/modules/xpayment/" method="post" onsubmit="return xoopsFormValidate_donation();">',
								1 => '<table class="outer" cellspacing="1" width="100%"><tbody><tr><th colspan="2">'.constant('_AM_TWITTERBOMB_ABOUT_MAKEDONATE').'</th></tr><tr align="left" valign="top"><td class="head"><div class="xoops-form-element-caption-required"><span class="caption-text">Donation Amount</span><span class="caption-marker">*</span></div></td><td class="even"><select size="1" name="item[A][amount]" id="item[A][amount]" title="Donation Amount"><option value="5">5.00 AUD</option><option value="10">10.00 AUD</option><option value="20">20.00 AUD</option><option value="40">40.00 AUD</option><option value="60">60.00 AUD</option><option value="80">80.00 AUD</option><option value="90">90.00 AUD</option><option value="100">100.00 AUD</option><option value="200">200.00 AUD</option></select></td></tr><tr align="left" valign="top"><td class="head"></td><td class="even"><input class="formButton" name="submit" id="submit" value="'._SUBMIT.'" title="'._SUBMIT.'" type="submit"></td></tr></tbody></table>',
								2 => '<input name="op" id="op" value="createinvoice" type="hidden"><input name="plugin" id="plugin" value="donations" type="hidden"><input name="donation" id="donation" value="1" type="hidden"><input name="drawfor" id="drawfor" value="Chronolabs Co-Operative" type="hidden"><input name="drawto" id="drawto" value="%s" type="hidden"><input name="drawto_email" id="drawto_email" value="%s" type="hidden"><input name="key" id="key" value="%s" type="hidden"><input name="currency" id="currency" value="AUD" type="hidden"><input name="weight_unit" id="weight_unit" value="kgs" type="hidden"><input name="item[A][cat]" id="item[A][cat]" value="XDN%s" type="hidden"><input name="item[A][name]" id="item[A][name]" value="Donation for %s" type="hidden"><input name="item[A][quantity]" id="item[A][quantity]" value="1" type="hidden"><input name="item[A][shipping]" id="item[A][shipping]" value="0" type="hidden"><input name="item[A][handling]" id="item[A][handling]" value="0" type="hidden"><input name="item[A][weight]" id="item[A][weight]" value="0" type="hidden"><input name="item[A][tax]" id="item[A][tax]" value="0" type="hidden"><input name="return" id="return" value="http://www.chronolabs.coop/modules/donations/success.php" type="hidden"><input name="cancel" id="cancel" value="http://www.chronolabs.coop/modules/donations/success.php" type="hidden"></form>',																'D'=>'',
								3 => '',
								4 => '<!-- Start Form Validation JavaScript //-->
<script type="text/javascript">
<!--//
function xoopsFormValidate_donation() { var myform = window.document.donation; 
var hasSelected = false; var selectBox = myform.item[A][amount];for (i = 0; i < selectBox.options.length; i++ ) { if (selectBox.options[i].selected == true && selectBox.options[i].value != \'\') { hasSelected = true; break; } }if (!hasSelected) { window.alert("Please enter Donation Amount"); selectBox.focus(); return false; }return true;
}
//--></script>
<!-- End Form Validation JavaScript //-->');
	$paypalform = array(	0 => '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">',
								1 => '<input name="cmd" value="_s-xclick" type="hidden">',
								2 => '<input name="hosted_button_id" value="%s" type="hidden">',
								3 => '<img alt="" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" height="1" border="0" width="1">',
								4 => '<input src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" type="poster">',
								5 => '</form>');
		for($key=0;$key<=4;$key++) {
			switch ($key) {
				case 2:
					$donationform[$key] =  sprintf($donationform[$key], $GLOBALS['xoopsConfig']['sitename'] . ' - ' . (strlen($GLOBALS['xoopsUser']->getVar('name'))>0?$GLOBALS['xoopsUser']->getVar('name'). ' ['.$GLOBALS['xoopsUser']->getVar('uname').']':$GLOBALS['xoopsUser']->getVar('uname')), $GLOBALS['xoopsUser']->getVar('email'), XOOPS_LICENSE_KEY, strtoupper($GLOBALS['vodModule']->getVar('dirname')),  strtoupper($GLOBALS['vodModule']->getVar('dirname')). ' '.$GLOBALS['vodModule']->getVar('name'));
					break;
			}
		}
		
		$istart = strpos($about, ($paypalform[0]), 1);
		$iend = strpos($about, ($paypalform[5]), $istart+1)+strlen($paypalform[5])-1;
		echo (substr($about, 0, $istart-1));
		echo implode("\n", $donationform);
		echo (substr($about, $iend+1, strlen($about)-$iend-1));
		break;
	case "agents":	
		vod_adminMenu(6, 'index.php?op=agents');
		
		include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
		xoops_load('XoopsCache');
		$ret = XoopsCache::read('vod_user_agents');
		asort($ret, SORT_DESC);
		$ttl = count($ret);
		$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
		$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		foreach (array(	'time','videos','agents','user') as $id => $key) {
			$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="#">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
		}
		$GLOBALS['xoopsTpl']->assign('limit', $limit);
		$GLOBALS['xoopsTpl']->assign('start', $start);
		$GLOBALS['xoopsTpl']->assign('order', $order);
		$GLOBALS['xoopsTpl']->assign('sort', $sort);
		$GLOBALS['xoopsTpl']->assign('filter', $filter);
		$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
		$s=0;
		$i=0;
		foreach($ret as $time => $agent) {
			if (is_array($agent)&&$s>=$start&&$i<=$limit) {
				$GLOBALS['xoopsTpl']->append('useragents', array('time'=>date(_DATESTRING, $time), 'videos'=>$agent['videos'], 'user'=>$agent['user'], 'useragent'=>$agent['useragent']));
				$i++;
			}
			$s++;
		}
		$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
		$GLOBALS['xoopsTpl']->display('db:vod_cpanel_useragents_list.html');
		break;		
		
	case "currency":
		switch ($fct)
		{
			default:
			case "list":				
				vod_adminMenu(5, 'index.php?op=currency&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$currency_handler =& xoops_getmodulehandler('currency', 'vod');
					
				$criteria = $currency_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $currency_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($currency_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $currency_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
				$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
				$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
				$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
				$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
									
				$criteria->setStart($GLOBALS['start']);
				$criteria->setLimit($GLOBALS['limit']);
				$criteria->setSort('`'.$GLOBALS['sort'].'`');
				$criteria->setOrder($GLOBALS['order']);
					
				$currencys = $currency_handler->getObjects($criteria, true);
				foreach($currencys as $cid => $currency) {
					if (is_object($currency))					
						$GLOBALS['xoopsTpl']->append('currency', $currency->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', vod_currency_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_currency_list.html');
				break;		
				
			case "new":
			case "edit":
				
				vod_adminMenu(5, 'index.php?op=currency&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$currency_handler =& xoops_getmodulehandler('currency', 'vod');
				if (isset($_REQUEST['id'])) {
					$currency = $currency_handler->get(intval($_REQUEST['id']));
				} else {
					$currency = $currency_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $currency->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_currency_edit.html');
				break;
			case "save":
				
				$currency_handler =& xoops_getmodulehandler('currency', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$currency = $currency_handler->get($id);
				} else {
					$currency = $currency_handler->create();
				}
				$currency->setVars($_POST[$id]);
				if (!$id=$currency_handler->insert($currency)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_FAILEDTOSAVE);
					exit(0);
				} else {
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$currency_handler =& xoops_getmodulehandler('currency', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$currency = $currency_handler->get($id);
					$currency->setVars($_POST[$id]);
					if (!$currency_handler->insert($currency)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$currency_handler =& xoops_getmodulehandler('currency', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$currency = $currency_handler->get($id);
					if (!$currency_handler->delete($currency)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CURRENCY_DELETED);
						exit(0);
					}
				} else {
					$currency = $currency_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_VOD_MSG_CURRENCY_DELETE, $currency->getVar('name')));
				}
				break;
		}
		break;
	case "category":
		switch ($fct)
		{
			default:
			case "list":				
				vod_adminMenu(2, 'index.php?op=category&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$category_handler =& xoops_getmodulehandler('category', 'vod');
					
				$criteria = $category_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $category_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($category_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $category_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
				$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
				$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
				$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
				$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
									
				$criteria->setStart($GLOBALS['start']);
				$criteria->setLimit($GLOBALS['limit']);
				$criteria->setSort('`'.$GLOBALS['sort'].'`');
				$criteria->setOrder($GLOBALS['order']);
					
				$categorys = $category_handler->getObjects($criteria, true);
				foreach($categorys as $cid => $category) {
					if (is_object($category))					
						$GLOBALS['xoopsTpl']->append('categories', $category->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', vod_category_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_category_list.html');
				break;		
				
			case "new":
			case "edit":
				
				vod_adminMenu(2, 'index.php?op=category&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$category_handler =& xoops_getmodulehandler('category', 'vod');
				if (isset($_REQUEST['id'])) {
					$category = $category_handler->get(intval($_REQUEST['id']));
				} else {
					$category = $category_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $category->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_category_edit.html');
				break;
			case "save":
				
				$category_handler =& xoops_getmodulehandler('category', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$category = $category_handler->get($id);
				} else {
					$category = $category_handler->create();
				}
				$category->setVars($_POST[$id]);
				
				if (!$id=$category_handler->insert($category)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_FAILEDTOSAVE);
					exit(0);
				} else {
					
					if (isset($_FILES['avata'])&&!empty($_FILES['avata']['name'])) {
						
						if (!is_dir($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']))) {
							foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas'])) as $folders)
								foreach(explode('/', $folders) as $folder) {
									$path .= DS . $folder;
									mkdir($path, 0777);
								}
						}
						
						include_once($GLOBALS['xoops']->path('modules/vod/include/uploader.php'));
						$category = $category_handler->get($id);
						$uploader = new VodMediaUploader($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']), explode('|', $GLOBALS['vodModuleConfig']['allowed_mimetype']), $GLOBALS['vodModuleConfig']['filesize_upload'], 0, 0, explode('|', $GLOBALS['vodModuleConfig']['allowed_extensions']));
						$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
						
						if ($uploader->fetchMedia('avata')) {
						  	if (!$uploader->upload()) {
						  		
						    	vod_adminMenu(1);
						    	echo $uploader->getErrors();
								vod_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  	    } else {
					  	    	
						      	if (strlen($category->getVar('avata')))
						      		unlink($GLOBALS['xoops']->path($category->getVar('path')).$category->getVar('avata'));
						      	
						      	$category->setVar('path', $GLOBALS['vodModuleConfig']['upload_areas']);
						      	$category->setVar('avata', $uploader->getSavedFileName());
						      	@$category_handler->insert($category);
						      	
						    }      	
					  	} else {
					  		
					  		vod_adminMenu(1);
					       	echo $uploader->getErrors();
							vod_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
					   	}
					}
					
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$category_handler =& xoops_getmodulehandler('category', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$category = $category_handler->get($id);
					$category->setVars($_POST[$id]);
					if (!$category_handler->insert($category)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$category_handler =& xoops_getmodulehandler('category', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$category = $category_handler->get($id);
					if (!$category_handler->delete($category)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CATEGORY_DELETED);
						exit(0);
					}
				} else {
					$category = $category_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_VOD_MSG_CATEGORY_DELETE, $category->getVar('name')));
				}
				break;
		}
		break;
				
	case "sessions":
		switch ($fct)
		{
			default:
			case "list":				
				vod_adminMenu(3, 'index.php?op=sessions&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
					
				$criteria = $sessions_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $sessions_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($sessions_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $sessions_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
				$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
				$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
				$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
				$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
									
				$criteria->setStart($GLOBALS['start']);
				$criteria->setLimit($GLOBALS['limit']);
				$criteria->setSort('`'.$GLOBALS['sort'].'`');
				$criteria->setOrder($GLOBALS['order']);
					
				$sessions = $sessions_handler->getObjects($criteria, true);
				foreach($sessions as $cid => $session) {
					if (is_object($session))					
						$GLOBALS['xoopsTpl']->append('sessions', $session->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', vod_sessions_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_sessions_list.html');
				break;		
				
			case "new":
			case "edit":
				
				vod_adminMenu(3, 'index.php?op=sessions&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
				if (isset($_REQUEST['id'])) {
					$sessions = $sessions_handler->get(intval($_REQUEST['id']));
				} else {
					$sessions = $sessions_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $sessions->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_sessions_edit.html');
				break;
			case "save":
				
				$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$sessions = $sessions_handler->get($id);
				} else {
					$sessions = $sessions_handler->create();
				}
				$sessions->setVars($_POST[$id]);
				if (!$id=$sessions_handler->insert($sessions)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_FAILEDTOSAVE);
					exit(0);
				} else {
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$sessions = $sessions_handler->get($id);
					$sessions->setVars($_POST[$id]);
					if (!$sessions_handler->insert($sessions)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$sessions_handler =& xoops_getmodulehandler('sessions', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$sessions = $sessions_handler->get($id);
					if (!$sessions_handler->delete($sessions)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_SESSIONS_DELETED);
						exit(0);
					}
				} else {
					$sessions = $sessions_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_VOD_MSG_SESSIONS_DELETE, $sessions->getVar('name')));
				}
				break;
			case 'cart':
				vod_adminMenu(3, 'index.php?op=sessions&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$cart_handler =& xoops_getmodulehandler('cart', 'vod');
					
				$criteria = $cart_handler->getFilterCriteria($GLOBALS['filter']);
				$criteria->add(new Criteria('sessid', $_REQUEST['id']));
				$ttl = $cart_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'id='.$_REQUEST['id'].'&limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($cart_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_REQUEST['id'].'&start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $cart_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
				$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
				$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
				$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
				$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
									
				$criteria->setStart($GLOBALS['start']);
				$criteria->setLimit($GLOBALS['limit']);
				$criteria->setSort('`'.$GLOBALS['sort'].'`');
				$criteria->setOrder($GLOBALS['order']);
					
				$carts = $cart_handler->getObjects($criteria, true);
				foreach($carts as $cid => $cart) {
					if (is_object($cart))					
						$GLOBALS['xoopsTpl']->append('cart', $cart->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', vod_cart_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_cart_list.html');
				break;
		}
		break;

	case "cart":
		switch ($fct)
		{
			case "new":
			case "edit":
				
				vod_adminMenu(3, 'index.php?op=sessions&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$cart_handler =& xoops_getmodulehandler('cart', 'vod');
				if (isset($_REQUEST['id'])) {
					$cart = $cart_handler->get(intval($_REQUEST['id']));
				} else {
					$cart = $cart_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $cart->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_cart_edit.html');
				break;
			case "save":
				
				$cart_handler =& xoops_getmodulehandler('cart', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$cart = $cart_handler->get($id);
				} else {
					$cart = $cart_handler->create();
				}
				$cart->setVars($_POST[$id]);
				if (!$id=$cart_handler->insert($cart)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_FAILEDTOSAVE);
					exit(0);
				} else {
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$cart_handler =& xoops_getmodulehandler('cart', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$cart = $cart_handler->get($id);
					$cart->setVars($_POST[$id]);
					if (!$cart_handler->insert($cart)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$cart_handler =& xoops_getmodulehandler('cart', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$cart = $cart_handler->get($id);
					if (!$cart_handler->delete($cart)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_VOD_MSG_CART_DELETED);
						exit(0);
					}
				} else {
					$cart = $cart_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_VOD_MSG_CART_DELETE, $cart->getVar('name')));
				}
				break;
		}
		break;		
	case "log":	
			
		xoops_loadLanguage('admin', 'vod');
		vod_adminMenu(7, 'index.php?op=log');
		
		include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );

		$log_handler =& xoops_getmodulehandler('log', 'vod');
			
		$criteria = $log_handler->getFilterCriteria($GLOBALS['filter']);
		$ttl = $log_handler->getCount($criteria);
		$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';

		$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
		$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

		foreach ($log_handler->filterFields() as $id => $key) {
			$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
			$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $log_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
		}
		
		$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
		$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
		$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
		$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
		$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
		$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
							
		$criteria->setStart($GLOBALS['start']);
		$criteria->setLimit($GLOBALS['limit']);
		$criteria->setSort('`'.$GLOBALS['sort'].'`');
		$criteria->setOrder($GLOBALS['order']);
			
		$logs = $log_handler->getObjects($criteria, true);
		foreach($logs as $id => $log) {
			$GLOBALS['xoopsTpl']->append('log', $log->toArray());
		}
				
		$GLOBALS['xoopsTpl']->display('db:vod_cpanel_log_list.html');
		break;
							
	
	case "videos":	
		$category_handler =& xoops_getmodulehandler('category', 'vod');
		if ($category_handler->getCount(NULL)==0) {
			redirect_header($_SERVER['PHP_SELF'].'?op=category&fct=new', 10, _AM_VOD_MSG_CATEGORY_NEEDTOADD_FIRST);
			exit;
		}
		switch ($fct)
		{
			default:
			case "list":				
				vod_adminMenu(1, 'index.php?op=videos&fct=list');

				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				$criteria = $videos_handler->getFilterCriteria($filter);
				$ttl = $videos_handler->getCount($criteria);
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
				
				foreach ($videos_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $videos_handler->getFilterForm($filter, $key, $sort, $op, $fct));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $limit);
				$GLOBALS['xoopsTpl']->assign('start', $start);
				$GLOBALS['xoopsTpl']->assign('order', $order);
				$GLOBALS['xoopsTpl']->assign('sort', $sort);
				$GLOBALS['xoopsTpl']->assign('filter', $filter);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
				
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
				
				$videos = $videos_handler->getObjects($criteria, true);
				foreach($videos as $cid => $video) {
					if (is_object($video))
						$GLOBALS['xoopsTpl']->append('videos', $video->toArray(false, 'admin'));
				}
				
				$GLOBALS['xoopsTpl']->assign('form', vod_videos_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_videos_list.html');
				break;
						
			case "publish":

				xoops_loadLanguage('main', 'vod');
				
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				if (isset($_REQUEST['id'])) {
					$video = $videos_handler->get(intval($_REQUEST['id']));
				}
				
				$GLOBALS['xoopsTpl']->assign('video', $video->toArray(false, 'main'));
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
								
				$nw_stories_handler = xoops_getmodulehandler('nw_stories', 'vod');
				if ($video->getVar('storyid')>0) {
					$article = $nw_stories_handler->get($video->getVar('storyid')); 
				} else {
					$article = $nw_stories_handler->create();
				}
				
				$article->setVar('title', $video->getVar('name'). ', '.$video->getVar('producedby'). ' ('.$video->getVar('year').')');
				if ($article->getVar('created')==0) {
					$article->setVar('created', time());
				}
				if ($article->getVar('published')==0) {
					$article->setVar('published', time());
				}
				$article->setVar('hostname', $_SERVER["REMOTE_ADDR"]);
				$article->setVar('uid', $GLOBALS['xoopsUser']->getVar('uid'));
				if ($video->getVar('price')>0) {
					$article->setVar('topicid', $GLOBALS['vodModuleConfig']['cost_topic_id']);
					$article->setVar('description', substr('Paid Video on Demand Service, video for viewing : '.$video->getVar('name'). ', produced by: '.$video->getVar('producedby'). ' ('.$video->getVar('year').')', 0, 254));
					$article->setVar('geturl', XOOPS_URL.'/modules/vod/index.php?op=cart&fct=add&vid='.$video->getVar('vid').'&cid='.$video->getVar('cid').'&uri=');
				} else { 
					$article->setVar('topicid', $GLOBALS['vodModuleConfig']['free_topic_id']);
					$article->setVar('description', substr('Free Fight Video for viewing : '.$video->getVar('name'). ', produced by: '.$video->getVar('producedby'). ' ('.$video->getVar('year').')', 0, 254));
					$article->setVar('geturl', XOOPS_URL.'/modules/vod/index.php?op=videos&fct=view&vid='.$video->getVar('vid').'&cid='.$video->getVar('cid').'&uri=');
				}
				$keywords=array();
				foreach(explode(' ',$video->getVar('name')) as $word)
					$keywords[] = $word;
				$keywords[] = $video->getVar('producedby');
				$keywords[] = $video->getVar('staring');
				$keywords[] = $video->getVar('year');
				$article->setVar('keywords', substr(implode(',', $keywords),0,254));
				
				ob_start();
				$GLOBALS['xoopsTpl']->display('db:vod_news_hometext.html');
				$article->setVar('hometext', ob_get_contents());
				ob_end_clean();
				$video->setVar('storyid', $nw_stories_handler->insert($article, true));
				@$videos_handler->insert($video);
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _VS_AM_MSG_VIDEOS_ARTICLEPUBLISHED);
				exit(0);
				break;
						
			case "new":
			case "edit":
				
				vod_adminMenu(1, 'index.php?op=videos&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
									
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				if (isset($_REQUEST['id'])) {
					$videos = $videos_handler->get(intval($_REQUEST['id']));
				} else {
					$videos = $videos_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', vod_videos_get_form($videos));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_videos_edit.html');
				break;
			case "save":
				
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$videos = $videos_handler->get($id);
				} else {
					$videos = $videos_handler->create();
				}
				$videos->setVars($_POST[$id]);
				if (!$id=$videos_handler->insert($videos)) {
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_FAILEDTOSAVE);
					exit(0);
				} else {
					if (file_exists($GLOBALS['xoops']->path('/modules/tag/class/tag.php'))&&$GLOBALS['vodModuleConfig']['tags']) {
						$tag_handler = xoops_getmodulehandler('tag', 'tag');
						$tag_handler->updateByItem($_POST['tags'], $id, $GLOBALS['vodModule']->getVar("dirname"), $videos->getVar('cid'));
					}
					if (isset($_FILES['poster'])&&!empty($_FILES['poster']['name'])) {
						if (!is_dir($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']))) {
							foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas'])) as $folders)
								foreach(explode('/', $folders) as $folder) {
									$path .= DS . $folder;
									mkdir($path, 0777);
								}
						}
						include_once($GLOBALS['xoops']->path('modules/vod/include/uploader.php'));
						$videos = $videos_handler->get($id);
						$uploader = new VodMediaUploader($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']), explode('|', $GLOBALS['vodModuleConfig']['allowed_mimetype']), $GLOBALS['vodModuleConfig']['filesize_upload'], 0, 0, explode('|', $GLOBALS['vodModuleConfig']['allowed_extensions']));
						$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
						if ($uploader->fetchMedia('poster')) {
						  	if (!$uploader->upload()) {
						    	vod_adminMenu(1);
						    	echo $uploader->getErrors();
								vod_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  	    } else {
					  	    	
						      	if (strlen($videos->getVar('poster')))
						      		unlink($GLOBALS['xoops']->path($videos->getVar('path')).$videos->getVar('poster'));
						      	
						      	$videos->setVar('path', $GLOBALS['vodModuleConfig']['upload_areas']);
						      	$videos->setVar('poster', $uploader->getSavedFileName());
						      	@$videos_handler->insert($videos);
						    }      	
					  	} else {
					  		vod_adminMenu(1);
					       	echo $uploader->getErrors();
							vod_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
					   	}
					}
					if (isset($_FILES['avata'])&&!empty($_FILES['avata']['name'])) {
						if (!is_dir($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']))) {
							foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas'])) as $folders)
								foreach(explode('/', $folders) as $folder) {
									$path .= DS . $folder;
									mkdir($path, 0777);
								}
						}
						include_once($GLOBALS['xoops']->path('modules/vod/include/uploader.php'));
						$videos = $videos_handler->get($id);
						$uploader = new VodMediaUploader($GLOBALS['xoops']->path($GLOBALS['vodModuleConfig']['upload_areas']), explode('|', $GLOBALS['vodModuleConfig']['allowed_mimetype']), $GLOBALS['vodModuleConfig']['filesize_upload'], 0, 0, explode('|', $GLOBALS['vodModuleConfig']['allowed_extensions']));
						$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
						if ($uploader->fetchMedia('avata')) {
						  	if (!$uploader->upload()) {
						    	vod_adminMenu(1);
						    	echo $uploader->getErrors();
								vod_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  	    } else {
					  	    	
						      	if (strlen($videos->getVar('avata')))
						      		unlink($GLOBALS['xoops']->path($videos->getVar('path')).$videos->getVar('avata'));
						      	
						      	$videos->setVar('path', $GLOBALS['vodModuleConfig']['upload_areas']);
						      	$videos->setVar('avata', $uploader->getSavedFileName());
						      	@$videos_handler->insert($videos);
						    }      	
					  	} else {
					  		vod_adminMenu(1);
					       	echo $uploader->getErrors();
							vod_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
					   	}
					}		
					switch($_REQUEST['mode']) {
						case 'new':
							redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_VOD_MSG_VIDEOS_SAVEDOKEY);
							break;
						default:
						case 'edit':
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_SAVEDOKEY);
							break;
					}
					exit(0);					
				}
				break;
			case "savelist":
				
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$videos = $videos_handler->get($id);
					$videos->setVars($_POST[$id]);
					if (!$videos_handler->insert($videos)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$videos_handler =& xoops_getmodulehandler('videos', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$videos = $videos_handler->get($id);
					if (!$videos_handler->delete($videos)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_VIDEOS_DELETED);
						exit(0);
					}
				} else {
					$videos = $videos_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_VOD_MSG_VIDEOS_DELETE, $videos->getVar('name')));
				}
				break;
		}
		break;
	case "mimetypes":	
		switch ($fct)
		{
			default:
			case "list":				
				vod_adminMenu(5, 'index.php?op=mimetypes&fct=list');

				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$mimetypes_handler =& xoops_getmodulehandler('mimetypes', 'vod');
				$criteria = $mimetypes_handler->getFilterCriteria($filter);
				$ttl = $mimetypes_handler->getCount($criteria);
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
				
				foreach ($mimetypes_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_VOD_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $mimetypes_handler->getFilterForm($filter, $key, $sort, $op, $fct));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $limit);
				$GLOBALS['xoopsTpl']->assign('start', $start);
				$GLOBALS['xoopsTpl']->assign('order', $order);
				$GLOBALS['xoopsTpl']->assign('sort', $sort);
				$GLOBALS['xoopsTpl']->assign('filter', $filter);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['vodModuleConfig']);
				
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
				
				$mimetypess = $mimetypes_handler->getObjects($criteria, true);
				foreach($mimetypess as $cid => $mimetypes) {
					if (is_object($mimetypes))
						$GLOBALS['xoopsTpl']->append('mimetypes', $mimetypes->toArray());
				}
				
				$GLOBALS['xoopsTpl']->assign('form', vod_mimetypes_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_mimetypes_list.html');
				break;		
				
			case "new":
			case "edit":
				
				vod_adminMenu(5, 'index.php?op=mimetypes&fct=list');
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
									
				$mimetypes_handler =& xoops_getmodulehandler('mimetypes', 'vod');
				if (isset($_REQUEST['id'])) {
					$mimetypes = $mimetypes_handler->get(intval($_REQUEST['id']));
				} else {
					$mimetypes = $mimetypes_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', vod_mimetypes_get_form($mimetypes));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:vod_cpanel_mimetypes_edit.html');
				break;
			case "save":
				
				$mimetypes_handler =& xoops_getmodulehandler('mimetypes', 'vod');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$mimetypes = $mimetypes_handler->get($id);
				} else {
					$mimetypes = $mimetypes_handler->create();
				}
				$mimetypes->setVars($_POST[$id]);
				if (!$id=$mimetypes_handler->insert($mimetypes)) {
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_FAILEDTOSAVE);
					exit(0);
				} else {
					switch($_REQUEST['mode']) {
						case 'new':
							redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_VOD_MSG_MIMETYPES_SAVEDOKEY);
							break;
						default:
						case 'edit':
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_SAVEDOKEY);
							break;
					}
					exit(0);					
				}
				break;
			case "savelist":
				
				$mimetypes_handler =& xoops_getmodulehandler('mimetypes', 'vod');
				foreach($_REQUEST['id'] as $id) {
					$mimetypes = $mimetypes_handler->get($id);
					$mimetypes->setVars($_POST[$id]);
					if (!$mimetypes_handler->insert($mimetypes)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$mimetypes_handler =& xoops_getmodulehandler('mimetypes', 'vod');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$mimetypes = $mimetypes_handler->get($id);
					if (!$mimetypes_handler->delete($mimetypes)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_VOD_MSG_MIMETYPES_DELETED);
						exit(0);
					}
				} else {
					$mimetypes = $mimetypes_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_VOD_MSG_MIMETYPES_DELETE, $mimetypes->getVar('name')));
				}
				break;
		}
		break;			
	}
	
	vod_footer_adminMenu();
	xoops_cp_footer();
?>