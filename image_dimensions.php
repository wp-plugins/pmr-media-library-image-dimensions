<?php
/* 
Plugin Name: PMR | Add Image Dimensions Columns
Plugin URI: Unknown
Description: Adds width and height columns to Media Library page.
Version: 1.0
Author: Paul Riley
*/

//register the plugin activation hook:
register_activation_hook(__FILE__, 'pmr_install');

function pmr_install() {
	
	global $wp_version;
	
	if (version_compare($wp_version, '3.5', '<')){
		
		wp_die('This plugin requires WordPress version 3.5 or higher.');
		
	}//end if

}	

function pmr_hook_dimension_columns() {
	add_filter( 'manage_media_columns', 'pmr_mediaColumn' );
	add_action( 'manage_media_custom_column', 'pmr_imageWidth', 10, 2 );
	add_action( 'manage_media_custom_column', 'pmr_imageHeight', 10, 2 );
}
add_action( 'admin_init', 'pmr_hook_dimension_columns' );

function pmr_mediaColumn( $cols ) {
        $cols['pmr_width'] = "Width";
		$cols['pmr_height'] = "Height";
        return $cols;
}

function pmr_imageWidth( $column_name, $id ) {
    if ( $column_name == 'pmr_width' ) {
		$meta = wp_get_attachment_metadata($id);
        if(isset($meta['width'])) {
			echo $meta['width'] . " px";
		}
	}
}

function pmr_imageHeight( $column_name, $id ) {
	if ( $column_name == 'pmr_height' ) {
		$meta = wp_get_attachment_metadata($id);
		if(isset($meta['height'])) {
			echo $meta['height'] . " px";
		}
	}
}

add_filter( 'manage_media_sortable_columns', 'pmr_sortable_mediaColumn' );	
function pmr_sortable_mediaColumn($columns) {
	$columns['pmr_width'] = 'width';
	$columns['pmr_height'] = 'height';
	
	return $columns;
}

add_action( 'pre_get_posts', 'my_slice_orderby' );
function my_slice_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'width' == $orderby ) {
        $query->set('meta_key','pmr_width');
        $query->set('orderby','meta_value_num');
    }
}

?>