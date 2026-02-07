@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Edit Customer: ' . $customer->name" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <x-common.component-card title="Edit Customer Information">
            <form method="POST" action="{{ route('customers.update', $customer->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Current Profile Picture -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Current Profile Picture
                    </label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $customer->customerprofile() }}" 
                             alt="{{ $customer->name }}"
                             class="h-24 w-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-700">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Current profile picture. Upload a new one to replace it.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Row 1: Name & Email -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Customer Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            required />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email<span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            required />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Phone Number & Company Name -->
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Phone Number
                        </label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('phone_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Company Name
                        </label>
                        <input type="text" name="company_name" value="{{ old('company_name', $customer->company_name) }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('company_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Address -->
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Address
                    </label>
                    <textarea name="address" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 4: Profile Picture Upload & Status -->
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Profile Picture Upload -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            New Profile Picture (Optional)
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
                                    const file = validFiles[0];
                                    this.files = [file];
                        
                                    const realInput = document.getElementById('real_profile_picture_input');
                                    const dt = new DataTransfer();
                                    dt.items.add(file);
                                    realInput.files = dt.files;
                                }
                            },
                            removeFile() {
                                this.files = [];
                                const realInput = document.getElementById('real_profile_picture_input');
                                realInput.value = '';
                            }
                        }"
                            class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500">
                            <div @drop.prevent="handleDrop($event)" @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false" @click="$refs.fileInput.click()"
                                :class="isDragging
                                    ?
                                    'border-brand-500 bg-gray-100 dark:bg-gray-800' :
                                    'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
                                class="dropzone rounded-xl border-dashed border-gray-300 p-4 lg:p-6 transition-colors cursor-pointer"
                                id="demo-upload">
                                <!-- Hidden File Input (for JS interaction only) -->
                                <input x-ref="fileInput" type="file"
                                    @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                                    accept="image/png,image/jpeg,image/webp,image/svg+xml" class="hidden" @click.stop />

                                <div class="flex flex-col items-center m-0">
                                    <!-- Icon Container -->
                                    <div class="mb-3 flex justify-center">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                            <svg class="fill-current" width="24" height="24" viewBox="0 0 29 28"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Text Content -->
                                    <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                                        <span x-show="!isDragging">Drag & Drop New Picture</span>
                                        <span x-show="isDragging" x-cloak>Drop Files Here</span>
                                    </h4>

                                    <span class="text-center mb-3 block w-full max-w-[250px] text-xs text-gray-700 dark:text-gray-400">
                                        PNG, JPG, WebP, SVG up to 2MB
                                    </span>

                                    <span class="font-medium underline text-xs text-brand-500">
                                        Browse File
                                    </span>
                                </div>
                            </div>

                            <!-- File Preview List -->
                            <div x-show="files.length > 0" class="mt-3 p-3 border-t border-gray-200 dark:border-gray-700"
                                x-cloak>
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">New Picture:</h5>
                                <ul class="space-y-2">
                                    <template x-for="(file, index) in files" :key="index">
                                        <li
                                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <template x-if="file.type.startsWith('image/')">
                                                    <img :src="URL.createObjectURL(file)" class="w-8 h-8 rounded-full object-cover" alt="Preview">
                                                </template>
                                                <template x-if="!file.type.startsWith('image/')">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </template>
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-700 dark:text-gray-300"
                                                        x-text="file.name"></span>
                                                    <span class="text-xs text-gray-500" x-text="(file.size / 1024).toFixed(2) + ' KB'"></span>
                                                </div>
                                            </div>
                                            <button @click.stop="removeFile()" type="button"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        @error('profile_picture')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status<span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            required>
                            <option value="1" {{ old('status', $customer->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $customer->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Update Section (Optional) -->
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Update Password (Optional)</h4>
                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Leave password fields blank if you don't want to change the password.
                    </p>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                New Password
                            </label>
                            <input type="password" name="password"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Leave blank to keep current" />
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Confirm new password" />
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Update Customer
                    </button>
                    <a href="{{ route('customers.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Customers
                    </a>
                    <a href="{{ route('customers.show', $customer->id) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Details
                    </a>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection