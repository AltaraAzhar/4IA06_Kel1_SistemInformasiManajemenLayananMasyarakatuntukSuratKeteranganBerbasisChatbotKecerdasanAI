@extends('layouts.admin')

@section('title', 'Riwayat Pengajuan - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-green-800 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-green-200 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold">Riwayat Pengajuan</h1>
                    <p class="text-sm text-green-200">Kelurahan Pabuaran Mekar</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Selamat datang <strong>{{ session('admin_authenticated') ? 'Admin Pabuaran Mekar' : (session('admin_email') ?? 'Admin') }}</strong></span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="bg-green-700 hover:bg-green-600 px-4 py-2 rounded-lg flex items-center space-x-2">
                        <span>Keluar</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Pengajuan Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pengajuan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor Pengajuan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Layanan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->jenis_layanan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pemohon</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->user->name ?? $pengajuan->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status Saat Ini</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        @if($pengajuan->status === 'menunggu') bg-orange-100 text-orange-800
                        @elseif($pengajuan->status === 'diproses') bg-blue-100 text-blue-800
                        @elseif($pengajuan->status === 'revisi') bg-yellow-100 text-yellow-800
                        @elseif($pengajuan->status === 'selesai') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @if($pengajuan->status === 'menunggu') Menunggu
                        @elseif($pengajuan->status === 'diproses') Diproses
                        @elseif($pengajuan->status === 'revisi') Revisi
                        @elseif($pengajuan->status === 'selesai') Selesai
                        @else Tidak Diketahui
                        @endif
                    </span>
                </div>
                @if($pengajuan->etiket)
                <div>
                    <p class="text-sm text-gray-600">Nomor Antrian / E-Ticket</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $pengajuan->etiket }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d F Y, H:i') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Riwayat Perubahan Status</h2>
            
            @if($history && $history->count() > 0)
            <div class="space-y-4">
                @foreach($history as $item)
                <div class="flex items-start space-x-4 p-4 border-l-4 
                    @if($item->status_baru === 'menunggu') border-orange-500
                    @elseif($item->status_baru === 'diproses') border-blue-500
                    @elseif($item->status_baru === 'revisi') border-yellow-500
                    @elseif($item->status_baru === 'selesai') border-green-500
                    @else border-gray-500
                    @endif bg-gray-50 rounded-lg">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($item->action === 'process') bg-blue-100 text-blue-600
                            @elseif($item->action === 'selesai') bg-green-100 text-green-600
                            @elseif($item->action === 'revise') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-600
                            @endif">
                            @if($item->action === 'process')
                                <i class="fas fa-cog"></i>
                            @elseif($item->action === 'selesai')
                                <i class="fas fa-check-circle"></i>
                            @elseif($item->action === 'revise')
                                <i class="fas fa-redo"></i>
                            @else
                                <i class="fas fa-info-circle"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                @if($item->action === 'process')
                                    Pengajuan Diproses
                                @elseif($item->action === 'selesai')
                                    Pengajuan Selesai
                                @elseif($item->action === 'revise')
                                    Pengajuan Dikembalikan untuk Revisi
                                @else
                                    Perubahan Status
                                @endif
                            </h3>
                            <span class="text-sm text-gray-500">
                                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Status: </span>
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($item->status_lama === 'menunggu') bg-orange-100 text-orange-800
                                @elseif($item->status_lama === 'diproses') bg-blue-100 text-blue-800
                                @elseif($item->status_lama === 'revisi') bg-yellow-100 text-yellow-800
                                @elseif($item->status_lama === 'selesai') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($item->status_lama === 'menunggu') Menunggu
                                @elseif($item->status_lama === 'diproses') Diproses
                                @elseif($item->status_lama === 'revisi') Revisi
                                @elseif($item->status_lama === 'selesai') Selesai
                                @else Tidak Diketahui
                                @endif
                            </span>
                            <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($item->status_baru === 'menunggu') bg-orange-100 text-orange-800
                                @elseif($item->status_baru === 'diproses') bg-blue-100 text-blue-800
                                @elseif($item->status_baru === 'revisi') bg-yellow-100 text-yellow-800
                                @elseif($item->status_baru === 'selesai') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($item->status_baru === 'menunggu') Menunggu
                                @elseif($item->status_baru === 'diproses') Diproses
                                @elseif($item->status_baru === 'revisi') Revisi
                                @elseif($item->status_baru === 'selesai') Selesai
                                @else Tidak Diketahui
                                @endif
                            </span>
                        </div>

                        @if($item->catatan)
                        <div class="mt-2 p-3 bg-white rounded border border-gray-200">
                            <p class="text-sm text-gray-600 mb-1">Catatan:</p>
                            <p class="text-sm text-gray-900">{{ $item->catatan }}</p>
                        </div>
                        @endif

                        @if(isset($item->admin_id))
                            @if($item->admin_id === 'admin_env')
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-user-shield mr-1"></i>
                                Oleh: Admin (via .env)
                            </div>
                            @else
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-user-shield mr-1"></i>
                                Oleh: Admin (ID: {{ $item->admin_id }})
                            </div>
                            @endif
                        @else
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-user-shield mr-1"></i>
                            Oleh: Admin
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada riwayat perubahan status</p>
                <p class="text-gray-400 text-sm mt-2">Riwayat akan muncul setelah ada perubahan status pengajuan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

