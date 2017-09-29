<?php
$digitalworld_blog_placehold  = digitalworld_option('digitalworld_blog_placehold','no');
if( have_posts()){
	?>
    <div class="post-grid">
    	<div class="row post-items auto-clear">
    		<?php
            $digitalworld_blog_lg_items = digitalworld_option('digitalworld_blog_lg_items',4);
            $digitalworld_blog_md_items = digitalworld_option('digitalworld_blog_md_items',4);
            $digitalworld_blog_sm_items = digitalworld_option('digitalworld_blog_sm_items',3);
            $digitalworld_blog_xs_items = digitalworld_option('digitalworld_blog_xs_items',6);
            $digitalworld_blog_ts_items = digitalworld_option('digitalworld_blog_ts_items',12);
            
            $blog_item_class = 'post-item';
            $blog_item_class_col = '';
            $blog_item_class_col .=' col-lg-'.$digitalworld_blog_lg_items;
            $blog_item_class_col .=' col-md-'.$digitalworld_blog_md_items;
            $blog_item_class_col .=' col-sm-'.$digitalworld_blog_sm_items;
            $blog_item_class_col .=' col-xs-'.$digitalworld_blog_xs_items;
            $blog_item_class_col .=' col-ts-'.$digitalworld_blog_ts_items;

    		while( have_posts()){
    			?>
    			<?php
    			the_post();
    			?>
                <div class="<?php echo esc_attr($blog_item_class_col);?>">
                    <div <?php post_class('post-item');?>>
                        <?php if( has_post_thumbnail() || $digitalworld_blog_placehold =='yes' ): ?>
                            <div class="post-thumb">
                                <?php digitalworld_post_thumbnail( );?>
                            </div>
                        <?php endif;?>
                        <div class="post-item-info">
                            <h3 class="post-name">
                                <a href="<?php the_permalink();?>"><?php the_title();?></a>
                            </h3>
                            <div class="post-metas">
                                <?php
                                if ( is_sticky() && is_home() && ! is_paged() ) {
                                    printf( '<span class="sticky-post">%s</span>', esc_html__( 'Sticky', 'digitalworld' ) );
                                }
                                ?>
                                <span class="author"><?php esc_html_e('Posted by:','digitalworld');?> <?php the_author();?></span>
                                <span class="time">
                                    <span class="day"><?php echo get_the_date('j');?></span>
                                                <?php echo get_the_date('M, j');?>
                                </span>
                            </div>
                            <div class="post-excerpt"><?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 40, __('...', 'digitalworld')); ?></div>
                            <a href="<?php the_permalink();?>" class="button"><span class="text"><?php esc_html_e('Read more','digitalworld');?></span> <span class="icon flaticon-arrows-3"></span></a>
                        </div>
                    </div>
                </div>
                <?php
    			?>
    			<?php
    		}
    		?>
    	</div>
    </div>
	<?php
	digitalworld_paging_nav();
}else{
	get_template_part( 'content', 'none' );
}