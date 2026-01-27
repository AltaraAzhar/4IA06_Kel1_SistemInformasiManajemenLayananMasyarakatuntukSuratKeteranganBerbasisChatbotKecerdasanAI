@extends('layouts.admin')

@section('title', 'Pengajuan Surat - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-green-800 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h1 class="text-xl font-bold">Dashboard Admin</h1>
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
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Pengajuan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
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
                        <p class="text-3xl font-bold text-orange-600">{{ $stats['menunggu'] ?? 0 }}</p>
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
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['diproses'] ?? 0 }}</p>
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
                        <p class="text-3xl font-bold text-green-600">{{ $stats['selesai'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.surat.index') }}" class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan nomor, nama, atau jenis surat..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ ($status ?? 'dalam_proses') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="dalam_proses" {{ ($status ?? 'dalam_proses') === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="menunggu" {{ ($status ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ ($status ?? '') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="revisi" {{ ($status ?? '') === 'revisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="selesai" {{ ($status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Pengajuan Surat</h2>
                    <div class="flex gap-2">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                            <i class="fas fa-download"></i>
                            <span>Export CSV</span>
                        </button>
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                            <i class="fas fa-chart-line"></i>
                            <span>Log Aktivitas</span>
                        </button>
                    </div>
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
                                @if($item->status === 'menunggu')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Menunggu</span>
                                @elseif($item->status === 'diproses')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                @elseif($item->status === 'revisi')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Revisi</span>
                                @elseif($item->status === 'selesai')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $item->status ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($item->status === 'menunggu')
                                        <form method="POST" action="{{ route('admin.surat.process', $item->_id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                                <i class="fas fa-check"></i>
                                                <span>Proses</span>
                                            </button>
                                        </form>
                                        <button onclick="openDocumentModal('{{ $item->_id }}')" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-file"></i>
                                            <span>Dokumen</span>
                                        </button>
                                    @elseif($item->status === 'diproses')
                                        <button onclick="openDocumentModal('{{ $item->_id }}')" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                            <i class="fas fa-file"></i>
                                            <span>Dokumen</span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.surat.selesai', $item->_id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Selesai</span>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.surat.history', $item->_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                        <i class="fas fa-clock"></i>
                                        <span>Riwayat</span>
                                    </a>
                                    
                                    
                                    <button onclick="openReviseModal('{{ $item->_id }}')" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs flex items-center space-x-1">
                                        <i class="fas fa-redo"></i>
                                        <span>Revisi</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data pengajuan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengajuan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pengajuan->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Include modals -->
@include('admin.partials.modals')

@endsection

