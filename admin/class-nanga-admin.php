<?php

class Nanga_Admin {
    private $nanga;
    private $version;

    public function __construct( $nanga, $version ) {
        $this->nanga   = $nanga;
        $this->version = $version;
    }

    public function disable_shake() {
        remove_action( 'login_head', 'wp_shake_js', 12 );
    }

    public function login_headerurl() {
        return get_site_url();
    }

    public function login_headertitle() {
        return get_option( 'blogname' );
    }

    public function acf_settings_show_admin( $show ) {
        return current_user_can( 'manage_options' );
    }

    public function disable_admin_notices() {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }

    public function disable_pointers() {
        remove_action( 'admin_enqueue_scripts', array(
            'WP_Internal_Pointers',
            'enqueue_scripts'
        ) );
    }

    public function disable_menus() {
        remove_menu_page( 'separator-last' );
        remove_menu_page( 'separator1' );
        remove_menu_page( 'separator2' );
        if ( current_theme_supports( 'nanga-disable-posts' ) ) {
            remove_menu_page( 'edit.php' );
        }
        if ( current_theme_supports( 'nanga-disable-comments' ) ) {
            remove_menu_page( 'edit-comments.php' );
        }
    }

    public function settings_page() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( array(
                'page_title' => __( 'General Settings', $this->nanga ),
                'menu_title' => __( 'Settings', $this->nanga ),
                'menu_slug'  => 'general-settings',
                'redirect'   => false,
                'position'   => false,
                'icon_url'   => 'dashicons-forms'
            ) );
        }
    }

    public function admin_bar( $wp_toolbar ) {
        remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_edit_menu', 80 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 0 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );
        remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 40 );
        //remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
        $wp_toolbar->remove_node( 'about' );
        $wp_toolbar->remove_node( 'appearance' );
        $wp_toolbar->remove_node( 'comments' );
        $wp_toolbar->remove_node( 'customize' );
        $wp_toolbar->remove_node( 'dashboard' );
        $wp_toolbar->remove_node( 'documentation' );
        $wp_toolbar->remove_node( 'edit' );
        $wp_toolbar->remove_node( 'edit-profile' );
        $wp_toolbar->remove_node( 'feedback' );
        $wp_toolbar->remove_node( 'menus' );
        $wp_toolbar->remove_node( 'new-content' );
        $wp_toolbar->remove_node( 'new-link' );
        $wp_toolbar->remove_node( 'new-media' );
        $wp_toolbar->remove_node( 'new-page' );
        $wp_toolbar->remove_node( 'new-post' );
        $wp_toolbar->remove_node( 'new-user' );
        $wp_toolbar->remove_node( 'search' );
        $wp_toolbar->remove_node( 'support-forums' );
        $wp_toolbar->remove_node( 'themes' );
        $wp_toolbar->remove_node( 'updates' );
        $wp_toolbar->remove_node( 'user-info' );
        $wp_toolbar->remove_node( 'view' );
        $wp_toolbar->remove_node( 'view-site' );
        $wp_toolbar->remove_node( 'wp-logo' );
        $wp_toolbar->remove_node( 'wp-logo-external' );
        $wp_toolbar->remove_node( 'wporg' );
        //$wp_toolbar->remove_node( 'logout' );
        //$wp_toolbar->remove_node( 'my-account' );
        //$wp_toolbar->remove_node( 'site-name' );
        //$wp_toolbar->remove_node( 'user-actions' );
        //$wp_toolbar->remove_node( 'wpseo-menu' );
    }

    public function disable_metaboxes() {
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'icl_dashboard_widget', 'dashboard', 'normal' );
        remove_meta_box( 'mandrill_widget', 'dashboard', 'normal' );
        remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
        remove_meta_box( 'woocommerce_dashboard_recent_orders', 'dashboard', 'normal' );
        remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
        remove_meta_box( 'woocommerce_dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'woocommerce_dashboard_sales', 'dashboard', 'normal' );
        remove_meta_box( 'wp_cube', 'dashboard', 'normal' );
    }

    public function footer_left() {
        return '';
    }

    public function footer_right( $wp_version ) {
        return '';
    }

    public function mime_types( $existing_mimes ) {
        $existing_mimes['mp4'] = 'video/mp4';
        $existing_mimes['ogg'] = 'video/ogg';
        $existing_mimes['ogv'] = 'video/ogv';
        unset( $existing_mimes['bmp'] );
        unset( $existing_mimes['gif'] );

        return $existing_mimes;
    }

    public function user_contact( $user_contact ) {
        unset( $user_contact['facebook'] );
        unset( $user_contact['googleplus'] );
        unset( $user_contact['twitter'] );

        return $user_contact;
    }
}
