<?php
/**
 * Scripts
 *
 * @package     EDD\Downloads_Lists\Scripts
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * EDD Downloads Lists shortcode
 * @since  1.0
 */
function edd_downloads_lists_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
            'id' => '',
            'title' => '',
        ), $atts, 'edd_favorites' )
    );

    // Prevents list from displaying if it's private
    if ( edd_wl_is_private_list() )
        return;

    $content = edd_wl_load_template( 'view' );

    return $content;
}
add_shortcode( 'edd_downloads_lists', 'edd_downloads_lists_shortcode' );