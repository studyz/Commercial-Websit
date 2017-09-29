<?php
/**
 * @version    1.0
 * @package    Digitalworld_Toolkit
 * @author     RedApple Team
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if( !class_exists('Digitalworld_Toolkit_Posttype')){
    class Digitalworld_Toolkit_Posttype {

        public function __construct() {
            add_action( 'init', array( &$this, 'init' ),9999 );
        }

        public static function init() {
            /*Mega menu */
            $args  = array(
                'labels'              => array(
                    'name'               => __( 'Mega Builder', 'digitalworld-toolkit' ),
                    'singular_name'      => __( 'Mega menu item', 'digitalworld-toolkit' ),
                    'add_new'            => __( 'Add new', 'digitalworld-toolkit' ),
                    'add_new_item'       => __( 'Add new menu item', 'digitalworld-toolkit' ),
                    'edit_item'          => __( 'Edit menu item', 'digitalworld-toolkit' ),
                    'new_item'           => __( 'New menu item', 'digitalworld-toolkit' ),
                    'view_item'          => __( 'View menu item', 'digitalworld-toolkit' ),
                    'search_items'       => __( 'Search menu items', 'digitalworld-toolkit' ),
                    'not_found'          => __( 'No menu items found', 'digitalworld-toolkit' ),
                    'not_found_in_trash' => __( 'No menu items found in trash', 'digitalworld-toolkit' ),
                    'parent_item_colon'  => __( 'Parent menu item:', 'digitalworld-toolkit' ),
                    'menu_name'          => __( 'Menu Builder', 'digitalworld-toolkit' ),
                ),
                'hierarchical'        => false,
                'description'         => __('Mega Menus.', 'digitalworld-toolkit'),
                'supports'            => array('title', 'editor'),
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'digitalworld',
                'menu_position'       => 40,
                'show_in_nav_menus'   => true,
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'has_archive'         => false,
                'query_var'           => true,
                'can_export'          => true,
                'rewrite'             => false,
                'capability_type'     => 'page',
                'menu_icon'           => 'dashicons-welcome-widgets-menus',
            );
            register_post_type( 'megamenu', $args);

            /* Footer */
            $args =  array(
                'labels'              => array(
                    'name'               => __( 'Footers', 'digitalworld-toolkit' ),
                    'singular_name'      => __( 'Footers', 'digitalworld-toolkit' ),
                    'add_new'            => __( 'Add New', 'digitalworld-toolkit' ),
                    'add_new_item'       => __( 'Add new footer', 'digitalworld-toolkit' ),
                    'edit_item'          => __( 'Edit footer', 'digitalworld-toolkit' ),
                    'new_item'           => __( 'New footer', 'digitalworld-toolkit' ),
                    'view_item'          => __( 'View footer', 'digitalworld-toolkit' ),
                    'search_items'       => __( 'Search template footer', 'digitalworld-toolkit' ),
                    'not_found'          => __( 'No template items found', 'digitalworld-toolkit' ),
                    'not_found_in_trash' => __( 'No template items found in trash', 'digitalworld-toolkit' ),
                    'parent_item_colon'  => __( 'Parent template item:', 'digitalworld-toolkit' ),
                    'menu_name'          => __( 'Footer Builder', 'digitalworld-toolkit' ),
                ),
                'hierarchical'        => false,
                'description'         => __('To Build Template Footer.', 'digitalworld-toolkit'),
                'supports'            => array( 'title', 'editor','page-attributes' ),
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'digitalworld',
                'menu_position'       => 40,
                'show_in_nav_menus'   => true,
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'has_archive'         => false,
                'query_var'           => true,
                'can_export'          => true,
                'rewrite'             => false,
                'capability_type'     => 'page',
            );
            register_post_type( 'footer', $args);

            /* Testimonials */
            $labels = array(
                'name'               => __( 'Testimonial', 'digitalworld-toolkit' ),
                'singular_name'      => __( 'Testimonial', 'digitalworld-toolkit'),
                'add_new'            => __( 'Add New', 'digitalworld-toolkit' ),
                'all_items'          => __( 'Testimonials', 'digitalworld-toolkit' ),
                'add_new_item'       => __( 'Add New Testimonial', 'digitalworld-toolkit' ),
                'edit_item'          => __( 'Edit Testimonial', 'digitalworld-toolkit' ),
                'new_item'           => __( 'New Testimonial', 'digitalworld-toolkit' ),
                'view_item'          => __( 'View Testimonial', 'digitalworld-toolkit' ),
                'search_items'       => __( 'Search Testimonial', 'digitalworld-toolkit' ),
                'not_found'          => __( 'No Testimonial found', 'digitalworld-toolkit' ),
                'not_found_in_trash' => __( 'No Testimonial found in Trash', 'digitalworld-toolkit' ),
                'parent_item_colon'  => __( 'Parent Testimonial', 'digitalworld-toolkit' ),
                'menu_name'          => __( 'Testimonials', 'digitalworld-toolkit' )
            );
            $args = array(
                'labels'             => $labels,
                'hierarchical'       => true,
                'show_ui'            => true,
                'show_in_menu'       => 'digitalworld',
                'show_in_nav_menus'  => false,
                'supports'           => array( 'title', 'thumbnail', 'editor' ),
                'rewrite'            => false,
                'query_var'          => false,
                'publicly_queryable' => false,
                'public'             => true,
                'menu_icon'          => 'dashicons-editor-quote',


            );
            register_post_type( 'testimonial', $args );
        }
    }

    new Digitalworld_Toolkit_Posttype();
}
