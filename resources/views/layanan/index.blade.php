@extends('layouts.app')

@section('title', 'Informasi Layanan Surat - Kelurahan Pabuaran Mekar')

@section('content')
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 py-16 border-b border-yellow-500/20 pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Informasi Layanan Surat</h1>
                <p class="text-gray-300 text-lg">
                    Lihat persyaratan dan informasi lengkap untuk setiap jenis surat
                </p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1. Surat Keterangan Kelahiran -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <!-- Icon and Header -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-baby text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Surat Keterangan Kelahiran</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Surat pengantar untuk pengurusan Akta Kelahiran
                            </p>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Persyaratan Dokumen:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pengantar RT/RW</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KK orang tua</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KTP orang tua</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy Buku Nikah / Akta Perkawinan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Asli dan fotocopy Surat Keterangan Kelahiran dari Rumah Sakit / Bidan</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Processing Time Badge -->
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Waktu Proses: 1-3 hari kerja</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Surat Keterangan Kematian -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <!-- Icon and Header -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dove text-gray-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Surat Keterangan Kematian</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Surat pengantar untuk pengurusan Akta Kematian
                            </p>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Persyaratan Dokumen:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pengantar RT/RW</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KK dan KTP almarhum/almarhumah</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KTP pelapor</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Asli dan fotocopy Surat Keterangan Kematian dari Rumah Sakit / Dokter / Bidan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pernyataan Kematian dari keluarga</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Processing Time Badge -->
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Waktu Proses: 1-3 hari kerja</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Surat Keterangan Usaha -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <!-- Icon and Header -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-green-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Surat Keterangan Usaha</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Surat keterangan untuk keperluan perizinan usaha
                            </p>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Persyaratan Dokumen:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pengantar RT/RW</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KTP dan KK pemohon</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy Surat Izin Usaha (jika ada)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pernyataan Usaha dari pemohon</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Foto lokasi usaha (jika diperlukan)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Processing Time Badge -->
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Waktu Proses: 1-3 hari kerja</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Surat Keterangan Tidak Mampu (SKTM) -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <!-- Icon and Header -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-blue-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Surat Keterangan Tidak Mampu</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Surat keterangan tidak mampu untuk keringanan biaya pendidikan, kesehatan, dll
                            </p>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Persyaratan Dokumen:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pengantar RT/RW</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KTP dan KK Pemohon</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Keterangan Rawat Inap (opsional)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Keterangan dari Sekolah (opsional)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pernyataan Keluarga Miskin (opsional)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Processing Time Badge -->
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Waktu Proses: 1-3 hari kerja</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Surat Pengantar PBB -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100 md:col-span-2">
                    <!-- Icon and Header -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-purple-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Surat Pengantar PBB</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Surat pengantar untuk keperluan administrasi Pajak Bumi dan Bangunan
                            </p>
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Persyaratan Dokumen:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Surat Pengantar RT/RW</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy KTP dan KK pemohon</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy SPPT PBB (Surat Pemberitahuan Pajak Terutang)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 leading-relaxed">Fotocopy Sertifikat Tanah / Bukti Kepemilikan (jika ada)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Processing Time Badge -->
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">Waktu Proses: 1-3 hari kerja</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
