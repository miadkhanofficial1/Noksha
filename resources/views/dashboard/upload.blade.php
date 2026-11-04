@php
    $categories = $categories ?? \App\Models\Category::all();
@endphp

<form action="{{ route('creator.assets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Cover Image Dropzone -->
        <div class="lg:col-span-5">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                Cover Preview Image <span class="text-rose-400">*</span>
            </label>

            <div class="p-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl text-center bg-slate-950/60 cursor-pointer min-h-[260px] flex flex-col items-center justify-center transition-colors" id="dashImageDropZone" onclick="document.getElementById('dash_preview_image').click();">
                <svg class="w-10 h-10 text-indigo-400 mb-2 shrink-0" width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div class="font-semibold text-white text-sm mb-1">Click to upload cover image</div>
                <div class="text-xs text-slate-500 mb-3">PNG, JPG, or WEBP (Max 10MB)</div>
                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20">
                    Select Image
                </span>
                <input type="file" name="cover_image" id="dash_preview_image" class="hidden" accept="image/png,image/jpeg,image/webp,image/jpg" required onchange="handleDashImageSelect(this)">
            </div>

            <div id="dashImagePreviewBox" class="hidden mt-3">
                <img id="dashImagePreviewImg" src="" class="rounded-xl border border-slate-700 w-full max-h-56 object-cover" alt="Preview">
                <div class="flex justify-between text-xs text-slate-400 mt-2">
                    <span id="dashImageFileName" class="font-medium text-emerald-400 truncate">image.jpg</span>
                    <button type="button" class="text-rose-400 hover:text-rose-300 font-semibold cursor-pointer" onclick="removeDashImage()">Remove</button>
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
                    <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Asset Description <span class="text-rose-400">*</span></label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="Provide a detailed description of your design asset..." required>{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Format & Dimensions <span class="text-rose-400">*</span></label>
                    <input type="text" name="dimensions" value="{{ old('dimensions') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="e.g. 1584x396 px, AI, PSD, Figma" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Search Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-white placeholder-slate-500 text-sm focus:border-indigo-500 focus:outline-none" placeholder="mobile, ui kit, figma, dark mode">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Source Package (.ZIP / .RAR) <span class="text-rose-400">*</span></label>
                <input type="file" name="zip_file" accept=".zip,.rar,application/zip,application/x-zip-compressed" class="w-full px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800 text-slate-300 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer" required>
                <p class="text-[11px] text-slate-500 mt-1">Upload the master ZIP archive containing source files (AI, EPS, PSD, Figma, SVG).</p>
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
