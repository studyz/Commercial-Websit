<?php

if( !class_exists('Digitalworld_Meta_Box_Settings') ){
    class Digitalworld_Meta_Box_Settings{


        public  function __construct(){
            add_filter( 'rwmb_meta_boxes', array( __CLASS__, 'meta_boxes' ) );
        }

        /**
         * Register additional meta boxes.
         *
         * @param   array  $meta_boxes  Current meta boxes.
         *
         * @return  array
         */
        public static function meta_boxes( $meta_boxes ) {
            global $wp_registered_sidebars;
            $sidebars = array();
            foreach ( $wp_registered_sidebars as $sidebar ){
                $sidebars[  $sidebar['id'] ] =   $sidebar['name'];
            }
            $footer_style_options = array(
                'default'   =>  esc_html__('Default', 'digitalworld'),
            );
            $layoutDir = get_template_directory().'/templates/footers/';
            if(is_dir($layoutDir)){
                $files = scandir($layoutDir);
                if($files && is_array($files)){
                    $option = '';
                    foreach ($files as $file){
                        if ($file != '.' && $file != '..'){
                            $fileInfo = pathinfo($file);
                            if($fileInfo['extension'] == 'php' && $fileInfo['basename'] !='index.php'){
                                $file_data = get_file_data( $layoutDir.$file, array('Name'=>'Name') );
                                $file_name = str_replace('footer-', '', $fileInfo['filename']);
                                $footer_style_options[$file_name] = $file_data['Name'];
                            }
                        }
                    }
                }
            }


            /* Metabox for page*/
            $fields = array(
                array(
                    'id'       => 'digitalworld_page_layout',
                    'name'     => esc_html__( 'Page layout', 'digitalworld' ),
                    'type'     => 'image_select',
                    'options'          => array(
                        'full'  => get_template_directory_uri().'/images/1column.png',
                        'left'  => get_template_directory_uri() .'/images/2cl.png',
                        'right' => get_template_directory_uri() .'/images/2cr.png',
                    ),
                    'tab' => esc_html__('General', 'digitalworld'),
                    'std' => 'left'
                ),
                array(
                    'name'    => esc_html__( 'Sidebar for page layout', 'digitalworld' ),
                    'id'      => 'digitalworld_page_used_sidebar',
                    'type'    => 'select',
                    'show_option_none' => true,
                    'options' => $sidebars,
                    'desc'    => esc_html__( 'Setting sidebar in the area sidebar', 'digitalworld' ),
                    'dependency' => array(
                        'id'  => 'digitalworld_page_layout',
                        'value' => array( 'left', 'right' )
                    ),
                    'tab'     => esc_html__( 'General', 'digitalworld' ),

                ),
                array(
                    'name' => esc_html__( 'Extra page class', 'digitalworld' ),
                    'desc' => esc_html__( 'If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', 'digitalworld' ),
                    'id'   => 'digitalworld_page_extra_class',
                    'type' => 'text',
                    'tab'     => esc_html__( 'General', 'digitalworld' ),
                ),
            );
            $demo_options = array();
            if( defined( 'DIGITALWORLD_DEV_MODE' ) && DIGITALWORLD_DEV_MODE == true){
                $demo_options = array(
                    'name'             => __('Page type', 'digitalworld'),
                    'desc'             => __("This page can be used as homepage or default page", 'digitalworld'),
                    'id'               => 'digitalworld_page_type',
                    'type'             => 'select',
                    'std'          => 'page',
                    'options'          => array(
                        'page' =>__('Default page','digitalworld'),
                        'homepage' => __( 'Home page', 'digitalworld' ),
                    ),
                    'tab' => esc_html__('General', 'digitalworld'),
                );
                array_push($fields,$demo_options);
            }
            $meta_boxes[] = array(
                'id'         => 'digitalworld_page_option',
                'title'      => esc_html__('Page Options', 'digitalworld'),
                'post_types' => 'page',
                'fields'     => $fields
            );

            /*Meta box for footer */
            $meta_boxes[] = array(
                'id'         => 'digitalworld_footer_option',
                'title'      => esc_html__('Footer Options', 'digitalworld'),
                'post_types' => 'footer',
                'fields'     => array(
                    array(
                        'name' => esc_html__( 'Template', 'digitalworld' ),
                        'id'   => 'digitalworld_template_style',
                        'type' => 'select',
                        'options'  => $footer_style_options,
                        'std' => 'default',
                        'tab'  => esc_html__( 'Template', 'digitalworld' ),
                    ),
                ),
            );

            /*Meta box for testimonial */
            $meta_boxes[] = array(
                'id'         => 'digitalworld_testimonial_option',
                'title'      => esc_html__('Testimonial Options', 'digitalworld'),
                'post_types' => 'testimonial',
                'fields'     => array(
                    array(
                        'name' => esc_html__( 'Name', 'digitalworld' ),
                        'id'   => 'digitalworld_testimonial_name',
                        'type' => 'text',
                    ),
                    array(
                        'name' => esc_html__( 'Position', 'digitalworld' ),
                        'id'   => 'digitalworld_testimonial_position',
                        'type' => 'text',
                    ),
                ),
            );

            /*Meta box for Mega menu */
            $meta_boxes[] = array(
                'id'         => 'digitalworld_mega_menu_option',
                'title'      => esc_html__('Mega Options', 'digitalworld'),
                'post_types' => 'megamenu',
                'fields'     => array(
                    array(
                        'name'             => esc_html__( 'Background Image', 'digitalworld' ),
                        'id'               => 'digitalworld_mega_menu_bg_image',
                        'type'             => 'image_advanced',
                        'max_file_uploads' => 1,
                    ),
                    array(
                        'name' => esc_html__( 'Menu Style', 'digitalworld' ),
                        'id'   => 'digitalworld_megamenu_style',
                        'type' => 'select',
                        'options'  => array(
                            'default' =>esc_html__( 'Default', 'digitalworld' ),
                            'dark' =>esc_html__( 'Dark', 'digitalworld' ),
                        ),
                        'std' => 'default',
                    ),
                ),
            );
            return $meta_boxes;
        }
    }
}
new  Digitalworld_Meta_Box_Settings();