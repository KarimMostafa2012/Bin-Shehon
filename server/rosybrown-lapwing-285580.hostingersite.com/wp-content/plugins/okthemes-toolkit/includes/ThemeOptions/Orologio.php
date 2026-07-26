<?php
namespace OKThemes\Toolkit\ThemeOptions;

use CSF;

defined('ABSPATH') || exit;

class Orologio {
    private static $instance = null;

    private $options_prefix = 'orologio_options';
    private $menu_slug      = 'orologio_options';
    private $template_builder_url;
    private $responsive_radio_trigger_array = array(
        'desktop' => 'Desktop',
        'tablet' => 'Tablet',
        'mobile' => 'Mobile',
    );


    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        if (!class_exists('CSF')) return;

        $this->template_builder_url = admin_url('edit.php?post_type=okthemes_template');

        $this->theme_options();

        $this->general_section();
        $this->header_section();
        $this->footer_section();
        $this->page_section();
        $this->blog_section();
        $this->shop_section();
        $this->comments_section();
        $this->error_section();
        $this->colors_section();
        $this->typography_section();
        $this->buttons_section();
        $this->custom_scrips_section();
        $this->backup_section();
    }

    /**
     * Create Theme Option
     */
    private function theme_options() {
        CSF::createOptions( $this->options_prefix, [
            'menu_title'         => esc_html__( 'Theme Options', 'okthemes-toolkit' ),
            'menu_slug'          => $this->menu_slug,
            'framework_title'    => esc_html__( 'Theme Options', 'okthemes-toolkit' ),
            'show_in_customizer' => true,
            'menu_type'          => 'menu',
            'menu_parent'        => 'okthemes_dashboard',
            'footer_text'        => '',
            'theme'              => 'light',
            'class'              => 'okthemes-admin',
        ] );
    }

    /**
     * General options
     */
    private function general_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'General', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'General', 'okthemes-toolkit' ),
                ],

                [
                    'id'      => 'site_smooth_scroll',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Site smooth scroll?', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'disabled',
                ],
                [
                    'id'      => 'back_to_top',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Site back to top button?', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Layout', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'container_width',
                    'type'    => 'number',
                    'title'   => 'Content Width',
                    'desc'    => esc_html__( 'Sets the default width of the content area (Default: 1500px)', 'okthemes-toolkit' ),
                    'default' => array(
                      'number' => '1500', 
                      'unit'   => 'px',
                    ),
                    'unit'    => true, // Enable unit selection
                ],
            ],
        ] );
    }


    

    /**
     * Header Options
     */
    private function header_section() {
        CSF::createSection( $this->options_prefix, [
            'id'    => 'header_options',
            'title' => esc_html__( 'Header', 'okthemes-toolkit' ),
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'header_options',
            'title'  => esc_html__( 'General', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'General', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'default_header',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Default Header', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable Theme default header', 'okthemes-toolkit' ),
                    'options'  => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'enabled',
                ],
                [
                    'id'       => 'sticky_header',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Sticky Header', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable sticky header', 'okthemes-toolkit' ),
                    'options'  => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'disabled',
                    'dependency' => [
                        'default_header', '==', 'enabled',
                    ],
                ],
                [
                    'id'       => 'sticky_header_logo_check',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Different Logo for Sticky Header?', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes'  => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no' => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'no',
                    'dependency' => [
                        ['sticky_header', '==', 'enabled'],
                        ['default_header', '==', 'enabled']
                    ],
                ],

                [
                    'id'         => 'sticky_site_image_logo',
                    'type'       => 'media',
                    'title'      => esc_html__( 'Image logo', 'okthemes-toolkit' ),
                    'library'    => 'image',
                    'url'        => false,
                    'dependency' => [['sticky_header_logo_check', '==', 'yes'],['default_header', '==', 'enabled']]
                ],
                [
                    'id'          => 'sticky_logo_max_width',
                    'type'        => 'number',
                    'unit'        => 'px',
                    'title'       => esc_html__( 'Max Width', 'okthemes-toolkit' ),
                    'desc'        => esc_html__( 'Logo wrapper max width', 'okthemes-toolkit' ),
                    'output'      => '#main-logo .sticky-logo img',
                    'output_mode' => 'max-width',
                    'default' => '180',
                    'dependency' => [['sticky_header_logo_check', '==', 'yes'],['default_header', '==', 'enabled']]
                ],

                [
                    'type'       => 'notice',
                    'style'      => 'warning',
                    'content'    => esc_html__( 'You have turned off the default theme header. Configure your website\'s header from ', 'okthemes-toolkit' ) . '<a href="' . esc_url( $this->template_builder_url ) . '">' . esc_html__( 'here', 'okthemes-toolkit' ) . '</a>',
                    'dependency' => [
                        'default_header', '==', 'disabled',
                    ],
                ],
                [
                    'id'         => 'header_breakpoint',
                    'type'       => 'number',
                    'title'      => esc_html__( 'Header Breakpoint', 'okthemes-toolkit' ),
                    'default'    => 1200,
                    'desc'       => esc_html__( 'Enter when the slide menu will appear', 'okthemes-toolkit' ),
                    'dependency' => [
                        'default_header', '==', 'enabled',
                    ],
                ],
                
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'header_options',
            'title'  => esc_html__( 'Layout', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Layout', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'site_header_search',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Site Header Search', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable the header search form', 'okthemes-toolkit' ),
                    'options'  => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'disabled',
                ],
                
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'header_options',
            'title'  => esc_html__( 'Logo', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Logo', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'site_logo_type',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Site Logo Type', 'okthemes-toolkit' ),
                    'options' => [
                        'text'  => esc_html__( 'Text', 'okthemes-toolkit' ),
                        'image' => esc_html__( 'Image', 'okthemes-toolkit' ),
                    ],
                    'default' => 'text',
                ],
                [
                    'id'         => 'site_image_logo',
                    'type'       => 'media',
                    'title'      => esc_html__( 'Image logo', 'okthemes-toolkit' ),
                    'library'    => 'image',
                    'url'        => false,
                    'dependency' => ['site_logo_type', '==', 'image'],
                ],
                [
                    'id'         => 'logo_dimension',
                    'type'       => 'dimensions',
                    'title'      => esc_html__( 'Logo Dimensions', 'okthemes-toolkit' ),
                    'output'     => '#main-logo img',
                    'dependency' => ['site_logo_type', '==', 'image'],
                ],
                [
                    'id'          => 'logo_max_width',
                    'type'        => 'number',
                    'unit'        => 'px',
                    'title'       => esc_html__( 'Max Width', 'okthemes-toolkit' ),
                    'desc'        => esc_html__( 'Logo wrapper max width', 'okthemes-toolkit' ),
                    'output'      => '#main-logo img',
                    'output_mode' => 'max-width',
                    'default' => '180',
                    'dependency' => ['site_logo_type', '==', 'image'],
                ],
                
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'header_options',
            'title'  => esc_html__( 'Styling', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Header Styling', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'header_background_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Header background color', 'okthemes-toolkit' ),
                    'default'  => '#000',
                    'desc'     => esc_html__( 'Default: #000', 'okthemes-toolkit' ),
                ],

                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Menu Items', 'okthemes-toolkit' ),
                ],
                [
                    'id'          => 'menu_item_color',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Menu Item Color', 'okthemes-toolkit' ),
                    'desc'        => esc_html__( 'This is the menu item font color.', 'okthemes-toolkit' ),
                    'default'     => '#fff'
                ],
                [
                    'id'          => 'menu_item_hover_color',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Hover/Active/Current Color', 'okthemes-toolkit' ),
                    'desc'        => esc_html__( 'This is the menu item font color.', 'okthemes-toolkit' ),
                    'default'     => '#8d725b'
                ],
                [
                    'id'               => 'menu_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Menu Typography', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '500',
                        'font-size'          => '1',
                        'line-height'        => '1',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Submenu', 'okthemes-toolkit' ),
                ],
                [
                    'id'          => 'submenu_bg',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Submenu Background', 'okthemes-toolkit' ),
                    'default'     => '#000000'
                ],
                [
                    'id'          => 'submenu_item_color',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Submenu Item Color', 'okthemes-toolkit' ),
                    'default'     => '#ffffff'
                ],
                [
                    'id'          => 'submenu_item_hover_color',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Submenu Item Hover Color', 'okthemes-toolkit' ),
                    'default'     => '#8d725b'
                ],
                [
                    'id'               => 'submenu_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Submenu Typography', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '500',
                        'font-size'          => '0.875',
                        'line-height'        => '1',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                ],
                
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'header_options',
            'title'  => esc_html__( 'Mobile', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Mobile menu', 'okthemes-toolkit' ),
                ],
                [
                    'id'          => 'mobile_bg',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Mobile Background', 'okthemes-toolkit' ),
                    'default'     => '#F8F5F2'
                ],
                [
                    'id'      => 'header_menu_open_text',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Menu open label', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Menu', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'header_menu_close_text',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Menu close label', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Close', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Mobile logo', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'mobile_image_logo',
                    'type'       => 'media',
                    'title'      => esc_html__( 'Image logo', 'okthemes-toolkit' ),
                    'library'    => 'image',
                    'url'        => false
                ],
                [
                    'id'         => 'mobile_logo_dimension',
                    'type'       => 'dimensions',
                    'title'      => esc_html__( 'Logo Dimensions', 'okthemes-toolkit' ),
                    //'output'     => '#main-logo img',
                ],
                [
                    'id'          => 'mobile_logo_max_width',
                    'type'        => 'number',
                    'unit'        => 'px',
                    'title'       => esc_html__( 'Max Width', 'okthemes-toolkit' ),
                    'desc'        => esc_html__( 'Logo wrapper max width', 'okthemes-toolkit' ),
                    //'output'      => '#main-logo img',
                    'output_mode' => 'max-width',
                    'default' => '180',
                ],
                
            ],
        ] );

        


    }

    /**
     * Footer Options
     */
    private function footer_section() {
        CSF::createSection( $this->options_prefix, [
            'id'    => 'footer_options',
            'title' => esc_html__( 'Footer', 'okthemes-toolkit' ),
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'footer_options',
            'title'  => esc_html__( 'General', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'General', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'notice',
                    'style'   => 'info',
                    'content' => esc_html__( 'If you used theme builder for site footer then disable default theme header', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'default_footer',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Default Footer', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable Theme default footer', 'okthemes-toolkit' ),
                    'options'  => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'enabled',
                ],
                [
                    'type'       => 'notice',
                    'style'      => 'warning',
                    'content'    => esc_html__( 'You have turned off the default theme footer. Configure your website\'s footer from ', 'okthemes-toolkit' ) . '<a href="' . esc_url( $this->template_builder_url ) . '">' . esc_html__( 'here', 'okthemes-toolkit' ) . '</a>',
                    'dependency' => [
                        'default_footer', '==', 'disabled',
                    ],
                ],
                [
                    'id'       => 'copyright',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Copyright?', 'okthemes-toolkit' ),
                    'options'  => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'enabled',
                ],
                [
                    'id'      => 'copyright_text',
                    'type'    => 'textarea',
                    'title'   => esc_html__( 'Copyright Text', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Copyright © 2024. All rights reserved.', 'okthemes-toolkit' ),
                    'dependency' => [
                        'copyright', '==', 'enabled',
                    ],
                ],
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'footer_options',
            'title'  => esc_html__( 'Styling', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Footer Styling', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'footer_background_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Footer background color', 'okthemes-toolkit' ),
                    'default'  => '#000',
                    'desc'     => esc_html__( 'Default: #000', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'footer_text_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Footer text color', 'okthemes-toolkit' ),
                    'default'  => '#ffffff',
                    'desc'     => esc_html__( 'Default: #ffffff', 'okthemes-toolkit' ),
                ],
                
            ],
        ] );
    }
  

    /**
     * Blog Options
     */
    private function blog_section() {
        CSF::createSection( $this->options_prefix, [
            'id'    => 'blog_options',
            'title' => esc_html__( 'Blog', 'okthemes-toolkit' ),
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'blog_options',
            'title'  => esc_html__( 'Blog Archive', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Blog Archive', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Layout', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'blog_archive_sidebar',
                    'type'    => 'image_select',
                    'title'   => esc_html__( 'Sidebar', 'okthemes-toolkit' ),
                    'options' => [
                        'left-sidebar'  => OKT_ASSETS . '/img/options/left-sidebar.png',
                        'right-sidebar' => OKT_ASSETS . '/img/options/right-sidebar.png',
                        'no-sidebar'    => OKT_ASSETS . '/img/options/no-sidebar.png',
                    ],
                    'default' => 'right-sidebar',
                ],
                [
                    'id'      => 'blog_archive_post_layout',
                    'type'    => 'image_select',
                    'title'   => esc_html__( 'Post layout', 'okthemes-toolkit' ),
                    'options' => [
                        'grid' => OKT_ASSETS . '/img/options/grid-layout.png',
                        'list' => OKT_ASSETS . '/img/options/list-layout.png',
                    ],
                    'default' => 'list'
                ],
                [
                    'id'      => 'blog_archive_post_grid_columns',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Columns', 'okthemes-toolkit' ),
                    'options' => [
                        '2' => esc_html__( 'Two', 'okthemes-toolkit' ),
                        '3' => esc_html__( 'Three', 'okthemes-toolkit' ),
                        '4' => esc_html__( 'Four', 'okthemes-toolkit' ),
                    ],
                    'default' => '3',
                    'dependency' => [
                        'blog_archive_post_layout', '==', 'grid',
                    ],
                ],
                [
                    'id'      => 'blog_archive_post_list_style',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Blog list style', 'okthemes-toolkit' ),
                    'options' => [
                        'block' => esc_html__( 'Block', 'okthemes-toolkit' ),
                        'inline' => esc_html__( 'Inline', 'okthemes-toolkit' )
                    ],
                    'default' => 'block',
                    'dependency' => [
                        'blog_archive_post_layout', '==', 'list',
                    ],
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Structure', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'archive_post_featured_image',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Featured Image', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                ],
                [
                    'id'       => 'archive_post_meta',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Meta', 'okthemes-toolkit' ),
                    'dependency' => [
                        'archive_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'id'       => 'archive_post_meta_date',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta Date ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                    'dependency' => [
                        'archive_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'id'       => 'archive_post_meta_category',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta Category ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                    'dependency' => [
                        'archive_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Post content', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'archive_post_content',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Post content', 'okthemes-toolkit' ),
                    'options'  => [
                        'full_content' => esc_html__( 'Full Content', 'okthemes-toolkit' ),
                        'excerpt'  => esc_html__( 'Excerpt', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'full_content',
                ],
                [
                    'id'         => 'archive_post_excerpt_count',
                    'type'       => 'number',
                    'title'      => esc_html__( 'Excerpt Word Count', 'okthemes-toolkit' ),
                    'subtitle'   => esc_html__( 'Set how many words you want to show in the post Excerpt', 'okthemes-toolkit' ),
                    'default'    => 12,
                    'dependency' => [
                        'archive_post_content', '==', 'excerpt',
                    ],
                ],
                [
                    'id'       => 'archive_read_more_button',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Read More Button', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable Post Read More Button on Blog Archive page', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'no',
                    'dependency' => [
                        'archive_post_content', '==', 'excerpt',
                    ],
                ],
                [
                    'id'       => 'archive_read_more_button_text',
                    'type'     => 'text',
                    'title'    => esc_html__( 'Read More Button Text', 'okthemes-toolkit' ),
                    'default'  => esc_html__( 'Read more', 'okthemes-toolkit' ),
                    'dependency' => [
                        ['archive_post_content', '==', 'excerpt'],
                        ['archive_read_more_button', '==', 'yes']
                    ],
                ],
            ],
        ] );

        CSF::createSection( $this->options_prefix, [
            'parent' => 'blog_options',
            'title'  => esc_html__( 'Blog Single Post', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Blog Single Post', 'okthemes-toolkit' ),
                ],

                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Structure', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'single_post_featured_image',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Featured Image inside the content wrapper', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'no',
                ],
                [
                    'id'       => 'single_post_meta',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Meta', 'okthemes-toolkit' ),
                    'dependency' => [
                        'single_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'id'       => 'single_post_meta_date',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta Date ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                    'dependency' => [
                        'single_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'id'       => 'single_post_meta_category',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta Category ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                    'dependency' => [
                        'single_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'id'       => 'single_post_meta_tags',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Meta Tags ', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                    'dependency' => [
                        'single_post_meta', '==', 'yes',
                    ],
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Navigation', 'okthemes-toolkit' )
                ],
                [
                    'id'       => 'single_post_navigation',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Show Post Navigation', 'okthemes-toolkit' ),
                    'options'  => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'yes',
                ],
                [
                    'id'       => 'single_post_navigation_next_button',
                    'type'     => 'text',
                    'title'    => esc_html__( 'Next Button Text', 'okthemes-toolkit' ),
                    'default'  => esc_html__( 'Next article', 'okthemes-toolkit' ),
                    'dependency' => ['single_post_navigation', '==', 'yes']
                ],

            ],
        ] );
    }

    /**
     * Shop Section
     */
    private function shop_section() {
        CSF::createSection( $this->options_prefix, [
            'id'     => 'shop_options',
            'title'  => esc_html__( 'Shop', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Shop', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'activate_product_image_sizes',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Activate product image sizes', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                    'desc'    => esc_html__( 'Check this to activate the inputs for image size change from WooCommerce >> Settings >> Products >> Display', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'store_catalog_mode',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Catalog mode', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'disabled',
                    'desc'    => esc_html__( 'Enable/Disable catalog mode. This will disable: add to cart, checkout and buy functions.', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'product_loop_columns',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Columns', 'okthemes-toolkit' ),
                    'options' => [
                        '1' => esc_html__( 'One', 'okthemes-toolkit' ),
                        '2' => esc_html__( 'Two', 'okthemes-toolkit' ),
                        '3' => esc_html__( 'Three', 'okthemes-toolkit' ),
                        '4' => esc_html__( 'Four', 'okthemes-toolkit' ),
                    ],
                    'default' => '3',
                    'desc'    => esc_html__( 'How many column should be shown per row?', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'product_loop_per_page',
                    'type'    => 'number',
                    'title'   => esc_html__( 'Product Per page', 'okthemes-toolkit' ),
                    'default' => 9,
                    'desc'    => esc_html__( 'How many products should be shown per page?', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Shop Filter Settings', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'store_filter',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product Filtering', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'disabled',
                    'desc'    => esc_html__( 'Toggle to enable or disable product filtering in your store.', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'store_filter_label',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Filter Label Text', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Filter the wines', 'okthemes-toolkit' ),
                    'desc'    => esc_html__( 'Set the label text for the product filter.', 'okthemes-toolkit' ),
                    'dependency' => ['store_filter', '==', 'enabled']
                ],
                [
                    'id'      => 'store_filter_clear_label',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Clear Filter Button Text', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Clear filters', 'okthemes-toolkit' ),
                    'desc'    => esc_html__( 'Set the label for the button that clears all active filters.', 'okthemes-toolkit' ),
                    'dependency' => ['store_filter', '==', 'enabled']
                ],
                [
                    'id'      => 'store_filter_product_count',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Show Product Count in Filter', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                    'desc'    => esc_html__( 'Choose whether to display the number of products available in each filter.', 'okthemes-toolkit' ),
                    'dependency' => ['store_filter', '==', 'enabled']
                ],
                [
                    'id'      => 'store_filter_active_filters',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Display Active Filters', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                    'desc'    => esc_html__( 'Toggle to show or hide the active filters section on the shop page.', 'okthemes-toolkit' ),
                    'dependency' => ['store_filter', '==', 'enabled']
                ],
                
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Shop page options', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'store_sale_flash',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Products sale flash', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'store_products_price',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Products price', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'store_add_to_cart',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Add to cart button', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Product page options', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'product_awards',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product awards', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_pdf_factsheet',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product PDF factsheet', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_sale_flash',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product sale flash', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_products_price',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product price', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_products_excerpt',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product short description', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_products_meta',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product meta', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_add_to_cart',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Product add to cart button', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_related_products',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Related products', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_upsells_products',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Up Sells products', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_reviews_tab',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Reviews tab', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_description_tab',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Description tab', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'product_attributes_tab',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Additional information tab', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Cart page options', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'product_crosssells_products',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Cross Sells products', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
            ],
        ] );
    }

    /**
     * Error Options
     */
    private function error_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Error 404', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Error Page', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'error_title',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Title', 'okthemes-toolkit' ),
                    'default' => esc_html__( '404 Error', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'error_desc',
                    'type'    => 'textarea',
                    'title'   => esc_html__( 'Description', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'It seems we cannot find what you are looking for.', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'error_button_text',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Error Button Text', 'okthemes-toolkit' ),
                    'default' => esc_html__( 'Return To Home', 'okthemes-toolkit' ),
                ],
            ],
        ] );
    }

    /**
     * Comments Options
     */
    private function comments_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Comments', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Comments', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'page_comments',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page comments', 'okthemes-toolkit' ),
                    'subtitle'   => esc_html__( 'Enable/Disable comments on all pages.', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'post_comments',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Post comments', 'okthemes-toolkit' ),
                    'subtitle'   => esc_html__( 'Enable/Disable comments on all posts.', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                
            ],
        ] );
    }

    /**
     * Page Options
     */
    
     private function page_section() {
        CSF::createSection( $this->options_prefix, [
            'id'    => 'page_options',
            'title' => esc_html__( 'Page', 'okthemes-toolkit' ),
        ] );
        CSF::createSection( $this->options_prefix, [
            'parent' => 'page_options',
            'title'  => esc_html__( 'General', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Page', 'okthemes-toolkit' ),
                ],

                [
                    'id'      => 'page_global_sidebar',
                    'type'    => 'image_select',
                    'title'   => esc_html__( 'Layout', 'okthemes-toolkit' ),
                    'options' => [
                        'left-sidebar'  => OKT_ASSETS . '/img/options/left-sidebar.png',
                        'right-sidebar' => OKT_ASSETS . '/img/options/right-sidebar.png',
                        'no-sidebar'    => OKT_ASSETS . '/img/options/no-sidebar.png',
                    ],
                    'default' => 'right-sidebar',
                ],
                
            ],
        ] );
        CSF::createSection( $this->options_prefix, [
            'parent' => 'page_options',
            'title'  => esc_html__( 'Page header', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Page header', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'page_header',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page header', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'page_title',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page Title', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                    'dependency' => ['page_header', '==', 'enabled'],
                ],
                [
                    'type'       => 'subheading',
                    'content'    => esc_html__( 'Page Header Styling', 'okthemes-toolkit' ),
                    'dependency' => ['page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'page_header_min_height',
                    'type'        => 'dimensions',
                    'width' => false,
                    'default'  => array(
                        'height' => '400',
                        'unit'   => 'px',
                    ),
                    'title'       => esc_html__( 'Page min height', 'okthemes-toolkit' ),
                    'desc'       => esc_html__( 'Default: 400px', 'okthemes-toolkit' ),

                    'dependency'  => ['page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'page_header_padding',
                    'type'        => 'spacing',
                    'title'       => esc_html__( 'Padding', 'okthemes-toolkit' ),
                    'default'  => array(
                        'top'    => '4',
                        'right'  => '2.5',
                        'bottom' => '4',
                        'left'   => '2.5',
                        'unit'   => 'em',
                    ),
                    'dependency'  => ['page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'page_header_bg',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                    'default'     => '#f8f5f2',
                    'dependency'  => ['page_header', '==', 'enabled'],
                ]
            ],
        ] );
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Sidebars', 'okthemes-toolkit' ),
            'parent' => 'page_options',
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Sidebars', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'General', 'okthemes-toolkit' ),
                ],
                [
                    'id'    => 'sidebar_width',
                    'type'  => 'dimensions',
                    'title' => 'Sidebar width',
                    'height' => false,
                    'default'  => array(
                        'width'  => '25',
                        'unit'   => '%',
                      ),
                    'desc' => esc_html__( 'Default: 25%', 'okthemes-toolkit' ),
                    'units' => ['%']
                ],
                [
                    'type'    => 'subheading',
                    'content' => esc_html__( 'Sidebar Generator', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'sidebar_options',
                    'type'    => 'repeater',
                    'title'   => esc_html__( 'Sidebar Generator', 'okthemes-toolkit' ),
                    'subtitle'   => esc_html__( 'Create a custom sidebar to use on pages or posts. Then go to Appearance >> Widgets to add widgets.', 'okthemes-toolkit' ),
                    'fields' => [

                        [
                          'id'    => 'sidebar_name',
                          'type'  => 'text',
                          'title' => 'Sidebar name',
                        ],
                    
                    ],
                ],
                
            ],
        ] );
    }

    /**
     * Color Options
     */
    private function colors_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Colors', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Theme Colors', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'primary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Primary Color', 'okthemes-toolkit' ),
                    'default'  => '#8d725b',
                    'desc'     => esc_html__( 'Default: #8d725b', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'secondary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Secondary Color', 'okthemes-toolkit' ),
                    'default'  => '#000',
                    'desc'     => esc_html__( 'Default: #000', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'tertiary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Tertiary Color', 'okthemes-toolkit' ),
                    'default'  => '#fbf6f1',
                    'desc'     => esc_html__( 'Default: #fbf6f1', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'accent_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Accent Color', 'okthemes-toolkit' ),
                    'default'  => '#8d725b',
                    'desc'     => esc_html__( 'Default: #8d725b', 'okthemes-toolkit' ),
                ],

                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Surface colors', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'body_background_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Body background color', 'okthemes-toolkit' ),
                    'default'  => '#ffffff',
                    'desc'     => esc_html__( 'Default: #ffffff', 'okthemes-toolkit' ),
                ],
                
                
                [
                    'id'       => 'modules_background_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Modules background color', 'okthemes-toolkit' ),
                    'default'  => '#fbf6f1',
                    'desc'     => esc_html__( 'Default: #fbf6f1', 'okthemes-toolkit' ),
                ],

                
                
            ],
        ] );
    }

    /**
     * Typography Options
     */
    private function typography_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Typography', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Typography', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'content',
                    'content' => esc_html__( 'You can modify the primary and secondary fonts below while keeping the same font size, line height, etc, loaded by the theme.', 'okthemes-toolkit' ),
                ],

                [
                    'id'         => 'typography_radios',
                    'type'       => 'radio',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'inline' => true,
                    'default'    => 'desktop',
                    'class' => 'responsiveness-switcher',
                    'desc' => 'Switch between Desktop, Tablet, and Mobile views to activate and customize settings specifically tailored for each device type'
                ],
                  
                [
                    'id'                  => 'body_typography',
                    'type'                => 'typography',
                    'title'               => esc_html__( 'Body', 'okthemes-toolkit' ),
                    'media_query_desktop' => true,                 
                    'default' => array(
                        'font-family'         => 'Manrope',
                        'font-weight'         => '400',
                        'font-size'           => '18',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'text-transform'      => 'none',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                        'type'                => 'google',
                        'color'               => '#000',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency'         => array( 'typography_radios', '==', 'desktop' ),
                    
                ],

                [
                    'id'               => 'body_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Body (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '18',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],

                [
                    'id'               => 'body_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Body (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '18',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],

                [
                    'id'               => 'h1_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 1', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-weight'         => '300',
                        'font-size'           => '4',
                        'line-height'         => '1.3',
                        'text-transform'      => 'none',
                        'letter-spacing'      => '0',
                        'type'                => 'google',
                        'color'               => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],

                [
                    'id'               => 'h1_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 1 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '3.4',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h1_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 1 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],

                [
                    'id'               => 'h2_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 2', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '300',
                        'font-size'          => '3',
                        'line-height'        => '1.3',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'color'              => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'               => 'h2_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 2 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '2.8',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h2_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 2 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '2.3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                [
                    'id'               => 'h3_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 3', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '300',
                        'font-size'          => '2.3',
                        'line-height'        => '1.3',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'color'              => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'               => 'h3_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 3 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '2.3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h3_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 3 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '2',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                [
                    'id'               => 'h4_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 4', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '300',
                        'font-size'          => '2',
                        'line-height'        => '1.3',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'color'              => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'               => 'h4_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 4 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '2',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h4_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 4 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '1.75',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                [
                    'id'               => 'h5_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 5', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Ibarra Real Nova',
                        'font-weight'        => '300',
                        'font-size'          => '1.5',
                        'line-height'        => '1.3',
                        'text-transform'     => 'none',
                        'letter-spacing'     => '0',
                        'type'               => 'google',
                        'color'              => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'               => 'h5_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 5 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '1.75',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h5_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 5 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '1.35',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                [
                    'id'               => 'h6_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 6', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-family'        => 'Manrope',
                        'font-weight'        => '600',
                        'font-size'          => '1',
                        'line-height'        => '1.3',
                        'text-transform'     => 'uppercase',
                        'letter-spacing'     => '3',
                        'type'               => 'google',
                        'color'              => '#000',
                        'font-size-unit'      => 'rem',
                        'line-height-unit'    => 'em',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => true,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'               => 'h6_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 6 (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '1',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '3',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],

                [
                    'id'               => 'h6_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Heading 6 (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '1',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.3',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '3',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                
                [
                    'id'      => 'paragraph_spacing',
                    'type'    => 'number',
                    'title'   => 'Paragraph margin bottom',
                    'desc'    => esc_html__( 'Default: 1em', 'okthemes-toolkit' ),
                    'default' => array(
                      'number' => '1.4', 
                      'unit'   => 'em',
                    ),
                    'unit'    => true, // Enable unit selection
                    'dependency' => array( 'typography_radios', '==', 'desktop' )
                ],
                [
                    'id'      => 'paragraph_spacing_tablet',
                    'type'    => 'number',
                    'title'   => 'Paragraph margin bottom (tablet)',
                    'desc'    => esc_html__( 'Default: 1em', 'okthemes-toolkit' ),
                    'default' => array(
                      'number' => '1', 
                      'unit'   => 'em',
                    ),
                    'unit'    => true, // Enable unit selection
                    'dependency' => array( 'typography_radios', '==', 'tablet' )
                ],
                [
                    'id'      => 'paragraph_spacing_mobile',
                    'type'    => 'number',
                    'title'   => 'Paragraph margin bottom (mobile)',
                    'desc'    => esc_html__( 'Default: 1em', 'okthemes-toolkit' ),
                    'default' => array(
                      'number' => '1', 
                      'unit'   => 'em',
                    ),
                    'unit'    => true, // Enable unit selection
                    'dependency' => array( 'typography_radios', '==', 'mobile' )
                ],
                
            ],
        ] );
    }

    /**
     * Typography Options
     */
    private function buttons_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Buttons', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Buttons', 'okthemes-toolkit' ),
                ],
                [
                    'type'    => 'content',
                    'content' => esc_html__( 'Modify the default styling of buttons', 'okthemes-toolkit' ),
                ],

                [
                    'id'      => 'buttons_text_color',
                    'type'    => 'link_color',
                    'title'   => 'Buttons Text Color',
                    'default' => array(
                      'color' => '#ffffff',
                      'hover' => '#ffffff',
                    ),
                ],
                [
                    'id'      => 'buttons_background_color',
                    'type'    => 'link_color',
                    'title'   => 'Buttons Background Color',
                    'default' => array(
                      'color' => '#8d725b',
                      'hover' => '#000000',
                    ),
                ],
                [
                    'id'      => 'buttons_border',
                    'type'    => 'border',
                    'title'   => 'Buttons Border',
                    'default' => array(
                        'top'    => '1',
                        'right'  => '1',
                        'bottom' => '1',
                        'left'   => '1',
                        'style'  => 'solid',
                        'unit'   => 'px',
                    ),
                    'color'  => false
                ],
                [
                    'id'          => 'buttons_border_radius',
                    'type'        => 'number',
                    'title'       => 'Buttons Border Radius',
                    'unit'        => 'px',
                    'default' => array(
                        'number' => '30', 
                        'unit'   => 'px',
                      ),
                      'unit'    => true, // Enable unit selection
                ],
                [
                    'id'      => 'buttons_border_color',
                    'type'    => 'link_color',
                    'title'   => 'Buttons Border Color',
                    'default' => array(
                      'color' => '#8d725b',
                      'hover' => '#000000',
                    ),
                ],

                [
                    'id'         => 'buttons_typography_radios',
                    'type'       => 'radio',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'inline' => true,
                    'default'    => 'desktop',
                    'class' => 'responsiveness-switcher',
                    'desc' => 'Switch between Desktop, Tablet, and Mobile views to activate and customize settings specifically tailored for each device type'
                ],

                [
                    'id'                  => 'buttons_typography',
                    'type'                => 'typography',
                    'title'               => esc_html__( 'Buttons Typography', 'okthemes-toolkit' ),
                    'media_query_desktop' => true,                 
                    'default' => array(
                        'font-family'         => 'Manrope',
                        'font-weight'         => 'normal',
                        'font-size'           => '16',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'text-transform'      => 'none',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                        'type'                => 'google',
                    ),
                    'font_family'        => true,
                    'font_weight'        => true,
                    'font_style'         => true,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => true,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => true,
                    'dependency' => array( 'buttons_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'               => 'buttons_typography_tablet',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Buttons Typography (tablet)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '16',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'buttons_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],

                [
                    'id'               => 'buttons_typography_mobile',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Buttons Typography (mobile)', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size'           => '16',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.8',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0',
                        'letter-spacing-unit' => 'px',
                    ),
                    'font_family'        => false,
                    'font_weight'        => false,
                    'font_style'         => false,
                    'extra_styles'       => false,
                    'font_size'          => true,
                    'line_height'        => true,
                    'letter_spacing'     => true,
                    'text_align'         => false,
                    'text_transform'     => false,
                    'color'              => false,
                    'backup_font_family' => false,
                    'subset'             => false,
                    'preview'            => false,
                    'dependency' => array( 'buttons_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
                [
                    'id'       => 'buttons_spacing',
                    'type'     => 'spacing',
                    'title'    => 'Buttons Padding',
                    'default'  => array(
                        'top'    => '15',
                        'right'  => '35',
                        'bottom' => '15',
                        'left'   => '35',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'desktop' )
                ],
                [
                    'id'       => 'buttons_spacing_tablet',
                    'type'     => 'spacing',
                    'title'    => 'Buttons Padding (tablet)',
                    'default'  => array(
                        'top'    => '15',
                        'right'  => '35',
                        'bottom' => '15',
                        'left'   => '35',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'tablet' )
                ],
                [
                    'id'       => 'buttons_spacing_mobile',
                    'type'     => 'spacing',
                    'title'    => 'Buttons Padding (mobile)',
                    'default'  => array(
                        'top'    => '15',
                        'right'  => '35',
                        'bottom' => '15',
                        'left'   => '35',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'mobile' )
                ],
                
                
                
                
            ],
        ] );
    }

    /**
     * Custom Script Options
     */
    private function custom_scrips_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Custom Scripts', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Custom Scripts', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'custom_scripts',
                    'type'     => 'code_editor',
                    'title'    => esc_html__( 'JS Code', 'okthemes-toolkit' ),
                    'settings' => [
                        'theme' => 'mbo',
                        'mode'  => 'javascript',
                    ],
                    'subtitle' => esc_html__( 'Add your custom js code here. Without script tag and valid code.', 'okthemes-toolkit' ),
                ],
                
                [
                    'type'    => 'submessage',
                    'style'   => 'info',
                    'content' => esc_html__( 'You can add custom css in Appearance>Customize>Additional CSS', 'okthemes-toolkit' ),
                ],
            ],
        ] );
    }

    /**
     * Backup Options
     */
    private function backup_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Backup', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Backup', 'okthemes-toolkit' ),
                ],
                [
                    'type' => 'backup',
                ],
            ],
        ] );
    }

}