<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Custommenu')){
    class Digitalworld_Toolkit_Shortcode_Custommenu extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'custommenu';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(

        );


        public static function generate_css( $atts ){
            // Extract shortcode parameters.
            extract($atts);
            $css = '';
            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_custommenu', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-scustommenu');
            $css_class[] = $atts['el_class'];
            $css_class[] =  $atts['custommenu_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            $nav_menu = get_term_by('slug', $atts['menu'],'nav_menu');
            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                <?php if( is_object($nav_menu) && count($nav_menu) == 1 ):?>
                    <?php
                    $instance = array();
                    if( $atts['title'] ){
                        $instance['title'] = $atts['title'];
                    }
                    if( $nav_menu->term_id ){
                        $instance['nav_menu'] = $nav_menu->term_id;
                    }
                    the_widget('WP_Nav_Menu_Widget',$instance);
                    ?>
                <?php endif;?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'digitalworld_toolkit_shortcode_custommenu', force_balance_tags( $html ), $atts ,$content );
        }
    }
}