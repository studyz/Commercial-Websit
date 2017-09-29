<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Title')){
    class Digitalworld_Toolkit_Shortcode_Title extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'title';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(
        );


        public  static function generate_css( $atts ){
            // Extract shortcode parameters.
            extract($atts);
            $css = '';

            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_title', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);


            $css_class = array('digitalworld-title');
            $css_class[] = $style;
            $css_class[] = $atts['el_class'];
            $css_class[] =  $atts['title_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            ob_start();
            ?>
            <?php if( $title ):?>
            <h3 class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                <span><?php echo esc_html( $title );?></span>
            </h3>
            <?php endif;?>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'digitalworld_toolkit_shortcode_title', force_balance_tags( $html ), $atts ,$content );
        }
    }
}