<?php 
/*
 * Plugin Name: DigitalWorld Importer
 * Plugin URI: 
 * Description: Import digitalworld theme demo data
 * Author: Kute Themes
 * Text Domain: digitalworld-importer
 * Domain Path: /languages
 * Version: 1.0.0
 * Author URI: 
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

?>
<?php
define('IMAGE_DEMO_URL', plugins_url('/data/images/', __FILE__));
define('DIGITAL_IMPORTER_URL', plugin_dir_url( __FILE__ ) );
define('DIGITAL_IMPORTER_VERSION', '1.0.0' );

if (!class_exists('DIGITALWORLD_IMPORTER')) {

    class DIGITALWORLD_IMPORTER
    {

        public $data_demos = array();

        function __construct()
        {
            // SET DATA DEMOS
            $widget_file_url = plugins_url( '/data/digitalworld-widgets.wie',__FILE__ );
            $file_content_url= 'content.xml';
            $registed_menu   = array(
                'primary'        => esc_html__('Primary Menu', 'digitalworld-importer'),
                'vertical_menu'  => esc_html__('Vertical Menu', 'digitalworld-importer'),
                'top-left-menu'  => esc_html__('Top bar left menu', 'digitalworld-importer'),
                'top-right-menu' => esc_html__( 'Top bar right menu', 'digitalworld-importer' ),
                'middle-menu'    => esc_html__( 'Middle menu', 'digitalworld-importer' ),
            );
            
            $menu_location = array(
                'primary'           => 'Main Menu',
                'top-left-menu'     => 'Top Left Menu',
                'top-right-menu'    => 'Top Right Menu',
                'middle-menu'       => 'Middle menu',
                'vertical_menu'     => 'Vertical Menu',
            );
            
            $this->data_demos = array(
				array(
					'name'           => __( 'Demo 01', 'digitalworld-importer' ),
					'file_content'	 => $file_content_url,
					'revslider'      => plugins_url( '/data/revsliders/digitalworld-opt1.zip', __FILE__ ),
					'theme_option'   => plugins_url( '/data/theme_options/home-01.json', __FILE__ ) ,
					'widget'         => $widget_file_url,
					'menus'          => $registed_menu,
					'homepage'       => 'Home 01',
					'blogpage'       => 'Blog',
					'preview'        => plugins_url( '/data/previews/Home01.jpg',__FILE__ ),
					'demo_link'      => 'http://kutethemes.net/wordpress/digitalworld/',
                    'menu_locations' => $menu_location
				),
                
                array(
					'name'           => __( 'Demo 02', 'digitalworld-importer' ),
					'file_content'	 => $file_content_url,
					'revslider'      => plugins_url( '/data/revsliders/digitalworld-opt2.zip', __FILE__ ),
					'theme_option'   => plugins_url( '/data/theme_options/home-02.json', __FILE__ ) ,
					'widget'         => $widget_file_url,
					'menus'          => $registed_menu,
					'homepage'       => 'Home 02',
					'blogpage'       => 'Blog',
					'preview'        => plugins_url( '/data/previews/Home02.jpg',__FILE__ ),
					'demo_link'      => 'http://kutethemes.net/wordpress/digitalworld/home-02/',
                    'menu_locations' => $menu_location
				),
                
                array(
					'name'           => __( 'Demo 03', 'digitalworld-importer' ),
					'file_content'	 => $file_content_url,
					'revslider'      => plugins_url( '/data/revsliders/digitalworld-opt3.zip', __FILE__ ),
					'theme_option'   => plugins_url( '/data/theme_options/home-03.json', __FILE__ ) ,
					'widget'         => $widget_file_url,
					'menus'          => $registed_menu,
					'homepage'       => 'Home 03',
					'blogpage'       => 'Blog',
					'preview'        => plugins_url( '/data/previews/Home03.jpg',__FILE__ ),
					'demo_link'      => 'http://kutethemes.net/wordpress/digitalworld/home-03/',
                    'menu_locations' => $menu_location
				),
                
                array(
					'name'           => __( 'Demo 04', 'digitalworld-importer' ),
					'file_content'	 => $file_content_url,
					'revslider'      => plugins_url( '/data/revsliders/digitalworld-opt4.zip', __FILE__ ),
					'theme_option'   => plugins_url( '/data/theme_options/home-04.json', __FILE__ ) ,
					'widget'         => $widget_file_url,
					'menus'          => $registed_menu,
					'homepage'       => 'Home 04',
					'blogpage'       => 'Blog',
					'preview'        => plugins_url( '/data/previews/Home04.jpg',__FILE__ ),
					'demo_link'      => 'http://kutethemes.net/wordpress/digitalworld/home-04/',
                    'menu_locations' => $menu_location
				),
                
                array(
					'name'           => __( 'Demo 05', 'digitalworld-importer' ),
					'file_content'	 => $file_content_url,
					'revslider'      => plugins_url( '/data/revsliders/digitalworld-opt5.zip', __FILE__ ),
					'theme_option'   => plugins_url( '/data/theme_options/home-05.json', __FILE__ ) ,
					'widget'         => $widget_file_url,
					'menus'          => $registed_menu,
					'homepage'       => 'Home 05',
					'blogpage'       => 'Blog',
					'preview'        => plugins_url( '/data/previews/Home05.jpg',__FILE__ ),
					'demo_link'      => 'http://kutethemes.net/wordpress/digitalworld/home-05/',
                    'menu_locations' => $menu_location
				),
                
			);
            
            // JS and css
            add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
            
            /* Register ajax action */
            add_action('wp_ajax_kt_import_content', array($this, 'import_content'));
            add_action('wp_ajax_kt_import_revslider', array($this, 'import_revslider'));
            add_action('wp_ajax_kt_import_theme_options', array($this, 'import_theme_options'));
            add_action('wp_ajax_kt_import_widget', array($this, 'import_widget'));
            add_action('wp_ajax_kt_import_config', array($this, 'import_config'));
            
            add_action( 'init', array( $this, 'load_textdomain' ) );
            
        }
        
        function load_textdomain() {
            load_plugin_textdomain( 'digitalworld-toolkit', false, plugin_dir_path( __FILE__ ) . '/languages' );
        }
        
        function register_scripts()
        {
            wp_enqueue_style('kt-admin-importer-style', DIGITAL_IMPORTER_URL . 'css/style.css' );
            wp_enqueue_style('kt-admin-importer-circle', DIGITAL_IMPORTER_URL . 'css/circle.css' );
            wp_enqueue_script('kt-admin-importer-script', DIGITAL_IMPORTER_URL . 'js/script.js', array('jquery'), false, true);
        }

        function importer_page_content()
        {
            $theme_name = wp_get_theme()->get('Name');
            ?>
            <div class="kt-importer-wrapper">
                <div class="progress_test" style="height: 5px; background-color: red; width: 0;"></div>
                <h1 class="heading"><?php echo ucfirst( esc_html( $theme_name ) ); ?> - Install Demo Content</h1>
                <div class="note">
                    <h3>Please read before importing:</h3>
                    <p>This importer will help you build your site look like our demo. Importing data is recommended
                        on fresh install.</p>
                    <p>Please ensure you have already installed and activated Digitalworld Toolkit, WooCommerce, Visual
                        Composer and Revolution Slider plugins.</p>
                    <p>Please note that importing data only builds a frame for your website. <strong>It will not
                            import all demo contents and images.</strong></p>
                    <p>It can take a few minutes to complete. <strong>Please don't close your browser while
                            importing.</strong></p>
                    <h3>Select the options below which you want to import:</h3>
                </div>
                <?php if ($this->data_demos) : ?>
                    <div class="options">
                        <?php $i = 0;
                            foreach ($this->data_demos as $key => $data): ?>
                                <div id="option-<?php echo $key; ?>" class="option">
                                    <div class="inner">
                                        <div class="progress-wapper">
                                            <div class="progress-item">
                                                <div class="meter item kt_import_content">
                                                    Import Demo content
                                                    <div class="checkmark">
                                                        <div class="checkmark_stem"></div>
                                                        <div class="checkmark_kick"></div>
                                                    </div>
                                                    <span style="width: 100%"></span>
                                                </div>
                                                <div class="meter item kt_import_theme_options">
                                                    Theme options
                                                    <div class="checkmark">
                                                        <div class="checkmark_stem"></div>
                                                        <div class="checkmark_kick"></div>
                                                    </div>
                                                    <span style="width: 100%"></span>
                                                </div>
                                                <div class="meter item kt_import_widget">
                                                    Widget
                                                    <div class="checkmark">
                                                        <div class="checkmark_stem"></div>
                                                        <div class="checkmark_kick"></div>
                                                    </div>
                                                    <span style="width: 100%"></span>
                                                </div>
                                                <div class="meter item kt_import_revslider">
                                                    Revslider
                                                    <div class="checkmark">
                                                        <div class="checkmark_stem"></div>
                                                        <div class="checkmark_kick"></div>
                                                    </div>
                                                    <span style="width: 100%"></span>
                                                </div>
                                                <div class="meter item kt_import_config">
                                                    Config Demo
                                                    <div class="checkmark">
                                                        <div class="checkmark_stem"></div>
                                                        <div class="checkmark_kick"></div>
                                                    </div>
                                                    <span style="width: 100%"></span>
                                                </div>
                                            </div>
                                            <div class="progress-circle">
                                                <div class="c100 p0 dark green">
                                                    <span class="percent">0%</span>
                                                    <div class="slice">
                                                        <div class="bar"></div>
                                                        <div class="fill"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview">
                                            <img src="<?php echo $data['preview']; ?>">
                                        </div>
                                        <div class="kt-control">
                                            <div class="control-inner">
                                                <h3 class="demo-name"><?php echo $data['name']; ?></h3>
                                                <a target="_blank" class="more"
                                                   href="<?php echo $data['demo_link']; ?>">View demo</a>
                                                <div class="group-control">
                                                    <label for="demo-content-<?php echo $key; ?>">
                                                        <input id="demo-content-<?php echo $key; ?>" type="checkbox"
                                                               value="1" checked="checked">
                                                        Demo content
                                                    </label>
                                                    <label for="config-<?php echo $key; ?>">
                                                        <input id="config-<?php echo $key; ?>" type="checkbox"
                                                               value="1" checked="checked">
                                                        Config Demo
                                                    </label>
                                                    <label for="theme-option-<?php echo $key; ?>">
                                                        <input id="theme-option-<?php echo $key; ?>" type="checkbox"
                                                               value="1" checked="checked">
                                                        Theme options
                                                    </label>
                                                    <label for="widget-<?php echo $key; ?>">
                                                        <input id="widget-<?php echo $key; ?>" type="checkbox"
                                                               value="1" checked="checked">
                                                        Widget
                                                    </label>
                                                    <?php if ($data['revslider'] != ''): ?>
                                                        <label for="revslider-<?php echo $key; ?>">
                                                            <input id="revslider-<?php echo $key; ?>"
                                                                   type="checkbox" value="1" checked="checked">
                                                            Revslider
                                                        </label>
                                                    <?php endif; ?>
                                                    <button data-id="<?php echo $key; ?>"
                                                            data-optionid="<?php echo $key; ?>"
                                                            class="button button-primary kt-button-import">Install
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No data import</p>
                <?php endif; ?>
            </div>
            <?php
        }

        /* DOWNLOAD FILE */
        function download($url = "", $file_name = "")
        {
            $filepath = "";
            if ($url != "") {
                $upload_dir = wp_upload_dir();
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                curl_close($ch);
                $destination = $upload_dir['path'] . "/" . $file_name;
                $file = fopen($destination, "w+");
                fputs($file, $data);
                fclose($file);
                $filepath = $destination;
            }

            return $filepath;
        }

        /* Include Importer Classes */
        function include_importer_classes()
        {

            if (!class_exists('WP_Importer')) {
                include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            }
            if (!class_exists('KT_WP_Import')) {

                if (file_exists(dirname(__FILE__) . '/inc/wordpress-importer.php')) {
                    include_once dirname(__FILE__) . '/inc/wordpress-importer.php';
                }

            }
        }

        /* Dont Resize image while importing */
        function no_resize_image($sizes)
        {
            return array();
        }

        function before_content_import()
        {
            // Set some WooCommerce $attributes
            if (class_exists('WooCommerce')) {
                global $wpdb;

                if (current_user_can('administrator')) {
                    $attributes = array(
                        array(
                            'attribute_label'   => 'Color',
                            'attribute_name'    => 'color',
                            'attribute_type'    => 'select',
                            'attribute_orderby' => 'menu_order',
                            'attribute_public'  => '0'
                        ),
                        array(
                            'attribute_label'   => 'Size',
                            'attribute_name'    => 'size',
                            'attribute_type'    => 'select',
                            'attribute_orderby' => 'menu_order',
                            'attribute_public'  => '0'
                        ),
                    );

                    foreach ($attributes as $attribute):
                        if (empty($attribute['attribute_name']) || empty($attribute['attribute_label'])) {
                            return new WP_Error('error', __('Please, provide an attribute name and slug.', 'woocommerce'));
                        } elseif (($valid_attribute_name = $this->wc_valid_attribute_name($attribute['attribute_name'])) && is_wp_error($valid_attribute_name)) {
                            return $valid_attribute_name;
                        } elseif (taxonomy_exists(wc_attribute_taxonomy_name($attribute['attribute_name']))) {
                            return new WP_Error('error', sprintf(__('Slug "%s" is already in use. Change it, please.', 'woocommerce'), sanitize_title($attribute['attribute_name'])));
                        }

                        $wpdb->insert($wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute);

                        do_action('woocommerce_attribute_added', $wpdb->insert_id, $attribute);

                        $attribute_name = wc_sanitize_taxonomy_name('pa_' . $attribute['attribute_name']);

                        if (!taxonomy_exists($attribute_name)) {
                            $args = array(
                                'hierarchical' => true,
                                'show_ui'      => false,
                                'query_var'    => true,
                                'rewrite'      => false,
                            );
                            register_taxonomy($attribute_name, array('product'), $args);
                        }


                        flush_rewrite_rules();
                        delete_transient('wc_attribute_taxonomies');
                    endforeach;
                }
            }
        }

        function wc_valid_attribute_name($attribute_name)
        {
            if (!class_exists('WooCommerce')) {
                return false;
            }

            if (strlen($attribute_name) >= 28) {
                return new WP_Error('error', sprintf(__('Slug "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce'), sanitize_title($attribute_name)));
            } elseif (wc_check_if_attribute_name_is_reserved($attribute_name)) {
                return new WP_Error('error', sprintf(__('Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce'), sanitize_title($attribute_name)));
            }

            return true;
        }

        function import_content()
        {
            set_time_limit(0);
            if (!defined('WP_LOAD_IMPORTERS')) {
                define('WP_LOAD_IMPORTERS', true);
            }
            $optionid = isset($_POST['optionid']) ? $_POST['optionid'] : "";
            add_filter('intermediate_image_sizes_advanced', array($this, 'no_resize_image'));

            if ($optionid != "") {
                $demo = $this->data_demos[$optionid];
                if (is_array($demo)) {
                    $this->before_content_import();

                    $filepath = dirname(__FILE__) . '/data/' . $demo['file_content'];
                    if (file_exists($filepath)) {
                        try {
                            $this->include_importer_classes();
                            $importer = new KT_WP_Import();
                            $importer->fetch_attachments = true;
                            $importer->import($filepath);
                            echo 'Successful Import Demo Content';
                        } catch (Exception $e) {
                            echo 'Caught exception: ', $e->getMessage(), "\n";
                        }

                    }

                }
            }
            wp_die();
        }

        function import_theme_options()
        {

            $optionid = isset($_POST['optionid']) ? $_POST['optionid'] : "";
            if ($optionid != "") {
                $demo = $this->data_demos[$optionid];
                if (!is_array($demo)) {
                    return;
                }
            }

            $url = $demo['theme_option'];


            $theme_options_url = untrailingslashit($url);
            $theme_options_txt = wp_remote_get($theme_options_url);


            if (empty($theme_options_txt)) {
                wp_die();
            }

            $theme_option_data = $theme_options_txt['body'];
            $options = json_decode($theme_option_data, true);
            $options = maybe_unserialize($options);
            if (!empty($options)) {
                update_option('digitalworld', $options);
            }

            wp_die();
        }

        /* import Sidebar Content */
        function import_widget()
        {
            $optionid = isset($_POST['optionid']) ? $_POST['optionid'] : "";
            if ($optionid != "") {
                $demo = $this->data_demos[$optionid];
                if (!is_array($demo)) {
                    return;
                }
            }

            $url = $demo['widget'];

            // Get file contents and decode
            $data = file_get_contents($url);
            $data = json_decode($data);

            global $wp_registered_sidebars;
            // Have valid data?
            // If no data or could not decode
            if (empty($data) || !is_object($data)) {
                wp_die();
            }
            // Hook before import
            do_action('wie_before_import');
            $data = apply_filters('wie_import_data', $data);

            // Get all available widgets site supports
            $available_widgets = $this->kt_available_widgets();

            // Get all existing widget instances
            $widget_instances = array();
            foreach ($available_widgets as $widget_data) {
                $widget_instances[$widget_data['id_base']] = get_option('widget_' . $widget_data['id_base']);
            }

            // Begin results
            $results = array();

            // Loop import data's sidebars
            foreach ($data as $sidebar_id => $widgets) {

                // Skip inactive widgets
                // (should not be in export file)
                if ('wp_inactive_widgets' == $sidebar_id) {
                    continue;
                }

                // Check if sidebar is available on this site
                // Otherwise add widgets to inactive, and say so
                if (isset($wp_registered_sidebars[$sidebar_id])) {
                    $sidebar_available = true;
                    $use_sidebar_id = $sidebar_id;
                    $sidebar_message_type = 'success';
                    $sidebar_message = '';
                } else {
                    $sidebar_available = false;
                    $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
                    $sidebar_message_type = 'error';
                    $sidebar_message = __('Sidebar does not exist in theme (using Inactive)', 'widget-importer-exporter');
                }

                // Result for sidebar
                $results[$sidebar_id]['name'] = !empty($wp_registered_sidebars[$sidebar_id]['name']) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
                $results[$sidebar_id]['message_type'] = $sidebar_message_type;
                $results[$sidebar_id]['message'] = $sidebar_message;
                $results[$sidebar_id]['widgets'] = array();

                // Loop widgets
                foreach ($widgets as $widget_instance_id => $widget) {

                    $fail = false;

                    // Get id_base (remove -# from end) and instance ID number
                    $id_base = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                    $instance_id_number = str_replace($id_base . '-', '', $widget_instance_id);

                    // Does site support this widget?
                    if (!$fail && !isset($available_widgets[$id_base])) {
                        $fail = true;
                        $widget_message_type = 'error';
                        $widget_message = __('Site does not support widget', 'widget-importer-exporter'); // explain why widget not imported
                    }

                    // Filter to modify settings object before conversion to array and import
                    // Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
                    // Ideally the newer wie_widget_settings_array below will be used instead of this
                    $widget = apply_filters('wie_widget_settings', $widget); // object

                    // Convert multidimensional objects to multidimensional arrays
                    // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
                    // Without this, they are imported as objects and cause fatal error on Widgets page
                    // If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
                    // It is probably much more likely that arrays are used than objects, however
                    $widget = json_decode(json_encode($widget), true);

                    // Filter to modify settings array
                    // This is preferred over the older wie_widget_settings filter above
                    // Do before identical check because changes may make it identical to end result (such as URL replacements)
                    $widget = apply_filters('wie_widget_settings_array', $widget);

                    // Does widget with identical settings already exist in same sidebar?
                    if (!$fail && isset($widget_instances[$id_base])) {

                        // Get existing widgets in this sidebar
                        $sidebars_widgets = get_option('sidebars_widgets');
                        $sidebar_widgets = isset($sidebars_widgets[$use_sidebar_id]) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

                        // Loop widgets with ID base
                        $single_widget_instances = !empty($widget_instances[$id_base]) ? $widget_instances[$id_base] : array();
                        foreach ($single_widget_instances as $check_id => $check_widget) {

                            // Is widget in same sidebar and has identical settings?
                            if (in_array("$id_base-$check_id", $sidebar_widgets) && (array)$widget == $check_widget) {

                                $fail = true;
                                $widget_message_type = 'warning';
                                $widget_message = __('Widget already exists', 'widget-importer-exporter'); // explain why widget not imported

                                break;

                            }

                        }

                    }

                    // No failure
                    if (!$fail) {

                        // Add widget instance
                        $single_widget_instances = get_option('widget_' . $id_base); // all instances for that widget ID base, get fresh every time
                        $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array('_multiwidget' => 1); // start fresh if have to
                        $single_widget_instances[] = $widget; // add it

                        // Get the key it was given
                        end($single_widget_instances);
                        $new_instance_id_number = key($single_widget_instances);

                        // If key is 0, make it 1
                        // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                        if ('0' === strval($new_instance_id_number)) {
                            $new_instance_id_number = 1;
                            $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                            unset($single_widget_instances[0]);
                        }

                        // Move _multiwidget to end of array for uniformity
                        if (isset($single_widget_instances['_multiwidget'])) {
                            $multiwidget = $single_widget_instances['_multiwidget'];
                            unset($single_widget_instances['_multiwidget']);
                            $single_widget_instances['_multiwidget'] = $multiwidget;
                        }

                        // Update option with new widget
                        update_option('widget_' . $id_base, $single_widget_instances);

                        // Assign widget instance to sidebar
                        $sidebars_widgets = get_option('sidebars_widgets'); // which sidebars have which widgets, get fresh every time
                        $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                        $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                        update_option('sidebars_widgets', $sidebars_widgets); // save the amended data

                        // After widget import action
                        $after_widget_import = array(
                            'sidebar'           => $use_sidebar_id,
                            'sidebar_old'       => $sidebar_id,
                            'widget'            => $widget,
                            'widget_type'       => $id_base,
                            'widget_id'         => $new_instance_id,
                            'widget_id_old'     => $widget_instance_id,
                            'widget_id_num'     => $new_instance_id_number,
                            'widget_id_num_old' => $instance_id_number
                        );
                        do_action('wie_after_widget_import', $after_widget_import);

                        // Success message
                        if ($sidebar_available) {
                            $widget_message_type = 'success';
                            $widget_message = __('Imported', 'widget-importer-exporter');
                        } else {
                            $widget_message_type = 'warning';
                            $widget_message = __('Imported to Inactive', 'widget-importer-exporter');
                        }

                    }

                    // Result for widget instance
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset($available_widgets[$id_base]['name']) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = !empty($widget['title']) ? $widget['title'] : __('No Title', 'widget-importer-exporter'); // show "No Title" if widget instance is untitled
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

                }

            }

            // Hook after import
            do_action('wie_after_import');

            // Return results
            return apply_filters('wie_import_results', $results);


            wp_die();
        }

        function kt_available_widgets()
        {

            global $wp_registered_widget_controls;

            $widget_controls = $wp_registered_widget_controls;

            $available_widgets = array();

            foreach ($widget_controls as $widget) {

                if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) { // no dupes

                    $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                    $available_widgets[$widget['id_base']]['name'] = $widget['name'];

                }

            }

            return apply_filters('wie_available_widgets', $available_widgets);

        }

        /* Import Revolution Slider */
        function import_revslider()
        {

            if (class_exists('UniteFunctionsRev') && class_exists('ZipArchive')) {
                global $wpdb;
                $updateAnim = true;
                $updateStatic = true;
                $rev_directory = dirname(__FILE__) . '/data/revsliders/';
                $rev_files = array();

                $rev_db = new RevSliderDB();

                foreach (glob($rev_directory . '*.zip') as $filename) {
                    $filename = basename($filename);
                    $allow_import = false;

                    $arr_filename = explode('_', $filename);
                    $slider_new_id = absint($arr_filename[0]);
                    if ($slider_new_id > 0) {
                        $response = $rev_db->fetch(RevSliderGlobals::$table_sliders, 'id=' + $slider_new_id);
                        if (empty($response)) { /* not exists */
                            $rev_files_ids[] = $slider_new_id;
                            $allow_import = true;
                        } else {
                            /* do nothing */
                        }
                    } else {
                        $rev_files_ids[] = 0;
                        $allow_import = true;
                    }

                    if ($allow_import) {
                        $rev_files[] = $rev_directory . $filename;
                    }
                }

                foreach ($rev_files as $index => $rev_file) {

                    $filepath = $rev_file;

                    $zip = new ZipArchive;
                    $importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);

                    if ($importZip === true) {

                        $slider_export = $zip->getStream('slider_export.txt');
                        $custom_animations = $zip->getStream('custom_animations.txt');
                        $dynamic_captions = $zip->getStream('dynamic-captions.css');
                        $static_captions = $zip->getStream('static-captions.css');

                        $content = '';
                        $animations = '';
                        $dynamic = '';
                        $static = '';

                        while (!feof($slider_export)) $content .= fread($slider_export, 1024);
                        if ($custom_animations) {
                            while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024);
                        }
                        if ($dynamic_captions) {
                            while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024);
                        }
                        if ($static_captions) {
                            while (!feof($static_captions)) $static .= fread($static_captions, 1024);
                        }

                        fclose($slider_export);
                        if ($custom_animations) {
                            fclose($custom_animations);
                        }
                        if ($dynamic_captions) {
                            fclose($dynamic_captions);
                        }
                        if ($static_captions) {
                            fclose($static_captions);
                        }

                    } else {
                        $content = @file_get_contents($filepath);
                    }

                    if ($importZip === true) {
                        $db = new UniteDBRev();

                        $animations = @unserialize($animations);
                        if (!empty($animations)) {
                            foreach ($animations as $key => $animation) {
                                $exist = $db->fetch(GlobalsRevSlider::$table_layer_anims, "handle = '" . $animation['handle'] . "'");
                                if (!empty($exist)) {
                                    if ($updateAnim == 'true') {
                                        $arrUpdate = array();
                                        $arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
                                        $db->update(GlobalsRevSlider::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));

                                        $id = $exist['0']['id'];
                                    } else {
                                        $arrInsert = array();
                                        $arrInsert["handle"] = 'copy_' . $animation['handle'];
                                        $arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

                                        $id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
                                    }
                                } else {
                                    $arrInsert = array();
                                    $arrInsert["handle"] = $animation['handle'];
                                    $arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

                                    $id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
                                }

                                $content = str_replace(array('customin-' . $animation['id'], 'customout-' . $animation['id']), array('customin-' . $id, 'customout-' . $id), $content);
                            }
                        } else {

                        }

                        if (!empty($static)) {
                            if (isset($updateStatic) && $updateStatic == 'true') {
                                RevOperations::updateStaticCss($static);
                            } else {
                                $static_cur = RevOperations::getStaticCss();
                                $static = $static_cur . "\n" . $static;
                                RevOperations::updateStaticCss($static);
                            }
                        }

                        $dynamicCss = UniteCssParserRev::parseCssToArray($dynamic);

                        if (is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0) {
                            foreach ($dynamicCss as $class => $styles) {
                                $class = trim($class);

                                if ((strpos($class, ':hover') === false && strpos($class, ':') !== false) ||
                                    strpos($class, " ") !== false ||
                                    strpos($class, ".tp-caption") === false ||
                                    (strpos($class, ".") === false || strpos($class, "#") !== false) ||
                                    strpos($class, ">") !== false
                                ) {
                                    continue;
                                }


                                if (strpos($class, ':hover') !== false) {
                                    $class = trim(str_replace(':hover', '', $class));
                                    $arrInsert = array();
                                    $arrInsert["hover"] = json_encode($styles);
                                    $arrInsert["settings"] = json_encode(array('hover' => 'true'));
                                } else {
                                    $arrInsert = array();
                                    $arrInsert["params"] = json_encode($styles);
                                }

                                $result = $db->fetch(GlobalsRevSlider::$table_css, "handle = '" . $class . "'");

                                if (!empty($result)) {
                                    $db->update(GlobalsRevSlider::$table_css, $arrInsert, array('handle' => $class));
                                } else {
                                    $arrInsert["handle"] = $class;
                                    $db->insert(GlobalsRevSlider::$table_css, $arrInsert);
                                }
                            }

                        } else {

                        }
                    }

                    $content = preg_replace_callback('!s:(\d+):"(.*?)";!', array('RevSliderSlider', 'clear_error_in_string'), $content); //clear errors in string

                    $arrSlider = @unserialize($content);
                    $sliderParams = $arrSlider["params"];

                    if (isset($sliderParams["background_image"]))
                        $sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath($sliderParams["background_image"]);

                    $json_params = json_encode($sliderParams);


                    $arrInsert = array();
                    $arrInsert["params"] = $json_params;
                    $arrInsert["title"] = UniteFunctionsRev::getVal($sliderParams, "title", "Slider1");
                    $arrInsert["alias"] = UniteFunctionsRev::getVal($sliderParams, "alias", "slider1");
                    if ($rev_files_ids[$index] != 0) {
                        $arrInsert["id"] = $rev_files_ids[$index];
                        $arrFormat = array('%s', '%s', '%s', '%d');
                    } else {
                        $arrFormat = array('%s', '%s', '%s');
                    }
                    $sliderID = $wpdb->insert(GlobalsRevSlider::$table_sliders, $arrInsert, $arrFormat);
                    $sliderID = $wpdb->insert_id;


                    /* create all slides */
                    $arrSlides = $arrSlider["slides"];

                    $alreadyImported = array();

                    foreach ($arrSlides as $slide) {

                        $params = $slide["params"];
                        $layers = $slide["layers"];

                        if (isset($params["image"])) {
                            if (trim($params["image"]) !== '') {
                                if ($importZip === true) {
                                    $image = $zip->getStream('images/' . $params["image"]);
                                    if (!$image) {
                                        echo $params["image"] . ' not found!<br>';
                                    } else {
                                        if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]])) {
                                            $importImage = UniteFunctionsWPRev::import_media('zip://' . $filepath . "#" . 'images/' . $params["image"], $sliderParams["alias"] . '/');

                                            if ($importImage !== false) {
                                                $alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]] = $importImage['path'];

                                                $params["image"] = $importImage['path'];
                                            }
                                        } else {
                                            $params["image"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]];
                                        }
                                    }
                                }
                            }
                            $params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
                        }

                        foreach ($layers as $key => $layer) {
                            if (isset($layer["image_url"])) {
                                if (trim($layer["image_url"]) !== '') {
                                    if ($importZip === true) {
                                        $image_url = $zip->getStream('images/' . $layer["image_url"]);
                                        if (!$image_url) {
                                            echo $layer["image_url"] . ' not found!<br>';
                                        } else {
                                            if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]])) {
                                                $importImage = UniteFunctionsWPRev::import_media('zip://' . $filepath . "#" . 'images/' . $layer["image_url"], $sliderParams["alias"] . '/');

                                                if ($importImage !== false) {
                                                    $alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]] = $importImage['path'];

                                                    $layer["image_url"] = $importImage['path'];
                                                }
                                            } else {
                                                $layer["image_url"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]];
                                            }
                                        }
                                    }
                                }
                                $layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
                                $layers[$key] = $layer;
                            }
                        }

                        /* create new slide */
                        $arrCreate = array();
                        $arrCreate["slider_id"] = $sliderID;
                        $arrCreate["slide_order"] = $slide["slide_order"];
                        $arrCreate["layers"] = json_encode($layers);
                        $arrCreate["params"] = json_encode($params);

                        $wpdb->insert(GlobalsRevSlider::$table_slides, $arrCreate);
                    }
                }
            }
            die();
        }

        function import_config()
        {
            $optionid = isset($_POST['optionid']) ? $_POST['optionid'] : "";
            if ($optionid != "") {
                $demo = $this->data_demos[$optionid];
                if (!is_array($demo)) {
                    return;
                }
            }

            $this->woocommerce_settings();
            $this->menu_locations($demo);
            $this->update_options($demo);
            wp_die();
        }

        /* WooCommerce Settings */
        function woocommerce_settings()
        {
            $woopages = array(
                'woocommerce_shop_page_id'    => 'Shop'
                , 'woocommerce_cart_page_id'      => 'Shopping cart'
                , 'woocommerce_checkout_page_id'  => 'Checkout'
                , 'woocommerce_myaccount_page_id' => 'My Account'
            );
            foreach ($woopages as $woo_page_name => $woo_page_title) {
                $woopage = get_page_by_title($woo_page_title);
                if (isset($woopage->ID) && $woopage->ID) {
                    update_option($woo_page_name, $woopage->ID);
                }
            }

            if (class_exists('YITH_Woocompare')) {
                update_option('yith_woocompare_compare_button_in_products_list', 'yes');
                update_option('yith_woocompare_is_button', 'button');
            }

            if (class_exists('WC_Admin_Notices')) {
                WC_Admin_Notices::remove_notice('install');
            }
            delete_transient('_wc_activation_redirect');
            /* UPDTE IMAGE SIZE */
            $catalog = array(
				'width' 	=> '300',	// px
				'height'	=> '300',	// px
				'crop'		=> 1 		// true
			);

			$single = array(
				'width' 	=> '600',	// px
				'height'	=> '600',	// px
				'crop'		=> 1 		// true
			);

			$thumbnail = array(
				'width' 	=> '180',	// px
				'height'	=> '180',	// px
				'crop'		=> 1 		// false
			);

            // Image sizes
            update_option('shop_catalog_image_size', $catalog);        // Product category thumbs
            update_option('shop_single_image_size', $single);        // Single product image
            update_option('shop_thumbnail_image_size', $thumbnail);    // Image gallery thumbs

            flush_rewrite_rules();
        }

        /* Menu Locations */
        /**
        function menu_locations($demo)
        {
            if (isset($demo['menus']) && is_array($demo['menus'])) {
                $locations = get_theme_mod('nav_menu_locations');
                $menus = wp_get_nav_menus();
                if ($menus) {
                    foreach ($menus as $menu) {
                        foreach ($demo['menus'] as $key => $value) {
                            if ($menu->name == $value) {
                                $locations[$key] = $menu->term_id;
                            }
                        }
                    }
                }
                set_theme_mod('nav_menu_locations', $locations);
            }

        }**/
        
        
        function menu_locations($demo)
        {
            $menu_location = array();
        	$locations = get_theme_mod('nav_menu_locations');
            $menus = wp_get_nav_menus();
            
            if( isset( $demo['menu_locations'] ) && is_array( $demo['menu_locations'] ) ){
                if ($menus) {
                    foreach ($menus as $menu) {
                        foreach ($demo['menu_locations'] as $key => $value) {
                            if ($menu->name == $value) {
                                $menu_location[$key] = $menu->term_id;
                            }
                        }
                    }
                }
                
                set_theme_mod('nav_menu_locations', $menu_location);
            }else if ( isset( $demo['menus'] ) && is_array( $demo['menus'] ) ) {
                $menu_location = $locations;
                set_theme_mod('nav_menu_locations', $menu_location);
            }

        }

        /* Update Options */
        function update_options($demo)
        {
            if (isset($demo['homepage']) && $demo['homepage'] != "") {
                // Home page
                $homepage = get_page_by_title($demo['homepage']);
                if (isset($homepage) && $homepage->ID) {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $homepage->ID);
                }
            }

            // Blog page
            if (isset($demo['blogpage']) && $demo['blogpage'] != "") {
                $post_page = get_page_by_title($demo['blogpage']);
                if (isset($post_page) && $post_page->ID) {
                    update_option('show_on_front', 'page');
                    update_option('page_for_posts', $post_page->ID);
                }
            }
        }
    }

    new DIGITALWORLD_IMPORTER();
}