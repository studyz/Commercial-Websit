<?php

if( !class_exists('Digitalworld_ThemeOptions')){
    class Digitalworld_ThemeOptions{

        public $args     = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;
        public $sidebars = array();
        public $header_options = array();
        public $product_attributes = array(''=>'None');
        public $socials = array();

        public function __construct() {
            if ( !class_exists( "ReduxFramework" ) ) {
                return;
            }
            $this->get_socials();
            $this->get_sidebars();
            $this->get_header_options();
            $this->get_all_product_attributes();
            $this->initSettings();
        }

        public function get_socials(){
            if( function_exists('digitalworld_get_all_social')){
                $all_socials = digitalworld_get_all_social();
                foreach ( $all_socials  as $social ){
                    $this->socials[$social['id']] = $social['name'];
                }
            }

        }

        public function get_sidebars(){
            global $wp_registered_sidebars;
            foreach ( $wp_registered_sidebars as $sidebar ){
                $sidebars[  $sidebar['id'] ] =   $sidebar['name'];
            }
            $this->sidebars = $sidebars;
        }

        public function  get_all_product_attributes(){
            $attributes = array();
            if( function_exists('wc_get_attribute_taxonomy_names')){
                $attributes = wc_get_attribute_taxonomy_names();
            }

            if( count( $attributes ) > 0 ){
                foreach ($attributes as $attribute ){
                    $this->product_attributes[$attribute] = $attribute;
                }
            }
            return $this->product_attributes;
        }

        public function get_header_options(){
            $layoutDir = get_template_directory().'/templates/headers/';
            $header_options = array();

            if(is_dir($layoutDir)){
                $files = scandir($layoutDir);
                if($files && is_array($files)){
                    $option = '';
                    foreach ($files as $file){
                        if ($file != '.' && $file != '..'){
                            $fileInfo = pathinfo($file);
                            if($fileInfo['extension'] == 'php' && $fileInfo['basename'] !='index.php'){
                                $file_data = get_file_data( $layoutDir.$file, array('Name'=>'Name') );
                                $file_name = str_replace('header-', '', $fileInfo['filename']);
                                $header_options[$file_name] = $file_data['Name'];
                            }
                        }
                    }
                }
            }
            $this->header_options = $header_options;
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if ( !isset( $this->args['opt_name'] ) ) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

            // Function to test the compiler hook and demo CSS output.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter( 'redux/options/' . $this->args['opt_name'] . '/sections', array( $this, 'dynamic_section' ) );

            $sections = array_values( apply_filters( 'digitalworld_all_theme_option_sections', $this->sections ) );

            $this->ReduxFramework = new ReduxFramework( $sections, $this->args );
        }

        /**
         *
         * This is a test function that will let you see when the compiler hook occurs.
         * It only runs if a field   set with compiler=>true is changed.
         * */
        function compiler_action( $options, $css ) {

        }

        function ts_redux_update_options_user_can_register( $options, $css ) {
            global $digitalworld;
            $users_can_register = isset( $digitalworld['opt-users-can-register'] ) ? $digitalworld['opt-users-can-register'] : 0;
            update_option( 'users_can_register', $users_can_register );
        }

        /**
         *
         * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
         * Simply include this function in the child themes functions.php file.
         *
         * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
         * so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => esc_html__( 'Section via hook', 'digitalworld' ),
                'desc'   => wp_kses( esc_html__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'digitalworld' ), array( 'p' => array( 'class' => array() ) ) ),
                'icon'   => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array(),
            );

            return $sections;
        }

        /**
         *
         * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**
         *
         * Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = "Testing filter hook!";

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );

            }
        }

        public function setSections() {

            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get( 'Name' );
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf( esc_html__( 'Customize &#8220;%s&#8221;', 'digitalworld' ), $this->theme->display( 'Name' ) );
            ?>
            <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                <?php if ( $screenshot ) : ?>
                    <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                           title="<?php echo esc_attr( $customize_title ); ?>">
                            <img src="<?php echo esc_url( $screenshot ); ?>"
                                 alt="<?php esc_attr_e( 'Current theme preview', 'digitalworld' ); ?>"/>
                        </a>
                    <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                         alt="<?php esc_attr_e( 'Current theme preview', 'digitalworld' ); ?>"/>
                <?php endif; ?>

                <h4>
                    <?php echo sanitize_text_field( $this->theme->display( 'Name' ) ); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf( esc_html__( 'By %s', 'digitalworld' ), $this->theme->display( 'Author' ) ); ?></li>
                        <li><?php printf( esc_html__( 'Version %s', 'digitalworld' ), $this->theme->display( 'Version' ) ); ?></li>
                        <li><?php echo '<strong>' . esc_html__( 'Tags', 'digitalworld' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo esc_attr( $this->theme->display( 'Description' ) ); ?></p>
                    <?php
                    if ( $this->theme->parent() ) {
                        printf(
                            ' <p class="howto">' . wp_kses( esc_html__( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'digitalworld' ), array( 'a' => array( 'href' => array() ) ) ) . '</p>', esc_html__( 'http://codex.wordpress.org/Child_Themes', 'digitalworld' ), $this->theme->parent()
                            ->display( 'Name' )
                        );
                    }
                    ?>

                </div>

            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            /*--General Settings--*/
            $this->sections[] = array(
                'title'            => esc_html__( 'General', 'digitalworld' ),
                'id'               => 'general',
                'desc'             => esc_html__( 'This General Setings', 'digitalworld' ),
                'customizer_width' => '400px',
                'icon'             => 'el el-home'
            );
            $this->sections[] = array(
                'title'      => esc_html__( 'Site Layout', 'techstore' ),
                'desc'       => esc_html__( 'Site Layout', 'techstore' ),
                'id'         => 'site_layout',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       => 'digitalworld_site_layout',
                        'type'     => 'select',
                        'title'    => esc_html__('Site Layout', 'techstore'),
                        'options'  => array(
                            'full'=>'Full',
                            'boxed'=> 'Boxed'
                        ),
                        'default'  => 'full',
                    ),
                    array(
                        'id'       => 'digitalworld_body_background',
                        'type'     => 'color',
                        'title'    => esc_html__('Body Background color', 'digitalworld'),
                        'subtitle' => esc_html__('Body background color for the theme (default: #fff).', 'digitalworld'),
                        'default'  => '#fff',
                        'validate' => 'color',
                        'required' => array( 'digitalworld_site_layout','=',array( 'boxed' ))
                    ),
                )
            );
            /* Logo */
            $this->sections[] = array(
                'title'            => esc_html__( 'Logo', 'digitalworld' ),
                'id'               => 'logo',
                'subsection'       => true,
                'customizer_width' => '450px',
                'desc'             => esc_html__( 'Setting logo of site', 'digitalworld' ),
                'fields'           => array(
                    array(
                        'id'       => 'digitalworld_logo',
                        'type'     => 'media',
                        'url'      => true,
                        'title'    => esc_html__( 'Logo', 'digitalworld' ),
                        'compiler' => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'     => esc_html__( 'Basic media uploader with disabled URL input field.', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Upload any media using the WordPress native uploader', 'digitalworld' ),
                        'default'  => array( 'url' => get_template_directory_uri().'/images/logo.png' ),
                    ),
                )
            );
            /* Color */
            $this->sections[] = array(
                'title'      => esc_html__( 'Color', 'digitalworld' ),
                'desc'       => esc_html__( 'Setting Color of site ', 'digitalworld' ),
                'id'         => 'site-color',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       => 'digitalworld_main_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Main site Color', 'digitalworld'),
                        'subtitle' => esc_html__('Pick a background color for the theme (default: #fcd022).', 'digitalworld'),
                        'default'  => '#fcd022',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'digitalworld_main_color2',
                        'type'     => 'color',
                        'title'    => esc_html__('Main site Color 2', 'digitalworld'),
                        'subtitle' => esc_html__('Pick a background color for the theme (default: #e5b700).', 'digitalworld'),
                        'default'  => '#e5b700',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'digitalworld_button_hover_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Main site Color 3', 'digitalworld'),
                        'default'  => '#222',
                        'validate' => 'color',
                    ),
                )
            );
            $this->sections[] = array(
                'title'      => esc_html__( 'Sidebar', 'digitalworld' ),
                'id'         => 'slidebar',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       =>'opt_multi_slidebars',
                        'type'     => 'multi_text',
                        'title'    => esc_html__('Sidebars', 'digitalworld'),
                        'subtitle' => esc_html__('Add custom sidebars.', 'digitalworld'),
                        'desc'     => esc_html__('This is the description field, again good for additional info.', 'digitalworld')
                    ),
                )
            );
            /* Custom css, js */
            $this->sections[] = array(
                'title'      => esc_html__( 'Custom CSS/JS', 'digitalworld' ),
                'desc'       => esc_html__( 'Custom css,js your site ', 'digitalworld' ),
                'id'         => 'custom-css-js',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       => 'digitalworld_custom_css',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'Custom CSS', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Paste your custom CSS code here.', 'digitalworld' ),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'desc'     => 'Custom css code.',
                        'default'  => "",
                    ),
                    array(
                        'id'       => 'digitalworld_custom_js',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'Custom JS ', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Paste your custom JS code here.', 'digitalworld' ),
                        'mode'     => 'javascript',
                        'theme'    => 'chrome',
                        'desc'     => 'Custom javascript code',
                        //'default' => "jQuery(document).ready(function(){\n\n});"
                    ),
                )
            );
            $this->sections[] = array(
                'title'      => esc_html__( 'Developer', 'digitalworld' ),
                'desc'       => esc_html__( 'Developer', 'digitalworld' ),
                'id'         => 'developer',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'      => 'digitalworld_dev_mode',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Developer Mode', 'digitalworld' ),
                        'default' => '0',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                    ),
                )
            );

            $this->sections[] = array(
                'title'            => esc_html__( 'Header', 'digitalworld' ),
                'id'               => 'header',
                'desc'             => esc_html__( 'Header Setings', 'digitalworld' ),
                'customizer_width' => '400px',
                'icon'             => 'el-icon-credit-card',
                'fields' => array(
                    array(
                        'id'       => 'digitalworld_used_header',
                        'type'     => 'select',
                        'title'    => esc_html__('Header Layout', 'digitalworld'),
                        'subtitle' => esc_html__('Select a header layout', 'digitalworld'),
                        'options'  =>$this->header_options,
                        'default'  => 'style-01',
                    ),
                    array(
                        'id'      => 'digitalworld_enable_sticky_menu',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Main Menu Sticky', 'digitalworld' ),
                        'default' => '1',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                    ),
                    array(
                        'id'      => 'digitalworld_enable_wishlist_link',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Wishlist link', 'digitalworld' ),
                        'default' => '1',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                        'required' => array(
                            array( 'digitalworld_used_header','=',array( 'style-01','style-02', 'style-03', 'style-04' ) )
                        )
                    ),
                    array(
                        'id'      => 'digitalworld_enable_compare_link',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Compare link', 'digitalworld' ),
                        'default' => '1',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                        'required' => array( 'digitalworld_used_header','=',array( 'style-03', 'style-04' ))
                    ),
                    array(
                        'title'    => 'Hotline',
                        'id'       => 'opt_hotline',
                        'type'     => 'text',
                        'default'  => esc_html__('Hot line: (+800) 123 456 7899','digitalworld'),
                    ),
                ),
            );
            $this->sections[] = array(
                'title'  => esc_html__( 'Vertical Menu Settings', 'digitalworld' ),
                'desc'   => esc_html__( 'Vertical Menu Settings', 'digitalworld' ),
                'subsection'   => true,
                'fields' => array(
                    array(
                        'title'    => __('Use Vertical Menu', 'digitalworld'),
                        'id'       => 'opt_enable_vertical_menu',
                        'type'     => 'switch',
                        'default'  => '1',
                        'on'       => esc_html__('Enable', 'digitalworld'),
                        'off'      => esc_html__('Disable', 'digitalworld'),
                        'subtitle' => esc_html__( 'Use Vertical Menu on show any page', 'digitalworld' ),
                    ),
                    array(
                        'title'    => 'Vertical Menu Title',
                        'id'       => 'opt_vertical_menu_title',
                        'type'     => 'text',
                        'default'  => esc_html__('Shop By Category','digitalworld'),
                        'required' => array('opt_enable_vertical_menu','=','1')
                    ),
                    array(
                        'title'    => 'Vertical Menu Button show all text',
                        'id'       => 'opt_vertical_menu_button_all_text',
                        'type'     => 'text',
                        'default'  => esc_html__('All Categories','digitalworld'),
                        'required' => array('opt_enable_vertical_menu','=','1')
                    ),

                    array(
                        'title'    => 'Vertical Menu Button close text',
                        'id'       => 'opt_vertical_menu_button_close_text',
                        'type'     => 'text',
                        'default'  => esc_html__('Close','digitalworld'),
                        'required' => array('opt_enable_vertical_menu','=','1')
                    ),
                    array(
                        'title'    => __('Collapse', 'digitalworld'),
                        'id'       => 'opt_click_open_vertical_menu',
                        'type'     => 'switch',
                        'default'  => '1',
                        'on'       => esc_html__('Enable', 'digitalworld'),
                        'off'      => esc_html__('Disable', 'digitalworld'),
                        'subtitle' => __('Vertical menu will expand on click', 'digitalworld'),
                        'required' => array('opt_enable_vertical_menu','=','1')
                    ),

                    array(
                        'title'    => __('The number of visible vertical menu items', 'digitalworld'),
                        'subtitle' => __('The number of visible vertical menu items', 'digitalworld'),
                        'id'       => 'opt_vertical_item_visible',
                        'default'  => 10,
                        'type'     => 'text',
                        'validate' => 'numeric',
                        'required' => array('opt_enable_vertical_menu','=','1')
                    )
                ),
            );
            // -> Footer Settings
            $this->sections[] = array(
                'title'  => esc_html__( 'Footer Settings', 'digitalworld' ),
                'desc'   => esc_html__( 'Footer Settings', 'digitalworld' ),
                'icon'   => 'el-icon-credit-card',
                'fields' => array(
                    array(
                        'id'=>'digitalworld_footer_style',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => array('post_type' => array('footer'), 'posts_per_page' => -1,'orderby' => 'title', 'order' => 'ASC'),
                        'title' => esc_html__('Footer Display', 'digitalworld'),
                    ),
                )
            );
            // -> Blog Settings
            $this->sections[] = array(
                'title'            => esc_html__( 'Blog Settings', 'digitalworld' ),
                'id'               => 'blog',
                'desc'             => esc_html__( 'This Blog Setings', 'digitalworld' ),
                'customizer_width' => '400px',
                'icon'             => 'el-icon-th-list',
                'fields'     => array(
                    array(
                        'id'       => 'digitalworld_blog_layout',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => esc_html__( 'Blog Layout', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Select a layout.', 'digitalworld' ),
                        'options'  => array(
                            'left'  => array( 'alt' => 'Left Sidebar', 'img' => get_template_directory_uri() . '/images/2cl.png' ),
                            'right' => array( 'alt' => 'Right Sidebar', 'img' => get_template_directory_uri() . '/images/2cr.png' ),
                            'full'  => array( 'alt' => 'Full Width', 'img' => get_template_directory_uri() . '/images/1column.png' ),
                        ),
                        'default'  => 'left',
                    ),
                    array(
                        'id'      => 'digitalworld_blog_used_sidebar',
                        'type'    => 'select',
                        'multi'   => false,
                        'title'   => esc_html__( 'Blog Sidebar', 'digitalworld' ),
                        'options' => $this->sidebars,
                        'default' => 'widget-area',
                        'required' => array('digitalworld_blog_layout','=',array('left','right'))
                    ),
                    array(
                        'id'      => 'digitalworld_blog_placehold',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Use Placehold', 'digitalworld' ),
                        'default' => '0',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                    ),
                    array(
                        'id'      => 'digitalworld_blog_list_style',
                        'type'    => 'select',
                        'multi'   => false,
                        'title'   => esc_html__( 'Blog Layout Style', 'digitalworld' ),
                        'options' => array(
                            'list'     => esc_html__( 'List Style', 'digitalworld' ),
                            'grid'     => esc_html__( 'Grid Style', 'digitalworld' ),
                        ),
                        'default' => 'list',
                    ),

                    /* Blog grid */
                    array(
                        'title'    => esc_html__( 'Items per row on Desktop', 'digitalworld' ),
                        'subtitle'=> esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                        'id'      => 'digitalworld_blog_lg_items',
                        'type'    => 'select',
                        'default' => '4',
                        'options'   =>  array(
                            '12'    =>  '1 item',
                            '6'     =>  '2 items',
                            '4'     =>  '3 items',
                            '3'     =>  '4 items',
                            '15'    =>  '5 items',
                            '2'     =>  '6 items',
                        ),
                        'required' => array('digitalworld_blog_list_style','=',array('grid'))
                    ),
                    array(
                        'title'    => esc_html__( 'Items per row on landscape tablet', 'digitalworld' ),
                        'subtitle'=>esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                        'id'      => 'digitalworld_blog_md_items',
                        'type'    => 'select',
                        'default' => '4',
                        'options'   =>  array(
                            '12'    =>  '1 item',
                            '6'     =>  '2 items',
                            '4'     =>  '3 items',
                            '3'     =>  '4 items',
                            '15'    =>  '5 items',
                            '2'     =>  '6 items',
                        ),
                        'required' => array('digitalworld_blog_list_style','=',array('grid'))
                    ),
                    array(
                        'title'    => esc_html__( 'Items per row on portrait tablet', 'digitalworld' ),
                        'subtitle'=>esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                        'id'      => 'digitalworld_blog_sm_items',
                        'type'    => 'select',
                        'default' => '4',
                        'options' => array(
                            '12'    =>  '1 item',
                            '6'     =>  '2 items',
                            '4'     =>  '3 items',
                            '3'     =>  '4 items',
                            '15'    =>  '5 items',
                            '2'     =>  '6 items',
                        ),
                        'required' => array('digitalworld_blog_list_style','=',array('grid'))
                    ),
                    array(
                        'title'    => esc_html__( 'Items per row on Mobile', 'digitalworld' ),
                        'subtitle'=>esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                        'id'      => 'digitalworld_blog_xs_items',
                        'type'    => 'select',
                        'default' => '6',
                        'options' => array(
                            '12'    =>  '1 item',
                            '6'     =>  '2 items',
                            '4'     =>  '3 items',
                            '3'     =>  '4 items',
                            '15'    =>  '5 items',
                            '2'     =>  '6 items',
                        ),
                        'required' => array('digitalworld_blog_list_style','=',array('grid'))
                    ),
                    array(
                        'title'    => esc_html__( 'Items per row on Mobile', 'digitalworld' ),
                        'subtitle'=>esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                        'id'      => 'digitalworld_blog_ts_items',
                        'type'    => 'select',
                        'default' => '12',
                        'options' => array(
                            '12'    =>  '1 item',
                            '6'     =>  '2 items',
                            '4'     =>  '3 items',
                            '3'     =>  '4 items',
                            '15'    =>  '5 items',
                            '2'     =>  '6 items',
                        ),
                        'required' => array('digitalworld_blog_list_style','=',array('grid'))
                    ),
                )
            );

            /* Single blog settings */
            $this->sections[] = array(
                'title'            => esc_html__( 'Single post', 'digitalworld' ),
                'id'               => 'blog-single',
                'desc'             => esc_html__( 'This Single post Setings', 'digitalworld' ),
                'customizer_width' => '400px',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       => 'digitalworld_single_layout',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => esc_html__( 'Layout', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Select a layout.', 'digitalworld' ),
                        'options'  => array(
                            'left'  => array( 'alt' => 'Left Sidebar', 'img' => get_template_directory_uri() . '/images/2cl.png' ),
                            'right' => array( 'alt' => 'Right Sidebar', 'img' => get_template_directory_uri() . '/images/2cr.png' ),
                            'full'  => array( 'alt' => 'Full Width', 'img' => get_template_directory_uri() . '/images/1column.png' ),
                        ),
                        'default'  => 'left',
                    ),
                    array(
                        'id'      => 'digitalworld_single_used_sidebar',
                        'type'    => 'select',
                        'multi'   => false,
                        'title'   => esc_html__( 'Sidebar', 'digitalworld' ),
                        'options' => $this->sidebars,
                        'default' => 'widget-area',
                        'required' => array('digitalworld_single_layout','=',array('left','right'))
                    ),
                )
            );
            if ( class_exists( 'WooCommerce' ) ) {
                // -> Woo Settings
                $this->sections[] = array(
                    'title'  => esc_html__( 'WooCommerce', 'digitalworld' ),
                    'desc'   => esc_html__( 'WooCommerce Settings', 'digitalworld' ),
                    'icon'   => 'el-icon-shopping-cart',
                    'fields' => array(
                        array(
                            'title'    => esc_html__('Number of days newness','digitalworld'),
                            'id'       => 'digitalworld_woo_newness',
                            'type'     => 'text',
                            'default'  => '7',
                        ),
                        array(
                            'title'    => esc_html__( 'Products perpage', 'digitalworld' ),
                            'id'      => 'woo_products_perpage',
                            'type'    => 'text',
                            'default' => '12',
                            'validate' => 'numeric',
                            'subtitle'    => esc_html__( 'Number of products on shop page', 'digitalworld' ),
                        ),
                        array(
                            'id'      => 'digitalworld_enable_lazy',
                            'type'    => 'switch',
                            'title'   => esc_html__( 'Use Lazy Load', 'digitalworld' ),
                            'default' => '1',
                            'on'      => esc_html__( 'On', 'digitalworld' ),
                            'off'     => esc_html__( 'Off', 'digitalworld' ),
                        ),
                        array(
                            'id'       => 'digitalworld_woo_product_style',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => esc_html__( 'Product grid style', 'digitalworld' ),
                            'options'  => array(
                                '1'  => array('alt' => 'Product Style 01', 'img' => get_template_directory_uri() . '/woocommerce/product-styles/content-product-style-1.jpg'),
                            ),
                            'default'  => '1',
                        ),
                        array(
                            'id'      => 'woo_shop_attribute_display',
                            'type'    => 'select',
                            'multi'   => false,
                            'title'   => esc_html__( 'Product attributes show in product item', 'digitalworld' ),
                            'options' => $this->product_attributes,
                            'default' => '',
                        ),
                        array(
                            'id'       => 'woo_shop_layout',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => esc_html__( 'Sidebar Position', 'digitalworld' ),
                            'subtitle' => esc_html__( 'Select sidebar position on shop, product archive page.', 'digitalworld' ),
                            'options'  => array(
                                'left'  => array('alt' => '1 Column Left', 'img' => get_template_directory_uri() . '/images/2cl.png'),
                                'right' => array('alt' => '2 Column Right', 'img' => get_template_directory_uri() . '/images/2cr.png'),
                                'full'  => array('alt' => 'Full Width', 'img' => get_template_directory_uri() . '/images/1column.png' ),
                            ),
                            'default'  => 'left',
                        ),
                        array(
                            'id'      => 'woo_shop_used_sidebar',
                            'type'    => 'select',
                            'multi'   => false,
                            'title'   => esc_html__( 'Sidebar', 'digitalworld' ),
                            'options' => $this->sidebars,
                            'default' => 'shop-widget-area',
                            'required' => array('woo_shop_layout','=',array('left','right'))
                        ),
                        array(
                            'id'       => 'woo_shop_list_style',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => esc_html__( 'Shop Default Layout', 'digitalworld' ),
                            'subtitle' => esc_html__( 'Select default layout for shop, product category archive.', 'digitalworld' ),
                            'options'  => array(
                                'grid' => array( 'alt' => 'Layout Grid', 'img' => get_template_directory_uri() . '/images/grid-display.png' ),
                                'list' => array( 'alt' => 'Layout List', 'img' => get_template_directory_uri() . '/images/list-display.png' ),
                            ),
                            'default'  => 'grid',
                        ),

                        array(
                            'title'    => esc_html__( 'Items per row on Desktop( For grid mode )', 'digitalworld' ),
                            'subtitle'=> esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'      => 'digitalworld_woo_lg_items',
                            'type'    => 'select',
                            'default' => '4',
                            'options'   =>  array(
                                '12'    =>  '1 item',
                                '6'     =>  '2 items',
                                '4'     =>  '3 items',
                                '3'     =>  '4 items',
                                '15'    =>  '5 items',
                                '2'     =>  '6 items',
                            ),

                        ),
                        array(
                            'title'    => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'digitalworld' ),
                            'subtitle'=>esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'      => 'digitalworld_woo_md_items',
                            'type'    => 'select',
                            'default' => '4',
                            'options'   =>  array(
                                '12'    =>  '1 item',
                                '6'     =>  '2 items',
                                '4'     =>  '3 items',
                                '3'     =>  '4 items',
                                '15'    =>  '5 items',
                                '2'     =>  '6 items',
                            ),

                        ),
                        array(
                            'title'    => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'digitalworld' ),
                            'subtitle'=>esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'      => 'digitalworld_woo_sm_items',
                            'type'    => 'select',
                            'default' => '4',
                            'options' => array(
                                '12'    =>  '1 item',
                                '6'     =>  '2 items',
                                '4'     =>  '3 items',
                                '3'     =>  '4 items',
                                '15'    =>  '5 items',
                                '2'     =>  '6 items',
                            ),

                        ),
                        array(
                            'title'    => esc_html__( 'Items per row on Mobile( For grid mode )', 'digitalworld' ),
                            'subtitle'=>esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'      => 'digitalworld_woo_xs_items',
                            'type'    => 'select',
                            'default' => '6',
                            'options' => array(
                                '12'    =>  '1 item',
                                '6'     =>  '2 items',
                                '4'     =>  '3 items',
                                '3'     =>  '4 items',
                                '15'    =>  '5 items',
                                '2'     =>  '6 items',
                            ),

                        ),
                        array(
                            'title'    => esc_html__( 'Items per row on Mobile( For grid mode )', 'digitalworld' ),
                            'subtitle'=>esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'      => 'digitalworld_woo_ts_items',
                            'type'    => 'select',
                            'default' => '12',
                            'options' => array(
                                '12'    =>  '1 item',
                                '6'     =>  '2 items',
                                '4'     =>  '3 items',
                                '3'     =>  '4 items',
                                '15'    =>  '5 items',
                                '2'     =>  '6 items',
                            ),

                        ),
                    )
                );
                /** Single Product **/
                $this->sections[] = array(
                    'title'        => esc_html__( 'Single product', 'digitalworld' ),
                    'desc'         => esc_html__( 'Single product settings', 'digitalworld' ),
                    'subsection'   => true,
                    'fields'       => array(
                        array(
                            'id'       => 'digitalworld_woo_single_layout',
                            'type'     => 'image_select',
                            'title'    => esc_html__( 'Single Product Sidebar Position', 'digitalworld' ),
                            'subtitle' => esc_html__( 'Select sidebar position on single product page.', 'digitalworld' ),
                            'options'  => array(
                                'left'      => array( 'alt' => '1 Column Left', 'img' => get_template_directory_uri() . '/images/2cl.png' ),
                                'right'     => array( 'alt' => '2 Column Right', 'img' => get_template_directory_uri() . '/images/2cr.png' ),
                                'full' => array( 'alt' => 'Full Width', 'img' => get_template_directory_uri() . '/images/1column.png' ),
                            ),
                            'default'  => 'left',
                        ),
                        array(
                            'id'      => 'digitalworld_woo_single_used_sidebar',
                            'type'    => 'select',
                            'multi'   => false,
                            'title'   => esc_html__( 'Sidebar', 'digitalworld' ),
                            'options' => $this->sidebars,
                            'default' => 'widget-area',
                            'required' => array('digitalworld_woo_single_layout','=',array('left','right'))
                        ),
                        array(
                            'id'      => 'digitalworld_enable_product_zoom', // not done,
                            'type'    => 'switch',
                            'title'   => esc_html__( 'Product Image Zoom', 'digitalworld' ),
                            'default' => '0',
                            'on'      => esc_html__( 'On', 'digitalworld' ),
                            'off'     => esc_html__( 'Off', 'digitalworld' ),
                        ),

                        array(
                            'id'      => 'digitalworld_enable_product_thumb_slide', // not done,
                            'subtitle'=> esc_html__( 'Display product thumbnails images by carousel', 'digitalworld' ),
                            'type'    => 'switch',
                            'title'   => esc_html__( 'Product thumbnails images carousel', 'digitalworld' ),
                            'default' => '1',
                            'on'      => esc_html__( 'On', 'digitalworld' ),
                            'off'     => esc_html__( 'Off', 'digitalworld' ),
                        ),

                        array(
                            'title'    => esc_html__( 'Thumbnail items per row on Desktop', 'digitalworld' ),
                            'subtitle' => esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'       => 'digitalworld_woo_product_thumb_slide_lg_items',
                            'type'     => 'select',
                            'default'  => '4',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('digitalworld_enable_product_thumb_slide','=',1)
                        ),
                        array(
                            'title'    => esc_html__( 'Thumbnail items per row on landscape tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'       => 'digitalworld_woo_product_thumb_slide_md_items',
                            'type'     => 'select',
                            'default'  => '4',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('digitalworld_enable_product_thumb_slide','=',1)
                        ),
                        array(
                            'title'    => esc_html__( 'Thumbnail items per row on portrait tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'       => 'digitalworld_woo_product_thumb_slide_sm_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('digitalworld_enable_product_thumb_slide','=',1)
                        ),
                        array(
                            'title'    => esc_html__( 'Thumbnail items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'       => 'digitalworld_woo_product_thumb_slide_xs_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('digitalworld_enable_product_thumb_slide','=',1)
                        ),
                        array(
                            'title'    => esc_html__( 'Thumbnail items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'       => 'digitalworld_woo_product_thumb_slide_ts_items',
                            'type'     => 'select',
                            'default'  => '1',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('digitalworld_enable_product_thumb_slide','=',1)
                        ),
                    )

                );
                /** Cross sell products **/
                $this->sections['woocommerce-cross-sell'] = array(
                    'title'        => esc_html__( 'Cross sell', 'digitalworld' ),
                    'desc'         => esc_html__( 'Cross sell settings', 'digitalworld' ),
                    'subsection'   => true,
                    'fields'       => array(
                        array(
                            'title'    => esc_html__( 'Cross sell title', 'digitalworld' ),
                            'id'       => 'digitalworld_cross_sells_products_title',
                            'type'     => 'text',
                            'default'  => 'You may be interested in...',
                            'subtitle' => esc_html__( 'Cross sell title', 'digitalworld' ),
                        ),

                        array(
                            'title'    => esc_html__( 'Cross sell items per row on Desktop', 'digitalworld' ),
                            'subtitle' => esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'       => 'digitalworld_woo_crosssell_lg_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Cross sell items per row on landscape tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'       => 'digitalworld_woo_crosssell_md_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Cross sell items per row on portrait tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'       => 'digitalworld_woo_crosssell_sm_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Cross sell items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'       => 'digitalworld_woo_crosssell_xs_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                        array(
                            'title'    => esc_html__( 'Cross sell items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'       => 'digitalworld_woo_crosssell_ts_items',
                            'type'     => 'select',
                            'default'  => '1',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                    )
                );

                /*-- RELATED PRODUCTS --*/
                $this->sections[] = array(
                    'title'        => esc_html__( 'Related products', 'digitalworld' ),
                    'desc'         => esc_html__( 'Related products settings', 'digitalworld' ),
                    'subsection'   => true,
                    'fields'       => array(
                        array(
                            'title'    => esc_html__( 'Related products title', 'digitalworld' ),
                            'id'       => 'digitalworld_related_products_title',
                            'type'     => 'text',
                            'default'  => 'Related Products',
                            'subtitle' => esc_html__( 'Related products title', 'digitalworld' ),
                        ),

                        array(
                            'title'    => esc_html__( 'Limit number of products', 'digitalworld' ),
                            'id'       => 'digitalworld_related_products_perpage',
                            'type'     => 'text',
                            'default'  => '8',
                            'validate' => 'numeric',
                            'subtitle' => esc_html__( 'Number of products on shop page', 'digitalworld' ),
                        ),

                        array(
                            'title'    => esc_html__( 'Related products items per row on Desktop', 'digitalworld' ),
                            'subtitle' => esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'       => 'digitalworld_woo_related_lg_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on landscape tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'       => 'digitalworld_woo_related_md_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Related product items per row on portrait tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'       => 'digitalworld_woo_related_sm_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'       => 'digitalworld_woo_related_xs_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'       => 'digitalworld_woo_related_ts_items',
                            'type'     => 'select',
                            'default'  => '1',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                    )
                );
                /*-- Recommended Products --*/
                $this->sections[] = array(
                    'title'        => esc_html__( 'Recommended products', 'digitalworld' ),
                    'desc'         => esc_html__( 'Display Featured Products', 'digitalworld' ),
                    'subsection'   => true,
                    'fields'       => array(
                        array(
                            'title'    => __('Enable Recommended products', 'digitalworld'),
                            'id'       => 'opt_enable_recommended_products',
                            'type'     => 'switch',
                            'default'  => '1',
                            'on'       => esc_html__('Enable', 'digitalworld'),
                            'off'      => esc_html__('Disable', 'digitalworld'),
                        ),
                        array(
                            'title'    => esc_html__( 'Recommended products title', 'digitalworld' ),
                            'id'       => 'digitalworld_related_products_title',
                            'type'     => 'text',
                            'default'  => 'Recommended Products',
                            'subtitle' => esc_html__( 'Recommended Products title', 'digitalworld' ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on Desktop', 'digitalworld' ),
                            'subtitle' => esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'       => 'digitalworld_woo_recommended_lg_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on landscape tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'       => 'digitalworld_woo_recommended_md_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                        array(
                            'title'    => esc_html__( 'Related product items per row on portrait tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'       => 'digitalworld_woo_recommended_sm_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'       => 'digitalworld_woo_recommended_xs_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                        array(
                            'title'    => esc_html__( 'Related products items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'       => 'digitalworld_woo_recommended_ts_items',
                            'type'     => 'select',
                            'default'  => '1',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                            'required' => array('opt_enable_recommended_products','=',array(1))
                        ),
                    )
                );

                /*-- UP SELL PRODUCTS --*/
                $this->sections[] = array(
                    'title'        => esc_html__( 'Up sells products', 'digitalworld' ),
                    'desc'         => esc_html__( 'Up sells products settings', 'digitalworld' ),
                    'subsection'   => true,
                    'fields'       => array(
                        array(
                            'title'    => esc_html__( 'Up sells title', 'digitalworld' ),
                            'id'       => 'digitalworld_upsell_products_title',
                            'type'     => 'text',
                            'default'  => 'You may also like&hellip;',
                            'subtitle' => esc_html__( 'Up sells products title', 'digitalworld' ),
                        ),

                        array(
                            'title'    => esc_html__( 'Up sells items per row on Desktop', 'digitalworld' ),
                            'subtitle' => esc_html__( '(Screen resolution of device >= 1200px )', 'digitalworld' ),
                            'id'       => 'digitalworld_woo_upsell_lg_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Up sells items per row on landscape tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=992px and < 1200px )','digitalworld'),
                            'id'       => 'digitalworld_woo_upsell_md_items',
                            'type'     => 'select',
                            'default'  => '3',
                            'options'   =>  array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Up sells items per row on portrait tablet', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=768px and < 992px )','digitalworld'),
                            'id'       => 'digitalworld_woo_upsell_sm_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            ),
                        ),
                        array(
                            'title'    => esc_html__( 'Up sells items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device >=480  add < 768px)','digitalworld'),
                            'id'       => 'digitalworld_woo_upsell_xs_items',
                            'type'     => 'select',
                            'default'  => '2',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                        array(
                            'title'    => esc_html__( 'Up sells items per row on Mobile', 'digitalworld' ),
                            'subtitle' => esc_html__('(Screen resolution of device < 480px)','digitalworld'),
                            'id'       => 'digitalworld_woo_upsell_ts_items',
                            'type'     => 'select',
                            'default'  => '1',
                            'options'  => array(
                                '1'     =>  '1 item',
                                '2'     =>  '2 items',
                                '3'     =>  '3 items',
                                '4'     =>  '4 items',
                                '5'     =>  '5 items',
                                '6'     =>  '6 items',
                            )
                        ),
                    )
                );

            }
            /*--Social Settings--*/
            $this->sections[] = array(
                'title'  => esc_html__( 'Social Settings', 'digitalworld' ),
                'icon'   => 'el-icon-credit-card',
                'fields' => array(
                    array(
                        'id'       => 'opt_twitter_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Twitter', 'digitalworld' ),
                        'default'  => 'https://twitter.com',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_fb_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Facebook', 'digitalworld' ),
                        'default'  => 'https://facebook.com',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_google_plus_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Google Plus', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_dribbble_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Dribbble', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_behance_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Behance', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_tumblr_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Tumblr', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_instagram_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Instagram', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_pinterest_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Pinterest', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_youtube_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Youtube', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_vimeo_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Vimeo', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_linkedin_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Linkedin', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                    array(
                        'id'       => 'opt_rss_link',
                        'type'     => 'text',
                        'title'    => esc_html__( 'RSS', 'digitalworld' ),
                        'default'  => '',
                        'validate' => 'url',
                    ),
                ),
            );
            /*--Typograply Options--*/
            $this->sections[] = array(
                'icon'   => 'el-icon-font',
                'title'  => esc_html__( 'Typography Options', 'digitalworld' ),
                'fields' => array(
                    array(
                        'id'       => 'opt_typography_body_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Body Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the body font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'body',
                    ),
                    array(
                        'id'       => 'opt_typography_h1_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 1(H1) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H1 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h1',
                    ),

                    array(
                        'id'       => 'opt_typography_h2_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 2(H2) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H2 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h2',
                    ),

                    array(
                        'id'       => 'opt_typography_h3_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 3(H3) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H3 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h3',
                    ),

                    array(
                        'id'       => 'opt_typography_h4_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 4(H4) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H4 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h4',
                    ),

                    array(
                        'id'       => 'opt_typography_h5_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 5(H5) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H5 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h5',
                    ),

                    array(
                        'id'       => 'opt_typography_h6_font',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 6(H6) Font Setting', 'digitalworld' ),
                        'subtitle' => esc_html__( 'Specify the H6 tag font properties.', 'digitalworld' ),
                        'google'   => true,
                        'output'   => 'h6',
                    ),
                ),
            );
            /*--Popup Newsletter Options--*/
            $this->sections[] = array(
                'icon'   => 'el-icon-credit-card',
                'title'  => esc_html__( 'Popup Newsletter', 'digitalworld' ),
                'fields' => array(
                    array(
                        'id'      => 'digitalworld_enable_popup',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Enable Poppup', 'digitalworld' ),
                        'default' => '0',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                    ),
                    array(
                        'id'       => 'digitalworld_popup_title',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Title', 'digitalworld' ),
                        'default'  => 'Newsletter',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_popup_subtitle',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Sub Title', 'digitalworld' ),
                        'default'  => 'Subscribe to our mailling list to get updates to your email inbox.',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_popup_input_placeholder',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Input placeholder text', 'digitalworld' ),
                        'default'  => 'Email Address',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_popup_button_text',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Input placeholder text', 'digitalworld' ),
                        'default'  => 'Sign Up',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_poppup_background',
                        'type'     => 'media',
                        'url'      => true,
                        'title'    => esc_html__( 'Popup Background', 'digitalworld' ),
                        'compiler' => 'true',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_poppup_socials',
                        'type'     => 'select',
                        'multi'    => true,
                        'title'    => __('Socials', 'digitalworld'),
                        'options'  => $this->socials,
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'       => 'digitalworld_popup_delay_time',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Delay time', 'digitalworld' ),
                        'default'  => '0',
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                    array(
                        'id'      => 'digitalworld_enable_popup_mobile',
                        'type'    => 'switch',
                        'title'   => esc_html__( 'Enable Poppup on Mobile', 'digitalworld' ),
                        'default' => '0',
                        'on'      => esc_html__( 'On', 'digitalworld' ),
                        'off'     => esc_html__( 'Off', 'digitalworld' ),
                        'required' => array('digitalworld_enable_popup','=',array('1'))
                    ),
                ),
            );
            /*--  Google map API key --*/
            $this->sections[] = array(
                'title'  => esc_html__('Google Map', 'digitalworld'),
                'fields' => array(
                    array(
                        'id'    => 'opt_gmap_api_key',
                        'type'  => 'text',
                        'title' => esc_html__('Google Map API Key', 'digitalworld'),
                        'desc'  => wp_kses(sprintf(__('Enter your Google Map API key. <a href="%s" target="_blank">How to get?</a>', 'digitalworld'), 'https://developers.google.com/maps/documentation/javascript/get-api-key'), array('a' => array('href' => array(), 'target' => array()))),
                    ),
                )
            );
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'      => 'redux-opts-1',
                'title'   => esc_html__( 'Theme Information 1', 'digitalworld' ),
                'content' => wp_kses( esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'digitalworld' ), array( 'p' ) ),
            );

            $this->args['help_tabs'][] = array(
                'id'      => 'redux-opts-2',
                'title'   => esc_html__( 'Theme Information 2', 'digitalworld' ),
                'content' => wp_kses( esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'digitalworld' ), array( 'p' ) ),
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = wp_kses( esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'digitalworld' ), array( 'p' ) );
        }

        /**
         *
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'           => 'digitalworld', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'       => '<span class="theme-name">' . sanitize_text_field( $theme->get( 'Name' ) ) . '</span>', // Name that appears at the top of your panel
                'display_version'    => $theme->get( 'Version' ), // Version that appears at the top of your panel
                'menu_type'          => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'     => false, // Show the sections below the admin menu item or not
                'menu_title'         => esc_html__( 'Theme Options', 'digitalworld' ),
                'page_title'         => esc_html__( 'Theme Options', 'digitalworld' ),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key'     => '', // Must be defined to add google fonts to the typography module
                //'async_typography'    => true, // Use a asynchronous font on the front end or font string
                //'admin_bar'           => false, // Show the panel pages on the admin bar
                'global_variable'    => 'digitalworld', // Set a different name for your global variable other than the opt_name
                'dev_mode'           => false, // Show the time the page took to load, etc
                'customizer'         => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority'      => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'        => 'digitalworld', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'   => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon'          => '', // Specify a custom URL to an icon
                'last_tab'           => '', // Force your panel to always open to a specific tab (by id)
                'page_icon'          => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug'          => 'digitalworld_options', // Page slug used to denote the panel
                'save_defaults'      => true, // On load save the defaults to DB before user clicks save or not
                'default_show'       => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark'       => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time'     => 60 * MINUTE_IN_SECONDS,
                'output'             => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'         => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'              => 'digitalworld', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                'footer_credit'      => esc_html__( 'ReaApple WordPress Team', 'digitalworld' ), // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'           => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info'        => false, // REMOVE
                'help_tabs'          => array(),
                'help_sidebar'       => '', // esc_html__( '', $this->args['domain'] );
                'show_options_object'=>false,
                'hints'              => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',

                    'tip_style'    => array(
                        'color'   => 'light',
                        'shadow'  => true,
                        'rounded' => false,
                        'style'   => '',
                    ),
                    'tip_position' => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'   => array(
                        'show' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'mouseover',
                        ),
                        'hide' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'click mouseleave',
                        ),
                    ),
                ),
            );

            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook',
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter',
            );

            // Panel Intro text -> before the form
            if ( !isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                if ( !empty( $this->args['global_variable'] ) ) {
                    $v = $this->args['global_variable'];
                }
                else {
                    $v = str_replace( "-", "_", $this->args['opt_name'] );
                }

            }
            else {

            }

        }
    }
}

if( !function_exists('Digitalworld_ThemeOptions')){
    function Digitalworld_ThemeOptions(){
        new Digitalworld_ThemeOptions();
    }
}
add_action('init','Digitalworld_ThemeOptions',1);