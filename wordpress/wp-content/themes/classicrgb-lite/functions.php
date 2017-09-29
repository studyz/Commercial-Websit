<?php
if ( ! function_exists( 'classicrgb_lite_setup' ) ) :
function classicrgb_lite_setup() {
	load_theme_textdomain( 'classicrgb-lite', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',	) );
	register_nav_menu('header-menu', __( 'Header Menu', 'classicrgb-lite' ));
}
endif;
add_action( 'after_setup_theme', 'classicrgb_lite_setup' );

function classicrgb_lite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'classicrgb_lite_content_width', 640 );
}
add_action( 'after_setup_theme', 'classicrgb_lite_content_width', 0 );
function classicrgb_lite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Left', 'classicrgb-lite' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header', 'classicrgb-lite' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-1', 'classicrgb-lite' ),
		'id'            => 'sidebar-5',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-2', 'classicrgb-lite' ),
		'id'            => 'sidebar-6',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-3', 'classicrgb-lite' ),
		'id'            => 'sidebar-7',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-4', 'classicrgb-lite' ),
		'id'            => 'sidebar-8',
		'description'   => esc_html__( 'Add widgets here.', 'classicrgb-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'classicrgb_lite_widgets_init' );


function woocommerce_output_related_products() {
    $args = array('posts_per_page' => 3, 'columns' => 3,'orderby' => 'rand' );
    woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );}
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {	function loop_columns() {
		return 3; // 3 products per row	
}}
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );
function classicrgb_lite_scripts() {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array( ), false, 'all' );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap-theme.css', array( ), false, 'all' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array( ), false, 'all' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array( ), false, 'all' );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( ), false, 'all' );
		wp_enqueue_style( 'classicrgb-lite-style', get_stylesheet_uri() );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'classicrgb_lite_scripts' );
require get_template_directory() . '/inc/template-tags.php';
add_action( 'after_setup_theme', 'classicrgb_lite_woocommerce_support' );
function classicrgb_lite_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_filter('add_to_cart_fragments', 'classicrgb_lite_fragment');
function classicrgb_lite_fragment( $fragments ) {
    global $woocommerce;
    ob_start(); ?>
    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'classicrgb-lite'); ?>"> <?php echo $woocommerce->cart->get_cart_total(); ?></a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}



function classicrgb_lite_menu() {
	add_theme_page('ClassicRGB Lite Setup', 'Menu - Help', 'edit_theme_options', 'classicrgb-lite', 'classicrgb_lite_menu_page');
}
add_action('admin_menu', 'classicrgb_lite_menu');
function classicrgb_lite_menu_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'ClassicRGB Lite', 'classicrgb-lite' ) . '</h1></ceter>
<br><br><br>
	<center><h1>' . __( 'Max Mega Menu', 'classicrgb-lite' ) . '</h1></ceter>
<br>
Installation:<br>
1. Go to the Plugins Menu in WordPress<br>
2. Search for "Max Mega Menu"<br>
3. Click "Install"<br><br>
<h2><center>' . __( 'Documentation', 'classicrgb-lite' ) . '  <a href="https://www.megamenu.com/documentation/mega-menus/">' . __( 'https://www.megamenu.com/documentation/mega-menus/', 'classicrgb-lite' ) . '</a></center></h2><br><br>


<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-1.jpg"></center>
<br><br>
<h2><center>' . __( 'Copy Paste code', 'classicrgb-lite' ) . '  <a href="https://rgb-classic.com/rgbclassic-lite-documentation/">' . __( 'https://rgb-classic.com/rgbclassic-lite-documentation/', 'classicrgb-lite' ) . '</a></center></h2><br><br>

<br>
<center><img src="' . get_template_directory_uri() . '/images/mega-menu-2.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/menu3.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-21.jpg"></center>
<br><br>


';
}



function classicrgb_lite_translation_menu() {
	add_theme_page('ClassicRGB Lite Setup', 'Translation', 'edit_theme_options', 'classicrgb-lite-translation', 'classicrgb_lite_menu_translation_page');
}
add_action('admin_menu', 'classicrgb_lite_translation_menu');
function classicrgb_lite_menu_translation_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'ClassicRGB Lite', 'classicrgb-lite-translation' ) . '</h1></ceter>
<br><br><br>
	<center><h1>' . __( 'Translation', 'classicrgb-lite' ) . '</h1></ceter>
<br>
If you have found a mistake in the translation or if you want to add a new language, please translate the text into your language and we will add it to the theme.
<h2><center>  <a href="https://rgb-classic.com/translation/">' . __( 'https://rgb-classic.com/translation/', 'classicrgb-lite' ) . '</a></center></h2><br>
We will add your translation and notify you by email.
<br>
';
}



function classicrgb_lite_pro_menu() {
	add_theme_page('ClassicRGB Lite Setup', 'Free vs PRO', 'edit_theme_options', 'classicrgb-lite-pro', 'classicrgb_lite_menu_pro_page');
}
add_action('admin_menu', 'classicrgb_lite_pro_menu');
function classicrgb_lite_menu_pro_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'free vs PRO', 'classicrgb-lite' ) . '</h1></ceter>
<br><br><br>
<h2><center>  <a href="https://rgb-classic.com/product/rgbclassic/">' . __( 'RGBClassic PRO', 'classicrgb-lite' ) . '</a></center></h2><br>
<br>
Thank you very much for choosing our template. In acknowledgement thereof, we would like to offer a 10% discount on purchase of an extended version. <br>
Discount coupon is valid until August 31, 2017. Here you will find a description of how you can use this coupon.
<h2><center>  <a href="https://rgb-classic.com/discount/">' . __( 'https://rgb-classic.com/discount/', 'classicrgb-lite' ) . '</a></center></h2><br>
<h1><center>rgb10%</center></h1><br>
</br></br></br>


<center><img src="' . get_template_directory_uri() . '/images/rgb-hp1.jpg"></center>
</br>
</br>
</br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/free-pro-rgb.png"></center>
</br>
</br>
</br>
<center>Visual Composer. Best visual designer which allows to easily and simply create nice pages without having programming skills. <br>
It is used to create corporate websites, online stores, landing pages. </center><br><br>
<center><img src="' . get_template_directory_uri() . '/images/vcomposer.jpg"></center>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/theme-options.jpg"></center><br><br>

</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-1.jpg"></center><br><br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-2.jpg"></center><br><br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-3.jpg"></center><br><br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-4.jpg"></center><br><br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-5.jpg"></center><br><br>
</br>
</br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/to-6.jpg"></center><br><br>








<center><img src="' . get_template_directory_uri() . '/images/rgbclassic-no-backlinks-0.jpg"></center><br><br>
<center><img src="' . get_template_directory_uri() . '/images/rgb-color-hp1.jpg"></center><br><br>
<h2><center>  <a href="https://rgb-classic.com/product/rgbclassic/">' . __( 'RGBClassic PRO', 'classicrgb-lite' ) . '</a></center></h2><br>
<br>
';
}


add_action( 'after_setup_theme', 'classicrgb_lite' );
 
function classicrgb_lite() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}