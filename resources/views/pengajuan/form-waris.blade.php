@extends('layouts.app')

@section('title', 'Form Pengajuan - Surat Pernyataan Waris')

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
                    onclick="window.location.href='{{ route('user.pengajuan') }}'"
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
        
        <!-- Header Layanan -->
        <div class="mb-6">
            <a href="{{ route('user.pengajuan') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Layanan
            </a>
        </div>
        <div class="mb-8">
            <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 rounded-2xl shadow-xl p-8">
                <div class="flex items-start space-x-6">
                    <!-- Icon Dokumen -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-yellow-400 rounded-xl flex items-center justify-center">
                            <i class="fas {{ $layanan['icon'] }} text-yellow-900 text-3xl"></i>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-white mb-2">
                            {{ $layanan['nama'] }}
                        </h1>
                        <p class="text-blue-200 text-sm mb-3">
                            Kelurahan Pabuaran Mekar, Kecamatan Cibinong
                        </p>
                        <p class="text-blue-100 text-sm">
                            {{ $layanan['deskripsi'] }}
                        </p>
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
                        <span id="step-2-text" class="font-semibold text-gray-600">Data Para Pewaris & Ahli Waris</span>
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

                        <!-- NIK Pelapor -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                NIK Pelapor <span class="text-red-500">*</span>
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
                                placeholder="08xxx00xxxxxx"
                                pattern="[0-9]{10,13}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                        </div>

                        <!-- Hubungan dengan Pewaris Utama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users mr-2 text-gray-400"></i>
                                Hubungan dengan Pewaris Utama <span class="text-red-500">*</span>
                            </label>
                            <select name="hubungan_pewaris" id="hubungan_pewaris"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                                    required>
                                <option value="">Pilih hubungan</option>
                                <option value="Anak">Anak</option>
                                <option value="Istri">Istri</option>
                                <option value="Suami">Suami</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Saudara Kandung">Saudara Kandung</option>
                                <option value="Kerabat">Kerabat</option>
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

            <!-- STEP 2: Data Para Pewaris & Ahli Waris -->
            <div id="step-2-content" class="step-content hidden">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-blue-50 border-b border-blue-100 px-8 py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Data Para Pewaris & Ahli Waris</h2>
                                    <p class="text-sm text-gray-600">Lengkapi data setiap pewaris dan ahli waris</p>
                                </div>
                            </div>
                            <div class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
                                Total: <span id="total-ahli-waris">1</span> Ahli Waris
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- BAGIAN A: DATA PEWARIS -->
                        <div class="border-b pb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Data Pewaris</h3>
                            
                            <div class="space-y-6">
                                <!-- Nama Pewaris -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-gray-400"></i>
                                        Nama Pewaris <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_pewaris" id="nama_pewaris"
                                           placeholder="Masukkan nama lengkap pewaris"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                </div>

                                <!-- NIK Pewaris -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                        NIK Pewaris <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nik_pewaris" id="nik_pewaris"
                                           placeholder="16 digit NIK"
                                           maxlength="16"
                                           pattern="[0-9]{16}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                </div>

                                <!-- Tempat Lahir -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                        Tempat Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tempat_lahir_pewaris" id="tempat_lahir_pewaris"
                                           placeholder="Masukkan tempat lahir pewaris"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_lahir_pewaris" id="tanggal_lahir_pewaris"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                </div>

                                <!-- Tanggal Meninggal -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                        Tanggal Meninggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_meninggal" id="tanggal_meninggal"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                </div>

                                <!-- Status Hubungan -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-users mr-2 text-gray-400"></i>
                                        Status Hubungan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status_hubungan_pewaris" id="status_hubungan_pewaris"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                                            required>
                                        <option value="">Pilih status hubungan</option>
                                        <option value="Ayah">Ayah</option>
                                        <option value="Ibu">Ibu</option>
                                        <option value="Kakek">Kakek</option>
                                        <option value="Nenek">Nenek</option>
                                        <option value="Saudara">Saudara</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <!-- Upload Dokumen Pewaris -->
                                <div class="border-t pt-6 mt-6">
                                    <h4 class="text-md font-semibold text-gray-800 mb-4">Upload Dokumen Pewaris</h4>
                                    
                                    <div class="space-y-4">
                                        <!-- Fotokopi Akta Kematian -->
                                        <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            Fotokopi Akta Kematian Almarhum/Almarhumah <span class="text-red-500">*</span>
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1" id="file-name-pewaris-kematian">No file chosen</p>
                                                    </div>
                                                </div>
                                                <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                    Choose File
                                                    <input type="file" name="doc_akta_kematian" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                           required onchange="updateFileName(this, 'file-name-pewaris-kematian', true)">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Fotokopi Akta Pernikahan/Perceraian (Opsional) -->
                                        <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            Fotokopi Akta Pernikahan/Perceraian Almarhum/Almarhumah <span class="text-gray-500 text-xs font-normal">(Opsional)</span>
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">Sesuai peruntukan Surat Keterangan Ahli Waris dibuat</p>
                                                        <p class="text-xs text-gray-500 mt-1" id="file-name-pewaris-nikah">No file chosen</p>
                                                    </div>
                                                </div>
                                                <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                    Choose File
                                                    <input type="file" name="doc_akta_nikah_pewaris" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                           onchange="updateFileName(this, 'file-name-pewaris-nikah', false)">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Fotokopi SK Pensiun/dll (Opsional) -->
                                        <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            Fotokopi SK Pensiun / Buku Tabungan / SHM / SHGB / BPJS Ketenagakerjaan <span class="text-gray-500 text-xs font-normal">(Opsional)</span>
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1" id="file-name-pewaris-optional">No file chosen</p>
                                                    </div>
                                                </div>
                                                <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                    Choose File
                                                    <input type="file" name="doc_optional_pewaris" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                           onchange="updateFileName(this, 'file-name-pewaris-optional', false)">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN B: DATA AHLI WARIS (Dinamis) -->
                        <div id="ahli-waris-container">
                            <!-- Ahli Waris #1 (Default) -->
                            <div class="ahli-waris-item border border-gray-200 rounded-lg p-6 bg-gray-50" data-index="1">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-bold text-gray-900">Ahli Waris #1</h3>
                                    <button type="button" class="btn-remove-ahli-waris hidden text-red-600 hover:text-red-700 text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Nama Ahli Waris -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-user mr-2 text-gray-400"></i>
                                            Nama Ahli Waris <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="ahli_waris[1][nama]" 
                                               placeholder="Masukkan nama lengkap ahli waris"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                               required>
                                    </div>

                                    <!-- NIK Ahli Waris -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                            NIK Ahli Waris <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="ahli_waris[1][nik]" 
                                               placeholder="16 digit NIK"
                                               maxlength="16"
                                               pattern="[0-9]{16}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                               required>
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                            Tanggal Lahir <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="ahli_waris[1][tanggal_lahir]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                               required>
                                    </div>

                                    <!-- Status Hubungan -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-users mr-2 text-gray-400"></i>
                                            Status Hubungan <span class="text-red-500">*</span>
                                        </label>
                                        <select name="ahli_waris[1][status_hubungan]" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                                                required>
                                            <option value="">Pilih status hubungan</option>
                                            <option value="Anak">Anak</option>
                                            <option value="Istri">Istri</option>
                                            <option value="Suami">Suami</option>
                                            <option value="Orang Tua">Orang Tua</option>
                                            <option value="Saudara Kandung">Saudara Kandung</option>
                                            <option value="Kerabat">Kerabat</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <!-- Upload Dokumen Ahli Waris -->
                                    <div class="border-t pt-4">
                                        <div class="space-y-4">
                                            <!-- Upload KTP -->
                                            <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3 flex-1">
                                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                Upload KTP <span class="text-red-500">*</span>
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1" id="file-name-ktp-1">No file chosen</p>
                                                        </div>
                                                    </div>
                                                    <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                        Choose File
                                                        <input type="file" name="ahli_waris[1][doc_ktp]" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                               required onchange="updateFileName(this, 'file-name-ktp-1', true)">
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Upload KK -->
                                            <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3 flex-1">
                                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                Upload KK <span class="text-red-500">*</span>
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1" id="file-name-kk-1">No file chosen</p>
                                                        </div>
                                                    </div>
                                                    <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                                        Choose File
                                                        <input type="file" name="ahli_waris[1][doc_kk]" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                               required onchange="updateFileName(this, 'file-name-kk-1', true)">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Ahli Waris -->
                        <div class="flex justify-center">
                            <button type="button" id="btn-tambah-ahli-waris"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all flex items-center space-x-2">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Ahli Waris Baru</span>
                            </button>
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
                                <span>Kirim Pengajuan Surat Waris</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Informasi Penting -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi Penting
            </h3>
            <ul class="space-y-2 text-sm text-blue-800">
                @foreach($layanan['persyaratan'] as $persyaratan)
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                        <span>{{ $persyaratan }}</span>
                    </li>
                @endforeach
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                    <span>Proses verifikasi dokumen memakan waktu 1-3 hari kerja</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                    <span>Anda akan menerima notifikasi melalui Email untuk status pengajuan</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                    <span>Setelah selesai, download surat atau dapatkan e-tiket dan datang ke kelurahan</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
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
    
    let ahliWarisCount = 1;
    
    // Go to Step 2
    if (btnNext) {
        btnNext.addEventListener('click', function() {
            // Validate Step 1
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
    
    // Tambah Ahli Waris
    const btnTambah = document.getElementById('btn-tambah-ahli-waris');
    const container = document.getElementById('ahli-waris-container');
    
    if (btnTambah) {
        btnTambah.addEventListener('click', function() {
            ahliWarisCount++;
            updateTotalAhliWaris();
            
            const newItem = document.createElement('div');
            newItem.className = 'ahli-waris-item border border-gray-200 rounded-lg p-6 bg-gray-50 mt-4';
            newItem.setAttribute('data-index', ahliWarisCount);
            newItem.innerHTML = `
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Ahli Waris #${ahliWarisCount}</h3>
                    <button type="button" class="btn-remove-ahli-waris text-red-600 hover:text-red-700 text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            Nama Ahli Waris <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ahli_waris[${ahliWarisCount}][nama]" 
                               placeholder="Masukkan nama lengkap ahli waris"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-gray-400"></i>
                            NIK Ahli Waris <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ahli_waris[${ahliWarisCount}][nik]" 
                               placeholder="16 digit NIK"
                               maxlength="16"
                               pattern="[0-9]{16}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="ahli_waris[${ahliWarisCount}][tanggal_lahir]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-gray-400"></i>
                            Status Hubungan <span class="text-red-500">*</span>
                        </label>
                        <select name="ahli_waris[${ahliWarisCount}][status_hubungan]" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                                required>
                            <option value="">Pilih status hubungan</option>
                            <option value="Anak">Anak</option>
                            <option value="Istri">Istri</option>
                            <option value="Suami">Suami</option>
                            <option value="Orang Tua">Orang Tua</option>
                            <option value="Saudara Kandung">Saudara Kandung</option>
                            <option value="Kerabat">Kerabat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="space-y-4">
                            <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                Upload KTP <span class="text-red-500">*</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1" id="file-name-ktp-${ahliWarisCount}">No file chosen</p>
                                        </div>
                                    </div>
                                    <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                        Choose File
                                        <input type="file" name="ahli_waris[${ahliWarisCount}][doc_ktp]" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                               required onchange="updateFileName(this, 'file-name-ktp-${ahliWarisCount}', true)">
                                    </label>
                                </div>
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                Upload KK <span class="text-red-500">*</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1" id="file-name-kk-${ahliWarisCount}">No file chosen</p>
                                        </div>
                                    </div>
                                    <label class="ml-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors flex-shrink-0">
                                        Choose File
                                        <input type="file" name="ahli_waris[${ahliWarisCount}][doc_kk]" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                               required onchange="updateFileName(this, 'file-name-kk-${ahliWarisCount}', true)">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(newItem);
            
            // Attach remove button handler
            const removeBtn = newItem.querySelector('.btn-remove-ahli-waris');
            removeBtn.addEventListener('click', function() {
                newItem.remove();
                updateTotalAhliWaris();
            });
        });
    }
    
    // Hapus Ahli Waris
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-ahli-waris')) {
            const item = e.target.closest('.ahli-waris-item');
            if (item && document.querySelectorAll('.ahli-waris-item').length > 1) {
                item.remove();
                updateTotalAhliWaris();
            } else {
                alert('Minimal harus ada 1 ahli waris');
            }
        }
    });
    
    function updateTotalAhliWaris() {
        const total = document.querySelectorAll('.ahli-waris-item').length;
        document.getElementById('total-ahli-waris').textContent = total;
        
        // Show/hide remove buttons
        const items = document.querySelectorAll('.ahli-waris-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.btn-remove-ahli-waris');
            if (total > 1) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }
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
    
    if (formContainer) {
        formContainer.style.pointerEvents = 'none';
        formContainer.style.opacity = '0.5';
    }
    
    const closeBtn = modal.querySelector('button[aria-label="Tutup modal"]');
    if (closeBtn) {
        setTimeout(() => closeBtn.focus(), 100);
    }
    
    document.body.style.overflow = 'hidden';
});

function closeEtiketModal() {
    const modal = document.getElementById('etiket-modal');
    const formContainer = document.getElementById('form-container');
    
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    if (formContainer) {
        formContainer.style.pointerEvents = 'auto';
        formContainer.style.opacity = '1';
    }
    
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('etiket-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeEtiketModal();
        }
    }
});

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

