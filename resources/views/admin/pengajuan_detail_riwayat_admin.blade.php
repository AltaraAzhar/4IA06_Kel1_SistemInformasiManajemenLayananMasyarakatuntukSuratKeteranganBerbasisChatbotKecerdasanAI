@extends('layouts.admin')

@section('title', 'Detail Riwayat Pengajuan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-green-800 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-history text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Detail Riwayat Pengajuan</h1>
                    <p class="text-sm text-green-200">Kelurahan Pabuaran Mekar</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-green-700 hover:bg-green-600 rounded-lg flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Pengajuan Detail -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pengajuan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">No. Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Jenis Surat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->jenis_layanan ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Nama Pemohon</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->user->name ?? $pengajuan->nama ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $pengajuan->status_badge }}">
                            {{ $pengajuan->status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- All Pengajuan from User -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Semua Pengajuan dari User Ini</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pengajuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($allPengajuan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->nomor_pengajuan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->jenis_layanan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge }}">
                                        {{ $item->status_text }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada pengajuan lain
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- History Timeline -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Perubahan Status</h2>
                @if($history->count() > 0)
                <div class="space-y-4">
                    @foreach($history as $item)
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ ucfirst($item->action ?? '-') }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $item->catatan ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($item->status_baru ?? '-') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Belum ada riwayat perubahan</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
