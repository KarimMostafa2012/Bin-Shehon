<?php
namespace OKThemes\Toolkit\TemplateBuilder;

use CSF;

defined('ABSPATH') || exit;

/**
 * Template Metaboxes for Builder Templates
 *
 * @package OKThemesToolkit
 */
class Metaboxes {

    protected static $instance = null;
    private $prefix = 'okthemes_template_meta';

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->init_metaboxes();
    }

    public function init_metaboxes() {
        CSF::createMetabox($this->prefix, [
            'title'        => esc_html__('Template Settings', 'okthemes-toolkit'),
            'post_type'    => 'okthemes_template',
            'show_restore' => true,
            'theme'        => 'dark',
            'data_type'    => 'unserialize',
        ]);

        // All fields are preserved without change from your original code
        CSF::createSection($this->prefix, [
            'fields' => $this->get_fields(),
        ]);
    }

    protected function get_fields() {
        // Moved the whole fields array here for organization, if you prefer to modularize it
        return [
            [
                'id'     => 'okthemes_tb_settings',
                'type'   => 'fieldset',
                'title'  => esc_html__( 'Common Settings', 'okthemes-toolkit' ),
                'fields' => [
                    [
                        'id'          => 'template_type',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Template Type', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Type', 'okthemes-toolkit' ),
                        'options'     => [
                            'header'    => esc_html__( 'Header', 'okthemes-toolkit' ),
                            'footer'    => esc_html__( 'Footer', 'okthemes-toolkit' ),
                            'block'     => esc_html__( 'Block', 'okthemes-toolkit' ),
                            'popup'     => esc_html__( 'Popup', 'okthemes-toolkit' ),
                            'offcanvas' => esc_html__( 'OffCanvas', 'okthemes-toolkit' ),
                        ],
                        'default'     => 'block',
                    ],
                    [
                        'id'         => 'popup_width',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Popup Width', 'okthemes-toolkit' ),
                        'subtitle'   => esc_html__( 'Select or type a value (PX)', 'okthemes-toolkit' ),
                        'options'    => [
                            'full'   => esc_html__( 'Full', 'okthemes-toolkit' ),
                            'custom' => esc_html__( 'Custom', 'okthemes-toolkit' ),
                        ],
                        'default'    => 'custom',
                        'dependency' => ['template_type', '==', 'popup'],
                    ],
                    [
                        'id'         => 'set_popup_width',
                        'type'       => 'dimensions',
                        'title'      => esc_html__( 'Popup Width', 'okthemes-toolkit' ),
                        'default'    => [
                            'width' => '820',
                        ],
                        'height'     => false,
                        'units'      => ['px'],
                        'show_units' => false,
                        'dependency' => ['template_type|popup_width', '==|==', 'popup|custom'],
                    ],
                    [
                        'id'         => 'popup_height',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Popup Height', 'okthemes-toolkit' ),
                        'subtitle'   => esc_html__( 'Set the popup max height.', 'okthemes-toolkit' ),
                        'options'    => [
                            'fit_content' => esc_html__( 'Fit Content', 'okthemes-toolkit' ),
                            'full'        => esc_html__( 'Full', 'okthemes-toolkit' ),
                            'custom'      => esc_html__( 'Custom', 'okthemes-toolkit' ),
                        ],
                        'default'    => 'fit_content',
                        'dependency' => ['template_type', '==', 'popup'],
                    ],
                    [
                        'id'         => 'set_popup_height',
                        'type'       => 'dimensions',
                        'title'      => esc_html__( 'Height', 'okthemes-toolkit' ),
                        'default'    => [
                            'height' => '520',
                        ],
                        'width'      => false,
                        'units'      => ['px'],
                        'show_units' => false,
                        'dependency' => ['template_type|popup_height', '==|==', 'popup|custom'],
                    ],
                    [
                        'id'         => 'popup_position',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Popup Position', 'okthemes-toolkit' ),
                        'subtitle'   => esc_html__( 'Choose the popup position on page.', 'okthemes-toolkit' ),
                        'options'    => [
                            'center-center' => esc_html__( 'Center Center', 'okthemes-toolkit' ),
                            'center-left'   => esc_html__( 'Center Left', 'okthemes-toolkit' ),
                            'center-right'  => esc_html__( 'Center Right', 'okthemes-toolkit' ),
                            'bottom-center' => esc_html__( 'Bottom Center', 'okthemes-toolkit' ),
                            'top-center'    => esc_html__( 'Top Center', 'okthemes-toolkit' ),
                            'bottom-left'   => esc_html__( 'Bottom Left', 'okthemes-toolkit' ),
                            'top-left'      => esc_html__( 'Top Left', 'okthemes-toolkit' ),
                            'bottom-right'  => esc_html__( 'Bottom Right', 'okthemes-toolkit' ),
                            'top-right'     => esc_html__( 'Top Right', 'okthemes-toolkit' ),
                        ],
                        'default'    => 'center-center',
                        'dependency' => ['template_type', '==', 'popup'],
                    ],
                    [
                        'id'         => 'popup_overly_color',
                        'type'       => 'color',
                        'title'      => esc_html__( 'Popup Overly Color', 'okthemes-toolkit' ),
                        'dependency' => ['template_type', '==', 'popup'],
                        'default'    => 'rgba(0, 0, 0, 0.5)',
                    ],
                    [
                        'id'         => 'popup_close',
                        'type'       => 'select',
                        'title'      => esc_html__( 'Popup Close Button', 'okthemes-toolkit' ),
                        'subtitle'   => esc_html__( 'Show or hide the Close button', 'okthemes-toolkit' ),
                        'options'    => [
                            'show'   => esc_html__( 'Show', 'okthemes-toolkit' ),
                            'hide' => esc_html__( 'Hide', 'okthemes-toolkit' ),
                        ],
                        'default'    => 'hide',
                        
                    ],
                    [
                        'id'         => 'popup_close_color',
                        'type'       => 'color',
                        'title'      => esc_html__( 'Popup Close Color', 'okthemes-toolkit' ),
                        'dependency' => ['template_type', '==', 'popup'],
                        'default'    => '#fb2614',
                    ],
                    [
                        'id'         => 'popup_close_bg',
                        'type'       => 'color',
                        'title'      => esc_html__( 'Popup Close Color', 'okthemes-toolkit' ),
                        'dependency' => ['template_type', '==', 'popup'],
                        'default'    => '#ffffff',
                    ],
                    [
                        'id'         => 'popup_close_size',
                        'type'       => 'dimensions',
                        'title'      => esc_html__( 'Popup Close Size', 'okthemes-toolkit' ),
                        'dependency' => ['template_type', '==', 'popup'],
                        'units'      => ['px'],
                        'default'    => [
                            'width'  => '40',
                            'height' => '40',
                        ],
                        'show_units' => false,
                    ],
                    [
                        'id'         => 'popup_close_radius',
                        'type'       => 'text',
                        'title'      => esc_html__( 'Popup Close Radius', 'okthemes-toolkit' ),
                        'default'    => 0,
                        'dependency' => ['template_type', '==', 'popup'],
                    ],
                    [
                        'id'         => 'popup_delay',
                        'type'       => 'text',
                        'title'      => esc_html__( 'Popup Delay', 'okthemes-toolkit' ),
                        'dependency' => ['template_type', '==', 'popup'],
                        'default'    => 3,
                        'subtitle'   => esc_html__( 'Show when page is loaded (Second).', 'okthemes-toolkit' ),
                    ],
                    [
                        'id'         => 'offcanvas_width',
                        'type'       => 'dimensions',
                        'title'      => esc_html__( 'Width', 'okthemes-toolkit' ),
                        'height'     => false,
                        'units'      => ['px'],
                        'default'    => [
                            'width' => '420',
                        ],
                        'show_units' => false,
                        'dependency' => ['template_type', '==', 'offcanvas'],
                    ],
                ],
            ],
            [
                'id'           => 'okthemes_tb_include',
                'type'         => 'repeater',
                'title'        => esc_html__( 'Display On', 'okthemes-toolkit' ),
                'subtitle'     => esc_html__( 'Select the locations where this item should be visible.', 'okthemes-toolkit' ),
                'button_title' => esc_html__( 'Add Display Rule', 'okthemes-toolkit' ),
                'dependency'   => ['template_type', 'any', 'header,footer,popup'],
                'fields'       => [
                    [
                        'type'    => 'subheading',
                        'content' => esc_html__( 'Define Rule', 'okthemes-toolkit' ),
                    ],
                    [
                        'id'      => 'rule',
                        'type'    => 'select',
                        'title'   => esc_html__( 'Display on', 'okthemes-toolkit' ),
                        'options' => [
                            'entire_website'    => esc_html__( 'Entire Website', 'okthemes-toolkit' ),
                            'all_pages'         => esc_html__( 'All Pages', 'okthemes-toolkit' ),
                            'front_page'        => esc_html__( 'Front Page', 'okthemes-toolkit' ),
                            'post_page'         => esc_html__( 'Post Page', 'okthemes-toolkit' ),
                            'post_details'      => esc_html__( 'Post Details', 'okthemes-toolkit' ),
                            'all_archive'       => esc_html__( 'All Archive', 'okthemes-toolkit' ),
                            'date_archive'      => esc_html__( 'Date Archive', 'okthemes-toolkit' ),
                            'author_archive'    => esc_html__( 'Author Archive', 'okthemes-toolkit' ),
                            'search_page'       => esc_html__( 'Search Page', 'okthemes-toolkit' ),
                            '404_page'          => esc_html__( '404 Page', 'okthemes-toolkit' ),
                            'specific_pages'    => esc_html__( 'Specific Pages', 'okthemes-toolkit' ),
                            'specific_posts'    => esc_html__( 'Specific Posts', 'okthemes-toolkit' ),
                            'shop_page'         => esc_html__( 'Shop Page', 'okthemes-toolkit' ),
                            'product_details'   => esc_html__( 'Product Details', 'okthemes-toolkit' ),
                            'specific_products' => esc_html__( 'Specific Products', 'okthemes-toolkit' ),
                        ],
                    ],
                    [
                        'id'          => 'page_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Pages', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Pages', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'pages',
                        'dependency'  => ['rule', '==', 'specific_pages'],
                    ],
                    [
                        'id'          => 'posts_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Posts', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Posts', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'posts',
                        'dependency'  => ['rule', '==', 'specific_posts'],
                    ],
                    [
                        'id'          => 'product_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Products', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Products', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'post',
                        'query_args'  => [
                            'post_type' => 'product',
                        ],
                        'dependency'  => ['rule', '==', 'specific_products'],
                    ],
                ],
            ],
            [
                'id'           => 'okthemes_tb_exclude',
                'type'         => 'repeater',
                'title'        => esc_html__( 'Hide On', 'okthemes-toolkit' ),
                'subtitle'     => esc_html__( 'Select the locations where this item should be visible.', 'okthemes-toolkit' ),
                'button_title' => esc_html__( 'Add Hide Rule', 'okthemes-toolkit' ),
                'dependency'   => ['template_type', 'any', 'header,footer,popup'],
                'fields'       => [
                    [
                        'type'    => 'subheading',
                        'content' => esc_html__( 'Hide Rule', 'okthemes-toolkit' ),
                    ],
                    [
                        'id'      => 'rule',
                        'type'    => 'select',
                        'title'   => esc_html__( 'Hide on', 'okthemes-toolkit' ),
                        'options' => [
                            'entire_website'    => esc_html__( 'Entire Website', 'okthemes-toolkit' ),
                            'all_pages'         => esc_html__( 'All Pages', 'okthemes-toolkit' ),
                            'front_page'        => esc_html__( 'Front Page', 'okthemes-toolkit' ),
                            'post_page'         => esc_html__( 'Post Page', 'okthemes-toolkit' ),
                            'post_details'      => esc_html__( 'Post Details', 'okthemes-toolkit' ),
                            'all_archive'       => esc_html__( 'All Archive', 'okthemes-toolkit' ),
                            'date_archive'      => esc_html__( 'Date Archive', 'okthemes-toolkit' ),
                            'author_archive'    => esc_html__( 'Author Archive', 'okthemes-toolkit' ),
                            'search_page'       => esc_html__( 'Search Page', 'okthemes-toolkit' ),
                            '404_page'          => esc_html__( '404 Page', 'okthemes-toolkit' ),
                            'specific_pages'    => esc_html__( 'Specific Pages', 'okthemes-toolkit' ),
                            'specific_posts'    => esc_html__( 'Specific Posts', 'okthemes-toolkit' ),
                            'shop_page'         => esc_html__( 'Shop Page', 'okthemes-toolkit' ),
                            'product_details'   => esc_html__( 'Product Details', 'okthemes-toolkit' ),
                            'specific_products' => esc_html__( 'Specific Products', 'okthemes-toolkit' ),
                        ],
                    ],
                    [
                        'id'          => 'page_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Pages', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Pages', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'pages',
                        'dependency'  => ['rule', '==', 'specific_pages'],
                    ],
                    [
                        'id'          => 'posts_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Posts', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Posts', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'posts',
                        'dependency'  => ['rule', '==', 'specific_posts'],
                    ],
                    [
                        'id'          => 'product_ids',
                        'type'        => 'select',
                        'title'       => esc_html__( 'Select Products', 'okthemes-toolkit' ),
                        'placeholder' => esc_html__( 'Select Products', 'okthemes-toolkit' ),
                        'chosen'      => true,
                        'ajax'        => true,
                        'multiple'    => true,
                        'sortable'    => true,
                        'options'     => 'post',
                        'query_args'  => [
                            'post_type' => 'product',
                        ],
                        'dependency'  => ['rule', '==', 'specific_products'],
                    ],
                ],
            ],
        ];
    }
}

Metaboxes::instance();
