@extends('layouts.admin')

@section('title', 'Detail Pengajuan - Admin')

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
                    <h1 class="text-xl font-bold">Detail Pengajuan</h1>
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

        <!-- Status Badge -->
        <div class="mb-6">
            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                @if($pengajuan->status === 'menunggu') bg-orange-100 text-orange-800
                @elseif($pengajuan->status === 'diproses') bg-blue-100 text-blue-800
                @elseif($pengajuan->status === 'revisi') bg-yellow-100 text-yellow-800
                @elseif($pengajuan->status === 'selesai') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800
                @endif">
                @if($pengajuan->status === 'menunggu') Status: Menunggu
                @elseif($pengajuan->status === 'diproses') Status: Diproses
                @elseif($pengajuan->status === 'revisi') Status: Revisi
                @elseif($pengajuan->status === 'selesai') Status: Selesai
                @else Status: Tidak Diketahui
                @endif
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pengajuan -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pengajuan</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nomor Pengajuan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jenis Layanan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->jenis_layanan ?? '-' }}</p>
                        </div>
                        @if($pengajuan->etiket)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nomor Antrian / E-Ticket</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $pengajuan->etiket }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Pengajuan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d F Y, H:i') : '-' }}</p>
                        </div>
                        @if($pengajuan->processed_at)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Diproses</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->processed_at->format('d F Y, H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Data Pemohon -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Data Pemohon</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->user->name ?? $pengajuan->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">NIK</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Alamat</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->alamat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nomor Telepon</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dokumen -->
                @if($pengajuan->dokumen && count($pengajuan->dokumen) > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Dokumen Pendukung</h2>
                    <div class="space-y-3">
                        @foreach($pengajuan->dokumen as $index => $doc)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-{{ ($doc['type'] ?? 'pdf') === 'pdf' ? 'pdf' : 'image' }} text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $doc['name'] ?? 'Dokumen ' . ($index + 1) }}</p>
                                    <p class="text-xs text-gray-500">
                                        FILE • {{ isset($doc['size']) && $doc['size'] > 0 ? number_format($doc['size'] / 1024, 2) . ' KB' : '0 Bytes' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.surat.documents.view', ['id' => $pengajuan->_id, 'index' => $index]) }}" target="_blank" 
                                   class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">Buka</a>
                                <a href="{{ route('admin.surat.documents.view', ['id' => $pengajuan->_id, 'index' => $index]) }}" download 
                                   class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">Download</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Catatan Admin -->
                @if($pengajuan->catatan_admin)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Catatan Admin</h2>
                    <p class="text-gray-700">{{ $pengajuan->catatan_admin }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        @if($pengajuan->status === 'diajukan')
                        <form method="POST" action="{{ route('admin.surat.process', $pengajuan->_id) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2">
                                <i class="fas fa-check"></i>
                                <span>Proses</span>
                            </button>
                        </form>
                        @elseif($pengajuan->status === 'diproses')
                        <button onclick="openDocumentModal('{{ $pengajuan->_id }}')" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-file"></i>
                            <span>Lihat Dokumen</span>
                        </button>
                        <div class="w-full bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Untuk menyelesaikan pengajuan, gunakan tombol <strong>Selesai</strong> di Dashboard Admin (agar bisa input nomor surat & upload file surat).
                        </div>
                        @endif
                        
                        <a href="{{ route('admin.surat.history', $pengajuan->_id) }}" 
                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-clock"></i>
                            <span>Riwayat</span>
                        </a>
                        
                        
                        <button onclick="openReviseModal('{{ $pengajuan->_id }}')" 
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-redo"></i>
                            <span>Revisi</span>
                        </button>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">Informasi</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Klik "Riwayat" untuk melihat history lengkap</li>
                        <li>• Klik "Lihat Dokumen" untuk review file</li>
                        <li>• Status dapat diubah sesuai alur</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include modals -->
@include('admin.partials.modals')

@endsection

