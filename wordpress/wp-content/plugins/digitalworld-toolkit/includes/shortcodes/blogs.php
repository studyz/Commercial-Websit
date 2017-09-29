<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_Blogs')){

    class Digitalworld_Toolkit_Shortcode_Blogs extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'blogs';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(

        );


        public static  function generate_css( $atts ){
            extract( $atts );
            $css = '';
            $top =  '-60';
            if( $atts['owl_navigation_position'] == 'nav-top-left' || $atts['owl_navigation_position'] == 'nav-top-right'){
                if( $atts['owl_navigation_position_top'] != ''){
                    $top = $atts['owl_navigation_position_top'];
                }
                $css .= '.'.$atts['blogs_custom_id'] .' .owl-nav{ top:'.$top.'px;} ';
            }
            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_blogs', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-blogs');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] =  $atts['blogs_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            $args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => $per_page,
                'suppress_filter'     => true,
                'orderby'             => $orderby,
                'order'               => $order
            );

            /* Get category id*/
            if ( $category_slug ) {
                $idObj = get_category_by_slug($category_slug);
                if (is_object($idObj)) {
                    $args['cat'] = $idObj->term_id;
                }
            }

            $posts = new WP_Query(apply_filters('trueshop_shortcode_posts_query', $args, $atts));

            $owl_settings = $this->generate_carousel_data_attributes('', $atts);
            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                <?php if ($posts->have_posts()): ?>
                    <?php if( $style == 'default'):?>
                        <div class="owl-carousel <?php echo esc_attr( $owl_navigation_position );?>" <?php echo $owl_settings; ?>>
                            <?php while ($posts->have_posts()): $posts->the_post(); ?>
                                <div class="blog-item">
                                    <div class="post-thumb">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php $image_thumb = digitalworld_resize_image(get_post_thumbnail_id(get_the_ID()), null, 350, 266, true, true, false); ?>
                                            <a class="<?php echo esc_attr( $image_effect )?>" href="<?php the_permalink() ?>">
                                                <img class="img-responsive" src="<?php echo esc_attr($image_thumb['url']); ?>"
                                                     width="<?php echo intval($image_thumb['width']) ?>"
                                                     height="<?php echo intval($image_thumb['height']) ?>"
                                                     alt="<?php the_title() ?>">
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                    <div class="post-info">
                                        <span class="time">
                                            <span class="day"><?php echo get_the_date('j');?></span>
                                            <?php echo get_the_date('M, j');?>
                                        </span>
                                        <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <div class="blog-des"><?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 10, __('...', 'digitalworld-toolkit')); ?></div>
                                        <div class="post-metas">
                                            <span class="count-comment">
                                                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                                <?php comments_number(
                                                    esc_html__('0 comment', 'digitalworld-toolkit'),
                                                    esc_html__('1 comment', 'digitalworld-toolkit'),
                                                    esc_html__('% comments', 'digitalworld-toolkit')
                                                );
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif;?>
                <?php else:?>
                    <p><?php esc_html_e('No Post !','digitalworld-toolkit');?></p>
                <?php endif;?>
            </div>

            <?php
            $html = ob_get_clean();
            return apply_filters( 'digitalworld_toolkit_shortcode_blogs', force_balance_tags( $html ), $atts ,$content );
        }
    }
}