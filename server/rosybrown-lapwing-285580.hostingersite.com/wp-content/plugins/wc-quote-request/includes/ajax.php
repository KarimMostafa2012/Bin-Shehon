<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_wcqr_submit_quote', 'wcqr_submit_quote');
add_action('wp_ajax_nopriv_wcqr_submit_quote', 'wcqr_submit_quote');

function wcqr_submit_quote()
{
    check_ajax_referer('wcqr_nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity   = isset($_POST['quantity']) ? max(1, absint($_POST['quantity'])) : 1;

    $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
    $notes   = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

    // Validate required fields
    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($address)
    ) {
        wp_send_json_error([
            'message' => 'Please fill in all required fields.'
        ]);
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error([
            'message' => 'Product not found.'
        ]);
    }

    try {

        $order = wc_create_order();

        // Add product
        $order->add_product($product, $quantity);

        // Customer Details
        $order->set_billing_first_name($name);
        $order->set_billing_email($email);
        $order->set_billing_phone($phone);
        $order->set_billing_address_1($address);

        // Customer note
        $order->set_customer_note($notes);

        // Custom Metadata
        $order->update_meta_data('_quote_request', 'yes');
        $order->update_meta_data('_quote_product_name', $product->get_name());
        $order->update_meta_data('_quote_product_price', $product->get_price());

        // Internal note
        $order->add_order_note('Quote request submitted from website.');

        // Calculate totals
        $order->calculate_totals();

        // Save
        $order->save();

        do_action('wcqr_quote_created', $order->get_id(), [
            'product'  => $product,
            'quantity' => $quantity,
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'notes'    => $notes,
        ]);

        wp_send_json_success([
            'message'  => 'Quote submitted successfully!',
            'order_id' => $order->get_id()
        ]);

    } catch (Exception $e) {

        wp_send_json_error([
            'message' => $e->getMessage()
        ]);

    }
}
