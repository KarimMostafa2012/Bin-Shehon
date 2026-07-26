<?php
class Orologio_Demo {
    public function __construct() {
        add_filter('ocdi/import_files', [$this, 'import_files']);
        add_action('ocdi/after_import', [$this, 'after_import']);

        add_filter('ocdi/disable_pt_branding', '__return_true');
        add_filter('ocdi/regenerate_thumbnails_in_content_import', '__return_false');
        add_filter('ocdi/time_for_one_ajax_call', fn() => 10);

        add_action('pt-ocdi/after_content_import_execution', [$this, 'import_codestar_options'], 3, 99);
    }

    public function import_files() {
        return [
            [
                'import_file_name'             => 'Orologio Demo Import',
                'categories'                   => ['Default'],
                'local_import_file'            => get_template_directory() . '/admin/importer/demo-files/demo-content.xml',
                'local_import_customizer_file' => get_template_directory() . '/admin/importer/demo-files/customizer.dat',
                'local_import_cs'              => [
                    [
                        'file_path'   => get_template_directory() . '/admin/importer/demo-files/theme-options.json',
                        'option_name' => 'orologio_options',
                    ]
                ]
            ]
        ];
    }

    public function after_import() {
        // Menus
        $locations = [];
        $main_menu = get_term_by('name', 'Main Menu', 'nav_menu');
        $footer_menu = get_term_by('name', 'Footer Menu', 'nav_menu');

        if ($main_menu) $locations['main-menu'] = $main_menu->term_id;
        if ($footer_menu) $locations['footer-menu'] = $footer_menu->term_id;
        if (!empty($locations)) set_theme_mod('nav_menu_locations', $locations);

        // Pages
        $home = $this->get_page_by_title_or_slug('Homepage');
        $blog = $this->get_page_by_title_or_slug('News');
        if ($home) update_option('page_on_front', $home->ID);
        if ($blog) update_option('page_for_posts', $blog->ID);
        if ($home || $blog) update_option('show_on_front', 'page');

        // Mailchimp
        if (post_type_exists('mc4wp-form')) {
            $form = $this->get_custom_post_by_title('Newsletter', 'mc4wp-form');
            if ($form) update_option('mc4wp_default_form_id', $form->ID);
        }

        // WooCommerce
        if (class_exists('WooCommerce')) {
            $shop = $this->get_page_by_slug('shop');
            if ($shop) update_option('woocommerce_shop_page_id', $shop->ID);
        }

        // Elementor - Enable Products post type support
        $this->enable_elementor_product_support();

        // Permalinks
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        flush_rewrite_rules();
    }

    /**
     * Enable Elementor support for WooCommerce products
     */
    private function enable_elementor_product_support() {
        // Check if both WooCommerce and Elementor are active
        if (!class_exists('WooCommerce') || !class_exists('\Elementor\Plugin')) {
            return;
        }

        // Enable product post type in Elementor
        $cpt_support = get_option('elementor_cpt_support', ['page', 'post']);
        if (!in_array('product', $cpt_support)) {
            $cpt_support[] = 'product';
            update_option('elementor_cpt_support', $cpt_support);
        }
    }

    public function import_codestar_options($selected_import, $all_imports, $selected_index) {
        if (!class_exists('CSFramework')) return;
        if (empty($all_imports[$selected_index]['local_import_cs'])) return;

        foreach ($all_imports[$selected_index]['local_import_cs'] as $import) {
            if (!empty($import['file_path']) && !empty($import['option_name'])) {
                $raw = $this->get_file_contents($import['file_path']);
                $decoded = @unserialize($raw);
                if (is_array($decoded)) {
                    update_option($import['option_name'], $decoded);
                }
            }
        }

        // Log import success
        if (class_exists('OCDI\OneClickDemoImport')) {
            $ocdi = OCDI\OneClickDemoImport::get_instance();
            $log_path = $ocdi->get_log_file_path();
            \OCDI\Helpers::append_to_file('Codestar options imported.', $log_path);
        }
    }

    private function get_file_contents($path) {
        return is_readable($path) ? file_get_contents($path) : '';
    }

    protected function get_page_by_title_or_slug($title) {
        $page = get_page_by_title($title);
        if ($page) return $page;
        return $this->get_page_by_slug(sanitize_title($title));
    }

    protected function get_page_by_slug($slug) {
        global $wpdb;
        $page_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND post_status = 'publish' LIMIT 1",
            $slug, 'page'
        ));
        return $page_id ? get_post($page_id) : null;
    }

    protected function get_custom_post_by_title($title, $post_type = 'page') {
        global $wpdb;
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = %s AND post_status = 'publish' LIMIT 1",
            $title, $post_type
        ));
        return $post_id ? get_post($post_id) : null;
    }
}
