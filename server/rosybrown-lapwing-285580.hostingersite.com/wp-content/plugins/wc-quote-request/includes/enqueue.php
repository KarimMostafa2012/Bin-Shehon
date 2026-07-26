<?php

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style(
        'wcqr-style',
        WCQR_URL . 'assets/quote.css',
        [],
        '1.1.1'
    );

    wp_enqueue_script(
        'wcqr-script',
        WCQR_URL . 'assets/quote.js',
        [],
        '1.1.1',
        true
    );

    wp_localize_script('wcqr-script', 'wcqr', [
        'ajax' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wcqr_nonce')
    ]);
});
