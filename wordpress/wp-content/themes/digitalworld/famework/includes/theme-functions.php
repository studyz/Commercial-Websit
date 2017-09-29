<?php
if( !function_exists('digitalworld_init')){
    function digitalworld_init() {
        $digitalworld_dev_mode = digitalworld_option('digitalworld_dev_mode',0);
        $dev_mode = false;
        if( $digitalworld_dev_mode == 1 ){
            $dev_mode = true;
        }
        define( 'DIGITALWORLD_THEME_VERSION', '1.0' );
        define( 'DIGITALWORLD_DEV_MODE', $dev_mode );
    }
}


add_action( 'init', 'digitalworld_init' );

add_action('init', 'digitalworld_StartSession', 1);
function digitalworld_StartSession() {
    if(!session_id()) {
        session_start();
    }
}

/* BODY CLASS */
add_filter('body_class', 'digitalworld_body_class');
if( !function_exists( 'digitalworld_body_class' ) ){

    function digitalworld_body_class( $classes ){
        /*Set demo home page*/
        if( isset( $_GET['digitalworld_is_home'] ) && $_GET['digitalworld_is_home'] == "true"){
            $classes[] = 'home';
        }
        if( defined( 'DIGITALWORLD_DEV_MODE' ) && DIGITALWORLD_DEV_MODE == true){
            $digitalworld_page_type = digitalworld_get_post_meta(get_the_ID(),'digitalworld_page_type','page');
            if( $digitalworld_page_type == 'homepage'){
                $classes[] = 'home';
            }
        }
        /* Custom page class */
        if( is_page() ){
            $digitalworld_page_extra_class = digitalworld_get_post_meta( get_the_ID(), 'digitalworld_page_extra_class', '' );
            $classes[] = $digitalworld_page_extra_class;
        }
        $digitalworld_used_header = digitalworld_option('digitalworld_used_header',1);
        $digitalworld_site_layout = digitalworld_option('digitalworld_site_layout','full');
        $classes[] = $digitalworld_site_layout;
        $classes[] = 'digitalworld_used_header_'.$digitalworld_used_header;
        $my_theme = wp_get_theme();
        $classes[] = $my_theme->get( 'Name' )."-".$my_theme->get( 'Version' );
        return $classes;
    }
}
//GET DEMO OPTIONS
add_action( 'wp', 'digitalworld_get_theme_options_json', 20 );
if( !function_exists( 'digitalworld_get_theme_options_json' ) ){
    function digitalworld_get_theme_options_json(){
        global $digitalworld;
        if(! is_front_page() && defined( 'DIGITALWORLD_DEV_MODE' ) && DIGITALWORLD_DEV_MODE == true ){
            $page_slug = get_post_field( 'post_name', get_post() );
            if( is_page( $page_slug ) ){
                $option_files = get_template_directory() . '/famework/settings/theme-options/' . $page_slug . '.json';
                if( file_exists ( $option_files ) ){
                   // $option_content = @file_get_contents( $option_files );
                    $url = get_template_directory_uri() . '/famework/settings/theme-options/' . $page_slug . '.json';
                    $theme_options_url = untrailingslashit( $url );
                    $option_content = wp_remote_get( $theme_options_url );

                    if ( !empty( $option_content ) ) {
                        $option_content = $option_content['body'];
                        $options_configs = json_decode( $option_content, true );
                        if( is_array( $options_configs ) && !empty( $options_configs ) ){
                            $digitalworld = $options_configs;
                        }
                    }
                }
            }
        }
    }
}

if ( ! function_exists( 'digitalworld_option' ) ){
    /**
     * Function get option form Theme Options
     *
     * @since digitalworld 1.0
     * @author ReaApple
     **/
    function digitalworld_option( $option = false, $default = false ){
        if( isset( $_GET[$option] )){
            return $_GET[$option];
        }
        global $digitalworld;

        if($option === FALSE){
            return FALSE;
        }
        if(isset($digitalworld[$option]) && $digitalworld[$option] != ''){
            return $digitalworld[$option];
        }else{
            return $default;
        }
    }
}


if( !function_exists('digitalworld_get_header')){
    /**
     * Function get the header form template
     *
     * @since digitalworld 1.0
     * @author ReaApple
     **/
    function digitalworld_get_header(){
        $digitalworld_used_header =digitalworld_option('digitalworld_used_header','style-01');
        get_template_part( 'templates/headers/header',$digitalworld_used_header );
    }
}


if( ! function_exists( 'digitalworld_get_logo') ){
    /**
     * Function get the site logo
     *
     * @since digitalworld 1.0
     * @author ReaApple
     **/
    function digitalworld_get_logo(){
        $default_logo = array(
            'url'       => get_template_directory_uri() . '/images/logo.png',
            'id'        => '',
            'width'     => '',
            'height'    => '',
            'thumbnail' => ''
        );
        $logo = digitalworld_option('digitalworld_logo',$default_logo);

        $logo_url = $logo['url'];

        $html = '<a href="'.esc_url( get_home_url() ).'"><img alt="'.esc_attr( get_bloginfo('name') ).'" src="'.esc_url($logo_url).'" class="_rw" /></a>';
        echo apply_filters( 'digitalworld_site_logo', $html );
    }
}

if( ! function_exists('digitalworld_search_form') ){
    /**
     * Function get the search form template
     *
     * @since digitalworld 1.0
     * @author ReaApple
     **/
    function digitalworld_search_form($suffix='') {
        get_template_part('template_parts/search', 'form'.$suffix);
    }
}
if( !function_exists('digitalworld_serch_from_mobile') ){
    function digitalworld_serch_from_mobile(){
        ?>
        <form id="block-search-mobile" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>" class="block-search-mobile">
            <?php if( class_exists( 'WooCommerce' ) ): ?>
                <input type="hidden" name="post_type" value="product" />
                <input type="hidden" name="taxonomy" value="product_cat">
            <?php endif; ?>
            <div class="form-content">
                <a href="#" class="close-block-serach"><span class="icon fa fa-times"></span></a>
                <div class="inner">
                    <input type="text" class="input" name="s" value ="<?php echo esc_attr( get_search_query() );?>" placeholder="<?php esc_html_e('Search...','digitalworld');?>">
                    <button class="btn-search" type="submit"><span class="flaticon-magnifying-glass"></span></button>
                </div>
            </div>
        </form><!-- block search -->
        <?php
    }
}





if ( ! function_exists( 'digitalworld_paging_nav' ) ){
    /**
     * Display navigation to next/previous set of posts when applicable.
     *
     * @since Boutique 1.0
     *
     * @global WP_Query   $wp_query   WordPress Query object.
     * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
     */
    function digitalworld_paging_nav() {
        global $wp_query, $wp_rewrite;
        // Don't print empty markup if there's only one page.
        if ( $wp_query->max_num_pages < 2 ) {
            return;
        }
        echo get_the_posts_pagination( array(
            'screen_reader_text' => '&nbsp;',
            'before_page_number' => '',
        ) );
    }
}


if ( !function_exists( 'digitalworld_resize_image' ) ) {
    /**
     * @param int $attach_id
     * @param string $img_url
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @param bool $place_hold          Using place hold image if the image does not exist
     * @param bool $use_real_img_hold   Using real image for holder if the image does not exist
     * @param string $solid_img_color   Solid placehold image color (not text color). Random color if null
     *
     * @since 1.0
     * @return array
     */
    function digitalworld_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
        /*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
        if ( is_singular() && !$attach_id ) {
            if ( has_post_thumbnail() && !post_password_required() ) {
                $attach_id = get_post_thumbnail_id();
            }
        }
        /*this is an attachment, so we have the ID*/
        $image_src = array();
        if ( $attach_id ) {
            $image_src = wp_get_attachment_image_src( $attach_id, 'full' );
            $actual_file_path = get_attached_file( $attach_id );
            /*this is not an attachment, let's use the image url*/
        } else if ( $img_url ) {
            $file_path = str_replace( get_site_url(), get_home_path(), $img_url );
            $actual_file_path = rtrim( $file_path, '/' );
            if ( !file_exists( $actual_file_path ) ) {
                $file_path = parse_url( $img_url );
                $actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
            }
            if ( file_exists( $actual_file_path ) ) {
                $orig_size = getimagesize( $actual_file_path );
                $image_src[0] = $img_url;
                $image_src[1] = $orig_size[0];
                $image_src[2] = $orig_size[1];
            }
            else{
                $image_src[0] = '';
                $image_src[1] = 0;
                $image_src[2] = 0;
            }
        }
        if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
            $file_info = pathinfo( $actual_file_path );
            $extension = '.' . $file_info['extension'];
            /*the image path without the extension*/
            $no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];
            $cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
            /*checking if the file size is larger than the target size*/
            /*if it is smaller or the same size, stop right here and return*/
            if ( $image_src[1] > $width || $image_src[2] > $height ) {
                /*the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)*/
                if ( file_exists( $cropped_img_path ) ) {
                    $cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
                    $vt_image = array(
                        'url' => $cropped_img_url,
                        'width' => $width,
                        'height' => $height
                    );
                    return $vt_image;
                }

                if ( $crop == false ) {
                    /*calculate the size proportionaly*/
                    $proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
                    $resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
                    /*checking if the file already exists*/
                    if ( file_exists( $resized_img_path ) ) {
                        $resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
                        $vt_image = array(
                            'url' => $resized_img_url,
                            'width' => $proportional_size[0],
                            'height' => $proportional_size[1]
                        );
                        return $vt_image;
                    }
                }
                /*no cache files - let's finally resize it*/
                $img_editor = wp_get_image_editor( $actual_file_path );
                if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => ''
                    );
                }
                $new_img_path = $img_editor->generate_filename();
                if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => ''
                    );
                }
                if ( ! is_string( $new_img_path ) ) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => ''
                    );
                }
                $new_img_size = getimagesize( $new_img_path );
                $new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
                /*resized output*/
                $vt_image = array(
                    'url' => $new_img,
                    'width' => $new_img_size[0],
                    'height' => $new_img_size[1]
                );
                return $vt_image;
            }
            /*default output - without resizing*/
            $vt_image = array(
                'url' => $image_src[0],
                'width' => $image_src[1],
                'height' => $image_src[2]
            );
            return $vt_image;
        }
        else {
            if ( $place_hold ) {
                $width = intval( $width );
                $height = intval( $height );
                /*Real image place hold (https://unsplash.it/)*/
                if ( $use_real_img_hold ) {
                    $random_time = time() + rand( 1, 100000 );
                    $vt_image = array(
                        'url' => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
                        'width' => $width,
                        'height' => $height
                    );
                }
                else{
                    $vt_image = array(
                        'url' => 'http://placehold.it/' . $width . 'x' . $height,
                        'width' => $width,
                        'height' => $height
                    );
                }
                return $vt_image;
            }
        }
        return false;
    }
}


if( ! function_exists( 'digitalworld_get_post_meta' ) ) {
    /**
     * Function get post meta
     *
     * @since digitalworld 1.0
     * @author RedApple
     */
    function digitalworld_get_post_meta( $post_id, $key, $default = "" ){

        $meta = get_post_meta( $post_id, $key, true );
        if($meta !=''){
            return $meta;
        }
        return $default;
    }
}


// POST THUMBNAIL
if( !function_exists( 'digitalworld_post_thumbnail' )){
    /**
     * Function display post thumb
     *
     * @since digitalworld 1.0
     * @author RedApple
     */
    function digitalworld_post_thumbnail(){
        $thumb_class           = array();
        $digitalworld_blog_layout     = digitalworld_option('digitalworld_blog_layout','left');
        $digitalworld_single_layout = digitalworld_option('digitalworld_single_layout','left');
        $digitalworld_blog_list_style = digitalworld_option('digitalworld_blog_list_style','list');
        $digitalworld_blog_placehold  = digitalworld_option('digitalworld_blog_placehold','no');
        $digitalworld_blog_lg_items   = digitalworld_option('digitalworld_blog_lg_items',4);

        if( $digitalworld_blog_list_style == 'grid'){
            $digitalworld_blog_placehold ='yes';
        }

        if( $digitalworld_blog_placehold == 'no' && !has_post_thumbnail()){
            return false;
        }

        $crop    = false;

        if( $digitalworld_blog_layout == 'full'){
            $thumb_w = 1170;
            $thumb_h = 888;
            $crop    = true;
        }else{
            $thumb_w = 870;
            $thumb_h = 661;
            $crop    = true;
        }

        if( $digitalworld_blog_list_style == 'grid'){
            $thumb_w = 720;
            $thumb_h = 428;
            $crop    = true;
        }

        if( is_single()){
            if( $digitalworld_single_layout == 'full'){
                $thumb_w = 1170;
                $thumb_h = 462;
                $crop    = false;
            }else{
                $thumb_w = 870;
                $thumb_h = 344;
                $crop    = false;
            }
        }
        if( $digitalworld_blog_list_style == 'grid' || $digitalworld_blog_list_style =="masonry" || is_single()){
            $thumb_class[]='banner-opacity';
        }else{
            $thumb_class[]= 'gray';
        }
        if( is_single()){
            $thumb_class = array('gray');
        }
        $image = digitalworld_resize_image( get_post_thumbnail_id(), null, $thumb_w, $thumb_h, $crop, true, false );
        ?>
        <?php if( is_single()):?>
            <img width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo esc_attr( $image['url'] ) ?>" alt="<?php the_title(); ?>" />
        <?php else:?>
            <a class="thumb-link banner-effect banner-effect5" href="<?php the_permalink(); ?>">
                <img width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo esc_attr( $image['url'] ) ?>" alt="<?php the_title(); ?>" />
            </a>
        <?php endif;?>
        <?php
    }
}


if ( ! function_exists( 'digitalworld_comment_nav' ) ){
    /**
     * Display navigation to next/previous comments when applicable.
     *
     * @since Digitalworld 1.0
     * @author RedApple
     */
    function digitalworld_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'digitalworld' ); ?></h2>
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'digitalworld' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;
                    if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'digitalworld' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
            <?php
        endif;
    }
}
/*Comment Layout*/

if( !function_exists('digitalworld_custom_comment') ){
    /**
     * Display comment teplate.
     *
     * @since Digitalworld 1.0
     * @author RedApple
     */
    function digitalworld_custom_comment($comment, $args, $depth)
    {
        if ( 'div' === $args['style'] ) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-content">
            <div class="head">
                <?php printf( __( '<span class="author">%s</span>' ,'digitalworld'), get_comment_author() ); ?>
                <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
                        <?php
                        /* translators: 1: date, 2: time */
                        printf( esc_html__('%1$s at %2$s','digitalworld'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( esc_html__( '(Edit)','digitalworld' ), '  ', '' );
                    ?>
                </div>
            </div>
            <div class="coment-text">
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.','digitalworld' ); ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
        </div>
        <?php if ( 'div' != $args['style'] ) : ?>
        </div>
    <?php endif; ?>
        <?php
    }
}

if( !function_exists('digitalworld_get_footer')){
    function digitalworld_get_footer(){
        $digitalworld_footer_style = digitalworld_option('digitalworld_footer_style','');
        $digitalworld_template_style = digitalworld_get_post_meta( $digitalworld_footer_style, 'digitalworld_template_style', 'default' );
        ob_start();
        $query = new WP_Query( array( 'p' => $digitalworld_footer_style , 'post_type' => 'footer', 'posts_per_page' => 1 ) );
        if( $query->have_posts() ):
            while( $query->have_posts() ): $query->the_post(); ?>
                <?php if($digitalworld_template_style == 'default'):  ?>
                    <footer class="footer default">
                        <div class="container">
                            <?php the_content();?>
                        </div>
                    </footer>
                <?php else: ?>
                    <?php get_template_part( 'templates/footers/footer',$digitalworld_template_style );  ?>
                <?php  endif; ?>
            <?php endwhile;
        endif;
        wp_reset_postdata();
        echo ob_get_clean();
    }
}

if( !function_exists('digitalworld_get_all_social')){
    function digitalworld_get_all_social(){
        $socials = array(
            'opt_twitter_link' => array(
                'name' => 'Twitter',
                'id'=>'opt_twitter_link',
                'icon'=>'<i class="fa fa-twitter"></i>'
            ),
            'opt_fb_link' => array(
                'name' => 'Facebook',
                'id'=>'opt_fb_link',
                'icon'=>'<i class="fa fa-facebook"></i>'
            ),
            'opt_google_plus_link' => array(
                'name' => 'Google plus',
                'id'=>'opt_google_plus_link',
                'icon'=>'<i class="fa fa-google-plus" aria-hidden="true"></i>'
            ),
            'opt_dribbble_link' => array(
                'name' => 'Dribbble',
                'id'=>'opt_dribbble_link',
                'icon'=>'<i class="fa fa-dribbble" aria-hidden="true"></i>'
            ),
            'opt_behance_link' => array(
                'name' => 'Behance',
                'id'=>'opt_behance_link',
                'icon'=>'<i class="fa fa-behance" aria-hidden="true"></i>'
            ),
            'opt_tumblr_link' => array(
                'name' => 'Tumblr',
                'id'=>'opt_tumblr_link',
                'icon'=>'<i class="fa fa-tumblr" aria-hidden="true"></i>'
            ),
            'opt_instagram_link' => array(
                'name' => 'Instagram',
                'id'=>'opt_instagram_link',
                'icon'=>'<i class="fa fa-instagram" aria-hidden="true"></i>'
            ),
            'opt_pinterest_link' => array(
                'name' => 'Pinterest',
                'id'=>'opt_pinterest_link',
                'icon'=>'<i class="fa fa-pinterest" aria-hidden="true"></i>'
            ),
            'opt_youtube_link'=> array(
                'name' => 'Youtube',
                'id'=>'opt_youtube_link',
                'icon'=>'<i class="fa fa-youtube" aria-hidden="true"></i>'
            ),
            'opt_vimeo_link' => array(
                'name' => 'Vimeo',
                'id'=>'opt_vimeo_link',
                'icon'=>'<i class="fa fa-vimeo" aria-hidden="true"></i>'
            ),
            'opt_linkedin_link' => array(
                'name' => 'Linkedin',
                'id'=>'opt_linkedin_link',
                'icon'=>'<i class="fa fa-linkedin" aria-hidden="true"></i>'
            ),
            'opt_rss_link' => array(
                'name' => 'RSS',
                'id'=>'opt_rss_link',
                'icon'=>'<i class="fa fa-rss" aria-hidden="true"></i>'
            )
        );
        return $socials;
    }
}
if( ! function_exists('digitalworld_social') ){
    function digitalworld_social( $social=''){
        $all_social = digitalworld_get_all_social();
        $social_link = digitalworld_option($social,'');
        $social_icon = $all_social[$social]['icon'];
        $social_name = $all_social[$social]['name'];
        echo balanceTags('<a class="social" target="_blank" href="'.esc_url($social_link).'" title ="'.esc_attr($social_name).'" >'.$social_icon.'</a>');
    }
}

if( !function_exists('digitalworld_check_demo_is_homepage')){
    function digitalworld_check_demo_is_homepage(){
        if( !defined('DIGITALWORLD_DEV_MODE')){
            return false;
        }
        if( defined( 'DIGITALWORLD_DEV_MODE' ) && DIGITALWORLD_DEV_MODE == true){
            if( is_page()){
                $page_type = digitalworld_get_post_meta( get_the_ID(), 'digitalworld_page_type', 'page' );
                if( $page_type == 'homepage'){
                    return true;
                }
            }
        }
        return false;
    }
}


//SET MENU DEMO
if( !function_exists( 'digitalworld_demo_menu_args' ) ){
    function digitalworld_demo_menu_args( $args ){

        if( !is_front_page() && defined( 'DIGITALWORLD_DEV_MODE' ) && DIGITALWORLD_DEV_MODE == true ){

            $page_slug = get_post_field( 'post_name', get_post() );

            if( is_page( $page_slug ) ){
                $menu_configs_file = get_template_directory() . '/famework/settings/menu-configs.php';
                if( file_exists( $menu_configs_file ) ){
                    $menu_configs = @include( $menu_configs_file );
                    if( is_array( $menu_configs ) && !empty( $menu_configs ) ){
                        if( array_key_exists( $page_slug, $menu_configs ) ){

                            $page_menu_config = $menu_configs[$page_slug];
                            foreach( $page_menu_config as $menu_theme_location=>$menu_menu_id ){
                                if( $args['theme_location'] == $menu_theme_location ){
                                    if( empty( $args['menu'] ) ){
                                        $args['menu'] = new stdClass();
                                    }

                                    $menu_data      = get_term_by( 'id', $menu_menu_id, 'nav_menu', OBJECT );
                                    $args['menu']   = $menu_data;
                                }
                            }

                        }
                    }
                }
            }

        }

        return $args;
    }
}
add_filter( 'wp_nav_menu_args', 'digitalworld_demo_menu_args' );