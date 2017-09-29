<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Banner')){
    class Digitalworld_Toolkit_Shortcode_Banner extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'banner';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(
            'banner_image'     => '',
            'style'            => '',
            'content_position' => 'left',
            'text_align'       => 'text-left',
            'banner_link'      => '',
            'el_class'         => '',
            'css'              => '',
            'banner_custom_id' => ''
        );


        public  static function generate_css( $atts ){
            // Extract shortcode parameters.
            extract($atts);
            $css = '';
            if( $content_position == 'right' ){
                $css .= '.'.$banner_custom_id .' .banner-content{ float:right;} ';
            }

            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_banner', $atts) : $atts;

            // Extract shortcode parameters.
            extract(
                shortcode_atts(
                    $this->default_atts,
                    $atts
                )
            );

            $css_class = 'digitalworld-banner '. $atts['el_class'] .' '.$atts['content_position'].' '.$atts['style'].' '.$text_align;
            $css_class .=' '.$banner_custom_id;
            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr( $css_class);?>">
                <?php if( $atts['banner_image']):?>
                    <div class="banner">
                        <?php if( $atts['banner_link']):?>
                            <?php echo wp_get_attachment_image( $atts['banner_image'] , 'full');?>
                        <?php else:?>
                            <a href="<?php echo esc_url( $atts['banner_link'] );?>"> <?php echo wp_get_attachment_image( $atts['banner_image'] , 'full');?> </a>
                        <?php endif;?>
                    </div>
                <?php endif;?>
                <div class="banner-content">
                    <?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'digitalworld_toolkit_shortcode_banner', force_balance_tags( $html ), $atts ,$content );
        }
    }
}