@extends('layouts.admin')

@section('title', 'Dashboard Admin - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-green-800 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-th text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Dashboard Admin</h1>
                    <p class="text-sm text-green-200">Kelurahan Pabuaran Mekar</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Selamat datang, <strong>{{ session('admin_authenticated') ? 'Admin Pabuaran Mekar' : (session('admin_email') ?? 'azmi') }}</strong></span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="bg-green-700 hover:bg-green-600 px-4 py-2 rounded-lg flex items-center space-x-2">
                        <span>Keluar</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Pengajuan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalPengajuan }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Menunggu</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $menunggu }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Diproses</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $diproses }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Selesai</p>
                        <p class="text-3xl font-bold text-green-600">{{ $selesai }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.dashboard') }}" id="searchForm" class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="Cari berdasarkan nomor, nama, atau jenis surat..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <select name="status" id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="menunggu" {{ ($status ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ ($status ?? '') === 'diproses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="revisi" {{ ($status ?? '') === 'revisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="selesai" {{ ($status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(($status ?? 'all') !== 'all' || !empty($search ?? ''))
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Letters Table Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Pengajuan Surat</h2>
                </div>

                <!-- Tabs -->
                <div class="flex space-x-4 border-b border-gray-200">
                    <a href="{{ route('admin.dashboard', ['status' => 'dalam_proses']) }}" class="px-4 py-2 flex items-center space-x-2 {{ ($status ?? 'dalam_proses') === 'dalam_proses' || ($status ?? 'dalam_proses') === 'all' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fas fa-clock"></i>
                        <span>Dalam Proses</span>
                        <span class="bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full ml-2">{{ $menunggu + $diproses + ($revisi ?? 0) }}</span>
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'selesai']) }}" class="px-4 py-2 flex items-center space-x-2 {{ ($status ?? '') === 'selesai' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Selesai</span>
                        <span class="bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded-full ml-2">{{ $selesai }}</span>
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengajuan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nomor_pengajuan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jenis_layanan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->user->name ?? $item->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->nik ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'menunggu' || $item->status === 'diajukan')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Menunggu</span>
                                @elseif($item->status === 'diproses')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                @elseif($item->status === 'revisi' || $item->status === 'direvisi')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Revisi</span>
                                @elseif($item->status === 'selesai')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $item->status ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-1">
                                    @php
                                        $currentStatus = $item->status ?? 'menunggu';
                                    @endphp
                                    
                                    @if($currentStatus === 'menunggu' || $currentStatus === 'diajukan')
                                        {{-- Status: Menunggu - Tampilkan tombol Proses, Dokumen, Riwayat, Revisi --}}
                                        <form method="POST" action="{{ route('admin.surat.process', $item->_id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                                <i class="fas fa-check"></i>
                                                <span>Proses</span>
                                            </button>
                                        </form>
                                        <button onclick="openDocumentModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-file"></i>
                                            <span>Dokumen</span>
                                        </button>
                                        <button onclick="openHistoryModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-clock"></i>
                                            <span>Riwayat</span>
                                        </button>
                                        <button onclick="openReviseModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Revisi</span>
                                        </button>
                                    @elseif($currentStatus === 'diproses')
                                        {{-- Status: Diproses - Tampilkan tombol Selesai, Dokumen, Riwayat, Revisi --}}
                                        <button onclick="openSelesaiModal('{{ $item->_id }}')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Selesai</span>
                                        </button>
                                        <button onclick="openDocumentModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-file"></i>
                                            <span>Dokumen</span>
                                        </button>
                                        <button onclick="openHistoryModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-clock"></i>
                                            <span>Riwayat</span>
                                        </button>
                                        <button onclick="openReviseModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Revisi</span>
                                        </button>
                                    @elseif($currentStatus === 'revisi' || $currentStatus === 'direvisi')
                                        {{-- Status: Revisi - Tampilkan tombol Revisi dan Dokumen --}}
                                        <button onclick="openReviseModal('{{ $item->_id }}')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Revisi</span>
                                        </button>
                                        <button onclick="openDocumentModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-file"></i>
                                            <span>Dokumen</span>
                                        </button>
                                        <button onclick="openHistoryModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-clock"></i>
                                            <span>Riwayat</span>
                                        </button>
                                    @elseif($currentStatus === 'selesai')
                                        {{-- Status: Selesai - Tampilkan tombol Detail --}}
                                        <button onclick="openDetailModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-eye"></i>
                                            <span>Detail</span>
                                        </button>
                                    @else
                                        {{-- Status tidak dikenal - Tampilkan tombol Detail sebagai fallback --}}
                                        <button onclick="openDetailModal('{{ $item->_id }}')" class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-eye"></i>
                                            <span>Detail</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-600 mb-2">Tidak ada data pengajuan</p>
                                    <p class="text-sm text-gray-500">
                                        @if(($status ?? 'all') !== 'all' || ($jenis_layanan ?? 'all') !== 'all')
                                            Data tidak ditemukan dengan filter yang dipilih. Silakan coba filter lain.
                                        @else
                                            Belum ada pengajuan surat yang terdaftar.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($pengajuan) && $pengajuan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pengajuan->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div id="documentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Dokumen Persyaratan</h3>
                <p class="text-sm text-gray-600 mt-1" id="modal-pengajuan-id"></p>
            </div>
            <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-gray-700">
                <span id="modal-doc-count">0</span> Dokumen Terupload • 
                Total: <span id="modal-total-size">0 Bytes</span>
            </p>
        </div>
        
        <div id="document-list" class="space-y-3 max-h-96 overflow-y-auto">
            <!-- Documents will be loaded here via AJAX -->
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="closeDocumentModal()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Revise Modal -->
<div id="reviseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Minta Revisi Pengajuan Surat</h3>
            <button onclick="closeReviseModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <p class="text-sm text-gray-600 mb-6">Masukkan keterangan revisi untuk pengajuan surat ini.</p>
        
        <!-- Pengajuan Details -->
        <div id="reviseModalContent" class="space-y-4 mb-6">
            <!-- Details will be loaded via AJAX -->
        </div>
        
        <form id="reviseForm" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan *</label>
                <textarea name="keterangan" rows="4" required placeholder="Masukkan keterangan revisi..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeReviseModal()" class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg">Batal</button>
                <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg">Kirim Revisi</button>
            </div>
        </form>
    </div>
</div>

<!-- Selesai Modal -->
<div id="selesaiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Selesaikan Pengajuan Surat</h3>
                <p class="text-sm text-gray-600 mt-1">Masukkan nomor surat dan upload file surat untuk menyelesaikan pengajuan ini.</p>
            </div>
            <button onclick="closeSelesaiModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="mb-4 p-3 bg-green-50 rounded-lg">
            <p class="text-sm text-gray-700">
                <i class="fas fa-upload text-green-600 mr-2"></i>
                Wajib upload file surat dan input nomor surat
            </p>
        </div>
        
        <form id="selesaiForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat *</label>
                <input type="text" name="nomor_surat" required placeholder="Contoh: 474/001/KEL-PM/2025" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                <p class="text-xs text-gray-500 mt-1">Input nomor surat sesuai dengan surat yang dibuat</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Surat (untuk Download Surat)</label>
                <p class="text-xs text-gray-500 mb-2">Upload file surat yang telah dibuat secara manual (PDF atau Word). Dilewati untuk layanan e-tiket.</p>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors">
                    <input type="file" name="file_surat" id="file_surat" accept=".pdf,.doc,.docx" class="hidden" onchange="handleFileSelect(this)">
                    <label for="file_surat" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600">Klik untuk upload atau drag & drop</p>
                        <p class="text-xs text-gray-500 mt-1">PDF atau Word (maksimal 10MB)</p>
                    </label>
                    <p id="file-name" class="text-sm text-green-600 mt-2 hidden"></p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeSelesaiModal()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Selesaikan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Detail Pengajuan Surat</h3>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap tentang pengajuan surat ini</p>
            </div>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div id="detailModalContent" class="space-y-4">
            <!-- Content will be loaded via AJAX -->
        </div>
        
        <div class="mt-6 flex justify-center">
            <button onclick="closeDetailModal()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- History Modal -->
<div id="historyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-history text-blue-600 text-xl"></i>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Riwayat Pengajuan</h3>
                    <p class="text-sm text-gray-600 mt-1">Seluruh riwayat pengajuan surat dari user ini</p>
                </div>
            </div>
            <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div id="historyModalContent">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDocumentModal(pengajuanId) {
        // Fetch dokumen via AJAX
        fetch(`/admin/surat/${pengajuanId}/documents`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-pengajuan-id').textContent = 'Pengajuan: ' + data.nomor_pengajuan;
                document.getElementById('modal-doc-count').textContent = data.dokumen.length;
                
                // Calculate total size
                let totalSize = 0;
                data.dokumen.forEach(doc => {
                    totalSize += doc.size || 0;
                });
                document.getElementById('modal-total-size').textContent = formatBytes(totalSize);
                
                // Render dokumen list
                const docList = document.getElementById('document-list');
                docList.innerHTML = '';
                
                if (data.dokumen.length === 0) {
                    docList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada dokumen</p>';
                } else {
                    data.dokumen.forEach((doc, index) => {
                        const docItem = document.createElement('div');
                        docItem.className = 'flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors';
                        const docType = (doc.type || 'pdf').toLowerCase();
                        const isPdf = docType === 'pdf' || doc.url.toLowerCase().endsWith('.pdf');
                        const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(docType);
                        
                        docItem.innerHTML = `
                            <div class="flex items-center space-x-3 flex-1">
                                <i class="fas fa-file-${isPdf ? 'pdf' : (isImage ? 'image' : 'alt')} text-blue-600 text-2xl"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">${doc.name || 'Dokumen ' + (index + 1)}</p>
                                    <p class="text-xs text-gray-500">${formatBytes(doc.size || 0)} • ${doc.type || 'file'}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="${doc.url}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg flex items-center space-x-2 transition-colors">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat</span>
                                </a>
                            </div>
                        `;
                        docList.appendChild(docItem);
                    });
                }
                
                document.getElementById('documentModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat dokumen');
            });
    }

    function closeDocumentModal() {
        document.getElementById('documentModal').classList.add('hidden');
    }

    function openReviseModal(pengajuanId) {
        document.getElementById('reviseForm').action = `/admin/surat/${pengajuanId}/revise`;
        
        // Fetch pengajuan details via AJAX
        fetch(`/admin/surat/${pengajuanId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const content = document.getElementById('reviseModalContent');
                const pengajuan = data.pengajuan || data;
                
                // Format status badge
                let statusBadge = 'bg-gray-100 text-gray-800';
                let statusText = pengajuan.status || '-';
                if (pengajuan.status === 'menunggu') {
                    statusBadge = 'bg-orange-100 text-orange-800';
                    statusText = 'Menunggu';
                } else if (pengajuan.status === 'diproses') {
                    statusBadge = 'bg-blue-100 text-blue-800';
                    statusText = 'Diproses';
                } else if (pengajuan.status === 'revisi') {
                    statusBadge = 'bg-yellow-100 text-yellow-800';
                    statusText = 'Revisi';
                } else if (pengajuan.status === 'selesai') {
                    statusBadge = 'bg-green-100 text-green-800';
                    statusText = 'Selesai';
                }
                
                // Format dokumen
                let dokumenHtml = '';
                if (pengajuan.dokumen && pengajuan.dokumen.length > 0) {
                    dokumenHtml = '<div class="mt-4 pt-4 border-t border-gray-200"><p class="text-sm font-medium text-gray-700 mb-2">Dokumen:</p><div class="space-y-2">';
                    pengajuan.dokumen.forEach((doc, index) => {
                        let docUrl = doc.url || '#';
                        if (!docUrl || docUrl === '#') {
                            if (doc.path) {
                                if (doc.path.startsWith('http')) {
                                    docUrl = doc.path;
                                } else {
                                    docUrl = '/storage/' + doc.path.replace(/^\/+/, '');
                                }
                            }
                        }
                        const docName = doc.name || `Dokumen ${index + 1}`;
                        dokumenHtml += `
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-file text-blue-600"></i>
                                    <span class="text-sm text-gray-700">${docName}</span>
                                </div>
                                <a href="${docUrl}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">Lihat</a>
                            </div>
                        `;
                    });
                    dokumenHtml += '</div></div>';
                }
                
                const createdDate = pengajuan.created_at ? new Date(pengajuan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                
                content.innerHTML = `
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">No. Pengajuan</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.nomor_pengajuan || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Jenis Surat</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.jenis_layanan || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Pemohon</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.nama || (pengajuan.user?.name || '-')}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">NIK</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.nik || '-'}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.alamat || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Nomor HP</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.no_hp || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Email</p>
                                <p class="text-sm font-semibold text-gray-900">${pengajuan.user?.email || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Tanggal Pengajuan</p>
                                <p class="text-sm font-semibold text-gray-900">${createdDate}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Status</p>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full ${statusBadge}">${statusText}</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-1">Keterangan</p>
                            <p class="text-sm text-gray-700">${pengajuan.keterangan || pengajuan.catatan_admin || 'Menunggu verifikasi dokumen oleh admin'}</p>
                        </div>
                        ${dokumenHtml}
                    </div>
                `;
                
                document.getElementById('reviseModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data pengajuan: ' + (error.message || 'Terjadi kesalahan. Silakan refresh halaman dan coba lagi.'));
            });
    }

    function closeReviseModal() {
        document.getElementById('reviseModal').classList.add('hidden');
        document.getElementById('reviseForm').reset();
        const content = document.getElementById('reviseModalContent');
        if (content) content.innerHTML = '';
    }

    function openSelesaiModal(pengajuanId) {
        document.getElementById('selesaiForm').action = `/admin/surat/${pengajuanId}/selesai`;
        document.getElementById('selesaiModal').classList.remove('hidden');
    }

    function closeSelesaiModal() {
        document.getElementById('selesaiModal').classList.add('hidden');
        document.getElementById('selesaiForm').reset();
        document.getElementById('file-name').classList.add('hidden');
    }

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('file-name').textContent = 'File dipilih: ' + fileName;
            document.getElementById('file-name').classList.remove('hidden');
        }
    }

    function openDetailModal(pengajuanId) {
        // Fetch pengajuan details via AJAX
        fetch(`/admin/surat/${pengajuanId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                const pengajuan = data.pengajuan || data;
                const content = document.getElementById('detailModalContent');
                
                // Format tanggal
                const createdDate = pengajuan.created_at ? new Date(pengajuan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                const processedDate = pengajuan.processed_at ? new Date(pengajuan.processed_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                
                // Status badge
                const statusBadge = getStatusBadge(pengajuan.status);
                const statusText = getStatusText(pengajuan.status);
                
                // Check if status is selesai (read-only)
                const isReadOnly = pengajuan.status === 'selesai';
                
                // Jenis akses (e-ticket atau download surat)
                const jenisAkses = pengajuan.etiket ? 'e-Ticket' : (pengajuan.file_surat ? 'Download Surat' : 'Download Surat');
                
                // Dokumen list
                let dokumenHtml = '';
                if (pengajuan.dokumen && pengajuan.dokumen.length > 0) {
                    pengajuan.dokumen.forEach((doc, index) => {
                        let docUrl = doc.url || '#';
                        if (!docUrl || docUrl === '#') {
                            if (doc.path) {
                                if (doc.path.startsWith('http')) {
                                    docUrl = doc.path;
                                } else {
                                    docUrl = '/storage/' + String(doc.path).replace(/^\/+/, '');
                                }
                            }
                        }
                        const docName = doc.name || `Dokumen ${index + 1}`;
                        const docSize = doc.size ? (doc.size / 1024).toFixed(2) + ' KB' : '0 Bytes';
                        const docType = doc.type || 'pdf';
                        dokumenHtml += `
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-${docType === 'pdf' ? 'pdf' : 'image'} text-blue-600 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">${docName}</p>
                                        <p class="text-xs text-gray-500">${docSize} • application/${docType}</p>
                                    </div>
                                </div>
                                <a href="${docUrl}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                    Lihat
                                </a>
                            </div>
                        `;
                    });
                } else {
                    dokumenHtml = '<p class="text-sm text-gray-500">Tidak ada dokumen</p>';
                }
                
                content.innerHTML = `
                    <!-- Informasi Umum -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Informasi Umum</h3>
                            ${isReadOnly ? '<span class="px-3 py-1 text-sm font-semibold rounded-full ' + statusBadge + '">' + statusText + '</span>' : ''}
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">No. Pengajuan</p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.nomor_pengajuan || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Jenis Surat</p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.jenis_layanan || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1 flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Tanggal Pengajuan
                                </p>
                                <p class="text-base font-semibold text-gray-900">${createdDate}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Jenis Akses</p>
                                <p class="text-base font-semibold text-gray-900">${jenisAkses}</p>
                            </div>
                            ${!isReadOnly ? `
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 mb-2">Status Pengajuan</p>
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full ${statusBadge}">${statusText}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Data Pemohon -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Data Pemohon</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.nama || (pengajuan.user?.name || '-')}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">NIK</p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.nik || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Alamat</p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.alamat || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1 flex items-center">
                                    <i class="fas fa-phone mr-1"></i>
                                    Nomor HP
                                </p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.no_hp || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1 flex items-center">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Email
                                </p>
                                <p class="text-base font-semibold text-gray-900">${pengajuan.user?.email || '-'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dokumen Persyaratan -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Dokumen Persyaratan</h3>
                        <div class="space-y-3">
                            ${dokumenHtml}
                        </div>
                    </div>
                    
                    <!-- Status & Keterangan -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Status & Keterangan</h3>
                        <div class="space-y-3">
                            ${!isReadOnly ? `
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Status Pengajuan</p>
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full ${statusBadge}">${statusText}</span>
                            </div>
                            ` : ''}
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Keterangan</p>
                                <p class="text-base text-gray-900">${pengajuan.catatan_admin || pengajuan.keterangan || 'Tidak ada keterangan'}</p>
                            </div>
                            ${pengajuan.nomor_surat ? `
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nomor Surat</p>
                                <p class="text-base font-semibold text-blue-600">${pengajuan.nomor_surat}</p>
                            </div>
                            ` : ''}
                            ${pengajuan.etiket ? `
                            <div>
                                <p class="text-sm text-gray-600 mb-1">e-Ticket / Nomor Antrian</p>
                                <p class="text-base font-semibold text-blue-600">${pengajuan.etiket}</p>
                            </div>
                            ` : ''}
                            ${isReadOnly ? `
                            <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pengajuan ini telah selesai dan tidak dapat diubah lagi. User telah menerima notifikasi.
                                </p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                document.getElementById('detailModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat riwayat pengajuan');
            });
    }
    
    function getActionText(action) {
        const actions = {
            'process': 'Diproses',
            'revise': 'Revisi',
            'selesai': 'Selesai',
        };
        return actions[action] || ucfirst(action);
    }
    
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModalContent').innerHTML = '';
    }

    function openHistoryModal(pengajuanId) {
        // Fetch pengajuan details and history
        Promise.all([
            fetch(`/admin/surat/${pengajuanId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(r => r.json()),
            fetch(`/admin/surat/${pengajuanId}/history`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(r => r.json())
        ])
            .then(([pengajuanData, historyData]) => {
                const pengajuan = pengajuanData.pengajuan || pengajuanData;
                const history = historyData.history || [];
                const content = document.getElementById('historyModalContent');
                
                const statusBadge = getStatusBadge(pengajuan.status);
                const statusText = getStatusText(pengajuan.status);
                const createdDate = pengajuan.created_at ? new Date(pengajuan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
                
                // Count status (for now just show current status)
                const counts = {
                    menunggu: pengajuan.status === 'menunggu' ? 1 : 0,
                    diproses: pengajuan.status === 'diproses' ? 1 : 0,
                    selesai: pengajuan.status === 'selesai' ? 1 : 0,
                    revisi: pengajuan.status === 'revisi' ? 1 : 0,
                    total: 1
                };
                content.innerHTML = `
                    <!-- Pemohon Info Card -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-green-600"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Nama Pemohon</p>
                                    <p class="text-sm font-semibold text-gray-900">${pengajuan.nama || pengajuan.user?.name || '-'}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-green-600"></i>
                                <div>
                                    <p class="text-xs text-gray-600">NIK</p>
                                    <p class="text-sm font-semibold text-gray-900">${pengajuan.nik || '-'}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-file-alt text-green-600"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Total Pengajuan</p>
                                    <p class="text-sm font-semibold text-gray-900">1 pengajuan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Summary Cards -->
                    <div class="grid grid-cols-5 gap-2 mb-4">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Menunggu</p>
                            <p class="text-lg font-bold text-yellow-600">${counts.menunggu}</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Diproses</p>
                            <p class="text-lg font-bold text-blue-600">${counts.diproses}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Selesai</p>
                            <p class="text-lg font-bold text-green-600">${counts.selesai}</p>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Revisi</p>
                            <p class="text-lg font-bold text-orange-600">${counts.revisi}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Total</p>
                            <p class="text-lg font-bold text-gray-600">${counts.total}</p>
                        </div>
                    </div>
                    
                    <!-- History Table -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pengajuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. S</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${history.length > 0 ? history.map((h, idx) => `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">${pengajuan.nomor_pengajuan || '-'}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-file-alt text-blue-600"></i>
                                                    <span>${pengajuan.jenis_layanan || '-'}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span>${h.created_at ? new Date(h.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : createdDate}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadge(h.status_baru || pengajuan.status)}">${getStatusText(h.status_baru || pengajuan.status)}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">${pengajuan.nomor_surat || pengajuan.etiket || '-'}</td>
                                            <td class="px-4 py-3">
                                                <span class="text-xs text-gray-500">${getActionText(h.action || '-')}</span>
                                            </td>
                                        </tr>
                                    `).join('') : `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">${pengajuan.nomor_pengajuan || '-'}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-file-alt text-blue-600"></i>
                                                    <span>${pengajuan.jenis_layanan || '-'}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span>${createdDate}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusBadge}">${statusText}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">${pengajuan.nomor_surat || pengajuan.etiket || '-'}</td>
                                            <td class="px-4 py-3">
                                                <span class="text-xs text-gray-500">-</span>
                                            </td>
                                        </tr>
                                    `}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- History Timeline -->
                    ${history.length > 0 ? `
                    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Timeline Perubahan Status</h4>
                        <div class="space-y-3">
                            ${history.map((h, idx) => `
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-history text-blue-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">${getActionText(h.action)}</p>
                                        <p class="text-xs text-gray-600 mt-1">${h.catatan || 'Tidak ada catatan'}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ${h.created_at ? new Date(h.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadge(h.status_baru)}">
                                            ${getStatusText(h.status_baru)}
                                        </span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                `;
                
                document.getElementById('historyModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat riwayat pengajuan');
            });
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
        document.getElementById('historyModalContent').innerHTML = '';
    }

    function getStatusBadge(status) {
        if (status === 'menunggu') return 'bg-yellow-100 text-yellow-800';
        if (status === 'diproses') return 'bg-blue-100 text-blue-800';
        if (status === 'revisi') return 'bg-orange-100 text-orange-800';
        if (status === 'selesai') return 'bg-green-100 text-green-800';
        return 'bg-gray-100 text-gray-800';
    }

    function getStatusText(status) {
        if (status === 'menunggu') return 'Menunggu';
        if (status === 'diproses') return 'Diproses';
        if (status === 'revisi') return 'Revisi';
        if (status === 'selesai') return 'Selesai';
        return status;
    }
    
    function getActionText(action) {
        const actions = {
            'process': 'Diproses',
            'revise': 'Revisi',
            'selesai': 'Selesai',
        };
        return actions[action] || (action ? action.charAt(0).toUpperCase() + action.slice(1) : '-');
    }


    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Auto-submit filter when dropdown changes
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const searchForm = document.getElementById('searchForm');

        if (statusFilter && searchForm) {
            statusFilter.addEventListener('change', function() {
                // If "Semua Status" is selected, clear search input
                if (this.value === 'all') {
                    const searchInput = searchForm.querySelector('input[name="search"]');
                    if (searchInput) {
                        searchInput.value = '';
                    }
                }
                // Force submit the form
                searchForm.submit();
            });
        }
    });
</script>
@endpush
@endsection

