@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Quotation Preview</h1>
                    <p class="text-gray-600">Quotation No: {{ $quotationData['quotation_no'] }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('quotation.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        ← Back to Form
                    </a>
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        🖨 Print
                    </button>
                    <form method="POST" action="{{ route('quotation.download') }}" id="downloadForm">
                        @csrf
                        @foreach($quotationData as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $index => $item)
                                    @foreach($item as $subkey => $subvalue)
                                        <input type="hidden" name="{{ $key }}[{{ $index }}][{{ $subkey }}]" value="{{ $subvalue }}">
                                    @endforeach
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            📄 Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quotation Preview -->
        <div class="bg-white shadow-lg p-8">
            @include('pages.admin.pdf.quotation', ['quotationData' => $quotationData])
            
            <!-- Email Form -->
            <div class="mt-8 p-6 border border-gray-200 rounded-lg bg-blue-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📧 Send Quotation via Email</h3>
                <form method="POST" action="{{ route('quotation.email') }}" id="emailPreviewForm">
                    @csrf
                    @foreach($quotationData as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $index => $item)
                                @foreach($item as $subkey => $subvalue)
                                    <input type="hidden" name="{{ $key }}[{{ $index }}][{{ $subkey }}]" value="{{ $subvalue }}">
                                @endforeach
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Recipient Email *
                            </label>
                            <input type="email" name="recipient_email" value="{{ $quotationData['client_email'] }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg email-input">
                            <div class="email-error mt-1 text-xs text-red-600 hidden"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Subject *
                            </label>
                            <input type="text" name="subject" value="Quotation {{ $quotationData['quotation_no'] }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg subject-input">
                            <div class="subject-error mt-1 text-xs text-red-600 hidden"></div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Message
                            </label>
                            <textarea name="message" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                placeholder="Add a custom message to your email..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" id="sendEmailBtn"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Send Quotation via Email
                        </button>
                        <div class="mt-2 success-message text-sm text-green-600 hidden"></div>
                        <div class="mt-2 error-message text-sm text-red-600 hidden"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Email form validation and AJAX submission
    $('#emailPreviewForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#sendEmailBtn');
        const originalText = submitBtn.html();
        const successMsg = $('.success-message');
        const errorMsg = $('.error-message');
        
        // Reset messages
        successMsg.addClass('hidden').text('');
        errorMsg.addClass('hidden').text('');
        $('.email-error, .subject-error').addClass('hidden').text('');
        $('.email-input, .subject-input').removeClass('border-red-300');
        
        // Validate
        let isValid = true;
        const email = form.find('input[name="recipient_email"]').val();
        const subject = form.find('input[name="subject"]').val();
        
        if (!email) {
            isValid = false;
            form.find('input[name="recipient_email"]').addClass('border-red-300');
            form.find('.email-error').removeClass('hidden').text('Recipient email is required.');
        } else if (!isValidEmail(email)) {
            isValid = false;
            form.find('input[name="recipient_email"]').addClass('border-red-300');
            form.find('.email-error').removeClass('hidden').text('Please enter a valid email address.');
        }
        
        if (!subject) {
            isValid = false;
            form.find('input[name="subject"]').addClass('border-red-300');
            form.find('.subject-error').removeClass('hidden').text('Subject is required.');
        }
        
        if (!isValid) return;
        
        // Show loading
        submitBtn.prop('disabled', true).html(`
            <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sending...
        `);
        
        // Submit via AJAX
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                successMsg.removeClass('hidden').text('Quotation sent successfully!');
                form[0].reset();
            },
            error: function(xhr) {
                let errorMessage = 'Failed to send email. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.recipient_email) {
                        form.find('input[name="recipient_email"]').addClass('border-red-300');
                        form.find('.email-error').removeClass('hidden').text(errors.recipient_email[0]);
                    }
                    if (errors.subject) {
                        form.find('input[name="subject"]').addClass('border-red-300');
                        form.find('.subject-error').removeClass('hidden').text(errors.subject[0]);
                    }
                }
                errorMsg.removeClass('hidden').text(errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});
</script>
@endpush