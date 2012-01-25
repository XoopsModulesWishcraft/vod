<?php
	
if (!function_exists('vod_getToken')) {
	function vod_getToken() {
		$sql = "SELECT md5(rand()/rand()*rand()/rand()*rand()*rand()/rand()*rand()) as `salt`";
		$result = $GLOBALS['xoopsDB']->queryF($sql);
		list($salt) = $GLOBALS['xoopsDB']->fetchRow($result);
		return $salt;
	}
}

if (!function_exists('ucword')) {
	function ucword($string) {
		$ret = array();
		foreach(explode(' ', strtolower($string)) as $part)
			$ret[] = ucfirst($part);
		return implode(' ', $ret);
	}
}
	
if (!function_exists("vod_getIPData")) {
	function vod_getIPData($ip=false){
		$ret = array();
		if (is_object($GLOBALS['xoopsUser'])) {
			$ret['uid'] = $GLOBALS['xoopsUser']->getVar('uid');
			$ret['uname'] = $GLOBALS['xoopsUser']->getVar('uname');
			$ret['email'] = $GLOBALS['xoopsUser']->getVar('email');
		} else {
			$ret['uid'] = 0;
			$ret['uname'] = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
			$ret['email'] = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
		}
		$ret['agent'] = $_SERVER['HTTP_USER_AGENT'];
		if (!$ip) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){ 
				$ip = (string)$_SERVER["HTTP_X_FORWARDED_FOR"]; 
				$ret['is_proxied'] = true;
				$proxy_ip = $_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = true;
					$ret['proxy-ip6'] = true;
					$ret['ip4'] = false;
					$ret['proxy-ip4'] = false;
				} else {
					$ret['ip4'] = true;
					$ret['proxy-ip4'] = true;
					$ret['ip6'] = false;
					$ret['proxy-ip6'] = false;
				}
				$ret['ip'] = $ip;
				$ret['proxy-ip'] = $proxy_ip;
			}else{ 
				$ret['is_proxied'] = false;
				$ip = (string)$_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = true;
					$ret['ip4'] = false;
				} else {
					$ret['ip4'] = true;
					$ret['ip6'] = false;
				}
				$ret['ip'] = $ip;
			} 
		} else {
			$ret['is_proxied'] = false;
			$ret['network-addy'] = @gethostbyaddr($ip); 
			$ret['long'] = @ip2long($ip);
			if (is_ipv6($ip)) {
				$ret['ip6'] = true;
				$ret['ip4'] = false;
			} else {
				$ret['ip4'] = true;
				$ret['ip6'] = false;
			}
			$ret['ip'] = $ip;
		}
		$ret['made'] = time();				
		return $ret;
	}
}

if (!function_exists("is_ipv6")) {
	function is_ipv6($ip = "") 
	{ 
		if ($ip == "") 
			return false;
			
		if (substr_count($ip,":") > 0){ 
			return true; 
		} else { 
			return false; 
		} 
	} 
}

if (!function_exists('vod_getFilterElement')) {
	function vod_getFilterElement($filter, $field, $sort='created', $op = '', $fct = '') {
		$components = vod_getFilterURLComponents($filter, $field, $sort);
		$ele = false;
		include_once('formobjects.vod.php');
		switch ($field) {
			case 'mode':
				$ele = new VodFormSelectSupport('', 'filter_'.$field.'', $components['value'], 1, false, true);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
			case 'currency':
				$ele = new VodFormSelectCurrency('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    $ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
				break;
		    	
		    case 'pid':
				$ele = new VodFormSelectPackageID('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    $ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
				break;
		    case 'parent':
				$ele = new VodFormSelectCategory('', 'filter_'.$field.'', $components['value'], 1, false, true);
			   	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
				break;
		    case 'cid':
				if ($op!='category') {
					$ele = new VodFormSelectCategory('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
				}
				break;
			case 'mid':
				if ($op!='mimetypes') {
					$ele = new VodFormSelectMimetype('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
				}
				break;
		    case 'purchases':
		    case 'hits':
		    case 'views':
			case 'id':
		    case 'tokens':
		    case 'price':
		    case 'catno':
		    case 'prefix':
		    case 'name':
		    case 'reference':
		    case 'raw':
		    case 'width':
		    case 'height':
		    case 'mimetype':
		    case 'codecs':	
		    	$ele = new XoopsFormElementTray('');
				$ele->addElement(new XoopsFormText('', 'filter_'.$field.'', 11, 40, $components['value']));
				$button = new XoopsFormButton('', 'button_'.$field.'', '[+]');
		    	$button->setExtra('onclick="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+$(\'#filter_'.$field.'\').val()'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	$ele->addElement($button);
		    	break;
		}
		return $ele;
	}
}

if (!function_exists('vod_getFilterURLComponents')) {
	function vod_getFilterURLComponents($filter, $field, $sort='created') {
		$parts = explode('|', $filter);
		$ret = array();
		$value = '';
		$ele_value = '';
		$operator = '';
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (count($var)>1) {
	    		if ($var[0]==$field) {
	    			$ele_value = $var[1];
	    			if (isset($var[2]))
	    				$operator = $var[2];
	    		} elseif ($var[0]!=1) {
	    			$ret[] = implode(',', $var);
	    		}
    		}
    	}
    	$pagenav = array();
    	$pagenav['op'] = isset($_REQUEST['op'])?$_REQUEST['op']:"vod";
		$pagenav['fct'] = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
		$pagenav['limit'] = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
		$pagenav['start'] = 0;
		$pagenav['order'] = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
		$pagenav['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':$sort;
    	$retb = array();
		foreach($pagenav as $key=>$value) {
			$retb[] = "$key=$value";
		}
		return array('value'=>$ele_value, 'field'=>$field, 'operator'=>$operator, 'filter'=>implode('|', $ret), 'extra'=>implode('&', $retb));
	}
}

if (!function_exists("vod_adminMenu")) {
  function vod_adminMenu ($currentoption = 0, $page = 'index.php?op=dashboard')  {
		echo "<table width=\"100%\" border='0'><tr><td>";
	   	echo "<tr><td>";
	   	$indexAdmin = new ModuleAdmin();
	   	echo $indexAdmin->addNavigation($page);
  	   	echo "</td></tr>";
	   	echo "<tr'><td><div id='form'>";
  }
  
  function vod_footer_adminMenu()
  {
		echo "</div></td></tr>";
  		echo "</table>";
  		echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=\"" . XOOPS_URL . '/' . $GLOBALS['vodImageAdmin'].'/xoopsmicrobutton.gif'.'"'." alt='XOOPS' title='XOOPS'></a></div>";
		echo "<div class='center smallsmall italic pad5'><strong>" . $GLOBALS['vodModule']->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a> and <a class='tooltip' rel='external' href='http://www.chronolabs.coop/' title='Visit Chronolabs Co-op'>Chronolabs Co-op</a></div>";
    }
}