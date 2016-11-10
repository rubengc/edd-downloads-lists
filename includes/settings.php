<?php
/**
 * Settings
 *
 * @package     EDD\Downloads_Lists\Settings
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Add settings section
 *
 * @access      public
 * @since       1.0.0
 * @param       array $sections The existing EDD settings sections array
 * @return      array The modified EDD settings sections array
 */
function edd_downloads_lists_settings_section( $sections ) {
    $sections['edd-downloads-lists'] = __( 'EDD Downloads Lists', 'edd-downloads-lists' );
    return $sections;
}
add_filter( 'edd_settings_sections_extensions', 'edd_downloads_lists_settings_section' );

/**
 * Add settings
 *
 * @access      public
 * @since       1.0.0
 * @param       array $settings The existing EDD settings array
 * @return      array The modified EDD settings array
 */
function edd_downloads_lists_settings( $settings ) {
    $icons = apply_filters( 'edd_download_lists_icons',
        array(
            'add' 		=>  __( 'Add', 'edd-wish-lists' ),
            'bookmark' 	=>  __( 'Bookmark', 'edd-wish-lists' ),
            'gift' 		=>  __( 'Gift', 'edd-wish-lists' ),
            'heart' 	=>  __( 'Heart', 'edd-wish-lists' ),
            'star' 		=>  __( 'Star', 'edd-wish-lists' ),
            'none' 		=>  __( 'No Icon', 'edd-wish-lists' ),
        )
    );

    $edd_downloads_lists_settings = array(
        array(
            'id'    => 'edd_downloads_list_header',
            'name'  => '<strong>' . __( 'EDD Downloads Lists', 'edd-downloads-lists' ) . '</strong>',
            'desc'  => __( 'Configure EDD Downloads Lists', 'edd-downloads-lists' ),
            'type'  => 'header',
        ),
    );

    foreach( edd_downloads_lists()->get_lists() as $list => $list_args ) {
        $list_display_name = ( isset( $list_args['name'] ) ? $list_args['name'] : str_replace( '_', ' ', $list ) );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_list_%s_header', $list ),
            'name'  => '<strong>' . __( ucfirst($list_display_name), 'edd-downloads-lists' ) . '</strong>',
            'desc'  => sprintf( __( 'Configure %s', $list_display_name), 'edd-downloads-lists' ),
            'type'  => 'header',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_link', $list ),
            'name'  => sprintf( __( 'Enable %s', 'edd-downloads-lists' ), ucfirst($list_display_name) ),
            'desc'  => sprintf( __( 'Checking this option will add a link to add a download to %s', 'edd-downloads-lists' ), $list_display_name ),
            'type'  => 'checkbox',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_label', $list ),
            'name'  => __( 'Label', 'edd-downloads-lists' ),
            'desc'  => '<p class="description">' . __( 'Enter the text you\'d like to appear for adding a download to %s', 'edd-downloads-lists' ) . '</p>',
            'type'  => 'text',
            'std' => ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_display_name ) )
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_icon', $list ),
            'name' => __( 'Icon', 'edd-downloads-lists' ),
            'desc' => '<p class="description">' . sprintf( __( 'The icon to show next to the add to %s links', 'edd-wish-lists' ), $list_display_name ) . '</p>',
            'type' => 'select',
            'options' =>  $icons,
            'std' => ( isset( $list_args['icon'] ) ? $list_args['icon'] : 'add' )
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_style', $list ),
            'name' => __( 'Style', 'edd-downloads-lists' ),
            'desc' => '<p class="description">' . __( 'Display a button or a plain text link', 'edd-wish-lists' ) . '</p>',
            'type' => 'select',
            'options' =>  array(
                'plain' =>  __( 'Plain Text', 'edd-wish-lists' ),
                'button' =>  __( 'Button', 'edd-wish-lists' ),
            ),
            'std' => 'button'
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_page_view', $list ),
            'name' => __( 'View Page', 'edd-downloads-lists' ),
            'desc' => '<p class="description">' . sprintf( __( 'Select the page where users will view their %s. This page should include the [edd_downloads_lists list="%s"] shortcode', 'edd-wish-lists' ), $list_display_name, $list ) . '</p>',
            'type' => 'dropdown_pages',
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_guest', $list ),
            'name' => sprintf( __( 'Allow Guests To Create %s', 'edd-downloads-lists' ), ucfirst($list_display_name) ),
            'type' => 'select',
            'options' =>  array(
                'yes' =>  __( 'Yes', 'edd-downloads-lists' ),
                'no' =>  __( 'No', 'edd-downloads-lists' ),
            ),
            'std' => 'yes'
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_cart', $list ),
            'name'  => sprintf( __( 'Allow Add To Cart', 'edd-downloads-lists' ), ucfirst($list_display_name) ),
            'desc'  => sprintf( __( 'Checking this option will add an Add To Cart button in not purchased downloads of this list', 'edd-downloads-lists' ), $list_display_name ),
            'type'  => 'checkbox',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_share', $list ),
            'name'  => sprintf( __( 'Allow Share', 'edd-downloads-lists' ), ucfirst($list_display_name) ),
            'desc'  => sprintf( __( 'Checking this option will allow user share this list', 'edd-downloads-lists' ), $list_display_name ),
            'type'  => 'checkbox',
        );
    }

    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $edd_downloads_lists_settings = array( 'edd-downloads-lists' => $edd_downloads_lists_settings );
    }

    return array_merge( $settings, $edd_downloads_lists_settings );
}
add_filter( 'edd_settings_extensions', 'edd_downloads_lists_settings', 1 );