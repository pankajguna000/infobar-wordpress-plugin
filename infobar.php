<?php
/*
Plugin Name: InfoBar Top Notification
Plugin URI: http://www.inkthemes.com
Description: Info Bar can be used to display your most important notice on the top of your site. It's very useful if you want to bring something to the attention to your users. Info Bar is very easy to customize and manage according to your needs which bring instant visitors attention to your notice on top.
Version: 1.3
Author: inkthemes.com
*/
/*  Copyright 2013 13Plugins.com 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
require_once dirname(__FILE__) . '/admin/define.php';
require_once dirname(__FILE__) . '/admin/install/install.php';
require_once dirname(__FILE__) . '/admin/database.php';
require_once dirname(__FILE__) . '/admin/init.php';
require_once dirname(__FILE__) . '/admin/getcontent.php';
require_once dirname(__FILE__) . '/admin/widgets.php';
require_once dirname(__FILE__) . '/admin/forms.php';
require_once dirname(__FILE__) . '/admin/adminmenu.php';
require_once dirname(__FILE__) . '/admin/functions.php';

load_plugin_textdomain('infobar', false, basename(dirname(__FILE__)) . '/languages/');

function nb_add_to_head() {
	if (get_option('pd_nb_inserttype', 'header') == 'header') {
		global $post;
		if(is_single() || is_page()) {
			$exclude = get_post_meta($post->ID,'pd_nb_metastatus',true);
			if(empty($exclude) || $exclude == 'true') {
				echo nb_get_content() . chr(13) . '<!-- POSTID: ' . $exclude . ' -->';
			}
		}else{
			echo nb_get_content() . chr(13);
		}
	}
}

if ( is_admin() ) {
add_action("admin_init", "nb_admin_init");
add_action('admin_menu', 'nb_init_admin_menu');
add_action('admin_notices','nb_admin_notices');
}
add_action("plugins_loaded", "nb_widget_init");
add_action('init', 'nb_init_scripts');
add_action('wp_print_styles', 'nb_init_stylesheets');
add_action('wp_head', 'nb_add_to_head');
add_action('init', 'infobar_colorpicker_script');

function infobar_colorpicker_script() {
    wp_enqueue_script('info_colorpicker_script', plugins_url('jscolor/jscolor.js', __FILE__), array('jquery'));
	 wp_enqueue_script('jsscript', plugins_url('js/script.js', __FILE__), array('jquery'));
}
?>