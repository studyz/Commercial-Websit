<?php
/*
 Name:  Header style 02
 */
?>
<header id="header" class="header style2">
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
                        <div class="col-sm-7 col-md-7 col-lg-8 hidden-sm hidden-xs">
                            <?php
                            digitalworld_search_form();
                            ?>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4">
                            <?php
                            $opt_enable_wishlist_link = digitalworld_option('digitalworld_enable_wishlist_link', '0');
                            $extra_class = '';
                            if( $opt_enable_wishlist_link && defined( 'YITH_WCWL' ) ){
                                $extra_class = 'has-wishlist-link';
                            }else{
                                $extra_class = 'no-wishlist-link';
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
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
    $opt_enable_vertical_menu = digitalworld_option('opt_enable_vertical_menu', '0');
    $opt_click_open_vertical_menu = digitalworld_option('opt_click_open_vertical_menu', '1');
    $opt_vertical_item_visible = digitalworld_option('opt_vertical_item_visible', 10);
    $header_nav_class = array('header-nav header-sticky');
    if( $opt_enable_vertical_menu == 1 ){
        $header_nav_class[] = 'has-vertical-menu';
    }

    ?>

    <div class="<?php echo esc_attr( implode(' ', $header_nav_class) );?>">
        <div class="container">
            <div class="header-nav-inner">
                <?php if( $opt_enable_vertical_menu == '1'):?>
                    <?php
                    $block_vertical_class = array('vertical-wapper block-nav-categori');
                    if( ($opt_click_open_vertical_menu == '0' ) && ( is_front_page() || ( isset($_GET['digitalworld_is_home']) && $_GET['digitalworld_is_home'] == 'true' ) ) ){
                        $block_vertical_class[] = 'open-on-home is-home always-open';
                    }
                    $opt_vertical_menu_title  = digitalworld_option('opt_vertical_menu_title','Shop By Category');
                    $opt_vertical_menu_button_all_text  = digitalworld_option('opt_vertical_menu_button_all_text','All Categories');
                    $opt_vertical_menu_button_close_text  = digitalworld_option('opt_vertical_menu_button_close_text','Close');
                    ?>
                    <!-- block categori -->
                    <div data-items="<?php echo esc_attr( $opt_vertical_item_visible );?>" class="<?php echo esc_attr( implode(' ', $block_vertical_class) );?>">
                        <div class="block-title">
                            <span class="icon-bar">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="text"><?php echo esc_html($opt_vertical_menu_title);?></span>
                        </div>
                        <div class="block-content verticalmenu-content">
                            <?php
                            wp_nav_menu( array(
                                'menu'            => 'vertical_menu',
                                'theme_location'  => 'vertical_menu',
                                'depth'           => 4,
                                'container'       => '',
                                'container_class' => '',
                                'container_id'    => '',
                                'menu_class'      => 'digitalworld-nav vertical-menu ',
                                'fallback_cb'     => 'Digitalworld_navwalker::fallback',
                                'walker'          => new Digitalworld_navwalker()
                            ));
                            ?>
                            <div class="view-all-categori">
                                <a href="#" data-closetext="<?php echo esc_attr( $opt_vertical_menu_button_close_text );?>" data-alltext="<?php echo esc_attr( $opt_vertical_menu_button_all_text )?>" class="btn-view-all open-cate"><?php echo esc_html( $opt_vertical_menu_button_all_text )?></a>
                            </div>
                        </div>
                    </div><!-- block categori -->
                <?php endif;?>
                <div class="box-header-nav">
                    <a class="menu-bar mobile-navigation" href="#">
                        <span class="icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <span class="text"><?php esc_html_e('Main Menu','digitalworld');?></span>
                    </a>
                    <?php 
                        wp_nav_menu( array(
                            'menu'            => 'primary',
                            'theme_location'  => 'primary',
                            'depth'           => 3,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'clone-main-menu digitalworld-nav main-menu',
                            'fallback_cb'     => 'Digitalworld_navwalker::fallback',
                            'walker'          => new Digitalworld_navwalker()
                        ));
                    ?>

                </div>
            </div>
        </div>
    </div>
</header>