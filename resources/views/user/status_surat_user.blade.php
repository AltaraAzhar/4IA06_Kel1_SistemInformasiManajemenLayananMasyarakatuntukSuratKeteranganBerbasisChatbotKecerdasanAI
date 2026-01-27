@extends('layouts.app')

@section('title', 'Status Pengajuan - Kelurahan Pabuaran Mekar')

@section('content')
<div class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Pengajuan Surat</h1>
            <p class="text-gray-600">Pantau status pengajuan surat Anda di sini</p>
        </div>

        <!-- Status List -->
        @if($pengajuan && $pengajuan->count() > 0)
            <div class="space-y-4">
                @foreach($pengajuan as $item)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border-l-4 border-blue-600">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <!-- Info Section -->
                            <div class="flex-1 mb-4 md:mb-0">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->jenis_layanan ?? '-' }}</h3>
                                        </div>
                                        @if(!empty($item->etiket))
                                            <p class="text-sm text-blue-700 font-semibold mb-2">
                                                <i class="fas fa-ticket-alt mr-1"></i>
                                                <span class="font-medium">Etiket / Nomor Antrian:</span> 
                                                <span class="font-bold">{{ $item->etiket }}</span>
                                            </p>
                                        @endif
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                                            <div>
                                                <i class="fas fa-calendar mr-1 text-gray-400"></i>
                                                <span class="font-medium">Tanggal Pengajuan:</span> 
                                                {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div class="flex flex-col items-start md:items-end space-y-2">
                                @php
                                    // Normalize status - handle all possible status values
                                    $rawStatus = $item->status ?? 'menunggu';
                                    
                                    // Map all possible status values to standard status
                                    $status = match(strtolower($rawStatus)) {
                                        'menunggu', 'diajukan' => 'menunggu',
                                        'diproses' => 'diproses',
                                        'revisi', 'direvisi' => 'revisi',
                                        'selesai' => 'selesai',
                                        default => 'menunggu', // Default to menunggu instead of unknown
                                    };
                                    
                                    $statusBadge = match($status) {
                                        'menunggu' => 'bg-orange-100 text-orange-800',
                                        'diproses' => 'bg-blue-100 text-blue-800',
                                        'revisi' => 'bg-red-100 text-red-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                    
                                    $statusText = match($status) {
                                        'menunggu' => 'Menunggu',
                                        'diproses' => '⏳ Pengajuan sedang diproses oleh kelurahan',
                                        'revisi' => '⚠️ Pengajuan perlu perbaikan',
                                        'selesai' => '✅ Pengajuan selesai',
                                        default => 'Menunggu',
                                    };
                                @endphp
                                
                                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $statusBadge }}">
                                    {{ $statusText }}
                                </span>
                                
                                @if($status === 'revisi' && ($item->keterangan || $item->catatan_admin))
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg max-w-md">
                                        <p class="text-sm font-semibold text-red-800 mb-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Keterangan Revisi:
                                        </p>
                                        <p class="text-sm text-red-900">{{ $item->keterangan ?? $item->catatan_admin }}</p>
                                    </div>
                                @elseif($status === 'diproses' && ($item->keterangan || $item->catatan_admin))
                                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg max-w-md">
                                        <p class="text-sm font-semibold text-blue-800 mb-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Informasi:
                                        </p>
                                        <p class="text-sm text-blue-900">{{ $item->keterangan ?? $item->catatan_admin }}</p>
                                    </div>
                                @elseif(($item->keterangan || $item->catatan_admin) && $status !== 'revisi' && $status !== 'diproses')
                                    <div class="mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg max-w-md">
                                        <p class="text-sm font-semibold text-gray-800 mb-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Keterangan:
                                        </p>
                                        <p class="text-sm text-gray-900">{{ $item->keterangan ?? $item->catatan_admin }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Detail Pemohon -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $item->nama ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">NIK:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $item->nik ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">No. HP:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $item->no_hp ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Alamat:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $item->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @php
                            // Normalize status for action buttons
                            $rawStatus = $item->status ?? 'menunggu';
                            $currentStatus = match(strtolower($rawStatus)) {
                                'menunggu', 'diajukan' => 'menunggu',
                                'diproses' => 'diproses',
                                'revisi', 'direvisi' => 'revisi',
                                'selesai' => 'selesai',
                                default => 'menunggu',
                            };
                        @endphp
                        
                        @if($currentStatus === 'selesai')
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="space-y-3">
                                    @if($item->nomor_surat)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <p class="text-sm text-gray-600 mb-1">Nomor Surat</p>
                                        <p class="text-lg font-bold text-blue-600">{{ $item->nomor_surat }}</p>
                                    </div>
                                    @endif
                                    @if($item->file_surat)
                                    <a href="{{ route('user.surat.download', $item->_id) }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors">
                                        <i class="fas fa-download mr-2"></i>Download Surat
                                    </a>
                                    @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            File surat belum tersedia. Silakan hubungi kelurahan untuk mendapatkan surat fisik.
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($currentStatus === 'revisi')
                            <div class="mt-4 pt-4 border-t border-red-200">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-sm font-semibold text-red-800 mb-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Tindakan yang Diperlukan:
                                    </p>
                                    <p class="text-sm text-red-900 mb-3">
                                        Silakan perbaiki dokumen sesuai keterangan di atas, kemudian hubungi admin untuk mengajukan ulang.
                                    </p>
                                    <a href="{{ route('user.pengajuan') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                                        <i class="fas fa-redo mr-2"></i>Ajukan Ulang
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pengajuan->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pengajuan Surat</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pengajuan surat. Silakan ajukan surat terlebih dahulu.</p>
                <a href="{{ route('user.pengajuan') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md">
                    <i class="fas fa-plus-circle mr-2"></i>Ajukan Surat Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal e-Tiket -->
@if(session('show_etiket_modal') && session('etiket_data'))
    <div id="etiket-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center text-white">
                <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center mb-4">
                    <i class="fas fa-ticket-alt text-blue-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">e-Tiket Berhasil Dibuat!</h2>
                <p class="text-blue-100 text-sm">Pengajuan Anda telah diterima</p>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6">
                <div class="space-y-4">
                    <!-- Nomor e-Tiket -->
                    <div class="bg-blue-50 border-2 border-blue-600 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-600 mb-2">Nomor e-Tiket Anda</p>
                        <p class="text-2xl font-bold text-blue-600 tracking-wider">{{ session('etiket_data')['nomor_tiket'] }}</p>
                    </div>

                    <!-- Info Detail -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">No. Pengajuan:</span>
                            <span class="font-semibold text-gray-900">{{ session('etiket_data')['no_pengajuan'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jenis Surat:</span>
                            <span class="font-semibold text-gray-900">{{ session('etiket_data')['jenis_surat'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-semibold text-gray-900">{{ session('etiket_data')['nama'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-semibold text-gray-900">{{ session('etiket_data')['tanggal'] }}</span>
                        </div>
                    </div>

                    <!-- Informasi Penting -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 text-sm mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Informasi Penting
                        </h4>
                        <ul class="text-xs text-yellow-800 space-y-1">
                            <li>• Simpan nomor e-Tiket Anda dengan baik</li>
                            <li>• Datang ke kelurahan HANYA jika status sudah "Selesai"</li>
                            <li>• Bawa KTP asli dan nomor e-Tiket</li>
                            <li>• Pantau status di menu "Status Pengajuan"</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <button onclick="closeEtiketModal()" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-check mr-2"></i>Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeEtiketModal() {
            document.getElementById('etiket-modal').style.display = 'none';
        }
    </script>
@endif

<!-- Modal Sukses Pengajuan -->
@if(session('show_success_modal') && session('pengajuan_data'))
<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: flex;">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">
        <!-- Close Button (X) -->
        <button onclick="closeSuccessModal()" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8 text-center text-white rounded-t-xl">
            <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center mb-4">
                <i class="fas fa-check-circle text-green-600 text-5xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">Permohonan Berhasil Diajukan!</h2>
            <p class="text-green-100 text-sm">Terima kasih telah menggunakan layanan online Kelurahan Pabuaran Mekar</p>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-6 space-y-4">
            <!-- Nomor Pengajuan -->
            <div class="bg-blue-50 border-2 border-blue-600 rounded-lg p-4">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    <p class="text-sm text-gray-600 font-medium">Nomor Pengajuan Anda</p>
                </div>
                <p class="text-2xl font-bold text-blue-600 text-center tracking-wider">
                    {{ session('pengajuan_data')['nomor_pengajuan'] }}
                </p>
                <p class="text-xs text-gray-600 text-center mt-2">Simpan nomor ini untuk tracking pengajuan</p>
            </div>

            <!-- Jenis Surat & Estimasi -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">Jenis Surat</p>
                        <p class="font-semibold text-gray-900">{{ session('pengajuan_data')['jenis_layanan'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            Estimasi Selesai
                        </p>
                        <p class="font-semibold text-gray-900">1-3 hari kerja</p>
                    </div>
                </div>
            </div>

            <!-- Langkah Selanjutnya -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-semibold text-yellow-900 mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-yellow-600"></i>
                    Langkah Selanjutnya
                </h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-yellow-800">
                    <li>Verifikasi dokumen oleh admin (1-2 hari kerja)</li>
                    <li>Proses pembuatan surat sesuai estimasi waktu</li>
                    <li>Notifikasi email saat surat siap</li>
                </ol>
            </div>

            <!-- Cek Status -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
                    Cek Status di Dashboard
                </h4>
                <p class="text-sm text-blue-800">
                    Lihat status terkini di menu "Riwayat Pengajuan" → Status akan berubah: Pending → Diproses → Selesai
                </p>
            </div>

            <!-- Butuh Bantuan -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">Butuh Bantuan?</h4>
                <p class="text-sm text-gray-700">
                    Telp: (021) 8765-4321 | Email: kelurahan.pabuaranmekar@bogor.go.id
                </p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end">
            <a href="{{ route('user.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md">
                <i class="fas fa-home mr-2"></i>
                Tutup & Ke Dashboard
            </a>
        </div>
    </div>
</div>

<script>
    function closeSuccessModal() {
        document.getElementById('success-modal').style.display = 'none';
        window.location.href = '{{ route("user.dashboard") }}';
    }
    
    // Close modal on outside click
    document.getElementById('success-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSuccessModal();
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('success-modal');
            if (modal && modal.style.display !== 'none') {
                closeSuccessModal();
            }
        }
    });
</script>
@endif
@endsection

