<?php
/*
Plugin Name: Dynamic Product Options
Description: Adds dynamic product options and quantity discounts to WooCommerce products.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handler.php';

// Register shortcode
function dynamic_product_options_shortcode($atts) {
    $atts = shortcode_atts(array('product_id' => 0), $atts, 'dynamic_product_options');
    if (!$atts['product_id']) return 'Product ID is required.';
    $product = wc_get_product($atts['product_id']);
    if (!$product) return 'Invalid product ID.';

    ob_start();
    ?>
    <div class="product-options" data-product-id="<?php echo $atts['product_id']; ?>">
        <div class="product-form__item">
            <label for="product-option">Options:</label>
            <fieldset id="product-option">
                <?php
                $attributes = $product->get_attributes();
                foreach ($attributes as $attribute) :
                    if ($attribute->get_variation()) :
                        foreach ($attribute->get_options() as $option) :
                            ?>
                            <input type="radio" name="product_option" value="<?php echo esc_attr($option); ?>" data-attribute="<?php echo esc_attr($attribute->get_name()); ?>"> <?php echo esc_html($option); ?>
                        <?php
                        endforeach;
                    endif;
                endforeach;
                ?>
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
add_shortcode('dynamic_product_options', 'dynamic_product_options_shortcode');
