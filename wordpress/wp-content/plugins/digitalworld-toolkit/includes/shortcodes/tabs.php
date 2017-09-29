<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Tabs')){
    class Digitalworld_Toolkit_Shortcode_Tabs extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'tabs';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(
            'style'          => '',
            'tab_animate'    => '',
            'el_class'       => '',
            'css'            => '',
            'ajax_check'     => 'no',
            'tabs_custom_id' => '',
            'tab_title'      => ''
            
        );


        public static  function generate_css( $atts ){
            // Extract shortcode parameters.
            extract($atts);
            return '';
        }

        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_tabs', $atts) : $atts;
            // Extract shortcode parameters.
            extract(
                shortcode_atts(
                    $this->default_atts,
                    $atts
                )
            );
            if( $atts['style'] === 'style3' ){
                $atts['style'] = 'default style3';
            }
            $css_class = 'digitalworld-tabs '. $atts['el_class'] .' '.$atts['style'];
            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            
            $sections = $this->get_all_attributes('vc_tta_section', $content);
            ob_start();
            ?>
            <div class="<?php echo esc_attr( $css_class );?>">
                <?php if( $sections && is_array( $sections ) && count( $sections ) > 0 ):?>
                    <div class="tab-head">
                    <?php if( $tab_title && $style === 'style2' ) : ?>
                        <h3 class="title"><?php echo esc_html( $tab_title ); ?></h3>
                    <?php endif; ?>
                    <ul class="tabs-link">
                        <?php $i=0;?>
                        <?php foreach( $sections as $section): ?>
                            <?php $i++; ?>
                            <?php $content_shortcode = base64_encode($section['content']); ?>
                            <li class="<?php if( $i == 1 ):?>active<?php endif;?>">
                                <a <?php if ( $i == 1 ) {echo 'class="loaded"';} ?> data-ajax="<?php echo esc_attr($ajax_check) ?>" data-shortcode='<?php echo esc_attr($content_shortcode); ?>' data-animate="<?php echo esc_attr( $tab_animate );?>" data-toggle="tab" href="#<?php echo esc_attr( $section['tab_id'] );?>"><?php echo esc_html( $section['title'] );?></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                    <div class="tab-container">
                        <?php $i=0;?>
                        <?php foreach( $sections as $section):?>
                            <?php $i++;?>
                            <div class="tab-panel <?php if( $i == 1 ):?>active<?php endif;?>" id="<?php echo esc_attr( $section['tab_id'] );?>">
                                <?php

                                if ( $ajax_check == '1' ) {
                                    if ( $i == 1 ) {
                                        echo do_shortcode($section['content']);
                                    }
                                } else {
                                    echo do_shortcode( $section['content'] );
                                }
                                ?>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
            </div>
            <?php
            $html = ob_get_clean();
            return apply_filters( 'digitalworld_toolkit_shortcode_tabs', force_balance_tags( $html ), $atts ,$content );
        }
    }
}