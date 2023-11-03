jQuery(document).ready(function($) {
    $('body').on('click', '.product-link', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var targetUrl = $(this).attr('href');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'record_product_click',
                product_id: productId,
            },
            success: function(response) {
                window.location.href = targetUrl;
            }
        });
    });
});
