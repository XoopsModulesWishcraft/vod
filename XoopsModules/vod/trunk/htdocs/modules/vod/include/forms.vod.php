<?php

	function vod_videos_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('videos', 'vod');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'vod');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_ISNEW_VOD, 'videos', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_EDIT_VOD, 'videos', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('vid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'videos');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		$ele['cid'] = new VodFormSelectCategory(($as_array==false?_FRM_VOD_FORM_VOD_CATEGORY:''), $id.'[cid]', $object->getVar('cid'), 1, false, false, false);
		$ele['cid']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_CATEGORY_DESC:''));
		$ele['mid'] = new VodFormSelectMimetype(($as_array==false?_FRM_VOD_FORM_VOD_MIMETYPE:''), $id.'[mid]', $object->getVar('mid'));
		$ele['mid']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_MIMETYPE_DESC:''));
		if ($GLOBALS['vodModuleConfig']['matrixstream']==true) {
			$ele['pid'] = new VodFormSelectPackageID(($as_array==false?_FRM_VOD_FORM_VOD_PACKAGE:''), $id.'[pid]', $object->getVar('pid'));
			$ele['pid']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_PACKAGE_DESC:''));
		}
		$ele['catno'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_CATNO:''), $id.'[catno]', ($as_array==false?16:8),32, $object->getVar('catno'));
		$ele['catno']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_CATNO_DESC:''));
		$ele['name'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_NAME:''), $id.'[name]', ($as_array==false?55:21),128, $object->getVar('name'));
		$ele['name']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_NAME_DESC:''));
		$ele['reference'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_ID:''), $id.'[reference]', ($as_array==false?55:21), 128, $object->getVar('reference'));
		$ele['reference']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_ID_DESC:''));
		$ele['length'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_LENGTH:''), $id.'[length]', ($as_array==false?32:16),64, $object->getVar('length'));
		$ele['length']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_LENGTH_DESC:''));
		$ele['producedby'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_PRODUCEDBY:''), $id.'[producedby]', ($as_array==false?45:25),128, $object->getVar('producedby'));
		$ele['producedby']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_PRODUCEDBY_DESC:''));
		$ele['staring'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_STARING:''), $id.'[staring]', ($as_array==false?45:25),255, $object->getVar('staring'));
		$ele['staring']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_STARING_DESC:''));
		$ele['year'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_YEAR:''), $id.'[year]', ($as_array==false?5:5),5, $object->getVar('year'));
		$ele['year']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_YEAR_DESC:''));
		if (class_exists('XoopsFormTag')) {
			$ele['tags'] = new XoopsFormTag("tags", 60, 255, $object->getVar('vid'), $object->getVar('cid'));
			$ele['tags']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_TAGS_DESC:''));			
		} else
			$ele['tags'] = new XoopsFormHidden("tags", $object->getVar('tags'));
		
		$ele['summary'] = new XoopsFormTextArea(($as_array==false?_FRM_VOD_FORM_VOD_SUMMARY:''), $id.'[summary]', $object->getVar('summary'), ($as_array==false?9:4), ($as_array==false?55:25));
		$ele['summary']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SUMMARY_DESC:''));
		$description_configs = array();
		$description_configs['name'] = $id.'[description]';
		$description_configs['value'] = $object->getVar('decription');
		$description_configs['rows'] = 35;
		$description_configs['cols'] = 60;
		$description_configs['width'] = "100%";
		$description_configs['height'] = "400px";
		$ele['description'] = new XoopsFormEditor(_FRM_VOD_FORM_VOD_DESCRIPTION, $GLOBALS['vodModuleConfig']['editor'], $description_configs);
		$ele['description']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_DESCRIPTION_DESC:''));
		$ele['raw'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RAW:''), $id.'[raw]', ($as_array==false?55:21), 500, $object->getVar('raw'));
		$ele['raw']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RAW_DESC:''));
		$ele['stream'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_STREAM:''), $id.'[stream]', $object->getVar('stream'));
		$ele['stream']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_STREAM_DESC:''));
		$ele['rtmp_server'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_SERVER:''), $id.'[rtmp_server]', ($as_array==false?55:21), 500, $object->getVar('rtmp_server'));
		$ele['rtmp_server']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_SERVER_DESC:''));
		$ele['rtmp'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTMP:''), $id.'[rtmp]', ($as_array==false?55:21), 500, $object->getVar('rtmp'));
		$ele['rtmp']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_DESC:''));
		$ele['flash'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_FLASH:''), $id.'[flash]', ($as_array==false?55:21), 500, $object->getVar('flash'));
		$ele['flash']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_FLASH_DESC:''));
		$ele['ios'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_IOS:''), $id.'[ios]', ($as_array==false?55:21), 500, $object->getVar('ios'));
		$ele['ios']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_IOS_DESC:''));
		$ele['silverlight'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SILVERLIGHT:''), $id.'[silverlight]', ($as_array==false?55:21), 500, $object->getVar('silverlight'));
		$ele['silverlight']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SILVERLIGHT_DESC:''));
		$ele['rtsp'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTSP:''), $id.'[rtsp]', ($as_array==false?55:21), 500, $object->getVar('rtsp'));
		$ele['rtsp']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTSP_DESC:''));
		$ele['http'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_HTTP:''), $id.'[http]', ($as_array==false?55:21), 500, $object->getVar('http'));
		$ele['http']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_HTTP_DESC:''));
		$ele['raw_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RAW_PREVIEW:''), $id.'[raw_preview]', ($as_array==false?55:21), 500, $object->getVar('raw_preview'));
		$ele['raw_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RAW_PREVIEW_DESC:''));
		$ele['rtmp_server_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_SERVER_PREVIEW:''), $id.'[rtmp_server_preview]', ($as_array==false?55:21), 500, $object->getVar('rtmp_server_preview'));
		$ele['rtmp_server_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_SERVER_PREVIEW_DESC:''));
		$ele['rtmp_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_PREVIEW:''), $id.'[rtmp_preview]', ($as_array==false?55:21), 500, $object->getVar('rtmp_preview'));
		$ele['rtmp_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTMP_PREVIEW_DESC:''));
		$ele['flash_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_FLASH_PREVIEW:''), $id.'[flash_preview]', ($as_array==false?55:21), 500, $object->getVar('flash_preview'));
		$ele['flash_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_FLASH_PREVIEW_DESC:''));
		$ele['ios_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_IOS_PREVIEW:''), $id.'[ios_preview]', ($as_array==false?55:21), 500, $object->getVar('ios_preview'));
		$ele['ios_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_IOS_PREVIEW_DESC:''));
		$ele['silverlight_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SILVERLIGHT_PREVIEW:''), $id.'[silverlight_preview]', ($as_array==false?55:21), 500, $object->getVar('silverlight_preview'));
		$ele['silverlight_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SILVERLIGHT_PREVIEW_DESC:''));
		$ele['rtsp_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_RTSP_PREVIEW:''), $id.'[rtsp_preview]', ($as_array==false?55:21), 500, $object->getVar('rtsp_preview'));
		$ele['rtsp_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_RTSP_PREVIEW_DESC:''));
		$ele['http_preview'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_HTTP_PREVIEW:''), $id.'[http_preview]', ($as_array==false?55:21), 500, $object->getVar('http_preview'));
		$ele['http_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_HTTP_PREVIEW_DESC:''));
		$ele['level'] = new XoopsFormSelect(($as_array==false?_FRM_VOD_FORM_VOD_LEVEL:''), $id.'[level]', $object->getVar('level'));
		$ele['level']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_LEVEL_DESC:''));
		for($i=1;$i<=100;$i++) {
			$ele['level']->addOption($i, $i.'%');
		}
		$ele['autoplay'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_AUTOPLAY:''), $id.'[autoplay]', $object->getVar('autoplay'));
		$ele['autoplay']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_AUTOPLAY_DESC:''));
		$ele['speciala_autoplay'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_AUTOPLAY:''), $id.'[speciala_autoplay]', $object->getVar('speciala_autoplay', 'no'));
		$ele['speciala_autoplay']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_AUTOPLAY_DESC:''));
		$ele['specialb_autoplay'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_AUTOPLAY:''), $id.'[specialb_autoplay]', $object->getVar('specialb_autoplay', 'no'));
		$ele['specialb_autoplay']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_AUTOPLAY_DESC:''));
		$ele['controls'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_CONTROLS:''), $id.'[controls]', $object->getVar('controls'));
		$ele['controls']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_CONTROLS_DESC:''));
		$ele['speciala_controls'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_CONTROLS:''), $id.'[speciala_controls]', $object->getVar('speciala_controls', 'no'));
		$ele['speciala_controls']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_CONTROLS_DESC:''));
		$ele['specialb_controls'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_CONTROLS:''), $id.'[specialb_controls]', $object->getVar('specialb_controls', 'no'));
		$ele['specialb_controls']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_CONTROLS_DESC:''));
		$ele['muted'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_MUTED:''), $id.'[muted]', $object->getVar('muted'));
		$ele['muted']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_MUTED_DESC:''));
		$ele['play'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_PLAY:''), $id.'[play]', $object->getVar('play'));
		$ele['play']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_PLAY_DESC:''));
		$ele['volume'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_VOLUME:''), $id.'[volume]', $object->getVar('volume'));
		$ele['volume']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_VOLUME_DESC:''));
		$ele['mute'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_MUTE:''), $id.'[mute]', $object->getVar('mute'));
		$ele['mute']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_MUTE_DESC:''));
		$ele['time'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_TIME:''), $id.'[time]', $object->getVar('time'));
		$ele['time']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_TIME_DESC:''));
		$ele['stop'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_STOP:''), $id.'[stop]', $object->getVar('stop'));
		$ele['stop']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_STOP_DESC:''));
		$ele['fullscreen'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_FULLSCREEN:''), $id.'[fullscreen]', $object->getVar('fullscreen'));
		$ele['fullscreen']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_FULLSCREEN_DESC:''));
		$ele['scrubber'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_SCRUBBER:''), $id.'[scrubber]', $object->getVar('scrubber'));
		$ele['scrubber']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SCRUBBER_DESC:''));
		$ele['width'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_WIDTH:''), $id.'[width]', ($as_array==false?15:10),12, $object->getVar('width'));
		$ele['width']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_WIDTH_DESC:''));
		$ele['speciala_width'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_WIDTH:''), $id.'[speciala_width]', ($as_array==false?15:10),12, $object->getVar('speciala_width', 'no'));
		$ele['speciala_width']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_WIDTH_DESC:''));
		$ele['specialb_width'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_WIDTH:''), $id.'[specialb_width]', ($as_array==false?15:10),12, $object->getVar('specialb_width', 'no'));
		$ele['specialb_width']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_WIDTH_DESC:''));
		$ele['height'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_HEIGHT:''), $id.'[height]', ($as_array==false?15:10),12, $object->getVar('height'));
		$ele['height']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_HEIGHT_DESC:''));
		$ele['speciala_height'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_HEIGHT:''), $id.'[speciala_height]', ($as_array==false?15:10),12, $object->getVar('speciala_height', 'no'));
		$ele['speciala_height']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALA_HEIGHT_DESC:''));
		$ele['specialb_height'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_HEIGHT:''), $id.'[specialb_height]', ($as_array==false?15:10),12, $object->getVar('specialb_height', 'no'));
		$ele['specialb_height']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_SPECIALB_HEIGHT_DESC:''));
		$ele['default'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_VOD_DEFAULT:''), $id.'[default]', $object->getVar('default'));
		$ele['default']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_DEFAULT_DESC:''));
		$ele['poster'] = new XoopsFormFile(($as_array==false?_FRM_VOD_FORM_VOD_UPLOAD_POSTER:''), 'poster', $GLOBALS['vodModuleConfig']['filesize_upload']);
		$ele['poster']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_UPLOAD_POSTER_DESC:''));
		if (strlen($object->getVar('poster'))>0&&file_exists($GLOBALS['xoops']->path($object->getVar('path').$object->getVar('poster')))) {
			$ele['poster_preview'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_POSTER:''), '<img src="'.$object->getImage('poster').'" width="340px" />' );
			$ele['poster_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_POSTER_DESC:''));
		}
		$ele['avata'] = new XoopsFormFile(($as_array==false?_FRM_VOD_FORM_VOD_UPLOAD_AVATA:''), 'avata', $GLOBALS['vodModuleConfig']['filesize_upload']);
		$ele['avata']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_UPLOAD_AVATA_DESC:''));
		if (strlen($object->getVar('avata'))>0&&file_exists($GLOBALS['xoops']->path($object->getVar('path').$object->getVar('avata')))) {
			$ele['avata_preview'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_AVATA:''), '<img src="'.$object->getImage('avata').'" width="340px" />' );
			$ele['avata_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_AVATA_DESC:''));
		}

		if ($GLOBALS['vodModuleConfig']['matrixstream']==false) {
			$ele['price'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_PRICE:''), $id.'[price]', ($as_array==false?15:10),12, $object->getVar('price', 'no'));
			$ele['price']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_PRICE_DESC:''));
			$ele['currency'] = new VodFormSelectCurrency(($as_array==false?_FRM_VOD_FORM_VOD_CURRENCY:''), $id.'[currency]', $object->getVar('currency'));
			$ele['currency']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_CURRENCY_DESC:''));
		}
		if ($GLOBALS['vodModuleConfig']['matrixstream']==true) {
			$ele['tokens'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_VOD_TOKENS:''), $id.'[tokens]', ($as_array==false?15:10),12, $object->getVar('tokens', 'no'));
			$ele['tokens']->setDescription(($as_array==false?_FRM_VOD_FORM_VOD_TOKENS_DESC:''));
		}
		if ($object->getVar('purchases')>0) {
			$ele['purchases'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_PURCHASES:''), $object->getVar('purchases'));
		}
		if ($object->getVar('views')>0) {
			$ele['views'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_VIEWS:''), $object->getVar('views'));
		}
		if ($object->getVar('hits')>0) {
			$ele['hits'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_HITS:''), $object->getVar('hits'));
		}
		if ($object->getVar('earned_usd')>0) {
			$ele['earned_usd'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_EARNED_USD:''), '$ ' . number_format($object->getVar('earned_usd'),2). 'USD');
		}
		if ($object->getVar('earned_aud')>0) { 
			$ele['earned_aud'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_EARNED_AUD:''), '$ ' . number_format($object->getVar('earned_aud'),2). 'AUD');
		}
		if ($object->getVar('discounted_usd')>0) {
			$ele['discounted_usd'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_DISCOUNTED_USD:''), '$ ' . number_format($object->getVar('discounted_usd'),2). 'USD');
		}
		if ($object->getVar('discounted_aud')>0) {
			$ele['discounted_aud'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_DISCOUNTED_AUD:''), '$ ' . number_format($object->getVar('discounted_aud'),2). 'AUD');
		}
		if ($object->getVar('purchased')>0) {
			$ele['purchased'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_PURCHASED:''), date(_DATESTRING, $object->getVar('purchased')));
		}
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_VOD_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}

		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name', 'id', 'source');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}

	function vod_mimetypes_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('mimetypes', 'vod');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'vod');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_ISNEW_MIMETYPES, 'mimetypes', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_EDIT_MIMETYPES, 'mmimetypes', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$id = $object->getVar('mid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'mimetypes');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		$ele['support'] = new VodFormSelectSupport(($as_array==false?_FRM_VOD_FORM_MIMETYPES_SUPPORT:''), $id.'[support]', $object->getVar('support'));
		$ele['support']->setDescription(($as_array==false?_FRM_VOD_FORM_MIMETYPES_SUPPORT_DESC:''));
		$ele['name'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_MIMETYPES_NAME:''), $id.'[name]', ($as_array==false?55:21),128, $object->getVar('name'));
		$ele['name']->setDescription(($as_array==false?_FRM_VOD_FORM_MIMETYPES_NAME_DESC:''));
		$ele['mimetype'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_MIMETYPES_MIMETYPE:''), $id.'[mimetype]', ($as_array==false?55:21), 128, $object->getVar('mimetype'));
		$ele['mimetype']->setDescription(($as_array==false?_FRM_VOD_FORM_MIMETYPES_MIMETYPE_DESC:''));
		$ele['codecs'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_MIMETYPES_CODECS:''), $id.'[codecs]', ($as_array==false?55:21), 500, $object->getVar('codecs'));
		$ele['codecs']->setDescription(($as_array==false?_FRM_VOD_FORM_MIMETYPES_CODECS_DESC:''));
		$ele['default'] = new XoopsFormRadioYN(($as_array==false?_FRM_VOD_FORM_MIMETYPES_DEFAULT:''), $id.'[default]', $object->getVar('default'));
		$ele['default']->setDescription(($as_array==false?_FRM_VOD_FORM_MIMETYPES_DEFAULT_DESC:''));

		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_MIMETYPES_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_MIMETYPES_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}
		
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name', 'mimetype', 'support');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}

	function vod_currency_get_form($object, $as_array=false) {
		
		xoops_loadLanguage('forms', 'vod');
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('currency', 'vod');
			$object = $handler->create();
			$id = '0';
		} else {
			$id = $object->getVar('currency_id'); 
		}
		
		$ele = array();
		$_required = array('email');
		
		$ele['start'] = new XoopsFormHidden('start', $_REQUEST['start'] );
		$ele['limit'] = new XoopsFormHidden('limit', $_REQUEST['limit'] );
		$ele['sort'] = new XoopsFormHidden('sort', $_REQUEST['sort'] );
		$ele['order'] = new XoopsFormHidden('order', $_REQUEST['order'] );
		$ele['filter'] = new XoopsFormHidden('filter', $_REQUEST['filter'] );
				
	    if (!$object->isNew()) {
	        if ($as_array==true) { $ele['id'] = new XoopsFormHidden('id['.$id.']', $id ); } else { $ele['id'] = new XoopsFormHidden('id', $id ); }
	        $ele['state'] = new XoopsFormHidden('state['.$id.']', 'edit' );
	    } else {
	    	$ele['state'] = new XoopsFormHidden('state['.$id.']', 'new' );
	    }
	    
	    $ele['name'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_NAME'):''), $id.'[name]', 35, 128, $object->getVar('name') );
	    $ele['name']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_NAME'):''));
	    $ele['alias'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_ALIAS'):''), $id.'[alias]', 35, 128, $object->getVar('alias') );
	    $ele['alias']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_ALIAS'):''));
	    $ele['code'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_CODE'):''), $id.'[code]', 5, 3, $object->getVar('code') );
	    $ele['code']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_CODE'):''));
	    $ele['left'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_LEFT'):''), $id.'[left]', 5, 2, $object->getVar('left') );
	    $ele['left']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_LEFT'):''));
	    $ele['right'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_RIGHT'):''), $id.'[right]', 5, 2, $object->getVar('right') );
	    $ele['right']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_RIGHT'):''));
	    $ele['decimals'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_DECIMALS'):''), $id.'[decimals]', 15, 15, $object->getVar('decimals') );
	    $ele['decimals']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_DECIMALS'):''));
	    $ele['rate'] = new XoopsFormText(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_RATE'):''), $id.'[rate]', 20, 19, $object->getVar('rate') );
	    $ele['rate']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_RATE'):''));
	    $ele['default'] = new XoopsFormRadioYN(($titles==true?constant('_FRM_VOD_FORM_CURRENCY_DEFAULT'):''), $id.'[default]', $object->getVar('default') );
	    $ele['default']->setDescription(($titles==true?constant('_FRM_VOD_FORM_DESC_CURRENCY_DEFAULT'):''));
	   
	    $ele['op'] = new XoopsFormHidden('op', 'currency' );
	    $ele['fct'] = new XoopsFormHidden('fct', 'save' );
	    $ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
	    if ($as_array==true) {
	    	return $ele;
	    }
		if (!$object->isNew()) {
			$form = new XoopsThemeForm(constant('_FRM_VOD_EDIT_CURRENCY'), 'currency', $_SERVER['PHP_SELF'], 'post', true);
		} else {
			$form = new XoopsThemeForm(constant('_FRM_VOD_NEW_CURRENCY'), 'currency', $_SERVER['PHP_SELF'], 'post', true);
		}
		$form->setExtra('enctype="multipart/form-data"');
		foreach($ele as $key => $element) {
			if (in_array($key, $_required)) {
				$form->addElement($ele[$key], true);
			} else {
				$form->addElement($ele[$key], false);
			}
		}
		return $form->render();
	}
	
	function vod_category_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('category', 'vod');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'vod');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_ISNEW_CATEGORY, 'category', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_EDIT_CATEGORY, 'category', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('vid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'category');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		$ele['parent'] = new VodFormSelectCategory(($as_array==false?_FRM_VOD_FORM_CATEGORY_PARENT:''), $id.'[parent]', $object->getVar('parent'), 1, false, $object->getVar('cid'));
		$ele['parent']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_PARENT_DESC:''));
		$ele['prefix'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_CATEGORY_PREFIX:''), $id.'[catno]', ($as_array==false?16:8),32, $object->getVar('prefix'));
		$ele['prefix']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_PREFIX_DESC:''));
		$ele['name'] = new XoopsFormText(($as_array==false?_FRM_VOD_FORM_CATEGORY_NAME:''), $id.'[name]', ($as_array==false?55:21),128, $object->getVar('name'));
		$ele['name']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_NAME_DESC:''));
		$ele['summary'] = new XoopsFormTextArea(($as_array==false?_FRM_VOD_FORM_CATEGORY_SUMMARY:''), $id.'[summary]', $object->getVar('summary'), ($as_array==false?9:4), ($as_array==false?55:25));
		$ele['summary']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_SUMMARY_DESC:''));
		$description_configs = array();
		$description_configs['name'] = $id.'[description]';
		$description_configs['value'] = $object->getVar('decription');
		$description_configs['rows'] = 35;
		$description_configs['cols'] = 60;
		$description_configs['width'] = "100%";
		$description_configs['height'] = "400px";
		$ele['description'] = new XoopsFormEditor(_FRM_VOD_FORM_CATEGORY_DESCRIPTION, $GLOBALS['vodModuleConfig']['editor'], $description_configs);
		$ele['description']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_DESCRIPTION_DESC:''));
		$ele['avata'] = new XoopsFormFile(($as_array==false?_FRM_VOD_FORM_CATEGORY_UPLOAD_AVATA:''), 'avata', $GLOBALS['vodModuleConfig']['filesize_upload']);
		$ele['avata']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_UPLOAD_AVATA_DESC:''));
		if (strlen($object->getVar('avata'))>0&&file_exists($GLOBALS['xoops']->path($object->getVar('path').$object->getVar('avata')))) {
			$ele['avata_preview'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_AVATA:''), '<img src="'.$object->getImage('avata').'" width="340px" />' );
			$ele['avata_preview']->setDescription(($as_array==false?_FRM_VOD_FORM_CATEGORY_AVATA_DESC:''));
		}

		if ($object->getVar('purchases')>0) {
			$ele['purchases'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_PURCHASES:''), $object->getVar('purchases'));
		}
		if ($object->getVar('views')>0) {
			$ele['views'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_VIEWS:''), $object->getVar('views'));
		}
		if ($object->getVar('hits')>0) {
			$ele['hits'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_HITS:''), $object->getVar('hits'));
		}
		if ($object->getVar('earned_usd')>0) {
			$ele['earned_usd'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_EARNED_USD:''), '$ ' . number_format($object->getVar('earned_usd'),2). 'USD');
		}
		if ($object->getVar('earned_aud')>0) { 
			$ele['earned_aud'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_EARNED_AUD:''), '$ ' . number_format($object->getVar('earned_aud'),2). 'AUD');
		}
		if ($object->getVar('discounts_usd')>0) {
			$ele['discounts_usd'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_DISCOUNTS_USD:''), '$ ' . number_format($object->getVar('discounts_usd'),2). 'USD');
		}
		if ($object->getVar('discounts_aud')>0) {
			$ele['discounts_aud'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_DISCOUNTS_AUD:''), '$ ' . number_format($object->getVar('discounts_aud'),2). 'AUD');
		}
		if ($object->getVar('purchased')>0) {
			$ele['purchased'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_PURCHASED:''), date(_DATESTRING, $object->getVar('purchased')));
		}
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_VOD_FORM_CATEGORY_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}

		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name', 'id', 'source');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
	
	function vod_sessions_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('sessions', 'vod');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'vod');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_ISNEW_SESSIONS, 'sessions', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_EDIT_SESSIONS, 'sessions', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('sessid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'sessions');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name', 'id', 'source');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
	
	function vod_cart_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('cart', 'vod');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'vod');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_ISNEW_CART, 'cart', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_VOD_FORM_EDIT_CART, 'cart', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('cid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'cart');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name', 'id', 'source');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
?>