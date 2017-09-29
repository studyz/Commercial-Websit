<?php

if( !class_exists('Digitalworld_Mailchimp') ){
	class Digitalworld_Mailchimp{
		public $plugin_uri;
		private $options;

		public function __construct() {
			$this->options = get_option( 'digitalworld_mailchimp_option' );

			$this->plugin_uri = trailingslashit(plugin_dir_url(__FILE__) );
			add_action( 'wp_enqueue_scripts',  array(&$this,'scripts') );

			add_action( 'wp_ajax_submit_mailchimp_via_ajax', array($this,'submit_mailchimp_via_ajax') );
			add_action( 'wp_ajax_nopriv_submit_mailchimp_via_ajax', array($this,'submit_mailchimp_via_ajax') );

			if ( !$this->options['api_key'] ) {
	            add_action( 'admin_notices', array( $this, 'admin_notice' ));
	        }
		}


		function admin_notice() {
        ?>
        <div class="updated">
            <p><?php 
                printf( 
                    __('Please enter Mail Chimp API Key in <a href="%s">here</a>', 'digitalworld-toolkit' ),
                    admin_url( 'admin.php?page=mailchimp-settings')
                ); 
            ?></p>
        </div>
        <?php
	    }

		public function scripts(){
			wp_enqueue_script( 'digitalworld-mailchimp', DIGITALWORLD_TOOLKIT_URL. '/includes/classes/mailchimp/js/mailchimp.min.js', array( 'jquery' ), '1.0', true );
			wp_localize_script( 'digitalworld-mailchimp', 'digitalworld_mailchimp', array(
				'ajaxurl'  => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce( 'digitalworld_mailchimp' ),
	        ) );
		}

		public function submit_mailchimp_via_ajax() {
			if ( !class_exists( 'MCAPI' ) ) {
				include_once( 'MCAPI.class.php' );
			}
			$response = array(
				'html'    => '',
				'message' => '',
				'success' => 'no'
			);

			$api_key = "";
			$list_id = "";
			$success_message = esc_html__('Your email added...','digitalworld-toolkit');

			if( $this->options){
				$api_key = $this->options['api_key'];
				$list_id = $this->options['list'];
				if( $this->options['success_message']!=""){
					$success_message = $this->options['success_message'];
				}
				
			}
			
			$email = isset( $_POST['email'] ) ? $_POST['email'] : '';

			$response['message'] = esc_html__( 'Failed', 'digitalworld-toolkit' );

			$merge_vars = array();

			if ( class_exists( 'MCAPI' ) ) {
				$api = new MCAPI( $api_key );
				if ( $api->listSubscribe( $list_id, $email, $merge_vars ) === true ) {

					$response['message'] = sanitize_text_field( $success_message );
					$response['success'] = 'yes';
				}
				else {
					// Sending failed
					$response['message'] = $api->errorMessage;
				}
			}

			wp_send_json( $response );
			die();
		}
	}
}
new Digitalworld_Mailchimp();
