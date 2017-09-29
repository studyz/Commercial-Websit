<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Verticalmenu')){
    class Digitalworld_Toolkit_Shortcode_Verticalmenu extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'verticalmenu';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(

        );


        public  static function generate_css( $atts ){
            //$atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_verticalmenu', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css = '';
            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_verticalmenu', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-verticalmenu block-nav-categori has-open');
            $css_class[] = $atts['el_class'];
            $css_class[] =  $atts['verticalmenu_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            $nav_menu = get_term_by('slug', $atts['menu'],'nav_menu');
            $button_close_text = $atts['button_close_text']; //'Close';
            $button_all_text = $atts['button_all_text']; // 'All Categories';
            $limit_items = absint( $atts['limit_items'] );//9
            
            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode(' ', $css_class) );?>" data-items="<?php echo esc_attr( $limit_items ); ?>">
                <?php if( $title ):?>
                    <div class="block-title">
                        <span class="icon-bar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <span class="text">
                            <?php echo esc_html( $title );?>
                        </span>

                    </div>
                <?php endif;?>
                <div class="block-content verticalmenu-content">
                    <?php if( is_object($nav_menu) && count($nav_menu) == 1 ):?>
                        <?php
                        wp_nav_menu( array(
                            'menu'            => $nav_menu,
                            'depth'           => 3,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'digitalworld-nav vertical-menu',
                            'fallback_cb'     => 'Digitalworld_navwalker::fallback',
                            'walker'          => new Digitalworld_navwalker()
                        ));
                        ?>

                    <?php endif;?>
                    <div class="view-all-categori">
                        <a href="#" data-closetext="<?php echo esc_attr( $button_close_text );?>" data-alltext="<?php echo esc_attr( $button_all_text )?>" class="btn-view-all open-cate"><?php echo esc_html( $button_all_text )?></a>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'digitalworld_toolkit_shortcode_verticalmenu', force_balance_tags( $html ), $atts ,$content );
        }
    }
}