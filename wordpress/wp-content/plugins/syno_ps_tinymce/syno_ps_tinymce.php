<?php
/*
Plugin Name: Photo Station Tinymce Plugin
Plugin URI: http://www.synology.com
Description: Synology Photo Station Tinymce plugin
Author: Synology Inc.
Author URI: http://www.synology.com
Version: 1.0.6
Text Domain: syno_ps_tinymce
*/

class syno_ps_tinymce {
	function __construct() {
		$this->basename = plugin_basename(__FILE__);
		$this->folder = dirname($this->basename);

		add_action('init', array($this, 'load_text'));
		add_action('admin_init', array($this, 'admin_init'));
		add_action('wp_logout', array($this, 'disable_setting_check'));
		add_action('wp_login', array($this, 'disable_setting_check'));
		register_activation_hook(__FILE__, array(&$this, 'activate'));
		register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
	}

	function load_text() {
		load_plugin_textdomain('syno_ps_tinymce', false, dirname( plugin_basename( __FILE__ ) ) .'/languages');
	}

	function disable_setting_check() {
		if (!empty(session_id())) {
			session_destroy();
		}
	}

	function admin_init() {
		if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
			add_filter('mce_buttons', array( $this, 'filter_mce_button'));
		}
		add_filter('mce_external_plugins', array($this, 'filter_mce_plugin'));

		if (!session_id()) {
			session_start();
		}

		if (current_user_can('edit_plugins')) {
			$_SESSION['syno_ps_tinymce_setting_skip_verify'] = true;
		} else {
			$_SESSION['syno_ps_tinymce_setting_skip_verify'] = false;
		}
	}

	function filter_mce_button( $buttons ) {
		array_push( $buttons, '|', 'syno_ps_tinymce' );
		return $buttons;
	}

	function filter_mce_plugin( $plugins ) {
		$file = 'plugin.min.js';
		$plugins['syno_ps_tinymce'] = plugin_dir_url( __FILE__ ) . $file;
		return $plugins;
	}

	function activate() {
		global $wp_version;

		if (!isset($wp_version)) {
			$wp_version = floatval(get_bloginfo('version'));
		}

		if ( ! version_compare( $wp_version, '3.0', '>=') ) {
			if ( function_exists('deactivate_plugins') )
				deactivate_plugins(__FILE__);
			die(sprintf( __('<strong>Photo Station Tinymce Plugin: </strong>This plug-in requires version %s or later.'), '3.0'));
		}
	}

	function deactivate(){
		delete_option('frmsvr_last_folder');
	}
}
new syno_ps_tinymce;

?>
