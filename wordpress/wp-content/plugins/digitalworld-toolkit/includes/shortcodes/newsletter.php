<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Newsletter')){

    class Digitalworld_Toolkit_Shortcode_Newsletter extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'newsletter';


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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_newsletter', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-newsletter widget');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] =  $atts['newsletter_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }

            ob_start();
            ?>
            <?php if( $style == 'layout2'):?>
                <div class="default <?php echo esc_attr( implode(' ', $css_class) );?>">
                    <?php if( $atts['title'] ):?>
                        <h3 class="widgettitle"><?php echo esc_html( $atts['title']  ); ?></h3>
                    <?php endif;?>
                    <div class="block-content">
                        <?php if( $atts['subtitle'] ):?>
                            <div class="subtitle"><?php echo esc_html( $atts['subtitle']  ); ?></div>
                        <?php endif;?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email" placeholder="<?php echo esc_attr($atts['placeholder_text']);?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter"><?php esc_html_e('Subscribe','digitalworld-toolkit')?></button>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                    <?php if( $atts['title'] ):?>
                        <h3 class="widgettitle"><?php echo esc_html( $atts['title']  ); ?></h3>
                    <?php endif;?>
                    <div class="block-content">
                        <?php if( $atts['subtitle'] ):?>
                            <div class="subtitle"><?php echo esc_html( $atts['subtitle']  ); ?></div>
                        <?php endif;?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email" placeholder="<?php echo esc_attr($atts['placeholder_text']);?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter"><?php esc_html_e('Sign in','digitalworld-toolkit')?></button>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php
            $html = ob_get_clean();
            return apply_filters( 'digitalworld_toolkit_shortcode_newsletter', force_balance_tags( $html ), $atts ,$content );
        }
    }
}