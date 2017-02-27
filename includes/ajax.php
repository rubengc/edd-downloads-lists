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
 * @since 1.0.0
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

        $response = array();

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
                update_user_meta( $user_id, 'edd_downloads_lists_' . $_POST['list'] . '_id', $list_id );
            } elseif ( ! is_user_logged_in() ) {
                // create token for logged out user
                $token = edd_wl_create_token( $list_id );

                update_post_meta( $list_id, 'edd_downloads_lists_' . $_POST['list'] . '_id', $token );
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

                // Updates a meta with total count on this list
                edd_downloads_lists_decrease_download_list_count( $_POST['list'], 1, $_POST['download_id'] );

                $response['removed'] = true;
                $response['removed_from'] = $list_id;
                $response['position'] = $position;
            }
            // item not in list, add it
            else {
                edd_wl_add_to_wish_list( $_POST['download_id'], $options, $list_id );

                do_action( 'edd_downloads_list_add_to_list', $list_id, $_POST['download_id'], $options, $_POST['list'] );

                // Updates a meta with total count on this list
                edd_downloads_lists_increase_download_list_count( $_POST['list'], 1, $_POST['download_id'] );

                $response['added'] = true;
                $response['added_to'] = $list_id;
            }

        }
        // ID of the list
        $response['list_id'] = $list_id;

        // Updates the link label
        $list_singular = ( isset( $list_args['singular'] ) ? $list_args['singular'] : $_POST['list'] );
        $default_label = ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_singular ) );
        $label = edd_get_option( sprintf( 'edd_downloads_lists_%s_label', $_POST['list'] ), $default_label );

        if( edd_get_option( sprintf( 'edd_downloads_lists_%s_count', $_POST['list'] ), false ) ) {
            $label = edd_downloads_lists_get_download_list_count( $_POST['list'], $_POST['download_id'] );
        }

        $response['label'] = $label;

        echo json_encode( $response );
    }
    edd_die();
}
add_action( 'wp_ajax_edd_downloads_lists_add_to_list', 'edd_downloads_lists_add_to_list' );
add_action( 'wp_ajax_nopriv_edd_downloads_lists_add_to_list', 'edd_downloads_lists_add_to_list' );

/**
 * Imports EDD Favorites lists to EDD Downloads Lists
 *
 * @since 1.0.1
 * @return void
 */
function edd_downloads_lists_favorites_import() {

    $response = array();
    $response['users_updated'] = array();

    // Try to find all users with EDD Favorties meta
    $args = array(
        'meta_query' => array(
            array(
                'key'     => 'edd_favorites_list_id',
                'compare' => 'EXISTS'
            )
        )
    );

    $user_query = new WP_User_Query( $args );

    $users = $user_query->get_results();

    if ( ! empty($users) ) {
        foreach ($users as $user) {
            $old_favorite_list_id = get_user_meta( $user->ID, 'edd_favorites_list_id', true );

            // If list exists, then update EDD Downloads Lists user meta
            if( get_post( $old_favorite_list_id ) != null ) {
                update_post_meta( $user->ID, 'edd_downloads_lists_favorite_id', $old_favorite_list_id );

                $response['users_updated'][] = array(
                    'ID' => $user->ID,
                    'list_ID' => $old_favorite_list_id,
                );
            }
        }

        if( ! empty($response['users_updated']) ) {
            $response['message'] = __('All favorite list update successfully', 'edd-downloads-lists');
        } else {
            $response['message'] = __( 'Nothing to update', 'edd-downloads-lists');
        }
    } else {
        $response['message'] = __( 'No EDD Favorites lists found', 'edd-downloads-lists');
    }

    echo json_encode( $response );

    edd_die();
}
add_action( 'wp_ajax_edd_downloads_lists_favorites_import', 'edd_downloads_lists_favorites_import' );
