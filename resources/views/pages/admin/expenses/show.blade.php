@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="View Expense Details" />

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <!-- Main Content -->
        <div class="xl:col-span-2">
            <x-common.component-card title="Expense Information" class="h-full">
                <!-- Header with Amount -->
                <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $expense->expense_title }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ date('F d, Y', strtotime($expense->expense_date)) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                            Ks {{ number_format($expense->amount, 1) }}
                        </div>
                        @if ($expense->status == 1)
                            <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                Active
                            </span>
                        @else
                            <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="space-y-6">
                    <!-- Note Section -->
                    @if($expense->note)
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Note
                        </label>
                        <div class="rounded-lg bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            {{ $expense->note }}
                        </div>
                    </div>
                    @endif

                    <!-- Metadata Section -->
                    <div class="grid grid-cols-1 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Created
                            </label>
                            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $expense->created_at ? date('F d, Y \a\t h:i A', strtotime($expense->created_at)) : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Last Updated
                            </label>
                            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ $expense->updated_at ? date('F d, Y \a\t h:i A', strtotime($expense->updated_at)) : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </x-common.component-card>
        </div>

        <!-- Sidebar -->
        <div class="xl:col-span-1">
            <!-- Action Card -->
            <x-common.component-card title="Actions" class="mb-6">
                <div class="space-y-3">
                    <a href="{{ route('expenses.edit', $expense->id) }}"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-brand-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    
                    <a href="{{ route('expenses.index') }}"
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-2 text-gray-500 dark:bg-gray-800 dark:text-gray-400">or</span>
                        </div>
                    </div>

                    <x-delete-confirm :action="route('expenses.destroy', $expense->id)" 
                        :message="json_encode('Are you sure you want to delete expense ' . $expense->expense_title . '? This action cannot be undone.')"
                        buttonClass="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-red-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </x-delete-confirm>
                </div>
            </x-common.component-card>
        </div>
    </div>
@endsection