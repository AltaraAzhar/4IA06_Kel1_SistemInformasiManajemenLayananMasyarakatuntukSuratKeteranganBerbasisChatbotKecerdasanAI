<!-- Document View Modal -->
<div id="documentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Dokumen Persyaratan</h3>
                <p class="text-sm text-gray-600 mt-1" id="modal-pengajuan-id"></p>
            </div>
            <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-gray-700">
                <span id="modal-doc-count">0</span> Dokumen Terupload • 
                Total: <span id="modal-total-size">0 Bytes</span>
            </p>
        </div>
        
        <div id="document-list" class="space-y-3 max-h-96 overflow-y-auto">
            <!-- Documents will be loaded here via AJAX -->
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="closeDocumentModal()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>


<!-- Revise Modal -->
<div id="reviseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Minta Revisi</h3>
            <button onclick="closeReviseModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="reviseForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan *</label>
                <textarea name="keterangan" rows="4" required placeholder="Masukkan keterangan revisi..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeReviseModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDocumentModal(pengajuanId) {
        // Fetch dokumen via AJAX
        fetch(`/admin/pengajuan/${pengajuanId}/documents`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-pengajuan-id').textContent = 'Pengajuan: ' + data.nomor_pengajuan;
                document.getElementById('modal-doc-count').textContent = data.dokumen.length;
                
                // Calculate total size
                let totalSize = 0;
                data.dokumen.forEach(doc => {
                    totalSize += doc.size || 0;
                });
                document.getElementById('modal-total-size').textContent = formatBytes(totalSize);
                
                // Render dokumen list
                const docList = document.getElementById('document-list');
                docList.innerHTML = '';
                data.dokumen.forEach((doc, index) => {
                    const docItem = document.createElement('div');
                    docItem.className = 'flex items-center justify-between p-3 border border-gray-200 rounded-lg';
                    docItem.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-${doc.type === 'pdf' ? 'pdf' : 'image'} text-blue-600 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${doc.name}</p>
                                <p class="text-xs text-gray-500">FILE • ${formatBytes(doc.size || 0)}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="${doc.url}" target="_blank" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">Buka</a>
                            <a href="${doc.url}" download class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">Download</a>
                        </div>
                    `;
                    docList.appendChild(docItem);
                });
                
                document.getElementById('documentModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat dokumen');
            });
    }

    function closeDocumentModal() {
        document.getElementById('documentModal').classList.add('hidden');
    }


    function openReviseModal(pengajuanId) {
        document.getElementById('reviseForm').action = `/admin/pengajuan/${pengajuanId}/revise`;
        document.getElementById('reviseModal').classList.remove('hidden');
    }

    function closeReviseModal() {
        document.getElementById('reviseModal').classList.add('hidden');
        document.getElementById('reviseForm').reset();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
</script>

