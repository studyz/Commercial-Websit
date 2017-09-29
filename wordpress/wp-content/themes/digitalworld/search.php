<?php get_header();?>
<?php
/*Blog layout*/
$digitalworld_blog_layout = digitalworld_option('digitalworld_blog_layout','left');

/*Blog settinglist*/
$digitalworld_blog_list_style = digitalworld_option('digitalworld_blog_list_style','');
$digitalworld_blog_list_columns = digitalworld_option('digitalworld_blog_list_columns',3);

/*Main container class*/
$digitalworld_main_container_class = array();
$digitalworld_main_container_class[] = 'main-container';
if( $digitalworld_blog_layout == 'full'){
	$digitalworld_main_container_class[] = 'no-sidebar';
}else{
	$digitalworld_main_container_class[] = $digitalworld_blog_layout.'-slidebar';
}


$digitalworld_main_content_class = array();
$digitalworld_main_content_class[] = 'main-content';
if( $digitalworld_blog_layout == 'full' ){
	$digitalworld_main_content_class[] ='col-sm-12';
}else{
	$digitalworld_main_content_class[] = 'col-lg-9 col-md-9 col-sm-8';
}

$digitalworld_slidebar_class = array();
$digitalworld_slidebar_class[] = 'sidebar';
if( $digitalworld_blog_layout != 'full'){
	$digitalworld_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4';
}

?>

<div class="<?php echo esc_attr( implode(' ', $digitalworld_main_container_class) );?>">
	<div class="container">
        <?php
        if( function_exists('breadcrumb_trail')){
          $args = array(
			'container'       => 'nav',
			'before'          => '',
			'after'           => '',
			'show_on_front'   => true,
			'network'         => false,
			'show_title'      => true,
			'show_browse'     => false,
			'labels'          => array(),
			'post_taxonomy'   => array(),
			'echo'            => true
		  );
          breadcrumb_trail($args);  
        } ?>
 
		<div class="row">
			<div class="<?php echo esc_attr( implode(' ', $digitalworld_main_content_class) );?>">
				<!-- Main content -->
				<?php
				if( $digitalworld_blog_list_style == "grid" || $digitalworld_blog_list_style == "masonry"){
					get_template_part( 'templates/blogs/blog','grid' );
				}else{
					get_template_part( 'templates/blogs/blog','list' );
				}
				?>
				<!-- ./Main content -->
			</div>
			<?php if( $digitalworld_blog_layout != "full" ):?>
			<div class="<?php echo esc_attr( implode(' ', $digitalworld_slidebar_class) );?>">
				<?php get_sidebar();?>
			</div>
			<?php endif;?>
		</div>


	</div>
</div>
<?php get_footer();?>