<?php
$digitalworld_blog_used_sidebar = digitalworld_option( 'digitalworld_blog_used_sidebar', 'widget-area' );
if( is_single()){
    $digitalworld_blog_used_sidebar = digitalworld_option( 'digitalworld_single_used_sidebar', 'widget-area' );
}
?>
<?php if ( is_active_sidebar( $digitalworld_blog_used_sidebar ) ) : ?>
<div id="widget-area" class="widget-area sidebar-blog">
	<?php dynamic_sidebar( $digitalworld_blog_used_sidebar ); ?>
</div><!-- .widget-area -->
<?php endif; ?>
