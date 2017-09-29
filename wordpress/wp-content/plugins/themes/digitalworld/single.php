<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package KuteTheme
 * @subpackage digitalworld
 * @since digitalworld 1.0
 */

get_header(); 
?>
<?php
/*Single post layout*/
$digitalworld_blog_layout = digitalworld_option('digitalworld_single_layout','left');


/*Main container class*/
$digitalworld_main_container_class = array();
$digitalworld_main_container_class[] = 'main-container';
if( $digitalworld_blog_layout == 'full'){
    $digitalworld_main_container_class[] = 'no-sidebar';
}else{
    $digitalworld_main_container_class[] = $digitalworld_blog_layout.'-slidebar';
}


$digitalworld_main_content_class = array();
$digitalworld_main_content_class[] = 'main-content';
if( $digitalworld_blog_layout == 'full' ){
    $digitalworld_main_content_class[] ='col-sm-12';
}else{
    $digitalworld_main_content_class[] = 'col-lg-9 col-md-9 col-sm-8';
}

$digitalworld_slidebar_class = array();
$digitalworld_slidebar_class[] = 'sidebar';
if( $digitalworld_blog_layout != 'full'){
    $digitalworld_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4';
}
?>
<div class="<?php echo esc_attr( implode(' ', $digitalworld_main_container_class) );?>">
    <div class="container">
        <?php get_template_part('template_parts/part','breadcrumb');?>
        <div class="row">
            <div class="<?php echo esc_attr( implode(' ', $digitalworld_main_content_class) );?>">
                <?php
                while (have_posts()): the_post();
                get_template_part( 'templates/blogs/blog','single' );

                /*If comments are open or we have at least one comment, load up the comment template.*/
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                endwhile;
                ?>
            </div>
            <?php if( $digitalworld_blog_layout != "full" ):?>
            <div class="<?php echo esc_attr( implode(' ', $digitalworld_slidebar_class) );?>">
                <?php get_sidebar();?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php get_footer(); ?>