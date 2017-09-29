<?php
$digitalworld_page_used_sidebar = digitalworld_get_post_meta(get_the_ID(),'digitalworld_page_used_sidebar','widget-area');
?>
<?php if ( is_active_sidebar( $digitalworld_page_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area">
        <?php dynamic_sidebar( $digitalworld_page_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>
