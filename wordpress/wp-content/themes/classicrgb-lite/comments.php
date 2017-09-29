<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
	        <?php
	            printf( _n( 'Comments (%1$s)', '%1$s Comments So Far', get_comments_number(), 'classicrgb-lite' ),
	            number_format_i18n( get_comments_number() ) );
	        ?>
		</h2>



		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
				) );
			?>
		</ol>


	<?php endif;?>

	<?php
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'classicrgb-lite' ); ?></p>
	<?php endif; ?>
<?php the_comments_pagination( ); ?>
	<?php comment_form(); ?>

</div>
