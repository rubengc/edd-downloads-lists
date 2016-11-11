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
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      array $edd_settings_page The slug for the EDD settings page
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function edd_downloads_lists_admin_scripts( $hook ) {
    global $edd_settings_page;

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    $section = ( isset($_GET['section']) ? $_GET['section'] : '' );

    if( $hook == $edd_settings_page && $section == 'edd-downloads-lists' ) {
        wp_enqueue_script( 'edd-downloads-lists-admin', EDD_DOWNLOADS_LISTS_URL . '/assets/js/admin' . $suffix . '.js', array( 'jquery' ) );

        wp_localize_script( 'edd-downloads-lists-admin', 'edd_downloads_lists', array(
                'lists' => edd_downloads_lists()->get_lists(),
            )
        );
    }
}
add_action( 'admin_enqueue_scripts', 'edd_downloads_lists_admin_scripts', 100 );

/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_downloads_lists_scripts( $hook ) {
    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_script( 'edd-wl' );
    wp_enqueue_script( 'edd-wl-modal' );

    wp_enqueue_script( 'edd-downloads-lists', EDD_DOWNLOADS_LISTS_URL . '/assets/js/edd-downloads-lists' . $suffix . '.js', array( 'jquery' ) );
    wp_enqueue_style( 'edd-downloads-lists', EDD_DOWNLOADS_LISTS_URL . '/assets/css/edd-downloads-lists' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'edd_downloads_lists_scripts', 101 );