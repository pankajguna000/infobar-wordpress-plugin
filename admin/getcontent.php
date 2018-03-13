<?php
function nb_get_content() {
	$content = '';
	global $wpdb;
	$table_name = $wpdb->prefix . "nb_data";
	$table_notifications = $wpdb->prefix . "nb_nottypes";
	$phptime = nb_get_tzepoch_time(time());
	if (get_option('pd_nb_debug','false') == 'true') {
		$content .= '<!-- PHPTIMEWITHOFFSET: ' . $phptime . ' -->' . chr(13);
	}
	$sql = "SELECT name,epochtimebegin,epochtimeend,timebegin,timeend,content,bgcolor,fontcolor,nottype FROM " . $table_name . " WHERE ('" . $phptime ."'  >= epochtimebegin AND '" . $phptime . "' <= epochtimeend AND status = 1) OR (epochtimebegin IS NULL AND status = 1) ORDER BY epochtimeend";
	if (get_option('pd_nb_debug','false') == 'true') {
		$content .= '<!-- SQL: ' . $sql . ' -->' . chr(13);
	}
        $myrows = $wpdb->get_results( $sql );
        if (count($myrows) != 0) {
        	if (get_option('pd_nb_debug','false') == 'true') {
				$content .= '<!-- FOUND: ' . count($myrows) . ' -->' . chr(13);
				$content .= '<!-- EPOCHTIME(): ' . time() . ' -->' . chr(13);
				$content .= '<!-- EPOCH-NBGETTZTIME(time()): ' . nb_get_tzepoch_time(time()) . ' -->' . chr(13);
				$content .= '<!-- NBGETTIME(time()): ' . nb_get_time(time()) . ' -->' . chr(13);
				$content .= '<!-- NBGETTZTIME(time()): ' . nb_get_tztime(time()) . ' -->' . chr(13);
				$content .= '<!-- EPOCHTIMEBEGIN: ' . $myrows->epochtimebegin . ' -->' . chr(13);
				$content .= '<!-- EPOCHTIMEEND: ' . $myrows->epochtimeend . ' -->' . chr(13);
				$content .= '<!-- PHPTIME: ' . date('y-m-d H:i:s e',time()) . ' -->' . chr(13);
				$content .= '<!-- PHPTIMEZONE: ' . date_default_timezone_get() . ' -->' .chr(13);
				$content .= '<!-- WPTIMEOFFSET: ' .get_option('gmt_offset').' -->' .chr(13);
				$content .= '<!-- WPTIMEZONE: ' . get_option('timezone_string') . ' -->' .chr(13);
				$content .= '<!-- WPTIMEFORMAT: ' . get_option('time_format') . ' -->' .chr(13);
				$content .= '<!-- WPDATEFORMAT: ' . get_option('date_format') . ' -->' .chr(13);
				$content .= '<!-- COOKIEHASH: ' . md5('cookieHash') . ' -->' .chr(13);
				$content .= '<!-- NBDIR: ' . NB_DIR . ' -->' .chr(13);
			}
			// Create messages
			$initialbgcolor = '';
			$initialfontcolor = '';
			$messages = '"text": [';
			$annmessages = '"text": [';
			$annfontsize = '"fontSize": [';
			$backgroundcolor ='"backgroundColor": [';
			$fontcolor = '"fontColor": [';
			$afontcolor = '"aFontColor": [';
			$annurl = '"url": [';
			$annimages = '"image": [';
			$annnames = '"name": [';
	        foreach ($myrows as $myrows) {
				if (get_option('pd_nb_debug','false') == 'true') {
					$content .= '<!-- FOUND: ' . $myrows->name . ' -->' . chr(13);
					$content .= '<!-- EPOCHTIME(): ' . time() . ' -->' . chr(13);
					$content .= '<!-- NBGETTIME(time()): ' . nb_get_time(time()) . ' -->' . chr(13);
					$content .= '<!-- NBGETTZTIME(time()): ' . nb_get_tztime(time()) . ' -->' . chr(13);
					$content .= '<!-- EPOCH-NBGETTZTIME(time()): ' . strtotime(nb_get_tztime(time())) . ' -->' . chr(13);
					$content .= '<!-- EPOCHTIMEBEGIN: ' . $myrows->epochtimebegin . ' -->' . chr(13);
					$content .= '<!-- EPOCHTIMEEND: ' . $myrows->epochtimeend . ' -->' . chr(13);
					$content .= '<!-- PHPTIME: ' . date('y-m-d H:i:s e',time()) . ' -->' . chr(13);
					$content .= '<!-- PHPTIMEZONE: ' . date_default_timezone_get() . ' -->' .chr(13);
					$content .= '<!-- WPTIMEOFFSET: ' .get_option('gmt_offset').' -->' .chr(13);
					$content .= '<!-- WPTIMEZONE: ' . get_option('timezone_string') . ' -->' .chr(13);
					$content .= '<!-- WPTIMEFORMAT: ' . get_option('time_format') . ' -->' .chr(13);
					$content .= '<!-- WPDATEFORMAT: ' . get_option('date_format') . ' -->' .chr(13);
					$content .= '<!-- COOKIEHASH: ' . md5('cookieHash') . ' -->' .chr(13);
					$content .= '<!-- NBDIR: ' . NB_DIR . ' -->' .chr(13);
				}
		        $messages .= '"' . str_replace('"','\'',stripslashes($myrows->content)) . '",';
			if (empty($myrows->bgcolor)) {
				if(empty($myrows->nottype)) {
					$rawbgcolor = get_option('pd_nb_barcolor','darkred');
					$backgroundcolor .= '"' . $rawbgcolor . '",';
				} else {
					$rawbgcolor = $wpdb->get_var( "SELECT bgcolor FROM " . $table_notifications . " WHERE id = " . $myrows->nottype );
					$backgroundcolor .= '"' . $rawbgcolor . '",';
				};
			} else {
				$rawbgcolor = $myrows->bgcolor;
				$backgroundcolor .= '"' . $rawbgcolor . '",';
			};
			// check for initial background color, so no swapping occurs when first building up the bar
			if (empty($initialbgcolor)) {
				$initialbgcolor = $rawbgcolor;
			};
			if (empty($myrows->fontcolor)) {
				if(empty($myrows->nottype)) {
					$rawfontcolor = get_option('pd_nb_fontcolor','white');
					$fontcolor .= '"' . $rawfontcolor . '",';
					$afontcolor .= '"' . $rawfontcolor . '",';
				} else {
					$rawfontcolor = $wpdb->get_var( "SELECT fontcolor FROM " . $table_notifications . " WHERE id = " . $myrows->nottype );
                                        $fontcolor .= '"' . $rawfontcolor . '",';
					$afontcolor .= '"' . $rawfontcolor . '",';
				};
			} else {
				$rawfontcolor = $myrows->fontcolor;
				$fontcolor .= '"' . $rawfontcolor .'",';
				$afontcolor .= '"' . $rawfontcolor .'",';
			};
			// check for initial fontcolor, so no swapping occurs when first building up the bar
			if (empty($initialfontcolor)) {
				$initialfontcolor = $rawfontcolor;
			};
			$tmpnottext = $wpdb->get_var( "SELECT name FROM " . $table_notifications . " WHERE id = " . $myrows->nottype );
			if (!empty($tmpnottext)) {
				$annmessages .= '"' . get_option('pd_nb_bartext', 'infobar') . '",';
			} else {
				$annmessages .= '"' . str_replace('"','\'',stripslashes($tmpnottext)) . '",';
			}
			$tmpnotsize = $wpdb->get_var( "SELECT size FROM " . $table_notifications . " WHERE id = " . $myrows->nottype );
			if (empty($tmpnotsize)) {
				$annfontsize .= '"' . get_option('pd_nb_notsize','16') . 'pt",';
			} else {
				$annfontsize .= '"' . $tmpnotsize . 'pt",';
			}
			if (empty($myrows->nottype)) {
				$annurl .= '"",';
				$annimages .= '"",';
				$annnames .= '"' . get_option('pd_nb_bartext') . '",';
			} else {
				$tmpnoticon = $wpdb->get_results( "SELECT icon,name,url FROM " . $table_notifications . " WHERE id = " . $myrows->nottype );
				foreach ($tmpnoticon as $tmpnoticon) {
					$annimages .= '"' . $tmpnoticon->icon . '",';
					$annnames .= '"' . $tmpnoticon->name . '",';
					$annurl .= '"' . $tmpnoticon->url . '",';
					}
			}
	        }
        $messages = substr($messages, 0, -1);
		$backgroundcolor = substr($backgroundcolor, 0, -1);
		$fontcolor = substr($fontcolor, 0, -1);
		$annmessages = substr($annmessages, 0, -1);
		$annfontsize = substr($annfontsize, 0, -1); 
		$annimages = substr($annimages, 0, -1);
		$annurl = substr($annurl, 0, -1);
		$annnames = substr($annnames,0 ,-1);
        $content .= '<script type="text/javascript">';
        $content .= 'jQuery(function(){';
        $content .= 'jQuery.attentionbar({';
		$content .= '"positioning" : "' . get_option('pd_nb_bartype','fixed') . '",';
        $content .= '"display" : "' . get_option('pd_nb_displaytype','delayed') . '",';
        $content .= '"displayDelay" : ' . get_option('pd_nb_displaydelay','2000') .',';
        $content .= '"speed" : 500,';
	$content .= '"messagesScrollSpeed" : ' . get_option('pd_nb_displayspeed','50') . ',';
        $content .= '"height" : ' . get_option('pd_nb_barheight','30') . ',';
        $content .= '"collapsedButtonHeight" : ' . get_option('pd_nb_buttonheight','30') . ',';
        $content .= '"messagesFadeDelay" : 1500,';
        $content .= '"easing" : "' . get_option('pd_nb_easingtype','swing') . '",';
		$content .= '"fontSize" : "' . get_option('pd_nb_fontsize','10') . 'pt",';
		$content .= '"fontColor" : "' . $initialfontcolor . '",';
        $content .= '"aFontColor" : "' . $initialfontcolor . '",';
		$content .= '"aFontSize" : "' . get_option('pd_nb_fontsize','10') . 'pt",';
		$content .= '"backgroundColor" : "' . $initialbgcolor . '", ';
        $content .= '"positionClose" : "' . get_option('pd_nb_buttonposition','left') . '", ';
        $content .= '"enableCookie" : ' . get_option('pd_nb_cookie','false') . ', ';
	if (get_option('pd_nb_cookiehash','true') == 'true') {
		$content .= '"cookieHash" : "' . md5($messages) . '", ';
	}
		$content .= '"cookieExpire" : ' . get_option('pd_nb_cookieexpire','1') . ', ';
        $content .= '"messagesDelay" : 5000,';
        $content .= '"buttonTheme" : "long-arrow",';
		$content .= '"announcementClass" : "announcement",';
		$content .= '"messages": {';
		$content .= $backgroundcolor;
		$content .= '],';
		$content .= $fontcolor;
		$content .= '],';
        $content .= $afontcolor;
		$content .= '],';
		$content .= $messages;
        $content .= ']';
		$content .= '},';
        $content .= '"announcement" : {';
        $content .= '"icon": [{';
        $content .= $annnames;
		$content .= '],';
		$content .= $annurl;
		$content .= '],';
		$content .= $annimages;
		$content .= ']';
        $content .= '}],';
		$content .= $annmessages;
		$content .= '],';
        $content .= '"fontColor" : "' . $initialfontcolor . '",';
        $content .= $annfontsize;
		$content .= ']}';
        $content .= '});';
        $content .= '});';
        $content .= '</script>' . chr(13);
        } else {
		if (get_option('pd_nb_debug','false') == 'true') {
			$sql = "SELECT * from  " . $table_name;
			$myrows = $wpdb->get_results($sql);
			$content .= '<!-- NO RESULTS FOUND DEBUGING -->' .chr(13);
			foreach ($myrows as $myrows) {
				$content .= '<!-- ID: ' . $myrows->id . ' -->' . chr(13);
				$content .=  '<!-- NAME: ' . $myrows->name . ' -->' . chr(13);
				$content .=  '<!-- CONTENT: ' . $myrows->content . ' -->' . chr(13);
				$content .=  '<!-- TIMEBEGIN: ' . $myrows->timebegin . ' -->' . chr(13);
				$content .=  '<!-- TIMEEND: ' . $myrows->timeend . ' -->' . chr(13);
				$content .=  '<!-- TIMECREATED: ' . $myrows->timecreated . ' -->' . chr(13);
				$content .=  '<!-- STATUS: ' . $myrows->status . ' -->' . chr(13);
			}
		}
	}
	if (get_option('pd_nb_showversion','true') == 'true' || get_option('pd_nb_debug','false') == 'true') {
		return chr(13) . '<!-- info bar: ' . NB_VERSION . ' START -->' . chr(13) . $content . '<!-- Info bar: ' . NB_VERSION . ' END -->';
	} else {
		return $content;
	}
}
?>