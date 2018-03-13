<?php
add_action( 'add_meta_boxes', 'nb_add_custom_metabox' );
add_action( 'save_post', 'nb_save_postdata' );

function nb_add_custom_metabox() {
    add_meta_box( 
        'pd_nb_metabox',
        __( 'Info bar', 'info-bar' ),
        'nb_generate_innermeta',
        'post',
		'side',
		'low'
    );
    add_meta_box(
        'pd_nb_metabox',
        __( 'Info bar', 'info-bar' ), 
        'nb_generate_innermeta',
        'page',
		'side',
		'low'
    );
}

function nb_generate_innermeta() {
  global $post;
  wp_nonce_field( plugin_basename( __FILE__ ), 'pd_nb_metanonce' );
  $value = get_post_meta($post->ID, 'pd_nb_metastatus', true);
  echo '<select name="pd_nb_metastatus">
	<option value="true" ' . selected($value,'true',false) . '>' . __('Enabled','info-bar') . '</option>
	<option value="false" ' . selected($value,'false',false) . '>' . __('Disabled','info-bar') . '</option>
	</select>
	';
}

function nb_save_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ( !wp_verify_nonce( $_POST['pd_nb_metanonce'], plugin_basename( __FILE__ ) ) )
      return;
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }
  $data = $_POST['pd_nb_metastatus'];
  update_post_meta($post_id,'pd_nb_metastatus',$data);
}
?>