<?php
header('Content-type: application/json');

include ('header.php');

xoops_loadLanguage('main', 'vod');

$user = vod_getIPData(false);

$resolve = $_GET['resolve'];
$preview = (empty($_GET['preview'])?false:$_GET['preview']);
			
$passkey = isset($_SESSION['vod'][$user['ip']][$resolve]['passkey'])?$_SESSION['vod'][$user['ip']][$resolve]['passkey']:'';
$vid = $_SESSION['vod'][$user['ip']][$resolve][$_SESSION['vod'][$user['ip']][$resolve]['passkey']]['vid'];
$width = $_SESSION['vod'][$user['ip']][$resolve][$_SESSION['vod'][$user['ip']][$resolve]['passkey']][$vid]['width'];
$height = $_SESSION['vod'][$user['ip']][$resolve][$_SESSION['vod'][$user['ip']][$resolve]['passkey']][$vid]['height'];
$block = $_SESSION['vod'][$user['ip']][$resolve][$_SESSION['vod'][$user['ip']][$resolve]['passkey']][$vid]['block'];

$values = array();
$submit = true;

// Checks Passkey
$diff=$GLOBALS['vodModuleConfig']['passkey_diff'];
$start=$GLOBALS['vodModuleConfig']['passkey_weight'];
$passed=false;
for($t=time()-$start;$t<=time()+$diff+$start;$t++) {
	if ($passkey==sha1(XOOPS_LICENSE_KEY.$GLOBALS['vodModuleConfig']['salt'].$user['ip'].date('Ymdhis', $t))) {
		$passed=true;
		continue;	
	}
}

if ($passed==false) {
	$values['innerhtml'][$resolve] = _MN_VOD_TOKEN_KEY_EXPIRED;
} else {
	
	$videos_handler = xoops_getmodulehandler('videos', 'vod');
	$videos = $videos_handler->get($vid);
	if (!is_object($videos)) {
		$values['innerhtml'][$resolve] = _MN_VOD_SESSION_EXPIRED;
	} else {
		switch ($videos->getSpecialWithUserAgent($_SERVER['HTTP_USER_AGENT'])) {
			case 'A':
			case 'B':
				$width = $videos->getVar('width');
				$height = $videos->getVar('height');
			default:
				if (empty($width)) 
					$width = $videos->getVar('width');
				if (empty($height)) 
					$height = $videos->getVar('height');
				break;		
		}
		 
		$mode = $videos->getModeWithUserAgent($_SERVER['HTTP_USER_AGENT']);
		$out = array();
		if (in_array($mode, $GLOBALS['vodModuleConfig']['load_vod'])) {
			if ($videos->getVar('stream')==true&&$mode=='rtmp') {
				$out[0] = XOOPS_URL.dirname($_SERVER['PHP_SELF'])."/swf/vod-3.2.7.swf";
				$out[1] = ($videos->getVar('autoplay')==true?true:false);
				$out[2] = ($videos->getVar('muted')==true?0:$videos->getVar('level'));
				if ($preview==false) {
					$out[3] = $this->getVar('rtmp_server');
					$out[4] = $this->getVar('rtmp');
				} else {
					$out[3] = $this->getVar('rtmp_server_preview');
					$out[4] = $this->getVar('rtmp_preview');
				}
				$out[5] = XOOPS_URL.dirname($_SERVER['PHP_SELF'])."/swf/vod.controls-3.2.5.swf";
				$out[6] = ($videos->getVar('play')==true&&$videos->getVar('controls')==true?true:false);
				$out[7] = ($videos->getVar('volume')==true&&$videos->getVar('controls')==true?true:false);
				$out[8] = ($videos->getVar('mute')==true&&$videos->getVar('controls')==true?true:false);
				$out[9] = ($videos->getVar('time')==true&&$videos->getVar('controls')==true?true:false);
				$out[10] = ($videos->getVar('stop')==true&&$videos->getVar('controls')==true?true:false);
				$out[11] = ($videos->getVar('fullscreen')==true&&$videos->getVar('controls')==true?true:false);
				$out[12] = ($videos->getVar('scrubber')==true&&$videos->getVar('controls')==true?true:false);
				$out[13] = XOOPS_URL.dirname($_SERVER['PHP_SELF'])."/swf/vod.rtmp-3.2.3.swf";
				$action = 'vodrtmp';
			} else {
				$out[0] = XOOPS_URL.dirname($_SERVER['PHP_SELF'])."/swf/vod-3.2.7.swf";
				$out[1] = ($videos->getVar('autoplay')==true?true:false);
				$out[2] = ($videos->getVar('muted')==true?0:$videos->getVar('level'));
				$out[3] = $videos->getSource($mode, $preview);
				$out[5] = XOOPS_URL.dirname($_SERVER['PHP_SELF'])."/swf/vod.controls-3.2.5.swf";
				$out[6] = ($videos->getVar('play')==true&&$videos->getVar('controls')==true?true:false);
				$out[7] = ($videos->getVar('volume')==true&&$videos->getVar('controls')==true?true:false);
				$out[8] = ($videos->getVar('mute')==true&&$videos->getVar('controls')==true?true:false);
				$out[9] = ($videos->getVar('time')==true&&$videos->getVar('controls')==true?true:false);
				$out[10] = ($videos->getVar('stop')==true&&$videos->getVar('controls')==true?true:false);
				$out[11] = ($videos->getVar('fullscreen')==true&&$videos->getVar('controls')==true?true:false);
				$out[12] = ($videos->getVar('scrubber')==true&&$videos->getVar('controls')==true?true:false);
				$action = 'vodfile';
			}
		} elseif (in_array($mode, $GLOBALS['vodModuleConfig']['load_videojs'])) {
			if ($videos->getVar('stream')==false) {
				$out[0] = $videos->getVar('controls');
				$out[1] = $videos->getVar('level')/100;
				$out[2] = $videos->getVar('autoplay');
				$action = 'videojsfile'; 		
		 	} else {
				$out[0] = $videos->getVar('controls');
				$out[1] = $videos->getVar('level')/100;
				$out[2] = $videos->getVar('autoplay');
				$action = 'videojsstream';
		 	}
		} else {
			if ($videos->getVar('stream')==false) {
				$action = 'otherfile'; 		
		 	} else {
				$action = 'otherstream';
		 	}
		}
		$out[20] = $width;
		$out[21] = $height;
		$values['innerhtml'][$resolve] = ($videos->getHTML($block, $width, $height, $_SERVER['HTTP_USER_AGENT'], $user['ip'], $preview));
		$values[$action][$videos->getReference($block)] = $out;
	}
}

if (!function_exists('json_encode')) {
	if (!class_exists('Services_JSON'))
		include ($GLOBALS['xoops']->path('/modules/vod/include/JSON.php'));
	$json = new services_JSON();
	print $json->encode($values);
} else {
	print json_encode($values);
}
?>