$(document).ready(function() {
    // State Management
    let currentStep = 1;
    let productsData = [];
    let selectedProducts = [];
    let productCounter = 1;
    let transportEnabled = false;
    const DEPOSIT_AMOUNT = Number(window.quotationConfig?.depositPerProduct ?? 5000);
    let totalDeposit = 0;

    // Load products from API
    function loadProducts() {
        return $.ajax({
            url: '/home/get-available-variants',
            method: 'GET',
            success: function(response) {
                productsData = response;
                if (productsData && productsData.length > 0) {
                    addProductRow(); // Add initial product row
                }
            },
            error: function() {
                showToast('Failed to load products. Please refresh the page.', 'error');
            }
        });
    }

    // Get product price based on type and duration
    function getProductPrice(productId) {
        const quotationType = $('input[name="type"]:checked').val();
        const variant = productsData.find(p => p.id == productId);
        if (!variant || !variant.prices) return 0;

        if (quotationType === 'purchase') {
            const salePrice = variant.prices.find(p => p.price_type === 'sale');
            return salePrice ? parseFloat(salePrice.price) : 0;
        } else {
            const duration = parseInt($('#rentDuration').val()) || 1;
            const rentPrices = variant.prices
                .filter(p => p.price_type === 'rent')
                .sort((a, b) => b.duration_days - a.duration_days);

            if (rentPrices.length === 0) return 0;

            let selectedPrice = rentPrices.find(p => duration >= p.duration_days);
            if (!selectedPrice) selectedPrice = rentPrices[rentPrices.length - 1];
            
            return selectedPrice ? parseFloat(selectedPrice.price) : 0;
        }
    }

    // Add product row
    function addProductRow() {
        const id = productCounter++;
        selectedProducts.push({ id: id, productId: productsData[0]?.id || null, qty: 1 });

        const productOptions = productsData.map(p => {
            const productName = p.product ? `${p.product.product_name} - ${p.size}` : p.size;
            return `<option value="${p.id}">${productName}</option>`;
        }).join('');

        const rowHtml = `
            <div class="product-row bg-navy-800/50 border border-white/10 rounded-lg p-4" data-id="${id}">
                <div class="grid grid-cols-12 gap-3 items-center">
                    <select class="product-select col-span-6 px-3 py-2 bg-navy-700 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-orange-500/50" data-id="${id}">
                        <option value="">Select Product</option>
                        ${productOptions}
                    </select>
                    <input type="number" class="product-qty col-span-3 px-3 py-2 bg-navy-700 border border-white/10 rounded-lg text-white text-sm text-center focus:outline-none focus:ring-1 focus:ring-orange-500/50" value="1" min="1" data-id="${id}">
                    <div class="col-span-2">
                        <span class="product-price-display text-sm text-orange-400 font-semibold" data-id="${id}">Ks 0</span>
                    </div>
                    <button type="button" class="remove-product col-span-1 text-steel-500 hover:text-red-400 transition-colors" data-id="${id}">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `;

        $('#productsContainer').append(rowHtml);
        $('#emptyProductsMsg').hide();
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        updateProductPrice(id);
    }

    // Update product price display
    function updateProductPrice(productId) {
        const product = selectedProducts.find(p => p.id === productId);
        if (!product || !product.productId) return;
        
        const price = getProductPrice(product.productId);
        const qty = product.qty;
        const total = price * qty;
        
        $(`.product-price-display[data-id="${productId}"]`).text(`Ks ${total.toFixed(0)}`);
        updateSubtotal();
    }

    // Update subtotal
    function updateSubtotal() {
        let subtotal = 0;
        totalDeposit = 0;
        const duration = parseInt($('#rentDuration').val()) || 1;
        const quotationType = $('input[name="type"]:checked').val();
        
        selectedProducts.forEach(product => {
            if (product.productId) {
                const price = getProductPrice(product.productId);
                const qty = product.qty;
                let total = price * qty;
                if (quotationType === 'rent') {
                    total *= duration;
                    totalDeposit += DEPOSIT_AMOUNT * product.qty;
                }
                subtotal += total;
            }
        });

        $('#summarySubTotal, #reviewSubTotal').text(`Ks ${subtotal.toFixed(0)}`);
        syncDepositInput();
        updateTotal();
        return subtotal;
    }

    function syncDepositInput() {
        const quotationType = $('input[name="type"]:checked').val();
        $('#depositInput').val(quotationType === 'rent' ? totalDeposit.toFixed(0) : 0);
    }

    // Update total
    function updateTotal() {
        const subtotal = parseFloat($('#summarySubTotal').text().replace('Ks ', '')) || 0;
        const quotationType = $('input[name="type"]:checked').val();
        
        let total = 0;
        if (quotationType === 'purchase') {
            total = subtotal;
        } else {
            total = totalDeposit;
        }

        $('#summaryTotal, #reviewTotal').text(`Ks ${total.toFixed(0)}`);
    }

    // Handle step navigation
    function goToStep(step) {
        // Validate current step before proceeding
        if (step > currentStep) {
            if (!validateStep(currentStep)) {
                return false;
            }
        }

        // Hide all steps
        $('.step-content').addClass('hidden');
        $(`#step${step}`).removeClass('hidden');

        // Update step buttons styling
        for (let i = 1; i <= 3; i++) {
            const btn = $(`#step${i}Btn`);
            if (i <= step) {
                btn.removeClass('opacity-50');
                btn.find('div:first-child').removeClass('bg-steel-700').addClass('bg-orange-500');
                btn.find('span:first-child').removeClass('text-steel-400').addClass('text-white');
            } else {
                btn.addClass('opacity-50');
                btn.find('div:first-child').removeClass('bg-orange-500').addClass('bg-steel-700');
                btn.find('span:first-child').removeClass('text-white').addClass('text-steel-400');
            }
        }

        // If moving to step 3, populate review data
        if (step === 3) {
            populateReviewData();
        }

        currentStep = step;
        return true;
    }

    // Validate current step
    function validateStep(step) {
        if (step === 1) {
            if (selectedProducts.length === 0 || selectedProducts.every(p => !p.productId)) {
                showToast('Please add at least one product.', 'error');
                return false;
            }
            
            const hasValidProduct = selectedProducts.some(p => p.productId && p.qty > 0);
            if (!hasValidProduct) {
                showToast('Please select products and specify quantities.', 'error');
                return false;
            }

            const quotationType = $('input[name="type"]:checked').val();
            if (quotationType === 'rent') {
                const rentDate = $('#rentDate').val();
                const rentDuration = $('#rentDuration').val();
                if (!rentDate) {
                    showToast('Please select a rental date.', 'error');
                    return false;
                }
                if (!rentDuration || parseInt(rentDuration) < 1) {
                    showToast('Please enter a valid rental duration.', 'error');
                    return false;
                }
            }
        }
        
        if (step === 2) {
            const customerName = $('#customerName').val().trim();
            if (!customerName) {
                showToast('Please enter your name.', 'error');
                return false;
            }
        }
        
        return true;
    }

    // Populate review data
    function populateReviewData() {
        // Products list
        const reviewProducts = $('#reviewProductsList');
        reviewProducts.empty();
        
        let subtotal = 0;
        totalDeposit = 0;
        const duration = parseInt($('#rentDuration').val()) || 1;
        const quotationType = $('input[name="type"]:checked').val();
        
        selectedProducts.forEach(product => {
            if (product.productId) {
                const variant = productsData.find(p => p.id == product.productId);
                console.log('Reviewing product:', variant);
                if (variant) {
                    const price = getProductPrice(product.productId);
                    const productName = variant.product ? `${variant.product.product_name} - ${variant.size}` : variant.size;
                    let total = price * product.qty;
                    if (quotationType === 'rent') {
                        total *= duration;
                        totalDeposit += DEPOSIT_AMOUNT * product.qty;
                    }
                    subtotal += total;
                    
                    reviewProducts.append(`
                        <tr class="border-b border-white/5">
                            <td class="px-4 py-3 text-white">${productName}</td>
                            <td class="text-center px-4 py-3 text-white">${product.qty} ${variant.unit}</td>
                            <td class="text-right px-4 py-3 text-white">${price.toFixed(0)}${quotationType === 'rent' ? '/day' : ''}</td>
                            <td class="text-right px-4 py-3 text-orange-400 font-semibold">${total.toFixed(0)}</td>
                        </tr>
                    `);
                }
            }
        });
        
        // Store items in hidden fields for submission
        buildHiddenItems();
        
        // Update review summary
        $('#reviewSubTotal').text(`Ks ${subtotal.toFixed(0)}`);
        $('#reviewDeposit').text(`Ks ${totalDeposit.toFixed(0)}`);
        syncDepositInput();
        $('#reviewType').text(quotationType);
        
        if (quotationType === 'rent') {
            $('#reviewDurationRow').show();
            $('#reviewDuration').text(`${duration} days`);
            $('#reviewDepositRow').show();
        } else {
            $('#reviewDurationRow').hide();
            $('#reviewDepositRow').hide();
        }
        
        // Customer details
        $('#reviewCustomerName').text($('#customerName').val() || '-');
        $('#reviewCustomerEmail').text($('#customerEmail').val() || '-');
        $('#reviewCustomerPhone').text($('#customerPhone').val() || '-');
        $('#reviewCustomerAddress').text($('#transportAddress').val() || '-');
        $('#reviewCustomerNotes').text($('#quoteNote').val() || '-');
        
        // Transport
        if ($('#transportCheckbox').is(':checked')) {
            $('#reviewTransportRow').removeClass('hidden');
            $('#reviewTransport').text('(Transport charges apply based on distance and will be confirmed by our team)');
        } else {
            $('#reviewTransportRow').addClass('hidden');
        }
        
        updateTotal();
    }

    // Build hidden items for form submission
    function buildHiddenItems() {
        const container = $('#hiddenItemsContainer');
        container.empty();
        
        selectedProducts.forEach((product, index) => {
            if (product.productId) {
                container.append(`
                    <input type="hidden" name="items[${index}][product_variant_id]" value="${product.productId}">
                    <input type="hidden" name="items[${index}][qty]" value="${product.qty}">
                `);
            }
        });
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 min-w-72 ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white animate-fade-in-up">
                <i data-lucide="${type === 'error' ? 'alert-circle' : 'check-circle'}" class="w-5 h-5"></i>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `);
        
        $('body').append(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        setTimeout(() => {
            toast.fadeOut(300, function() { $(this).remove(); });
        }, 4000);
    }

    // Event Listeners
    $(document).on('click', '#addProductBtn', function() {
        if (productsData.length === 0) {
            showToast('Products are still loading. Please wait.', 'error');
            return;
        }
        addProductRow();
    });
    
    $(document).on('click', '.remove-product', function() {
        const id = parseInt($(this).data('id'));
        selectedProducts = selectedProducts.filter(p => p.id !== id);
        $(`.product-row[data-id="${id}"]`).remove();
        
        if (selectedProducts.length === 0) {
            $('#emptyProductsMsg').show();
        }
        
        updateSubtotal();
    });
    
    $(document).on('change', '.product-select', function() {
        const id = parseInt($(this).data('id'));
        const productId = $(this).val();
        const product = selectedProducts.find(p => p.id === id);
        if (product) {
            product.productId = productId;
            updateProductPrice(id);
        }
    });
    
    $(document).on('input change', '.product-qty', function() {
        const id = parseInt($(this).data('id'));
        const qty = parseInt($(this).val()) || 1;
        const product = selectedProducts.find(p => p.id === id);
        if (product) {
            product.qty = Math.max(1, qty);
            $(this).val(product.qty);
            updateProductPrice(id);
        }
    });
    
    // Handle custom radio button change for quotation type
    $('input[name="type"]').on('change', function() {
        const isRent = $(this).val() === 'rent';
        if (isRent) {
            $('#rentDetails').slideDown();
        } else {
            $('#rentDetails').slideUp();
        }
        
        // Recalculate all product prices
        selectedProducts.forEach(product => {
            if (product.productId) updateProductPrice(product.id);
        });
        updateSubtotal();
    });
    
    $('#rentDuration').on('input change', function() {
        if ($('input[name="type"]:checked').val() === 'rent') {
            selectedProducts.forEach(product => {
                if (product.productId) updateProductPrice(product.id);
            });
            updateSubtotal();
        }
    });
    
    $('#transportCheckbox').on('change', function() {
        transportEnabled = $(this).is(':checked');
    });
    
    // Step navigation
    $('.next-step').on('click', function() {
        if (currentStep < 3) {
            goToStep(currentStep + 1);
        }
    });
    
    $('.prev-step').on('click', function() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });
    
    $('#step1Btn, #step2Btn, #step3Btn').on('click', function() {
        const step = parseInt($(this).data('step'));
        if (step <= currentStep || validateStep(currentStep)) {
            goToStep(step);
        } else {
            showToast('Please complete the current step first.', 'error');
        }
    });
    
    // Form submission
    $('#quotationForm').on('submit', function(e) {
        if (!validateStep(1) || !validateStep(2)) {
            e.preventDefault();
            showToast('Please complete all required fields.', 'error');
            return false;
        }
        
        if (selectedProducts.length === 0 || selectedProducts.every(p => !p.productId)) {
            e.preventDefault();
            showToast('Please add at least one product.', 'error');
            return false;
        }

        buildHiddenItems();
        updateSubtotal();
        
        $('#submitQuotationBtn').prop('disabled', true).html('<i data-lucide="loader-2" class="w-4 h-4 inline mr-1 animate-spin"></i> Submitting...');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
    
    // Initialization
    $('#rentDate').val(new Date().toISOString().split('T')[0]);
    loadProducts();
});
