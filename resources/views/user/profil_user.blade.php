@extends('layouts.app')

@section('title', 'Profil Saya - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Saya</h1>
            <p class="text-gray-600">Informasi akun dan data pribadi Anda</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-12 text-center">
                <div class="w-24 h-24 bg-white rounded-full mx-auto flex items-center justify-center mb-4">
                    <i class="fas fa-user text-blue-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
                <p class="text-blue-100">{{ $user->email }}</p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mx-8 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Details -->
            <div class="px-8 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
                    <button type="button" id="btn-edit-profil" onclick="toggleEditMode()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </button>
                </div>
                
                <form method="POST" action="{{ route('user.profil.update') }}" id="profil-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nama Lengkap -->
                        <div class="flex items-start border-b border-gray-200 pb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></p>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="text-lg font-semibold text-gray-900 w-full border-0 focus:ring-0 p-0 view-mode" 
                                    readonly required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start border-b border-gray-200 pb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                            </div>
                        </div>

                        <!-- NIK -->
                        <div class="flex items-start border-b border-gray-200 pb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-id-card text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">NIK</p>
                                <input type="text" name="nik_or_nip" id="nik_or_nip" value="{{ old('nik_or_nip', $user->nik_or_nip ?? '') }}" 
                                    class="text-lg font-semibold text-gray-900 w-full border-0 focus:ring-0 p-0 view-mode" 
                                    placeholder="Masukkan NIK (16 digit)" 
                                    maxlength="16" 
                                    pattern="[0-9]{16}"
                                    readonly>
                                <p class="text-xs text-gray-500 mt-1">NIK harus 16 digit angka</p>
                            </div>
                        </div>

                        <!-- No HP -->
                        <div class="flex items-start border-b border-gray-200 pb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-orange-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">No. HP/WhatsApp</p>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                                    class="text-lg font-semibold text-gray-900 w-full border-0 focus:ring-0 p-0 view-mode" 
                                    placeholder="08xxxxxxxxxx" readonly>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-start border-b border-gray-200 pb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Alamat</p>
                                <textarea name="address" id="address" rows="2" 
                                    class="text-lg font-semibold text-gray-900 w-full border-0 focus:ring-0 p-0 view-mode resize-none" 
                                    placeholder="Masukkan alamat lengkap" readonly>{{ old('address', $user->address ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Password (hanya muncul saat edit) -->
                        <div id="password-section" class="hidden">
                            <div class="flex items-start border-b border-gray-200 pb-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-lock text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-1">Password Baru (opsional)</p>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" 
                                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            placeholder="Kosongkan jika tidak ingin mengubah password">
                                        <button type="button" onclick="togglePasswordVisibility('password', 'password-toggle-icon')" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <i id="password-toggle-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                                </div>
                            </div>
                            <div class="flex items-start pb-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-lock text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-1">Konfirmasi Password</p>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            placeholder="Konfirmasi password baru">
                                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'password-confirmation-toggle-icon')" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <i id="password-confirmation-toggle-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Role</p>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (hidden by default) -->
                    <div id="form-actions" class="hidden mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="cancelEdit()" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
let isEditMode = false;

function toggleEditMode() {
    isEditMode = !isEditMode;
    const inputs = document.querySelectorAll('.view-mode');
    const btnEdit = document.getElementById('btn-edit-profil');
    const formActions = document.getElementById('form-actions');
    const passwordSection = document.getElementById('password-section');
    const nikInput = document.getElementById('nik_or_nip');
    
    if (isEditMode) {
        // Enable edit mode
        inputs.forEach(input => {
            input.classList.remove('border-0', 'focus:ring-0', 'p-0');
            input.classList.add('border', 'border-gray-300', 'rounded-lg', 'px-4', 'py-2', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
            input.removeAttribute('readonly');
        });
        
        // Validasi khusus untuk NIK: hanya angka, maksimal 16 digit
        if (nikInput) {
            nikInput.addEventListener('input', function(e) {
                // Hanya izinkan angka
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                // Batasi maksimal 16 digit
                if (e.target.value.length > 16) {
                    e.target.value = e.target.value.slice(0, 16);
                }
            });
        }
        
        btnEdit.classList.add('hidden');
        formActions.classList.remove('hidden');
        passwordSection.classList.remove('hidden');
    } else {
        // Disable edit mode
        inputs.forEach(input => {
            input.classList.add('border-0', 'focus:ring-0', 'p-0');
            input.classList.remove('border', 'border-gray-300', 'rounded-lg', 'px-4', 'py-2', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
            input.setAttribute('readonly', 'readonly');
        });
        
        btnEdit.classList.remove('hidden');
        formActions.classList.add('hidden');
        passwordSection.classList.add('hidden');
    }
}

function cancelEdit() {
    // Reset form
    document.getElementById('profil-form').reset();
    toggleEditMode();
}

function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection

