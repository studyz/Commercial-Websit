<?php
// Prevent direct access to this file
defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

/**
 * Core class.
 *
 * @package  RedApple Theme
 * @since    1.0
 */

if( !class_exists('Digitalworld_Famework')){
    class Digitalworld_Famework{
        /**
         * Define theme version.
         *
         * @var  string
         */
        const VERSION = '1.0.0';

        /**
         * Instance of the class.
         *
         * @since   1.0.0
         *
         * @var   object
         */
        protected static $instance = null;

        /**
         * Return an instance of this class.
         *
         * @since    1.0.0
         *
         * @return  object  A single instance of the class.
         */
        public static function get_instance() {

            // If the single instance hasn't been set yet, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;

        }

        public function __construct() {
            $this->includes();
        }

        public  function includes(){
            /* Wellcome */
            include_once( get_template_directory().'/famework/admin/wellcome.php' );

            /* Classes */
            include_once( get_template_directory().'/famework/includes/classes/class-tgm-plugin-activation.php' );
            include_once( get_template_directory().'/famework/includes/classes/breadcrumbs.php' );

            /*Mega menu */
            include_once( get_template_directory().'/famework/includes/megamenu/megamenu.php' );
            /*Plugin load*/
            include_once( get_template_directory().'/famework/settings/plugins-load.php' );
            /*Theme options*/
            include_once( get_template_directory().'/famework/settings/theme-options.php' );
            /*Metabox*/
            include_once( get_template_directory().'/famework/includes/meta-box/meta-box.php' );
            include_once( get_template_directory().'/famework/settings/meta-box.php' );

            /*Theme Functions*/
            include_once( get_template_directory().'/famework/includes/theme-functions.php' );
            /*WIDGETS*/
            include_once( get_template_directory().'/famework/widgets/widget-latest-posts.php' );
            include_once( get_template_directory().'/famework/widgets/widget-instagram.php' );
            include_once( get_template_directory().'/famework/widgets/widget-testimonial.php' );
            include_once( get_template_directory().'/famework/widgets/widget-newsletter.php' );

            if ( class_exists( 'WooCommerce' ) ){
                include_once( get_template_directory().'/famework/widgets/widget-products.php' );

                /*Product attribute */
                include_once( get_template_directory().'/famework/includes/woo-attributes-swatches/woo-term.php' );
                include_once( get_template_directory().'/famework/includes/woo-attributes-swatches/woo-product-attribute-meta.php' );

                /* Woo Functions*/
                include_once( get_template_directory().'/famework/includes/woo-functions.php' );

            }

            /* Custom css and js*/
            include_once( get_template_directory().'/famework/includes/custom-css-js.php' );

            // Register custom shortcodes
            if ( class_exists( 'Vc_Manager' ) ) {
                include_once( get_template_directory().'/famework/includes/visual-composer.php' );
                include_once( get_template_directory().'/famework/settings/vc_templates.php' );
            }
        }

    }
    new Digitalworld_Famework();
}