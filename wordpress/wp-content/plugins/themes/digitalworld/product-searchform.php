<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
    <p>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'digitalworld' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'digitalworld' ); ?>" />
	</p>
    <input class="button" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'digitalworld' ); ?>" />
	<input type="hidden" name="post_type" value="product" />
</form>