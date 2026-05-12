$(document).ready(function() {
    let searchTimer;

    // ═══════════════════════════════════
    //  REAL-TIME SEARCH
    // ═══════════════════════════════════
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            performSearch();
        }, 400);
    });

    // ═══════════════════════════════════
    //  CATEGORY FILTER
    // ═══════════════════════════════════
    $('#categoryFilter').on('change', function() {
        performSearch();
    });

    // ═══════════════════════════════════
    //  RESET BUTTON
    // ═══════════════════════════════════
    $('#resetBtn').on('click', function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        performSearch();
    });

    // ═══════════════════════════════════
    //  PERFORM AJAX SEARCH
    // ═══════════════════════════════════
    function performSearch() {
        const search   = $('#searchInput').val();
        const category = $('#categoryFilter').val();
        const menuUrl  = $('#menuUrl').data('url');

        $('#loadingSpinner').removeClass('hidden');
        $('#productsGrid').hide();

        $.ajax({
            url: menuUrl,
            method: 'GET',
            data: { search: search, category: category },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.products.length === 0) {
                    $('#productsGrid').html(`
                        <div class="text-center py-20 text-gray-400">
                            <p class="text-5xl mb-4">☕</p>
                            <p class="text-lg">No drinks found.</p>
                            <p class="text-sm mt-1">Try adjusting your search or filters.</p>
                        </div>
                    `).show();
                    $('#resultsCount').text('Showing 0 product(s)');
                    return;
                }

                $('#resultsCount').text('Showing ' + response.products.length + ' product(s)');

                let html = '<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">';

                response.products.forEach(function(product) {
                    const imageHtml = product.image
                        ? `<div class="relative w-full h-44 overflow-hidden rounded-t-xl">
                                <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                                     style="background-image: url('/images/product_images/${product.image}')"></div>
                                <img src="/images/product_images/${product.image}"
                                     alt="${product.name}"
                                     class="relative z-10 w-full h-full object-contain">
                           </div>`
                        : `<div class="w-full h-44 bg-amber-100 flex items-center justify-center">
                                <span class="text-5xl">☕</span>
                           </div>`;

                    html += `
                        <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
                            ${imageHtml}
                            <div class="p-4">
                                <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-full">
                                    ${product.category_name}
                                </span>
                                <h3 class="font-bold text-amber-900 mt-2">${product.name}</h3>
                                <p class="text-gray-400 text-xs mt-1 line-clamp-2">${product.description ?? ''}</p>
                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-amber-700 font-bold">
                                        ₱${parseFloat(product.price).toFixed(2)}
                                    </span>
                                    <a href="/menu/${product.slug}"
                                       class="bg-amber-900 text-white px-3 py-1 rounded-full
                                              text-sm hover:bg-amber-700 transition">
                                        Order Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += '</div>';
                $('#productsGrid').html(html).show();
            },
            error: function() {
                $('#productsGrid').html(`
                    <div class="text-center py-20 text-red-400">
                        <p class="text-lg">Something went wrong. Please try again.</p>
                    </div>
                `).show();
            },
            complete: function() {
                $('#loadingSpinner').addClass('hidden');
            }
        });
    }
});