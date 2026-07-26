<?php

class OKThemes_License_Helper {
    protected $expected_item_id;

    public function __construct($expected_item_id) {
        $this->expected_item_id = $expected_item_id;
    }

    /**
     * Verify the license remotely using OKThemes API.
     */
    public function verify($purchase_code, $email) {
        $response = wp_remote_post('https://api.okthemes.com/wp-json/api/v1/verification', [
            'timeout' => 15,
            'body' => [
                'purchase_code' => $purchase_code,
                'email'         => $email,
                'site_url'      => home_url(),
            ]
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'Connection error',
            ];
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (!empty($data['success']) && isset($data['data']['item_id']) && $data['data']['item_id'] == $this->expected_item_id) {
            return [
                'success' => true,
                'item_name' => $data['data']['item_name'] ?? '',
                'buyer' => $data['data']['buyer'] ?? '',
                'message' => $data['message'] ?? 'Verified',
            ];
        }

        return [
            'success' => false,
            'message' => $data['message'] ?? 'Invalid license or mismatched theme.',
        ];
    }
}
