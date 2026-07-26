<?php
/*
Plugin Name: WooCommerce Quote Request
Description: Replace WooCommerce purchases with quote requests.
Version: 1.0.0
Author: Karim
*/

if (!defined('ABSPATH')) {
    exit;
}

define('WCQR_PATH', plugin_dir_path(__FILE__));
define('WCQR_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', function () {

    if (!class_exists('WooCommerce')) {
        return;
    }
    require_once WCQR_PATH . 'includes/ajax.php';
    require_once WCQR_PATH . 'includes/enqueue.php';
    require_once WCQR_PATH . 'includes/popup.php';
    require_once WCQR_PATH . 'includes/button.php';

});