<?php
/**
 * Ajax
 *
 * @package     EDD\Downloads_Lists\Ajax
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Adds item(s) directly to user list via Ajax
 *
 * @since 1.0
 * @param $list string User list, defaults: wish_list|favorite|like
 * @return void
 */
function edd_downloads_lists_add_to_list() {
    if ( isset( $_POST['download_id'] ) && isset( $_POST['list'] ) ) {
        $to_add = array();

        if ( isset( $_POST['price_ids'] ) && is_array( $_POST['price_ids'] ) ) {
            foreach ( $_POST['price_ids'] as $price ) {
                $to_add[] = array( 'price_id' => $price );
            }
        }

        $return = array();

        // Get user's list ID if already exists
        $list_id = edd_downloads_lists_get_user_list_id( $_POST['list'] );

        // no list
        if ( ! $list_id ) {
            // create list if it does not exist
            $list_id = edd_downloads_lists_create_list( $_POST['list'] );

            // update list ID with token
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                // store list ID against user's profile
                update_user_meta( $user_id, 'edd_downloads_list_' . $_POST['list'] . '_id', $list_id );
            } elseif ( ! is_user_logged_in() ) {
                // create token for logged out user
                $token = edd_wl_create_token( $list_id );

                update_post_meta( $list_id, 'edd_downloads_list_' . $_POST['list'] . '_id', $token );
            }
        }

        // add each download to list
        foreach ( $to_add as $options ) {
            if( $_POST['download_id'] == $options['price_id'] ) {
                $options = array();
            }

            // item already in list, remove
            if ( edd_wl_item_in_wish_list( $_POST['download_id'], $options, $list_id ) ) {
                $position = edd_wl_get_item_position_in_list( $_POST['download_id'], $list_id, $options );

                edd_remove_from_wish_list( $position, $list_id );
                $return['removed'] = true;
                $return['removed_from'] = $list_id;
                $return['position'] = $position;
            }
            // item not in list, add it
            else {
                edd_wl_add_to_wish_list( $_POST['download_id'], $options, $list_id );

                $return['added'] = true;
                $return['added_to'] = $list_id;
            }

        }
        // ID of the list
        $return['list_id'] = $list_id;

        echo json_encode( $return );
    }
    edd_die();
}
add_action( 'wp_ajax_edd_downloads_lists_add_to_list', 'edd_downloads_lists_add_to_list' );
add_action( 'wp_ajax_nopriv_edd_downloads_lists_add_to_list', 'edd_downloads_lists_add_to_list' );
