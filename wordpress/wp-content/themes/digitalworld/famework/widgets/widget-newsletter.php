<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Pages widget class
 *
 * @since 1.0
 */
class Digitalworld_Widget_Newsletter extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'digitalworld_widget_newsletter',
            'description' => esc_attr__( 'Newsletter Box.', 'digitalworld' ) );
        parent::__construct( 'digitalworld_widget_newsletter', esc_attr__('Digitalworld: Newsletter', 'digitalworld' ), $widget_ops );
    }

    public function widget( $args, $instance ) {
        echo apply_filters( 'newsletter_before_widget', $args['before_widget'] );


        $title   = ( isset( $instance[ 'title' ] )   && $instance[ 'title' ] ) ? esc_html( $instance[ 'title' ] ) : '';
        $subtitle   = ( isset( $instance[ 'subtitle' ] )   && $instance[ 'subtitle' ] ) ? $instance[ 'subtitle' ]  : '';


        if( $title ){
            echo apply_filters( 'newsletter_before_title', $args['before_title'] ) ;
            echo esc_html( $title );
            echo apply_filters( 'newsletter_after_title', $args['after_title'] ) ;
        }
        if( $subtitle ){
            echo $subtitle;
        }
        ?>
        <div class="newsletter-form-wrap">
            <input class="email" type="email" name="email" placeholder="<?php esc_html_e('Enter your email...','digitalworld');?>">
            <button type="submit" name="submit_button" class="btn-submit submit-newsletter"><?php esc_html_e('Sign Up','digitalworld');?></button>
        </div>
        <?php

        echo apply_filters( 'newsletter_after_widget', $args[ 'after_widget' ] ) ;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $new_instance;

        $instance[ 'title' ]      = ( isset( $new_instance[ 'title' ] ) && $new_instance[ 'title' ] )? esc_html( $new_instance[ 'title' ] )      : '';
        $instance[ 'subtitle' ]      = ( isset( $new_instance[ 'subtitle' ] ) && $new_instance[ 'title' ] )?  $new_instance[ 'subtitle' ]      : '';

        return $instance;
    }

    public function form( $instance ) {
        //Defaults
        $title      = ( isset( $instance[ 'title' ] ) && $instance[ 'title' ] ) ? esc_html( $instance[ 'title' ] ) : '';
        $subtitle      = ( isset( $instance[ 'subtitle' ] ) && $instance[ 'subtitle' ] ) ?  $instance[ 'subtitle' ] : '';
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'digitalworld'); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Sub Title:', 'digitalworld'); ?></label>
            <textarea class="widefat" name="<?php echo esc_attr( $this->get_field_name('subtitle') ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" cols="30" rows="10"><?php echo $subtitle; ?></textarea>
        </p>
        <?php
    }

}
add_action( 'widgets_init', 'Digitalworld_Widget_Newsletter');

function Digitalworld_Widget_Newsletter(){
    register_widget( 'Digitalworld_Widget_Newsletter' );
}