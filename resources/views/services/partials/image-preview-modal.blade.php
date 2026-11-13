<!-- IMAGE PREVIEW LIGHTBOX MODAL -->
<div id="nokshaImagePreviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md hidden transition-all" onclick="if(event.target === this) closeServicePreviewModal()">
    <div class="relative max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200">
        <div class="p-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h4 id="previewModalTitle" class="text-xs font-bold text-slate-800 truncate px-2 mb-0">Preview</h4>
            <button type="button" onclick="closeServicePreviewModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center cursor-pointer">
                ✕
            </button>
        </div>
        <div class="p-2 bg-slate-950 flex items-center justify-center max-h-[80vh] overflow-hidden">
            <img id="previewModalImg" src="" class="max-w-full max-h-[75vh] object-contain rounded-xl" alt="Enlarged Preview">
        </div>
    </div>
</div>

<script>
function openServicePreviewModal(url, title) {
    var modal = document.getElementById('nokshaImagePreviewModal');
    var img = document.getElementById('previewModalImg');
    var titleEl = document.getElementById('previewModalTitle');
    if (!modal || !img) return;

    img.src = url;
    if (titleEl) titleEl.textContent = title || 'Service Preview';
    modal.classList.remove('hidden');
}

function closeServicePreviewModal() {
    var modal = document.getElementById('nokshaImagePreviewModal');
    if (modal) modal.classList.add('hidden');
}
</script>
