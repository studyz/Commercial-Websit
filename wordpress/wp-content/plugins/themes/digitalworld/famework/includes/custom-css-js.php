<?php

if( !function_exists('digitalworld_custom_css') ){
    function digitalworld_custom_css(){
        $css = digitalworld_option('digitalworld_custom_css','');
        $css .= digitalworld_theme_color();
        $css .= digitalworld_vc_custom_css_footer();
        wp_enqueue_style(
            'digitalworld_custom_css',
            get_template_directory_uri() . '/css/custom.css'
        );
        wp_add_inline_style( 'digitalworld_custom_css', $css );
    }
}
add_action( 'wp_enqueue_scripts', 'digitalworld_custom_css', 999 );

if( !function_exists('digitalworld_theme_color') ){
    function digitalworld_theme_color(){

        $main_color = digitalworld_option('digitalworld_main_color','#fcd022');
        $digitalworld_body_background = digitalworld_option('digitalworld_body_background','#fff');
        $digitalworld_button_hover_color = digitalworld_option('digitalworld_button_hover_color','#222222');
        $main_color = str_replace("#",'',$main_color);
        $main_color = "#".$main_color;

        $main_color2 = digitalworld_option('digitalworld_main_color2','#e5b700');
        $main_color2 = str_replace("#",'',$main_color2);
        $main_color2 = "#".$main_color2;

        $main_color2_rgb = digitalworld_hex2rgb($main_color2);

        /* Main color */
        $css = '
        body{
            background-color:'.$digitalworld_body_background.';
        }
        .product-item .group-button .button:hover,
        .product-item .group-button .added_to_cart:hover,
        .product-item  .yith-wcwl-add-to-wishlist>div a:hover,
        .product-item.list .product-innfo-right .button,
        .product-item.list .product-innfo-right .added_to_cart,
        .mini-cart-content .actions .button:hover,
        .product-item .yith-wcqv-button,
        .digitalworld-tabs.default .tabs-link li.active a,
        .owl-carousel .owl-next:hover,
        .owl-carousel .owl-prev:hover,
        .digitalworld_products .widgettitle,
        .digitalworld_products .widgettitle,
        .widget_kt_testimonial .widgettitle,
        .digitalworld_widget_newsletter .newsletter-form-wrap .submit-newsletter,
        .header.style2 .form-search-width-category .form-content .btn-search,
        .block-minicart .cartlink .cart-icon .count,
        .header.style2 .main-menu > li > a,
        .digitalworld-newsletter.default .submit-newsletter,
        .digitalworld-categories .button,
        .product-item.style-2 .product-innfo .button,
         .product-item.style-2 .product-innfo .added_to_cart,
         #popup-newsletter .newsletter-form-wrap .submit-newsletter,
         #popup-newsletter .block-social .social:hover,
         .main-menu .toggle-submenu,
         .button,
         .woocommerce-tabs .wc-tabs li.active a,
         input[type="submit"],
         .button:hover,
        input[type="submit"]:hover,
        .button:focus,
        input[type="submit"]:focus,
        .digitalworld_products .owl-prev, .digitalworld_products .owl-next, .widget_kt_testimonial .owl-prev, .widget_kt_testimonial .owl-next,
        .tparrows.custom:hover:before,
        .header.style5 .form-search-width-category .form-content .btn-search,
        .backtotop:hover,
        .backtotop:focus,
        .wishlist_table tr td a.button{
            color:'.$digitalworld_button_hover_color.';
        }
        a:hover,
        a:focus,
        .post-item .post-metas .time .day,
        .nav-links .page-numbers.current,
        .required,
        .toolbar-products .modes a.active:before,
        .woocommerce-pagination ul li .current,
        .digitalworld-blogs .blog-item .time .day,
        .digitalworld-socials a:hover,
        .digitalworld-iconbox.default .icon,
        header.style1 .form-search-width-category .form-content .btn-search:hover,
        .header.style1 .main-menu > li.active > a,
        .header.style1 .main-menu > li:hover > a,
        .top-bar-menu > li > a .icon,
        .middle-menu li a .icon,
        .digitalworld-categories.style3 .list-category li a:hover,
        .top-bar-menu .submenu > li:hover > a,
        .top-bar-menu .submenu > li.active > a{
            color:'.$main_color.';
        }
        
        .button,
        input[type="submit"],
        .block-minicart .cartlink .cart-icon .count,
        .block-nav-categori .block-title,
        .product-item .group-button .button:hover,
        .product-item .group-button .added_to_cart:hover,
        .product-item  .yith-wcwl-add-to-wishlist>div a:hover,
        .product-item.list .product-innfo-right .button,
        .product-item.list .product-innfo-right .added_to_cart,
        .mini-cart-content .actions .button:hover,
        .WOOF_Widget .woof_list li input[type="checkbox"]:checked + label .term-attr, 
        .WOOF_Widget .woof_list li input[type="radio"]:checked + label .term-attr,
        .widget_price_filter .ui-slider-handle,
        .WOOF_Widget .woof .widget_price_filter .ui-slider .ui-slider-handle,
        .owl-carousel .owl-prev:hover,
        .owl-carousel .owl-next:hover,
        .woocommerce-tabs .wc-tabs li.active a,
        .digitalworld-tabs.default .tabs-link li.active a,
        .digitalworld_products .widgettitle,
        .widget_kt_testimonial .widgettitle,
        .widget_kt_testimonial .owl-dots .owl-dot.active,
        .digitalworld_widget_newsletter .newsletter-form-wrap .submit-newsletter,
        .product-item.style-2 .product-innfo .added_to_cart,
        .header.style2 .header-nav,
        .header.style2 .form-search-width-category .form-content .btn-search,
        .digitalworld-newsletter.default .submit-newsletter,
        .digitalworld-tabs.style2 .tabs-link li a:before,
        .digitalworld-socials.style2 a:hover,
        .header.style5 .main-menu > li:hover > a,
        .header.style5 .main-menu > li.active > a,
        .header.style5 .form-search-width-category .form-content .btn-search,
        .footer.style1 .digitalworld-socials.style2 a:hover,
        #popup-newsletter .newsletter-form-wrap .submit-newsletter,
        #popup-newsletter .block-social .social:hover,
        .page-links>span,
        .cssload-square-pink,
        .product-item.style-3 .product-innfo .added_to_cart,
        .tparrows.custom:hover,
        .store-info .info:hover .icon,
        .backtotop:hover,
        .backtotop:focus,
        .product-item.style-6 .product-innfo .added_to_cart{
            background-color:'.$main_color.';
        }
        
        .product-item .group-button .button:hover,
        .product-item .group-button .added_to_cart:hover,
        .product-item  .yith-wcwl-add-to-wishlist>div a:hover,
        .WOOF_Widget .woof_list li input[type="checkbox"]:checked + label .term-attr, 
        .WOOF_Widget .woof_list li input[type="radio"]:checked + label .term-attr,
        .owl-carousel .owl-prev:hover,
        .owl-carousel .owl-next:hover,
        .single-product .images-small .zoom-thumb-link.active img,
        .woocommerce-tabs .wc-tabs li.active a img,
        .digitalworld-socials.style2 a:hover,
        .footer.style1 .digitalworld-socials.style2 a:hover,
        .page-links>span,
        .store-info .info:hover .icon{
            border-color:'.$main_color.';
        }
        
        .header.style2 .block-nav-categori .block-title,
        .header.style2 .main-menu > li:hover>a,
        .header.style2 .main-menu > li.active>a,
        #popup-newsletter .newsletter-form-wrap .submit-newsletter:hover,
        .cssload-square-blend,
        .button:hover,
         input[type="submit"]:hover,
         .button:focus,
         input[type="submit"]:focus,
         .digitalworld_widget_newsletter .newsletter-form-wrap .submit-newsletter:hover,
         .header.style2 .form-search-width-category .form-content .btn-search:hover,
         .digitalworld-newsletter.default .submit-newsletter:hover,
         .header.style5 .form-search-width-category .form-content .btn-search:hover,
         .product-item.style-6 .product-innfo .added_to_cart:hover{
            background-color:'.$main_color2.';
        }
        ';
        return $css;
    }
}

if( !function_exists('digitalworld_vc_custom_css_footer')){
    function digitalworld_vc_custom_css_footer(){
        $digitalworld_footer_style = digitalworld_option('digitalworld_footer_style','');
        $shortcodes_custom_css = get_post_meta( $digitalworld_footer_style, '_wpb_shortcodes_custom_css', true );
        $shortcodes_custom_css .= get_post_meta( $digitalworld_footer_style, '_digitalworld_shortcode_custom_css', true );
        return $shortcodes_custom_css;
    }
}

if( !function_exists( 'digitalworld_write_custom_js ' )){
    function digitalworld_write_custom_js(){
        $digitalworld_custom_js = digitalworld_option('digitalworld_custom_js','');
        ?>
        <script id="digitalworld_custom_js" type="text/javascript">
            <?php echo apply_filters( 'digitalworld_theme_customize_js', $digitalworld_custom_js );?>
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'digitalworld_write_custom_js',100 );

if( ! function_exists( 'digitalworld_hex2rgb' ) ){
    /**
     * Convert HEX to RGB.
     *
     * @since digitalworld 1.0
     *
     * @param string $color The original color, in 3- or 6-digit hexadecimal form.
     * @return array Array containing RGB (red, green, and blue) values for the given HEX code, empty array otherwise.
     * @author RedApple
     */
    function digitalworld_hex2rgb( $color ) {
        $color = trim( $color, '#' );

        if ( strlen( $color ) == 3 ) {
            $r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
            $g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
            $b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
        } else if ( strlen( $color ) == 6 ) {
            $r = hexdec( substr( $color, 0, 2 ) );
            $g = hexdec( substr( $color, 2, 2 ) );
            $b = hexdec( substr( $color, 4, 2 ) );
        } else {
            return array();
        }

        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
    }
}