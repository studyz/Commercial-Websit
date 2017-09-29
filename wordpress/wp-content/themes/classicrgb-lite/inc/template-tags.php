<?php

if ( ! function_exists( 'classicrgb_lite_posted_on' ) ) :

function classicrgb_lite_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'classicrgb-lite' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'classicrgb-lite' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'classicrgb_lite_entry_footer' ) ) :



function classicrgb_lite_entry_footer() {

	if ( 'post' === get_post_type() ) {

		$categories_list = get_the_category_list( esc_html__( ', ', 'classicrgb-lite' ) );
		if ( $categories_list && classicrgb_lite_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'classicrgb-lite' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}


		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'classicrgb-lite' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( ' Tagged: %1$s', 'classicrgb-lite' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';

		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'classicrgb-lite' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(

			esc_html__( 'Edit %s', 'classicrgb-lite' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;






function classicrgb_lite_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'classicrgb_lite_categories' ) ) ) {

		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			'number'     => 2,
		) );


		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'classicrgb_lite_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {

		return true;
	} else {

		return false;
	}
}




function classicrgb_lite_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	delete_transient( 'classicrgb_lite_categories' );
}
add_action( 'edit_category', 'classicrgb_lite_category_transient_flusher' );
add_action( 'save_post',     'classicrgb_lite_category_transient_flusher' );
