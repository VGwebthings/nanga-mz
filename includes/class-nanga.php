<?php

class Nanga {
    protected $loader;
    protected $nanga;
    protected $version;

    public function __construct() {
        $this->nanga   = 'nanga';
        $this->version = '1.1.5';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_shared_hooks();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nanga-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nanga-i18n.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nanga-shared.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nanga-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nanga-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cpt/extended-cpts.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cpt/extended-taxos.php';
        $this->loader = new Nanga_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new Nanga_i18n();
        $plugin_i18n->set_domain( $this->get_nanga() );
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    public function get_nanga() {
        return $this->nanga;
    }

    private function define_shared_hooks() {
        $plugin_shared = new Nanga_Shared( $this->get_nanga(), $this->get_version() );
        $this->loader->add_action( 'init', $plugin_shared, 'disable_comments' );
        $this->loader->add_action( 'init', $plugin_shared, 'disable_core_functionality', 10 );
        $this->loader->add_action( 'plugins_loaded', $plugin_shared, 'features_json_api' );
        $this->loader->add_action( 'plugins_loaded', $plugin_shared, 'features_wpml' );
        $this->loader->add_filter( 'heartbeat_settings', $plugin_shared, 'heartbeat' );
    }

    public function get_version() {
        return $this->version;
    }

    private function define_admin_hooks() {
        $plugin_admin = new Nanga_Admin( $this->get_nanga(), $this->get_version() );
        $this->loader->add_action( 'admin_footer_text', $plugin_admin, 'footer_left' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'disable_admin_notices' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'disable_pointers' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'disable_menus', 999 );
        $this->loader->add_action( 'login_head', $plugin_admin, 'disable_shake' );
        $this->loader->add_action( 'plugins_loaded', $plugin_admin, 'settings_page' );
        $this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'disable_metaboxes' );
        $this->loader->add_filter( 'acf/settings/show_admin', $plugin_admin, 'acf_settings_show_admin' );
        $this->loader->add_filter( 'login_headertitle', $plugin_admin, 'login_headertitle' );
        $this->loader->add_filter( 'login_headerurl', $plugin_admin, 'login_headerurl' );
        $this->loader->add_filter( 'update_footer', $plugin_admin, 'footer_right', 999 );
        $this->loader->add_filter( 'upload_mimes', $plugin_admin, 'mime_types' );
        $this->loader->add_filter( 'user_contactmethods', $plugin_admin, 'user_contact', 666 );
        $this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'admin_bar', 999 );
    }

    private function define_public_hooks() {
        $plugin_public = new Nanga_Public( $this->get_nanga(), $this->get_version() );
        $this->loader->add_action( 'after_setup_theme', $plugin_public, 'disable_adminbar' );
        $this->loader->add_action( 'template_redirect', $plugin_public, 'nice_search' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'asset_cachebusting', 666 );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'js_to_footer' );
        $this->loader->add_filter( 'body_class', $plugin_public, 'body_class' );
        $this->loader->add_filter( 'comment_id_fields', $plugin_public, 'remove_self_closing_tags' );
        $this->loader->add_filter( 'get_avatar', $plugin_public, 'remove_self_closing_tags' );
        $this->loader->add_filter( 'get_image_tag_class', $plugin_public, 'attachment_class', 10, 4 );
        $this->loader->add_filter( 'post_class', $plugin_public, 'post_class', 10, 3 );
        $this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'remove_self_closing_tags' );
        $this->loader->add_filter( 'the_content', $plugin_public, 'remove_paragraphs_from_images' );
    }

    public function run() {
        $this->loader->run();
    }

    public function get_loader() {
        return $this->loader;
    }
}
