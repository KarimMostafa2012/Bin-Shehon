<?php
/**
 * Plugin Name: OKThemes Toolkit
 * Plugin URI: https://okthemes.com
 * Description: Master plugin for premium functionality across all OKThemes WordPress themes.
 * Version: 1.7.5
 * Author: OKThemes
 * Author URI: http://okthemes.com/
 * Text Domain: okthemes-toolkit
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined('ABSPATH') ) exit;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Autoload
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Plugin update checker
if (file_exists(__DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php')) {
    require_once __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
}

final class OKThemes_Toolkit {
    const VERSION = '1.7.5';
    const MIN_PHP_VERSION = '7.4';

    private $supported_themes = ['Torac', 'Orologio', 'Vinart', 'Juliette', 'Villenoir'];
    private $update_checker;
    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        define('OKT_VERSION', self::VERSION);
        define('OKT_FILE', __FILE__);
        define('OKT_PATH', plugin_dir_path(__FILE__));
        define('OKT_URL', plugin_dir_url(__FILE__));
        define('OKT_INC', OKT_PATH . 'includes/');
        define('OKT_ASSETS', untrailingslashit( OKT_URL . 'assets' ) );
        define('OKT_INCLUDES', untrailingslashit( OKT_PATH . 'includes' ) );

        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    public function init_plugin() {
        load_plugin_textdomain('okthemes-toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages');

        if ( ! $this->is_compatible() ) {
            return;
        }

        // Delay the update checker to 'init' so WooCommerce and other plugins
        // have loaded their textdomains before wp_get_schedules() is triggered.
        add_action('init', [$this, 'init_updater']);
        $this->load_dependencies();
        $this->init_modules();
    }

    private function is_compatible(): bool {
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_min_php']);
            return false;
        }
        if ( ! in_array(ucfirst(strtolower(get_template())), $this->supported_themes, true) ) {
            add_action('admin_notices', [$this, 'admin_notice_wrong_theme']);
            return false;
        }
        return true;
    }

    public function admin_notice_min_php() {
        echo '<div class="notice notice-warning"><p>' . esc_html__('OKThemes Toolkit requires PHP 7.4 or higher.', 'okthemes-toolkit') . '</p></div>';
    }

    public function admin_notice_wrong_theme() {
        echo '<div class="notice notice-warning"><p>' . esc_html__('OKThemes Toolkit requires an OKThemes theme to be active.', 'okthemes-toolkit') . '</p></div>';
    }

    public function init_updater() {
        $metadata_url = apply_filters(
            'okthemes_toolkit_update_metadata_url',
            'https://api.okthemes.com/toolkit/okthemes-toolkit.json'
        );
        $this->update_checker = PucFactory::buildUpdateChecker(
            $metadata_url,
            __FILE__,
            'okthemes-toolkit'
        );
    }

    private function load_dependencies() {
        if (!class_exists('CSF')) {
            require_once OKT_INC . 'Library/CodestarFramework/codestar-framework.php';
        }

        $theme = ucfirst(strtolower(get_template()));
        $theme_options = OKT_INC . "ThemeOptions/{$theme}.php";
        $theme_metaboxes = OKT_INC . "Metaboxes/{$theme}.php";
        

        // Load theme-specific options if they exist
        if (file_exists($theme_options)) {
            require_once $theme_options;
            $theme_class = '\\OKThemes\\Toolkit\\ThemeOptions\\' . $theme;
            add_action('after_setup_theme', function () use ($theme_class) {
                $theme_class::instance();
            });
        }

        // Load theme-specific metaboxes if they exist
        if (file_exists($theme_metaboxes)) {
            require_once $theme_metaboxes;
            $theme_class = '\\OKThemes\\Toolkit\\Metaboxes\\' . $theme;
            add_action('after_setup_theme', function () use ($theme_class) {
                $theme_class::instance();
            });
        }
    }

    private function init_modules() {
        // Load helper functions
        \OKThemes\Toolkit\Functions\Helpers::init();

        // Elementor-based modules
        if ( did_action( 'elementor/loaded' ) ) {
            $this->init_elementor_modules();
        } else {
            add_action( 'elementor/loaded', [ $this, 'init_elementor_modules' ] );
        }

        // Gutenberg-based modules
        new \OKThemes\Toolkit\Gutenberg\Gutenberg();

    }

    /**
     * Initialize Elementor related modules.
     */
    public function init_elementor_modules() {
        \OKThemes\Toolkit\Elementor\Manager::init();
        \OKThemes\Toolkit\TemplateBuilder\TemplateBuilder::instance();
    }

}

OKThemes_Toolkit::instance();

register_activation_hook(__FILE__, function () {
    update_option('okthemes_tb_flush_rewrite', 'yes');
});

register_deactivation_hook(__FILE__, function () {
    delete_option('okthemes_tb_flush_rewrite');
});
