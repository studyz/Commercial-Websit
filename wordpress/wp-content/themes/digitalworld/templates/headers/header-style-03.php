<?php
/*
 Name:  Header style 03
 */
?>
<header id="header" class="header style2 style3">
    <div class="top-header">
        <div class="container">
            <?php
            wp_nav_menu( array(
                'menu'            => 'top_left_menu',
                'theme_location'  => 'top_left_menu',
                'depth'           => 2,
                'container'       => '',
                'container_class' => '',
                'container_id'    => '',
                'menu_class'      => 'digitalworld-nav top-bar-menu left',
                'fallback_cb'     => 'Digitalworld_navwalker::fallback',
                'walker'          => new Digitalworld_navwalker()
            ));
            ?>
            <?php  get_template_part( 'template_parts/header','topbar-control');?>
        </div>
    </div>
    <div class="main-header">
        <div class="container">
            <div class="main-menu-wapper"></div>
            <div class="row">
                <div class="col-ts-12 col-xs-6 col-sm-6 col-md-3">
                    <div class="logo">
                        <?php digitalworld_get_logo();?>
                    </div>
                </div>
                <div class="col-ts-12 col-xs-6 col-sm-6 col-md-9">
                    <?php
                    wp_nav_menu( array(
                        'menu'            => 'middle-menu',
                        'theme_location'  => 'middle-menu',
                        'depth'           => 1,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'digitalworld-nav middle-menu',
                        'fallback_cb'     => 'Digitalworld_navwalker::fallback',
                        'walker'          => new Digitalworld_navwalker()
                    ));
                    ?>
                    <div class="row">
                        <div class="col-sm-7 col-md-8 col-lg-7 hidden-sm hidden-xs">
                            <?php
                            digitalworld_search_form();
                            ?>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-5">
                            <?php
                            $opt_enable_wishlist_link = digitalworld_option('digitalworld_enable_wishlist_link', '0');
                            $opt_enable_compare_link  = digitalworld_option('digitalworld_enable_compare_link', '0');
                            $extra_class = '';
                            if( $opt_enable_wishlist_link && defined( 'YITH_WCWL' ) ){
                                $extra_class = 'has-wishlist-link';
                            }else{
                                $extra_class = 'no-wishlist-link';
                            }
                            if( $opt_enable_compare_link && defined( 'YITH_WOOCOMPARE' ) ){
                                $extra_class .= ' has-compare-btn';
                            }else{
                                $extra_class .= ' no-compare-btn';
                            }
                            ?>
                            <div class="header-control clear-both <?php echo esc_attr( $extra_class ); ?>">
                                <a href="#" class="search-icon-mobile"><?php esc_html_e('Serach','digitalworld');?></a>
                                <?php if( class_exists( 'WooCommerce' ) ):?>
                                    <div class="block-minicart digitalworld-mini-cart">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                <?php endif;?>
                                <?php if( defined( 'YITH_WCWL' ) && $opt_enable_wishlist_link ) : ?>
                                    <?php
                                    $yith_wcwl_wishlist_page_id = get_option('yith_wcwl_wishlist_page_id');
                                    $wishlist_url = get_page_link($yith_wcwl_wishlist_page_id);
                                    ?>
                                    <?php if( $wishlist_url != '' ) : ?>
                                        <a class="woo-wishlist-link" href="<?php echo esc_url( $wishlist_url ); ?>"><?php esc_html_e( 'Wishlist', 'digitalworld' ); ?></a>
                                    <?php endif; ?>
                                    <?php if( $opt_enable_compare_link && defined( 'YITH_WOOCOMPARE' ) ):  ?>
                                        <a href="#" class="woo-compare-link open-compare-table" id="digitalworld_open_compare_table"><?php esc_html_e( 'Compare', 'digitalworld' ); ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>