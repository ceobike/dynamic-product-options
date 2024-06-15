<?php
/**
 * Plugin Name: Dynamic Product Options
 * Description: Adds dynamic product options and quantity discounts with AJAX add-to-cart functionality.[dynamic_product_options product_id="123"]
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: dynamic-product-options
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path
define('DPO_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include AJAX handler
require_once DPO_PLUGIN_PATH . 'includes/ajax-handler.php';

// Enqueue scripts and styles
function dpo_enqueue_scripts() {
    wp_enqueue_style('dpo-styles', plugins_url('assets/css/dynamic-product-options.css', __FILE__));
    wp_enqueue_script('dpo-scripts', plugins_url('assets/js/dynamic-product-options.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('dpo-scripts', 'dpo_ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dpo_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'dpo_enqueue_scripts');

// Shortcode to display dynamic product options
function dpo_dynamic_product_options_shortcode($atts) {
    $atts = shortcode_atts(array(
        'product_id' => 0
    ), $atts, 'dynamic_product_options');

    if (!$atts['product_id']) return 'Product ID is required.';

    $product = wc_get_product($atts['product_id']);
    if (!$product) return 'Invalid product ID.';

    ob_start();
    ?>
    <div class="product-options" data-product-id="<?php echo $atts['product_id']; ?>">
        <div class="product-form__item">
            <label for="product-option">Options:</label>
            <fieldset id="product-option">
                <?php foreach ($product->get_attributes() as $attribute) : ?>
                    <?php if ($attribute->get_variation()) : ?>
                        <?php foreach ($attribute->get_options() as $option) : ?>
                            <input type="radio" name="product_option" value="<?php echo esc_attr($option); ?>" data-attribute="<?php echo esc_attr($attribute->get_name()); ?>"> <?php echo esc_html($option); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </fieldset>
        </div>
        <div class="product-single__quantity">
            <label for="product-quantity">Quantity:</label>
            <div class="quantity-options">
                <div class="quantity-option">
                    <input type="radio" name="quantity" value="1" checked> Buy 1: <span class="price"><?php echo wc_price($product->get_price()); ?></span>
                </div>
                <div class="quantity-option">
                    <input type="radio" name="quantity" value="2" data-discount="10"> Buy 2: <span class="price"><?php echo wc_price($product->get_price() * 2 * 0.9); ?></span> <span class="original-price"><?php echo wc_price($product->get_price() * 2); ?></span> <span class="discount">Save 10%</span>
                </div>
                <div class="quantity-option">
                    <input type="radio" name="quantity" value="3" data-discount="15"> Buy 3: <span class="price"><?php echo wc_price($product->get_price() * 3 * 0.85); ?></span> <span class="original-price"><?php echo wc_price($product->get_price() * 3); ?></span> <span class="discount">Save 15%</span>
                </div>
            </div>
        </div>
        <div class="product-single__add-to-cart">
            <button type="button" id="add-to-cart-button" class="btn btn--primary">Add to Cart</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('dynamic_product_options', 'dpo_dynamic_product_options_shortcode');
