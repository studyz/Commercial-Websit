<?php
$classes = array();
$digitalworld_woo_product_style = digitalworld_option('digitalworld_woo_product_style',1);

$classes[] = 'product-item style'.$digitalworld_woo_product_style;
$template_style = 'style-'.$digitalworld_woo_product_style;

?>
<li <?php post_class($classes)?>>
    <?php wc_get_template_part('product-styles/content-product', $template_style );?>
</li>