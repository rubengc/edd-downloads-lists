<?php
/**
 * Template_Functions
 *
 * @package     EDD\Downloads_Lists\Template_Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Adds all lists links
 */
function edd_downloads_lists_links( $download_id ) {
    if ( ! $download_id ) {
        $download_id = get_the_ID();
    }

    foreach( edd_downloads_lists()->get_lists() as $list => $list_args ) {
        if( edd_get_option( sprintf( 'edd_downloads_lists_%s_link', $list ), false ) ) {
            $list_display_name = ( isset( $list_args['name'] ) ? $list_args['name'] : $list );
            $is_multiple = ( isset($list_args['multiple']) && $list_args['multiple'] );
            $default_label = ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_display_name ) );

            $classes = array();
            // assign a class to the link depending on where it's hooked to
            // this way we can control the margin needed at the top or bottom of the link
            if( has_action( 'edd_purchase_link_end', 'edd_downloads_lists_links' ) ) {
                $classes[] = 'after';
            }
            elseif( has_action( 'edd_purchase_link_top', 'edd_downloads_lists_links' ) ) {
                $classes[] = 'before';
            }

            // default classes
            $classes[] = 'edd-wl-action';

            if( $is_multiple ) {
                $classes[] = 'edd-wl-open-modal';
            } else {
                $classes[] = 'edd-downloads-lists-add';
                $classes[] = 'list-' . $list;

                $list_id = edd_downloads_lists_get_users_list_id( $list );

                // Adds the listed CSS class if the list exists
                if ( $list_id && edd_wl_item_in_wish_list( $download_id, null, $list_id ) ) {
                    $classes[] = 'listed';
                }
            }

            $args = array(
                'download_id'	=> $download_id,
                'link'		    => '#' . $list,
                'action'		=> (( $is_multiple ) ? 'edd_wl_open_modal' : 'edd_downloads_lists_add_to_list' ),
                'class'			=> implode( ' ', $classes ),
                'link_size'		=> apply_filters( 'edd_downloads_lists_link_size', '' ),
                'text'        	=> edd_get_option( sprintf( 'edd_downloads_lists_%s_label', $list ), $default_label ),
                'icon'			=> edd_get_option( sprintf( 'edd_downloads_lists_%s_icon', $list ), 'add' ),
                'style'       	=> edd_get_option( sprintf( 'edd_downloads_lists_%s_style', $list ), 'button' ),
            );

            if ( ! ( edd_is_checkout() && apply_filters( sprintf( 'edd_downloads_lists_%s_disable_on_checkout', $list ), true ) ) ) {
                edd_wl_wish_list_link($args);
            }
        }
    }
}
add_action( 'edd_purchase_link_top', 'edd_downloads_lists_links' );

/**
 * Removes standard wish list and favorite links
 */
function edd_downloads_lists_links_hooks() {
    // Removes default add to wish list link
    remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );

    // Removes default add to favorite link
    remove_action( 'edd_purchase_link_top', 'edd_favorite_load_link' );
}
add_action( 'template_redirect', 'edd_downloads_lists_links_hooks', 11 );

/**
 * Set the 'view' and 'edit' query var on the favorites page
 *
 * @since  1.0
 * @todo set query var for each user, other
 */
function edd_downloads_lists_set_query_var() {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        set_query_var( 'wl_view', edd_downloads_lists_get_users_list_id( $list ) );
    }

    /*if ( $list ) {
        set_query_var( 'wl_edit', edd_downloads_lists_get_users_list_id( $list ) );
    }*/
}
add_action( 'template_redirect', 'edd_downloads_lists_set_query_var', 9 ); // runs just before edd_wl_process_form_requests() so it can pick up the correct query_var