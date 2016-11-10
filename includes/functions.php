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
 * Returns a user's favorites list ID
 * list will be created if one does not exist
 *
 * @since  1.0
 * @param $list string User list, defaults: wish_list|favorite|like
 * @param $user_id integer (Optional) User id
 * @return integer|boolean List id
 */
function edd_downloads_lists_get_user_list_id( $list, $user_id = null ) {
    // If not set $user_id then try to get list from logged user or guest
    if( $user_id == null ) {
        // user is logged in, get ID of list
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();

            $user_list = get_user_meta($user_id, 'edd_downloads_list_' . $list . '_id', true);

            if ('publish' == get_post_status($user_list) || 'private' == get_post_status($user_list)) {
                $list_id = $user_list;
            }
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
                'meta_key' => 'edd_downloads_list_' . $list . '_id',
                'meta_value' => edd_wl_get_list_token()
            );

            $post = get_posts($args);
            $list_id = $post ? $post[0]->ID : '';
        }
    } else {
        $user_list = get_user_meta($user_id, 'edd_downloads_list_' . $list . '_id', true);

        if ('publish' == get_post_status($user_list) || 'private' == get_post_status($user_list)) {
            $list_id = $user_list;
        }
    }

    $list_id = isset( $list_id ) ? $list_id : false;

    return $list_id;
}

/**
 * Create a list
 * @return integer|boolean List id
 */
function edd_downloads_lists_create_list( $list ) {
    $default_title = __( 'List', 'edd-downloads-list' );
    $default_status = 'publish';

    if($list == 'wish_list') {
        $default_title = __( 'Wishes', 'edd-downloads-list' );
        $default_status = 'private';
    } else if($list == 'favorite') {
        $default_title = __( 'Favorites', 'edd-downloads-list' );
    } else if($list == 'like') {
        $default_title = __( 'Likes', 'edd-downloads-list' );
    }

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