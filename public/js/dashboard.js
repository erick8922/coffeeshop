// public/js/dashboard.js

$(document).ready(function() {
    
    // ═══════════════════════════════════
    //  ORDERS PER MONTH CHART
    // ═══════════════════════════════════
    if (window.dashboardData && window.dashboardData.ordersPerMonth && window.dashboardData.ordersPerMonth.length > 0) {
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: window.dashboardData.ordersPerMonth.map(d => d.month),
                datasets: [{
                    label: 'Orders',
                    data: window.dashboardData.ordersPerMonth.map(d => d.total),
                    borderColor: '#92400e',
                    backgroundColor: 'rgba(146, 64, 14, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // ═══════════════════════════════════
    //  SALES PER MONTH CHART
    // ═══════════════════════════════════
    if (window.dashboardData && window.dashboardData.salesPerMonth && window.dashboardData.salesPerMonth.length > 0) {
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: window.dashboardData.salesPerMonth.map(d => d.month),
                datasets: [{
                    label: 'Sales (₱)',
                    data: window.dashboardData.salesPerMonth.map(d => d.total),
                    backgroundColor: 'rgba(146, 64, 14, 0.7)',
                    borderColor: '#92400e',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ═══════════════════════════════════
    //  ORDERS BY STATUS CHART
    // ═══════════════════════════════════
    if (window.dashboardData && window.dashboardData.ordersByStatus && window.dashboardData.ordersByStatus.length > 0) {
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: window.dashboardData.ordersByStatus.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1)),
                datasets: [{
                    data: window.dashboardData.ordersByStatus.map(d => d.total),
                    backgroundColor: [
                        '#FEF08A', // pending - yellow
                        '#BFDBFE', // preparing - blue
                        '#BBF7D0', // ready - green
                        '#D1D5DB', // completed - gray
                        '#FECACA', // cancelled - red
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 } }
                    }
                }
            }
        });
    }

    // ═══════════════════════════════════
    //  TOP PRODUCTS CHART
    // ═══════════════════════════════════
    if (window.dashboardData && window.dashboardData.topProducts && window.dashboardData.topProducts.length > 0) {
        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: window.dashboardData.topProducts.map(d => d.name),
                datasets: [{
                    label: 'Units Sold',
                    data: window.dashboardData.topProducts.map(d => d.total_sold),
                    backgroundColor: [
                        'rgba(146, 64, 14, 0.8)',
                        'rgba(180, 83, 9, 0.8)',
                        'rgba(217, 119, 6, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                    ],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // horizontal bar
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // ═══════════════════════════════════
    //  DATATABLES - Fixed reinitialization
    // ═══════════════════════════════════
    var ordersTable = $('#recentOrdersTable');
    
    // Check if DataTable already exists and destroy it
    if ($.fn.DataTable.isDataTable(ordersTable[0])) {
        ordersTable.DataTable().destroy();
    }
    
    ordersTable.DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        destroy: true, // Additional safety
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
        }
    });
});