<?php

if (!class_exists('Digitalworld_Toolkit_Shortcode_categories')){

    class Digitalworld_Toolkit_Shortcode_categories extends  Digitalworld_Toolkit_Shortcode{
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'categories';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public  $default_atts  = array(

        );


        public static function generate_css( $atts ){
           // $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_categories', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css = '';
            return $css;
        }


        public function output_html( $atts, $content = null ){
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('digitalworld_categories', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('digitalworld-categories');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] =  $atts['categories_custom_id'];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ){
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }

            ob_start();
            $term_link = get_term_link( $taxonomy,'product_cat');
            $prodcut_category = get_term_by('slug', $taxonomy, 'product_cat');
            $number = 0;
            if( $limit && is_numeric( $limit) && $limit > 0 ){
                $number = $limit;
            }
            if( $prodcut_category ){
                $args = array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'child_of'   => $prodcut_category->term_id,
                    'number'     => $number
                );
                $prodcut_category_sub  = get_terms( $args );
            }

            ?>
            <?php if( $style == 'style3'):?>
                <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                    <?php if( $title):?>
                        <h3 class="title"><?php echo esc_html( $title );?></h3>
                    <?php endif;?>
                    <?php
                    if( $product_cats ){
                        $product_cats_slug = explode(',',$product_cats);
                        $args = array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'slug' => $product_cats_slug
                        );
                        $prodcut_categories  = get_terms( $args );
                    }
                    ?>
                    <?php if( $prodcut_categories  && is_array($prodcut_categories) && count( $prodcut_categories ) > 0):?>
                        <ul class="list-category">
                            <?php foreach ($prodcut_categories as $cat):?>
                                <li><a href="<?php echo esc_url(get_term_link($cat->term_id));?>"><?php echo esc_html( $cat->name);?></a></li>
                            <?php endforeach;?>
                            <?php if( isset($link_all)):?>
                                <?php $item_link = vc_build_link($link_all); ?>
                                <?php if($item_link['url']): ?>
                                    <li>
                                        <a class="linkall" href="<?php echo esc_url($item_link['url']) ?>" <?php if($item_link['target']): ?> target="<?php echo esc_html($item_link['target']) ?>" <?php endif; ?>  <?php if($item_link['rel']): ?> rel="<?php echo esc_attr($item_link['rel']) ; ?>" <?php endif; ?>><?php echo esc_html($item_link['title']);?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endif;?>
                        </ul>
                    <?php endif;?>
                </div>
            <?php elseif( $style == 'style2'):?>
                <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                    <?php if ( $bg_cat ) : ?>
                        <div class="thumb">
                            <figure>
                                <?php $image_thumb = digitalworld_resize_image( $bg_cat, null, 1000, 1000, true, true, false ); ?>
                                <img  src="<?php echo esc_attr($image_thumb['url']); ?>" width="<?php echo intval($image_thumb['width']) ?>" height="<?php echo intval($image_thumb['height']) ?>" alt="">
                            </figure>
                        </div>
                    <?php endif; ?>
                    <?php if( $taxonomy ):?>
                        <div class="info">
                            <h3 class="title"><?php echo esc_html( $prodcut_category->name  ); ?></h3>
                            <a class="button" href="<?php echo esc_url($term_link) ?>"><?php esc_html_e('View More','digitalworld');?></a>
                            <?php if( $prodcut_category_sub ):?>
                                <ul class="sub-cat">
                                    <?php foreach ($prodcut_category_sub as $cat):?>
                                        <li><a href="<?php echo esc_url(get_term_link($cat->term_id));?>"><?php echo esc_html( $cat->name);?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>

                        </div>
                    <?php endif;?>
                </div>
            <?php else:?>
                <div class="<?php echo esc_attr( implode(' ', $css_class) );?>">
                    <?php if ( $bg_cat ) : ?>
                        <div class="thumb">
                            <figure>
                                <?php $image_thumb = digitalworld_resize_image( $bg_cat, null, 186, 186, true, true, false ); ?>
                                <img  src="<?php echo esc_attr($image_thumb['url']); ?>" width="<?php echo intval($image_thumb['width']) ?>" height="<?php echo intval($image_thumb['height']) ?>" alt="">
                            </figure>
                        </div>
                    <?php endif; ?>
                    <?php if( $taxonomy ):?>
                        <div class="info">
                            <h3 class="title"><?php echo esc_html( $prodcut_category->name  ); ?></h3>
                            <?php if( $prodcut_category_sub ):?>
                                <ul class="sub-cat">
                                    <?php foreach ($prodcut_category_sub as $cat):?>
                                        <li><a href="<?php echo esc_url(get_term_link($cat->term_id));?>"><?php echo esc_html( $cat->name);?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                            <a class="button" href="<?php echo esc_url($term_link) ?>"><?php esc_html_e('View More','digitalworld');?></a>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>
            <?php
            $html = ob_get_clean();
            return apply_filters( 'digitalworld_toolkit_shortcode_categories', force_balance_tags( $html ), $atts ,$content );
        }
    }
}