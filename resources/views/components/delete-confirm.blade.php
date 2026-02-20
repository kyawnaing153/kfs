{{-- Create a components folder and add this file: components/delete-confirm.blade.php --}}

@props([
    'action',
    'method' => 'DELETE',
    'message' => 'Are you sure you want to delete this item?',
    'class' => '',
    'buttonText' => 'Delete',
    'buttonClass' => 'flex w-full items-center gap-2 px-4 py-2 text-sm text-red-700 hover:bg-red-100 dark:text-red-300 dark:hover:bg-red-700',
    'iconClass' => 'fas fa-trash text-red-500',
])

<form method="POST" action="{{ $action }}" class="delete-form {{ $class }}">
    @csrf
    @method($method)
    <button type="button" 
            onclick="confirmDelete(this, '{{ $message }}')"
            class="delete-confirm-btn {{ $buttonClass }}"
            role="menuitem">
        <i class="{{ $iconClass }}"></i>
        {{ $buttonText }}
    </button>
</form>

@push('scripts')
<script>
    function confirmDelete(button, message) {
        Swal.fire({
            title: 'Confirm Delete',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = button.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
@endpush