<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Products')){
    class Digitalworld_Toolkit_Shortcode_Products extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'products';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(
            'owl_navigation_position_top' => '-60'
        );


        public static function generate_css( $atts ){
            extract( $atts );
            $css = '';
            if( $atts['owl_navigation_position'] == 'nav-top-left' || $atts['owl_navigation_position'] == 'nav-top-right'){
                $css .= '.'.$atts['products_custom_id'] .' .owl-nav{ top:'.$atts['owl_navigation_position_top'].'px;} ';
            }

            return $css;
        }

        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_products', $atts) : $atts;

            extract( $atts );
            $css_class = array('digitalworld-products');
            $css_class[] = $atts['el_class'];
            $css_class[] =  $atts['products_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[]= apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }


            /* Product Size */
            if ( $product_image_size ){
                if( $product_image_size == 'custom'){
                    $thumb_width = $product_custom_thumb_width;
                    $thumb_height = $product_custom_thumb_height;
                }else{
                    $product_image_size = explode("x",$product_image_size);
                    $thumb_width = $product_image_size[0];
                    $thumb_height = $product_image_size[1];
                }
                if($thumb_width > 0){
                    add_filter( 'digitalworld_shop_pruduct_thumb_width', create_function('','return '.$thumb_width.';') );
                }
                if($thumb_height > 0){
                    add_filter( 'digitalworld_shop_pruduct_thumb_height', create_function('','return '.$thumb_height.';') );
                }
            }
            $products = $this->getProducts($atts);
            $product_item_class = array('product-item',$target);
            $product_item_class[] ='style-'.$product_style;
            $product_list_class = array();
            $owl_settings = '';
            if( $productsliststyle  == 'grid' ){
                $product_list_class[] = 'product-list-grid row auto-clear equal-container';

                $product_item_class[] = $boostrap_rows_space;
                $product_item_class[] = 'col-lg-'.$boostrap_lg_items;
                $product_item_class[] = 'col-md-'.$boostrap_md_items;
                $product_item_class[] = 'col-sm-'.$boostrap_sm_items;
                $product_item_class[] = 'col-xs-'.$boostrap_xs_items;
                $product_item_class[] = 'col-ts-'.$boostrap_ts_items;
            }
            if( $productsliststyle  == 'owl' ){
                $product_list_class[] = 'product-list-owl owl-carousel equal-container '.$owl_navigation_position;

                $product_item_class[] = $owl_rows_space;

                $owl_settings = $this->generate_carousel_data_attributes('owl_', $atts);
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                <?php if( $products->have_posts()): ?>
                    <?php if( $productsliststyle == 'grid'):?>
                        <ul class="<?php echo esc_attr( implode(' ', $product_list_class) );?>" >
                            <?php while ( $products->have_posts() ) : $products->the_post();  ?>
                                <li <?php post_class( $product_item_class );?>>
                                    <?php wc_get_template_part('product-styles/content-product-style', $product_style); ?>
                                </li>
                            <?php endwhile;?>
                        </ul>
                    <?php endif;?>
                    <!-- OWL Products -->
                    <?php if( $productsliststyle == 'owl'):?>
                        <?php
                        $i = 1;
                        $toal_product = $products->post_count;
                        ?>
                        <div class="<?php echo esc_attr( implode(' ', $product_list_class) );?>" <?php echo force_balance_tags($owl_settings);?>>
                            <div class="owl-one-row">
                                <?php while ( $products->have_posts() ) : $products->the_post();  ?>
                                    <div <?php post_class( $product_item_class );?>>
                                        <?php wc_get_template_part('product-styles/content-product-style', $product_style); ?>
                                    </div>
                                    <?php
                                    if( $i % $owl_number_row == 0 && $i < $toal_product ){
                                        echo '</div><div class="owl-one-row">';
                                    }
                                    $i++;
                                    ?>
                                <?php endwhile;?>
                            </div>
                        </div>
                    <?php endif;?>
                <?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'trueshop' ); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'digitalworld_toolkit_shortcode_products', force_balance_tags( $html ), $atts ,$content );
        }
    }
}