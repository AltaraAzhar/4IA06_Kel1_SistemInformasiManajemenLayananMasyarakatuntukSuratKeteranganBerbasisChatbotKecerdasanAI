@extends('layouts.user')

@section('title', 'Dashboard User - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Dashboard (GREEN GRADIENT - FULL WIDTH) -->
    <div class="bg-gradient-to-r from-green-800 to-green-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between py-6">
                <!-- Left Side: Title -->
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold">Dashboard User</h1>
                    <p class="text-green-200 text-sm">Layanan Surat Kelurahan Pabuaran Mekar</p>
                </div>

                <!-- Right Side: Welcome + Keluar -->
                <div class="flex items-center space-x-4">
                    <p class="text-white text-sm">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
                    <a href="{{ route('landing') }}" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Navigation Tabs (WITH ICONS) -->
        <div class="mb-8 border-b border-gray-200">
            <div class="flex space-x-8">
                <a href="{{ route('user.dashboard') }}" 
                   class="px-4 py-3 border-b-2 border-green-600 text-green-600 font-semibold flex items-center space-x-2">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Pengajuan -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Total Pengajuan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalPengajuan }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Diajukan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $menunggu }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Diproses -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Diproses</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $diproses }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-spinner text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Revisi -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Direvisi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $revisi }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $selesai }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Aksi Cepat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('user.pengajuan') }}" 
                       class="flex items-center space-x-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Ajukan Surat</p>
                            <p class="text-sm text-gray-600">Buat pengajuan baru</p>
                        </div>
                    </a>
                    <a href="{{ route('user.surat.status') }}" 
                       class="flex items-center space-x-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Cek Status</p>
                            <p class="text-sm text-gray-600">Lihat status pengajuan</p>
                        </div>
                    </a>
                    <a href="{{ route('user.profil') }}" 
                       class="flex items-center space-x-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Profil Saya</p>
                            <p class="text-sm text-gray-600">Kelola data profil</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-history text-gray-600 mr-2"></i>
                    Pengajuan Terbaru
                </h2>
            </div>
            <div class="p-6">
                @if($recentPengajuan->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPengajuan as $pengajuan)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $pengajuan->jenis_layanan }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Nomor:</span> {{ $pengajuan->nomor_pengajuan ?? 'N/A' }}
                                        @if($pengajuan->etiket)
                                            | <span class="font-medium">E-Tiket:</span> {{ $pengajuan->etiket }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $pengajuan->created_at ? $pengajuan->created_at->format('d M Y H:i') : 'N/A' }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    @php
                                        $status = $pengajuan->status ?? 'diajukan';
                                        $badgeClass = match($status) {
                                            'diajukan', 'menunggu' => 'bg-orange-100 text-orange-800',
                                            'diproses' => 'bg-blue-100 text-blue-800',
                                            'direvisi', 'revisi' => 'bg-red-100 text-red-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                        $statusText = match($status) {
                                            'diajukan', 'menunggu' => 'Diajukan',
                                            'diproses' => 'Diproses',
                                            'direvisi', 'revisi' => 'Direvisi',
                                            'selesai' => 'Selesai',
                                            default => 'Tidak Diketahui',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            @if($pengajuan->keterangan && in_array($status, ['direvisi', 'revisi']))
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <strong>Catatan Revisi:</strong> {{ $pengajuan->keterangan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 font-medium mb-2">Belum ada pengajuan</p>
                        <p class="text-gray-500 text-sm mb-4">Mulai dengan mengajukan surat baru</p>
                        <a href="{{ route('user.pengajuan') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Ajukan Surat Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

