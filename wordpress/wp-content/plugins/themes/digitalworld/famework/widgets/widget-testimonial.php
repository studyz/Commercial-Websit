<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Pages widget class
 *
 * @since 1.0
 */
class Digitalworld_Widget_Testimonial extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'widget_kt_testimonial',
            'description' => esc_attr__( 'Testimonial Carousel on sidebar.', 'digitalworld' ) );
        parent::__construct( 'widget_kt_testimonial', esc_attr__('Digitalworld: Testimonial', 'digitalworld' ), $widget_ops );
    }

    public function widget( $args, $instance ) {
        echo apply_filters( 'kt_wg_before_widget', $args['before_widget'] );


        $title   = ( isset( $instance[ 'title' ] )   && $instance[ 'title' ] ) ? esc_html( $instance[ 'title' ] ) : '';


        if( $title ){
            echo apply_filters( 'kt_wg_before_title', $args['before_title'] ) ;
            echo esc_html( $title );
            echo apply_filters( 'kt_wg_after_title', $args['after_title'] ) ;
        }
        $query = array(
            'post_type' => 'testimonial',
        );
        $testimonials = new WP_Query( $query );
        if($testimonials->have_posts()):
            ?>
            <!-- Testimonials -->
            <div class="block left-module container-testimonials">
                <div class="block_content">
                    <ul class="testimonials owl-carousel" data-items="1" data-nav="true" data-dots="true">
                        <?php while($testimonials->have_posts()): $testimonials->the_post(); ?>
                            <?php
                            $name = digitalworld_get_post_meta(get_the_ID(),'digitalworld_testimonial_name','');
                            $position = digitalworld_get_post_meta(get_the_ID(),'digitalworld_testimonial_position','');
                            ?>
                            <li>
                                <div class="client-avarta">
                                    <?php $image_thumb = digitalworld_resize_image(get_post_thumbnail_id(get_the_ID()), null, 118, 118, true, true, false); ?>
                                    <a class="" href="<?php the_permalink() ?>">
                                        <img class="img-responsive" src="<?php echo esc_attr($image_thumb['url']); ?>"
                                             width="<?php echo intval($image_thumb['width']) ?>"
                                             height="<?php echo intval($image_thumb['height']) ?>"
                                             alt="<?php the_title() ?>">
                                    </a>
                                </div>
                                <?php if( $name ):?>
                                <div class="client-mane"><?php echo esc_html($name); ?></div>
                                <?php endif;?>
                                <?php if( $position ):?>
                                    <div class="client-position"><?php echo esc_html($position); ?></div>
                                <?php endif;?>
                                <div class="text">
                                    <?php the_content();  ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <!-- ./Testimonials -->
            <?php
        endif;
        wp_reset_postdata();
        echo apply_filters( 'kt_wg_after_widget', $args[ 'after_widget' ] ) ;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $new_instance;

        $instance[ 'title' ]      = ( isset( $new_instance[ 'title' ] )        && $new_instance[ 'title' ] )                ? esc_html( $new_instance[ 'title' ] )      : '';

        return $instance;
    }

    public function form( $instance ) {
        //Defaults
        $title      = ( isset( $instance[ 'title' ] ) && $instance[ 'title' ] ) ? esc_html( $instance[ 'title' ] ) : '';

        $autoplay   = ( isset( $instance[ 'autoplay' ] ) && $instance[ 'autoplay' ] ) ? "true" : "false";

        $loop       = ( isset( $instance[ 'loop' ] ) && $instance[ 'loop' ] ) ? "true" : "false";

        $slidespeed = ( isset( $instance[ 'slidespeed' ] ) && intval( $instance[ 'slidespeed' ] ) ) ? intval( $instance[ 'slidespeed' ] ) : 250;

        $number  = ( isset( $instance[ 'number' ] ) && intval( $instance[ 'number' ] ) > 0 )  ? intval( $instance[ 'number' ] )  : 3;

        $orderby = ( isset( $instance[ 'orderby' ] ) && $instance[ 'orderby' ] ) ? esc_attr( $instance[ 'orderby' ] ) : 'date';

        $order   = ( isset( $instance[ 'order' ] ) && $instance[ 'order' ] ) ? esc_attr( $instance[ 'order' ] )   : 'desc';
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'digitalworld'); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

}
add_action( 'widgets_init', 'Digitalworld_Widget_Testimonial');

function Digitalworld_Widget_Testimonial(){
    register_widget( 'Digitalworld_Widget_Testimonial' );
}