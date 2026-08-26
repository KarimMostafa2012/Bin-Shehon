<?php

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style(
        'wcqr-style',
        WCQR_URL . 'assets/quote.css',
        [],
        WCQR_VERSION
    );

    wp_enqueue_script(
        'wcqr-script',
        WCQR_URL . 'assets/quote.js',
        [],
        WCQR_VERSION,
        true
    );

    wp_localize_script('wcqr-script', 'wcqr', [
        'ajax' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wcqr_nonce')
    ]);
});
