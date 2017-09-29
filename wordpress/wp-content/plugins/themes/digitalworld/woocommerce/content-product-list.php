<?php
/*
 Name:Product style 1
 Slug:content-product
 */
    do_action( 'woocommerce_before_shop_loop_item' );
?>
<div class="product-inner">
    <?php
    /**
     * woocommerce_before_shop_loop_item hook.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action( 'woocommerce_before_shop_loop_item' );
    ?>
    <div class="product-thumb">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action( 'woocommerce_before_shop_loop_item_title' );
        do_action('digitalworld_function_shop_loop_item_quickview');
        ?>

    </div>
    <div class="product-innfo">
        <div class="inner">
            <div class="product-innfo-left">
                <?php
                /**
                 * woocommerce_shop_loop_item_title hook.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action( 'woocommerce_shop_loop_item_title' );



                ?>
                <?php do_action( 'digitalworld_loop_product_short_descript' );?>
            </div>

            <div class="product-innfo-right">
                <div class="group-price">
                    <?php
                    /**
                     * woocommerce_after_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_template_loop_rating - 5
                     * @hooked woocommerce_template_loop_price - 10
                     */
                    do_action( 'woocommerce_after_shop_loop_item_title' );
                    do_action('digitalworld_woo_get_stock_status');
                    ?>
                </div>
                <?php
                do_action( 'woocommerce_after_shop_loop_item' );
                do_action('digitalworld_function_shop_loop_item_wishlist');
                do_action('digitalworld_function_shop_loop_item_compare');

                ?>
            </div>
        </div>
    </div>


</div>