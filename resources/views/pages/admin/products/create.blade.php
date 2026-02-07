@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Add New Product" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
        <x-common.component-card title="Product Information">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Row 1: Product Name & Type -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Product Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            required placeholder="Enter product name" />
                        @error('product_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Product Type<span class="text-red-500">*</span>
                        </label>
                        <select name="product_type"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            required>
                            <option value="">Select Product Type</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type }}" {{ old('product_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Description -->
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Enter product description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 3: Tags -->
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tags
                    </label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                        @forelse($tags as $tag)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                    {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                            </label>
                        @empty
                            <div class="col-span-full">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No tags available. Create tags first.</p>
                            </div>
                        @endforelse
                    </div>
                    @error('tags')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 4: Thumbnail Image & Status -->
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Thumbnail Image -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Product Thumbnail
                        </label>

                        <!-- Hidden file input -->
                        <input type="file" name="thumb" id="real_thumb_input" class="hidden"
                            accept="image/png,image/jpeg,image/webp,image/gif" />

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
                                const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
                                const validFiles = selectedFiles.filter(file => validTypes.includes(file.type));
                        
                                if (validFiles.length > 0) {
                                    const file = validFiles[0];
                                    this.files = [file];
                        
                                    const realInput = document.getElementById('real_thumb_input');
                                    const dt = new DataTransfer();
                                    dt.items.add(file);
                                    realInput.files = dt.files;
                                }
                            },
                            removeFile() {
                                this.files = [];
                                const realInput = document.getElementById('real_thumb_input');
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
                                class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer">
                                <!-- Hidden File Input (for JS interaction only) -->
                                <input x-ref="fileInput" type="file"
                                    @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                                    accept="image/png,image/jpeg,image/webp,image/gif" class="hidden" @click.stop />

                                <div class="flex flex-col items-center m-0">
                                    <!-- Icon Container -->
                                    <div class="mb-[22px] flex justify-center">
                                        <div
                                            class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                            <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Text Content -->
                                    <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                                        <span x-show="!isDragging">Drag & Drop Thumbnail</span>
                                        <span x-show="isDragging" x-cloak>Drop Here</span>
                                    </h4>

                                    <span
                                        class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                                        PNG, JPG, WebP, GIF up to 2MB
                                    </span>

                                    <span class="font-medium underline text-theme-sm text-brand-500">
                                        Browse File
                                    </span>
                                </div>
                            </div>

                            <!-- File Preview List -->
                            <div x-show="files.length > 0" class="mt-4 p-4 border-t border-gray-200 dark:border-gray-700"
                                x-cloak>
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Thumbnail Preview:</h5>
                                <ul class="space-y-2">
                                    <template x-for="(file, index) in files" :key="index">
                                        <li
                                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <template x-if="file.type.startsWith('image/')">
                                                    <img :src="URL.createObjectURL(file)" class="w-12 h-12 rounded-lg object-cover" alt="Preview">
                                                </template>
                                                <template x-if="!file.type.startsWith('image/')">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </template>
                                                <div class="flex flex-col">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300"
                                                        x-text="file.name"></span>
                                                    <span class="text-xs text-gray-500" x-text="(file.size / 1024).toFixed(2) + ' KB'"></span>
                                                </div>
                                            </div>
                                            <button @click.stop="removeFile()" type="button"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
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

                        @error('thumb')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status & Feature -->
                    <div class="space-y-4">
                        <!-- Status -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Status
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Feature -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Feature on Rent Page
                            </label>
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_feature" value="0">
                                    <input type="checkbox" name="is_feature" value="1" {{ old('is_feature') == '1' ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        Mark as featured
                                    </span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Featured products will be highlighted on the rent page
                            </p>
                            @error('is_feature')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Note about Variants -->
                <div class="mt-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                Product Variants & Pricing
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                <p>
                                    After creating the product, you can add variants (sizes, colors, etc.) and set pricing 
                                    for each variant (sale price and/or rental prices with different durations).
                                </p>
                                <p class="mt-1">
                                    You'll be redirected to manage variants after product creation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Create Product
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Products
                    </a>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection

@push('scripts')
<script>
    // File validation
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('real_thumb_input');
        const dropzone = document.querySelector('[x-data]');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('Only PNG, JPG, WebP, and GIF files are allowed');
                        this.value = '';
                        return;
                    }
                }
            });
        }
    });
</script>
@endpush