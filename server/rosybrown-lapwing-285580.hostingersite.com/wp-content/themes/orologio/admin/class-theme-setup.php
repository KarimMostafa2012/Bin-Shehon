<?php
class Orologio_Setup {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_setup_page']);
        add_action('admin_init', [$this, 'redirect_after_activation']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        require_once get_template_directory() . '/admin/class-license-helper.php';
        require_once get_template_directory() . '/admin/class-theme-license.php';
        require_once get_template_directory() . '/admin/class-theme-plugins.php';
        require_once get_template_directory() . '/admin/class-theme-demo.php';

        new Orologio_License();
        new Orologio_Plugins();
        new Orologio_Demo();
    }

    public function add_setup_page() {
        add_theme_page(
            __('Theme Setup', 'orologio'),
            __('Theme Setup', 'orologio'),
            'manage_options',
            'theme-setup',
            [$this, 'render_setup_page']
        );
    }

    public function redirect_after_activation() {
        if (is_admin() && isset($_GET['activated']) && $GLOBALS['pagenow'] === 'themes.php') {
            wp_safe_redirect(admin_url('admin.php?page=theme-setup'));
            exit;
        }
    }

    public function enqueue_assets($hook) {
        if ($hook === 'appearance_page_theme-setup') {
            wp_enqueue_style('orologio-admin-css', get_template_directory_uri() . '/admin/assets/admin-style.css');
            wp_enqueue_script('orologio-admin-js', get_template_directory_uri() . '/admin/assets/admin-scripts.js', ['jquery'], null, true);
        }
    }

    public function render_setup_page() {
        include get_template_directory() . '/admin/views/page-setup.php';
    }
}

new Orologio_Setup();