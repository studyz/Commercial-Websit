<?php
include_once( DIGITALWORLD_TOOLKIT_PATH . '/includes/classes/wellcome.php' );
/*MAILCHIP*/
include_once( 'classes/mailchimp/MCAPI.class.php' );
include_once( 'classes/mailchimp/mailchimp-settings.php' );
include_once( 'classes/mailchimp/mailchimp.php' );

if (!function_exists('digitalworld_toolkit_vc_param')) {
    function digitalworld_toolkit_vc_param($key = false, $value = false)
    {
        if ( !class_exists( 'Vc_Manager' ) ) {
            return;
        }
        return vc_add_shortcode_param($key, $value);
    }

    add_action('init', 'digitalworld_toolkit_vc_param');
}
add_action( 'admin_enqueue_scripts', 'digitalworld_toolkit_enqueue_scripts' );
if( !function_exists('digitalworld_toolkit_enqueue_scripts')){
    function  digitalworld_toolkit_enqueue_scripts(){
        wp_enqueue_style( 'type-admin', DIGITALWORLD_TOOLKIT_URL. '/assets/css/admin-redux.css' );
    }
}
