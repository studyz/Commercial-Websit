<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

while ( have_posts() ) : the_post(); ?>
<div class="catalog-product-view catalog-view_default">
<div class="product">
	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-5">
                <div class="product-media media-horizontal">
                    <?php do_action( 'yith_wcqv_product_image' ); ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-7">
                <div class="product-info-main summary entry-summary">
        			<div class="summary-content">
        				<?php do_action( 'yith_wcqv_product_summary' ); ?>
        			</div>
        		</div>
            </div>
        </div>
	</div>

</div>
</div>
<?php endwhile; // end of the loop.?>