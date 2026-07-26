<?php
/**
 * OKThemes Toolkit Gutenberg Class
 *
 * @package OKThemes\Toolkit\Gutenberg
 */
// Gutenberg Integration Module
namespace OKThemes\Toolkit\Gutenberg;

class Gutenberg {
    public function __construct() {
        add_action('after_setup_theme', [$this, 'add_editor_support']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    public function add_editor_support() {
        add_theme_support('editor-color-palette', [
            [
                'name'  => __('Primary', 'okthemes-toolkit'),
                'slug'  => 'primary',
                'color' => '#cc3333',
            ],
            [
                'name'  => __('Secondary', 'okthemes-toolkit'),
                'slug'  => 'secondary',
                'color' => '#222222',
            ],
        ]);

        add_theme_support('editor-font-sizes', [
            [
                'name' => __('Small', 'okthemes-toolkit'),
                'size' => 14,
                'slug' => 'small'
            ],
            [
                'name' => __('Normal', 'okthemes-toolkit'),
                'size' => 16,
                'slug' => 'normal'
            ],
            [
                'name' => __('Large', 'okthemes-toolkit'),
                'size' => 36,
                'slug' => 'large'
            ]
        ]);

        add_theme_support('align-wide');
    }

    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'okthemes-editor-style',
            OKT_URL . 'assets/css/editor-style.css',
            [],
            OKT_VERSION
        );
    }
}
