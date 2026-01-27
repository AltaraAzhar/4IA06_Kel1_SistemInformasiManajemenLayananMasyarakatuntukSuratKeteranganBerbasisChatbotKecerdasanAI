<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Pengajuan Surat - Kelurahan Pabuaran Mekar</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-green-800 text-white px-6 py-4 shadow-md">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/Lambang_Kabupaten_Bogor.svg') }}" 
                         alt="Logo Kabupaten Bogor" 
                         class="h-12 w-12 object-contain">
                    <div>
                        <h1 class="text-xl font-bold">Preview Pengajuan Surat</h1>
                        <p class="text-sm text-green-200">Kelurahan Pabuaran Mekar</p>
                    </div>
                </div>
                <div class="text-sm text-green-200">
                    <i class="fas fa-eye mr-2"></i>
                    Mode Preview (Read-Only)
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Info Alert -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800">
                            <strong>Halaman Preview:</strong> Halaman ini hanya untuk melihat informasi pengajuan. 
                            Tidak dapat melakukan perubahan status atau aksi lainnya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6 text-center">
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

            <!-- Informasi Umum -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informasi Umum
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Surat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->jenis_layanan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $pengajuan->created_at ? $pengajuan->created_at->format('d F Y, H:i') : '-' }}
                        </p>
                    </div>
                    @if($pengajuan->etiket)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor Antrian / E-Ticket</p>
                        <p class="text-lg font-semibold text-blue-600">{{ $pengajuan->etiket }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Data Pemohon -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Data Pemohon
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">NIK</p>
                        <p class="text-lg font-semibold text-gray-900">{{ maskNIK($pengajuan->nik ?? '') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor HP</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pengajuan->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Status & Keterangan -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                    Status & Keterangan
                </h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Status Pengajuan</p>
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
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
                    @if($pengajuan->catatan_admin)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-gray-900">{{ $pengajuan->catatan_admin }}</p>
                        </div>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-gray-500 italic">
                                @if($pengajuan->status === 'menunggu')
                                    Menunggu verifikasi dokumen oleh admin
                                @elseif($pengajuan->status === 'diproses')
                                    Sedang dalam proses pembuatan surat
                                @elseif($pengajuan->status === 'revisi')
                                    Perlu perbaikan sesuai catatan admin
                                @elseif($pengajuan->status === 'selesai')
                                    Pengajuan telah selesai diproses
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dokumen Persyaratan -->
            @if($pengajuan->dokumen && count($pengajuan->dokumen) > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    Dokumen Persyaratan
                </h2>
                <div class="space-y-3">
                    @foreach($pengajuan->dokumen as $index => $doc)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
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
                            <a href="{{ $doc['url'] ?? asset('storage/' . ($doc['path'] ?? '')) }}" target="_blank" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg flex items-center space-x-2">
                                <i class="fas fa-eye"></i>
                                <span>Lihat</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Footer Info -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Catatan:</strong> Halaman ini hanya untuk preview. Untuk melakukan perubahan status atau aksi lainnya, 
                    silakan login sebagai admin di <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline">halaman admin</a>.
                </p>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('landing') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>

