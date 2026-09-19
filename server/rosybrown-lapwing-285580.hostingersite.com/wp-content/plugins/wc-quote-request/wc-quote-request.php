<?php
/*
Plugin Name: WooCommerce Order Request
Description: Replace WooCommerce purchases with order requests.
Version: 1.1.3
Author: Karim
*/

if (!defined('ABSPATH')) {
    exit;
}

define('WCQR_PATH', plugin_dir_path(__FILE__));
define('WCQR_URL', plugin_dir_url(__FILE__));
define('WCQR_VERSION', '1.1.3');

add_action('plugins_loaded', function () {

    if (!class_exists('WooCommerce')) {
        return;
    }
    require_once WCQR_PATH . 'includes/ajax.php';
    require_once WCQR_PATH . 'includes/enqueue.php';
    require_once WCQR_PATH . 'includes/popup.php';
    require_once WCQR_PATH . 'includes/button.php';

});
