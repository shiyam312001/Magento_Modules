
    require(['jquery'], function($) {
        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();

            var productId = $(this).data('product-id');
            var qty = 1; // You can adjust this if you want to add more than one item
            var formKey = $('input[name="form_key"]').val(); // Get the form key value

            console.log("Product ID:", productId);
            console.log("Form Key:", formKey);
            $.ajax({
                url: '/home/index/AddToCart',
                showLoader:true,
                method: 'POST',
                dataType: 'json',
                data: {
                    product_id: productId, // Changed to product_id to match controller logic
                    form_key: formKey,
                    qty: qty
                },
                success: function(response) {
                    console.log("AJAX Response:", response);
                    if (response.success) {
                        console.log(response);
                        alert(response.message); // Assuming message field contains the success message
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error("AJAX Error:", error);
                }
            });
        });
    });

