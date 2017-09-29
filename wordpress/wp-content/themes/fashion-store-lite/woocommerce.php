<?php get_header(); ?>

<section class="main_content">
		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<div class="content-text">
						<div class="content-woocommerce">
							<div class="blog_item">
							

									<?php woocommerce_content(); ?>


							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<aside class="sidebar-right">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside>
				</div>

			</div>

		</div>

	</section>







































<?php

get_footer();