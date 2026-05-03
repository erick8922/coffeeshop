$(document).ready(function() {
    $('#recentOrdersTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
        }
    });
});