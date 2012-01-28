/**
 * Function for lodging videos with content and block. 
 */

function vod_dojson_videos(xoops_url, dirname, resolve, preview) {
	var params = new Array();
 	$.getJSON(xoops_url+"/modules/"+dirname+"/dojson_videos.php?resolve="+resolve+"&preview="+preview, params, voddisplayvideos);
}

