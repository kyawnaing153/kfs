$(document).ready(function () {
    // Get rent data from the form element
    const rentData = window.rentData || {
        rentDate: '',
        deposit: 0,
        subTotal: 0,
        totalPayment: 0
    };

    // Image preview
    const returnImageInput = $('#return_image');
    const imagePreview = $('#imagePreview');
    const previewImage = $('#previewImage');

    if (returnImageInput.length) {
        returnImageInput.on('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.attr('src', e.target.result);
                    imagePreview.removeClass('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.addClass('hidden');
            }
        });
    }

    // Checkbox functionality
    const selectAllCheckbox = $('#selectAllItems');
    const itemCheckboxes = $('.item-checkbox');

    if (selectAllCheckbox.length) {
        selectAllCheckbox.on('change', function () {
            const isChecked = $(this).is(':checked');
            itemCheckboxes.prop('checked', isChecked).trigger('change');
        });
    }

    // Individual checkbox change
    itemCheckboxes.on('change', function () {
        toggleItemInputs($(this));
        updateSelectedCount();
        updateSelectAllCheckbox();
        calculateRentalCosts();
    });

    // Toggle item inputs based on checkbox
    function toggleItemInputs(checkbox) {
        const itemId = checkbox.data('item-id');
        const qtyInput = $(`.return-qty[data-item-id="${itemId}"]`);
        const damageInput = $(`.damage-fee[data-item-id="${itemId}"]`);
        const noteInput = $(`.item-note[data-item-id="${itemId}"]`);

        if (checkbox.is(':checked')) {
            if (qtyInput.length) {
                qtyInput.prop('disabled', false);
                qtyInput.attr('min', 0);
                qtyInput.val(1);
            }
            if (damageInput.length) damageInput.prop('disabled', false);
            if (noteInput.length) noteInput.prop('disabled', false);
        } else {
            if (qtyInput.length) {
                qtyInput.prop('disabled', true);
                qtyInput.val(0);
            }
            if (damageInput.length) {
                damageInput.prop('disabled', true);
                damageInput.val(0);
            }
            if (noteInput.length) {
                noteInput.prop('disabled', true);
                noteInput.val('');
            }
        }
        calculateRentalCosts();
    }

    // Update selected items count
    function updateSelectedCount() {
        const selectedCount = $('.item-checkbox:checked').length;
        $('#selectedItemsCount').text(selectedCount);
    }

    // Update select all checkbox
    function updateSelectAllCheckbox() {
        const totalCheckboxes = itemCheckboxes.length;
        const checkedCheckboxes = $('.item-checkbox:checked').length;
        const allChecked = totalCheckboxes === checkedCheckboxes;
        const someChecked = checkedCheckboxes > 0;

        if (selectAllCheckbox.length) {
            selectAllCheckbox.prop('checked', allChecked);
            selectAllCheckbox.prop('indeterminate', someChecked && !allChecked);
        }
    }

    // Calculate total damage fees from all items
    function calculateDamageFees() {
        let totalDamage = 0;
        $('.damage-fee').each(function () {
            if (!$(this).prop('disabled') && $(this).val()) {
                totalDamage += parseFloat($(this).val()) || 0;
            }
        });

        $('#totalDamageFee').text('$' + totalDamage.toFixed(1));
        return totalDamage;
    }

    // Calculate rental costs
    function calculateRentalCosts() {
        const returnDate = $('input[name="return_date"]').val();

        // Validate return date
        if (!returnDate) {
            console.log('Return date not selected');
            return;
        }

        // Validate rent data
        if (!rentData.rentDate || rentData.subTotal === 0) {
            console.error('Invalid rent data for calculation');
            return;
        }

        // Calculate total rent days
        const rentDate = new Date(rentData.rentDate);
        const returnDateObj = new Date(returnDate);

        // Handle invalid dates
        if (isNaN(rentDate.getTime()) || isNaN(returnDateObj.getTime())) {
            console.error('Invalid date format');
            return;
        }

        // Ensure return date is not before rent date
        if (returnDateObj < rentDate) {
            // If return date is before rent date, set it to rent date
            const rentDateStr = rentDate.toISOString().split('T')[0];
            $('input[name="return_date"]').val(rentDateStr);
            alert('Return date cannot be before rent date. Date reset to rent date.');
            return calculateRentalCosts(); // Recalculate with corrected date
        }

        const timeDiff = returnDateObj.getTime() - rentDate.getTime();
        const totalRentDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        // Ensure at least 1 day (if return date is same as rent date)
        const actualRentDays = totalRentDays < 1 ? 1 : totalRentDays;

        // Set total days for form submission
        $('#totalDays').val(actualRentDays);
        // Calculate costs
        const totalRentCost = actualRentDays * rentData.subTotal;
        const totalDamageFees = calculateDamageFees();
        const transportAmount = parseFloat($('#transport').val()) || 0;

        // Calculate sub_total (damage fees + total rent cost + transport)
        const subTotal = totalDamageFees + totalRentCost + transportAmount;

        // Calculate grand_total (deposit - sub_total)
        const grandTotal = (rentData.deposit + rentData.totalPayment) - subTotal;

        // Update UI with calculations
        updateCalculationDisplay({
            totalRentDays: actualRentDays,
            totalRentCost: totalRentCost,
            totalDamageFees: totalDamageFees,
            transportAmount: transportAmount,
            subTotal: subTotal,
            deposit: rentData.deposit,
            grandTotal: grandTotal
        });

        // Update refund/collect amounts based on grandTotal
        updateRefundCollectAmounts(grandTotal);
    }

    // Update the calculation display in the UI
    function updateCalculationDisplay(calculations) {
        // Remove any existing calculation summary
        $('#calculationSummary').remove();

        // Create fresh calculation summary
        const calculationSummary = $(`
            <div id="calculationSummary" class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                <h4 class="mb-4 text-sm font-medium text-gray-700 dark:text-gray-300">Rental Cost Calculation</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Rental Days:</span>
                        <span class="font-medium text-gray-900 dark:text-white">${calculations.totalRentDays} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Daily Rental Cost:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$${rentData.subTotal.toFixed(0)}/day</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Rental Cost:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$${calculations.totalRentCost.toFixed(0)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Damage Fees:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$${calculations.totalDamageFees.toFixed(1)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Transport:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$${calculations.transportAmount.toFixed(0)}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 dark:border-gray-700">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Sub-total:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$${calculations.subTotal.toFixed(0)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Deposit Paid:</span>
                        <span class="font-medium text-green-600 dark:text-green-400">$${calculations.deposit.toFixed(0)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Payment:</span>
                        <span class="font-medium text-green-600 dark:text-green-400">$${rentData.totalPayment.toFixed(0)}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 dark:border-gray-700">
                        <span class="font-medium ${calculations.grandTotal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">Balance:</span>
                        <span class="font-medium ${calculations.grandTotal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">
                            $${Math.abs(calculations.grandTotal).toFixed(0)} ${calculations.grandTotal >= 0 ? '(Refund)' : '(Collect)'}
                        </span>
                    </div>
                </div>
            </div>
        `);

        // Insert after the financial summary section
        $('div:contains("Transport Amount")').closest('.rounded-lg.border').after(calculationSummary);
    }

    // Update refund and collect amounts based on grandTotal
    function updateRefundCollectAmounts(grandTotal) {
        const refundInput = $('#refund_amount');
        const collectInput = $('#collect_amount');

        if (grandTotal > 0) {
            // Customer gets refund
            const refundAmount = grandTotal.toFixed(0);
            refundInput.val(refundAmount);
            collectInput.val(0);
        } else if (grandTotal < 0) {
            // Customer owes money
            const collectAmount = Math.abs(grandTotal).toFixed(0);
            collectInput.val(collectAmount);
            refundInput.val(0);
        } else {
            // Exactly zero
            refundInput.val(0);
            collectInput.val(0);
        }
    }

    // Listen to input changes for calculations
    $(document).on('input', '.damage-fee', function () {
        calculateRentalCosts();
    });

    $(document).on('input', '#transport', function () {
        calculateRentalCosts();
    });

    $(document).on('input', 'input[name="return_date"]', function () {
        // Remove future date restriction - allow any date
        calculateRentalCosts();
    });

    // Listen to quantity input changes
    $(document).on('input', '.return-qty', function () {
        const maxQty = parseInt($(this).attr('max'));
        const value = parseInt($(this).val()) || 0;

        if (value > maxQty) {
            $(this).val(maxQty);
            alert(`Cannot return more than ${maxQty} items for this product.`);
        }

        // Recalculate
        calculateRentalCosts();
    });

    // Form validation and submission
    $('#returnForm').on('submit', function (e) {
        // Prevent default submission
        e.preventDefault();

        // Check if at least one item is selected
        const selectedItems = $('.item-checkbox:checked');
        if (selectedItems.length === 0) {
            alert('Please select at least one item to return.');
            return false;
        }

        // Collect checked item IDs that have qty > 0
        const selectedItemIds = [];
        let totalQty = 0;

        selectedItems.each(function () {
            const itemId = $(this).data('item-id');
            const qtyInput = $(`.return-qty[data-item-id="${itemId}"]`);
            const qtyValue = parseInt(qtyInput.val()) || 0;

            if (qtyValue > 0) {
                selectedItemIds.push(itemId);
                totalQty += qtyValue;
            }
        });

        // Validate that we have at least one item with quantity > 0
        if (totalQty === 0) {
            alert('Please enter return quantities for selected items.');
            return false;
        }

        // Set the hidden field with selected item IDs
        $('#selectedItems').val(JSON.stringify(selectedItemIds));

        // Validate return date is not before rent date
        const returnDate = $('input[name="return_date"]');
        const rentDate = new Date(rentData.rentDate);
        const returnDateObj = new Date(returnDate.val());

        if (returnDateObj < rentDate) {
            alert('Return date cannot be before the rent date.');
            return false;
        }

        // Validate transport amount (can be zero or positive)
        const transportAmount = parseFloat($('#transport').val()) || 0;
        if (transportAmount < 0) {
            alert('Transport amount cannot be negative.');
            return false;
        }

        // Show confirmation with calculation summary
        const totalDamageFees = calculateDamageFees();
        const rentalDays = calculateRentalDays();
        const totalRentCost = rentalDays * rentData.subTotal;
        const grandTotal = rentData.deposit - (totalDamageFees + totalRentCost + transportAmount);

        const balanceType = grandTotal >= 0 ? 'refund' : 'payment';
        const balanceAmount = Math.abs(grandTotal).toFixed(0);

        const confirmMessage = `Are you sure you want to process this return?\n\n` +
            `Summary:\n` +
            `- Rental Days: ${rentalDays}\n` +
            `- Rental Cost: $${totalRentCost.toFixed(0)}\n` +
            `- Damage Fees: $${totalDamageFees.toFixed(1)}\n` +
            `- Transport: $${transportAmount.toFixed(0)}\n` +
            `- Sub-total: $${(totalRentCost + totalDamageFees + transportAmount).toFixed(0)}\n` +
            `- Deposit: $${rentData.deposit.toFixed(0)}\n` +
            `- Balance: $${balanceAmount} to ${balanceType}\n\n` +
            `Refund Amount: $${$('#refund_amount').val()}\n` +
            `Collect Amount: $${$('#collect_amount').val()}`;

        if (confirm(confirmMessage)) {
            // Submit the form normally
            $(this).off('submit').submit();
        }
    });

    // Helper function to calculate rental days
    function calculateRentalDays() {
        const returnDate = $('input[name="return_date"]').val();
        if (!returnDate) return 1;

        const rentDate = new Date(rentData.rentDate);
        const returnDateObj = new Date(returnDate);

        if (isNaN(rentDate.getTime()) || isNaN(returnDateObj.getTime())) {
            return 1;
        }

        // Ensure return date is not before rent date
        if (returnDateObj < rentDate) {
            return 1;
        }

        const timeDiff = returnDateObj.getTime() - rentDate.getTime();
        const totalRentDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        return totalRentDays < 1 ? 1 : totalRentDays;
    }

    // Initialize
    updateSelectedCount();
    calculateRentalCosts(); // Initial calculation
});