$(document).ready(function() {
    const token = $('meta[name="csrf-token"]').attr('content');

    // ═══════════════════════════════════
    //  UPDATE CART ITEM
    // ═══════════════════════════════════
    $(document).on('click', '.update-btn', function() {
        const id       = $(this).data('id');
        const url      = $(this).data('url');
        const quantity = $('#item-' + id + ' .cart-quantity').val();

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'PATCH', _token: token, quantity: quantity },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    updateTotal();
                }
            },
            error: function() {
                showToast('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  REMOVE CART ITEM
    // ═══════════════════════════════════
    $(document).on('click', '.remove-item', function() {
        if (!confirm('Remove this item?')) return;

        const id  = $(this).data('id');
        const url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    $('#item-' + id).fadeOut(300, function() {
                        $(this).remove();
                        updateTotal();

                        // Kung wala nang items, i-reload
                        if ($('.cart-item').length === 0) {
                            location.reload();
                        }
                    });
                    showToast('success', response.message);
                }
            },
            error: function() {
                showToast('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  CLEAR CART
    // ═══════════════════════════════════
    $('#clearCartBtn').on('click', function() {
        if (!confirm('Clear all items from your cart?')) return;

        $.ajax({
            url: "{{ route('cart.clear') }}",
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function() {
                showToast('error', 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  CHECKOUT FORM - AJAX
    // ═══════════════════════════════════
    $('#checkoutForm').on('submit', function(e) {
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
                const res = xhr.responseJSON;
                showToast('error', res?.message ?? 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  UPDATE TOTAL
    // ═══════════════════════════════════
    function updateTotal() {
        let total = 0;
        $('.cart-item').each(function() {
            const price    = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).find('.cart-quantity').val());
            total += price * quantity;
        });
        const formatted = '₱' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        $('#cartTotal').text(formatted);
        $('#cartTotalBottom').text(formatted);
    }

    // ═══════════════════════════════════
    //  TOAST NOTIFICATION
    // ═══════════════════════════════════
    function showToast(type, message) {
        const colors = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error:   'bg-red-100 border-red-400 text-red-700',
        };
        const icon  = type === 'success' ? '✅' : '❌';
        const toast = $(`
            <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg border
                        shadow-lg ${colors[type]}">
                ${icon} ${message}
            </div>
        `);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, function() { $(this).remove(); }), 3000);
    }
});