<?php
/**
 * orologio Class
 *
 * @since    2.0.0
 * @package  orologio
 */
use OrologioTheme\Classes\Orologio_Helper;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Orologio' ) ) :

    /**
     * The main orologio class
     */
    class Orologio {

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct() {
            add_action( 'after_setup_theme', [ $this, 'setup' ] );
            add_action( 'widgets_init', [ $this, 'widgetsInit' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ], 20 );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueChildScripts' ], 30 );
            add_action( 'enqueue_block_editor_assets', [ $this, 'enqueueEditorStyles' ] );
            add_filter( 'body_class', [ $this, 'addBodyClasses' ] );
            add_filter( 'wp_page_menu_args', [ $this, 'modifyPageMenuArgs' ] );
            add_filter( 'navigation_markup_template', [ $this, 'customNavigationMarkupTemplate' ] );
            add_action( 'wp_head', [ $this, 'addPingbackHeader' ] );
        }

        /**
         * Theme setup.
         */
        public function setup() {
            // Load text domain.
            load_theme_textdomain( 'orologio', get_template_directory() . '/languages' );

            // Add theme supports.
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'post-thumbnails' );
            set_post_thumbnail_size( 9999, 9999 );

            add_theme_support( 'custom-logo', [
                'height'      => 82,
                'width'       => 140,
                'flex-height' => true,
                'flex-width'  => true,
            ]);

            add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'widgets', 'script', 'style' ] );

            add_theme_support( 'custom-background', [
                'default-color' => apply_filters( 'orologio_default_background_color', 'ffffff' ),
                'default-image' => '',
            ]);

            add_theme_support( 'custom-header', [
                'default-image' => '',
                'header-text'   => false,
                'width'         => 1950,
                'height'        => 500,
                'flex-width'    => true,
                'flex-height'   => true,
            ]);

            add_theme_support( 'site-logo', [ 'size' => 'full' ] );
            add_theme_support( 'title-tag' );
            add_theme_support( 'customize-selective-refresh-widgets' );
            add_theme_support( 'wp-block-styles' );
            add_theme_support( 'editor-color-palette', $this->getGutenbergColorPalette() );

            // Register navigation menus.
            register_nav_menus([
                'main-menu'   => esc_html__( 'Main Menu', 'orologio' ),
                'footer-menu' => esc_html__( 'Footer Menu', 'orologio' ),
            ]);

            // Elementor onboarding steps.
            delete_transient( 'elementor_activation_redirect' );
            update_option( 'elementor_onboarded', true );
        }

        /**
         * Gutenberg block color palettes.
         */
        public function getGutenbergColorPalette() {
            $colors = Orologio_Helper::get_global_colors();
            $palette = [];

            foreach ( $colors as $slug => $args ) {
                $palette[] = [
                    'name'  => esc_html( $args['title'] ),
                    'slug'  => esc_html( $args['slug'] ),
                    'color' => esc_html( $args['value'] ),
                ];
            }

            return $palette;
        }

 
        /**
         * Register widget areas.
         */
        public function widgetsInit() {
            $default_sidebars = [
                esc_html__( 'Page Sidebar', 'orologio' )          => 'sidebar-page',
                esc_html__( 'Posts Sidebar', 'orologio' )         => 'sidebar-posts',
                esc_html__( 'Search Sidebar', 'orologio' )        => 'sidebar-search',
                esc_html__( 'Shop Sidebar', 'orologio' )          => 'sidebar-shop',
                esc_html__( 'Product Sidebar', 'orologio' )       => 'sidebar-product',
                esc_html__( 'Footer Hero Area', 'orologio' )      => 'footer-hero-area',
                esc_html__( 'Footer Widgets Area', 'orologio' )   => 'footer-widgets-area',
            ];

            foreach ( $default_sidebars as $name => $id ) {
                register_sidebar([
                    'name'          => $name,
                    'id'            => $id,
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h4 class="widget-title">',
                    'after_title'   => '</h4>',
                ]);
            }

            // Register dynamic sidebars.
            $dynamic_sidebars = Orologio_Helper::get_option( 'sidebar_options', '' );
            if ( $dynamic_sidebars ) {
                foreach ( $dynamic_sidebars as $sidebar ) {
                    if ( ! empty( $sidebar['sidebar_name'] ) ) {
                        register_sidebar([
                            'name'          => sanitize_text_field( $sidebar['sidebar_name'] ),
                            'id'            => sanitize_title_with_dashes( $sidebar['sidebar_name'] ),
                            'before_widget' => '<div id="%1$s" class="widget %2$s">',
                            'after_widget'  => '</div>',
                            'before_title'  => '<h4 class="widget-title">',
                            'after_title'   => '</h4>',
                        ]);
                    }
                }
            }
        }

        public function enqueueScripts() {
            if ( orologio_is_wpml_activated() && ICL_LANGUAGE_CODE === 'he' ) {
                wp_enqueue_style( 'rtl', get_template_directory_uri() . '/rtl.css', [], OROLOGIO_VERSION );
            }

            wp_enqueue_style( 'modern-normalize', get_template_directory_uri() . '/assets/css/modern-normalize.css', [], '1.0.0' );
            wp_enqueue_style( 'orologio-style', get_template_directory_uri() . '/style.css', [ 'modern-normalize' ], OROLOGIO_VERSION );
            wp_enqueue_style( 'orologio-fonts', $this->getGoogleFontsUrl(), [], null );

            wp_enqueue_script( 'orologio-navigation', get_template_directory_uri() . '/assets/js/primary-navigation.js', [], OROLOGIO_VERSION, true );
            wp_localize_script( 'orologio-navigation', 'orologioScreenReaderText', [
                'expand'   => esc_html__( 'Expand child menu', 'orologio' ),
                'collapse' => esc_html__( 'Collapse child menu', 'orologio' ),
            ]);
            wp_enqueue_script( 'orologio-lenis', get_template_directory_uri() . '/assets/js/lenis.min.js', [], OROLOGIO_VERSION, true );
            wp_enqueue_script( 'orologio-swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', [], OROLOGIO_VERSION, true );
            wp_enqueue_script( 'orologio-gsap', get_template_directory_uri() . '/assets/js/gsap.min.js', [ 'jquery' ], OROLOGIO_VERSION, true );
            wp_enqueue_script( 'orologio-custom', get_template_directory_uri() . '/assets/js/custom.js', [ 'jquery' ], OROLOGIO_VERSION, true );

            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
        }

        /**
         * Enqueue child theme styles.
         */
        public function enqueueChildScripts() {
            if ( is_child_theme() ) {
                wp_enqueue_style( 'orologio-child-style', get_stylesheet_uri(), [ 'orologio-style' ], wp_get_theme()->get( 'Version' ) );
            }
        }

        /**
         * Enqueue editor styles.
         */
        public function enqueueEditorStyles() {
            wp_enqueue_style( 'orologio-editor-style', get_template_directory_uri() . '/editor-style.css', [], OROLOGIO_VERSION );
        }

        /**
         * Generate Google Fonts URL.
         */
        public function getGoogleFontsUrl() {
            $fonts = apply_filters( 'orologio_google_fonts', [
                'Ibarra+Real+Nova:ital,wght@0,400..700;1,400..700',
                'Manrope:wght@200..800',
            ]);

            return $fonts ? esc_url_raw( add_query_arg([
                'family'  => implode( '&family=', $fonts ),
                'display' => 'swap',
            ], 'https://fonts.googleapis.com/css2' ) ) : '';
        }

        /**
         * Modify page menu arguments.
         */
        public function modifyPageMenuArgs( $args ) {
            $args['show_home'] = true;
            return $args;
        }

        /**
         * Add custom classes to the body element.
         */
        public function addBodyClasses( $classes ) {

            $default_header           = Orologio_Helper::get_option( 'default_header', 'enabled' );
            $sticky_header            = Orologio_Helper::get_option( 'sticky_header', 'disabled' );
            $sticky_header_logo_check = Orologio_Helper::get_option( 'sticky_header_logo_check', 'no' );
            $sticky_site_image_logo   = Orologio_Helper::get_option( 'sticky_site_image_logo' );

            if ($default_header != 'enabled') {
                $classes[] = 'header-is-custom-editor';
            } else {
                if ( $sticky_header == 'enabled' ) {
                    $classes[] = 'header-is-sticky';
                }
                if ( $sticky_header_logo_check == 'yes' && $sticky_site_image_logo['url'] != '' ) {
                    $classes[] = 'has-sticky-logo';
                }
            }


            /* Add page slug if it doesn't exist */
            if (is_single() || is_page() && !is_front_page()) {
                if (!in_array(basename(get_permalink()), $classes)) {
                    $classes[] = basename(get_permalink());
                }
            }

            //Page layout
            if (is_singular() && has_post_thumbnail()) {
                $classes[] = 'gg-page-has-header-image';
            }
            

            if (!is_multi_author()) {
                $classes[] = 'single-author';
            }

            //WPML
            if ( orologio_is_wpml_activated() ) {
                
                $classes[] = 'gg-theme-has-wpml';
                
                //WPML currency
                if ( class_exists('woocommerce_wpml') ) {
                    $classes[] = 'gg-theme-has-wpml-currency';
                }
            }

            // Add class if sidebar is used.
            if ( ! is_active_sidebar( 'sidebar-page' ) && ( is_page() || is_home() ) ) {
                $classes[] = 'no-active-sidebar-page';
            } 

            if ( ! is_active_sidebar( 'sidebar-posts' ) && ( is_single() || is_archive() ) ) {
                $classes[] = 'no-active-sidebar-post';
            }

            if ( ! is_active_sidebar( 'sidebar-search' ) && is_search() ) {
                $classes[] = 'no-active-sidebar-search';
            }

            //Sidebars
            $classes[] = orologio_page_layout();

            return $classes;
        }

        /**
         * Customize navigation markup template.
         */
        public function customNavigationMarkupTemplate() {
            return '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="%2$s">
                        <h2 class="screen-reader-text">%2$s</h2>
                        <div class="nav-links">%3$s</div>
                    </nav>';
        }

        /**
         * Add a pingback URL header for single posts, pages, or attachments.
         */
        public function addPingbackHeader() {
            if ( is_singular() && pings_open() ) {
                printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
            }
        }
        
    }
endif;

return new orologio();