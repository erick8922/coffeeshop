$(document).ready(function() {

    // ═══════════════════════════════════
    //  DATATABLES - Admin Tables
    // ═══════════════════════════════════
    if ($('#productsTable').length) {
        $('#productsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']],
        });
    }

    if ($('#ordersTable').length) {
        $('#ordersTable').DataTable({
            responsive: true,
            pageLength: 15,
            order: [[0, 'desc']],
        });
    }

    // ═══════════════════════════════════
    //  AJAX - Add to Cart
    // ═══════════════════════════════════
    $(document).on('submit', '#addToCartForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    // Update cart count sa navbar
                    $('#cartCount').text(response.cart_count);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response.message ?? 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  AJAX - Remove Cart Item
    // ═══════════════════════════════════
    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();

        const btn  = $(this);
        const url  = btn.data('url');
        const token = $('meta[name="csrf-token"]').attr('content');

        if (!confirm('Remove this item?')) return;

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: token,
            },
            success: function(response) {
                if (response.success) {
                    btn.closest('.cart-item').fadeOut(300, function() {
                        $(this).remove();
                        updateCartTotal();
                    });
                    showAlert('success', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  AJAX - Update Cart Quantity
    // ═══════════════════════════════════
    $(document).on('change', '.cart-quantity', function() {
        const input    = $(this);
        const url      = input.data('url');
        const quantity = input.val();
        const token    = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'PATCH',
                _token: token,
                quantity: quantity,
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    updateCartTotal();
                }
            },
            error: function() {
                showAlert('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  AJAX - Checkout
    // ═══════════════════════════════════
    $(document).on('submit', '#checkoutForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = '/orders/success/' + response.order_id;
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response.message ?? 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  AJAX - Update Order Status (Admin)
    // ═══════════════════════════════════
    $(document).on('submit', '#updateStatusForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  HELPER - Show Alert
    // ═══════════════════════════════════
    function showAlert(type, message) {
        const colors = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error:   'bg-red-100 border-red-400 text-red-700',
        };

        const icon = type === 'success' ? '✅' : '❌';

        const alert = $(`
            <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg border
                        shadow-lg ${colors[type]} transition-all duration-300">
                ${icon} ${message}
            </div>
        `);

        $('body').append(alert);

        setTimeout(function() {
            alert.fadeOut(300, function() { $(this).remove(); });
        }, 3000);
    }

    // ═══════════════════════════════════
    //  HELPER - Update Cart Total
    // ═══════════════════════════════════
    function updateCartTotal() {
        let total = 0;
        $('.cart-item').each(function() {
            const price    = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).find('.cart-quantity').val());
            total += price * quantity;
        });
        $('#cartTotal').text('₱' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    }

    // ═══════════════════════════════════
    //  Product Table
    // ═══════════════════════════════════
    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        let btn = $(this).find('button[type="submit"]');
        btn.text('Saving...').prop('disabled', true);

        let formData = new FormData(this);

        $.ajax({
            url: BASE_URL + '/admin/products',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function(res) {
                alert(res.message);

                $('#productForm')[0].reset();
                btn.text('Save Product').prop('disabled', false);

                location.reload(); 
            },

            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error saving product');
                btn.text('Save Product').prop('disabled', false);
            }
        });
    });

});