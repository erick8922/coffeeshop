$(document).ready(function() {

    // ═══════════════════════════════════
    //  DATATABLES
    // ═══════════════════════════════════
    if (!$.fn.DataTable.isDataTable('#categoriesTable')) {
        $('#categoriesTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            language: {
                search: "Search categories:",
                lengthMenu: "Show _MENU_ categories",
                info: "Showing _START_ to _END_ of _TOTAL_ categories",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }

    // ═══════════════════════════════════
    //  DELETE CATEGORY - AJAX
    // ═══════════════════════════════════
    const token = $('meta[name="csrf-token"]').attr('content');

    $(document).on('click', '.delete-category', function() {
        if (!confirm('Are you sure you want to delete this category?')) return;

        const btn = $(this);
        const url = btn.data('url');
        const id  = btn.data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    $('#categoriesTable').DataTable().row('#category-' + id).remove().draw();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showToast('error', res?.message ?? 'Something went wrong!');
            }
        });
    });

    // ═══════════════════════════════════
    //  CREATE CATEGORY - AJAX
    // ═══════════════════════════════════
    const storeUrl = $('#categoryStoreUrl').data('url');
    const indexUrl = $('#categoryIndexUrl').data('url');

    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#submitBtn');
        btn.text('Saving...').prop('disabled', true);

        $.ajax({
            url: storeUrl,
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = indexUrl;
                    }, 1000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors?.name) {
                    $('#nameError').text(errors.name[0]).removeClass('hidden');
                }
                btn.text('Save Category').prop('disabled', false);
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