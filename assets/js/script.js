jQuery(document).ready(function($) {
    $('#add-to-cart-button').on('click', function() {
        var productId = $('.product-options').data('product-id');
        var productOption = $('input[name="product_option"]:checked').val();
        var quantity = $('input[name="quantity"]:checked').val();
        var discount = $('input[name="quantity"]:checked').data('discount');

        var data = {
            action: 'wcdpo_add_to_cart',
            security: wcdpo_ajax.nonce,
            product_id: productId,
            product_option: productOption,
            quantity: quantity,
            discount: discount
        };

        $.ajax({
            url: wcdpo_ajax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    console.log('Product added to cart');
                } else {
                    console.log('Error adding to cart');
                }
            }
        });

        // Shake the button
        $(this).addClass('shake');
        setTimeout(() => {
            $(this).removeClass('shake');
        }, 1000);
    });
});
