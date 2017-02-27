<?php
/**
 * Functions
 *
 * @package     EDD\Downloads_Lists\Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Returns a user's list ID
 * list will be created if one does not exist
 *
 * @since   1.0.0
 * @param   $list string        User list, defaults: wish_list|favorite|like|recommend
 * @param   $user_id integer    (Optional) User id
 * @return  integer|boolean     List id|false
 */
function edd_downloads_lists_get_user_list_id( $list, $user_id = null ) {
    // If not specified $user_id then try to get list from logged user or guest
    if( $user_id == null ) {
        // user is logged in, set $user_id using current user ID
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
        }
        // user is logged out
        elseif (!is_user_logged_in()) {

            // if user does not have token, exit
            if (!edd_wl_get_list_token())
                return null;

            // find the list ID that has the edd_downloads_list meta key and value of user's token
            $args = array(
                'post_type' => 'edd_wish_list',
                'posts_per_page' => '-1',
                'post_status' => array('publish', 'private'),
                'meta_key' => 'edd_downloads_lists_' . $list . '_id',
                'meta_value' => edd_wl_get_list_token()
            );

            $post = get_posts($args);
            $list_id = $post ? $post[0]->ID : '';
        }
    }

    if( $user_id != null ) {
        $user_list = get_user_meta($user_id, 'edd_downloads_lists_' . $list . '_id', true);

        $post = get_post($user_list);

        // If exists the user list
        if ( $post != null) {
            if( 'publish' == $post->post_status || 'private' == $post->post_status ) {
                $list_id = $user_list;
            }
        }
        // Creates the list if does not exist
        else {
            $list_id = edd_downloads_lists_create_list( $list );

            if( $list_id ) {
                update_user_meta($user_id, 'edd_downloads_lists_' . $list . '_id', $list_id);
            }
        }
    }

    $list_id = isset( $list_id ) ? $list_id : false;

    return $list_id;
}

/**
 * Gets the downloads of a specific list
 *
 * @param  string $list 	    User list, defaults: wish_list|favorite|like|recommend
 * @param  integer $user_id     (Optional) User id
 * @return array|boolean        Contents of the list|false
 * @since  1.0.0
 */
function edd_downloads_lists_get_downloads_list( $list, $user_id = null ) {
    $list_id = edd_downloads_lists_get_user_list_id( $list, $user_id );

    if( $list_id ) {
        return edd_wl_get_wish_list($list_id);
    }

    return false;
}

function edd_downloads_lists_get_download_list_count( $list, $download_id = null ) {
    if ( $download_id == null ) {
        $download_id = get_the_ID();
    }

    if ( '' == get_post_meta( $download_id, sprintf( 'edd_downloads_lists_%s_count', $list), true ) ) {
        add_post_meta( $download_id, sprintf( 'edd_downloads_lists_%s_count', $list), 0 );
    }

    $count = get_post_meta( $download_id, sprintf( 'edd_downloads_lists_%s_count', $list ), true );

    // Never let this be less than zero
    return max( $count, 0 );
}

function edd_downloads_lists_increase_download_list_count( $list, $quantity = 1, $download_id = null ) {
    if ( $download_id == null ) {
        $download_id = get_the_ID();
    }

    $quantity = absint( $quantity );
    $count    = edd_downloads_lists_get_download_list_count( $list, $download_id ) + $quantity;

    return update_post_meta( $download_id, sprintf( 'edd_downloads_lists_%s_count', $list ), $count );
}

function edd_downloads_lists_decrease_download_list_count( $list, $quantity = 1, $download_id = null ) {
    if ( $download_id == null ) {
        $download_id = get_the_ID();
    }

    $quantity = absint( $quantity );
    $count    = edd_downloads_lists_get_download_list_count( $list, $download_id ) - $quantity;

    return update_post_meta( $download_id, sprintf( 'edd_downloads_lists_%s_count', $list ), $count );
}

/**
 * Create a list
 * @param $list string List identifier
 * @return integer|boolean List id
 */
function edd_downloads_lists_create_list( $list ) {
    $list_args = edd_downloads_lists()->get_list_args( $list );

    $default_title = isset($list_args['plural']) ? $list_args['plural'] : __( 'List', 'edd-downloads-list' );
    $default_status = isset($list_args['post_status']) ? $list_args['post_status'] : 'publish';

    $args = array(
        'post_title'    => apply_filters( 'edd_downloads_lists_post_title', $default_title ),
        'post_content'  => '',
        'post_status'   => apply_filters( 'edd_downloads_lists_post_status', $default_status ),
        'post_type'     => 'edd_wish_list',
    );

    $list_id = wp_insert_post( $args );

    if ( $list_id )
        return $list_id;
    else
        return false;
}

/**
 * Is list view page
 */
function edd_downloads_lists_is_page_view() {
    foreach(edd_downloads_lists()->get_lists() as $list => $list_args) {
        $id = edd_get_option( sprintf( 'edd_downloads_lists_%s_page_view', $list ) );

        if ( $id && is_page($id) ) {
            return $list;
        }
    }

    return false;
}