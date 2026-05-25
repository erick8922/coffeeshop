// public/js/orders.js

$(document).ready(function() {
    var ordersTable = $('#ordersTable');
    
    // Check if DataTable already exists and destroy it
    if ($.fn.DataTable.isDataTable(ordersTable[0])) {
        ordersTable.DataTable().destroy();
    }
    
    // Initialize DataTable with 10 entries per page
    ordersTable.DataTable({
        responsive: true,
        pageLength: 10,  // Binago mula 15 para maging 10 entries per page
        order: [[0, 'desc']],
        destroy: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Optional: Add options
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders",  // Ipapakita nito ang dropdown menu
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