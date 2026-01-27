@extends('layouts.app')

@section('title', 'Beranda - Layanan Surat Online Kelurahan Pabuaran Mekar')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg min-h-[600px] flex items-center relative">
        <div class="hero-overlay absolute inset-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-20">
            <div class="max-w-3xl">
                <!-- Badge -->
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-medium border border-yellow-500/30">
                        Pelayanan Digital Terpercaya
                    </span>
                </div>
                
                <!-- Title -->
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 leading-tight">
                    Layanan Surat Online
                </h1>
                <h2 class="text-3xl md:text-4xl font-semibold text-yellow-400 mb-6">
                    Kelurahan Pabuaran Mekar
                </h2>
                
                <!-- Description -->
                <p class="text-lg text-gray-200 mb-8 leading-relaxed">
                    Ajukan berbagai jenis surat keterangan secara online dengan mudah, cepat, dan efisien. 
                    Kecamatan Cibinong, Kabupaten Bogor.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <a href="{{ route('user.pengajuan') }}" 
                       class="btn-primary px-6 py-3 rounded-lg text-white font-semibold flex items-center justify-center space-x-2">
                        <i class="fas fa-file-alt"></i>
                        <span>Mulai Pengajuan</span>
                    </a>
                    <a href="{{ route('user.surat.status') }}" 
                       class="btn-outline px-6 py-3 rounded-lg font-semibold flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Cek Status Surat</span>
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="stat-card rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-1">5</div>
                        <div class="text-gray-200 text-sm">Jenis Layanan</div>
                    </div>
                    <div class="stat-card rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-1">24/7</div>
                        <div class="text-gray-200 text-sm">Akses Online</div>
                    </div>
                    <div class="stat-card rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-1">Fast</div>
                        <div class="text-gray-200 text-sm">Proses Cepat</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jenis Layanan Section -->
    <section class="section-padding bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium mb-4">
                    Layanan Kami
                </span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Jenis Layanan Surat</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Daftar lengkap layanan pembuatan surat yang tersedia di Kelurahan Pabuaran Mekar beserta persyaratannya
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $layanan = [
                        ['icon' => 'baby', 'name' => 'Surat Keterangan Kelahiran', 'desc' => 'Surat pengantar untuk pengurusan Akta Kelahiran'],
                        ['icon' => 'dove', 'name' => 'Surat Keterangan Kematian', 'desc' => 'Surat pengantar untuk pengurusan Akta Kematian'],
                        ['icon' => 'store', 'name' => 'Surat Keterangan Usaha', 'desc' => 'Surat keterangan untuk usaha mikro dan kecil'],
                        ['icon' => 'credit-card', 'name' => 'Surat Keterangan Tidak Mampu', 'desc' => 'Surat keterangan tidak mampu untuk keringanan biaya pendidikan, kesehatan, dll'],
                        ['icon' => 'home', 'name' => 'Surat Pengantar PBB', 'desc' => 'Surat pengantar untuk pengurusan pajak bumi dan bangunan'],
                    ];
                @endphp

                @foreach($layanan as $item)
                <div class="service-card bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-{{ $item['icon'] }} text-yellow-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item['name'] }}</h3>
                            <p class="text-gray-600 text-sm">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Cara Mengajukan & Catatan Penting -->
    <section class="section-padding bg-yellow-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Cara Mengajukan -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Cara Mengajukan Surat</h3>
                    <div class="space-y-4">
                        @php
                            $steps = [
                                ['icon' => 'user-plus', 'text' => 'Daftar/Login'],
                                ['icon' => 'file-alt', 'text' => 'Pilih Layanan'],
                                ['icon' => 'edit', 'text' => 'Lengkapi Data'],
                                ['icon' => 'check-circle', 'text' => 'Submit'],
                                ['icon' => 'download', 'text' => 'Akses Surat'],
                            ];
                        @endphp
                        @foreach($steps as $index => $step)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex items-center space-x-3 flex-1">
                                <i class="fas fa-{{ $step['icon'] }} text-yellow-600"></i>
                                <span class="text-gray-700 font-medium">{{ $step['text'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Catatan Penting -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Catatan Penting</h3>
                    <ul class="space-y-4">
                        @php
                            $notes = [
                                ['icon' => 'check-circle', 'text' => 'Pastikan semua dokumen persyaratan sudah disiapkan sebelum mengajukan'],
                                ['icon' => 'file-upload', 'text' => 'Upload dokumen dalam format PDF atau JPG dengan ukuran max 2MB'],
                                ['icon' => 'info-circle', 'text' => 'Data yang diisi harus sesuai dengan dokumen asli'],
                                ['icon' => 'clock', 'text' => 'Proses verifikasi membutuhkan waktu 1-3 hari kerja'],
                                ['icon' => 'envelope', 'text' => 'Anda akan mendapat notifikasi via email untuk setiap update status'],
                            ];
                        @endphp
                        @foreach($notes as $note)
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-{{ $note['icon'] }} text-yellow-600 mt-1"></i>
                            <span class="text-gray-700">{{ $note['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Kontak Section -->
    <section class="section-padding bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium mb-4">
                    Hubungi Kami
                </span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Informasi Kontak</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Hubungi kami untuk informasi lebih lanjut mengenai layanan surat online
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Alamat Kantor</h3>
                            <p class="text-gray-600 text-sm">Jl. Raya Pabuaran Mekar No. 123, Cibinong, Kabupaten Bogor, Jawa Barat 16916</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Telepon</h3>
                            <p class="text-gray-600 text-sm">(0251) 123-4567</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                            <p class="text-gray-600 text-sm">kelpabuaranmekar@bogorkab.go.id</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Jam Pelayanan</h3>
                            <p class="text-gray-600 text-sm">Senin - Jumat: 08:00 - 16:00 WIB (Istirahat 12:00 - 13:00)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Butuh Bantuan Section -->
            <div class="bg-slate-900 rounded-lg shadow-lg p-8 text-center">
                <h3 class="text-2xl font-bold text-white mb-3">Butuh Bantuan?</h3>
                <p class="text-gray-300 mb-6">
                    Tim kami siap membantu Anda. Hubungi kami melalui WhatsApp untuk respon yang lebih cepat.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://wa.me/6281234567890" 
                       class="btn-primary px-6 py-3 rounded-lg text-white font-semibold flex items-center justify-center space-x-2">
                        <i class="fab fa-whatsapp text-xl"></i>
                        <span>WhatsApp: 0812-3456-7890</span>
                    </a>
                    <a href="tel:+622511234567" 
                       class="btn-outline px-6 py-3 rounded-lg font-semibold flex items-center justify-center space-x-2">
                        <i class="fas fa-phone"></i>
                        <span>Telepon Kantor</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section-padding bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-4">
                @php
                    $faqs = [
                        [
                            'q' => 'Berapa lama proses pembuatan surat?',
                            'a' => 'Proses verifikasi dan pembuatan surat memakan waktu 1-3 hari kerja tergantung jenis surat yang diajukan.'
                        ],
                        [
                            'q' => 'Apakah ada biaya untuk mengajukan surat?',
                            'a' => 'Semua layanan pembuatan surat di kelurahan ini GRATIS. Tidak ada biaya yang dipungut.'
                        ],
                        [
                            'q' => 'Bagaimana cara mengambil surat yang sudah jadi?',
                            'a' => 'Surat dapat diambil di kantor kelurahan dengan membawa KTP asli dan nomor pengajuan pada jam pelayanan.'
                        ],
                    ];
                @endphp

                @foreach($faqs as $faq)
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-question-circle text-yellow-600 mr-2"></i>
                        {{ $faq['q'] }}
                    </h3>
                    <p class="text-gray-600 ml-8">{{ $faq['a'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
