<?php
/*
 Name:  Header style 05
 */
?>
<header id="header" class="header style5">
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
            <?php if( class_exists( 'WooCommerce' ) ):?>
                <div class="block-minicart">
                    <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
                </div>
            <?php endif;?>
            <?php  get_template_part( 'template_parts/header','topbar-control');?>

        </div>
    </div>
    <div class="main-header">
        <div class="container">
            <div class="main-menu-wapper"></div>
            <div class="row">
                <div class="col-ts-12 col-xs-6 col-sm-5 col-md-4 col-lg-6">
                    <div class="logo">
                        <?php digitalworld_get_logo();?>
                    </div>
                </div>
                <div class="col-ts-12 col-xs-6 col-sm-7 col-md-8 col-lg-6">
                    <?php
                    digitalworld_search_form();
                    ?>
                    <div class="mobile-control clear-both hidden-lg hidden-md">

                        <?php if( class_exists( 'WooCommerce' ) ):?>
                            <div class="block-minicart digitalworld-mini-cart">
                                <?php woocommerce_mini_cart(); ?>
                            </div>
                        <?php endif;?>
                        <a href="#" class="search-icon-mobile"><?php esc_html_e('Serach','digitalworld');?></a>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
    $header_nav_class = array('header-nav header-sticky');

    ?>

    <div class="<?php echo esc_attr( implode(' ', $header_nav_class) );?>">
        <div class="container">
            <div class="header-nav-inner">
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
                    <?php $opt_hotline = digitalworld_option('opt_hotline','');?>
                    <?php if( $opt_hotline ):?>
                        <div class="hotline">
                            <?php echo balanceTags( $opt_hotline );?>
                        </div>
                    <?php endif;?>

                </div>
            </div>
        </div>
    </div>
</header>