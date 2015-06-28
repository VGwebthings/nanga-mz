<?php

class Nanga_Activator {
    public static function activate() {
        if ( ! get_option( 'nanga_plugin_activated' ) ) {
            update_option( 'avatar_default', 'blank' );
            update_option( 'blog_public', 0 );
            update_option( 'blogdescription', '' );
            update_option( 'comment_max_links', 1 );
            update_option( 'comments_notify', 0 );
            update_option( 'date_format', 'd/m/Y' );
            update_option( 'default_comment_status', 'closed' );
            update_option( 'default_ping_status', 'closed' );
            update_option( 'gform_enable_noconflict', 1 );
            update_option( 'gzipcompression', 1 );
            update_option( 'image_default_link_type', 'none' );
            update_option( 'imsanity_bmp_to_jpg', 1 );
            update_option( 'imsanity_max_height', 1350 );
            update_option( 'imsanity_max_height_library', 1350 );
            update_option( 'imsanity_max_height_other', 0 );
            update_option( 'imsanity_max_width', 1350 );
            update_option( 'imsanity_max_width_library', 1350 );
            update_option( 'imsanity_max_width_other', 0 );
            update_option( 'imsanity_quality', 90 );
            update_option( 'mailserver_login', '' );
            update_option( 'mailserver_pass', '' );
            update_option( 'mailserver_port', 0 );
            update_option( 'mailserver_url', '' );
            update_option( 'medium_size_h', 300 );
            update_option( 'medium_size_w', 300 );
            update_option( 'moderation_notify', 0 );
            update_option( 'posts_per_page', 5 );
            update_option( 'posts_per_rss', 1 );
            update_option( 'rg_gforms_currency', 'EUR' );
            update_option( 'rg_gforms_disable_css', 1 );
            update_option( 'rg_gforms_enable_html5', 1 );
            update_option( 'rss_use_excerpt', 1 );
            update_option( 'show_avatars', 0 );
            update_option( 'show_on_front', 'page' );
            update_option( 'thread_comments', 0 );
            update_option( 'thumbnail_size_h', 300 );
            update_option( 'thumbnail_size_w', 300 );
            update_option( 'time_format', 'H:i' );
            update_option( 'timezone_string', 'Europe/Athens' );
            update_option( 'use_smilies', 0 );
        }
        update_option( 'nanga_plugin_activated', true );
        flush_rewrite_rules();
    }
}
