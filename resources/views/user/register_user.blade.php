@extends('layouts.auth')

@section('title', 'Daftar - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('user.login') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Login</span>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/Lambang_Kabupaten_Bogor.svg') }}" 
                     alt="Logo Kabupaten Bogor" 
                     class="h-20 w-20 object-contain">
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-2">Daftar</h1>
            <p class="text-gray-600 text-center mb-8 text-sm">
                Buat akun baru untuk mengakses layanan surat online
            </p>


            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form id="registerForm" method="POST" action="{{ route('user.register') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="role" value="user">
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="nama" 
                            name="name"
                            placeholder="Nama lengkap sesuai KTP"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                    </div>
                </div>

                <!-- NIK/NIP -->
                <div>
                    <label for="nik" id="nik-label" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="nik" 
                            name="nik_or_nip"
                            placeholder="16 digit NIK"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            placeholder="nama@email.com"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                    </div>
                </div>

                <!-- Nomor HP/WhatsApp -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP/WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input 
                            type="tel" 
                            id="no_hp" 
                            name="phone"
                            placeholder="08xxxxxxxxxx"
                            pattern="[0-9]{10,13}"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                        <textarea 
                            id="alamat" 
                            name="address"
                            placeholder="Alamat lengkap (opsional)"
                            rows="3"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            maxlength="20"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', 'password-toggle-icon')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                            aria-label="Toggle password visibility"
                        >
                            <i id="password-toggle-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            minlength="8"
                            maxlength="20"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation', 'password-confirmation-toggle-icon')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                            aria-label="Toggle password confirmation visibility"
                        >
                            <i id="password-confirmation-toggle-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            required
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-600">
                            Saya menyetujui syarat dan ketentuan serta kebijakan privasi yang berlaku
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    {{-- href="{{ route('l') }}" --}}
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <!-- Separator -->
            <div class="my-6 text-center">
                <span class="text-gray-400 text-sm">Atau</span>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <a 
                    href="{{ route('user.login') }}"
                    class="w-full block border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-right"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-2">
            <p class="text-blue-800 text-sm">
                <strong>Catatan:</strong>
            </p>
            <ul class="text-blue-800 text-sm space-y-1 ml-4 list-disc">
                <li>Registrasi ini untuk akun masyarakat umum (User)</li>
                <li>Gunakan NIK 16 digit (Nomor Induk Kependudukan)</li>
                <li>Untuk akses admin, silakan hubungi administrator</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        // Validate password match
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            return;
        }
        
        // Form will submit to backend
    });
</script>
@endpush
@endsection

