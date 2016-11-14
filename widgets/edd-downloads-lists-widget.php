<?php
/**
 * EDD Downloads Lists Links Widget
 *
 * @package     EDD\Downloads_Lists\Links_Widget
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

class EDD_Downloads_Lists_Links_Widget extends WP_Widget {

    /**
     * Construct
     *
     * @since    1.0
     */
    function __construct() {
        parent::__construct( false, __( 'EDD Downloads Lists links', 'edd-downloads-lists' ), array( 'description' => __( 'Adds EDD Downloads Lists links to your download sidebar.', 'edd-downloads-lists' ), ) );
    }

    /**
     * Widget
     *
     * @return   void
     * @since    1.0
     */
    function widget( $args, $instance ) {
        global $post;

        if ( ! is_singular( 'download' ) )
            return;

        // Get the title and apply filters
        $title = apply_filters( 'widget_title', $instance['title'] ? $instance['title'] : '' );
        // Start collecting the output
        $out = '';

        // Check if there is a title
        if ( ! empty($title) ) {
            // Adds the title to the output
            $out .= $args['before_title'] . $title . $args['after_title'];
        }

        // Gets the output from edd_downloads_lists_links() located at template-functions.php
        ob_start();
        edd_wl_load_wish_list_link( $post->ID );
        edd_downloads_lists_links( $post->ID );
        $links = ob_get_clean();

        apply_filters( 'edd_downloads_lists_widget_links', $links, $post );

        $out .= $links;

        // Set the widget's containers
        $output = $args['before_widget'] . $out . $args['after_widget'];

        echo $output;
    }

    /**
     * Form
     *
     * @return   void
     * @since    1.0
     */
    function form( $instance )
    {
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'edd-downloads-lists' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <?php
    }
}

/**
 * Register EDD Downloads Lists Widget
 *
 * @access   private
 * @return   void
 * @since    1.0
 */
function edd_downloads_lists_links_widget() {
    register_widget( 'EDD_Downloads_Lists_Links_Widget' );
}
add_action( 'widgets_init', 'edd_downloads_lists_links_widget', 10 );