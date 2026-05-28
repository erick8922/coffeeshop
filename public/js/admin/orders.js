$(document).ready(function() {

    // ═══════════════════════════════════
    //  DATATABLES
    // ═══════════════════════════════════
    if (!$.fn.DataTable.isDataTable('#ordersTable')) {
          $('#ordersTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']], 
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders", 
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
    }

    const token = $('meta[name="csrf-token"]').attr('content');
    let deleteUrl = '';
    let deleteRow = null;

    // ═══════════════════════════════════
    //  OPEN DELETE MODAL
    // ═══════════════════════════════════
    $(document).on('click', '.delete-order', function() {
        deleteUrl = $(this).data('url');
        deleteRow = $(this).closest('tr');
        $('#deleteConfirmModal').show();
    });

    // ═══════════════════════════════════
    //  CANCEL DELETE
    // ═══════════════════════════════════
    $('#cancelDeleteBtn').on('click', function() {
        $('#deleteConfirmModal').hide();
        deleteUrl = '';
        deleteRow = null;
    });

    // ═══════════════════════════════════
    //  CONFIRM DELETE
    // ═══════════════════════════════════
    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteUrl) return;

        $('#deleteConfirmModal').hide();

        $.ajax({
            url: deleteUrl,
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    $('#ordersTable').DataTable().row(deleteRow).remove().draw();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
                deleteUrl = '';
                deleteRow = null;
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showToast('error', res?.message ?? 'Something went wrong!');
                deleteUrl = '';
                deleteRow = null;
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