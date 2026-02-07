// Rent Creation Manager - jQuery AJAX Version
(function($) {
    'use strict';
    
    // RentCreator class to manage all rent creation functionality
    function RentCreator() {
        this.itemCount = 0;
        this.selectedProducts = new Set();
        this.productVariants = [];
        this.csrfToken = $('meta[name="csrf-token"]').attr('content');
        this.storeUrl = $('#rentForm').attr('action') || '/rents';
        this.init();
    }
    
    RentCreator.prototype = {
        // Initialize the rent creator
        init: function() {
            var self = this;
            
            // Load product variants via AJAX
            this.loadProductVariants(function() {
                // Initialize with one empty item
                self.addItemCard();
                self.calculateTotals();
                
                // Bind all events
                self.bindEvents();
                
                // Mobile touch improvements
                self.initMobileTouch();
            });
        },
        
        // Load product variants via AJAX
        loadProductVariants: function(callback) {
            var self = this;
            
            // Show loading indicator
            this.showLoading(true);
            
            $.ajax({
                url: '/admin/rents/available-variants',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    self.productVariants = response;
                    console.log('Product variants loaded:', self.productVariants);
                    self.showLoading(false);
                    if (callback) callback();
                },
                error: function(xhr, status, error) {
                    console.error('Error loading product variants:', error);
                    self.showError('Failed to load products. Please refresh the page.');
                    self.productVariants = [];
                    self.showLoading(false);
                    if (callback) callback();
                }
            });
        },
        
        // Add new item card
        addItemCard: function() {
            var index = this.itemCount;
            var self = this;
            
            // Create mobile card
            var cardHtml = `
                <div id="item-card-${index}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Item #${index + 1}</h4>
                        <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                data-index="${index}" ${index === 0 ? 'disabled' : ''}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Product<span class="text-red-500">*</span>
                            </label>
                            <select name="items[${index}][product_variant_id]" 
                                    class="product-select h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                                    data-index="${index}">
                                <option value="">Select Product</option>
                                ${this.getProductOptions()}
                            </select>
                            <input type="hidden" name="items[${index}][unit]" class="unit-input" data-index="${index}">
                            <div class="mt-1 text-xs text-gray-500 product-info" data-index="${index}"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Quantity<span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="items[${index}][rent_qty]" min="1" value="1"
                                       class="quantity-input h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                                       data-index="${index}">
                            </div>
                            
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Unit Price<span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="items[${index}][unit_price]" step="0.1"
                                       class="price-input h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                                       data-index="${index}">
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Item Total:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white item-total" data-index="${index}">$0.0</span>
                            <input type="hidden" name="items[${index}][total]" class="hidden-total" data-index="${index}" value="0">
                        </div>
                    </div>
                </div>
            `;
            
            $('#itemsContainer').append(cardHtml);
            
            // Create desktop row
            var rowHtml = `
                <tr id="item-row-${index}" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-4 py-3">
                        <select name="items[${index}][product_variant_id]" 
                                class="product-select h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                                data-index="${index}">
                            <option value="">Select Product</option>
                            ${this.getProductOptions()}
                        </select>
                        <input type="hidden" name="items[${index}][unit]" class="unit-input" data-index="${index}">
                        <div class="mt-1 text-xs text-gray-500 product-info" data-index="${index}"></div>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="items[${index}][rent_qty]" min="1" value="1"
                               class="quantity-input h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                               data-index="${index}">
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="items[${index}][unit_price]" step="0.1"
                               class="price-input h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                               data-index="${index}">
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white item-total" data-index="${index}">$0.0</div>
                        <input type="hidden" name="items[${index}][total]" class="hidden-total" data-index="${index}" value="0">
                    </td>
                    <td class="px-4 py-3">
                        <button type="button" class="remove-item-btn inline-flex items-center rounded-lg border border-red-300 bg-white p-1.5 text-red-600 hover:bg-red-50 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                                data-index="${index}" ${index === 0 ? 'disabled' : ''}>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#desktopItemsContainer').append(rowHtml);
            
            this.itemCount++;
        },
        
        // Get product options HTML
        getProductOptions: function() {
            var options = '';
            var self = this;
            
            console.log('Generating product options from variants:', this.productVariants);
            $.each(this.productVariants, function(i, variant) {
                console.log('Processing variant:', variant);
                if (variant && variant.prices) {
                    var rentPrice = variant.prices.find(function(p) {
                        return p.price_type === 'rent';
                    });
                    console.log('Found rent price:', rentPrice);
                    var price = rentPrice ? rentPrice.price : 0;
                    var productName = variant.product ? variant.product.product_name : 'Unknown';
                    var size = variant.size || 'Standard';
                    var qty = variant.qty || 0;
                    var unit = variant.unit || '';
                    
                    options += `
                        <option value="${variant.id}" 
                                data-price="${price}"
                                data-stock="${qty}"
                                data-unit="${unit}"
                                data-product="${productName}"
                                data-size="${size}">
                            ${productName} - ${size} (Stock: ${qty})
                        </option>
                    `;
                }
            });
            
            return options;
        },
        
        // Remove item
        removeItem: function(index) {
            if (parseInt(index) === 0) return;
            
            // Remove mobile card
            $('#item-card-' + index).remove();
            
            // Remove desktop row
            $('#item-row-' + index).remove();
            
            this.calculateTotals();
            this.updateItemNumbers();
        },
        
        // Update product info when selected
        updateProductInfo: function(index, element) {
            var option = element.find('option:selected');
            
            if (option.val()) {
                var price = option.data('price') || 0;
                var stock = option.data('stock') || 0;
                var unit = option.data('unit') || '';
                var productName = option.data('product') || '';
                var size = option.data('size') || '';
                
                // Update all price inputs for this index
                $('.price-input[data-index="' + index + '"]').val(price);
                
                // Update all unit inputs for this index
                $('.unit-input[data-index="' + index + '"]').val(unit);
                
                // Update all product info displays for this index
                $('.product-info[data-index="' + index + '"]').html(`
                    ${productName} - ${size}<br>
                    Stock: ${stock} ${unit}
                `);
                
                this.calculateItemTotal(index);
                this.calculateTotals();
            }
        },
        
        // Calculate item total
        calculateItemTotal: function(index) {
            var qtyInput = $('.quantity-input[data-index="' + index + '"]');
            var priceInput = $('.price-input[data-index="' + index + '"]');
            
            var qty = parseInt(qtyInput.val()) || 0;
            var price = parseFloat(priceInput.val()) || 0;
            var total = qty * price;
            
            // Update all total displays for this index
            $('.item-total[data-index="' + index + '"]').text('$' + total.toFixed(1));
            
            // Update all hidden totals for this index
            $('.hidden-total[data-index="' + index + '"]').val(total);
        },
        
        // Calculate all totals
        calculateTotals: function() {
            var subTotal = 0;
            var self = this;
            
            // Sum all item totals
            $('.item-total').each(function() {
                var text = $(this).text().replace('$', '');
                var total = parseFloat(text) || 0;
                subTotal += total;
            });
            
            // Get other values
            var discount = parseFloat($('#discount').val()) || 0;
            var transport = parseFloat($('#transport').val()) || 0;
            var deposit = parseFloat($('#deposit').val()) || 0;
            var totalPaid = parseFloat($('#totalPaid').val()) || 0;

            console.log('Calculating totals with values:', totalPaid);
            // Calculate totals
            var grandTotal = transport + deposit;
            var dueAmount = grandTotal - totalPaid;
            
            // Update displays
            $('#subTotal').text('$' + subTotal.toFixed(1));
            $('#displaySubTotal').text('$' + subTotal.toFixed(1));
            $('#grandTotal').text('$' + grandTotal.toFixed(1));
            $('#dueAmount').text('$' + dueAmount.toFixed(1));
            
            // Update hidden inputs
            $('#hiddenSubTotal').val(subTotal);
            $('#hiddenTotal').val(grandTotal);
            $('#hiddenTotalDue').val(dueAmount);
        },
        
        // Update item numbers
        updateItemNumbers: function() {
            $('#itemsContainer > div').each(function(index) {
                $(this).find('h4').text('Item #' + (index + 1));
            });
        },
        
        // Show loading indicator
        showLoading: function(show) {
            if (show) {
                $('#itemsContainer').append(`
                    <div id="loadingIndicator" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-brand-600"></div>
                        <p class="mt-2 text-sm text-gray-500">Loading products...</p>
                    </div>
                `);
            } else {
                $('#loadingIndicator').remove();
            }
        },
        
        // Show error message
        showError: function(message) {
            var errorHtml = `
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-red-700 dark:text-red-300">${message}</span>
                    </div>
                </div>
            `;
            
            $('#itemsContainer').prepend(errorHtml);
            
            // Auto-remove after 5 seconds
            setTimeout(function() {
                $('.bg-red-50').remove();
            }, 5000);
        },
        
        // Initialize mobile touch improvements
        initMobileTouch: function() {
            // Add larger tap targets for mobile
            $('input[type="number"], select, button').css('min-height', '44px');
            
            // Prevent iOS zoom on input focus
            $('input[type="number"], input[type="tel"]').on('focus', function() {
                $(this).css('font-size', '16px');
            }).on('blur', function() {
                $(this).css('font-size', '');
            });
        },
        
        // Bind all events
        bindEvents: function() {
            var self = this;
            
            // Add item button
            $(document).on('click', '#addItemBtn, button[onclick*="addItem"]', function(e) {
                e.preventDefault();
                self.addItemCard();
            });
            
            // Remove item button (event delegation)
            $(document).on('click', '.remove-item-btn', function(e) {
                e.preventDefault();
                var index = $(this).data('index');
                self.removeItem(index);
            });
            
            // Product select change
            $(document).on('change', '.product-select', function() {
                var index = $(this).data('index');
                self.updateProductInfo(index, $(this));
            });
            
            // Quantity and price input changes
            $(document).on('input', '.quantity-input, .price-input', function() {
                var index = $(this).data('index');
                self.calculateItemTotal(index);
                self.calculateTotals();
            });
            
            // Discount, transport, deposit changes
            $(document).on('input', '#discount, #transport, #deposit', function() {
                // If transport or deposit changed, update totalPaid
                var transport = parseFloat($('#transport').val()) || 0;
                var deposit = parseFloat($('#deposit').val()) || 0;
                $('#totalPaid').val(transport + deposit);
                self.calculateTotals();
            });

            // total paid changes
            $(document).on('input', '#totalPaid', function() {
                self.calculateTotals();
            });
            
            // Form submission validation
            $('#rentForm').on('submit', function(e) {
                if (!self.validateForm()) {
                    e.preventDefault();
                    return false;
                }
                
                // Show processing indicator
                self.showProcessing(true);
                
                return true;
            });
            
            // Prevent multiple submissions
            var formSubmitted = false;
            $('#rentForm').on('submit', function() {
                if (formSubmitted) {
                    return false;
                }
                formSubmitted = true;
                return true;
            });
            
            // Check stock availability before adding items
            $(document).on('change', '.product-select', function() {
                var option = $(this).find('option:selected');
                var stock = parseInt(option.data('stock')) || 0;
                var productName = option.data('product') || '';
                
                if (stock <= 0 && $(this).val()) {
                    self.showError(`${productName} is out of stock.`);
                }
            });
            
            // Auto-focus first empty product select when adding new item
            $(document).on('click', '#addItemBtn', function() {
                setTimeout(function() {
                    var emptySelect = $('.product-select').filter(function() {
                        return !$(this).val();
                    }).first();
                    
                    if (emptySelect.length) {
                        emptySelect.focus();
                    }
                }, 100);
            });
        },
        
        // Validate form before submission
        validateForm: function() {
            // Check if at least one item is added
            var hasItems = $('.product-select').length > 0;
            if (!hasItems) {
                this.showError('Please add at least one item to the rent.');
                return false;
            }
            
            // Validate all items have products selected
            var emptyItems = $('.product-select').filter(function() {
                return !$(this).val();
            });
            
            if (emptyItems.length > 0) {
                this.showError('Please select a product for all items.');
                emptyItems.first().focus();
                return false;
            }
            
            // Validate customer selected
            var customerId = $('#customer_id').val();
            if (!customerId) {
                this.showError('Please select a customer.');
                $('#customer_id').focus();
                return false;
            }
            
            // Validate rent date
            var rentDate = $('#rent_date').val();
            if (!rentDate) {
                this.showError('Please select a rent date.');
                $('#rent_date').focus();
                return false;
            }
            
            // Validate quantities are positive
            var invalidQuantities = $('.quantity-input').filter(function() {
                var qty = parseInt($(this).val()) || 0;
                return qty <= 0;
            });
            
            if (invalidQuantities.length > 0) {
                this.showError('Please enter valid quantities (greater than 0).');
                invalidQuantities.first().focus();
                return false;
            }
            
            // Validate prices are positive
            var invalidPrices = $('.price-input').filter(function() {
                var price = parseFloat($(this).val()) || 0;
                return price <= 0;
            });
            
            if (invalidPrices.length > 0) {
                this.showError('Please enter valid prices (greater than 0).');
                invalidPrices.first().focus();
                return false;
            }
            
            return true;
        },
        
        // Show processing indicator
        showProcessing: function(show) {
            if (show) {
                $('#rentForm').append(`
                    <div id="formProcessing" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-brand-600 mb-4"></div>
                            <p class="text-gray-700 dark:text-gray-300">Creating rent...</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please wait</p>
                        </div>
                    </div>
                `);
            } else {
                $('#formProcessing').remove();
            }
        },
        
        // Search products by name (optional enhancement)
        initProductSearch: function() {
            var self = this;
            
            // Add search input
            $('#itemsContainer').before(`
                <div class="mb-4">
                    <input type="text" id="productSearch" 
                           class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm placeholder-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                           placeholder="Search products by name...">
                </div>
            `);
            
            $('#productSearch').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $('.product-select option').each(function() {
                    var optionText = $(this).text().toLowerCase();
                    if (searchTerm === '' || optionText.indexOf(searchTerm) >= 0) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Check if we're on the rent creation page
        if ($('#rentForm').length) {
            window.rentCreator = new RentCreator();
            
            // Optional: Initialize product search
            // window.rentCreator.initProductSearch();
        }
    });
    
})(jQuery);