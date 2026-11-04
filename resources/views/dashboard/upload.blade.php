@php
    $categories = $categories ?? \App\Models\Category::all();
@endphp

<form action="{{ route('creator.assets.store') }}" method="POST" enctype="multipart/form-data" id="creatorAssetForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Cover Image Dropzone -->
        <div class="lg:col-span-5">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                Cover Preview Image
            </label>

            <div class="p-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl text-center bg-slate-950/60 cursor-pointer min-h-[260px] flex flex-col items-center justify-center transition-colors" id="dashImageDropZone" onclick="document.getElementById('cover_image_input').click();">
                <svg class="w-10 h-10 text-indigo-400 mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div class="font-semibold text-white text-sm mb-1">Click to upload cover image</div>
                <div class="text-xs text-slate-500 mb-3">PNG, JPG, or WEBP (Max 15MB)</div>
                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20">
                    Select Image
                </span>
                <input type="file" name="cover_image" id="cover_image_input" accept="image/*" class="hidden" onchange="handleCoverImageSelect(this)">
            </div>

            <div id="dashImagePreviewBox" class="hidden mt-3">
                <img id="dashImagePreviewImg" src="" class="rounded-xl border border-slate-700 w-full max-h-56 object-cover" alt="Preview">
                <div class="flex justify-between text-xs text-slate-400 mt-2">
                    <span id="dashImageFileName" class="font-medium text-emerald-400 truncate">image.jpg</span>
                    <button type="button" class="text-rose-400 hover:text-rose-300 font-semibold cursor-pointer" onclick="removeCoverImage()">Remove</button>
                </div>
            </div>
        </div>

        <!-- Asset Metadata -->
        <div class="lg:col-span-7 space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Asset Title <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="e.g. Modern FinTech Mobile App UI Kit" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Category <span class="text-rose-400">*</span></label>
                    <select name="category" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ (old('category') == $category->name || old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Price (BDT) <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold text-sm">৳</span>
                        <input type="number" step="0.01" min="0" name="price" class="w-full pl-8 pr-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none font-mono" placeholder="0.00 for Free" value="{{ old('price', '0.00') }}" required>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Asset Description</label>
                <textarea name="asset_description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="Provide a detailed description of your design asset...">{{ old('asset_description', old('description')) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Format & Dimensions</label>
                    <input type="text" name="dimensions" value="{{ old('dimensions') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="e.g. 1920x1080 px, Figma, PSD">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Search Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="mobile, ui kit, figma, dark mode">
                </div>
            </div>

            <!-- Source Digital Asset File Upload -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                    Source Digital Asset File (.PSD, .AI, .FIG, .PDF, .EPS, .ZIP) <span class="text-rose-400">*</span>
                </label>

                <input type="file" name="asset_file" id="asset_file_input" accept=".psd,.ai,.fig,.pdf,.eps,.zip,.rar,.png,.jpg" required class="hidden" onchange="handleAssetFileSelect(this)">

                <div class="p-4 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-xl bg-slate-950/60 transition-colors flex items-center justify-between gap-4 cursor-pointer" onclick="document.getElementById('asset_file_input').click();">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div id="assetFileNameDisplay" class="text-sm font-semibold text-white truncate">Click to select digital asset file</div>
                            <div id="assetFileSizeDisplay" class="text-xs text-slate-500">Supports .PSD, .AI, .FIG, .PDF, .EPS, .ZIP, .RAR (Max 100MB)</div>
                        </div>
                    </div>
                    <button type="button" class="shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20 transition-all cursor-pointer" onclick="event.stopPropagation(); document.getElementById('asset_file_input').click();">
                        Choose File
                    </button>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Upload your raw creative project file (Photoshop .PSD, Illustrator .AI, Figma, PDF or Archive).</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition-all cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Publish Asset to Noksha</span>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function handleCoverImageSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var nameEl = document.getElementById('dashImageFileName');
        if (nameEl) nameEl.textContent = file.name;
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewImg = document.getElementById('dashImagePreviewImg');
            var dropZone = document.getElementById('dashImageDropZone');
            var previewBox = document.getElementById('dashImagePreviewBox');
            if (previewImg) previewImg.src = e.target.result;
            if (dropZone) dropZone.classList.add('hidden');
            if (previewBox) previewBox.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeCoverImage() {
    var input = document.getElementById('cover_image_input');
    if (input) input.value = '';
    var dropZone = document.getElementById('dashImageDropZone');
    var previewBox = document.getElementById('dashImagePreviewBox');
    if (previewBox) previewBox.classList.add('hidden');
    if (dropZone) dropZone.classList.remove('hidden');
}

function handleAssetFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        var nameDisplay = document.getElementById('assetFileNameDisplay');
        var sizeDisplay = document.getElementById('assetFileSizeDisplay');
        if (nameDisplay) {
            nameDisplay.textContent = file.name;
            nameDisplay.classList.add('text-emerald-400');
        }
        if (sizeDisplay) {
            var ext = file.name.split('.').pop().toUpperCase();
            sizeDisplay.textContent = sizeMB + ' MB (' + ext + ')';
        }
    }
}
</script>
