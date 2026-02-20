$(document).ready(function() {
    // Get rent data from the form element
    const itemCountBlade = window.items || {
        items: 0,
    };

    let itemCount = itemCountBlade.items;
    const itemsContainer = $('#itemsContainer');
    const addItemBtn = $('#addItemBtn');
    //const generateNumberBtn = $('#generateNumber');
    const quotationForm = $('#quotationForm');
    const emailForm = $('#emailForm');
    
    // Function to update all form data in email form
    function updateEmailFormData() {
        // Clear existing hidden inputs
        emailForm.find('input[type="hidden"]').not('[name="_token"]').remove();
        
        // Add all form data as hidden inputs
        quotationForm.serializeArray().forEach(function(field) {
            if (field.name !== '_token' && field.name !== 'action') {
                emailForm.append(
                    $('<input>').attr({
                        type: 'hidden',
                        name: field.name,
                        value: field.value
                    })
                );
            }
        });
    }
    
    // Update live preview on form changes
    function updateLivePreview() {
        const formData = {};
        
        // Get form data
        quotationForm.serializeArray().forEach(function(field) {
            // Handle nested arrays (items)
            if (field.name.match(/items\[\d+\]\[/)) {
                const match = field.name.match(/items\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const index = match[1];
                    const key = match[2];
                    if (!formData.items) formData.items = [];
                    if (!formData.items[index]) formData.items[index] = {};
                    formData.items[index][key] = field.value;
                }
            } else {
                formData[field.name] = field.value;
            }
        });
        
        // Calculate totals
        calculateTotals(formData);
        
        // Update preview HTML
        updatePreviewHTML(formData);
        
        // Update email form data
        updateEmailFormData();
    }
    
    function calculateTotals(data) {
        let itemsSubtotal = 0;
        
        if (data.items && data.items.length > 0) {
            data.items.forEach(function(item, index) {
                const quantity = parseFloat(item.quantity) || 0;
                const unitPrice = parseFloat(item.unit_price) || 0;
                item.lineTotal = quantity * unitPrice;
                itemsSubtotal += item.lineTotal;
                
                // Update line total in form
                $(`.item-row:eq(${index}) .line-total`).text(item.lineTotal.toFixed(2));
            });
        }
        
        const secureDeposit = parseFloat(data.secure_deposit) || 0;
        const transportFee = parseFloat(data.transport_fee) || 0;
        const discount = parseFloat(data.discount) || 0;
        const taxPercentage = parseFloat(data.tax_percentage) || 0;
        
        const taxAmount = itemsSubtotal * (taxPercentage / 100);
        const grandTotal = itemsSubtotal + secureDeposit + transportFee - discount + taxAmount;
        
        data.itemsSubtotal = itemsSubtotal;
        data.taxAmount = taxAmount;
        data.grandTotal = grandTotal;
        
        // Format date if exists
        if (data.date) {
            const dateObj = new Date(data.date);
            data.formattedDate = dateObj.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        } else {
            data.formattedDate = 'Not set';
        }
        
        return data;
    }
    
    function updatePreviewHTML(data) {
        const preview = $('#livePreview');
        
        if (!data.client_name && !data.quotation_title) {
            preview.html(`
                <div class="text-center text-gray-500 py-20">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p>Fill in the form to see preview</p>
                </div>
            `);
            return;
        }
        
        let itemsHTML = '';
        if (data.items && data.items.length > 0) {
            data.items.forEach(function(item, index) {
                if (item.name) {
                    itemsHTML += `
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">${item.name}</td>
                            <td class="py-2 text-sm text-center">${item.quantity || 0}</td>
                            <td class="py-2 text-sm text-center">${item.unit || 'pcs'}</td>
                            <td class="py-2 text-sm text-right">$${(parseFloat(item.unit_price) || 0).toFixed(2)}</td>
                            <td class="py-2 text-sm text-right font-medium">$${((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)).toFixed(2)}</td>
                        </tr>
                    `;
                }
            });
        }
        
        preview.html(`
            <div class="preview-content text-sm">
                <!-- Header -->
                <div class="mb-4 pb-4 border-b border-gray-300">
                    <h2 class="text-xl font-bold text-blue-900 mb-2">${data.quotation_title || 'QUOTATION'}</h2>
                    <div class="flex justify-between text-gray-600">
                        <div>
                            <p><strong>Q No:</strong> ${data.quotation_no || 'N/A'}</p>
                            <p><strong>Date:</strong> ${data.formattedDate || 'Not set'}</p>
                        </div>
                        <div class="text-right">
                            <p><strong>To:</strong> ${data.client_name || 'Client Name'}</p>
                            <p>${data.client_phone || ''}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Items Table -->
                ${itemsHTML ? `
                <table class="w-full mb-4">
                    <thead>
                        <tr class="bg-blue-50 text-blue-900">
                            <th class="py-2 px-2 text-left text-xs font-semibold">Item</th>
                            <th class="py-2 px-2 text-center text-xs font-semibold">Qty</th>
                            <th class="py-2 px-2 text-center text-xs font-semibold">Unit</th>
                            <th class="py-2 px-2 text-right text-xs font-semibold">Price</th>
                            <th class="py-2 px-2 text-right text-xs font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHTML}
                    </tbody>
                </table>
                ` : '<p class="text-gray-500 text-center py-4">No items added</p>'}
                
                <!-- Summary -->
                <div class="border-t border-gray-300 pt-4">
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span>Items Subtotal:</span>
                            <span>$${(data.itemsSubtotal || 0).toFixed(2)}</span>
                        </div>
                        ${data.secure_deposit > 0 ? `
                        <div class="flex justify-between">
                            <span>Secure Deposit:</span>
                            <span>+ $${parseFloat(data.secure_deposit).toFixed(2)}</span>
                        </div>
                        ` : ''}
                        ${data.transport_fee > 0 ? `
                        <div class="flex justify-between">
                            <span>Transport Fee:</span>
                            <span>+ $${parseFloat(data.transport_fee).toFixed(2)}</span>
                        </div>
                        ` : ''}
                        ${data.discount > 0 ? `
                        <div class="flex justify-between text-green-600">
                            <span>Discount:</span>
                            <span>- $${parseFloat(data.discount).toFixed(2)}</span>
                        </div>
                        ` : ''}
                        ${data.tax_percentage > 0 ? `
                        <div class="flex justify-between">
                            <span>Tax (${data.tax_percentage}%):</span>
                            <span>+ $${(data.taxAmount || 0).toFixed(2)}</span>
                        </div>
                        ` : ''}
                        <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-300">
                            <span>GRAND TOTAL:</span>
                            <span>$${(data.grandTotal || 0).toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }
    
    // Add new item
    addItemBtn.on('click', function() {
        const itemRow = $(`
            <div class="item-row p-4 border border-gray-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Item Name *</label>
                        <input type="text" name="items[${itemCount}][name]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 item-name">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Quantity *</label>
                        <input type="number" name="items[${itemCount}][quantity]" value="1" min="0.01" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 quantity">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Unit</label>
                        <select name="items[${itemCount}][unit]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pcs">Pcs</option>
                            <option value="set">Set</option>
                            <option value="kg">Kg</option>
                            <option value="m">M</option>
                            <option value="day">Day</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Unit Price *</label>
                        <input type="number" name="items[${itemCount}][unit_price]" value="0" min="0" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 unit-price">
                    </div>
                </div>
                <div class="mt-2 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Line Total: $<span class="line-total">0.00</span></span>
                    <button type="button" class="remove-item text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </div>
            </div>
        `);
        
        itemsContainer.append(itemRow);
        itemCount++;
        updateLivePreview();
        attachItemListeners(itemRow);
    });
    
    // Remove item
    function attachItemListeners(itemRow) {
        const removeBtn = itemRow.find('.remove-item');
        const quantityInput = itemRow.find('.quantity');
        const priceInput = itemRow.find('.unit-price');
        
        removeBtn.on('click', function() {
            itemRow.remove();
            updateLivePreview();
        });
        
        function updateLineTotal() {
            const quantity = parseFloat(quantityInput.val()) || 0;
            const price = parseFloat(priceInput.val()) || 0;
            const total = quantity * price;
            itemRow.find('.line-total').text(total.toFixed(2));
            updateLivePreview();
        }
        
        quantityInput.on('input', updateLineTotal);
        priceInput.on('input', updateLineTotal);
        
        updateLineTotal(); // Initial calculation
    }
    
    // Attach listeners to existing items
    $('.item-row').each(function() {
        attachItemListeners($(this));
    });
    
    // Generate new quotation number
    // generateNumberBtn.on('click', function() {
    //     $.ajax({
    //         url: '{{ route("quotation.generate-number") }}',
    //         type: 'GET',
    //         success: function(response) {
    //             $('input[name="quotation_no"]').val(response.quotation_no);
    //             updateLivePreview();
    //         },
    //         error: function() {
    //             alert('Failed to generate quotation number. Please try again.');
    //         }
    //     });
    // });
    
    // Update live preview on form changes
    quotationForm.on('input change', 'input, select, textarea', function() {
        updateLivePreview();
    });
    
    // Initial preview update
    updateLivePreview();
    
    // Form validation before submission
    quotationForm.on('submit', function(e) {
        let isValid = true;
        const items = $('.item-row');
        
        // Check each item
        items.each(function(index) {
            const itemRow = $(this);
            const name = itemRow.find('.item-name').val().trim();
            const quantity = itemRow.find('.quantity').val();
            const price = itemRow.find('.unit-price').val();
            
            if (!name || !quantity || !price) {
                isValid = false;
                itemRow.addClass('border-red-300 bg-red-50');
            } else {
                itemRow.removeClass('border-red-300 bg-red-50');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields for each item.');
        }
    });
    
    // Email form validation
    emailForm.on('submit', function(e) {
        const recipientEmail = emailForm.find('input[name="recipient_email"]').val();
        const subject = emailForm.find('input[name="subject"]').val();
        
        if (!recipientEmail || !subject) {
            e.preventDefault();
            alert('Please fill in recipient email and subject.');
        }
    });
});