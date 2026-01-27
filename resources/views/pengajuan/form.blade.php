@extends('layouts.app')

@section('title', 'Form Pengajuan - ' . $layanan['nama'])

@section('content')
@php
    $memerlukanEtiket = in_array($layanan['nama'], \App\Models\PengajuanSurat::layananEtiket());
    $showEtiketModal = $memerlukanEtiket && $layanan['slug'] !== 'kelahiran';
@endphp

@if($showEtiketModal)
<!-- Modal Informasi e-Ticket (Muncul saat halaman form di-load) -->
<div id="etiket-modal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center z-50 px-4" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl px-6 py-5 relative">
        
        <!-- Close Button (X) -->
        <button type="button" 
                onclick="closeEtiketModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
                aria-label="Tutup modal">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <!-- Icon Atas (Center) -->
        <div class="flex justify-center mb-3">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-ticket-alt text-white text-base"></i>
            </div>
        </div>

        <!-- Judul -->
        <h2 id="modal-title" class="text-center text-base font-semibold text-gray-900 mb-2">
            Layanan Menggunakan e-Ticket
        </h2>

        <!-- Deskripsi -->
        <p class="text-sm text-gray-500 text-center leading-relaxed mb-4">
            Layanan ini menggunakan e-Ticket. Silakan lakukan pengajuan dan upload dokumen yang diminta. Setelah admin memverifikasi permohonan Anda, Anda dapat datang ke kelurahan dengan membawa e-Ticket untuk proses lanjutan.
        </p>

        <!-- Info Box (4 Item) -->
        <div class="space-y-2 mb-4">
            
            <!-- Item 1: BIRU (Info) -->
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-700">
                    e-Ticket otomatis diberikan setelah Anda submit form
                </p>
            </div>

            <!-- Item 2: KUNING (Warning) -->
            <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-yellow-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xs"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-700">
                    Status e-Ticket default: <strong>"Menunggu Verifikasi"</strong>
                </p>
            </div>

            <!-- Item 3: HIJAU (Success) -->
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xs"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-700">
                    Anda hanya boleh datang ke kelurahan setelah status = <strong>"Selesai"</strong>
                </p>
            </div>

            <!-- Item 4: KUNING MUDA (Lightbulb/Info) -->
            <div class="flex items-start gap-3 bg-yellow-50/50 border border-yellow-200 rounded-lg px-4 py-3">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-lightbulb text-yellow-900 text-xs"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-700">
                    Informasi status e-Ticket dapat dilihat di halaman tracking pengajuan
                </p>
            </div>

        </div>

        <!-- Footer Button -->
        <div class="flex justify-between items-center mt-4">
            <button type="button" 
                    onclick="closeEtiketModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" 
                    onclick="closeEtiketModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md px-4 py-2 transition-colors">
                Saya Mengerti, Lanjutkan
            </button>
        </div>

    </div>
</div>
@endif

<div id="form-container" class="min-h-screen py-20" style="background-color: #F1F5F9;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('user.pengajuan') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Layanan
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $layanan['nama'] }}</h1>
            <p class="text-gray-600">{{ $layanan['deskripsi'] }}</p>
        </div>

        <!-- Card Layanan Header -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center space-x-3 text-white">
                        <i class="fas {{ $layanan['icon'] }} text-2xl"></i>
                        <div>
                            <h3 class="text-lg font-bold">{{ $layanan['nama'] }}</h3>
                            <p class="text-sm text-blue-100">Kelurahan Pabuaran Mekar, Kecamatan Cibinong</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Stepper (2 Steps) -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <!-- Step 1 -->
                <div class="flex items-center">
                    <div id="step-1-indicator" class="flex items-center space-x-3 px-6 py-3 rounded-lg transition-all step-1-active">
                        <div id="step-1-circle" class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold step-1-circle-active">
                            <span id="step-1-number">1</span>
                            <i id="step-1-check" class="fas fa-check hidden"></i>
                        </div>
                        <span id="step-1-text" class="font-semibold step-1-text-active">Data Pelapor</span>
                    </div>
                </div>

                <!-- Arrow -->
                <i class="fas fa-arrow-right text-gray-400 text-xl"></i>

                <!-- Step 2 -->
                <div class="flex items-center">
                    <div id="step-2-indicator" class="flex items-center space-x-3 bg-gray-100 px-6 py-3 rounded-lg transition-all">
                        <div id="step-2-circle" class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold">
                            2
                        </div>
                        <span id="step-2-text" class="font-semibold text-gray-600">Data Pembuat Surat</span>
                    </div>
                </div>
            </div>
        </div>

        <form id="form-pengajuan" method="POST" action="{{ route('user.pengajuan.store', $layanan['slug']) }}" enctype="multipart/form-data">
            @csrf

                <!-- STEP 1: Data Pelapor -->
                <div id="step-1-content" class="step-content">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Form Header -->
                        <div class="bg-blue-50 border-b border-blue-100 px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Data Pelapor</h2>
                                    <p class="text-sm text-gray-600">Lengkapi data diri pelapor</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            <!-- Nama Pelapor -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                    Nama Pelapor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pelapor" id="nama_pelapor" 
                                    value="{{ old('nama_pelapor', auth()->user()->name ?? '') }}"
                                    placeholder="Masukkan nama lengkap pelapor"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>

                            <!-- NIK -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                    NIK <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nik_pelapor" id="nik_pelapor"
                                    value="{{ old('nik_pelapor', auth()->user()->nik_or_nip ?? '') }}"
                                    placeholder="16 digit NIK"
                                    maxlength="16"
                                    pattern="[0-9]{16}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>

                            <!-- Nomor KK -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-address-card mr-2 text-gray-400"></i>
                                    Nomor KK <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_kk" id="nomor_kk"
                                    placeholder="16 digit Nomor Kartu Keluarga"
                                    maxlength="16"
                                    pattern="[0-9]{16}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>

                            <!-- Alamat Lengkap -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat_pelapor" id="alamat_pelapor" rows="3"
                                        placeholder="Masukkan alamat lengkap sesuai KTP"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                        required>{{ old('alamat_pelapor', auth()->user()->address ?? '') }}</textarea>
                            </div>

                            <!-- Nomor Telepon/WhatsApp -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="nomor_telepon_pelapor" id="nomor_telepon_pelapor"
                                    value="{{ old('nomor_telepon_pelapor', auth()->user()->phone ?? '') }}"
                                    placeholder="08xxxxxxxxxx"
                                    pattern="[0-9]{10,13}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>

                            <!-- Hubungan dengan Pembuat Surat -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    Hubungan dengan Pembuat Surat <span class="text-red-500">*</span>
                                </label>
                                <select name="hubungan" id="hubungan"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                                        required>
                                    <option value="">Pilih hubungan</option>
                                    <option value="Diri Sendiri">Diri Sendiri</option>
                                    <option value="Suami">Suami</option>
                                    <option value="Istri">Istri</option>
                                    <option value="Anak">Anak</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Saudara Kandung">Saudara Kandung</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <!-- Button Next -->
                            <div class="flex justify-end pt-4 border-t">
                                <button type="button" id="btn-next-step-2"
                                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all flex items-center space-x-2">
                                    <span>Lanjutkan</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Data Pembuat Surat & Upload -->
                <div id="step-2-content" class="step-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Form Header -->
                        <div class="bg-blue-50 border-b border-blue-100 px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Form Pembuat Surat</h2>
                                    <p class="text-sm text-gray-600">Lengkapi data pembuat surat dan upload dokumen pendukung</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            <!-- Nama Pembuat Surat -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                    Nama Pembuat Surat <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pembuat_surat" id="nama_pembuat_surat"
                                       placeholder="Masukkan nama lengkap pembuat surat"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                       required>
                            </div>

                            <!-- NIK Pembuat Surat -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                    NIK Pembuat Surat <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nik_pembuat_surat" id="nik_pembuat_surat"
                                       placeholder="16 digit NIK sesuai KTP"
                                       maxlength="16"
                                       pattern="[0-9]{16}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                       required>
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-home mr-2 text-gray-400"></i>
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat_pembuat_surat" id="alamat_pembuat_surat" rows="3"
                                          placeholder="Masukkan alamat lengkap sesuai KTP"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                          required></textarea>
                            </div>

                            <!-- Upload Dokumen Section -->
                            <div class="border-t pt-6">
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                                        <i class="fas fa-upload text-purple-600 mr-3 text-xl"></i>
                                        Unggah Dokumen Pendukung <span class="text-red-500 ml-1">*</span>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Upload semua dokumen dalam format PDF atau JPG (maks. 2MB per file)
                                    </p>
                                </div>

                                @php
                                    // Mapping dokumen berdasarkan jenis layanan
                                    $dokumenList = [];
                                    
                                    if ($layanan['slug'] === 'kelahiran') {
                                        $dokumenList = [
                                            ['key' => 'doc_pengantar_rt', 'name' => 'Surat Pengantar RT/RW', 'required' => true],
                                            ['key' => 'doc_kk_ortu', 'name' => 'Fotocopy Kartu Keluarga Orang Tua', 'required' => true],
                                            ['key' => 'doc_ktp_ortu', 'name' => 'Fotocopy KTP Orang Tua', 'required' => true],
                                            ['key' => 'doc_buku_nikah', 'name' => 'Fotocopy Buku Nikah / Akta Perkawinan', 'required' => true],
                                            ['key' => 'doc_surat_lahir', 'name' => 'Surat Keterangan Kelahiran dari RS / Bidan (Asli & Fotocopy)', 'required' => true],
                                        ];
                                    } elseif ($layanan['slug'] === 'tidak-mampu') {
                                        // Surat Pernyataan Waris (berdasarkan requirement user)
                                        $dokumenList = [
                                            ['key' => 'doc_pengantar_rt', 'name' => 'Pengantar RT/RW', 'required' => true],
                                            ['key' => 'doc_kk_ahli_waris', 'name' => 'Photocopy Kartu Keluarga Para Ahli Waris', 'required' => true],
                                            ['key' => 'doc_akta_nikah_almarhum', 'name' => 'Photocopy Akta Pernikahan / Akta Perceraian Almarhum', 'required' => true],
                                            ['key' => 'doc_akta_lahir_waris', 'name' => 'Photocopy Akta Kelahiran Ahli Waris', 'required' => true],
                                            ['key' => 'doc_akta_kematian', 'name' => 'Photocopy Akta Kematian Almarhum', 'required' => true],
                                            ['key' => 'doc_optional_waris', 'name' => 'Photocopy SK Pensiun / Buku Tabungan / SHM / SHGB / BPJS', 'required' => false],
                                        ];
                                    } elseif ($layanan['slug'] === 'nikah') {
                                        // Surat Keterangan Usaha (jika nikah = usaha, perlu konfirmasi mapping)
                                        // Untuk saat ini, kita tetap gunakan mapping sesuai yang ada
                                        $dokumenList = [
                                            ['key' => 'doc_pengantar_rt', 'name' => 'Pengantar RT/RW', 'required' => true],
                                            ['key' => 'doc_kk', 'name' => 'Photocopy Kartu Keluarga', 'required' => true],
                                            ['key' => 'doc_ktp_pemohon', 'name' => 'Photocopy KTP Pemohon', 'required' => true],
                                            ['key' => 'doc_foto_usaha', 'name' => 'Foto Usaha', 'required' => true],
                                            ['key' => 'doc_izin_lingkungan', 'name' => 'Izin Lingkungan + KTP Tetangga', 'required' => false],
                                            ['key' => 'doc_sewa', 'name' => 'Perjanjian Sewa / Kwitansi', 'required' => false],
                                            ['key' => 'doc_shm_pbb', 'name' => 'Photocopy SHM + Bukti Bayar PBB', 'required' => false],
                                        ];
                                    } elseif ($layanan['slug'] === 'domisili') {
                                        // Surat Keterangan Domisili Usaha
                                        $dokumenList = [
                                            ['key' => 'doc_pengantar_rt', 'name' => 'Pengantar RT/RW', 'required' => true],
                                            ['key' => 'doc_kk', 'name' => 'Photocopy Kartu Keluarga', 'required' => true],
                                            ['key' => 'doc_ktp_pemohon', 'name' => 'Photocopy KTP Pemohon', 'required' => true],
                                            ['key' => 'doc_akta_usaha', 'name' => 'Akta Pendirian Usaha + SK MENKUMHAM', 'required' => true],
                                            ['key' => 'doc_izin_lingkungan', 'name' => 'Izin Lingkungan + KTP Tetangga', 'required' => false],
                                            ['key' => 'doc_foto_usaha', 'name' => 'Foto Usaha', 'required' => false],
                                            ['key' => 'doc_sewa', 'name' => 'Perjanjian Sewa / Kwitansi', 'required' => false],
                                            ['key' => 'doc_shm_pbb', 'name' => 'Photocopy SHM + Bukti Bayar PBB', 'required' => false],
                                        ];
                                    } elseif ($layanan['slug'] === 'pbb') {
                                        $dokumenList = [
                                            ['key' => 'doc_pengantar_rt', 'name' => 'Surat Pengantar RT/RW', 'required' => true],
                                            ['key' => 'doc_ktp_pemohon', 'name' => 'Photocopy KTP Pemohon', 'required' => true],
                                            ['key' => 'doc_kk_pemohon', 'name' => 'Photocopy Kartu Keluarga Pemohon', 'required' => true],
                                            ['key' => 'doc_bukti_kepemilikan', 'name' => 'Photocopy Bukti Kepemilikan (AJB / APHB / Hibah / Sertifikat)', 'required' => true],
                                            ['key' => 'doc_pendukung_pbb', 'name' => 'Dokumen pendukung lainnya sesuai jenis layanan PBB (penerbitan baru / mutasi / sebagian / mutasi keseluruhan / perbaikan)', 'required' => true],
                                        ];
                                    }
                                @endphp

                                <div class="space-y-4">
                                    @foreach($dokumenList as $index => $doc)
                                        <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $doc['name'] }} 
                                                            @if($doc['required'])
                                                                <span class="text-red-500">*</span>
                                                            @else
                                                                <span class="text-gray-500 text-xs font-normal">(Opsional)</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1" id="file-name-{{ $index + 1 }}">No file chosen</p>
                                                    </div>
                                                </div>
                                                <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                    Choose Files
                                                    <input type="file" name="{{ $doc['key'] }}" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                           @if($doc['required']) required @endif
                                                           onchange="updateFileName(this, 'file-name-{{ $index + 1 }}', {{ $doc['required'] ? 'true' : 'false' }})">
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t">
                                <button type="button" id="btn-back-step-1"
                                        class="px-6 py-3 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-all flex items-center space-x-2">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Kembali</span>
                                </button>
                                <button type="submit" id="btn-submit"
                                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition-all flex items-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Kirim Permohonan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        <!-- Informasi Penting -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informasi Penting
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                @foreach($layanan['persyaratan'] as $persyaratan)
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                        <span>{{ $persyaratan }}</span>
                    </li>
                @endforeach
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                    <span>Proses verifikasi dokumen memakan waktu 3-5 hari kerja</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                    <span>Anda akan menerima notifikasi melalui Email untuk status pengajuan</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                    <span>Untuk pertanyaan, hubungi: (0251) 123-4567</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Multi-Step Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const step1Content = document.getElementById('step-1-content');
    const step2Content = document.getElementById('step-2-content');
    
    const step1Indicator = document.getElementById('step-1-indicator');
    const step2Indicator = document.getElementById('step-2-indicator');
    
    const step1Circle = document.getElementById('step-1-circle');
    const step2Circle = document.getElementById('step-2-circle');
    
    const step1Text = document.getElementById('step-1-text');
    const step2Text = document.getElementById('step-2-text');
    
    const step1Number = document.getElementById('step-1-number');
    const step1Check = document.getElementById('step-1-check');
    
    const btnNext = document.getElementById('btn-next-step-2');
    const btnBack = document.getElementById('btn-back-step-1');
    
    // Go to Step 2
    if (btnNext) {
        btnNext.addEventListener('click', function() {
            // Validate Step 1
            const form = document.getElementById('form-pengajuan');
            const step1Inputs = step1Content.querySelectorAll('input[required], textarea[required], select[required]');
            let isValid = true;
            
            step1Inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                alert('Mohon lengkapi semua field yang wajib diisi');
                return;
            }
            
            // Hide Step 1
            step1Content.classList.add('hidden');
            
            // Show Step 2
            step2Content.classList.remove('hidden');
            
            // Update Step 1 to Completed (Green with Check)
            step1Indicator.classList.remove('step-1-active');
            step1Indicator.classList.add('bg-green-100');
            
            step1Circle.classList.remove('step-1-circle-active');
            step1Circle.classList.add('bg-green-600');
            
            step1Text.classList.remove('step-1-text-active');
            step1Text.classList.add('text-green-900');
            
            step1Number.classList.add('hidden');
            step1Check.classList.remove('hidden');
            
            // Update Step 2 to Active (Blue)
            step2Indicator.classList.remove('bg-gray-100');
            step2Indicator.classList.add('bg-blue-100');
            
            step2Circle.classList.remove('bg-gray-400');
            step2Circle.classList.add('bg-blue-600');
            
            step2Text.classList.remove('text-gray-600');
            step2Text.classList.add('text-blue-900');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Back to Step 1
    if (btnBack) {
        btnBack.addEventListener('click', function() {
            // Hide Step 2
            step2Content.classList.add('hidden');
            
            // Show Step 1
            step1Content.classList.remove('hidden');
            
            // Update Step 1 back to Active
            step1Indicator.classList.add('step-1-active');
            step1Indicator.classList.remove('bg-green-100');
            
            step1Circle.classList.add('step-1-circle-active');
            step1Circle.classList.remove('bg-green-600');
            
            step1Text.classList.add('step-1-text-active');
            step1Text.classList.remove('text-green-900');
            
            step1Number.classList.remove('hidden');
            step1Check.classList.add('hidden');
            
            // Update Step 2 back to Inactive
            step2Indicator.classList.add('bg-gray-100');
            step2Indicator.classList.remove('bg-blue-100');
            
            step2Circle.classList.add('bg-gray-400');
            step2Circle.classList.remove('bg-blue-600');
            
            step2Text.classList.add('text-gray-600');
            step2Text.classList.remove('text-blue-900');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});

// Update file name display
function updateFileName(input, targetId, isRequired = false) {
    const target = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        
        // Validate file size (max 2MB)
        if (fileSize > 2) {
            alert('Ukuran file terlalu besar. Maksimal 2MB per file.');
            input.value = '';
            target.textContent = 'No file chosen';
            target.classList.add('text-gray-500');
            target.classList.remove('text-green-600', 'font-medium', 'text-red-600');
            return;
        }
        
        // Validate file type
        const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid. Gunakan PDF, JPG, atau PNG.');
            input.value = '';
            target.textContent = 'No file chosen';
            target.classList.add('text-gray-500');
            target.classList.remove('text-green-600', 'font-medium', 'text-red-600');
            return;
        }
        
        target.textContent = file.name;
        target.classList.remove('text-gray-500', 'text-red-600');
        target.classList.add('text-green-600', 'font-medium');
    } else {
        target.textContent = 'No file chosen';
        target.classList.add('text-gray-500');
        target.classList.remove('text-green-600', 'font-medium', 'text-red-600');
    }
}
</script>

<style>
/* Step 1 Active State */
.step-1-active {
    background-color: rgb(219 234 254); /* bg-blue-100 */
}

.step-1-circle-active {
    background-color: rgb(37 99 235); /* bg-blue-600 */
}

.step-1-text-active {
    color: rgb(30 58 138); /* text-blue-900 */
}
</style>

@if($showEtiketModal)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('etiket-modal');
    const formContainer = document.getElementById('form-container');
    
    // Blokir interaksi dengan form saat modal aktif
    if (formContainer) {
        formContainer.style.pointerEvents = 'none';
        formContainer.style.opacity = '0.5';
    }
    
    // Focus ke modal saat terbuka
    const closeBtn = modal.querySelector('button[aria-label="Tutup modal"]');
    if (closeBtn) {
        setTimeout(() => closeBtn.focus(), 100);
    }
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
});

function closeEtiketModal() {
    const modal = document.getElementById('etiket-modal');
    const formContainer = document.getElementById('form-container');
    
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Aktifkan form setelah modal ditutup
    if (formContainer) {
        formContainer.style.pointerEvents = 'auto';
        formContainer.style.opacity = '1';
    }
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('etiket-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeEtiketModal();
        }
    }
});

// Close modal saat klik di backdrop
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('etiket-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEtiketModal();
            }
        });
    }
});
</script>
@endif
@endpush

@endsection

