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
            'id'    => 'edd_downloads_lists_header',
            'name'  => '<strong>' . __( 'EDD Downloads Lists', 'edd-downloads-lists' ) . '</strong>',
            'desc'  => __( 'Configure EDD Downloads Lists', 'edd-downloads-lists' ),
            'type'  => 'header',
        ),
    );

    foreach( edd_downloads_lists()->get_lists() as $list => $list_args ) {
        $list_singular = ( isset( $list_args['singular'] ) ? $list_args['singular'] : str_replace( '_', ' ', $list ) );
        $list_plural = ( isset( $list_args['plural'] ) ? $list_args['plural'] : str_replace( '_', ' ', $list ) );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_header', $list ),
            'name'  => '<strong>' . __( $list_singular, 'edd-downloads-lists' ) . '</strong>',
            'desc'  => sprintf( __( 'Configure %s', $list_plural), 'edd-downloads-lists' ),
            'type'  => 'header',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_link', $list ),
            'name'  => sprintf( __( 'Enable %s', 'edd-downloads-lists' ), $list_plural ),
            'desc'  => sprintf( __( 'Checking this option will add a link to add a download to %s', 'edd-downloads-lists' ), $list_singular ),
            'type'  => 'checkbox',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_label', $list ),
            'name'  => __( 'Label', 'edd-downloads-lists' ),
            'desc'  => '<p class="description">' . __( 'Enter the text you\'d like to appear for adding a download to %s', 'edd-downloads-lists' ) . '</p>',
            'type'  => 'text',
            'std' => ( isset( $list_args['label'] ) ? $list_args['label'] : sprintf( __( 'Add to %s', 'edd-downloads-lists' ), $list_singular ) )
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_count', $list ),
            'name'  => __( 'Count', 'edd-downloads-lists' ),
            'desc'  => '<p class="description">' . __( 'Checking this option will show a count of times that this download has been added to this type of list instead of the label', 'edd-downloads-lists' ) . '</p>',
            'type'  => 'checkbox',
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_icon', $list ),
            'name' => __( 'Icon', 'edd-downloads-lists' ),
            'desc' => '<p class="description">' . sprintf( __( 'The icon to show next to the add to %s links', 'edd-wish-lists' ), $list_plural ) . '</p>',
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
            'desc' => '<p class="description">' . sprintf( __( 'Select the page where users will view their %s. This page should include the [edd_downloads_lists list="%s"] shortcode', 'edd-wish-lists' ), $list_singular, $list ) . '</p>',
            'type' => 'dropdown_pages',
        );

        $edd_downloads_lists_settings[] = array(
            'id' => sprintf( 'edd_downloads_lists_%s_guest', $list ),
            'name' => sprintf( __( 'Allow Guests To Create %s', 'edd-downloads-lists' ), $list_singular ),
            'type' => 'select',
            'options' =>  array(
                'yes' =>  __( 'Yes', 'edd-downloads-lists' ),
                'no' =>  __( 'No', 'edd-downloads-lists' ),
            ),
            'std' => 'yes'
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_cart', $list ),
            'name'  => __( 'Allow Add To Cart', 'edd-downloads-lists' ),
            'desc'  => __( 'Checking this option will add an Add To Cart button in not purchased downloads of this list', 'edd-downloads-lists' ),
            'type'  => 'checkbox',
        );

        $edd_downloads_lists_settings[] = array(
            'id'    => sprintf( 'edd_downloads_lists_%s_share', $list ),
            'name'  => __( 'Allow Share', 'edd-downloads-lists' ),
            'desc'  => __( 'Checking this option will allow user share this list', 'edd-downloads-lists' ),
            'type'  => 'checkbox',
        );

        if($list == 'favorite') {
            $edd_downloads_lists_settings[] = array(
                'id' => sprintf('edd_downloads_lists_%s_button', $list),
                'name' => __('Import from EDD Favorites', 'edd-downloads-lists'),
                'desc' => __('<p class="description">If you want to move EDD Favorites lists to EDD Downloads Lists click button bellow to update user favorite lists (EDD Favorites data not will lost)</p>', 'edd-downloads-lists') .
                    '<button type="button" id="edd_downloads_lists_favorites_import_button" class="button">' . __( 'Import from EDD Favorites', 'edd-downloads-lists') . '</button>' .
                    '<span id="edd_downloads_lists_favorites_import_button_spinner" class="spinner" style="float: none;"></span>' .
                    '<div id="edd_downloads_lists_favorites_import_response"></div>'
                    ,
                'type' => 'descriptive_text',
            );
        }
    }

    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $edd_downloads_lists_settings = array( 'edd-downloads-lists' => $edd_downloads_lists_settings );
    }

    return array_merge( $settings, $edd_downloads_lists_settings );
}
add_filter( 'edd_settings_extensions', 'edd_downloads_lists_settings', 1 );