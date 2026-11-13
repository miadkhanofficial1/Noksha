@php
    $categories = $categories ?? \App\Models\Category::all();
@endphp

<form action="{{ route('creator.assets.store') }}" method="POST" enctype="multipart/form-data" id="creatorAssetForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- LEFT COLUMN: Previews & Visual Showcase -->
        <div class="lg:col-span-5 space-y-5">
            <!-- 1. Primary Cover Image (Mandatory) -->
            <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-[#121826]">
                        Primary Cover Preview <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] font-medium text-slate-500">Image 1 of 5</span>
                </div>

                <div class="p-6 border-2 border-dashed border-slate-300 hover:border-[#20165c] rounded-xl text-center bg-white cursor-pointer min-h-[220px] flex flex-col items-center justify-center transition-colors" id="dashImageDropZone" onclick="document.getElementById('cover_image_input').click();">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="font-semibold text-slate-900 text-sm mb-1">Click to select primary cover</div>
                    <div class="text-xs text-slate-500 mb-3">PNG, JPG, or WEBP (Max 5MB)</div>
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-[#20165c] bg-[#20165c]/10 border border-[#20165c]/20 hover:bg-[#20165c]/15 transition">
                        Browse Image
                    </span>
                    <input type="file" name="cover_image" id="cover_image_input" accept="image/jpeg,image/png,image/webp,image/jpg" required class="hidden" onchange="handleCoverImageSelect(this)">
                </div>

                <div id="dashImagePreviewBox" class="hidden mt-3">
                    <div class="relative group rounded-xl overflow-hidden border border-slate-200">
                        <img id="dashImagePreviewImg" src="" class="w-full max-h-56 object-cover" alt="Cover Preview">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-800 shadow hover:bg-slate-100 cursor-pointer" onclick="document.getElementById('cover_image_input').click();">Change</button>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-600 text-white shadow hover:bg-rose-700 cursor-pointer" onclick="removeCoverImage()">Remove</button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-500 mt-2 px-1">
                        <span id="dashImageFileName" class="font-medium text-emerald-600 truncate max-w-[200px]">image.jpg</span>
                        <span class="text-slate-400">Main Cover</span>
                    </div>
                </div>
            </div>

            <!-- 2. Additional Preview Slides / Screenshots (Up to 4 more) -->
            <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-[#121826]">
                        Additional Showcase Slides
                    </label>
                    <span class="text-[11px] font-medium text-slate-500">Up to 4 images</span>
                </div>
                <p class="text-xs text-slate-500 mb-3">Add supplementary previews showing mockups, details, or color themes.</p>

                <div class="p-4 border-2 border-dashed border-slate-300 hover:border-[#20165c] rounded-xl text-center bg-white cursor-pointer transition-colors" onclick="document.getElementById('additional_previews_input').click();">
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-[#20165c]">
                        <svg class="w-4 h-4 shrink-0 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Preview Images (Max 4)</span>
                    </div>
                    <input type="file" name="preview_images[]" id="additional_previews_input" accept="image/jpeg,image/png,image/webp,image/jpg" multiple class="hidden" onchange="handleAdditionalPreviewsSelect(this)">
                </div>

                <!-- Preview Grid -->
                <div id="additionalPreviewsGrid" class="grid grid-cols-4 gap-2 mt-3 hidden"></div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Asset Details & Multi-Format Files -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Asset Title -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Asset Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none transition" placeholder="e.g. Modern FinTech Mobile App UI Kit" required>
            </div>

            <!-- Category & Price -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Category <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none transition" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ (old('category') == $category->name || old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Price (BDT) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#f47402] font-bold text-sm">৳</span>
                        <input type="number" step="0.01" min="0" name="price" class="w-full pl-8 pr-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none font-mono" placeholder="0.00 for Free" value="{{ old('price', '0.00') }}" required>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Asset Description</label>
                <textarea name="asset_description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none transition" placeholder="Provide a detailed description of your design asset...">{{ old('asset_description', old('description')) }}</textarea>
            </div>

            <!-- Format & Tags -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Format & Dimensions</label>
                    <input type="text" name="dimensions" value="{{ old('dimensions') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:outline-none" placeholder="e.g. 1920x1080 px, Figma, PSD">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Search Tags</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:border-[#20165c] focus:outline-none" placeholder="mobile, ui kit, figma, dark mode">
                </div>
            </div>

            <!-- 3. MAIN SOURCE DIGITAL FILE (Separated: PSD, AI, FIG, EPS, SVG, ZIP - Max 100MB) -->
            <div class="bg-purple-50/40 border border-purple-100 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-[#121826] flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span>Main Source Project File <span class="text-rose-500">*</span></span>
                    </label>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-[#20165c]/10 text-[#20165c]">Max 100MB</span>
                </div>
                <p class="text-[11px] text-slate-500 mb-3">Upload your raw creative project file: .PSD, .AI, .FIG, .EPS, .SVG, or .ZIP package.</p>

                <input type="file" name="source_file" id="source_file_input" accept=".psd,.ai,.fig,.eps,.svg,.zip,application/zip,application/x-zip-compressed,multipart/x-zip,application/octet-stream" required class="hidden" onchange="handleSourceFileSelect(this)">

                <div class="p-4 border-2 border-dashed border-slate-300 hover:border-[#20165c] rounded-xl bg-white transition-colors flex items-center justify-between gap-4 cursor-pointer" onclick="document.getElementById('source_file_input').click();">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-purple-100/70 border border-purple-200 text-[#20165c] flex items-center justify-center shrink-0">
                            <span id="sourceFileIconBadge" class="text-xs font-black uppercase">FILE</span>
                        </div>
                        <div class="min-w-0">
                            <div id="sourceFileNameDisplay" class="text-sm font-semibold text-slate-900 truncate">Choose main source file</div>
                            <div id="sourceFileSizeDisplay" class="text-xs text-slate-500">Supports .PSD, .AI, .FIG, .EPS, .SVG, .ZIP (Max 100MB)</div>
                        </div>
                    </div>
                    <button type="button" class="shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-[#20165c] hover:bg-[#181045] text-white shadow-sm transition cursor-pointer" onclick="event.stopPropagation(); document.getElementById('source_file_input').click();">
                        Browse File
                    </button>
                </div>
            </div>

            <!-- 4. OPTIONAL INSTRUCTION / GUIDELINE PDF (Max 20MB) -->
            <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#f47402]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Instruction / Guideline PDF <span class="text-slate-400 font-normal normal-case">(Optional)</span></span>
                    </label>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-[#f47402]/10 text-[#f47402]">Max 20MB</span>
                </div>
                <p class="text-[11px] text-slate-500 mb-3">Optional usage guide, license terms, font links, or setup instructions for the buyer.</p>

                <input type="file" name="instruction_pdf" id="instruction_pdf_input" accept=".pdf,application/pdf" class="hidden" onchange="handleInstructionPdfSelect(this)">

                <div class="p-3.5 border border-dashed border-slate-300 hover:border-[#f47402] rounded-xl bg-white transition-colors flex items-center justify-between gap-4 cursor-pointer" onclick="document.getElementById('instruction_pdf_input').click();">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 border border-orange-200 text-[#f47402] flex items-center justify-center shrink-0 text-xs font-bold">
                            PDF
                        </div>
                        <div class="min-w-0">
                            <div id="pdfFileNameDisplay" class="text-xs font-semibold text-slate-900 truncate">Attach instruction PDF (Optional)</div>
                            <div id="pdfFileSizeDisplay" class="text-[11px] text-slate-500">PDF Document (Max 20MB)</div>
                        </div>
                    </div>
                    <button type="button" class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-300 hover:border-slate-400 text-slate-700 transition cursor-pointer" onclick="event.stopPropagation(); document.getElementById('instruction_pdf_input').click();">
                        Attach PDF
                    </button>
                </div>
            </div>

            <!-- Submit CTA -->
            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-sm shadow-md transition-all cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Publish Asset to Noksha</span>
                </button>
                <p class="text-[11px] text-center text-slate-400 mt-2">All uploaded assets are automatically packaged and organized for instant buyer delivery.</p>
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

function handleAdditionalPreviewsSelect(input) {
    var grid = document.getElementById('additionalPreviewsGrid');
    if (!grid) return;
    grid.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        var maxFiles = Math.min(input.files.length, 4);
        grid.classList.remove('hidden');
        
        for (var i = 0; i < maxFiles; i++) {
            (function(file, index) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var thumb = document.createElement('div');
                    thumb.className = 'relative rounded-lg overflow-hidden border border-slate-200 aspect-video bg-slate-100';
                    thumb.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" alt="Slide ' + (index + 2) + '"><span class="absolute bottom-1 right-1 bg-black/60 text-white text-[9px] px-1 rounded">#' + (index + 2) + '</span>';
                    grid.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            })(input.files[i], i);
        }
    } else {
        grid.classList.add('hidden');
    }
}

function handleSourceFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        var nameDisplay = document.getElementById('sourceFileNameDisplay');
        var sizeDisplay = document.getElementById('sourceFileSizeDisplay');
        var badge = document.getElementById('sourceFileIconBadge');
        var ext = file.name.split('.').pop().toUpperCase();

        if (nameDisplay) {
            nameDisplay.textContent = file.name;
            nameDisplay.classList.add('text-emerald-700', 'font-bold');
        }
        if (sizeDisplay) {
            sizeDisplay.textContent = sizeMB + ' MB (' + ext + ' Format ready)';
        }
        if (badge) {
            badge.textContent = ext.substring(0, 4);
        }
    }
}

function handleInstructionPdfSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        var nameDisplay = document.getElementById('pdfFileNameDisplay');
        var sizeDisplay = document.getElementById('pdfFileSizeDisplay');

        if (nameDisplay) {
            nameDisplay.textContent = file.name;
            nameDisplay.classList.add('text-emerald-700', 'font-bold');
        }
        if (sizeDisplay) {
            sizeDisplay.textContent = sizeMB + ' MB (PDF Guide attached)';
        }
    }
}
</script>
