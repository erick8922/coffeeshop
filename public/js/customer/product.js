document.querySelectorAll('.size-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const price = parseFloat(this.dataset.price);
                document.getElementById('displayPrice').textContent =
                    '₱' + price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            });
        });

        const firstSize = document.querySelector('.size-radio:checked');
        if (firstSize) {
            const price = parseFloat(firstSize.dataset.price);
            document.getElementById('displayPrice').textContent =
                '₱' + price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        function handleAddToCart() {
            const sizeOptions = document.querySelectorAll('.size-radio');
            if (sizeOptions.length > 0) {
                const selected = document.querySelector('.size-radio:checked');
                if (!selected) {
                    document.getElementById('sizePopup').classList.remove('hidden');
                    return;
                }
            }

            // AJAX submit
            const form = document.getElementById('addToCartForm');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    document.getElementById('cartCount').textContent = data.cart_count;
                    // Show success popup
                    showSuccessPopup(data.message);
                }
            })
            .catch(err => console.error(err));
        }

        function showSuccessPopup(message) {
            const popup = document.createElement('div');
            popup.className = 'fixed top-4 right-4 z-50 bg-green-100 border border-green-400 ' +
                              'text-green-700 px-6 py-3 rounded-lg shadow-lg';
            popup.textContent = message;
            document.body.appendChild(popup);
            setTimeout(() => popup.remove(), 3000);
        }

        function closePopup() {
            document.getElementById('sizePopup').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePopup();
        });