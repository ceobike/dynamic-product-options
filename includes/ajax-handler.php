<?php
function wcdpo_add_to_cart() {
    // Check if nonce is valid
    check_ajax_referer('wcdpo_nonce', 'security');

    $product_id = intval($_POST['product_id']);
    $product_option = sanitize_text_field($_POST['product_option']);
    $quantity = intval($_POST['quantity']);
    $discount = intval($_POST['discount']);

    if ($product_id && $quantity) {
        $product = wc_get_product($product_id);
        if ($product) {
            // Add coupon if necessary
            if ($discount) {
                if ($discount == 10) {
                    WC()->cart->add_discount('BUY2SAVE10'); // Use WooCommerce coupon code
                } elseif ($discount == 15) {
                    WC()->cart->add_discount('BUY3SAVE15'); // Use WooCommerce coupon code
                }
            }

            $cart_item_data = array(
                'product_option' => $product_option
            );

            $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);
            if ($cart_item_key) {
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
add_action('wp_ajax_wcdpo_add_to_cart', 'wcdpo_add_to_cart');
add_action('wp_ajax_nopriv_wcdpo_add_to_cart', 'wcdpo_add_to_cart');
