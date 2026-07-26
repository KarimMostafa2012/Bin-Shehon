<?php
    add_action('template_redirect', function () {
        if (is_cart() || is_checkout()) {
            wp_redirect(home_url('/shop'));
            exit;
        }
    });
    
?>Ï