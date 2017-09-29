<?php
if( !class_exists('Digitalworld_Visual_Composer')){
    class Digitalworld_Visual_Composer{

        public function __construct() {
            $this->define_constants();
            add_filter('vc_google_fonts_get_fonts_filter',array($this,'vc_fonts'));
            add_action( 'vc_after_mapping', array( &$this, 'params' ) );
            add_action( 'vc_after_mapping', array( &$this, 'autocomplete' ) );
            /* Custom font Icon*/
            add_filter( 'vc_iconpicker-type-digitalworldcustomfonts', array(&$this,'iconpicker_type_digitalworld_customfonts') );
            $this->map_shortcode();
        }

        /**
         * Define  Constants.
         */
        private function define_constants() {
            $this->define( 'DIGITALWORLD_SHORTCODE_PREVIEW',get_template_directory_uri()."/famework/assets/images/shortcode-privews/" );
            $this->define( 'DIGITALWORLD_PRODUCT_STYLE_PREVIEW',get_template_directory_uri()."/woocommerce/product-styles/" );

        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        function  params(){
            if( function_exists('digitalworld_toolkit_vc_param')){
                digitalworld_toolkit_vc_param( 'taxonomy',array( &$this, 'taxonomy_field' ) );
                digitalworld_toolkit_vc_param( 'animate',array( &$this, 'animate_field' ) );
                digitalworld_toolkit_vc_param( 'uniqid',array( &$this, 'uniqid_field' ) );
                digitalworld_toolkit_vc_param( 'select_preview',array( &$this, 'select_preview_field' ) );

            }


        }

        /**
         * load param autocomplete render
         * */
        public function autocomplete(){
            add_filter( 'vc_autocomplete_digitalworld_products_ids_callback', array(&$this, 'productIdAutocompleteSuggester'), 10, 1 );
            add_filter( 'vc_autocomplete_digitalworld_products_ids_render', array(&$this, 'productIdAutocompleteRender'), 10, 1 );
            add_filter( 'vc_autocomplete_digitalworld_deal_ids_callback', array(&$this, 'productIdAutocompleteSuggester'), 10, 1 );
            add_filter( 'vc_autocomplete_digitalworld_deal_ids_render', array(&$this, 'productIdAutocompleteRender'), 10, 1 );
        }

        /*
         * taxonomy_field
         * */
        public function taxonomy_field( $settings, $value ){
            $dependency = '';
            $value_arr = $value;
            if ( ! is_array($value_arr) ) {
                $value_arr = array_map( 'trim', explode(',', $value_arr) );
            }
            $output = '';
            if( isset( $settings['hide_empty'] ) && $settings['hide_empty'] ){
                $settings['hide_empty'] = 1;
            }else{
                $settings['hide_empty'] = 0;
            }
            if ( ! empty($settings['taxonomy']) ) {
                $terms_fields = array();
                if(isset($settings['placeholder']) && $settings['placeholder']){
                    $terms_fields[] = "<option value=''>".$settings['placeholder']."</option>";
                }
                $terms = get_terms( $settings['taxonomy'] , array('hide_empty' => false, 'parent' => $settings['parent'], 'hide_empty' => $settings['hide_empty'] ));
                if ( $terms && !is_wp_error($terms) ) {
                    foreach( $terms as $term ) {
                        $selected = (in_array( $term->slug, $value_arr )) ? ' selected="selected"' : '';
                        $terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
                    }
                }
                $size = (!empty($settings['size'])) ? 'size="'.$settings['size'].'"' : '';
                $multiple = (!empty($settings['multiple'])) ? 'multiple="multiple"' : '';
                $uniqeID    = uniqid();
                $output = '<select style="width:100%;" id="vc_taxonomy-'.$uniqeID.'" '.$multiple.' '.$size.' name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'_field" '.$dependency.'>'
                    .implode( $terms_fields )
                    .'</select>';
                $output .= '<script type="text/javascript">jQuery("#vc_taxonomy-' . $uniqeID . '").chosen();</script>';
            }
            return $output;
        }
        public function animate_field( $settings, $value ) {
            // Animate list
            $animate_arr = array(
                'bounce',
                'flash',
                'pulse',
                'rubberBand',
                'shake',
                'headShake',
                'swing',
                'tada',
                'wobble',
                'jello',
                'bounceIn',
                'bounceInDown',
                'bounceInLeft',
                'bounceInRight',
                'bounceInUp',
                'bounceOut',
                'bounceOutDown',
                'bounceOutLeft',
                'bounceOutRight',
                'bounceOutUp',
                'fadeIn',
                'fadeInDown',
                'fadeInDownBig',
                'fadeInLeft',
                'fadeInLeftBig',
                'fadeInRight',
                'fadeInRightBig',
                'fadeInUp',
                'fadeInUpBig',
                'fadeOut',
                'fadeOutDown',
                'fadeOutDownBig',
                'fadeOutLeft',
                'fadeOutLeftBig',
                'fadeOutRight',
                'fadeOutRightBig',
                'fadeOutUp',
                'fadeOutUpBig',
                'flipInX',
                'flipInY',
                'flipOutX',
                'flipOutY',
                'lightSpeedIn',
                'lightSpeedOut',
                'rotateIn',
                'rotateInDownLeft',
                'rotateInDownRight',
                'rotateInUpLeft',
                'rotateInUpRight',
                'rotateOut',
                'rotateOutDownLeft',
                'rotateOutDownRight',
                'rotateOutUpLeft',
                'rotateOutUpRight',
                'hinge',
                'rollIn',
                'rollOut',
                'zoomIn',
                'zoomInDown',
                'zoomInLeft',
                'zoomInRight',
                'zoomInUp',
                'zoomOut',
                'zoomOutDown',
                'zoomOutLeft',
                'zoomOutRight',
                'zoomOutUp',
                'slideInDown',
                'slideInLeft',
                'slideInRight',
                'slideInUp',
                'slideOutDown',
                'slideOutLeft',
                'slideOutRight',
                'slideOutUp',
            );
            $uniqeID    = uniqid();
            ob_start();
            ?>
            <select id="kt_animate-<?php echo $uniqeID ?>" name="<?php echo $settings['param_name'] ?>" class="wpb_vc_param_value wpb-input wpb-select <?php echo $settings['param_name']; ?> <?php echo $settings['type'] ?>_field">
                <option value=""><?php esc_html_e( 'None', 'digitalworld' ) ?></option>
                <?php foreach( $animate_arr as $animate ):
                    $selected = ( $value == $animate ) ? ' selected="selected"' : '';
                    ?>
                    <option value='<?php echo esc_attr( $animate)  ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $animate ) ?></option>
                <?php endforeach; ?>
            </select>
            <?php
            return ob_get_clean();
        }
        public function uniqid_field($settings, $value){
            if( ! $value){
                $value = uniqid(hash('crc32', $settings[ 'param_name' ]).'-');
            }
            $output = '<input type="text" class="wpb_vc_param_value textfield" name="'.$settings[ 'param_name' ].'" value="'.esc_attr($value).'" />';
            return $output;
        }
        public function select_preview_field($settings, $value) {
            ob_start();
            // Get menus list
            $options = $settings['value'];
            $default = $settings['default'];
            if(is_array($options) && count( $options) > 0 ){
                $uniqeID    = uniqid();
                $i = 0;
                ?>
                <div class="container-select_preview">
                    <select id="kt_select_preview-<?php echo $uniqeID ?>" name="<?php echo $settings['param_name'] ?>" class="vc_select_image wpb_vc_param_value wpb-input wpb-select <?php $settings['param_name'] ?> <?php echo $settings['type'] ?>_field">
                        <?php foreach( $options as $k => $option ):  ?>
                            <?php
                            if( $i == 0 ){
                                $first_value = $k;
                            }
                            $i++;
                            ?>
                            <?php $selected = ( $k == $value ) ? ' selected="selected"' : '';?>
                            <option data-img="<?php echo esc_url($option['img']);?>" value='<?php echo esc_attr( $k )  ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['alt'] ) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="image-preview">
                        <?php if( isset($options[$value]) && $options[$value] && (isset($options[$value]['img']))):?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;" src="<?php echo esc_url($options[$value]['img']);?>" alt="">
                        <?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;" src="<?php echo esc_url($options[$default]['img']);?>" alt="">
                        <?php endif;?>
                    </div>
                </div>
                <script type="text/javascript">
                    (function($) {
                        "use strict";
                        $(document).on('change','#kt_select_preview-<?php echo $uniqeID ?>',function(){
                            var url = $(this).find(':selected').data('img');
                            $(this).closest('.container-select_preview').find('.image-preview img').attr('src',url);
                        });
                    })(jQuery);
                </script>
                <?php
            }
            return ob_get_clean();
        }
        /**
         * Suggester for autocomplete by id/name/title/sku
         * @since 1.0
         *
         * @param $query
         * @author Reapple
         * @return array - id's from products with title/sku.
         */
        public function productIdAutocompleteSuggester( $query ) {
            global $wpdb;
            $product_id = (int) $query;
            $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
    					FROM {$wpdb->posts} AS a
    					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
    					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );
            $results = array();
            if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
                foreach ( $post_meta_infos as $value ) {
                    $data = array();
                    $data['value'] = $value['id'];
                    $data['label'] = esc_html__( 'Id', 'digitalworld' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'digitalworld' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'digitalworld' ) . ': ' . $value['sku'] : '' );
                    $results[] = $data;
                }
            }
            return $results;
        }
        /**
         * Find product by id
         * @since 1.0
         *
         * @param $query
         * @author Reapple
         *
         * @return bool|array
         */
        public function productIdAutocompleteRender( $query ) {
            $query = trim( $query['value'] ); // get value from requested
            if ( ! empty( $query ) ) {
                // get product
                $product_object = wc_get_product( (int) $query );
                if ( is_object( $product_object ) ) {
                    $product_sku = $product_object->get_sku();
                    $product_title = $product_object->get_title();
                    $product_id = $product_object->id;
                    $product_sku_display = '';
                    if ( ! empty( $product_sku ) ) {
                        $product_sku_display = ' - ' . esc_html__( 'Sku', 'digitalworld' ) . ': ' . $product_sku;
                    }
                    $product_title_display = '';
                    if ( ! empty( $product_title ) ) {
                        $product_title_display = ' - ' . esc_html__( 'Title', 'digitalworld' ) . ': ' . $product_title;
                    }
                    $product_id_display = esc_html__( 'Id', 'digitalworld' ) . ': ' . $product_id;
                    $data = array();
                    $data['value'] = $product_id;
                    $data['label'] = $product_id_display . $product_title_display . $product_sku_display;
                    return ! empty( $data ) ? $data : false;
                }
                return false;
            }
            return false;
        }

        public function vc_fonts( $fonts_list ){
            /* Gotham */
            $Gotham = new stdClass();
            $Gotham->font_family = "Gotham";
            $Gotham->font_styles = "100,300,400,600,700";
            $Gotham->font_types = "300 Light:300:light,400 Normal:400:normal";

            $fonts = array($Gotham);
            return array_merge($fonts_list, $fonts);
        }
        /* Custom Font icon*/
        function iconpicker_type_digitalworld_customfonts($icons){
            $customfonts_icons = array(
                array('flaticon-like' => '01'),
                array('flaticon-present' => '02'),
                array('flaticon-money-bag' => '03'),
                array('flaticon-transport' => '04'),
                array('flaticon-truck' => '05'),
            );
            return array_merge($icons, $customfonts_icons);
        }
        public static function map_shortcode() {
            /* ADD PARAM*/
            // Update parameters for Row.
            vc_add_params(
                'vc_single_image',
                array(
                    array(
                        'param_name'       => 'image_effect',
                        'heading'          => esc_html__( 'Effect', 'digitalworld' ),
                        'group'            => esc_html__( 'Image Effect', 'digitalworld' ),
                        'type'             => 'dropdown',
                        'value'      => array(
                            esc_html__( 'None', 'digitalworld' )      => '',
                            esc_html__( 'Effect1', 'digitalworld' )      => 'banner-effect  banner-effect1',
                            esc_html__( 'Effect2', 'digitalworld' )      => 'banner-effect banner-effect2',
                            esc_html__( 'Effect3', 'digitalworld' )      => 'banner-effect banner-effect3',
                            esc_html__( 'Effect4', 'digitalworld' )      => 'banner-effect banner-effect4',
                            esc_html__( 'Effect5', 'digitalworld' )      => 'banner-effect banner-effect5',
                            esc_html__( 'Effect6', 'digitalworld' )      => 'banner-effect banner-effect6',
                            esc_html__( 'Effect7', 'digitalworld' )      => 'banner-effect banner-effect7',
                            esc_html__( 'Effect8', 'digitalworld' )      => 'banner-effect banner-effect8',
                            esc_html__( 'Effect9', 'digitalworld' )      => 'banner-effect banner-effect9',
                            esc_html__( 'Effect10', 'digitalworld' )      => 'banner-effect banner-effect10',
                        ),
                        'sdt'=>''
                    )
                )
            );
            // Map new Tabs element.
            vc_map(
                array(
                    'name' => esc_html__( 'Digitalworld: Tabs', 'digitalworld' ),
                    'base' => 'digitalworld_tabs',
                    'icon' => 'icon-wpb-ui-tab-content',
                    'is_container' => true,
                    'show_settings_on_create' => false,
                    'as_parent' => array(
                        'only' => 'vc_tta_section',
                    ),
                    'category'    => esc_html__('Digitalworld Elements', 'digitalworld'),
                    'description' => esc_html__( 'Tabbed content', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Select style', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'Style 01', //DIGITALWORLD_SHORTCODE_PREVIEW
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'/digitalworld_tabs/default.jpg'
                                ),
                                'style2'=>array(
                                    'alt'=> 'Style 02', //DIGITALWORLD_SHORTCODE_PREVIEW
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'/digitalworld_tabs/layout2.jpg'
                                ),
                                'style3'=>array(
                                    'alt'=> 'Style 03', //DIGITALWORLD_SHORTCODE_PREVIEW
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'/digitalworld_tabs/layout3.jpg'
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'style',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Tabs title', 'digitalworld'),
                            'param_name'  => 'tab_title',
                            'value'       => '',
                            'admin_label' => false,
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => 'style2'
                            )
                        ),
                        array(
                            'type'        => 'animate',
                            'heading'     => esc_html__('Tabs animate', 'digitalworld'),
                            'param_name'  => 'tab_animate',
                            'value'       => '',
                            'admin_label' => false,
                        ),
                        array(
                            'param_name' => 'ajax_check',
                            'heading'    => esc_html__( 'Using Ajax Tabs', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Yes', 'digitalworld')  => '1',
                                esc_html__('No', 'digitalworld' )  => '0',
                            ),
                            'std' => '0'
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Extra class name', 'digitalworld'),
                            'param_name'  => 'el_class',
                            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'digitalworld' ),
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('CSS box', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design Options', 'digitalworld' ),
                        ),
                        array(
                            'param_name'       => 'tabs_custom_id',
                            'heading'          => esc_html__('Hidden ID', 'digitalworld'),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    ),
                    'js_view' => 'VcBackendTtaTabsView',
                    'custom_markup' => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
                        . '<ul class="vc_tta-tabs-list">'
                        . '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
                        . '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
                    'default_content' => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'digitalworld' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'digitalworld' ), 2 ) . '"][/vc_tta_section]
                    ',
                    'admin_enqueue_js' => array(
                        vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
                    ),
                )
            );
            // Map new Banner element.
            vc_map(
                array(
                    'name'                    => esc_html__( 'Digitalworld: Banner', 'digitalworld' ),
                    'icon'                    => 'fa fa-table',
                    'base'                    => 'digitalworld_banner',
                    'category'                => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'as_parent'               => array( 'only' => 'digitalworld_heading,vc_custom_heading,vc_column_text' ),
                    'content_element'         => true,
                    'show_settings_on_create' => true,
                    'js_view'                 => 'VcColumnView',
                    'params'                  => array(

                        array(
                            'param_name' => 'banner_image',
                            'heading'    => esc_html__( 'Banner Image', 'digitalworld' ),
                            'type'       => 'attach_image',
                            'admin_label' => true
                        ),
                        array(
                            'param_name' => 'style',
                            'heading'    => esc_html__( 'Content position', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Default', 'digitalworld')         => '',
                                esc_html__('Content rotate', 'digitalworld' ) => 'rotate',
                            ),
                            'std' => ''
                        ),

                        array(
                            'param_name' => 'content_position',
                            'heading'    => esc_html__( 'Content position', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Left', 'digitalworld' )    => 'left',
                                esc_html__('Right', 'digitalworld')    => 'right',
                            ),
                            'std' => 'left',
                            "dependency"  => array("element" => "style", "value" => array( '')),
                        ),
                        array(
                            'param_name' => 'text_align',
                            'heading'    => esc_html__( 'Text align', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Left', 'digitalworld' )    => 'text-left',
                                esc_html__('Right', 'digitalworld')    => 'text-right',
                                esc_html__('Right', 'digitalworld')    => 'text-center',
                            ),
                            'std' => 'left',
                            "dependency"  => array("element" => "style", "value" => array( '')),
                        ),
                        array(
                            'heading'     => esc_html__( 'Link to', 'digitalworld' ),
                            'type'        => 'textfield',
                            'param_name'  => 'banner_link',
                        ),
                        array(
                            'heading'     => esc_html__( 'Extra Class Name', 'digitalworld' ),
                            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'digitalworld' ),
                            'type'        => 'textfield',
                            'param_name'  => 'el_class',
                        ),
                        'css' => array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__( 'Css', 'digitalworld' ),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design options', 'digitalworld' ),
                        ),
                        array(
                            'param_name'       => 'banner_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            // Map new Products
            // CUSTOM PRODUCT SIZE
            $product_size_width_list  = array();
            $width = 300;
            $height = 300;
            $crop = 1;
            if( function_exists( 'wc_get_image_size' ) ){
                $size = wc_get_image_size( 'shop_catalog' );
                $width   = isset( $size[ 'width' ] ) ? $size[ 'width' ] : $width;
                $height  = isset( $size[ 'height' ] ) ? $size[ 'height' ] : $height;
                $crop    = isset( $size[ 'crop' ] ) ? $size[ 'crop' ] : $crop;
            }
            for( $i = 100; $i < $width; $i= $i+10){
                array_push($product_size_width_list, $i);
            }
            $product_size_list = array();
            $product_size_list[$width.'x'.$height] = $width.'x'.$height;
            foreach( $product_size_width_list as $k => $w ){
                $w = intval( $w );
                if(isset($width) && $width > 0){
                    $h = round( $height * $w / $width ) ;
                }else{
                    $h = $w;
                }
                $product_size_list[$w.'x'.$h] = $w.'x'.$h;
            }
            $product_size_list['Custom'] = 'custom';
            $attributes_tax = array();
            if( function_exists('wc_get_attribute_taxonomies')){
                $attributes_tax = wc_get_attribute_taxonomies();
            }

            $attributes = array();
            if( is_array($attributes_tax) && count( $attributes_tax ) > 0 ){
                foreach ( $attributes_tax as $attribute ) {
                    $attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
                }
            }


            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Products', 'digitalworld' ),
                    'base'     => 'digitalworld_products', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a product list.', 'digitalworld' ),
                    'params' => array(
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Product List style', 'digitalworld' ),
                            'param_name'  => 'productsliststyle',
                            'value'       => array(
                                esc_html__('Grid', 'digitalworld')         => 'grid',
                                esc_html__('Owl Carousel', 'digitalworld') => 'owl',
                            ),
                            'description' => esc_html__( 'Select a style for list', 'digitalworld' ),
                            'admin_label' => true,
                            'std'         => 'grid'
                        ),
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Product style', 'digitalworld' ),
                            'value' => array(
                                '1'=>array(
                                    'alt'=> __( 'Style 01', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-1.jpg'
                                ),
                                '2'=>array(
                                    'alt'=> __( 'Style 02', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-2.jpg'
                                ),
                                '3'=>array(
                                    'alt'=> __( 'Style 03', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-3.jpg'
                                ),
                                '4'=>array(
                                    'alt'=> __( 'Style 04', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-4.jpg'
                                ),
                                '5'=>array(
                                    'alt'=> __( 'Style 05', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-5.jpg'
                                ),
                                '6'=>array(
                                    'alt'=> __( 'Style 06', 'digitalworld' ),
                                    'img'=> DIGITALWORLD_PRODUCT_STYLE_PREVIEW.'content-product-style-6.jpg'
                                ),
                            ),
                            'default'       =>'1',
                            'admin_label' => true,
                            'param_name' => 'product_style',
                            'description' => esc_html__( 'Select a style for product item', 'digitalworld' ),
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Image size', 'digitalworld' ),
                            'param_name'  => 'product_image_size',
                            'value'       => $product_size_list,
                            'description' => esc_html__( 'Select a size for product', 'digitalworld' ),
                            'admin_label' => true,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Width", 'digitalworld'),
                            "param_name"  => "product_custom_thumb_width",
                            "value"       => $width,
                            "suffix"      => esc_html__("px", 'digitalworld'),
                            "dependency"  => array("element" => "product_image_size", "value" => array( 'custom' )),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Height", 'digitalworld'),
                            "param_name"  => "product_custom_thumb_height",
                            "value"       => $height,
                            "suffix"      => esc_html__("px", 'digitalworld'),
                            "dependency"  => array("element" => "product_image_size", "value" => array( 'custom' )),
                        ),
                        /*Products */
                        array(
                            "type"        => "taxonomy",
                            "taxonomy"    => "product_cat",
                            "class"       => "",
                            "heading"     => esc_html__("Product Category", 'digitalworld'),
                            "param_name"  => "taxonomy",
                            "value"       => '',
                            'parent'      => '',
                            'multiple'    => true,
                            'hide_empty'  => false,
                            'placeholder' => esc_html__('Choose category', 'digitalworld'),
                            "description" => esc_html__("Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'digitalworld'),
                            'std'         => '',
                            'group'       => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        array(
                            'type'        =>    'dropdown',
                            'heading'     =>    esc_html__( 'Target', 'digitalworld' ),
                            'param_name'  =>    'target',
                            'value'       => array(
                                esc_html__( 'Best Selling Products', 'digitalworld' ) => 'best-selling',
                                esc_html__( 'Top Rated Products', 'digitalworld' )    => 'top-rated',
                                esc_html__( 'Recent Products', 'digitalworld' )       => 'recent-product',
                                esc_html__( 'Product Category', 'digitalworld' )      => 'product-category',
                                esc_html__( 'Products', 'digitalworld' )              => 'products',
                                esc_html__( 'Featured Products', 'digitalworld' )     => 'featured_products',
                                esc_html__( 'On Sale', 'digitalworld' )               => 'on_sale',
                            ),
                            'description'   =>  esc_html__( 'Choose the target to filter products', 'digitalworld' ),
                            'std'           =>  'recent-product',
                            'group'      => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        array(
                            "type"       => "dropdown",
                            "heading"    => esc_html__("Order by", 'digitalworld'),
                            "param_name" => "orderby",
                            "value"      => array(
                                '',
                                esc_html__( 'Date', 'digitalworld' )        => 'date',
                                esc_html__('ID', 'digitalworld')            => 'ID',
                                esc_html__('Author', 'digitalworld')        => 'author',
                                esc_html__('Title', 'digitalworld')         => 'title',
                                esc_html__('Modified', 'digitalworld')      => 'modified',
                                esc_html__('Random', 'digitalworld')        => 'rand',
                                esc_html__('Comment count', 'digitalworld') => 'comment_count',
                                esc_html__('Menu order', 'digitalworld')    => 'menu_order',
                                esc_html__('Sale price', 'digitalworld')    => '_sale_price',
                            ),
                            'std'         => 'date',
                            "description" => esc_html__("Select how to sort.",'digitalworld'),
                            "dependency"  => array("element" => "target", "value" => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'product_attribute')),
                            'group'      => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        array(
                            "type"       => "dropdown",
                            "heading"    => esc_html__("Order", 'digitalworld'),
                            "param_name" => "order",
                            "value"      => array(
                                esc_html__('ASC', 'digitalworld')  => 'ASC',
                                esc_html__('DESC', 'digitalworld') => 'DESC'
                            ),
                            'std'         => 'DESC',
                            "description" => esc_html__("Designates the ascending or descending order.",'digitalworld'),
                            "dependency"  => array("element" => "target", "value" => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'product_attribute')),
                            'group'      => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__( 'Product per page', 'digitalworld' ),
                            'param_name'  => 'per_page',
                            'value'       => 6,
                            "dependency"  => array("element" => "target", "value" => array( 'best-selling', 'top-rated', 'recent-product', 'product-category', 'featured_products', 'product_attribute', 'on_sale')),
                            'group'      => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        array(
                            'type'               => 'autocomplete',
                            'heading'            => esc_html__( 'Products', 'digitalworld' ),
                            'param_name'         => 'ids',
                            'settings'           => array(
                                'multiple'       => true,
                                'sortable'       => true,
                                'unique_values'  => true,
                            ),
                            'save_always' => true,
                            'description' => esc_html__( 'Enter List of Products', 'digitalworld' ),
                            "dependency"  => array("element" => "target", "value" => array( 'products')),
                            'group'      => esc_html__( 'Products options', 'digitalworld' ),
                        ),
                        /* OWL Settings */
                         array(
                             'type'  => 'dropdown',
                             'value' => array(
                                 esc_html__( '1 Row', 'digitalworld' )  => '1',
                                 esc_html__( '2 Rows', 'digitalworld' ) => '2',
                                 esc_html__( '3 Rows', 'digitalworld' ) => '3',
                                 esc_html__( '4 Rows', 'digitalworld' ) => '4',
                                 esc_html__( '5 Rows', 'digitalworld' ) => '5'
                             ),
                             'std'         => '1',
                             'heading'     => esc_html__( 'The number of rows which are shown on block', 'digitalworld' ),
                             'param_name'  => 'owl_number_row',
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),

                         array(
                             'type'        => 'dropdown',
                             'heading'     => esc_html__( 'Rows space', 'digitalworld' ),
                             'param_name'  => 'owl_rows_space',
                             'value'       => array(
                                 esc_html__('Default', 'digitalworld') => 'rows-space-0',
                                 esc_html__('10px', 'digitalworld')    => 'rows-space-10',
                                 esc_html__('20px', 'digitalworld')    => 'rows-space-20',
                                 esc_html__('30px', 'digitalworld')    => 'rows-space-30',
                                 esc_html__('40px', 'digitalworld')    => 'rows-space-40',
                                 esc_html__('50px', 'digitalworld')    => 'rows-space-50',
                                 esc_html__('60px', 'digitalworld')    => 'rows-space-60',
                                 esc_html__('70px', 'digitalworld')    => 'rows-space-70',
                                 esc_html__('80px', 'digitalworld')    => 'rows-space-80',
                                 esc_html__('90px', 'digitalworld')    => 'rows-space-90',
                                 esc_html__('100px', 'digitalworld')   => 'rows-space-100',
                             ),
                             'std'=>'rows-space-0',
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             "dependency"  => array("element" => "owl_number_row", "value" => array( '2','3','4','5' )),
                         ),
                         array(
                             'type'  => 'dropdown',
                             'value' => array(
                                 esc_html__( 'Yes', 'digitalworld' ) => 'true',
                                 esc_html__( 'No', 'digitalworld' )  => 'false'
                             ),
                             'std'         => 'false',
                             'heading'     => esc_html__( 'AutoPlay', 'digitalworld' ),
                             'param_name'  => 'owl_autoplay',
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             'type'  => 'dropdown',
                             'value' => array(
                                 esc_html__( 'No', 'digitalworld' )  => 'false',
                                 esc_html__( 'Yes', 'digitalworld' ) => 'true'
                             ),
                             'std'         => false,
                             'heading'     => esc_html__( 'Navigation', 'digitalworld' ),
                             'param_name'  => 'owl_navigation',
                             'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'digitalworld' ),
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                             'admin_label' => false,
                         ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Navigation position', 'digitalworld' ),
                            'param_name'  => 'owl_navigation_position',
                            'value'       => array(
                                esc_html__('Default', 'digitalworld')      => '',
                                esc_html__('Center', 'digitalworld')      => 'nav-center',
                                esc_html__('Top Left', 'digitalworld')     => 'nav-top-left',
                                esc_html__('Top right', 'digitalworld')    => 'nav-top-right',
                                esc_html__('Bottom right', 'digitalworld') => 'nav-bottom-right',
                            ),
                            'std'=>'rows-space-0',
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            "dependency"  => array("element" => "owl_navigation", "value" => array( 'true' )),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Postion Top", 'digitalworld'),
                            "param_name"  => "owl_navigation_position_top",
                            "value"       => "-60",
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            'admin_label' => false,
                            "dependency"  => array(
                                "element" => "owl_navigation_position", "value" => array('nav-top-left','nav-top-right')
                            ),
                        ),
                         array(
                             'type'  => 'dropdown',
                             'value' => array(
                                 esc_html__( 'Yes', 'digitalworld' ) => 'true',
                                 esc_html__( 'No', 'digitalworld' )  => 'false'
                             ),
                             'std'         => false,
                             'heading'     => esc_html__( 'Loop', 'digitalworld' ),
                             'param_name'  => 'owl_loop',
                             'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'digitalworld' ),
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("Slide Speed", 'digitalworld'),
                             "param_name"  => "owl_slidespeed",
                             "value"       => "200",
                             "suffix"      => esc_html__("milliseconds", 'digitalworld'),
                             "description" => esc_html__('Slide speed in milliseconds', 'digitalworld'),
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("Margin", 'digitalworld'),
                             "param_name"  => "owl_margin",
                             "value"       => "0",
                             "description" => esc_html__('Distance( or space) between 2 item', 'digitalworld'),
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),

                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 1200px )", 'digitalworld'),
                             "param_name"  => "owl_lg_items",
                             "value"       => "4",
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 992px < 1200px )", 'digitalworld'),
                             "param_name"  => "owl_md_items",
                             "value"       => "3",
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("The items on tablet (Screen resolution of device >=768px and < 992px )", 'digitalworld'),
                             "param_name"  => "owl_sm_items",
                             "value"       => "3",
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'digitalworld'),
                             "param_name"  => "owl_xs_items",
                             "value"       => "2",
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         array(
                             "type"        => "textfield",
                             "heading"     => esc_html__("The items on mobile (Screen resolution of device < 480px)", 'digitalworld'),
                             "param_name"  => "owl_ts_items",
                             "value"       => "1",
                             'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                             'admin_label' => false,
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('owl')
                             ),
                         ),
                         /* Bostrap setting */
                         array(
                             'type'        => 'dropdown',
                             'heading'     => esc_html__( 'Rows space', 'digitalworld' ),
                             'param_name'  => 'boostrap_rows_space',
                             'value'       => array(
                                 esc_html__( 'Default', 'digitalworld' ) => 'rows-space-0',
                                 esc_html__( '10px', 'digitalworld' )    => 'rows-space-10',
                                 esc_html__( '20px', 'digitalworld' )    => 'rows-space-20',
                                 esc_html__( '30px', 'digitalworld' )    => 'rows-space-30',
                                 esc_html__( '40px', 'digitalworld' )    => 'rows-space-40',
                                 esc_html__( '50px', 'digitalworld' )    => 'rows-space-50',
                                 esc_html__( '60px', 'digitalworld' )    => 'rows-space-60',
                                 esc_html__( '70px', 'digitalworld' )    => 'rows-space-70',
                                 esc_html__( '80px', 'digitalworld' )    => 'rows-space-80',
                                 esc_html__( '90px', 'digitalworld' )    => 'rows-space-90',
                                 esc_html__( '100px', 'digitalworld' )   => 'rows-space-100',
                             ),
                             'std'=>'rows-space-0',
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),

                         array(
                             'type'          =>  'dropdown',
                             'heading'       =>  esc_html__( 'Items per row on Desktop', 'digitalworld' ),
                             'param_name'    =>  'boostrap_lg_items',
                             'value'         =>  array(
                                 esc_html__( '1 item', 'digitalworld' ) => '12',
                                 esc_html__( '2 items', 'digitalworld' ) => '6',
                                 esc_html__( '3 items', 'digitalworld' ) => '4',
                                 esc_html__( '4 items', 'digitalworld' ) => '3',
                                 esc_html__( '5 item', 'digitalworld' ) => '15',
                                 esc_html__( '6 item', 'digitalworld' ) => '2',
                             ),
                             'description'   =>  esc_html__( '(Item per row on screen resolution of device >= 1200px )', 'digitalworld' ),
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             'std'           =>  '15',
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),
                         array(
                             'type'          =>  'dropdown',
                             'heading'       =>  esc_html__( 'Items per row on landscape tablet', 'digitalworld' ),
                             'param_name'    =>  'boostrap_md_items',
                             'value'         =>  array(
                                 esc_html__( '1 item', 'digitalworld' )  => '12',
                                 esc_html__( '2 items', 'digitalworld' ) => '6',
                                 esc_html__( '3 items', 'digitalworld' ) => '4',
                                 esc_html__( '4 items', 'digitalworld' ) => '3',
                                 esc_html__( '5 item', 'digitalworld' )  => '15',
                                 esc_html__( '6 item', 'digitalworld' )  => '2',
                             ),
                             'description'   =>  esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'digitalworld' ),
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             'std'           =>  '3',
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),
                         array(
                             'type'          =>  'dropdown',
                             'heading'       =>  esc_html__( 'Items per row on portrait tablet', 'digitalworld' ),
                             'param_name'    =>  'boostrap_sm_items',
                             'value'         =>  array(
                                 esc_html__( '1 item', 'digitalworld' ) => '12',
                                 esc_html__( '2 items', 'digitalworld' ) => '6',
                                 esc_html__( '3 items', 'digitalworld' ) => '4',
                                 esc_html__( '4 items', 'digitalworld' ) => '3',
                                 esc_html__( '5 item', 'digitalworld' ) => '15',
                                 esc_html__( '6 item', 'digitalworld' ) => '2',
                             ),
                             'description'   =>  esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'digitalworld' ),
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             'std'           =>  '4',
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),
                         array(
                             'type'          =>  'dropdown',
                             'heading'       =>  esc_html__( 'Items per row on Mobile', 'digitalworld' ),
                             'param_name'    =>  'boostrap_xs_items',
                             'value'         =>  array(
                                 esc_html__( '1 item', 'digitalworld' ) => '12',
                                 esc_html__( '2 items', 'digitalworld' ) => '6',
                                 esc_html__( '3 items', 'digitalworld' ) => '4',
                                 esc_html__( '4 items', 'digitalworld' ) => '3',
                                 esc_html__( '5 item', 'digitalworld' ) => '15',
                                 esc_html__( '6 item', 'digitalworld' ) => '2',
                             ),
                             'description'   =>  esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'digitalworld' ),
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             'std'           =>  '6',
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),
                         array(
                             'type'          =>  'dropdown',
                             'heading'       =>  esc_html__( 'Items per row on Mobile', 'digitalworld' ),
                             'param_name'    =>  'boostrap_ts_items',
                             'value'         =>  array(
                                 esc_html__( '1 item', 'digitalworld' ) => '12',
                                 esc_html__( '2 items', 'digitalworld' ) => '6',
                                 esc_html__( '3 items', 'digitalworld' ) => '4',
                                 esc_html__( '4 items', 'digitalworld' ) => '3',
                                 esc_html__( '5 item', 'digitalworld' ) => '15',
                                 esc_html__( '6 item', 'digitalworld' ) => '2',
                             ),
                             'description'   =>  esc_html__( '(Item per row on screen resolution of device < 480px)', 'digitalworld' ),
                             'group'         =>  esc_html__( 'Boostrap settings', 'digitalworld' ),
                             'std'           =>  '12',
                             "dependency"  => array(
                                 "element" => "productsliststyle", "value" => array('grid')
                             ),
                         ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Extra class name", "digitalworld"),
                            "param_name" => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__( 'Css', 'digitalworld' ),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design options', 'digitalworld' ),
                        ),
                        array(
                            'param_name'       => 'products_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        ),
                    )
                )
            );
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Icon Box', 'digitalworld' ),
                    'base'     => 'digitalworld_iconbox',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Select style', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'Style 01',
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'style',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Title', 'digitalworld'),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'param_name' => 'text_content',
                            'heading'    => esc_html__( 'Content', 'digitalworld' ),
                            'type'       => 'textarea',
                            'admin_label' => true,
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Link', 'digitalworld'),
                            'param_name'  => 'link',
                        ),
                        array(
                            'param_name'  => 'icon_type',
                            'heading'     => esc_html__( 'Icon Library', 'digitalworld' ),
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__( 'Font Awesome', 'digitalworld' ) => 'fontawesome',
                                esc_html__('Open Iconic', 'digitalworld')    => 'openiconic',
                                esc_html__('Typicons', 'digitalworld')       => 'typicons',
                                esc_html__('Entypo', 'digitalworld')         => 'entypo',
                                esc_html__('Linecons', 'digitalworld')       => 'linecons',
                                esc_html__('Other', 'digitalworld')          =>'digitalworldcustomfonts'
                            ),
                        ),
                        array(
                            'param_name'  => 'icon_digitalworldcustomfonts',
                            'heading'     => esc_html__( 'Icon', 'digitalworld' ),
                            'description' => esc_html__( 'Select icon from library.', 'digitalworld' ),
                            'type'        => 'iconpicker',
                            'settings'   => array(
                                'emptyIcon'    => false,
                                'type'         => 'digitalworldcustomfonts',
                            ),
                            'dependency'  => array(
                                'element' => 'icon_type',
                                'value'   => 'digitalworldcustomfonts',
                            ),
                        ),
                        array(
                            'param_name'  => 'icon_fontawesome',
                            'heading'     => esc_html__( 'Icon', 'digitalworld' ),
                            'description' => esc_html__( 'Select icon from library.', 'digitalworld' ),
                            'type'        => 'iconpicker',
                            'settings'    => array(
                                'emptyIcon'    => true,
                                'iconsPerPage' => 4000,
                            ),
                            'dependency'  => array(
                                'element' => 'icon_type',
                                'value'   => 'fontawesome',
                            ),
                        ),
                        array(
                            'param_name'  => 'icon_openiconic',
                            'heading'     => esc_html__( 'Icon', 'digitalworld' ),
                            'description' => esc_html__( 'Select icon from library.', 'digitalworld' ),
                            'type'        => 'iconpicker',
                            'settings'    => array(
                                'emptyIcon'    => true,
                                'type'         => 'openiconic',
                                'iconsPerPage' => 4000,
                            ),
                            'dependency'  => array(
                                'element' => 'icon_type',
                                'value'   => 'openiconic',
                            ),
                        ),
                        array(
                            'param_name'  => 'icon_typicons',
                            'heading'     => esc_html__( 'Icon', 'digitalworld' ),
                            'description' => esc_html__( 'Select icon from library.', 'digitalworld' ),
                            'type'        => 'iconpicker',
                            'settings'    => array(
                                'emptyIcon'    => true,
                                'type'         => 'typicons',
                                'iconsPerPage' => 4000,
                            ),
                            'dependency'  => array(
                                'element' => 'icon_type',
                                'value'   => 'typicons',
                            ),
                        ),
                        array(
                            'param_name' => 'icon_entypo',
                            'heading'    => esc_html__( 'Icon', 'digitalworld' ),
                            'type'       => 'iconpicker',
                            'settings'   => array(
                                'emptyIcon'    => true,
                                'type'         => 'entypo',
                                'iconsPerPage' => 4000,
                            ),
                            'dependency' => array(
                                'element' => 'icon_type',
                                'value'   => 'entypo',
                            ),
                        ),
                        array(
                            'param_name'  => 'icon_linecons',
                            'heading'     => esc_html__( 'Icon', 'digitalworld' ),
                            'description' => esc_html__( 'Select icon from library.', 'digitalworld' ),
                            'type'        => 'iconpicker',
                            'settings'    => array(
                                'emptyIcon'    => true,
                                'type'         => 'linecons',
                                'iconsPerPage' => 4000,
                            ),
                            'dependency'  => array(
                                'element' => 'icon_type',
                                'value'   => 'linecons',
                            ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Extra class name', 'digitalworld'),
                            'param_name'  => 'el_class',
                            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'digitalworld' ),
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('CSS box', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design Options', 'digitalworld' ),
                        ),
                        array(
                            'param_name'       => 'iconbox_custom_id',
                            'heading'          => esc_html__('Hidden ID', 'digitalworld'),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map blog */
            $categories_array = array(
                esc_html__('All', 'digitalworld') => ''
            );
            $args = array();
            $categories = get_categories($args);
            foreach ($categories as $category) {
                $categories_array[$category->name] = $category->slug;
            }

            vc_map(
                array(
                    'name'        => esc_html__('Digitalworld: Blogs', 'digitalworld'),
                    'base'        => 'digitalworld_blogs', // shortcode
                    'class'       => '',
                    'category'    => esc_html__('Digitalworld Elements', 'digitalworld'),
                    'description' => esc_html__('Display a blog lists.', 'digitalworld'),
                    'params'      => array(
                        array(
                            'param_name' => 'style',
                            'heading'    => esc_html__( 'Select style', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__( 'Style 01', 'digitalworld' ) => 'default',
                            ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Number Post', 'digitalworld'),
                            'param_name'  => 'per_page',
                            'std'         => 10,
                            'admin_label' => true,
                            'description' => esc_html__('Number post in a slide', 'digitalworld')
                        ),
                        array(
                            'param_name'  => 'category_slug',
                            'type'        => 'dropdown',
                            'value'       => $categories_array, // here I'm stuck
                            'heading'     => esc_html__('Category filter:', 'digitalworld'),
                            "admin_label" => true,
                        ),
                        array(
                            "type"        => "dropdown",
                            "heading"     => esc_html__("Order by", 'digitalworld'),
                            "param_name"  => "orderby",
                            "value"       => array(
                                esc_html__('None', 'digitalworld')     => 'none',
                                esc_html__('ID', 'digitalworld')       => 'ID',
                                esc_html__('Author', 'digitalworld')   => 'author',
                                esc_html__('Name', 'digitalworld')     => 'name',
                                esc_html__('Date', 'digitalworld')     => 'date',
                                esc_html__('Modified', 'digitalworld') => 'modified',
                                esc_html__('Rand', 'digitalworld')     => 'rand',
                            ),
                            'std'         => 'date',
                            "description" => esc_html__("Select how to sort retrieved posts.", 'digitalworld'),
                        ),
                        array(
                            "type"        => "dropdown",
                            "heading"     => esc_html__("Order", 'digitalworld'),
                            "param_name"  => "order",
                            "value"       => array(
                                esc_html__('ASC', 'digitalworld')  => 'ASC',
                                esc_html__('DESC', 'digitalworld') => 'DESC'
                            ),
                            'std'         => 'DESC',
                            "description" => esc_html__("Designates the ascending or descending order.", 'digitalworld')
                        ),
                        /* Owl */
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('Yes', 'digitalworld') => 'true',
                                esc_html__('No', 'digitalworld')  => 'false'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('AutoPlay', 'digitalworld'),
                            'param_name'  => 'autoplay',
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('No', 'digitalworld')  => 'false',
                                esc_html__('Yes', 'digitalworld') => 'true'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('Navigation', 'digitalworld'),
                            'param_name'  => 'navigation',
                            'description' => esc_html__("Show buton 'next' and 'prev' buttons.", 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Navigation position', 'digitalworld' ),
                            'param_name'  => 'owl_navigation_position',
                            'value'       => array(
                                esc_html__('Default', 'digitalworld')      => '',
                                esc_html__('Center', 'digitalworld')       => 'nav-center',
                                esc_html__('Top Left', 'digitalworld')     => 'nav-top-left',
                                esc_html__('Top right', 'digitalworld')    => 'nav-top-right',
                                esc_html__('Bottom right', 'digitalworld') => 'nav-bottom-right',
                            ),
                            'std'=>'rows-space-0',
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            "dependency"  => array("element" => "navigation", "value" => array( 'true' )),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Postion Top", 'digitalworld'),
                            "param_name"  => "owl_navigation_position_top",
                            'value'       => '',
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            'admin_label' => false,
                            "dependency"  => array(
                                "element" => "owl_navigation_position", "value" => array('nav-top-left','nav-top-right')
                            ),
                        ),
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('Yes', 'digitalworld') => 'true',
                                esc_html__('No', 'digitalworld')  => 'false'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('Loop', 'digitalworld'),
                            'param_name'  => 'loop',
                            'description' => esc_html__("Inifnity loop. Duplicate last and first items to get loop illusion.", 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Slide Speed", 'digitalworld'),
                            "param_name"  => "slidespeed",
                            "value"       => "200",
                            "description" => esc_html__('Slide speed in milliseconds', 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Margin", 'digitalworld'),
                            "param_name"  => "margin",
                            "value"       => "30",
                            "description" => esc_html__('Distance( or space) between 2 item', 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 1200px )", 'digitalworld'),
                            "param_name"  => "lg_items",
                            "value"       => "3",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 992px < 1200px )", 'digitalworld'),
                            "param_name"  => "md_items",
                            "value"       => "3",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on tablet (Screen resolution of device >=768px and < 992px )", 'digitalworld'),
                            "param_name"  => "sm_items",
                            "value"       => "2",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'digitalworld'),
                            "param_name"  => "xs_items",
                            "value"       => "2",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on mobile (Screen resolution of device < 480px)", 'digitalworld'),
                            "param_name"  => "ts_items",
                            "value"       => "1",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'param_name'       => 'image_effect',
                            'heading'          => esc_html__( 'Effect', 'digitalworld' ),
                            'group'            => esc_html__( 'Image Effect', 'digitalworld' ),
                            'type'             => 'dropdown',
                            'value'      => array(
                                esc_html__( 'None', 'digitalworld' )      => '',
                                esc_html__( 'Effect1', 'digitalworld' )      => 'banner-effect  banner-effect1',
                                esc_html__( 'Effect2', 'digitalworld' )      => 'banner-effect banner-effect2',
                                esc_html__( 'Effect3', 'digitalworld' )      => 'banner-effect banner-effect3',
                                esc_html__( 'Effect4', 'digitalworld' )      => 'banner-effect banner-effect4',
                                esc_html__( 'Effect5', 'digitalworld' )      => 'banner-effect banner-effect5',
                                esc_html__( 'Effect6', 'digitalworld' )      => 'banner-effect banner-effect6',
                                esc_html__( 'Effect7', 'digitalworld' )      => 'banner-effect banner-effect7',
                                esc_html__( 'Effect8', 'digitalworld' )      => 'banner-effect banner-effect8',
                                esc_html__( 'Effect9', 'digitalworld' )      => 'banner-effect banner-effect9',
                                esc_html__( 'Effect10', 'digitalworld' )      => 'banner-effect banner-effect10',
                            ),
                            'sdt'=>''
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'blogs_custom_id',
                            'heading'          => esc_html__('Hidden ID', 'digitalworld'),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );


            /*Map container */
            vc_map(
                array(
                    'name'                    => esc_html__( 'Digitalworld: Container', 'digitalworld' ),
                    'base'                    => 'digitalworld_container',
                    'category'                => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'content_element'         => true,
                    'show_settings_on_create' => true,
                    'is_container'            => true,
                    'js_view'                 => 'VcColumnView',
                    'params'                  => array(
                        array(
                            'param_name' => 'content_width',
                            'heading'    => esc_html__( 'Content width', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Default', 'digitalworld')         => '',
                            ),
                            'std' => 'container'
                        ),
                        'css' => array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__( 'Css', 'digitalworld' ),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design options', 'digitalworld' ),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'param_name'       => 'container_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map Social*/
            $socials = array();
            $all_socials = digitalworld_get_all_social();
            if( $all_socials ){
                foreach ($all_socials as $key =>  $social)
                    $socials[$social['name']] = $key;
            }
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Socials', 'digitalworld' ),
                    'base'     => 'digitalworld_socials', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a social list.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Select style', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'Default',
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'/digitalworld_socials/social-default.jpg'
                                ),
                                'style2'=>array(
                                    'alt'=> 'Style 02',
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'/digitalworld_socials/social-layout2.jpg'
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'style',
                        ),
                        array(
                            'type'          =>  'textfield',
                            'heading'       =>  esc_html__( 'Title', 'digitalworld' ),
                            'param_name'    =>  'title',
                            'description'   =>  esc_html__( 'The title of shortcode', 'digitalworld' ),
                            'admin_label'   =>  true,
                            'std'           =>  '',
                        ),
                        array(
                            'param_name' => 'text_align',
                            'heading'    => esc_html__( 'Text align', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__('Left', 'digitalworld' )    => 'text-left',
                                esc_html__('Right', 'digitalworld')    => 'text-right',
                                esc_html__('Center', 'digitalworld')    => 'text-center',
                            ),
                            'std' => 'text-left',
                        ),
                        array(
                            'type'        => 'checkbox',
                            'heading'     => esc_html__( 'Display on', 'digitalworld' ),
                            'param_name'  => 'use_socials',
                            'class'         => 'checkbox-display-block',
                            'value'       => $socials,
                            'admin_label'   =>  true,
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Extra class name", "digitalworld"),
                            "param_name" => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__( 'Css', 'digitalworld' ),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design options', 'digitalworld' ),
                        ),
                        array(
                             'param_name'       => 'socials_custom_id',
                             'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                             'type'             => 'uniqid',
                             'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map slider*/
            vc_map(
                array(
                    'name'                    => esc_html__( 'Digitalworld: Slider', 'digitalworld' ),
                    'base'                    => 'digitalworld_slider',
                    'category'                => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'as_parent'               => array( 'only' => 'vc_single_image,digitalworld_categories' ),
                    'content_element'         => true,
                    'show_settings_on_create' => true,
                    'js_view'                 => 'VcColumnView',
                    'params'                  => array(
                        array(
                            'param_name' => 'style',
                            'heading'    => esc_html__( 'Select style', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__( 'Default', 'digitalworld' ) => 'default',
                            ),
                        ),
                        /* Owl */
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('Yes', 'digitalworld') => 'true',
                                esc_html__('No', 'digitalworld')  => 'false'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('AutoPlay', 'digitalworld'),
                            'param_name'  => 'autoplay',
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('No', 'digitalworld')  => 'false',
                                esc_html__('Yes', 'digitalworld') => 'true'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('Navigation', 'digitalworld'),
                            'param_name'  => 'navigation',
                            'description' => esc_html__("Show buton 'next' and 'prev' buttons.", 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Navigation position', 'digitalworld' ),
                            'param_name'  => 'owl_navigation_position',
                            'value'       => array(
                                esc_html__('Default', 'digitalworld')      => '',
                                esc_html__('Center', 'digitalworld')       => 'nav-center',
                                esc_html__('Top Left', 'digitalworld')     => 'nav-top-left',
                                esc_html__('Top right', 'digitalworld')    => 'nav-top-right',
                                esc_html__('Bottom right', 'digitalworld') => 'nav-bottom-right',
                            ),
                            'std'=>'',
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            "dependency"  => array("element" => "navigation", "value" => array( 'true' )),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Postion Top", 'digitalworld'),
                            "param_name"  => "owl_navigation_position_top",
                            "std"       => -60,
                            'group'       => esc_html__( 'Carousel settings', 'digitalworld' ),
                            'admin_label' => false,
                            "dependency"  => array(
                                "element" => "owl_navigation_position", "value" => array('nav-top-left','nav-top-right')
                            ),
                        ),
                        array(
                            'type'        => 'dropdown',
                            'value'       => array(
                                esc_html__('Yes', 'digitalworld') => 'true',
                                esc_html__('No', 'digitalworld')  => 'false'
                            ),
                            'std'         => 'false',
                            'heading'     => esc_html__('Loop', 'digitalworld'),
                            'param_name'  => 'loop',
                            'description' => esc_html__("Inifnity loop. Duplicate last and first items to get loop illusion.", 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Slide Speed", 'digitalworld'),
                            "param_name"  => "slidespeed",
                            "value"       => "200",
                            "description" => esc_html__('Slide speed in milliseconds', 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Margin", 'digitalworld'),
                            "param_name"  => "margin",
                            "value"       => "30",
                            "description" => esc_html__('Distance( or space) between 2 item', 'digitalworld'),
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 1200px )", 'digitalworld'),
                            "param_name"  => "lg_items",
                            "value"       => "3",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on desktop (Screen resolution of device >= 992px < 1200px )", 'digitalworld'),
                            "param_name"  => "md_items",
                            "value"       => "3",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on tablet (Screen resolution of device >=768px and < 992px )", 'digitalworld'),
                            "param_name"  => "sm_items",
                            "value"       => "2",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'digitalworld'),
                            "param_name"  => "xs_items",
                            "value"       => "2",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("The items on mobile (Screen resolution of device < 480px)", 'digitalworld'),
                            "param_name"  => "ts_items",
                            "value"       => "1",
                            'group'       => esc_html__('Carousel settings', 'digitalworld'),
                            'admin_label' => false,
                        ),
                        array(
                            'heading'     => esc_html__( 'Extra Class Name', 'digitalworld' ),
                            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'digitalworld' ),
                            'type'        => 'textfield',
                            'param_name'  => 'el_class',
                        ),
                        'css' => array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__( 'Css', 'digitalworld' ),
                            'param_name' => 'css',
                            'group'      => esc_html__( 'Design options', 'digitalworld' ),
                        ),
                        array(
                            'param_name'       => 'slider_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map Newsletter*/
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Newsletter', 'digitalworld' ),
                    'base'     => 'digitalworld_newsletter', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a newsletter box.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Layout', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'default',
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'digitalworld_newsletter/default.jpg'
                                ),
                                'layout2'=>array(
                                    'alt'=> 'Layout 02',
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'digitalworld_newsletter/layout2.jpg'
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'style',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Title', 'digitalworld'),
                            'param_name'  => 'title',
                            'description' => esc_html__('The title of shortcode', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => '',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Sub title', 'digitalworld'),
                            'param_name'  => 'subtitle',
                            'description' => esc_html__('The sub title of shortcode', 'digitalworld'),
                            'std'         => '',
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Placeholder text", 'digitalworld'),
                            "param_name"  => "placeholder_text",
                            "admin_label" => false,
                            'std'         => 'Email address here'
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'newsletter_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );
            /*Map Vertical menu*/
            
            $all_menu = array();
            $menus =  get_terms( 'nav_menu', array( 'hide_empty' => false ));
            if( $menus && count($menus) > 0 ){
                foreach ($menus as $m){
                    $all_menu[$m->name] = $m->slug;
                }
            }
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Vertical Menu', 'digitalworld' ),
                    'base'     => 'digitalworld_verticalmenu', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a vertical menu.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Layout', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'Style 01',
                                    'img'=> DIGITALWORLD_SHORTCODE_PREVIEW.'digitalworld_verticalmenu_default.jpg'
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'layout',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Title', 'digitalworld'),
                            'param_name'  => 'title',
                            'description' => esc_html__('The title of shortcode', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => '',
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Menu', 'digitalworld' ),
                            'param_name'  => 'menu',
                            'value'       => $all_menu,
                            'description' => esc_html__( 'Select menu to display.', 'digitalworld' )
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Button close text', 'digitalworld'),
                            'param_name'  => 'button_close_text',
                            'description' => esc_html__('Button close text', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => esc_html__( 'Close', 'digitalworld' )
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Button all text', 'digitalworld'),
                            'param_name'  => 'button_all_text',
                            'description' => esc_html__('Button all text', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => esc_html__( 'All Categories', 'digitalworld' )
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Limit items', 'digitalworld'),
                            'param_name'  => 'limit_items',
                            'description' => esc_html__('Limit item for showing', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => '9'
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'verticalmenu_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map Custom menu*/

            $all_menu = array();
            $menus =  get_terms( 'nav_menu', array( 'hide_empty' => false ));
            if( $menus && count($menus) > 0 ){
                foreach ($menus as $m){
                    $all_menu[$m->name] = $m->slug;
                }
            }
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Custom Menu', 'digitalworld' ),
                    'base'     => 'digitalworld_custommenu', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a custom menu.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Title', 'digitalworld'),
                            'param_name'  => 'title',
                            'description' => esc_html__('The title of shortcode', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => '',
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => esc_html__( 'Menu', 'digitalworld' ),
                            'param_name'  => 'menu',
                            'value'       => $all_menu,
                            'description' => esc_html__( 'Select menu to display.', 'digitalworld' )
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'custommenu_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

            /*Map Categories*/

            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Categories', 'digitalworld' ),
                    'base'     => 'digitalworld_categories', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display Categories.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'type' => 'select_preview',
                            'heading' => __( 'Layout', 'digitalworld' ),
                            'value' => array(
                                'default'=>array(
                                    'alt'=> 'Default',
                                ),
                                'style2'=>array(
                                    'alt'=> 'Style2',
                                ),
                                'style3'=>array(
                                    'alt'=> 'Style3',
                                ),
                            ),
                            'default'       =>'default',
                            'admin_label' => true,
                            'param_name' => 'style',
                        ),
                        array(
                            "type"        => "taxonomy",
                            "taxonomy"    => "product_cat",
                            "class"       => "",
                            "heading"     => esc_html__("Product Category", 'digitalworld'),
                            "param_name"  => "taxonomy",
                            "value"       => '',
                            'parent'      => '',
                            'multiple'    => false,
                            'hide_empty'  => false,
                            'placeholder' => esc_html__('Choose category', 'digitalworld'),
                            "description" => esc_html__("Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'digitalworld'),
                            'std'         => '',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('default','style2'),
                            ),
                        ),
                        array(
                            "type"        => "attach_image",
                            "heading"     => __( "Background", "digitalworld" ),
                            "param_name"  => "bg_cat",
                            "admin_label" => true,
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('default','style2'),
                            ),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Limit", "digitalworld"),
                            "param_name"  => "limit",
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('default','style2'),
                            ),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Title", "digitalworld"),
                            "param_name"  => "title",
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('style3'),
                            ),
                        ),
                        array(
                            "type"        => "taxonomy",
                            "taxonomy"    => "product_cat",
                            "class"       => "",
                            "heading"     => esc_html__("Product Categories", 'digitalworld'),
                            "param_name"  => "product_cats",
                            "value"       => '',
                            'parent'      => '',
                            'multiple'    => true,
                            'hide_empty'  => false,
                            'placeholder' => esc_html__('Choose categorys', 'digitalworld'),
                            "description" => esc_html__("Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'digitalworld'),
                            'std'         => '',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('style3'),
                            ),
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => __( 'View All URL (Link)', 'digitalworld' ),
                            'param_name' => 'link_all',
                            'admin_label'   =>  false,
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('style3'),
                            ),
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'categories_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );


            /*Map section title */
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Section Title', 'digitalworld' ),
                    'base'     => 'digitalworld_title', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a custom title.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            'param_name' => 'style',
                            'heading'    => esc_html__( 'Select style', 'digitalworld' ),
                            'type'       => 'dropdown',
                            'value'      => array(
                                esc_html__( 'Default', 'digitalworld' ) => 'default',
                            ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Title', 'digitalworld'),
                            'param_name'  => 'title',
                            'description' => esc_html__('The title of shortcode', 'digitalworld'),
                            'admin_label' => true,
                            'std'         => '',
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", "digitalworld"),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'title_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );


            /*Map Googlemap */
            vc_map(
                array(
                    'name'     => esc_html__( 'Digitalworld: Google Map', 'digitalworld' ),
                    'base'     => 'digitalworld_googlemap', // shortcode
                    'class'    => '',
                    'category' => esc_html__( 'Digitalworld Elements', 'digitalworld' ),
                    'description'   =>  esc_html__( 'Display a google map.', 'digitalworld' ),
                    'params'   => array(
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Title", 'digitalworld'),
                            "param_name"    => "title",
                            'admin_label'   => true,
                            "description"   => __("title.", 'digitalworld'),
                            'std'           => 'Kute themes',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Phone", 'digitalworld'),
                            "param_name"    => "phone",
                            'admin_label'   => true,
                            "description"   => __("phone.", 'digitalworld'),
                            'std'           => '088-465 9965 02',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Email", 'digitalworld'),
                            "param_name"    => "email",
                            'admin_label'   => true,
                            "description"   => __("email.", 'digitalworld'),
                            'std'           => 'kutethemes@gmail.com',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Map Height", 'digitalworld'),
                            "param_name"    => "map_height",
                            'admin_label'   => true,
                            'std'           => '400',
                        ),
                        array(
                            'type'          =>  'dropdown',
                            'heading'       =>  __( 'Maps type', 'digitalworld' ),
                            'param_name'    =>  'map_type',
                            'value'         =>  array(
                                __( 'ROADMAP', 'digitalworld' )     => 'ROADMAP',
                                __( 'SATELLITE', 'digitalworld' )   => 'SATELLITE',
                                __( 'HYBRID', 'digitalworld' )      => 'HYBRID',
                                __( 'TERRAIN', 'digitalworld' )     => 'TERRAIN',
                            ),
                            'std'           =>  'ROADMAP',
                        ),
                        array(
                            'type'          =>  'dropdown',
                            'heading'       =>  __( 'Show info content?', 'digitalworld' ),
                            'param_name'    =>  'info_content',
                            'value'         =>  array(
                                __( 'Yes', 'digitalworld' )     => '1',
                                __( 'No', 'digitalworld' )      => '2',
                            ),
                            'std'           =>  '1',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Address", 'digitalworld'),
                            "param_name"    => "address",
                            'admin_label'   => true,
                            "description"   => __("address.", 'digitalworld'),
                            'std'           => 'Z115 TP. Thai Nguyen',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Longitude", 'digitalworld'),
                            "param_name"    => "longitude",
                            'admin_label'   => true,
                            "description"   => __("longitude.", 'digitalworld'),
                            'std'           => '105.800286',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Latitude", 'digitalworld'),
                            "param_name"    => "latitude",
                            'admin_label'   => true,
                            "description"   => __("latitude.", 'digitalworld'),
                            'std'           => '21.587001',
                        ),
                        array(
                            "type"          => "textfield",
                            "heading"       => __("Zoom", 'digitalworld'),
                            "param_name"    => "zoom",
                            'admin_label'   => true,
                            "description"   => __("zoom.", 'digitalworld'),
                            'std'           => '14',
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => esc_html__("Extra class name", 'digitalworld'),
                            "param_name"  => "el_class",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "digitalworld")
                        ),
                        array(
                            'type'       => 'css_editor',
                            'heading'    => esc_html__('Css', 'digitalworld'),
                            'param_name' => 'css',
                            'group'      => esc_html__('Design options', 'digitalworld'),
                        ),
                        array(
                            'param_name'       => 'googlemap_custom_id',
                            'heading'          => esc_html__( 'Hidden ID', 'digitalworld' ),
                            'type'             => 'uniqid',
                            'edit_field_class' => 'hidden',
                        )
                    )
                )
            );

        }
    }

    new Digitalworld_Visual_Composer();
}
VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );
class WPBakeryShortCode_Digitalworld_Banner extends WPBakeryShortCodesContainer {};
class WPBakeryShortCode_Digitalworld_Tabs extends WPBakeryShortCode_VC_Tta_Accordion {};
class WPBakeryShortCode_Digitalworld_Container extends WPBakeryShortCodesContainer {};
class WPBakeryShortCode_Digitalworld_Slider extends WPBakeryShortCodesContainer {};
