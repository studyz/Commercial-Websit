<?php
if( !class_exists('Digitalworld_Wellcome') ){
    class Digitalworld_Wellcome{

        public $tabs = array();

        public function __construct() {
            $this->set_tabs();
            // Add action to enqueue scripts.
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ),9 );
        }

        public  function admin_menu(){
            if ( current_user_can( 'edit_theme_options' ) ) {
                add_menu_page( 'Digitalworld', 'Digitalworld', 'manage_options', 'digitalworld', array( $this, 'wellcome' ),DIGITALWORLD_TOOLKIT_URL . '/assets/images/icon-menu.png', 2 );
                add_submenu_page( 'digitalworld', 'Digitalworld Dashboard', 'Dashboard', 'manage_options', 'digitalworld',  array( $this, 'wellcome' ) );
            }
        }

        public  function  enqueue_scripts(){
            wp_enqueue_style( 'chosen', get_template_directory_uri() . '/css/chosen.min.css' );
            wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css' );
            wp_enqueue_style( 'digitalworld-admin', get_template_directory_uri() . '/famework/assets/css/admin.css' );
            wp_enqueue_script( 'chosen', get_template_directory_uri() . '/js/chosen.jquery.min.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'digitalworld-admin', get_template_directory_uri() . '/famework/assets/js/admin.js', array( 'jquery' ), false, true );
        }

        public function set_tabs(){
            $this->tabs = array(
                'demos' => esc_html__('Sample Data','digitalworld'),
                'plugins' => esc_html__('Plugins','digitalworld'),
                'support' => esc_html__('Support','digitalworld')
            );

        }
        public  function active_plugin(){
            if (empty($_GET['magic_token']) || wp_verify_nonce($_GET['magic_token'], 'panel-plugins') === false) {
                esc_html_e('Permission denied','digitalworld');
                die;
            }

            if( isset($_GET['plugin_slug']) && $_GET['plugin_slug']!=""){
                $plugin_slug = $_GET['plugin_slug'];
                $plugins = TGM_Plugin_Activation::$instance->plugins;
                foreach ($plugins as $plugin) {
                    if ($plugin['slug'] == $plugin_slug) {
                        activate_plugins($plugin['file_path']);
                        ?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=digitalworld&tab=plugins";
                        </script>
                        <?php
                        break;
                    }
                }
            }

        }

        public function deactivate_plugin(){
            if (empty($_GET['magic_token']) || wp_verify_nonce($_GET['magic_token'], 'panel-plugins') === false) {
                esc_html_e('Permission denied','digitalworld');
                die;
            }

            if( isset($_GET['plugin_slug']) && $_GET['plugin_slug']!=""){
                $plugin_slug = $_GET['plugin_slug'];
                $plugins = TGM_Plugin_Activation::$instance->plugins;
                foreach ($plugins as $plugin) {
                    if ($plugin['slug'] == $plugin_slug) {
                        deactivate_plugins($plugin['file_path']);
                        ?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=digitalworld&tab=plugins";
                        </script>
                        <?php
                        break;
                    }
                }
            }

        }
        public  function intall_plugin(){

        }
        /**
         * Render HTML of intro tab.
         *
         * @return  string
         */

        public function wellcome(){

            /* deactivate_plugin */
            if( isset($_GET['action']) && $_GET['action'] == 'deactivate_plugin'){
                $this->deactivate_plugin();
            }
            /* deactivate_plugin */
            if( isset($_GET['action']) && $_GET['action'] == 'active_plugin'){
                $this->active_plugin();
            }

            $tab = 'demos';
            if( isset($_GET['tab'])){
                $tab = $_GET['tab'];
            }
            ?>
            <div class="wrap redapple-wrap">
                <div class="welcome-panel">
                    <div class="welcome-panel-content">
                        <h2><?php esc_html_e('Welcome to Digitalworld!','digitalworld');?></h2>
                        <p class="about-description"><?php esc_html_e('We\'ve assembled some links to get you started','digitalworld');?></p>
                        <div class="welcome-panel-column-container">
                            <div class="welcome-panel-column">
                                <h3><?php esc_html_e('Get Started','digitalworld');?></h3>
                                <a target="_blank" href="http://kutethemes.net/wordpress/digitalworld" class="button button-primary button-hero trigger-tab"><?php esc_html_e('Wiew All Demos','digitalworld');?></a>
                            </div>
                            <div class="welcome-panel-column">
                                <h3><?php echo esc_html_e('Next Steps','digitalworld');?></h3>
                                <ul>
                                    <li><a target="_blank" href="#" class="welcome-icon dashicons-media-document"><?php esc_html_e('Read Documentation','digitalworld')?></a></li>
                                    <li><a target="_blank" href="http://support.ovicsoft.com/support-system" class="welcome-icon dashicons-editor-help"><?php esc_html_e('Request Support','digitalworld');?></a></li>
                                    <li><a target="_blank" href="http://kutethemes.net/wordpress/digitalworld/changelog.txt" class="welcome-icon dashicons-backup"><?php esc_html_e('View Changelog Details','digitalworld');?></a></li>
                                </ul>
                            </div>
                            <div class="welcome-panel-column">
                                <h3><?php esc_html_e('Keep in Touch','digitalworld');?></h3>
                                <ul>
                                    <li><a target="_blank" href="#" class="welcome-icon dashicons-email-alt"><?php esc_html_e('Newsletter','digitalworld');?></a></li>
                                    <li><a target="_blank" href="#" class="welcome-icon dashicons-twitter"><?php esc_html_e('Twitter','digitalworld');?></a></li>
                                    <li><a target="_blank" href="https://www.facebook.com/kutethemes" class="welcome-icon dashicons-facebook"><?php esc_html_e('Facebook','digitalworld');?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .welcome-panel-content -->
                </div>
                <div id="tabs-container" role="tabpanel">
                    <div class="nav-tab-wrapper">
                        <?php foreach ($this->tabs as $key => $value ):?>
                            <a class="nav-tab digitalworld-nav <?php if( $tab == $key ):?> active<?php endif;?>" href="admin.php?page=digitalworld&tab=<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></a>
                        <?php endforeach;?>
                    </div>
                    <div class="tab-content">
                        <?php $this->$tab();?>
                    </div>
                </div>
            </div>
            <?php
        }
        public static function demos(){
            if( class_exists('DIGITALWORLD_IMPORTER')){
                $digitalworld_importer = new DIGITALWORLD_IMPORTER();
                $digitalworld_importer->importer_page_content();
            }
        }
        public static function plugins(){
            $digitalworld_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;
            $tgm =   TGM_Plugin_Activation::$instance;

            $status_class = "";
            ?>
            <div class="plugins rp-row">
                <?php
                $wp_plugin_list = get_plugins();
                foreach ($digitalworld_tgm_theme_plugins as $digitalworld_tgm_theme_plugin ){
                    if( $tgm->is_plugin_active($digitalworld_tgm_theme_plugin['slug'])){
                        $status_class = 'is-active';
                        if( $tgm->does_plugin_have_update($digitalworld_tgm_theme_plugin['slug'])){
                            $status_class = 'plugin-update';
                        }
                    }else if (isset($wp_plugin_list[$digitalworld_tgm_theme_plugin['file_path']])) {
                        $status_class = 'plugin-inactive';
                    }else{
                        $status_class ='no-intall';
                    }
                    ?>
                    <div class="rp-col">
                        <div class="plugin <?php echo esc_attr($status_class);?>">
                            <div class="preview">
                                <?php if( isset($digitalworld_tgm_theme_plugin['image']) && $digitalworld_tgm_theme_plugin['image'] != "" ):?>
                                    <img src="<?php echo esc_url($digitalworld_tgm_theme_plugin['image']);?>" alt="">
                                <?php else:?>
                                    <img src="<?php echo esc_url(get_template_directory_uri().'/famework/assets/images/no-image.jpg');?>" alt="">
                                <?php endif;?>
                            </div>
                            <div class="plugin-name">
                                <h3 class="theme-name"><?php echo $digitalworld_tgm_theme_plugin['name'] ?></h3>
                            </div>
                            <div class="actions">
                                <a class="button button-primary button-install-plugin" href="<?php
                                echo esc_url( wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
                                            'plugin'		=> urlencode($digitalworld_tgm_theme_plugin['slug']),
                                            'tgmpa-install' => 'install-plugin',
                                        ),
                                        admin_url('themes.php')
                                    ),
                                    'tgmpa-install',
                                    'tgmpa-nonce'
                                ));
                                ?>"><?php esc_html_e('Install','digitalworld');?></a>

                                <a class="button button-primary button-update-plugin" href="<?php
                                echo esc_url( wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
                                            'plugin'		=> urlencode($digitalworld_tgm_theme_plugin['slug']),
                                            'tgmpa-update' => 'update-plugin',
                                        ),
                                        admin_url('themes.php')
                                    ),
                                    'tgmpa-install',
                                    'tgmpa-nonce'
                                ));
                                ?>"><?php esc_html_e('Update','digitalworld');?></a>

                                <a class="button button-primary button-activate-plugin" href="<?php
                                echo esc_url(
                                    add_query_arg(
                                        array(
                                            'page'                   => urlencode('digitalworld'),
                                            'plugin_slug' => urlencode($digitalworld_tgm_theme_plugin['slug']),
                                            'action'                 => 'active_plugin',
                                            'magic_token'         => wp_create_nonce('panel-plugins')
                                        ),
                                        admin_url('admin.php')
                                    ));
                                ?>""><?php esc_html_e('Activate','digitalworld');?></a>
                                <a class="button button-secondary button-uninstall-plugin" href="<?php
                                echo esc_url(
                                    add_query_arg(
                                        array(
                                            'page'                   => urlencode('digitalworld'),
                                            'plugin_slug' => urlencode($digitalworld_tgm_theme_plugin['slug']),
                                            'action'                 => 'deactivate_plugin',
                                            'magic_token'         => wp_create_nonce('panel-plugins')
                                        ),
                                        admin_url('admin.php')
                                    ));
                                ?>""><?php esc_html_e('Deactivate','digitalworld');?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        public static function support(){
            ?>
            <div class="rp-row">
                <div class="rp-col">
                    <div class="suport-item">
                        <h3><?php esc_html_e('Documentation','digitalworld');?></h3>
                        <p><?php esc_html_e('Here is our user guide for Digitalworld, including basic setup steps, as well as Digitalworld features and elements for your reference.','digitalworld');?></p>
                        <a target="_blank" href="#" class="button button-primary"><?php esc_html_e('Read Documentation','digitalworld');?></a>
                    </div>
                </div>
                <div class="rp-col closed">
                    <div class="suport-item">
                        <h3><?php esc_html_e('Video Tutorials','digitalworld');?></h3>
                        <p class="coming-soon"><?php esc_html_e('Video tutorials is the great way to show you how to setup Digitalworld theme, make sure that the feature works as it\'s designed.','digitalworld');?></p>
                        <a href="#" class="button button-primary disabled"><?php esc_html_e('See Video','digitalworld');?></a>
                    </div>
                </div>
                <div class="rp-col">
                    <div class="suport-item">
                        <h3><?php esc_html_e('Forum','digitalworld');?></h3>
                        <p><?php esc_html_e('Can\'t find the solution on documentation? We\'re here to help, even on weekend. Just click here to start 1on1 chatting with us!','digitalworld');?></p>
                        <a target="_blank" href="http://support.ovicsoft.com/support-system" class="button button-primary"><?php esc_html_e('Request Support','digitalworld');?></a>
                    </div>
                </div>
            </div>

            <?php
        }
    }

    new Digitalworld_Wellcome();
}
