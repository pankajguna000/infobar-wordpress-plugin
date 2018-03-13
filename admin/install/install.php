<?php
register_activation_hook(__FILE__,'nb_install');

function nb_install() {
	global $wpdb;
	$table_name_data = $wpdb->prefix . "nb_data";
	if( get_option( "pd_nb_db_version" ) != NB_DB_VERSION ) {
	$sqldata = "CREATE TABLE " . $table_name_data . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		timecreated datetime NOT NULL,
		name tinytext NOT NULL,
		content text NOT NULL,
		bgcolor text NULL,
		fontcolor text NULL,
		timebegin datetime DEFAULT NULL,
		timeend datetime DEFAULT NULL,
		nottype mediumint(9) NULL,
		status tinyint(1) NOT NULL,
		epochtimebegin int(12) NULL,
		epochtimeend int(12) NULL,
		PRIMARY KEY (id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	$table_name_notifications = $wpdb->prefix . "nb_nottypes";
	$sqlnotifications = "CREATE TABLE " . $table_name_notifications . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		icon text NULL,
		bgcolor text NULL,
		fontcolor text NULL,
		size text NULL,
		url text NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sqldata);
		dbDelta($sqlnotifications);
	}
}

function nb_install_example_data() {
	global $wpdb;
	$table_name_data = $wpdb->prefix . "nb_data";
	$table_name_notifications = $wpdb->prefix . "nb_nottypes";
	$nb_name = __('Notification example','infobar');
	$nb_content = __('Congratulations, you have just installed Info Bar !','infobar');
	$wpdb->insert( $table_name_data, array( 'status' => 0, 'timecreated' => current_time('mysql'), 'name' => $nb_name, 'content' => $nb_content, 'nottype' => 3 ));
}

function nb_install_example_notifications() {
	global $wpdb;
	$table_name_notifications = $wpdb->prefix . "nb_nottypes";
	$wpdb->insert( $table_name_notifications, array( 'name' => __('TIP','infobar'), 'icon' => NB_DIR . '/images/tip.png', 'bgcolor' => 'white', 'fontcolor' => 'black' ));
	$wpdb->insert( $table_name_notifications, array( 'name' => __('IMPORTANT','infobar'), 'icon' => NB_DIR . '/images/important.png', 'bgcolor' => 'darkred', 'fontcolor' => 'white' ));
	$wpdb->insert( $table_name_notifications, array( 'name' => __('ANNOUNCEMENT','infobar'), 'icon' => NB_DIR . '/images/announcement.png', 'bgcolor' => 'lightyellow', 'fontcolor' => 'black' ));
}

function nb_convert_mysql_time() {
	global $wpdb;
	$table_name_data = $wpdb->prefix . "nb_data";
	$sql = 'SELECT id,timebegin,timeend FROM ' . $table_name_data;
	$results = $wpdb->get_results($sql);
	foreach ($results as $result) {
		if(!empty($result->timebegin)) {
			$wpdb->update( $table_name_data, array( 'epochtimebegin' => (int)strtotime($result->timebegin)), array( 'id' => $result->id));
		}
		if(!empty($result->timeend)) {
			$wpdb->update( $table_name_data, array( 'epochtimeend' => (int)strtotime($result->timeend)), array( 'id' => $result->id));
		}
	}
	update_option('pd_nb_db_convertepoch','true');
}

function nb_update_db_check() {
	if( get_option( "pd_nb_db_version" ) != NB_DB_VERSION ) {
		nb_install();
		if(get_option('pd_nb_db_convertepoch','false') != 'true') {
			nb_convert_mysql_time();
			update_option('pd_nb_db_convertepoch','true');
		}
		update_option('pd_nb_db_version', NB_DB_VERSION);
	}
	if (get_option('pd_nb_db_example_data','false') != 'true') {
		nb_install_example_data();
		update_option('pd_nb_db_example_data','true');
	}
	if (get_option('pd_nb_db_example_notifications','false') != 'true') {
		nb_install_example_notifications();
		update_option('pd_nb_db_example_notifications','true');
	}
}

add_action('plugins_loaded', 'nb_update_db_check');
?>
