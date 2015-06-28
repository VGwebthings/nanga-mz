<?php

class Nanga_Public {
    private $nanga;
    private $version;

    public function __construct( $nanga, $version ) {
        $this->nanga   = $nanga;
        $this->version = $version;
    }

    public function asset_cachebusting() {
        if ( current_theme_supports( 'nanga-asset-cachebusting' ) ) {
            global $wp_styles, $wp_scripts;
            $wp_dir         = str_replace( home_url(), '', site_url() );
            $site_root_path = str_replace( $wp_dir, '', ABSPATH );
            foreach (
                array(
                    'wp_styles',
                    'wp_scripts'
                ) as $resource
            ) {
                foreach ( (array) $$resource->queue as $name ) {
                    if ( empty( $$resource->registered[ $name ] ) ) {
                        continue;
                    }
                    $src = $$resource->registered[ $name ]->src;
                    if ( 0 === strpos( $src, '/' ) ) {
                        $src = site_url( $src );
                    }
                    if ( false === strpos( $src, home_url() ) ) {
                        continue;
                    }
                    $file = str_replace( home_url( '/' ), $site_root_path, $src );
                    if ( ! file_exists( $file ) ) {
                        continue;
                    }
                    $mtime = filectime( $file );
                    //$$resource->registered[ $name ]->ver = $$resource->registered[ $name ]->ver . '-' . $mtime;
                    $$resource->registered[ $name ]->ver = $mtime;
                }
            }
        }
    }

    public function remove_self_closing_tags( $input ) {
        return str_replace( ' />', '>', $input );
    }

    public function body_class( $classes ) {
        global $wp_query;
        $no_classes = array();
        if ( is_page() ) {
            $page_id      = $wp_query->get_queried_object_id();
            $no_classes[] = 'page-id-' . $page_id;
            $ancestors    = get_ancestors( get_queried_object_id(), 'page' );
            if ( ! empty ( $ancestors ) ) {
                foreach ( $ancestors as $ancestor ) {
                    $no_classes[] = 'parent-pageid-' . $ancestor;
                }
            }
            $classes[] = str_replace( '.php', '', basename( get_page_template() ) );
        }
        if ( is_single() ) {
            $post_id      = $wp_query->get_queried_object_id();
            $no_classes[] = 'postid-' . $post_id;
        }
        if ( is_author() ) {
            $author_id    = $wp_query->get_queried_object_id();
            $no_classes[] = 'author-' . $author_id;
        }
        if ( is_category() ) {
            $cat_id       = $wp_query->get_queried_object_id();
            $no_classes[] = 'category-' . $cat_id;
        }
        if ( is_tax() ) {
            $ancestors = get_ancestors( get_queried_object_id(), get_queried_object()->taxonomy );
            if ( ! empty( $ancestors ) ) {
                foreach ( $ancestors as $ancestor ) {
                    $term      = get_term( $ancestor, get_queried_object()->taxonomy );
                    $classes[] = esc_attr( "parent-$term->taxonomy-$term->term_id" );
                }
            }
        }
        if ( is_single() || is_page() && ! is_front_page() ) {
            $classes[] = 'slug-' . basename( get_permalink() );
        }
        $no_classes[] = 'page-template-default';
        $no_classes[] = 'page-id-' . get_option( 'page_on_front' );
        $classes      = array_diff( $classes, $no_classes );

        return $classes;
    }

    public function attachment_class( $classes, $image_id, $align, $size ) {
        $classes = str_replace( ' wp-image-' . $image_id, '', $classes );
        $classes = $classes . ' image-in-content';

        return $classes;
    }

    public function post_class( $classes, $class, $post_id ) {
        $remove_classes = array(
            'page',
            'post',
            'post-' . $post_id,
            'status-publish',
        );
        $classes        = array_diff( $classes, $remove_classes );

        return $classes;
    }

    public function nice_search() {
        global $wp_rewrite;
        if ( ! isset( $wp_rewrite ) || ! is_object( $wp_rewrite ) || ! $wp_rewrite->using_permalinks() ) {
            return;
        }
        $search_base = $wp_rewrite->search_base;
        if ( is_search() && ! is_admin() && false === strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) ) {
            wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
            exit();
        }
    }

    public function js_to_footer() {
        if ( current_theme_supports( 'nanga-js-to-footer' ) ) {
            remove_action( 'wp_head', 'wp_print_scripts' );
            remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
            remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
        }
    }

    public function disable_adminbar() {
        if ( ! current_user_can( 'manage_options' ) ) {
            show_admin_bar( false );
        }
    }

    public function remove_paragraphs_from_images( $content ) {
        return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
    }
}
