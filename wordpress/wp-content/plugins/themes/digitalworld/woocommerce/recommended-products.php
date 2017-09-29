<?php
$opt_enable_recommended_products = digitalworld_option('opt_enable_recommended_products',1);
if( $opt_enable_recommended_products == 0  || is_singular( 'product' ) ){
    return;
}

$args = array(
    'post_type' => 'product',
    'meta_key' => '_featured',
    'meta_value' => 'yes',
    'posts_per_page' => -1
);

$products = new WP_Query( $args );
$digitalworld_woo_recommended_lg_items = digitalworld_option('digitalworld_woo_recommended_lg_items',4);
$digitalworld_woo_recommended_md_items = digitalworld_option('digitalworld_woo_recommended_md_items',4);
$digitalworld_woo_recommended_sm_items = digitalworld_option('digitalworld_woo_recommended_sm_items',3);
$digitalworld_woo_recommended_xs_items = digitalworld_option('digitalworld_woo_recommended_xs_items',2);
$digitalworld_woo_recommended_ts_items = digitalworld_option('digitalworld_woo_recommended_ts_items',1);

$data_reponsive = array(
    '0'=>array(
        'items'=>$digitalworld_woo_recommended_ts_items
    ),
    '480'=>array(
        'items'=>$digitalworld_woo_recommended_xs_items
    ),
    '768'=>array(
        'items'=>$digitalworld_woo_recommended_sm_items
    ),
    '992'=>array(
        'items'=>$digitalworld_woo_recommended_md_items
    ),
    '1200'=>array(
        'items'=>$digitalworld_woo_recommended_lg_items
    ),
);
$data_reponsive = json_encode($data_reponsive);
$loop = 'false';

if ( $products->have_posts() ) :

    if( $products->post_count > 1){
        $loop = 'true';
    }
    $digitalworld_recommended_products_title = digitalworld_option('digitalworld_recommended_products_title','Recommended Products');
    ?>
    <div class="block-related block-recommended">
        <h3 class="block-title">
            <?php echo esc_html($digitalworld_recommended_products_title);?>
        </h3>
        <div class="block-content ">
            <ul class="product-items owl-carousel kt-owl-carousel equal-container" data-nav="true" data-margin="28" data-loop="<?php echo esc_attr($loop);?>" data-responsive='<?php echo esc_attr($data_reponsive);?>'>
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php
                    $classes = array();
                    $digitalworld_woo_product_style = digitalworld_option('digitalworld_woo_product_style',1);

                    $classes[] = 'product-item style'.$digitalworld_woo_product_style;
                    $template_style = 'style-'.$digitalworld_woo_product_style;

                    ?>
                    <li <?php post_class($classes)?>>
                        <?php wc_get_template_part('product-styles/content-product', $template_style );?>
                    </li>

                <?php endwhile; // end of the loop. ?>
            </ul>
        </div>
    </div>

<?php endif;

wp_reset_postdata();