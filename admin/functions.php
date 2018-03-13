<?php

function nb_get_currentrole() {
	global $current_user;
	get_currentuserinfo();
	$user_id = intval( $current_user->ID );

	if( ! $user_id ) {
		return false;
	}
	$user = new WP_User( $user_id );
	$roles = $user->roles;
	foreach($roles as $role) {
		return strtolower($role);
	}
}

function nb_check_access() {
	global $current_user;
	get_currentuserinfo();
	$user_id = intval( $current_user->ID );

	if( ! $user_id ) {
		return false;
	}
	$user = new WP_User( $user_id );
	$roles = $user->roles;
	$access = false;
	$abroles = get_option('pd_nb_roles');
	if(empty($abroles)){
		$access = true;
	}
	foreach($roles as $role) {
		if($abroles[$role] == 'true') {
			$access = true;
		}
	}
	return $access;
}

function nb_redirect($url){
	@header('Location: ' . $url);
	exit;
}

function nb_get_roles() {
	global $wp_roles;
	$roles = $wp_roles->get_names();
	return $roles;
}

function nb_notice($messages,$options){
//	$dismissbeta = '<span style="float:right;"><a href="' . admin_url('admin.php?page=pd_sub_settings&betacheck=true&noheader=true') . '">' . __('Stop bugging me','infobar') . '</a></span>';
	//$dismissversion = '<span style="float:right;"><a href="' . admin_url('admin.php?page=pd_sub_settings&versioncheck=true&noheader=true') . '">' . __('Stop bugging me','infobar') . '</a></span>';
	if(is_array($messages)){
		foreach ($messages as $message) {
			$mescombined .= '<p>* <b>' . $message . '</b><br /></p>';
		}
		$message = $mescombined;
	} else {
		$message = '<p>' . $messages . '</p>';
	}
	switch($options['type']){
		case 'error':
			$type =  '<div class="error fade">';
			break;
		default:
			$type = '<div class="updated fade">';
			break;
	}
	$message = 'INFO-BAR: ' . $message;
	switch ($options['dismiss']) {
		case 'version':
			$notice = $type . $dismissversion . $message;
		break;
		case 'beta':
			$notice = $type . $dismissbeta . $message;
		break;
	}			
	$notice .= '</p></div>';
	//echo $notice;
}
function nb_admin_notices() {
	global $wp_version;
	if(get_option('pd_nb_installnotification') != NB_VERSION) {
		switch (NB_VERSION) {
			case '1.0':
				if (get_option('pd_nb_db_convertepoch') == 'true'){
					nb_notice( __('All your existing time schedules have been converted to epoch timing, check your schedules asap!','infobar'),array('type'=>'notice','dismiss'=>'version'));
				}
				break;
		}
	}

}

function nb_get_time($request) {
	$time = date(get_option('date_format') . ' ' . get_option('time_format'),(int)$request);
	return $time;
}

function nb_get_current_time() {
	return date('Y-m-d H:i:s',time());
}
function nb_get_tztime($request) {
	if (function_exists('date_default_timezone_set')) {
		$timezone = date_default_timezone_get();
		date_default_timezone_set(get_option('timezone_string'));
		$time = date(get_option('date_format') . ' ' . get_option('time_format'),(int)$request);
		date_default_timezone_set($timezone);
	} else {
		$time = date(get_option('date_format') . ' ' . get_option('time_format'),(int)$request);
	}
	return($time);
}

function nb_get_edit_time($request) {
	if (!empty($request)) {
		return date('Y-m-d H:i:s',(int)$request);
	}
}

function nb_get_tzepoch_time($request) {
	if (function_exists('date_default_timezone_set')) {
		$timezone = date_default_timezone_get();
		date_default_timezone_set(get_option('timezone_string'));
		$phptime = date('Y-m-d H:i:s',(int)$request);
		date_default_timezone_set($timezone);
	} else {
	$phptime = date('Y-m-d H:i:s',(int)$request);
	}
	return strtotime($phptime);
}

function nb_get_info_box() {
	/** $info = '<div class="postbox-container" style="width:20%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables ui-sortable">
				<div id="info" class="postbox">
					<div class="handlediv" title="Click to toggle">
						<br>
					</div>
					<h3 class="hndle">
						<span>' . __('Information','infobar') . '</span>
					</h3>
					<div class="inside">
					<h4>Support</h4>
					<br class="clear">
					<ul>
						<li><a href="http://wordpress.org/extend/plugins/infobar/" target="_blank">WordPress Plugin page</a></li>
						<li><a href="http://wordpress.org/tags/infobar?forum_id=10" target="_blank">WordPress Forum</a></li>
						<li><a href="http://pascal.dreissen.nl/bugreport" target="_blank">Bug report</a></li>
					</ul>
					</div>	
				</div>
			</div>
		</div>
	</div>';
	return $info;
	**/
}
?>