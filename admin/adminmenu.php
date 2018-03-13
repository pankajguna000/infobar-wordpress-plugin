<?php
function nb_init_admin_menu () {
	if(nb_check_access()){
		add_menu_page(
			'Info Bar',
			'Info Bar',
			'edit_posts',
			'pd_nb_entries',
			'nb_sub_entries',
			plugins_url('infobar/css/images/admin/wp_admin_menu_icon.png')
		);
		$entriespage = add_submenu_page(
			'pd_nb_entries',
			__('Notification entries','infobar'),
			__('Notification entries','infobar'),
			'edit_posts',
			'pd_nb_entries',
			'nb_sub_entries'
			);
			add_action( 'admin_print_styles-' . $entriespage, 'nb_admin_styles' );
	}
	
	
}
?>