@extends('layouts.app')

@section('title', 'Informasi e-Tiket - ' . $layanan['nama'])

@section('content')
<!-- Modal e-Tiket (PRESISI SESUAI SCREENSHOT) -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center z-50 px-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-2xl px-6 py-5 relative">
                
                <!-- Icon Atas (Center, Kecil) -->
                <div class="flex justify-center mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-white text-base"></i>
                    </div>
                </div>

                <!-- Judul -->
                <h2 class="text-center text-base font-semibold text-gray-900 mb-2">
                    Layanan Menggunakan e-Tiket
                </h2>

                <!-- Deskripsi -->
                <p class="text-sm text-gray-500 text-center leading-relaxed mb-4">
                    Layanan ini menggunakan e-Tiket. Silakan lakukan pengajuan dan upload dokumen yang diminta. Setelah admin memverifikasi permohonan Anda, Anda dapat datang ke kelurahan dengan membawa e-Tiket untuk proses lanjutan.
                </p>

                <!-- Info Box (4 Item) -->
                <div class="space-y-2 mb-4">
                    
                    <!-- Item 1: BIRU -->
                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">
                            e-Tiket otomatis diberikan setelah Anda submit form
                        </p>
                    </div>

                    <!-- Item 2: KUNING -->
                    <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-yellow-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">
                            Status e-Tiket default: <strong>"Menunggu Verifikasi"</strong>
                        </p>
                    </div>

                    <!-- Item 3: HIJAU -->
                    <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">
                            Anda hanya boleh datang ke kelurahan setelah status = <strong>"Selesai"</strong>
                        </p>
                    </div>

                    <!-- Item 4: ORANGE -->
                    <div class="flex items-start gap-3 bg-orange-50 border border-orange-200 rounded-lg px-4 py-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-orange-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-lightbulb text-white text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">
                            Informasi status e-Tiket dapat dilihat di halaman tracking pengajuan
                        </p>
                    </div>

                </div>

                <!-- Footer Button -->
                <div class="flex justify-between items-center mt-4">
                    <a href="{{ route('user.pengajuan') }}" 
                       class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        Batal
                    </a>
                    <a href="{{ route('user.pengajuan.form', ['jenis' => $layanan['slug'], 'confirmed' => 1]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md px-4 py-2 transition-colors">
                        Saya Mengerti, Lanjutkan
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

