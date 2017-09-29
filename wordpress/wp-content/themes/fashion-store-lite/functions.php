<?php
if ( ! function_exists( 'fashion_store_lite_setup' ) ) :
function fashion_store_lite_setup() {
	load_theme_textdomain( 'fashion-store-lite', get_template_directory() . '/languages' );
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
	register_nav_menu('header-menu', __( 'Header Menu', 'fashion-store-lite' ));
}
endif;
add_action( 'after_setup_theme', 'fashion_store_lite_setup' );

function fashion_store_lite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fashion_store_lite_content_width', 640 );
}
add_action( 'after_setup_theme', 'fashion_store_lite_content_width', 0 );
function fashion_store_lite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right', 'fashion-store-lite' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header 1', 'fashion-store-lite' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header 2', 'fashion-store-lite' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header 3', 'fashion-store-lite' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-1', 'fashion-store-lite' ),
		'id'            => 'sidebar-5',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-2', 'fashion-store-lite' ),
		'id'            => 'sidebar-6',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-3', 'fashion-store-lite' ),
		'id'            => 'sidebar-7',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-4', 'fashion-store-lite' ),
		'id'            => 'sidebar-8',
		'description'   => esc_html__( 'Add widgets here.', 'fashion-store-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'fashion_store_lite_widgets_init' );




add_filter('add_to_cart_fragments', 'fashion_store_lite_fragment');
function fashion_store_lite_fragment( $fragments ) {
    global $woocommerce;
    ob_start(); ?>
    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'fashion-store-lite'); ?>"> <?php echo $woocommerce->cart->get_cart_total(); ?></a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}





function fashion_store_lite_scripts() {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array( ), false, 'all' );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap-theme.css', array( ), false, 'all' );
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array( ), false, 'all' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array( ), false, 'all' );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( ), false, 'all' );
		wp_enqueue_style( 'fashion-store-style', get_stylesheet_uri() );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fashion_store_lite_scripts' );
require get_template_directory() . '/inc/template-tags.php';
add_action( 'after_setup_theme', 'fashion_store_lite_woocommerce_support' );
function fashion_store_lite_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
function woocommerce_output_related_products() {
    $args = array('posts_per_page' => 3, 'columns' => 3,'orderby' => 'rand' );
    woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );}
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {	function loop_columns() {
		return 3; // 3 products per row	
}}
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

function fashion_store_lite_menu() {
	add_theme_page('Fashion Store Lite Setup', 'Menu - Help', 'edit_theme_options', 'fashion-store-lite', 'fashion_store_lite_menu_page');
}
add_action('admin_menu', 'fashion_store_lite_menu');
function fashion_store_lite_menu_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'Fashion Store Lite', 'fashion-store-lite' ) . '</h1></ceter>
<br><br><br>
	<center><h1>' . __( 'Max Mega Menu', 'fashion-store-lite' ) . '</h1></ceter>
<br>
Installation:<br>
1. Go to the Plugins Menu in WordPress<br>
2. Search for "Max Mega Menu"<br>
3. Click "Install"<br><br>

<h2><center>' . __( 'Documentation', 'fashion-store-lite' ) . '  <a href="https://www.megamenu.com/documentation/mega-menus/">' . __( 'https://www.megamenu.com/documentation/mega-menus/', 'fashion-store-lite' ) . '</a></center></h2><br><br>

<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-1.jpg"></center>
<br><br>
<h2><center>' . __( 'Copy Paste code', 'fashion-store-lite' ) . '  <a href="https://rgb-classic.com/fashion-store-lite-documentation/">' . __( 'https://rgb-classic.com/fashion-store-lite-documentation/', 'fashion-store-lite' ) . '</a></center></h2><br><br>

<br>
<center><img src="' . get_template_directory_uri() . '/images/mega-menu-2.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/mega-menu-3.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-2.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-3.jpg"></center>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/max-mega-menu-4.jpg"></center>
<br>


';
}


function fashion_store_lite_translation_menu() {
	add_theme_page('Fashion Store Lite Setup', 'Translation', 'edit_theme_options', 'fashion-store-lite-translation', 'fashion_store_lite_menu_translation_page');
}
add_action('admin_menu', 'fashion_store_lite_translation_menu');
function fashion_store_lite_menu_translation_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'Fashion Store Lite', 'fashion-store-lite' ) . '</h1></ceter>
<br><br><br>
	<center><h1>' . __( 'Translation', 'fashion-store-lite' ) . '</h1></ceter>
<br>
If you have found a mistake in the translation or if you want to add a new language, please translate the text into your language and we will add it to the theme.
<h2><center>  <a href="https://rgb-classic.com/translation/">' . __( 'https://rgb-classic.com/translation/', 'fashion-store-lite' ) . '</a></center></h2><br>
We will add your translation and notify you by email.
<br>
';
}










function fashion_store_lite_pro_menu() {
	add_theme_page('Fashion Store Lite Setup', 'Free vs PRO', 'edit_theme_options', 'fashion-store-lite-pro', 'fashion_store_lite_menu_pro_page');
}
add_action('admin_menu', 'fashion_store_lite_pro_menu');
function fashion_store_lite_menu_pro_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'free vs PRO', 'fashion-store-lite' ) . '</h1></ceter>
<br><br><br>
<h2><center>  <a href="https://rgb-classic.com/product/fashion-store/">' . __( 'Fashion Store PRO', 'fashion-store-lite' ) . '</a></center></h2><br>
<br>
Thank you very much for choosing our template. In acknowledgement thereof, we would like to offer a 10% discount on purchase of an extended version. <br>
Discount coupon is valid until September 31, 2017. Here you will find a description of how you can use this coupon.
<h2><center>  <a href="https://rgb-classic.com/discount/">' . __( 'https://rgb-classic.com/discount/', 'fashion-store-lite' ) . '</a></center></h2><br>
<h1><center>rgb10%</center></h1><br>
</br></br></br>


<center><img src="' . get_template_directory_uri() . '/images/fs-hp1.jpg"></center>
</br>
<center><img src="' . get_template_directory_uri() . '/images/fs-color-hp1.jpg"></center>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
<center>Various options for sidebar display (Left, Right, Content) </center><br>
<center><img src="' . get_template_directory_uri() . '/images/scr-fashion-store-s.jpg"></center>
</br>
</br>
<center>Visual Composer. Best visual designer which allows to easily and simply create nice pages without having programming skills. <br>
It is used to create corporate websites, online stores, landing pages. </center><br><br>
<center><img src="' . get_template_directory_uri() . '/images/vcomposer.jpg"></center>
</br>
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



<h2><center>  <a href="https://rgb-classic.com/product/fashion-store/">' . __( 'Fashion Store PRO', 'fashion-store-lite' ) . '</a></center></h2><br>
</br>
<center><img src="' . get_template_directory_uri() . '/images/fashion-store-free-pro.jpg"></center>



';
}

add_action( 'after_setup_theme', 'fashion_store_lite' );
 
function fashion_store_lite() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}