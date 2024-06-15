<?php
if (!defined('ABSPATH')) {
    exit;
}

function dpo_add_to_cart_ajax_handler() {
    check_ajax_referer('dpo_nonce', 'nonce');

    $product_id = intval($_POST['product_id']);
    $product_option = sanitize_text_field($_POST['product_option']);
    $quantity = intval($_POST['quantity']);
    $discount = intval($_POST['discount']);

    if ($product_id && $quantity) {
        $product = wc_get_product($product_id);
        if ($product) {
            // Use WooCommerce coupons from the backend
            $coupon_code = '';
            if ($quantity == 2) {
                $coupon_code = 'YOUR_2_PRODUCT_COUPON_CODE'; // Replace with your actual coupon code
            } elseif ($quantity == 3) {
                $coupon_code = 'YOUR_3_PRODUCT_COUPON_CODE'; // Replace with your actual coupon code
            }
            
            $cart_item_data = array(
                'product_option' => $product_option
            );

            $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);
            if ($cart_item_key) {
                if ($coupon_code) {
                    WC()->cart->apply_coupon($coupon_code);
                }
                wp_send_json_success();
            } else {
                wp_send_json_error('Error adding to cart');
            }
        } else {
            wp_send_json_error('Invalid product');
        }
    } else {
        wp_send_json_error('Invalid data');
    }

    wp_die();
}
add_action('wp_ajax_add_to_cart', 'dpo_add_to_cart_ajax_handler');
add_action('wp_ajax_nopriv_add_to_cart', 'dpo_add_to_cart_ajax_handler');
