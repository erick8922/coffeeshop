// index

$(document).ready(function() {
    // ═══════════════════════════════════
    //  DATATABLES
    // ═══════════════════════════════════
    $('#productsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']], // Default sorting by ID
        columnDefs: [
            { orderable: false, targets: [1, 7] } // Image at Actions — hindi sortable
        ],
        language: {
            search: "Search products:",
            lengthMenu: "Show _MENU_ products",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // ═══════════════════════════════════
    //  DELETE PRODUCT - AJAX
    // ═══════════════════════════════════
    const token = $('meta[name="csrf-token"]').attr('content');

    $(document).on('click', '.delete-btn', function() {
        if (!confirm('Are you sure you want to delete this product?')) return;

        const btn = $(this);
        const url = btn.data('url');
        const row = btn.closest('tr');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    // Remove row from DataTable
                    $('#productsTable').DataTable().row(row).remove().draw();
                    showToast('success', response.message);
                }
            },
            error: function() {
                showToast('error', 'Something went wrong!');
            }
        });
    });

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