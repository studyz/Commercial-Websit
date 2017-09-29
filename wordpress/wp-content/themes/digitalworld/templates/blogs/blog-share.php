<div class="post-item-share">
    <label><?php esc_html_e('Share:', 'digitalworld');?></label>
    <a title="<?php echo sprintf( esc_html__( 'Share "%s" on Facebook', 'digitalworld'), get_the_title() ); ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>&display=popup" target="_blank">
        <i class="fa fa-facebook"></i>
    </a>
    <a title="<?php echo sprintf( esc_html__( 'Post status "%s" on Twitter', 'digitalworld'), get_the_title() ); ?>" href="https://twitter.com/home?status=<?php the_permalink(); ?>" target="_blank">
        <i class="fa fa-twitter"></i>
    </a>
    <a title="<?php echo sprintf( esc_html__( 'Share "%s" on Google Plus', 'digitalworld'), get_the_title() ); ?>"  href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank">
        <i class="fa fa-google-plus"></i>
    </a>
    <a title="<?php echo sprintf( esc_html__( 'Pin "%s" on Pinterest', 'digitalworld'), get_the_title() ); ?>" href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url( get_the_post_thumbnail_url('full')); ?>&amp;description=<?php echo urlencode( get_the_excerpt() ); ?>" target="_blank">
        <i class="fa fa-pinterest"></i>
    </a>
    <a title="<?php echo sprintf( esc_html__( 'Share "%s" on LinkedIn', 'digitalworld'), get_the_title() ); ?>"  href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>&amp;summary=<?php echo urlencode( get_the_excerpt() ); ?>&amp;source=<?php echo urlencode( get_bloginfo( 'name' ) ); ?>" target="_blank">
        <i class="fa fa-linkedin"></i>
    </a>
</div>
