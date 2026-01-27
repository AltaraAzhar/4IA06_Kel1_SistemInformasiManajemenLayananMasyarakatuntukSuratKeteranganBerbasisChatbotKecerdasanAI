@extends('layouts.app')

@section('title', 'Pengajuan Surat Online - Kelurahan Pabuaran Mekar')

@section('content')
<!-- Header Section (Dark Background) -->
<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Pengajuan Surat Online
            </h1>
            <p class="text-lg text-blue-100 max-w-3xl mx-auto">
                Pilih jenis surat yang ingin Anda ajukan dan lengkapi formulir pengajuan
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Layanan Surat Cards (Grid 2 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($layananSurat as $slug => $layanan)
                @php
                    $memerlukanEtiket = in_array($layanan['nama'], \App\Models\PengajuanSurat::layananEtiket());
                    // Define which services have downloadable forms
                    $hasDownloadableForms = in_array($slug, ['tidak-mampu', 'kematian']);
                    
                    // Define forms for each service with file mapping
                    $forms = [];
                    if ($slug === 'tidak-mampu') {
                        $forms = [
                            ['name' => 'Surat Pernyataan Keluarga Kurang Mampu', 'desc' => 'Harus diisi, ditandatangani, dan diketahui RT/RW dengan materai cukup', 'file' => 'pernyataan keluarga miskin.pdf']
                        ];
                    } elseif ($slug === 'kematian') {
                        $forms = [
                            ['name' => 'Formulir Pelaporan Kematian', 'desc' => 'Wajib diisi dengan lengkap dan benar', 'file' => 'Formulir pelaporan Kematian.pdf'],
                            ['name' => 'Surat Pernyataan Kematian', 'desc' => 'Jika meninggal di rumah dan tidak ada surat keterangan dari medis', 'file' => 'surat pernyataan Kematian.pdf']
                        ];
                    }
                @endphp
                
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <!-- Icon & Title -->
                        <div class="flex items-start space-x-4 mb-5">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas {{ $layanan['icon'] }} text-blue-600 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $layanan['nama'] }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $layanan['deskripsi'] }}</p>
                            </div>
                        </div>

                        @if($hasDownloadableForms && count($forms) > 0)
                            <!-- Downloadable Forms Section -->
                            <div class="mb-5">
                                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-3">
                                    <h4 class="text-sm font-bold text-blue-900 mb-1 flex items-center">
                                        <i class="fas fa-download mr-2"></i>
                                        Formulir yang Harus Diunduh & Dicetak
                                    </h4>
                                    <p class="text-xs text-blue-700">
                                        Download, isi dengan lengkap, cetak, tandatangan, lalu upload saat pengajuan
                                    </p>
                                </div>

                                <!-- Forms List -->
                                <div class="space-y-2 mb-4">
                                    @foreach($forms as $form)
                                        <div class="flex items-start justify-between bg-gray-50 border border-gray-200 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                            <div class="flex items-start space-x-3 flex-1">
                                                <i class="fas fa-file-pdf text-blue-600 text-lg mt-0.5"></i>
                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $form['name'] }}</p>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $form['desc'] }}</p>
                                                </div>
                                            </div>
                                            <!-- Button untuk preview modal (SEMUA layanan) -->
                                            <button type="button" 
                                                    onclick="openPreviewModal('{{ asset('formulir/' . ($form['file'] ?? '')) }}', '{{ $form['name'] }}', '{{ $form['file'] }}')"
                                                    class="ml-4 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors flex-shrink-0">
                                                Download
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Warning Alert -->
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                    <div class="flex">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-0.5"></i>
                                        <p class="text-xs text-yellow-800 font-medium">
                                            Formulir wajib diisi lengkap, dicetak, ditandatangani, lalu diupload saat pengisian form pengajuan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <a href="{{ route('user.pengajuan.form', $slug) }}" 
                           class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded-lg transition-all shadow-md hover:shadow-lg group">
                            <span>Isi Form Pengajuan</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

<!-- Modal Preview PDF (Universal untuk semua formulir) -->
<div id="preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4" role="dialog" aria-modal="true" aria-labelledby="preview-modal-title">
    <div class="w-full max-w-[90%] bg-white rounded-xl shadow-2xl relative" style="height: 80vh;">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-xl flex items-center justify-between">
            <div>
                <h2 id="preview-modal-title" class="text-lg font-bold text-white">Preview Formulir</h2>
                <p id="preview-modal-subtitle" class="text-sm text-blue-100 mt-1"></p>
            </div>
            <button type="button" 
                    onclick="closePreviewModal()"
                    class="text-white hover:text-gray-200 transition-colors"
                    aria-label="Tutup modal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body - PDF Preview -->
        <div class="p-4" style="height: calc(80vh - 140px);">
            <iframe id="preview-iframe" 
                    src="" 
                    class="w-full h-full border border-gray-200 rounded-lg"
                    style="min-height: 500px;">
            </iframe>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex items-center justify-end space-x-3 border-t">
            <button type="button" 
                    onclick="closePreviewModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                Tutup
            </button>
            <a id="download-link" 
               href="" 
               download
               class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center space-x-2">
                <i class="fas fa-download"></i>
                <span>Download</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openPreviewModal(pdfUrl, formName, fileName) {
    const modal = document.getElementById('preview-modal');
    const iframe = document.getElementById('preview-iframe');
    const downloadLink = document.getElementById('download-link');
    const modalTitle = document.getElementById('preview-modal-title');
    const modalSubtitle = document.getElementById('preview-modal-subtitle');
    
    // Set modal title and subtitle
    modalTitle.textContent = 'Preview ' + formName;
    modalSubtitle.textContent = fileName;
    
    // Set iframe source
    iframe.src = pdfUrl;
    
    // Set download link
    downloadLink.href = pdfUrl;
    downloadLink.download = fileName;
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    const modal = document.getElementById('preview-modal');
    const iframe = document.getElementById('preview-iframe');
    
    // Hide modal
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Clear iframe source
    iframe.src = '';
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('preview-modal');
        if (!modal.classList.contains('hidden')) {
            closePreviewModal();
        }
    }
});

// Close modal saat klik di backdrop
document.getElementById('preview-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
</script>
@endpush

@endsection

