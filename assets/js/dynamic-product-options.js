jQuery(document).ready(function($) {
    $('#add-to-cart-button').on('click', function() {
        var productId = $('.product-options').data('product-id');
        var productOption = $('input[name="product_option"]:checked').val();
        var quantity = $('input[name="quantity"]:checked').val();
        var discount = $('input[name="quantity"]:checked').data('discount');
        var nonce = dpo_ajax_params.nonce;

        var data = {
            action: 'add_to_cart',
            product_id: productId,
            product_option: productOption,
            quantity: quantity,
            discount: discount,
            nonce: nonce
        };

        $.ajax({
            url: dpo_ajax_params.ajax_url,
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
