jQuery(function ($) {

    $(document).ready(function() {
        
        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
        }

        setTimeout(function() {
            
            var itemsInCart = getCookie("woocommerce_items_in_cart");
        
            
            if (itemsInCart && parseInt(itemsInCart) > 0) {
                console.log("ОК");
                
                var productElements = $('.wc-block-cart-item__product');
        
                
                productElements.each(function(index) {
                    
                    if (index === 0) {
                        var newElement = $('<div class="container_select"><select id="slect_product" name="select"> </select> <br> <button id="add_product">Read More</button></div>');
        
                        
                        $(this).after(newElement);
        
                        $.ajax({
                            url: testwoo_url_ajax.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'testwoo_action',
                            },
                            success: function (res) {
                                $('#slect_product').html(res);
                            }
                        });
                    }
                });
            }
        }, 300);        
    });
    
    $(document).on('click', '#add_product', function(e) {
        var customValue = $('#slect_product option:selected').data('custom-value');
        console.log(customValue);

        $.ajax({
            url: testwoo_url_ajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'add_product',
                id: customValue
            },
            success: function (res) {
                location.reload();
            }
        });
    });

});

