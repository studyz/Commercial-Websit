<?php
/**
 * Plugin Name: Digitalworld Toolkit
 * Plugin URI:  http://kutethemes.net
 * Description: Digitalworld toolkit for Digitalworld theme. Currently supports the following theme functionality: shortcodes, CPT.
 * Version:     1.0.3
 * Author:      Kutethemes Team
 * Author URI:  http://www.taodoteam.com
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: digitalworld-toolkit
 */

// Define url to this plugin file.
define( 'DIGITALWORLD_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );

// Define path to this plugin file.
define( 'DIGITALWORLD_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );

// Include function plugins if not include.
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.3
 */
function digitalworld_toolkit_load_textdomain() {
    load_plugin_textdomain( 'digitalworld-toolkit', false, DIGITALWORLD_TOOLKIT_PATH . '/languages' );
}
add_action( 'init', 'digitalworld_toolkit_load_textdomain' );


// Load basic initialization
include_once( DIGITALWORLD_TOOLKIT_PATH . '/includes/init.php' );

// Run shortcode in widget text
add_filter( 'widget_text', 'do_shortcode' );

// Register custom shortcodes
include_once( DIGITALWORLD_TOOLKIT_PATH . '/includes/shortcode.php' );

// Register custom post types
include_once( DIGITALWORLD_TOOLKIT_PATH . '/includes/post-types.php' );
