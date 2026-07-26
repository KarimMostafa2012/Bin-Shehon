<?php
namespace OKThemes\Toolkit\ThemeOptions;

use CSF;

defined('ABSPATH') || exit;

class Juliette {
    private static $instance = null;

    private $options_prefix = 'juliette_options';
    private $menu_slug      = 'juliette_options';
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
                    'desc'    => esc_html__( 'Sets the default width of the content area (Default: 1460px)', 'okthemes-toolkit' ),
                    'default' => array(
                      'number' => '1460', 
                      'unit'   => 'px',
                    ),
                    'unit'    => true, // Enable unit selection
                ],
                [
                    'id'      => 'container_padding',
                    'type'    => 'spacing',
                    'title'   => 'Container Padding',
                    'desc'    => esc_html__( 'Sets the default padding for Elementor containers (Default: 20px)', 'okthemes-toolkit' ),
                    'default' => array(
                      'top'    => '20',
                      'right'  => '20',
                      'bottom' => '20',
                      'left'   => '20',
                      'unit'   => 'px',
                    ),
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
                    'title'    => esc_html__( 'Header Background Color', 'okthemes-toolkit' ),
                    'default'  => '#FFFAF7',
                ],
                [
                    'id'       => 'header_text_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Header Text Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
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
                    'title'    => esc_html__( 'Footer Background Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'id'       => 'footer_text_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Footer Text Color', 'okthemes-toolkit' ),
                    'default'  => '#ffffff',
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
     * Shop Options
     */
    private function shop_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Shop', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Shop Options', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'shop_columns',
                    'type'     => 'number',
                    'title'    => esc_html__( 'Shop Columns', 'okthemes-toolkit' ),
                    'default'  => '3',
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
     * Colors Options
     */
    private function colors_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Colors', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Global Colors', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'primary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Primary Color', 'okthemes-toolkit' ),
                    'default'  => '#e4afac',
                ],
                [
                    'id'       => 'secondary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Secondary Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'id'       => 'tertiary_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Tertiary Color', 'okthemes-toolkit' ),
                    'default'  => '#fffaf7',
                ],
                [
                    'id'       => 'accent_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Accent Color', 'okthemes-toolkit' ),
                    'default'  => '#e4afac',
                ],
                [
                    'id'       => 'modules_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Modules Color', 'okthemes-toolkit' ),
                    'default'  => '#FBF5EF',
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Body Colors', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'body_background_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Body Background Color', 'okthemes-toolkit' ),
                    'default'  => '#FFFAF7',
                ],
                [
                    'id'       => 'body_text_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Body Text Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'id'       => 'links_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Links Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
            ],
        ] );
    }

    /**
     * Typography Options
     */
    private function typography_section() {
        CSF::createSection( $this->options_prefix, [
            'id'    => 'typography_options',
            'title' => esc_html__( 'Typography', 'okthemes-toolkit' ),
        ] );

        // Primary & Secondary Fonts
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'Special Fonts Style', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Special Fonts Style', 'okthemes-toolkit' ),
                ],
                [
                    'id'               => 'accent_font',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Accent', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '700',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'uppercase',
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
                ],
                [
                    'id'      => 'lead_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Lead', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '1.3',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '500',
                        'line-height'         => '1.4',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ],
                [
                    'id'      => 'subtitle_d_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Subtitle (Display)', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '10',
                        'font-size-unit'      => 'vw',
                        'font-weight'         => '700',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ],
                [
                    'id'      => 'subtitle_l_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Subtitle (L)', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '4.4',
                        'font-size-unit'      => 'vw',
                        'font-weight'         => '500',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ],
                [
                    'id'      => 'subtitle_s_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Subtitle (S)', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '2.2',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '500',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ],
                [
                    'id'      => 'subtitle_s_i_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Subtitle (S) Italic', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '2.2',
                        'font-size-unit'      => 'rem',
                        'font-style'          => 'italic',
                        'font-weight'         => '500',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ],
                [
                    'id'      => 'subtitle_xs_font',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Subtitle (XS)', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '1.3',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '500',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                ]
            ],
        ] );

        // Body Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'Body', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Body Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'body_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'body_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Body Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '18',
                        'font-size-unit'      => 'px',
                        'font-weight'         => '400',
                        'line-height'         => '1.6',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-1',
                        'letter-spacing-unit' => 'px',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'body_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'body_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'Body Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '16',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.6',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-1',
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
                    'dependency'         => array( 'body_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'body_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'Body Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '16',
                        'font-size-unit'      => 'px',
                        'line-height'         => '1.6',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-1',
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
                    'dependency'         => array( 'body_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H1 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H1', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H1 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h1_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h1_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H1 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '7',
                        'font-size-unit'      => 'vw',
                        'font-weight'         => '900',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'h1_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h1_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H1 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '6',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h1_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h1_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H1 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '4',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h1_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H2 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H2', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H2 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h2_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h2_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H2 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '5.6',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '900',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'h2_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h2_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H2 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '4',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h2_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h2_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H2 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h2_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H3 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H3', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H3 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h3_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h3_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H3 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '900',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'h3_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h3_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H3 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h3_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h3_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H3 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h3_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H4 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H4', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H4 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h4_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h4_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H4 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Ibarra Real Nova',
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '500',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'h4_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h4_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H4 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '3',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h4_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h4_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H4 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '2.6',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1.1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h4_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H5 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H5', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H5 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h5_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h5_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H5 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '700',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'uppercase',
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
                    'dependency'         => array( 'h5_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h5_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H5 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h5_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h5_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H5 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h5_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );

        // H6 Typography
        CSF::createSection( $this->options_prefix, [
            'parent' => 'typography_options',
            'title'  => esc_html__( 'H6', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'H6 Typography', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'h6_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'h6_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'H6 Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '1',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '900',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'none',
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
                    'dependency'         => array( 'h6_typography_radios', '==', 'desktop' ),
                ],
                [
                    'id'                 => 'h6_typography_tablet',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H6 Typography (tablet)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '1',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h6_typography_radios', '==', 'tablet' ),
                    'media_query_tablet' => true
                ],
                [
                    'id'                 => 'h6_typography_mobile',
                    'type'               => 'typography',
                    'title'              => esc_html__( 'H6 Typography (mobile)', 'okthemes-toolkit' ),
                    'default'            => array(
                        'font-size'           => '1',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '-0.05',
                        'letter-spacing-unit' => 'em',
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
                    'dependency'         => array( 'h6_typography_radios', '==', 'mobile' ),
                    'media_query_mobile' => true
                ],
            ],
        ] );
    }

    /**
     * Buttons Options
     */
    private function buttons_section() {
        CSF::createSection( $this->options_prefix, [
            'title'  => esc_html__( 'Buttons', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Buttons Styling', 'okthemes-toolkit' ),
                ],
                [
                    'id'         => 'buttons_typography_radios',
                    'type'       => 'button_set',
                    'title'      => '',
                    'options'    => $this->responsive_radio_trigger_array,
                    'default'    => 'desktop'
                ],
                [
                    'id'               => 'buttons_typography',
                    'type'             => 'typography',
                    'title'            => esc_html__( 'Buttons Typography', 'okthemes-toolkit' ),
                    'default'          => array(
                        'font-family'         => 'Inter',
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'font-weight'         => '700',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
                        'text-transform'      => 'uppercase',
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
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
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
                        'font-size'           => '0.9',
                        'font-size-unit'      => 'rem',
                        'line-height'         => '1',
                        'line-height-unit'    => 'em',
                        'letter-spacing'      => '0.05',
                        'letter-spacing-unit' => 'em',
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
                        'top'    => '30',
                        'right'  => '50',
                        'bottom' => '30',
                        'left'   => '50',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'desktop' )
                ],
                [
                    'id'       => 'buttons_spacing_tablet',
                    'type'     => 'spacing',
                    'title'    => 'Buttons Padding (tablet)',
                    'default'  => array(
                        'top'    => '30',
                        'right'  => '30',
                        'bottom' => '30',
                        'left'   => '30',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'tablet' )
                ],
                [
                    'id'       => 'buttons_spacing_mobile',
                    'type'     => 'spacing',
                    'title'    => 'Buttons Padding (mobile)',
                    'default'  => array(
                        'top'    => '20',
                        'right'  => '30',
                        'bottom' => '20',
                        'left'   => '30',
                        'unit'   => 'px',
                    ),
                    'dependency' => array( 'buttons_typography_radios', '==', 'mobile' )
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Buttons Colors', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'buttons_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Text Color', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'id'       => 'buttons_color_hover',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Text Color (Hover)', 'okthemes-toolkit' ),
                    'default'  => '#e4afac',
                ],
                [
                    'id'       => 'buttons_bg_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Background Color', 'okthemes-toolkit' ),
                    'default'  => '#e4afac',
                ],
                [
                    'id'       => 'buttons_bg_color_hover',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Background Color (Hover)', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'id'       => 'buttons_border_color',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Border Color', 'okthemes-toolkit' ),
                    'default'  => '#e4afac',
                ],
                [
                    'id'       => 'buttons_border_color_hover',
                    'type'     => 'color',
                    'title'    => esc_html__( 'Buttons Border Color (Hover)', 'okthemes-toolkit' ),
                    'default'  => '#2D2A26',
                ],
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Buttons Border', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'buttons_border',
                    'type'     => 'border',
                    'title'    => esc_html__( 'Buttons Border', 'okthemes-toolkit' ),
                    'default'  => array(
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                        'style'  => 'solid',
                        'color'  => '#e4afac',
                    ),
                ],
                [
                    'id'       => 'buttons_border_radius',
                    'type'     => 'spacing',
                    'title'    => esc_html__( 'Buttons Border Radius', 'okthemes-toolkit' ),
                    'default'  => array(
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                        'unit'   => 'px',
                    ),
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
