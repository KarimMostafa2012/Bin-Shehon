<?php

class Orologio_License {
    protected $helper;

    public function __construct() {
        $this->helper = new OKThemes_License_Helper('57880093'); // Orologio theme item ID
        add_action('admin_post_theme_activate_license', [$this, 'process_license_form']);
        add_action('admin_post_theme_deactivate_license', [$this, 'deactivate_license']);
        add_action('admin_post_theme_reset_license', [$this, 'reset_license']);
    }

    public function process_license_form() {
        check_admin_referer('theme_license_activate');

        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized request.', 'orologio'));
        }

        $source = sanitize_text_field($_POST['theme_source'] ?? 'market');
        $email  = sanitize_email($_POST['email'] ?? '');
        $code   = sanitize_text_field($_POST['purchase_code'] ?? '');

        if (empty($email)) {
            wp_redirect(admin_url('admin.php?page=theme-setup&license_error=missing_email'));
            exit;
        }

        // Theme metadata
        $theme      = wp_get_theme();
        if (is_child_theme()) {
            $theme = wp_get_theme($theme->get('Template'));
        }

        $theme_slug = sanitize_title($theme->get('Name'));
        $theme_name = $theme->get('Name');

        // Save common data locally
        update_option('orologio_license_email', $email);
        update_option('orologio_license_source', $source);

        // Prepare API request
        $api_body = [
            'email'        => $email,
            'site_url'     => home_url(),
            'theme_source' => $source,
            'theme_slug'   => $theme_slug,
            'theme_name'   => $theme_name,
        ];

        if ($source === 'market') {
            if (empty($code)) {
                wp_redirect(admin_url('admin.php?page=theme-setup&license_error=missing_code'));
                exit;
            }

            $api_body['purchase_code'] = $code;
        }

        $response = wp_remote_post('https://api.okthemes.com/wp-json/api/v1/verification', [
            'timeout' => 15,
            'body'    => $api_body,
        ]);

        if (is_wp_error($response)) {
            wp_redirect(admin_url('admin.php?page=theme-setup&license_error=connection_error'));
            exit;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (!empty($data['success'])) {
            update_option('orologio_license_status', 'active');
            if (!empty($code)) {
                update_option('orologio_license_code', $code);
            }
            wp_redirect(admin_url('admin.php?page=theme-setup&license=success'));
        } else {
            $error_message = $data['message'] ?? 'invalid_response';
            wp_redirect(admin_url('admin.php?page=theme-setup&license_error=' . urlencode($error_message)));
        }

        exit;
    }

    public function deactivate_license() {
        check_admin_referer('theme_license_deactivate');

        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized request.', 'orologio'));
        }

        $email = get_option('orologio_license_email');
        $code  = get_option('orologio_license_code');

        if ($code && $email) {
            wp_remote_post('https://api.okthemes.com/wp-json/api/v1/deactivate', [
                'timeout' => 15,
                'body' => [
                    'purchase_code' => $code,
                ]
            ]);
        }

        delete_option('orologio_license_status');
        delete_option('orologio_license_code');

        wp_redirect(admin_url('admin.php?page=theme-setup&license=deactivated'));
        exit;
    }

    public function reset_license() {
        check_admin_referer('theme_license_reset');

        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized request.', 'orologio'));
        }

        $email = get_option('orologio_license_email');
        $code  = get_option('orologio_license_code');

        if ($code && $email) {
            wp_remote_post('https://api.okthemes.com/wp-json/api/v1/reset_activation', [
                'timeout' => 15,
                'body' => [
                    'purchase_code' => $code,
                ]
            ]);
        }

        delete_option('orologio_license_status');
        delete_option('orologio_license_code');
        delete_option('orologio_license_email');
        delete_option('orologio_license_source');

        wp_redirect(admin_url('admin.php?page=theme-setup&license=reset'));
        exit;
    }
}
