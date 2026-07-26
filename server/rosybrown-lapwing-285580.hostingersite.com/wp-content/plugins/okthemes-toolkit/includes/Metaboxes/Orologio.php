<?php
namespace OKThemes\Toolkit\Metaboxes;

use CSF;

defined('ABSPATH') || exit;

class Orologio {
    private static $instance = null;

    private $global_prefix    = 'orologio_global_meta';
    private $post_prefix      = 'orologio_post_meta';
    private $page_prefix      = 'orologio_page_meta';
    private $product_prefix   = 'orologio_product_meta';

    private $template_builder_url;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        if (!class_exists('CSF')) return;

        $this->template_builder_url = admin_url('edit.php?post_type=okthemes_template');

        $this->register_global_metaboxes();
        $this->register_post_metaboxes();
        $this->register_page_metaboxes();
        $this->register_product_metaboxes();
    }

    private function register_global_metaboxes() {
        CSF::createMetabox( $this->global_prefix, [
            'title'        => esc_html__( 'Options', 'okthemes-toolkit' ),
            'post_type'    => array('post','page'),
            'show_restore' => true,
        ] );
        
        // Page Layout
        CSF::createSection( $this->global_prefix, [
            'title'  => esc_html__( 'Layout', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Page Layout', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'page_layout_select',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page Layout', 'okthemes-toolkit' ),
                    'options' => [
                        'theme_default' => esc_html__( 'Default', 'okthemes-toolkit' ),
                        'custom'        => esc_html__( 'Custom', 'okthemes-toolkit' )
                    ],
                    'default' => 'theme_default',
                    
                ],
                [
                    'id'      => 'page_layout',
                    'type'    => 'image_select',
                    'title'   => esc_html__( 'Sidebar', 'okthemes-toolkit' ),
                    'options' => [
                        'left-sidebar'  => OKT_ASSETS . '/img/options/left-sidebar.jpg',
                        'right-sidebar' => OKT_ASSETS . '/img/options/right-sidebar.jpg',
                        'no-sidebar'    => OKT_ASSETS . '/img/options/no-sidebar.jpg',
                    ],
                    'default' => 'no-sidebar',
                    'dependency' => [
                        'page_layout_select', '==', 'custom',
                    ],
                ],
                [
                    'id'          => 'sidebar_select',
                    'type'        => 'select',
                    'title'       => 'Select sidebar',
                    'placeholder' => 'Select sidebar',
                    'chosen'      => true,
                    'options'     => 'sidebars',
                    'dependency' => [
                        ['page_layout', '!=', 'no-sidebar'],
                        ['page_layout_select', '==', 'custom']
                    ],
                ]
            ],
        ] );

        // Page Title
        CSF::createSection( $this->global_prefix, [
            'title'  => esc_html__( 'Page Header', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'heading',
                    'content' => esc_html__( 'Page header', 'okthemes-toolkit' ),
                ],
                [
                    'id'      => 'meta_page_header',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page header', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                ],
                [
                    'id'      => 'meta_page_title',
                    'type'    => 'button_set',
                    'title'   => esc_html__( 'Page Title', 'okthemes-toolkit' ),
                    'options' => [
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default' => 'enabled',
                    'dependency' => ['meta_page_header', '==', 'enabled'],
                ],
                [
                    'id'         => 'page_header_top_title',
                    'type'       => 'text',
                    'title'      => esc_html__( 'Page Top Title', 'okthemes-toolkit' ),
                    'dependency' => ['meta_page_title', '!=', 'disabled'],
                ],
                [
                    'id'         => 'page_header_custom_title',
                    'type'       => 'wp_editor',
                    'title'      => esc_html__( 'Custom Page Title', 'okthemes-toolkit' ),
                    'subtitle'   => esc_html__( 'Override the default page title with custom text. HTML tags like &lt;em&gt;, &lt;strong&gt; are supported.', 'okthemes-toolkit' ),
                    'tinymce'    => true,
                    'media_buttons' => false,
                    'quicktags'  => true,
                    'height'     => '100',
                    'dependency' => ['meta_page_title', '!=', 'disabled'],
                ],
                [
                    'id'         => 'page_header_description',
                    'type'       => 'textarea',
                    'title'      => esc_html__( 'Page description', 'okthemes-toolkit' ),
                    'dependency' => ['meta_page_title', '!=', 'disabled'],
                ],
                [
                    'type'       => 'subheading',
                    'content'    => esc_html__( 'Page Header Styling', 'okthemes-toolkit' ),
                    'dependency' => ['meta_page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'meta_page_header_min_height',
                    'type'        => 'dimensions',
                    'width' => false,
                    'title'       => esc_html__( 'Page min height', 'okthemes-toolkit' ),
                    'desc'       => esc_html__( 'Default: 400px', 'okthemes-toolkit' ),
                    'output'      => '.site-subheader .page-meta',
                    'output_prefix' => 'min',
                    'dependency'  => ['meta_page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'meta_page_header_padding',
                    'type'        => 'spacing',
                    'title'       => esc_html__( 'Padding', 'okthemes-toolkit' ),
                    'output'      => '.site-subheader .page-meta .page-meta-wrapper',
                    'output_mode' => 'padding',
                    'dependency'  => ['meta_page_header', '==', 'enabled'],
                ],
                [
                    'id'          => 'meta_page_header_bg',
                    'type'        => 'color',
                    'title'       => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                    'output'      => '.site-subheader',
                    'output_mode' => 'background-color',
                    'dependency'  => ['meta_page_header', '==', 'enabled'],
                ],
                [
                    'id'         => 'meta_page_title_typo',
                    'type'       => 'typography',
                    'title'      => esc_html( 'Typography', 'okthemes-toolkit' ),
                    'default' => array(
                        'font-size-unit'      => 'px',
                        'line-height-unit'    => 'px',
                        'letter-spacing-unit' => 'px',
                    ),
                    'output'     => '.site-subheader .page-meta .page-meta-wrapper h1',
                    'dependency' => ['meta_page_header', '==', 'enabled'],
                ],
            ],
        ] );

        // Header
        CSF::createSection( $this->global_prefix, [
            'title'  => esc_html__( 'Site Header', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'notice',
                    'style'   => 'info',
                    'content' => esc_html__( 'If you used theme builder for page header then disable default header', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'page_default_header',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Default Header', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable page default header. Default comes form theme option', 'okthemes-toolkit' ),
                    'options'  => [
                        'default'  => esc_html__( 'Default', 'okthemes-toolkit' ),
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'default',
                ],
                [
                    'type'       => 'notice',
                    'style'      => 'warning',
                    'content'    => esc_html__( 'You disabled default header. Set your page header from ', 'okthemes-toolkit' ) . '<a href="' . esc_url( $this->template_builder_url ) . '">' . esc_html__( 'here', 'okthemes-toolkit' ) . '</a>',
                    'dependency' => [
                        'page_default_header', '==', 'disabled',
                    ],
                ],
            ],
        ] );

        // Footer
        CSF::createSection( $this->global_prefix, [
            'title'  => esc_html__( 'Footer', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'type'    => 'notice',
                    'style'   => 'info',
                    'content' => esc_html__( 'If you used theme builder for page footer then disable default footer', 'okthemes-toolkit' ),
                ],
                [
                    'id'       => 'page_default_footer',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Default Footer', 'okthemes-toolkit' ),
                    'subtitle' => esc_html__( 'Enable or Disable page default footer. Default comes form theme option', 'okthemes-toolkit' ),
                    'options'  => [
                        'default'  => esc_html__( 'Default', 'okthemes-toolkit' ),
                        'enabled'  => esc_html__( 'Enable', 'okthemes-toolkit' ),
                        'disabled' => esc_html__( 'Disable', 'okthemes-toolkit' ),
                    ],
                    'default'  => 'default',
                ],
                [
                    'type'       => 'notice',
                    'style'      => 'warning',
                    'content'    => esc_html__( 'You disabled default footer. Set your page footer form ', 'okthemes-toolkit' ) . '<a href="' . esc_url( $this->template_builder_url ) . '">' . esc_html__( 'here', 'okthemes-toolkit' ) . '</a>',
                    'dependency' => [
                        'page_default_footer', '==', 'disabled',
                    ],
                ],
            ],
        ] );
    }

    private function register_post_metaboxes() {
        CSF::createMetabox( $this->post_prefix, [
            'title'        => esc_html__( 'Post Options', 'okthemes-toolkit' ),
            'post_type'    => 'post',
            'show_restore' => true,
        ] );

        // Page Layout
        CSF::createSection( $this->post_prefix, [
            'title'  => esc_html__( 'Layout', 'okthemes-toolkit' ),
            'fields' => [
                [
                    'id'         => 'post_social_share',
                    'type'       => 'button_set',
                    'title'      => esc_html__( 'Social share', 'okthemes-toolkit' ),
                    'options'    => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'    => 'yes',
                ],
                [
                    'id'         => 'post_navigation',
                    'type'       => 'button_set',
                    'title'      => esc_html__( 'Post navigation', 'okthemes-toolkit' ),
                    'options'    => [
                        'yes' => esc_html__( 'Yes', 'okthemes-toolkit' ),
                        'no'  => esc_html__( 'No', 'okthemes-toolkit' ),
                    ],
                    'default'    => 'yes',
                ],
            ],
        ] );
    }

    private function register_page_metaboxes() {
        CSF::createMetabox( $this->page_prefix, [
            'title'        => esc_html__( 'Okthemes Page Options', 'okthemes-toolkit' ),
            'post_type'    => 'page',
            'show_restore' => true,
        ] );
    }

    private function register_product_metaboxes() {
        CSF::createMetabox( $this->product_prefix, [
            'title'        => esc_html__( 'Product Options', 'okthemes-toolkit' ),
            'post_type'    => 'product',
            'show_restore' => true,
        ] );
    }
}
