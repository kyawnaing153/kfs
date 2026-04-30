<div id="changePasswordModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 px-4 py-6"
    data-modal>
    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-gray-200">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                <p class="mt-1 text-sm text-gray-500">Update your customer account password.</p>
            </div>
            <button type="button"
                class="modal-close rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                data-modal-close="changePasswordModal" aria-label="Close change password modal">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('customers.password.update') }}" method="POST" class="space-y-4 px-6 py-5">
            @csrf
            @method('PUT')

            <div>
                <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" autocomplete="current-password"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500"
                    required>
            </div>

            <div>
                <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" id="newPassword" name="password" autocomplete="new-password"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500"
                    required minlength="6">
            </div>

            <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" id="confirmPassword" name="password_confirmation" autocomplete="new-password"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500"
                    required minlength="6">
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
                <button type="button"
                    class="modal-close px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900"
                    data-modal-close="changePasswordModal">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
