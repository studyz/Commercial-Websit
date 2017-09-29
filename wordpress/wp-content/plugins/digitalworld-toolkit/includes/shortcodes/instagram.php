<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Instagram')){

    class Digitalworld_Toolkit_Shortcode_Instagram extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'instagram';

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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_instagram', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-instagram');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] =  $atts['instagram_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }

            $owl_settings = $this->generate_carousel_data_attributes('', $atts);
            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                <?php
                if( intval( $id ) === 0 ){
                    esc_html_e('No user ID specified.','digitalworld');
                }
                $transient_var = $id . '_' . $limit;

                if ( false === ( $items = get_transient( $transient_var ) ) && ! empty( $id ) && ! empty( $token ) ) {
                    $response = wp_remote_get( 'https://api.instagram.com/v1/users/' . esc_attr( $id ) . '/media/recent/?access_token=' . esc_attr( $token ) . '&count=' . esc_attr( $limit ) );
                    if( ! is_wp_error( $response ) ) {
                        $response_body = json_decode( $response['body'] );

                        if ( $response_body->meta->code !== 200 ) {
                            echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'digitalworld' ) . '</p>';
                        }

                        $items_as_objects = $response_body->data;
                        $items = array();

                        foreach ( $items_as_objects as $item_object ) {
                            $item['link'] = $item_object->link;
                            $item['src']  = $item_object->images->low_resolution->url;
                            $items[]      = $item;
                        }

                        set_transient( $transient_var, $items, 60 * 60 );
                    }
                }
                ?>
                <?php if ( isset( $items ) && $items ):?>
                <div class="owl-carousel nav-center instagram" <?php echo $owl_settings; ?>>
                    <?php foreach ( $items as $item ):?>
                        <div class="item">
                            <a target="_blank" href="<?php echo esc_url($item['link'])?>"><img width="320" height="320" src="<?php echo esc_url( $item['src'] );?>" alt="Instagram" /></a>
                        </div>
                    <?php endforeach;?>
                </div>
                <?php endif;?>
            </div>
            <?php
            $html = ob_get_clean();
            return apply_filters( 'digitalworld_toolkit_shortcode_blogs', force_balance_tags( $html ), $atts ,$content );
        }
    }
}