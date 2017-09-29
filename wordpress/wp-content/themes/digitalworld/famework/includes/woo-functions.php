<?php
/*Remove woocommerce_template_loop_product_link_open */
remove_action('woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open',10);
remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close',5);

/*Custom product thumb*/
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail',10);
add_action('woocommerce_before_shop_loop_item_title','digitalworld_template_loop_product_thumbnail',10);

/*Custom product name*/
remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title');
add_action('woocommerce_shop_loop_item_title','digitalworld_template_loop_product_title',10);
/*Custom product per page*/
add_filter( 'loop_shop_per_page','digitalworld_loop_shop_per_page', 20 );
add_filter( 'woof_products_query','digitalworld_woof_products_query', 20 );
/*Custom flash icon*/
remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash',10);
add_action( 'woocommerce_before_shop_loop_item_title', 'digitalworld_group_flash', 5 );
/*Custom next text woocommerce pagination*/
add_filter( 'woocommerce_pagination_args' , 'digitalworld_custom_override_pagination_args' );
/*Custom shop banner*/
add_action( 'digitalworld_before_main_content', 'digitalworld_shop_banner', 1 );
/*Custom shop top control*/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action('woocommerce_before_shop_loop','digitalworld_shop_top_control',1);

add_action('digitalworld_woocommerce_before_loop_start','digitalworld_recommended_products',1);

add_action('digitalworld_woo_get_stock_status','digitalworld_woo_get_stock_status',1);

add_action('digitalworld_display_product_color_in_loop','digitalworld_display_product_color_in_loop',1);
add_action('digitalworld_display_product_countdown_in_loop','digitalworld_display_product_countdown_in_loop',1);



add_filter( 'woocommerce_breadcrumb_defaults', 'digitalworld_woocommerce_breadcrumbs' );
/*Short Product descript*/
add_action('digitalworld_loop_product_short_descript','digitalworld_product_short_descript',15);
/*CUSTOM CART PAGE*/
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10, 1 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10, 1 );
/*---------------------
SINGLE PRODUCT
*/

remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
add_action('woocommerce_single_product_summary','woocommerce_template_single_price',25);


/*Custom placeholder*/
add_filter('woocommerce_placeholder_img_src', 'kutetheme_ovi_custom_woocommerce_placeholder_img_src');

// SETTING PRODUCT ZOOM
add_action('init','digitalworld_prodcut_zoom_setting',2);

/* WC_Vendors */
if( class_exists('WC_Vendors') && class_exists('WCV_Vendor_Shop')){
    // Add sold by to product loop before add to cart
    if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
        remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
        add_action( 'woocommerce_shop_loop_item_title', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 1 );
    }
}

/**
 * Function remove style css of Woocommerce
 *
 * @since digitalworld 1.0
 * @author ReaApple
 **/
add_filter( 'woocommerce_enqueue_styles', 'digitalworld_dequeue_styles' );
function digitalworld_dequeue_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
    unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
    unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
    return $enqueue_styles;

}



if( !function_exists('digitalworld_template_loop_product_thumbnail')){
    function digitalworld_template_loop_product_thumbnail(){
        global $product;
        $digitalworld_enable_lazy = digitalworld_option('digitalworld_enable_lazy','1');
        $thumb_inner_class = array('thumb-inner');
        /*GET SIZE IMAGE SETTING*/
        $w = 400;
        $h = 400;
        $crop = true;
        $size = wc_get_image_size( 'shop_catalog' );
        if( $size ){
            $w = $size['width'];
            $h = $size['height'];
            if( !$size['crop'] ){
                $crop = false;
            }
        }
        $w = apply_filters( 'digitalworld_shop_pruduct_thumb_width', $w);
        $h = apply_filters( 'digitalworld_shop_pruduct_thumb_height', $h);

        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode(' ', $thumb_inner_class) );?>">
            <a class="thumb-link" href="<?php the_permalink();?>">
                <?php
                $image_thumb = digitalworld_resize_image( get_post_thumbnail_id($product->get_id()), null, $w, $h, $crop, true, false );
                ?>
                <?php if( $digitalworld_enable_lazy == '1'):?>
                    <img width="<?php echo esc_attr( $image_thumb['width'] ); ?>" height="<?php echo esc_attr( $image_thumb['height'] ); ?>" class="attachment-post-thumbnail wp-post-image lazy owl-lazy" src="<?php echo trailingslashit ( get_template_directory_uri() ). 'images/1x1.jpg'?>" data-src="<?php echo esc_attr( $image_thumb['url'] ) ?>" data-original="<?php echo esc_attr( $image_thumb['url'] ) ?>" alt="" />
                <?php else:?>
                    <img width="<?php echo esc_attr( $image_thumb['width'] ); ?>" height="<?php echo esc_attr( $image_thumb['height'] ); ?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo esc_attr( $image_thumb['url'] ) ?>"   alt="" />
                <?php endif;?>
            </a>
        </div>
        <?php
        echo ob_get_clean();
    }
}
if( !function_exists('digitalworld_template_loop_product_title')){
    function digitalworld_template_loop_product_title(){
        $title_class = array('product-name');

        $digitalworld_short_product_name = digitalworld_option('digitalworld_short_product_name','yes');
        if( $digitalworld_short_product_name == 'yes' ){
            $title_class[] = 'short';
        }
        ?>
        <h3 class="<?php echo esc_attr( implode(' ', $title_class) );?>"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
        <?php
    }
}
if(!function_exists( 'digitalworld_loop_shop_per_page')){
    function digitalworld_loop_shop_per_page(){
        $digitalworld_woo_products_perpage = digitalworld_option('digitalworld_woo_products_perpage','12');
        return $digitalworld_woo_products_perpage;
    }
}
if(!function_exists( 'digitalworld_group_flash')){
    function digitalworld_group_flash(){
        global $product;
        ?>
        <div class="flashs">
            <?php
            if ( $product->is_in_stock()) {
                woocommerce_show_product_loop_sale_flash();
                digitalworld_show_product_loop_new_flash();
            }
            digitalworld_show_product_loop_stock_flash();
            ?>
        </div>
        <?php
    }
}
/*New flash*/

if ( ! function_exists( 'digitalworld_show_product_loop_new_flash' ) ) {
    /**
     * Get the sale flash for the loop.
     *
     * @subpackage	Loop
     */
    function digitalworld_show_product_loop_new_flash() {
        wc_get_template( 'loop/new-flash.php' );
    }
}

/*Stock flash*/

if ( ! function_exists( 'digitalworld_show_product_loop_stock_flash' ) ) {
    /**
     * Get the sale flash for the loop.
     *
     * @subpackage	Loop
     */
    function digitalworld_show_product_loop_stock_flash() {
        wc_get_template( 'loop/out-of-stock.php' );
    }
}

function digitalworld_custom_override_pagination_args( $args ) {
    $args['prev_text'] = esc_html__('Prev','digitalworld');
    $args['next_text'] = esc_html__('Next','digitalworld');
    $args['mid_size'] =1;
    $args['end_size'] =1;
    return $args;
}

if(!function_exists( 'digitalworld_shop_top_control')){
    function digitalworld_shop_top_control(){
        get_template_part( 'template_parts/shop', 'control' );
    }
}
/*Custom shop view more*/
if( !function_exists( 'digitalworld_shop_view_more')){
    function digitalworld_shop_view_more(){

        $shop_display_mode = digitalworld_option('digitalworld_shop_display_mode','grid');
        if( isset($_SESSION['shop_display_mode'])){
            $shop_display_mode = $_SESSION['shop_display_mode'];
        }

        ?>
        <a data-mode="grid" class="modes-mode mode-grid display-mode <?php if($shop_display_mode =="grid"):?>active<?php endif;?>" href="javascript:void(0)"><?php esc_html_e('Grid','digitalworld');?></a>
        <a data-mode="list" class="modes-mode mode-list display-mode <?php if($shop_display_mode =="list"):?>active<?php endif;?>" href="javascript:void(0)"><?php esc_html_e('List','digitalworld');?></a>
        <?php
    }
}
function digitalworld_woo_hide_page_title() {
    return false;
}
function digitalworld_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => '',
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x( 'Home', 'breadcrumb', 'digitalworld' ),
    );
}
if( !function_exists('digitalworld_product_short_descript')){
    function digitalworld_product_short_descript(){
        global $post;
        $shop_display_mode = digitalworld_option('digitalworld_shop_display_mode','grid');
        if( isset($_SESSION['shop_display_mode'])){
            $shop_display_mode = $_SESSION['shop_display_mode'];
        }
        if(is_shop() || is_product_category() || is_product_tag()){
            if ( ! $post->post_excerpt ) return;
            if( $shop_display_mode =="grid") return;
            ?>
            <div class="product-item-des">
                <?php the_excerpt(); ?>
            </div>
            <?php
        }
    }
}
add_filter('woocommerce_sale_flash', 'digitalworld_custom_sale_flash');

if( !function_exists('digitalworld_custom_sale_flash')){
    function digitalworld_custom_sale_flash($text) {
        $percent = digitalworld_get_percent_discount();
        if( $percent != '' ){
            return '<span class="onsale">'.$percent.'</span>';
        }else{
            return '';
        }

    }
}
/*----------------------
Product view style
----------------------*/
if( ! function_exists( 'wp_ajax_fronted_set_products_view_style_callback' ) ){
    function  wp_ajax_fronted_set_products_view_style_callback(){
        check_ajax_referer( 'digitalworld_ajax_fontend', 'security' );
        $mode = $_POST['mode'];
        $_SESSION['shop_display_mode'] = $mode;
        die();
    }
}
add_action( 'wp_ajax_fronted_set_products_view_style', 'wp_ajax_fronted_set_products_view_style_callback' );
add_action( 'wp_ajax_nopriv_fronted_set_products_view_style', 'wp_ajax_fronted_set_products_view_style_callback' );



function kutetheme_ovi_custom_woocommerce_placeholder_img_src( $src ) {
    $size = wc_get_image_size( 'shop_single' );
    $src = 'https://placehold.it/'.$size['width'].'x'.$size['height'];
    return $src;
}

if( !function_exists( 'digitalworld_prodcut_zoom_setting' ) ){
    function digitalworld_prodcut_zoom_setting(){
        $digitalworld_enable_product_zoom = digitalworld_option('digitalworld_enable_product_zoom',0);
        if( $digitalworld_enable_product_zoom == 1 ){
            remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_images',20);
            add_action('woocommerce_before_single_product_summary','digitalworld_product_zoom_image',20);
        }
    }
}

if( !function_exists( 'digitalworld_product_zoom_image' ) ){
    function digitalworld_product_zoom_image(){
        wc_get_template( 'single-product/product-image-zoom.php' );
    }
}


if( !function_exists('digitalworld_get_percent_discount')){
    function digitalworld_get_percent_discount(){
        global $product;
        $percent = '';
        if ( $product->is_on_sale() ) {
            if( $product->is_type( 'variable' ) ){
                $available_variations = $product->get_available_variations();
                $maximumper = 0;
                $minimumper = 0;
                $percentage = 0;

                for ($i = 0; $i < count($available_variations); ++$i) {
                    $variation_id = $available_variations[$i]['variation_id'];

                    $variable_product1= new WC_Product_Variation( $variation_id );
                    $regular_price = $variable_product1 ->get_regular_price();
                    $sales_price = $variable_product1 ->get_sale_price();
                    if( $regular_price > 0 && $sales_price > 0 ){
                        $percentage = round((( ( $regular_price - $sales_price ) / $regular_price ) * 100),1) ;
                    }

                    if( $minimumper == 0){
                        $minimumper = $percentage;
                    }
                    if ( $percentage > $maximumper ) {
                        $maximumper = $percentage;
                    }

                    if( $percentage < $minimumper ){
                        $minimumper = $percentage;
                    }
                }
                if( $minimumper == $maximumper ){
                    $percent .= '-'.$minimumper.'%';
                }else{
                    $percent .= '-('.$minimumper.'-'.$maximumper.')%';
                }

            }else{
                if( $product->get_regular_price() > 0  && $product->get_sale_price() > 0 ){
                    $percentage = round( ( (( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100),1 );
                    $percent .= '-'.$percentage.'%';
                }
            }
        }
        return $percent;
    }
}


if( !function_exists('digitalworld_woof_products_query')){
    function digitalworld_woof_products_query($wr){
        $digitalworld_woo_products_perpage = digitalworld_option('digitalworld_woo_products_perpage','12');
        $wr['posts_per_page'] = $digitalworld_woo_products_perpage;
        return $wr;
    }
}

add_filter( 'woof_before_term_name','digitalworld_woof_before_term_name', 20,2 );

if( !function_exists('digitalworld_woof_before_term_name')){
    function digitalworld_woof_before_term_name($term, $taxonomy_info){
        global $woocommerce;
        $type = get_woocommerce_term_meta($term['term_id'], $term['taxonomy'].'_attribute_swatch_type', true);
        $class = 'term-attr';
        $style ='';
        ob_start();
        if( $type == 'color'){
            $color = get_woocommerce_term_meta($term['term_id'], $term['taxonomy'].'_attribute_swatch_color', true);
            $class .= ' swatch swatch-color';
            $style = 'style="background-color: '.$color.'"';
        }
        if( $type == 'photo'){
            $thumbnail_id = get_woocommerce_term_meta($term['term_id'], $term['taxonomy'].'_attribute_swatch_photo', true);
            if( $thumbnail_id ){
                $imgsrc = wp_get_attachment_image_src($thumbnail_id, 'attribute_swatch');
                if ($imgsrc && is_array($imgsrc)) {
                    $thumbnail_src = current($imgsrc);
                } else {
                    $thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
                }
                $class .= ' swatch swatch-photo';
                $style = 'style="background-image: url('.esc_url($thumbnail_src).')"';

            }
        }
        echo '<span class="'.esc_attr($class).'" '.$style.'></span>';
        echo $term['name'];
        $content1 = ob_get_contents();
        ob_clean();
        ob_end_flush();
        return $content1;
    }
}



/* Compare */
if( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ){
    global $yith_woocompare;
    $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
    if( $yith_woocompare->is_frontend() || $is_ajax ) {
        if ($is_ajax) {
            if( !class_exists( 'YITH_Woocompare_Frontend' ) ){
                if( file_exists(YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php')){
                    require_once( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' );
                }
            }
            $yith_woocompare->obj = new YITH_Woocompare_Frontend();
        }
        /* Remove button */
        remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
        /* Add compare button */
        if ( !function_exists( 'digitalworld_wc_loop_product_compare_btn' ) ) {
            function digitalworld_wc_loop_product_compare_btn() {
                if ( shortcode_exists( 'yith_compare_button' ) ) {
                    echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
                } // End if ( shortcode_exists( 'yith_compare_button' ) )
                else {
                    if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
                        $YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
                        echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
                    }
                }
            }
        }
        add_action('digitalworld_function_shop_loop_item_compare','digitalworld_wc_loop_product_compare_btn',1);
    }

}

if( class_exists('YITH_WCWL') && get_option('yith_wcwl_enabled') == 'yes'){
    if ( !function_exists( 'digitalworld_wc_loop_product_wishlist_btn' ) ) {
        function digitalworld_wc_loop_product_wishlist_btn() {
            if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
                echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . get_the_ID() . '"]' );
            }
        }
    }
    add_action('digitalworld_function_shop_loop_item_wishlist','digitalworld_wc_loop_product_wishlist_btn',1);
}

/*Custom hook quick view*/
if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
    // Class frontend
    $enable             = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
    $enable_on_mobile   = get_option( 'yith-wcqv-enable-mobile' ) ==  'yes' ? true : false;
    // Class frontend
    if( ( ! wp_is_mobile() && $enable ) || ( wp_is_mobile() && $enable_on_mobile && $enable ) ) {
        remove_action( 'woocommerce_after_shop_loop_item', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 15 );
        add_action( 'digitalworld_function_shop_loop_item_quickview', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 5 );
    }
}

if( !function_exists( 'digitalworld_single_product_sharing' ) ){
    function digitalworld_single_product_sharing(){
        global $product, $post;
        $url = get_the_permalink( $post->ID );
        $title = esc_html( urlencode( get_the_title( $post->ID ) ) );
        $desc = urlencode( get_the_excerpt( $post->ID ) );
        ob_start();
        ?>
        <div class="product_share">
            <a class="facebook" target="_blank" title="<?php esc_html_e( 'share on facebook', 'digitalworld' ); ?>" href="<?php echo esc_url( "https://www.facebook.com/sharer.php?u=" . $url ); ?>"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
            <a class="twitter" target="_blank" title="<?php esc_html_e( 'share on twitter', 'digitalworld' ); ?>" href="<?php echo esc_url( "https://twitter.com/intent/tweet?url=". $url."&text=" .$title ); ?>"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
            <a class="google" target="_blank" title="<?php esc_html_e( 'share on google+', 'digitalworld' ); ?>" href="<?php echo esc_url( "https://plus.google.com/share?url=" . $url ); ?>"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
            <a class="linkedin" target="_blank" title="<?php esc_html_e( 'share on linkedin', 'digitalworld' ); ?>" href="<?php echo esc_url( "https://www.linkedin.com/shareArticle?mini=true&url=" . $url ."&title=" . $title ."&summary=" . $desc ); ?>"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
            <a class="tumblr" target="_blank" title="<?php esc_html_e( 'share on tumblr', 'digitalworld' ); ?>" href="<?php echo esc_url( "https://www.tumblr.com/share/link?url=" . $url ); ?>"><i class="fa fa-tumblr-square" aria-hidden="true"></i></a>

        </div>
        <?php
        $content = ob_get_clean();
        echo $content;
    }
}

//add_action('woocommerce_register_form_start','digitalworld_woo_register_field',10,0);

if( !function_exists('digitalworld_woo_register_field') ){
    function digitalworld_woo_register_field(){
        ?>
        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
            <label for="reg_first_name"><?php esc_html_e( 'First name', 'digitalworld' ); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_first_name" value="<?php if ( ! empty( $_POST['first_name'] ) ) echo esc_attr( $_POST['first_name'] ); ?>" />
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
            <label for="reg_last_name"><?php esc_html_e( 'First name', 'digitalworld' ); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_last_name" value="<?php if ( ! empty( $_POST['last_name'] ) ) echo esc_attr( $_POST['last_name'] ); ?>" />
        </p>
        <?php
    }
}

if( !function_exists( 'digitalworld_woo_get_stock_status' ) ){
    function digitalworld_woo_get_stock_status(){
        global $product;
        ?>
        <div class="product-info-stock-sku">
            <div class="stock available">
                <span class="label-available"><?php esc_html_e( 'Avaiability: ', 'digitalworld' ); ?> </span><?php $product->is_in_stock() ? esc_html_e( 'In Stock', 'digitalworld' ): esc_html_e( 'Out Of Stock', 'digitalworld' ); ?>
            </div>
        </div>
        <?php
    }
}

if( !function_exists('digitalworld_display_product_color_in_loop')){
    function digitalworld_display_product_color_in_loop(){
        global $product;
        $woo_shop_attribute_display = digitalworld_option('woo_shop_attribute_display','');
        if( $woo_shop_attribute_display != '' ){

            // Get product attributes
            $attributes_values = get_the_terms( $product->get_id(), $woo_shop_attribute_display);

            if( is_array($attributes_values) && count( $attributes_values ) > 0 ){
                ?>
                <div class="color-display">
                    <?php foreach ($attributes_values as $term ): ?>
                        <?php
                        $class = '';
                        $color = '';
                        $thumbnail_id = 0;
                        $style = '';
                        $type = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_type', true);
                        if( $type == 'color'){
                            $color = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_color', true);
                            $class = ' swatch swatch-color';
                            $style = 'style="background-color: '.$color.'"';
                        }
                        if( $type == 'photo'){
                            $thumbnail_id = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_photo', true);
                            if( $thumbnail_id ){
                                $imgsrc = wp_get_attachment_image_src($thumbnail_id, 'attribute_swatch');
                                if ($imgsrc && is_array($imgsrc)) {
                                    $thumbnail_src = current($imgsrc);
                                } else {
                                    $thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
                                }
                                $class = ' swatch swatch-photo';
                                $style = 'style="background-image: url('.esc_url($thumbnail_src).')"';

                            }
                        }
                        echo '<span class="'.esc_attr($class).'" '.$style.'></span>';
                        ?>
                    <?php endforeach;?>
                </div>
                <?php
            }
        }
    }
}

// GET DATE SALE
if( ! function_exists( 'digitalworld_get_max_date_sale') ) {
    function digitalworld_get_max_date_sale( $product_id ) {
        $time = 0;
        // Get variations
        $args = array(
            'post_type'     => 'product_variation',
            'post_status'   => array( 'private', 'publish' ),
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'post_parent'   => $product_id
        );
        $variations = get_posts( $args );
        $variation_ids = array();
        if( $variations ){
            foreach ( $variations as $variation ) {
                $variation_ids[]  = $variation->ID;
            }
        }
        $sale_price_dates_to = false;

        if( !empty(  $variation_ids )   ){
            global $wpdb;
            $sale_price_dates_to = $wpdb->get_var( "
                SELECT
                meta_value
                FROM $wpdb->postmeta
                WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ")
                ORDER BY meta_value DESC
                LIMIT 1
            " );

            if( $sale_price_dates_to != '' ){
                return $sale_price_dates_to;
            }
        }

        if( ! $sale_price_dates_to ){
            $sale_price_dates_to = get_post_meta( $product_id, '_sale_price_dates_to', true );

            if($sale_price_dates_to == ''){
                $sale_price_dates_to = '0';
            }

            return $sale_price_dates_to;
        }
    }
}


if( !function_exists('digitalworld_display_product_countdown_in_loop') ){
    function digitalworld_display_product_countdown_in_loop(){
        global $product;
        $date = digitalworld_get_max_date_sale($product->get_id());
        ?>
        <?php if( $date > 0 ):
            $y = date('Y',$date);
            $m = date('m',$date);
            $d = date('d',$date);
            $h = date('h',$date);
            $i = date('i',$date);
            $s = date('s',$date);
            ?>
            <div class="product-count-down">
                <div class="digitalworld-countdown" data-y="<?php echo esc_attr($y);?>" data-m="<?php echo esc_attr($m);?>" data-d="<?php echo esc_attr($d);?>" data-h="<?php echo esc_attr($h);?>" data-i="<?php echo esc_attr($i);?>" data-s="<?php echo esc_attr($s);?>"></div>
            </div>
        <?php endif;?>
        <?php
    }
}

if( !function_exists('digitalworld_recommended_products')){
    function digitalworld_recommended_products(){
        wc_get_template( 'recommended-products.php' );
    }
}

if( !function_exists( 'digitalworld_product_slider' ) ){
    function digitalworld_product_slider(){
        global $product;
        
        $list_gallery = $product->get_gallery_image_ids();
        $src_list = array();
        if( is_array( $list_gallery ) && !empty( $list_gallery ) ){

            foreach( $list_gallery as $list ){
                 $thumbnail = wp_get_attachment_image_src( $list, 'thumbnail' );
                 $full = wp_get_attachment_image_src( $list, 'full' );
                 if( empty( $thumbnail ) ){ $thumbnail[0] = ''; }
                 if( empty( $full ) ){ $full[0] = ''; }
                 
                 $src_list[] = '<div class="gallery_single_img"><img alt="" src="'.$thumbnail[0].'" data-big_img="'.$full[0].'" /></div>';
            }
        }
        
        if( !empty( $src_list ) ){
            ?>
                <div class="product_gallery owl-carousel" data-items="3" data-margin="10">
                    <?php echo implode( '', $src_list ); ?>
                </div>
            <?php
        }
    }
}

add_action( 'digitalworld_before_product_info', 'digitalworld_product_slider', 10 );

/* TABS AJAX */
if(!function_exists('digitalworld_tab_product_via_ajax')){
    function digitalworld_tab_product_via_ajax(){
        $response = array(
            'html'    => '',
            'message' => '',
            'success' => 'no'
        );
        $decode       = isset( $_POST['decode'] ) ? $_POST['decode'] : '';
        $the_content  = str_replace('\"','"',$decode);

        ob_start();
        ?>

        <?php echo do_shortcode( $the_content );?>

        <?php
        $response['html'] = ob_get_clean();

        $response['success'] = 'ok';


        wp_send_json( $response );
        die();
    }
}
add_action( 'wp_ajax_digitalworld_tab_product_via_ajax', 'digitalworld_tab_product_via_ajax' );
add_action( 'wp_ajax_nopriv_digitalworld_tab_product_via_ajax', 'digitalworld_tab_product_via_ajax' );


add_filter('woocommerce_add_to_cart_fragments', 'digitalworld_header_add_to_cart_fragment');

if( !function_exists('digitalworld_header_add_to_cart_fragment')){
    function digitalworld_header_add_to_cart_fragment($fragments)
    {
        ob_start();
        global $woocommerce;
        ?>

        <div class="block-minicart digitalworld-mini-cart">
            <?php woocommerce_mini_cart(); ?>
        </div>

        <?php
        $fragments['div.digitalworld-mini-cart'] = ob_get_clean();

        return $fragments;
    }
}
