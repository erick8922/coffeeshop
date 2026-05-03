$(document).ready(function() {
    $('#ordersTable').DataTable({
        responsive: true,
        pageLength: 15,
        order: [[0, 'desc']],
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
});