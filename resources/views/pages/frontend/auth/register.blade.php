@extends('layouts.frontend.app')

@section('title', 'Register - KFS Scaffolding')

@section('content')
    <div id="page-register" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12 lg:py-20">
            <div class="w-full max-w-md lg:max-w-5xl">

                {{-- Logo --}}
                <div class="text-center mb-8">
                    <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-2 group mb-6">
                        <img src="{{ asset('images/logo/kfs-logo-teal.svg') }}" alt="Logo" width="150" height="90" />
                    </a>
                    <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Create account</h1>
                    <p class="text-gray-600">Join KFS to access rental quotes and more</p>
                </div>

                {{-- Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6 lg:p-10 shadow-sm">

                    <form method="POST" action="{{ route('customers.storeRegistration') }}" class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-6"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="status" value="1">

                        {{-- Name Fields --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-orange-500">*</span></label>
                                <input type="text" name="name"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500 @error('name') border-red-500 @enderror" placeholder="U Aung Myo"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-orange-500">*</span></label>
                            <input type="email" name="email" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500 @error('email') border-red-500 @enderror"
                                placeholder="john@company.com" value="{{ old('email') }}" required>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500" placeholder="09XXXXXXXXX"
                                value="{{ old('phone_number') }}">
                        </div>

                        {{-- Company --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name (Optional)</label>
                            <input type="text" name="company_name" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500"
                                placeholder="Your company (optional)" value="{{ old('company_name') }}">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-orange-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="regPassword"
                                    class="w-full px-4 py-2.5 pr-12 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                    placeholder="Min 8 characters" required>
                                <button type="button" onclick="togglePasswordVis('regPassword', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-orange-500">*</span></label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500"
                                placeholder="Re-enter password" required>
                        </div>

                        <!-- Profile Picture (Dropzone) -->
                        <div class="mt-2 lg:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Profile Picture
                            </label>

                            <!-- REAL hidden file input for Laravel form submission -->
                            <input type="file" name="profile_picture" id="real_profile_picture_input" class="hidden"
                                accept="image/png,image/jpeg,image/webp,image/svg+xml" />

                            <!-- Dropzone Component -->
                            <div x-data="{
                                isDragging: false,
                                files: [],
                                handleDrop(e) {
                                    this.isDragging = false;
                                    const droppedFiles = Array.from(e.dataTransfer.files);
                                    this.handleFiles(droppedFiles);
                                },
                                handleFiles(selectedFiles) {
                                    const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                                    const validFiles = selectedFiles.filter(file => validTypes.includes(file.type));
                            
                                    if (validFiles.length > 0) {
                                        // Only allow one file (as per your model)
                                        const file = validFiles[0];
                                        this.files = [file];
                            
                                        // Update the real hidden file input so Laravel receives it
                                        const realInput = document.getElementById('real_profile_picture_input');
                                        const dt = new DataTransfer();
                                        dt.items.add(file);
                                        realInput.files = dt.files;
                                    }
                                },
                                removeFile() {
                                    this.files = [];
                                    const realInput = document.getElementById('real_profile_picture_input');
                                    realInput.value = ''; // Clear the actual file input
                                }
                            }"
                                class="transition border border-gray-300 border-dashed cursor-pointer rounded-xl hover:border-orange-500">
                                <div @drop.prevent="handleDrop($event)" @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false" @click="$refs.fileInput.click()"
                                    :class="isDragging
                                        ?
                                        'border-orange-500 bg-orange-50' :
                                        'border-gray-300 bg-gray-50'"
                                    class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer"
                                    id="demo-upload">
                                    <!-- Hidden File Input (for JS interaction only) -->
                                    <input x-ref="fileInput" type="file"
                                        @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                                        accept="image/png,image/jpeg,image/webp,image/svg+xml" class="hidden" @click.stop />

                                    <div class="flex flex-col items-center m-0">
                                        <!-- Icon Container -->
                                        <div class="mb-[22px] flex justify-center">
                                            <div
                                                class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700">
                                                <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Text Content -->
                                        <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl">
                                            <span x-show="!isDragging">Drag & Drop Profile Picture</span>
                                            <span x-show="isDragging" x-cloak>Drop Files Here</span>
                                        </h4>

                                        <span
                                            class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700">
                                            Drag and drop your PNG, JPG, WebP, SVG images here or browse
                                        </span>

                                        <span class="font-medium underline text-theme-sm text-brand-500">
                                            Browse File
                                        </span>
                                    </div>
                                </div>

                                <!-- File Preview List -->
                                <div x-show="files.length > 0"
                                    class="mt-4 p-4 border-t border-gray-200" x-cloak>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-3">Uploaded File:
                                    </h5>
                                    <ul class="space-y-2">
                                        <template x-for="(file, index) in files" :key="index">
                                            <li
                                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center gap-3">
                                                    <!-- Image Preview -->
                                                    <template x-if="file.type.startsWith('image/')">
                                                        <img :src="URL.createObjectURL(file)"
                                                            class="w-10 h-10 rounded-full object-cover" alt="Preview">
                                                    </template>
                                                    <template x-if="!file.type.startsWith('image/')">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </template>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm text-gray-700"
                                                            x-text="file.name"></span>
                                                        <span class="text-xs text-gray-500"
                                                            x-text="(file.size / 1024).toFixed(2) + ' KB'"></span>
                                                    </div>
                                                </div>
                                                <button @click.stop="removeFile()" type="button"
                                                    class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <!-- Validation Error -->
                            @error('profile_picture')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terms --}}
                        <div class="flex items-start gap-3 pt-1 lg:col-span-2">
                            <input type="checkbox" name="agree_terms" id="agreeTerms"
                                class="w-4 h-4 mt-0.5 rounded border-gray-300 bg-white text-orange-500 focus:ring-orange-500 cursor-pointer"
                                required>
                            <div class="text-xs text-gray-600">
                                <label for="agreeTerms" class="cursor-pointer">I agree to the</label>
                                <button type="button" data-modal-open="termsConditionModal"
                                    class="text-orange-600 hover:text-orange-700">Terms & Conditions</button>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full py-3.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2 lg:col-span-2">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Create Account
                        </button>
                    </form>

                    {{-- Login Link --}}
                    <div class="mt-6 pt-5 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('customers.showLoginFrom') }}"
                                class="text-orange-600 font-semibold hover:text-orange-700 transition-colors ml-1">
                                Sign in
                            </a>
                        </p>
                    </div>
                </div>

                {{-- Back to Home --}}
                <p class="text-center text-xs text-gray-500 mt-6">
                    <a href="{{ route('frontend.home') }}" class="hover:text-gray-700 transition-colors">← Back to
                        Home</a>
                </p>
            </div>
        </div>
    </div>

    @include('pages.frontend.customers.partials.terms-condition-modal')
@endsection

@push('scripts')
    <script>
        function togglePasswordVis(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i data-lucide="eye" class="w-4 h-4"></i>';
            }
            lucide.createIcons();
        }

        function openRegisterModal(modalId) {
            $(`#${modalId}`).removeClass('hidden').addClass('flex');
            $('body').addClass('overflow-hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeRegisterModal(modalId) {
            const modal = $(`#${modalId}`);
            modal.addClass('hidden').removeClass('flex');

            if ($('[data-modal].flex').length === 0) {
                $('body').removeClass('overflow-hidden');
            }
        }

        $(document).ready(function() {
            $('[data-modal-open]').on('click', function() {
                openRegisterModal($(this).data('modal-open'));
            });

            $('[data-modal-close]').on('click', function() {
                closeRegisterModal($(this).data('modal-close'));
            });

            $('[data-modal]').on('click', function(event) {
                if (event.target === this) {
                    closeRegisterModal($(this).attr('id'));
                }
            });

            $(document).on('keydown', function(event) {
                if (event.key === 'Escape') {
                    $('[data-modal]').each(function() {
                        closeRegisterModal($(this).attr('id'));
                    });
                }
            });
        });
    </script>
@endpush
