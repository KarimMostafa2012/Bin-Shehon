<?php
namespace OKThemes\Toolkit\TemplateBuilder;

defined('ABSPATH') || exit;

use OKThemes\Toolkit\TemplateBuilder\Admin;
use OKThemes\Toolkit\TemplateBuilder\CPT;
use OKThemes\Toolkit\TemplateBuilder\Frontend;
use OKThemes\Toolkit\TemplateBuilder\Metaboxes;
use OKThemes\Toolkit\TemplateBuilder\Rule;
use OKThemes\Toolkit\TemplateBuilder\Core\CSS_Optimizer;
use OKThemes\Toolkit\TemplateBuilder\Core\Font_Manager;

/**
 * Main TemplateBuilder loader
 */
class TemplateBuilder {

    /**
     * Instance
     */
    private static $instance = null;

    /**
     * Get instance (singleton)
     */
    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_core();
        $this->init_components();
    }

    /**
     * Load core optimizers
     */
    private function init_core() {
        Font_Manager::instance();
        CSS_Optimizer::instance();
    }

    /**
     * Load template builder components
     */
    private function init_components() {
        CPT::instance();
        Rule::instance();
        Admin::instance();
        Frontend::instance();
        add_action( 'after_setup_theme', function() {
            Metaboxes::instance();
        });
    }
}