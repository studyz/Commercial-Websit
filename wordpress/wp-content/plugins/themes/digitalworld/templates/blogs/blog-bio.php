<?php $description = get_the_author_meta('description');?>
<?php  if( $description != "" ):?>
<div class="post-arthur">
    <div class="avata">
        <?php echo get_avatar( get_the_author_meta('email'), '170' ); ?>
    </div>
    <div class="des">
        <strong class="name"><?php the_author_posts_link(); ?></strong>
        <p><?php the_author_meta('description'); ?></p>
    </div>
</div>
<?php endif;?>
