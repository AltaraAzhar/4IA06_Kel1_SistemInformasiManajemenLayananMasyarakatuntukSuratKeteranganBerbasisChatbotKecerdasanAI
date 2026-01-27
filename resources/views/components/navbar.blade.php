<nav class="fixed top-0 left-0 right-0 bg-white z-50 border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ auth()->check() ? route('user.dashboard') : route('landing') }}" class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/Lambang_Kabupaten_Bogor.svg') }}" 
                         alt="Logo Kabupaten Bogor" 
                         class="h-14 w-14 object-contain">
                </div>
                <div>
                    <div class="text-gray-900 font-bold text-lg">Kelurahan Pabuaran Mekar</div>
                    <div class="text-gray-600 text-xs">Kec. Cibinong, Kab. Bogor</div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    @if(auth()->user()->role === 'user')
                        {{-- User dengan role 'user' --}}
                        <a href="{{ route('user.dashboard') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                            Dashboard
                        </a>
                        <a href="{{ route('user.pengajuan') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                            Pengajuan Surat
                        </a>
                        <a href="{{ route('user.surat.status') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                            Status Pengajuan
                        </a>
                    @elseif(auth()->user()->role === 'admin')
                        {{-- Admin - tidak menampilkan menu pengajuan --}}
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                            Dashboard Admin
                        </a>
                    @endif
                    
                    {{-- Tombol Logout - Mudah diakses --}}
                    @if(auth()->user()->role === 'user')
                        <form method="POST" action="{{ route('user.logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center space-x-2 px-4 py-2 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium rounded-lg transition-colors border border-red-200"
                                    title="Keluar dari akun"
                                    aria-label="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @elseif(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center space-x-2 px-4 py-2 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium rounded-lg transition-colors border border-red-200"
                                    title="Keluar dari akun"
                                    aria-label="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @endif
                    
                    {{-- Dropdown Akun - Hanya menampilkan informasi user --}}
                    <div class="relative group">
                        <button class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition"
                                aria-label="Menu Akun"
                                aria-expanded="false">
                            <i class="fas fa-user-circle text-gray-600 text-xl"></i>
                            <span class="text-gray-800 font-medium">Akun</span>
                            <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                        </button>
                        
                        {{-- Dropdown Menu --}}
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden group-hover:block z-50">
                            <div class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- User belum login --}}
                    <a href="{{ route('landing') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                        Beranda
                    </a>
                    <a href="{{ route('layanan') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                        Layanan
                    </a>
                    <a href="{{ route('kontak') }}" class="text-gray-800 hover:text-blue-600 font-medium transition">
                        Kontak
                    </a>
                    
                    <a href="{{ route('user.login') }}" class="flex items-center gap-2 px-4 py-2 border border-black text-black rounded-lg hover:bg-black/10 font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('user.register') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition hover:opacity-90" style="background-color: #E9A500; color: black;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                        <span>Daftar</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-800 p-2">
                <i id="menu-icon" class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-4 space-y-2">
            @auth
                @if(auth()->user()->role === 'user')
                    {{-- User dengan role 'user' --}}
                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.pengajuan') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-file-alt mr-2"></i>Pengajuan Surat
                    </a>
                    <a href="{{ route('user.surat.status') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-list mr-2"></i>Status Pengajuan
                    </a>
                    
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    <form method="POST" action="{{ route('user.logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @elseif(auth()->user()->role === 'admin')
                    {{-- Admin - tidak menampilkan menu pengajuan --}}
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-home mr-2"></i>Dashboard Admin
                    </a>
                    
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endif
            @else
                {{-- User belum login --}}
                <a href="{{ route('landing') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('layanan') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-list mr-2"></i>Layanan
                </a>
                <a href="{{ route('kontak') }}" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-phone mr-2"></i>Kontak
                </a>
                
                <div class="border-t border-gray-200 my-2"></div>
                
                <a href="{{ route('user.login') }}" class="flex items-center justify-center gap-2 border border-black text-black py-3 rounded-lg font-medium hover:bg-black/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Masuk</span>
                </a>
                <a href="{{ route('user.register') }}" class="flex items-center justify-center gap-2 py-3 rounded-lg font-medium transition hover:opacity-90 mt-2" style="background-color: #E9A500; color: black;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                    </svg>
                    <span>Daftar</span>
                </a>
            @endauth
        </div>
    </div>
</nav>  

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');
        menu?.classList.toggle('hidden');
        icon?.classList.toggle('fa-bars');
        icon?.classList.toggle('fa-times');
    });
</script>

