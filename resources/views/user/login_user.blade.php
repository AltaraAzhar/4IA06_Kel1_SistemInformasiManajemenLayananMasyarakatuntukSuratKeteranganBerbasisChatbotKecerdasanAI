    @extends('layouts.auth')

@section('title', 'Masuk - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('landing') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Beranda</span>
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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-2">Login</h1>
            <p class="text-gray-600 text-center mb-8 text-sm">
                Masuk ke akun Anda untuk mengakses layanan surat online
            </p>

            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Login Sebagai</label>
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        id="role-user" 
                        onclick="selectRole('user')"
                        class="role-btn active flex items-center justify-center space-x-2 py-3 px-4 rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-600 font-medium transition-all">
                        <i class="fas fa-user"></i>
                        <span>User</span>
                    </button>
                    <button 
                        id="role-admin" 
                        onclick="selectRole('admin')"
                        class="role-btn flex items-center justify-center space-x-2 py-3 px-4 rounded-lg border-2 border-gray-300 bg-white text-gray-600 font-medium transition-all hover:border-gray-400">
                        <i class="fas fa-shield-alt"></i>
                        <span>Admin</span>
                    </button>
                </div>
            </div>

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
            <form id="loginForm" method="POST" action="" class="space-y-5">
                @csrf
                <input type="hidden" name="role" id="selected-role" value="user">
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

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lupa password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Masukkan password"
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

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </button>
            </form>

            <!-- Separator -->
            <div class="my-6 text-center">
                <span class="text-gray-400 text-sm">Atau</span>
            </div>

            <!-- Register Link -->
            <div class="text-center mb-4">
                <p class="text-gray-600 text-sm mb-3">Belum punya akun?</p>
                <a 
                    href="{{ route('user.register') }}"
                    class="w-full block border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar Sekarang</span>
                </a>
            </div>
        </div>

            {{-- <!-- Demo Note -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800 text-sm text-center">
                    <strong>Demo:</strong> Pilih role yang sesuai, lalu gunakan email dan password yang telah Anda daftarkan
                </p> --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedRole = 'user';

    function selectRole(role) {
        selectedRole = role;
        
        // Update hidden input
        document.getElementById('selected-role').value = role;
        
        // Update form action
        const form = document.getElementById('loginForm');
        if (role === 'user') {
            form.action = '{{ route("user.login") }}';
        } else {
            form.action = '{{ route("admin.login") }}';
        }
        
        // Update buttons
        const userBtn = document.getElementById('role-user');
        const adminBtn = document.getElementById('role-admin');
        
        if (role === 'user') {
            userBtn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-600');
            userBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-600');
            adminBtn.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
            adminBtn.classList.add('border-gray-300', 'bg-white', 'text-gray-600');
        } else {
            adminBtn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-600');
            adminBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-600');
            userBtn.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
            userBtn.classList.add('border-gray-300', 'bg-white', 'text-gray-600');
        }
    }

    // Initialize form action on page load
    document.addEventListener('DOMContentLoaded', function() {
        selectRole('user');
    });

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

    // Form will submit normally to backend
    // No need to prevent default
</script>
@endpush
@endsection

