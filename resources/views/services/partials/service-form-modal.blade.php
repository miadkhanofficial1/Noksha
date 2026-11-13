@php
    $userTemplates = $userTemplates ?? (auth()->check() ? auth()->user()->resources()->where('status', 'approved')->latest()->get() : collect());
    $serviceCategories = [
        'Graphics & Branding',
        'UI/UX Design',
        'Logo & Identity',
        'Social Media Kit',
        'Packaging & Labels',
        'Illustrations',
        '3D & Mockups',
        'Marketing Assets',
    ];
@endphp

<!-- CREATE / EDIT SERVICE MODAL -->
<div id="nokshaServiceFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all my-8 max-h-[92vh] flex flex-col">
        <!-- HEADER -->
        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 via-white to-orange-50/40 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#20165c] text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#121826] mb-0" id="serviceModalHeading">Create Creative Service</h3>
                    <p class="text-xs text-slate-500 mb-0">Offer bespoke custom projects protected by Noksha Smart Escrow</p>
                </div>
            </div>
            <button type="button" onclick="closeServiceFormModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                ✕
            </button>
        </div>

        <!-- BODY (SCROLLABLE) -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <form id="serviceActionForm" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div id="serviceMethodSpoof"></div>

                <!-- TITLE -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Service Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="formServiceTitle" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-sm focus:border-[#20165c] focus:outline-none" 
                           placeholder="e.g. Modern Minimalist Brand Identity & Logo Suite">
                </div>

                <!-- CATEGORY & STARTING PRICE -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" id="formServiceCategory" required class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-sm focus:border-[#20165c] focus:outline-none">
                            <option value="">Select Category</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Starting Price (BDT) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-[#f47402] text-sm">৳</span>
                            <input type="number" step="0.01" min="600" name="starting_price" id="formServicePrice" required 
                                   class="w-full pl-8 pr-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-sm focus:border-[#20165c] focus:outline-none font-mono" 
                                   placeholder="600.00" value="600.00">
                        </div>
                        <span class="text-[10px] text-slate-400">Minimum policy threshold: ৳600.00 BDT</span>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Detailed Service Scope & Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="formServiceDescription" rows="3" required minlength="20" 
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs focus:border-[#20165c] focus:outline-none" 
                              placeholder="Detail what is included in this service (deliverables, file types, process, and client prerequisites)..."></textarea>
                </div>

                <!-- SPECIFICATIONS: DELIVERY DAYS, CONCEPTS, REVISIONS, MAX SLOTS -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                            ⏱️ Delivery
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="number" name="delivery_days" id="formServiceDays" min="1" max="60" value="3" required 
                                   class="w-full px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-mono font-bold text-center">
                            <span class="text-[11px] text-slate-500">Days</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                            ✨ Concepts
                        </label>
                        <input type="number" name="concepts_count" id="formServiceConcepts" min="1" max="10" value="1" required 
                               class="w-full px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-mono font-bold text-center">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                            🔄 Revisions
                        </label>
                        <input type="number" name="revisions_count" id="formServiceRevisions" min="0" max="20" value="2" required 
                               class="w-full px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-mono font-bold text-center">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                            📦 Slots / Wk
                        </label>
                        <input type="number" name="max_slots" id="formServiceSlots" min="1" max="5" value="3" required 
                               class="w-full px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-mono font-bold text-center"
                               title="Max concurrent active orders (1 to 5)">
                    </div>
                </div>

                <!-- STATUS (ACTIVE / PAUSED) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Visibility Status <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-[#20165c] transition">
                            <input type="radio" name="status" id="formStatusActive" value="active" checked class="text-[#20165c] focus:ring-[#20165c]">
                            <div>
                                <div class="text-xs font-bold text-slate-900">🟢 Active</div>
                                <div class="text-[10px] text-slate-500">Visible on marketplace</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-amber-500 transition">
                            <input type="radio" name="status" id="formStatusPaused" value="paused" class="text-amber-600 focus:ring-amber-500">
                            <div>
                                <div class="text-xs font-bold text-slate-900">⏸️ Paused</div>
                                <div class="text-[10px] text-slate-500">Temporarily hide from public</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- SAMPLE IMAGES SELECTOR (MAX 3 PREVIEWS) -->
                <div class="bg-purple-50/40 p-4 rounded-2xl border border-purple-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wider text-[#121826] flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Sample Showcase Images (Max 3)</span>
                            </label>
                            <p class="text-[11px] text-slate-500 mb-0">Upload new images or pick from your existing approved Noksha assets</p>
                        </div>
                        <span class="text-xs font-bold text-[#20165c] px-2 py-0.5 rounded bg-purple-100" id="selectedImagesCountBadge">0 / 3 Images</span>
                    </div>

                    <!-- PREVIEW SLOTS (3 SLOTS) -->
                    <div class="grid grid-cols-3 gap-3" id="sampleImagesPreviewContainer">
                        <!-- Filled by JS -->
                    </div>

                    <!-- UPLOAD CONTROLS & GALLERY PICKER BUTTON -->
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <input type="file" name="sample_images[]" id="serviceSampleFilesInput" accept="image/jpeg,image/png,image/webp,image/jpg" multiple class="hidden" onchange="handleSampleFileUpload(this)">
                        
                        <button type="button" onclick="document.getElementById('serviceSampleFilesInput').click()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 hover:border-[#20165c] text-slate-700 transition cursor-pointer">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Upload Files</span>
                        </button>

                        @if(isset($userTemplates) && $userTemplates->count() > 0)
                            <button type="button" onclick="toggleGalleryPickerModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-[#20165c] bg-purple-100 hover:bg-purple-200 border border-purple-200 transition cursor-pointer">
                                <svg class="w-4 h-4 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span>Pick from Noksha Assets ({{ $userTemplates->count() }})</span>
                            </button>
                        @endif

                        <button type="button" onclick="clearSelectedSampleImages()" class="text-xs text-rose-500 hover:underline ml-auto cursor-pointer">Clear All</button>
                    </div>

                    <!-- Hidden input to store chosen asset paths -->
                    <input type="hidden" name="selected_asset_images" id="selectedAssetImagesInput" value="">
                </div>

                <!-- SUBMIT CTA -->
                <div class="pt-2">
                    <button type="submit" id="serviceFormSubmitBtn" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-sm shadow-md transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="serviceSubmitBtnText">Save & Publish Service</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- VISUAL GALLERY PICKER MODAL (SELECT EXISTING NOKSHA TEMPLATES) -->
@if(isset($userTemplates) && $userTemplates->count() > 0)
    <div id="nokshaGalleryPickerModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm hidden transition-all">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden my-8 max-h-[85vh] flex flex-col">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#121826] mb-0">Select Images from Your Noksha Assets</h3>
                    <p class="text-xs text-slate-500 mb-0">Click any asset thumbnail to add it to your service preview (Max 3)</p>
                </div>
                <button type="button" onclick="toggleGalleryPickerModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="p-6 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 flex-1">
                @foreach($userTemplates as $template)
                    @php
                        $coverPath = $template->thumbnail_path ?? $template->preview_image;
                        $coverUrl = $coverPath ? (str_starts_with($coverPath, 'http') ? $coverPath : asset('storage/' . ltrim(str_replace('storage/', '', $coverPath), '/'))) : null;
                    @endphp
                    @if($coverUrl)
                        <div class="group relative rounded-xl overflow-hidden border border-slate-200 aspect-video bg-slate-100 cursor-pointer hover:border-[#20165c] hover:shadow-md transition"
                             onclick="selectAssetImage('{{ $coverPath }}', '{{ $coverUrl }}')">
                            <img src="{{ $coverUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition" alt="{{ $template->title }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-2 text-white">
                                <span class="text-[11px] font-bold line-clamp-1 leading-tight">{{ $template->title }}</span>
                                <span class="text-[9px] text-purple-200 font-semibold">+ Select as Preview</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="toggleGalleryPickerModal()" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] transition cursor-pointer">
                    Done Selecting
                </button>
            </div>
        </div>
    </div>
@endif

<script>
var pickedAssetImages = [];

function openCreateServiceModal() {
    var modal = document.getElementById('nokshaServiceFormModal');
    var form = document.getElementById('serviceActionForm');
    var spoof = document.getElementById('serviceMethodSpoof');
    if (!modal || !form) return;

    form.action = "{{ route('services.store') }}";
    if (spoof) spoof.innerHTML = '';

    document.getElementById('serviceModalHeading').textContent = 'Create Creative Service';
    document.getElementById('serviceSubmitBtnText').textContent = 'Save & Publish Service';

    // Clear fields
    document.getElementById('formServiceTitle').value = '';
    document.getElementById('formServiceCategory').value = '';
    document.getElementById('formServicePrice').value = '600.00';
    document.getElementById('formServiceDescription').value = '';
    document.getElementById('formServiceDays').value = '3';
    document.getElementById('formServiceConcepts').value = '1';
    document.getElementById('formServiceRevisions').value = '2';
    document.getElementById('formServiceSlots').value = '3';
    document.getElementById('formStatusActive').checked = true;

    pickedAssetImages = [];
    renderSamplePreviews();

    modal.classList.remove('hidden');
}

function openEditServiceModal(service) {
    var modal = document.getElementById('nokshaServiceFormModal');
    var form = document.getElementById('serviceActionForm');
    var spoof = document.getElementById('serviceMethodSpoof');
    if (!modal || !form) return;

    form.action = '/services/' + service.id;
    if (spoof) spoof.innerHTML = '@method("PUT")';

    document.getElementById('serviceModalHeading').textContent = 'Edit Creative Service';
    document.getElementById('serviceSubmitBtnText').textContent = 'Update Service';

    document.getElementById('formServiceTitle').value = service.title || '';
    document.getElementById('formServiceCategory').value = service.category || '';
    document.getElementById('formServicePrice').value = service.starting_price || 600.00;
    document.getElementById('formServiceDescription').value = service.description || '';
    document.getElementById('formServiceDays').value = service.delivery_days || 3;
    document.getElementById('formServiceConcepts').value = service.concepts_count || 1;
    document.getElementById('formServiceRevisions').value = service.revisions_count || 2;
    document.getElementById('formServiceSlots').value = service.max_slots || 3;

    if (service.status === 'paused' || !service.is_active) {
        document.getElementById('formStatusPaused').checked = true;
    } else {
        document.getElementById('formStatusActive').checked = true;
    }

    pickedAssetImages = [];
    if (service.preview_images && Array.isArray(service.preview_images)) {
        service.preview_images.forEach(function(img) {
            var fullUrl = img.startsWith('http') ? img : '/storage/' + img.replace(/^storage\//, '');
            pickedAssetImages.push({ path: img, url: fullUrl });
        });
    }

    renderSamplePreviews();
    modal.classList.remove('hidden');
}

function closeServiceFormModal() {
    var modal = document.getElementById('nokshaServiceFormModal');
    if (modal) modal.classList.add('hidden');
}

function toggleGalleryPickerModal() {
    var modal = document.getElementById('nokshaGalleryPickerModal');
    if (modal) modal.classList.toggle('hidden');
}

function selectAssetImage(path, url) {
    if (pickedAssetImages.length >= 3) {
        alert('You have already selected the maximum of 3 sample images.');
        return;
    }

    // Check duplicate
    for (var i = 0; i < pickedAssetImages.length; i++) {
        if (pickedAssetImages[i].path === path) return;
    }

    pickedAssetImages.push({ path: path, url: url });
    renderSamplePreviews();
    toggleGalleryPickerModal();
}

function handleSampleFileUpload(input) {
    if (input.files && input.files.length > 0) {
        for (var i = 0; i < input.files.length; i++) {
            if (pickedAssetImages.length >= 3) break;
            (function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    pickedAssetImages.push({
                        path: '',
                        url: e.target.result,
                        isUpload: true
                    });
                    renderSamplePreviews();
                };
                reader.readAsDataURL(file);
            })(input.files[i]);
        }
    }
}

function removeSampleImage(index) {
    pickedAssetImages.splice(index, 1);
    renderSamplePreviews();
}

function clearSelectedSampleImages() {
    pickedAssetImages = [];
    var fileInput = document.getElementById('serviceSampleFilesInput');
    if (fileInput) fileInput.value = '';
    renderSamplePreviews();
}

function renderSamplePreviews() {
    var container = document.getElementById('sampleImagesPreviewContainer');
    var badge = document.getElementById('selectedImagesCountBadge');
    var input = document.getElementById('selectedAssetImagesInput');
    if (!container) return;

    container.innerHTML = '';
    var paths = [];

    for (var i = 0; i < 3; i++) {
        var box = document.createElement('div');
        box.className = 'relative rounded-xl border border-dashed border-slate-300 aspect-video bg-white flex items-center justify-center overflow-hidden';
        
        if (i < pickedAssetImages.length) {
            var item = pickedAssetImages[i];
            if (item.path) paths.push(item.path);

            box.className = 'relative rounded-xl border border-slate-300 aspect-video bg-slate-100 overflow-hidden shadow-xs';
            box.innerHTML = '<img src="' + item.url + '" class="w-full h-full object-cover">' +
                '<button type="button" onclick="removeSampleImage(' + i + ')" class="absolute top-1 right-1 w-5 h-5 bg-rose-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow hover:bg-rose-700 cursor-pointer">✕</button>' +
                '<span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-mono">#' + (i + 1) + '</span>';
        } else {
            box.innerHTML = '<span class="text-[10px] text-slate-400 font-medium">Slot ' + (i + 1) + '</span>';
        }

        container.appendChild(box);
    }

    if (badge) badge.textContent = pickedAssetImages.length + ' / 3 Images';
    if (input) input.value = paths.join(',');
}
</script>
