<?php
require_once get_template_directory() . '/lib/class-tgm-plugin-activation.php';

class Orologio_Plugins {
    public function __construct() {
        add_action('tgmpa_register', [$this, 'register_plugins']);
    }

    public function register_plugins() {
        $plugins = [
            [
                'name'     => 'OKThemes Toolkit',
                'slug'     => 'okthemes-toolkit',
                'source'   => 'https://api.okthemes.com/toolkit/okthemes-toolkit.zip',
                'required' => true,
                'version'  => '1.7.1'
            ],
            [
                'name'     => 'Elementor Website Builder',
                'slug'     => 'elementor',
                'required' => true
            ],
            [
                'name'     => 'One Click Demo Import',
                'slug'     => 'one-click-demo-import',
                'required' => false
            ],
            [
                'name'     => 'WooCommerce',
                'slug'     => 'woocommerce',
                'required' => false
            ],
            [
                'name'     => 'Contact Form 7',
                'slug'     => 'contact-form-7',
                'required' => false
            ],
            [
                'name'     => 'MC4WP: Mailchimp for WordPress',
                'slug'     => 'mailchimp-for-wp',
                'required' => false
            ]
        ];

        $config = [
            'id'           => 'orologio',
            'menu'         => 'tgmpa-install-plugins',
            'has_notices'  => true,
            'dismissable'  => true,
            'is_automatic' => false
        ];

        tgmpa($plugins, $config);
    }

}