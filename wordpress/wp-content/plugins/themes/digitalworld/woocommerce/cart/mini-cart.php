<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

?>
<?php  global $woocommerce; ?>
    <a class="cartlink" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
        <span class="cart-icon">
            <span class="count"><?php echo WC()->cart->cart_contents_count ?></span>
        </span>
        <span class="cart-text">
            <span class="text2">
                <?php echo WC()->cart->cart_contents_count; ?> <?php esc_html_e('Item(s)','digitalworld');?>
                - <?php echo balanceTags( $woocommerce->cart->get_cart_subtotal() ); ?>
            </span>
        </span>
    </a>
    <?php if ( ! WC()->cart->is_empty() ) : ?>
    <div class="mini-cart-content">
        <div  class="minicart-content-wrapper" >
            <div class="minicart-title"><?php esc_html_e('You have ','digitalworld');?><?php echo WC()->cart->cart_contents_count;?><?php esc_html_e(' item(s) in your cart','digitalworld')?></div>
            <div class="minicart-items-wrapper">
                <ol class="minicart-items">
                    <?php  foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ): ?>
                        <?php $bag_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key ); ?>
                        <?php $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key ); ?>

                        <?php if ( $bag_product &&  $bag_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ): ?>

                            <?php $product_name  = apply_filters( 'woocommerce_cart_item_name', $bag_product->get_title(), $cart_item, $cart_item_key );?>
                            <?php $thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $bag_product->get_image('shop_thumbnail'), $cart_item, $cart_item_key );?>
                            <?php $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $bag_product ), $cart_item, $cart_item_key );?>
                            <li class="item">
                                <a class="thumb" href="<?php echo esc_url( get_permalink( $cart_item[ 'product_id' ] ) )  ?>">
                                     <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                </a>
                                <div class="info">
                                    <h3 class="product-name"><?php echo balanceTags( $product_name ) ; ?></h3>
                                    <div class="product-item-qty">
                                        <span class="number"><?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="qty">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?></span>
                                    </div>
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="action delete" title="%s" data-product_id="%s" data-product_sku="%s"><span>%s</span></a>',
                                            esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove this item', 'digitalworld' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $bag_product->get_sku() ),
                                            esc_html__( 'Remove', 'digitalworld' )
                                        ), $cart_item_key );
                                    ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </div>
            <div class="subtotal">
                <span class="text"><?php esc_html_e('Subtotal:','digitalworld');?></span>
                <span class="total"><?php echo balanceTags( $woocommerce->cart->get_cart_subtotal() ); ?></span>
            </div>
            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
            <div class="actions">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button small btn-checkout"><span><?php esc_html_e('Check out','digitalworld');?></span></a>
                <a class="button small border btn-viewcart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                    <span><?php esc_html_e('View cart','digitalworld');?></span>
                </a>
            </div>
    </div>
    </div>
    <?php endif;?>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
