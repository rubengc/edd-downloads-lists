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
 * Helper function for theme developers to show a desired add to list link
 *
 * Note: This function does not detects if button is enabled, to check it manually use:
 * edd_get_option( sprintf( 'edd_downloads_lists_%s_link', $list ), false )
 *
 * @param $list string User list, defaults: wish_list|favorite|like|recommend
 * @param $download_id integer (Optional) The download ID
 */
function edd_downloads_lists_link( $list, $download_id = null ) {
    if ( $download_id == null ) {
        $download_id = get_the_ID();
    }

    if( ! is_user_logged_in() && edd_get_option( sprintf( 'edd_downloads_lists_%s_guest', $list ), 'yes' ) == 'no' ) {
        return;
    }

    $list_args = edd_downloads_lists()->get_list_args( $list );

    if( $list_args ) {
        $list_singular = ( isset( $list_args['singular'] ) ? $list_args['singular'] : $list );
        $default_label = ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_singular ) );

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
        $classes[] = 'list-' . $list;
        $classes[] = 'edd-downloads-lists-add';

        $list_id = edd_downloads_lists_get_user_list_id( $list );

        // Adds the listed CSS class if the list exists
        if ( $list_id && edd_wl_item_in_wish_list( $download_id, null, $list_id ) ) {
            $classes[] = 'listed';
        }

        $label = edd_get_option( sprintf( 'edd_downloads_lists_%s_label', $list ), $default_label );

        if( edd_get_option( sprintf( 'edd_downloads_lists_%s_count', $list ), false ) ) {
            // Adds an extra space because edd_wl_wish_list_link() checks if this variable and if this is '0' or 0 does not shows it
            $label = edd_downloads_lists_get_download_list_count( $list, $download_id ) . ' ';
        }

        $args = array(
            'download_id'	=> $download_id,
            'link'		    => '#' . $list,
            'action'		=> 'edd_downloads_lists_add_to_list',
            'class'			=> implode( ' ', $classes ),
            'link_size'		=> apply_filters( 'edd_downloads_lists_link_size', '' ),
            'text'        	=> $label,
            'icon'			=> edd_get_option( sprintf( 'edd_downloads_lists_%s_icon', $list ), 'add' ),
            'style'       	=> edd_get_option( sprintf( 'edd_downloads_lists_%s_style', $list ), 'button' ),
        );

        if ( ! ( edd_is_checkout() && apply_filters( sprintf( 'edd_downloads_lists_%s_disable_on_checkout', $list ), true ) ) ) {
            edd_wl_wish_list_link( $args );
        }
    }
}

/**
 * Adds all lists links
 */
function edd_downloads_lists_links( $download_id = null ) {
    if ( $download_id == null ) {
        $download_id = get_the_ID();
    }

    foreach( edd_downloads_lists()->get_lists() as $list => $list_args ) {
        if( edd_get_option( sprintf( 'edd_downloads_lists_%s_link', $list ), false ) ) {

            if( ! is_user_logged_in() && edd_get_option( sprintf( 'edd_downloads_lists_%s_guest', $list ), 'yes' ) == 'no' ) {
                return;
            }

            $list_singular = ( isset( $list_args['singular'] ) ? $list_args['singular'] : $list );
            $default_label = ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_singular ) );

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

            $classes[] = 'edd-downloads-lists-add';
            $classes[] = 'list-' . $list;

            $list_id = edd_downloads_lists_get_user_list_id( $list );

            // Adds the listed CSS class if the list exists
            if ( $list_id && edd_wl_item_in_wish_list( $download_id, null, $list_id ) ) {
                $classes[] = 'listed';
            }

            $label = edd_get_option( sprintf( 'edd_downloads_lists_%s_label', $list ), $default_label );

            if( edd_get_option( sprintf( 'edd_downloads_lists_%s_count', $list ), false ) ) {
                // Adds an extra space because edd_wl_wish_list_link() checks if this variable and if this is '0' or 0 does not shows it
                $label = edd_downloads_lists_get_download_list_count( $list, $download_id ) . ' ';
            }

            $args = array(
                'download_id'	=> $download_id,
                'link'		    => '#' . $list,
                'action'		=> 'edd_downloads_lists_add_to_list',
                'class'			=> implode( ' ', $classes ),
                'link_size'		=> apply_filters( 'edd_downloads_lists_link_size', '' ),
                'text'        	=> $label,
                'icon'			=> edd_get_option( sprintf( 'edd_downloads_lists_%s_icon', $list ), 'add' ),
                'style'       	=> edd_get_option( sprintf( 'edd_downloads_lists_%s_style', $list ), 'button' ),
            );

            if ( ! ( edd_is_checkout() && apply_filters( sprintf( 'edd_downloads_lists_%s_disable_on_checkout', $list ), true ) ) ) {
                edd_wl_wish_list_link( $args );
            }
        }
    }
}
add_action( 'edd_purchase_link_top', 'edd_downloads_lists_links', 11 );

/**
 * Set the 'view' and 'edit' query var on the current list page
 *
 * @since  1.0
 */
function edd_downloads_lists_set_query_var() {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        set_query_var( 'wl_view', edd_downloads_lists_get_user_list_id( $list ) );
    }
}
add_action( 'template_redirect', 'edd_downloads_lists_set_query_var', 9 ); // runs just before edd_wl_process_form_requests() so it can pick up the correct query_var

/**
 * Hides add to cart button if is setting
 *
 * @since  1.0
 */
function edd_downloads_lists_item_purchase( $html ) {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        if( ! edd_get_option( sprintf( 'edd_downloads_lists_%s_cart', $list ), false ) ) {
            return '';
        }
    }

    return $html;
}
add_filter( 'edd_wl_item_purchase', 'edd_downloads_lists_item_purchase' );

/**
 * Hides add all to cart button if is setting
 *
 * @since  1.0
 */
function edd_downloads_lists_add_all_to_cart_link( $show ) {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        if( ! edd_get_option( sprintf( 'edd_downloads_lists_%s_cart', $list ), false ) ) {
            return false;
        }
    }

    return $show;
}
add_filter( 'edd_wl_show_add_all_to_cart_link', 'edd_downloads_lists_add_all_to_cart_link' );

/**
 * Hides share options if is setting
 *
 * @since  1.0
 */
function edd_downloads_lists_display_sharing( $show ) {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        if( ! edd_get_option( sprintf( 'edd_downloads_lists_%s_share', $list ), false ) ) {
            return false;
        }
    }

    return $show;
}
add_filter( 'edd_wl_display_sharing', 'edd_downloads_lists_display_sharing' );

/**
 * Hides edit sittings link
 *
 * @since  1.0
 */
function edd_downloads_lists_edit_settings_link( $show ) {
    if ( $list = edd_downloads_lists_is_page_view() ) {
        return 'none';
    }

    return $show;
}
add_filter( 'edd_wl_edit_settings_link_return', 'edd_downloads_lists_edit_settings_link' );