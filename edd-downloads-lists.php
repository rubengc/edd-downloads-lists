<?php
/**
 * Plugin Name:     EDD Downloads List
 * Plugin URI:      https://wordpress.org/plugins/edd-downloads-lists/
 * Description:     Adds custom lists to EDD Wish Lists
 * Version:         1.0.1
 * Author:          rubengc
 * Author URI:      http://rubengc.com
 * Text Domain:     edd-downloads-lists
 *
 * @package         EDD\Downloads_Lists
 * @author          rubengc
 * @copyright       Copyright (c) rubengc
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Downloads_Lists' ) ) {

    /**
     * Main EDD_Downloads_Lists class
     *
     * @since       1.0.0
     */
    class EDD_Downloads_Lists {

        /**
         * @var         EDD_Downloads_Lists $instance The one true EDD_Downloads_Lists
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true EDD_Downloads_Lists
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Downloads_Lists();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_DOWNLOADS_LISTS_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_DOWNLOADS_LISTS_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_DOWNLOADS_LISTS_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/ajax.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/functions.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/hooks.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/scripts.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/settings.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/shortcodes.php';
            require_once EDD_DOWNLOADS_LISTS_DIR . 'includes/template-functions.php';

            require_once EDD_DOWNLOADS_LISTS_DIR . 'widgets/edd-downloads-lists-links-widget.php';
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = EDD_DOWNLOADS_LISTS_DIR . '/languages/';
            $lang_dir = apply_filters( 'edd_downloads_lists_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'edd-downloads-lists' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-downloads-lists', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-downloads-lists/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-downloads-lists/ folder
                load_textdomain( 'edd-downloads-lists', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-downloads-lists/languages/ folder
                load_textdomain( 'edd-downloads-lists', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-downloads-lists', false, $lang_dir );
            }
        }

        /**
         * Lists to use
         * Default: wish_list, favorite, like, recommend
         *
         * @access      public
         * @since       1.0.0
         * @return      array
         */
        public function get_lists() {
            return apply_filters( 'edd_downloads_lists_registered_lists', array(
                'wish' => array(
                    'singular' => 'Wish',
                    'plural' => 'Wishes',
                    'post_status' => 'private',
                    'icon' => 'gift'
                ),
                'favorite' => array(
                    'singular' => 'Favorite',
                    'plural' => 'Favorites',
                    'label' => __( 'Favorite', 'edd-downloads-list' ),
                    'icon' => 'star'
                ),
                'like' => array(
                    'singular' => 'Like',
                    'plural' => 'Likes',
                    'label' => __( 'Like', 'edd-downloads-list' ),
                    'icon' => 'heart'
                ),
                'recommend' => array(
                    'singular' => 'Recommend',
                    'plural' => 'Recommendations',
                    'label' => __( 'Recommend', 'edd-downloads-list' ),
                    'icon' => 'add'
                ),
            ) );
        }

        /**
         * Returns activated lists
         *
         * @access      public
         * @since       1.0.0
         * @return      array
         */
        public function get_available_lists() {
            $lists = self::get_lists();

            foreach( $lists as $list => $list_args ) {
                if( ! edd_get_option( sprintf( 'edd_downloads_lists_%s_link', $list ), false ) ) {
                    unset($lists[$list]);
                }
            }

            return $lists;
        }

        /**
         * Get list args
         *
         * @access      public
         * @since       1.0.0
         * @param       $list string
         * @return      array|boolean
         */
        public function get_list_args( $list ) {
            $lists = self::$instance->get_lists();

            if(isset($lists[$list])) {
                return $lists[$list];
            }

            return false;
        }
    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true EDD_Downloads_Lists
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_Downloads_Lists The one true EDD_Downloads_Lists
 */
function edd_downloads_lists() {
    return EDD_Downloads_Lists::instance();
}
add_action( 'plugins_loaded', 'edd_downloads_lists' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function edd_downloads_lists_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'edd_downloads_lists_activation' );
