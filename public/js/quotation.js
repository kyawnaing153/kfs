$(document).ready(function () {
    // ===== QUOTATION STATE =====
    let transportEnabled = false;
    let quotationType = 'rent';
    let quoteItems = [];
    let quoteCounter = 1;
    let itemCounter = 1;
    let productsData = [];
    const DEPOSIT_AMOUNT = 500;

    // Load available variants from API
    $.ajax({
        url: '/home/get-available-variants',
        method: 'GET',
        success: function (response) {
            productsData = response;
            // Optionally add one initial item if products exist
            if (productsData && productsData.length > 0) {
                $('#addQuoteItemBtn').click();
            }
        },
        error: function () {
            showToast('Failed to load products from API.', 'error');
        }
    });

    // Generate quote code on load
    function generateQuoteCode() {
        const codeEl = $('#quotationCode');
        if (codeEl.length === 0) return;
        const now = new Date();
        const yy = now.getFullYear().toString().slice(2);
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const dd = String(now.getDate()).padStart(2, '0');
        const seq = String(quoteCounter++).padStart(4, '0');
        codeEl.val(`QT-${yy}${mm}${dd}-${seq}`);
    }

    // Handle Quotation Type change
    $('#quotationType').on('change', function () {
        quotationType = $(this).val();

        if (quotationType === 'purchase') {
            $('#rentDateContainer').hide();
            $('#depositContainer').hide();
            $('#summaryDepositRow').hide();
            $('#rentDate').prop('required', false);
            $('#rentDuration').prop('required', false);
        } else {
            $('#rentDateContainer').show();
            $('#depositContainer').show();
            $('#summaryDepositRow').show();
            $('#rentDate').prop('required', true);
            $('#rentDuration').prop('required', true);
        }

        // Re-render items because rate might have changed
        renderQuoteItems();
    });

    // Transport toggle
    $('#transportToggle').on('click', function () {
        transportEnabled = !transportEnabled;
        const btn = $(this);
        const costValue = $('#transportCostValue');
        const transportRow = $('#transportRow');
        const transportInput = $('#transportRequired');

        if (transportEnabled) {
            btn.addClass('active').html('<i data-lucide="truck" class="w-4 h-4"></i> Transport: ON');
            costValue.removeClass('hidden');
            transportRow.css('display', 'flex');
            transportInput.val('1');
        } else {
            btn.removeClass('active').html('<i data-lucide="truck" class="w-4 h-4"></i> Transport: OFF');
            costValue.addClass('hidden');
            transportRow.css('display', 'none');
            transportInput.val('0');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        updateTotals();
    });

    // Helper to get rate of product
    function getProductRate(productId) {
        if (!productsData) return 0;
        
        const variant = productsData.find(p => p.id == productId);
        if (!variant || !variant.prices) return 0;

        if (quotationType === 'purchase') {
            // Find sale price
            const salePriceObj = variant.prices.find(p => p.price_type === 'sale');
            if (salePriceObj) {
                return parseFloat(salePriceObj.price) || 0;
            }
        } else {
            // Find rent price based on duration
            const duration = parseInt($('#rentDuration').val()) || 1;
            
            const rentPrices = variant.prices
                .filter(p => p.price_type === 'rent')
                .sort((a, b) => b.duration_days - a.duration_days);

            if (rentPrices.length === 0) return 0;

            let selectedPrice = null;
            for (let i = 0; i < rentPrices.length; i++) {
                if (duration >= rentPrices[i].duration_days) {
                    selectedPrice = rentPrices[i];
                    break;
                }
            }

            // If duration is less than the smallest tier, use the smallest tier
            if (!selectedPrice) {
                selectedPrice = rentPrices[rentPrices.length - 1];
            }

            return parseFloat(selectedPrice.price) || 0;
        }
        return 0;
    }

    // Handle duration change
    $('#rentDuration').on('input change', function () {
        renderQuoteItems();
    });

    // Render items
    function renderQuoteItems() {
        const container = $('#quoteItemsContainer');
        const empty = $('#quoteItemsEmpty');

        if (container.length === 0 || empty.length === 0) return;

        if (quoteItems.length === 0) {
            container.empty();
            empty.show();
            updateTotals();
            return;
        }

        empty.hide();

        const html = quoteItems.map((item, index) => {
            let durationMultiplier = 1;
            if (quotationType === 'rent') {
                durationMultiplier = parseInt($('#rentDuration').val()) || 1;
            }

            const productOptions = productsData.map(v => {
                const name = v.product ? `${v.product.product_name} - ${v.size}` : `Product ${v.size}`;
                const rate = getProductRate(v.id);
                const rateText = quotationType === 'rent' ? `Ks ${rate}/day` : `Ks ${rate}`;
                const isSelected = (item.productId == v.id) ? 'selected' : '';
                return `<option value="${v.id}" ${isSelected}>${name} - ${rateText}</option>`;
            }).join('');

            const lineTotal = getProductRate(item.productId) * item.qty * durationMultiplier;

            return `
            <div class="quote-item-row bg-navy-900/50 border border-white/10 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-1 space-y-3">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <select name="items[${index}][product_variant_id]" 
                                data-id="${item.id}"
                                class="item-product-select col-span-5 px-2 py-2 bg-navy-800 border border-white/10 rounded-lg text-white text-xs focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                ${productOptions}
                            </select>
                            <input type="number" name="items[${index}][qty]" 
                                data-id="${item.id}"
                                value="${item.qty}" min="1" max="9999"
                                class="item-qty-input col-span-3 px-2 py-2 bg-navy-800 border border-white/10 rounded-lg text-white text-sm text-center focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                            <div class="col-span-3 text-right">
                                <span class="item-line-total text-sm font-semibold text-orange-400"
                                    data-id="${item.id}">Ks ${lineTotal.toFixed(0)}</span>
                            </div>
                            <button type="button" data-id="${item.id}"
                                class="remove-item-btn col-span-1 flex justify-center text-steel-500 hover:text-red-400 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        container.html(html);

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        updateTotals();
    }

    // Add Item
    $('#addQuoteItemBtn').on('click', function () {
        if (productsData.length === 0) {
            showToast('Products are still loading or unavailable.', 'error');
            return;
        }
        const defaultProduct = productsData[0].id;
        quoteItems.push({ id: itemCounter++, productId: defaultProduct, qty: 1 });
        renderQuoteItems();
    });

    // Remove item
    $('#quoteItemsContainer').on('click', '.remove-item-btn', function () {
        const id = $(this).data('id');
        quoteItems = quoteItems.filter(i => i.id !== id);
        renderQuoteItems();
    });

    // Product select change
    $('#quoteItemsContainer').on('change', '.item-product-select', function () {
        const id = $(this).data('id');
        const val = $(this).val();
        const item = quoteItems.find(i => i.id === id);
        if (item) {
            item.productId = val;
            renderQuoteItems();
        }
    });

    // Quantity change
    $('#quoteItemsContainer').on('input change', '.item-qty-input', function () {

        const input = $(this);
        const id = input.data('id');
        const val = input.val();

        const item = quoteItems.find(i => i.id === id);
        if (!item) return;

        item.qty = Math.max(1, parseInt(val) || 1);

        // calculate new line total
        let durationMultiplier = 1;
        if (quotationType === 'rent') {
            durationMultiplier = parseInt($('#rentDuration').val()) || 1;
        }
        const rate = getProductRate(item.productId);
        const lineTotal = rate * item.qty * durationMultiplier;

        // update only this row total
        $('.item-line-total[data-id="' + id + '"]').text('Ks ' + lineTotal.toFixed(0));

        // update quotation totals
        updateTotals();
    });

    function updateTotals() {
        let durationMultiplier = 1;
        if (quotationType === 'rent') {
            durationMultiplier = parseInt($('#rentDuration').val()) || 1;
        }

        const subTotal = quoteItems.reduce((sum, item) => {
            return sum + getProductRate(item.productId) * item.qty * durationMultiplier;
        }, 0);

        const adminTransportCost = $('#adminTransportCost').val();
        const transportCost = transportEnabled
            ? (parseFloat(adminTransportCost) || 0)
            : 0;

        let total = 0;
        if (quotationType === 'purchase') {
            total = subTotal + transportCost;
        } else {
            // Original rent logic
            total = DEPOSIT_AMOUNT + transportCost;
        }

        $('#summarySubTotal').text('Ks ' + subTotal.toFixed(0));
        $('#summaryTotal').text('Ks ' + total.toFixed(0));

        if (transportEnabled) {
            $('#summaryTransport').text('Ks ' + transportCost.toFixed(0));
            $('#transportCostValue').text('Ks ' + transportCost.toFixed(0));
        }
    }

    // Toast function
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        const msg = $('#toastMsg');

        if (toast.length === 0 || msg.length === 0) {
            alert(message);
            return;
        }

        msg.text(message);
        toast.attr('class', `toast fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 min-w-72 ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white`);
        toast.addClass('show');
        setTimeout(() => toast.removeClass('show'), 4000);
    }

    // Form validation before submit
    $('#quotationForm').on('submit', function (e) {
        if (quoteItems.length === 0) {
            e.preventDefault();
            showToast('Please add at least one item to the quotation.', 'error');
            return false;
        }

        if (quotationType === 'rent') {
            const rentDateVal = $('#rentDate').val();
            const rentDurationVal = $('#rentDuration').val();
            if (!rentDateVal) {
                e.preventDefault();
                showToast('Please select a rental date.', 'error');
                return false;
            }
            if (!rentDurationVal || parseInt(rentDurationVal) < 1) {
                e.preventDefault();
                showToast('Please enter a valid rental duration.', 'error');
                return false;
            }
        }

        const badge = $('#quoteStatusBadge');
        if (badge.length > 0) {
            badge.text('Submitting...');
            badge.attr('class', 'px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-full border border-yellow-500/30');
        }
    });

    // Run initializations
    const rentDateEl = $('#rentDate');
    if (rentDateEl.length > 0) {
        rentDateEl.val(new Date().toISOString().split('T')[0]);
    }
    generateQuoteCode();
});
