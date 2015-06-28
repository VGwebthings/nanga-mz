<?php

class Nanga_Shared {
    private $nanga;
    private $version;

    public function __construct( $nanga, $version ) {
        $this->nanga   = $nanga;
        $this->version = $version;
        $this->run_cleanup();
    }

    private function run_cleanup() {
        add_filter( 'comment_flood_filter', '__return_false', 10, 3 );
        add_filter( 'enable_post_by_email_configuration', '__return_false', 100 );
        add_filter( 'sanitize_user', 'strtolower' );
        add_filter( 'the_generator', '__return_false' );
        add_filter( 'use_default_gallery_style', '__return_false' );
        add_filter( 'widget_text', 'do_shortcode' );
        remove_action( 'init', 'smilies_init', 5 );
        remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
        remove_action( 'welcome_panel', 'wp_welcome_panel' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra' );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'parent_post_rel_link', 10 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'start_post_rel_link', 10 );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
        remove_all_filters( 'comment_flood_filter' );
        remove_filter( 'comment_text', 'capital_P_dangit', 31 );
        remove_filter( 'comment_text', 'make_clickable', 9 );
        remove_filter( 'comments_open', '_close_comments_for_old_post', 10, 2 );
        remove_filter( 'pings_open', '_close_comments_for_old_post', 10, 2 );
        remove_filter( 'template_redirect', 'redirect_canonical' );
        remove_filter( 'template_redirect', 'wp_old_slug_redirect' );
        remove_filter( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
        remove_filter( 'template_redirect', 'wp_shortlink_header', 11 );
        remove_filter( 'the_content', 'capital_P_dangit', 11 );
        remove_filter( 'the_content', 'convert_smilies' );
        remove_filter( 'the_content', 'wptexturize' );
        remove_filter( 'the_excerpt', 'convert_smilies' );
        remove_filter( 'the_excerpt', 'wptexturize' );
        remove_filter( 'the_title', 'capital_P_dangit', 11 );
        remove_filter( 'the_title', 'wptexturize' );
        remove_filter( 'wp_title', 'capital_P_dangit', 11 );
        remove_filter( 'wp_title', 'wptexturize' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', function ( $plugins ) {
            if ( is_array( $plugins ) ) {
                return array_diff( $plugins, array( 'wpemoji' ) );
            } else {
                return array();
            }
        } );
    }

    public function disable_core_functionality() {
        remove_post_type_support( 'attachment', 'comments' );
        remove_post_type_support( 'page', 'author' );
        remove_post_type_support( 'page', 'custom-fields' );
        remove_post_type_support( 'page', 'trackbacks' );
        remove_post_type_support( 'post', 'custom-fields' );
        remove_post_type_support( 'post', 'post-formats' );
        remove_post_type_support( 'post', 'trackbacks' );
    }

    public function disable_comments() {
        if ( current_theme_supports( 'nanga-disable-comments' ) ) {
            remove_post_type_support( 'page', 'comments' );
            remove_post_type_support( 'post', 'comments' );
        }
    }

    public function features_wpml() {
        if ( class_exists( 'SitePress' ) ) {
            global $sitepress;
            remove_action( 'wp_head', array( $sitepress, 'meta_generator_tag' ) );
            add_action( 'wp_before_admin_bar_render', function () {
                global $wp_admin_bar;
                $wp_admin_bar->remove_menu( 'WPML_ALS' );
            } );
            add_action( 'admin_init', function () {
                global $sitepress;
                remove_action( 'show_user_profile', array( $sitepress, 'show_user_options' ) );
            } );
        }
    }

    public function features_json_api() {
        if ( class_exists( 'WP_JSON_Server' ) ) {
            remove_action( 'wp_head', 'json_output_link_wp_head', 10, 0 );
            add_filter( 'json_url_prefix', function () {
                return 'api/v1';
            } );
            add_filter( 'json_serve_request', function () {
                header( 'Access-Control-Allow-Origin: *' );
            } );
            add_filter( 'json_query_var-posts_per_page', function ( $posts_per_page ) {
                if ( 10 < intval( $posts_per_page ) ) {
                    $posts_per_page = 10;
                }

                return $posts_per_page;
            } );
            add_filter( 'json_query_vars', function ( $valid_vars ) {
                $valid_vars[] = 'offset';

                return $valid_vars;
            } );
        }
    }

    public function empty_search( $query_vars ) {
        if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
            $query_vars['s'] = 'empty';
        }

        return $query_vars;
    }

    public function heartbeat( $settings ) {
        $settings['interval'] = 15;

        return $settings;
    }
}
