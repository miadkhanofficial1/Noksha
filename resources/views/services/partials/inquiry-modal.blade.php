<!-- SMART BRIEF INQUIRY MODAL -->
<div id="nokshaInquiryModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all my-8 max-h-[90vh] flex flex-col">
        <!-- MODAL HEADER -->
        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 via-white to-orange-50/40 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#20165c] text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#121826] mb-0">Direct Service Inquiry</h3>
                    <p class="text-xs text-slate-500 mb-0">Collaborate & negotiate directly with the creator</p>
                </div>
            </div>
            <button type="button" onclick="closeInquiryModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                ✕
            </button>
        </div>

        <!-- MODAL BODY (SCROLLABLE) -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <!-- SERVICE SUMMARY CARD -->
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3.5">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-200" id="inqThumbBox">
                    <img id="inqServiceThumb" src="" class="w-full h-full object-cover" alt="Thumbnail">
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span id="inqCategoryBadge" class="text-[10px] font-bold uppercase tracking-wider text-[#20165c] bg-[#20165c]/10 px-2 py-0.5 rounded">Category</span>
                        <span id="inqSlotBadge" class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">🟢 3 Slots Left</span>
                    </div>
                    <h4 id="inqServiceTitle" class="text-sm font-bold text-[#121826] truncate mb-0.5">Service Title</h4>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span id="inqCreatorName" class="font-medium">Creator Name</span>
                        <span>•</span>
                        <span class="font-bold text-[#20165c]" id="inqStartingPrice">From ৳600.00</span>
                    </div>
                </div>
            </div>

            <form id="inquiryForm" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- QUICK SELECTION CHIPS -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Quick Project Requirements
                    </label>
                    <div class="flex flex-wrap gap-2" id="inquiryChipsContainer">
                        <button type="button" onclick="toggleInquiryChip(this, 'Urgent Delivery')" class="inq-chip px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:border-[#20165c] transition cursor-pointer">
                            🚀 Urgent Delivery
                        </button>
                        <button type="button" onclick="toggleInquiryChip(this, 'Source File Needed')" class="inq-chip px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:border-[#20165c] transition cursor-pointer">
                            📁 Source File Needed
                        </button>
                        <button type="button" onclick="toggleInquiryChip(this, 'Commercial Use')" class="inq-chip px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:border-[#20165c] transition cursor-pointer">
                            💼 Commercial Use
                        </button>
                        <button type="button" onclick="toggleInquiryChip(this, 'Brand Guidelines Included')" class="inq-chip px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:border-[#20165c] transition cursor-pointer">
                            🎨 Brand Guidelines
                        </button>
                    </div>
                    <!-- Hidden input to store selected chips -->
                    <div id="hiddenChipsInputs"></div>
                </div>

                <!-- PROJECT BRIEF TEXTAREA -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Project Brief & Scope <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="brief" id="inquiryBriefInput" rows="4" required minlength="15" 
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-xs focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] focus:outline-none transition" 
                              placeholder="Describe your exact design goals, required formats, color preferences, copy/text, and references..."></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Be as detailed as possible to help the creator evaluate scope and send a customized proposal.</p>
                </div>

                <!-- BUDGET & TIMELINE -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Estimated Budget (BDT)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center font-bold text-[#f47402] text-sm">৳</span>
                            <input type="number" step="0.01" min="600" name="budget" id="inquiryBudgetInput" 
                                   class="w-full pl-7 pr-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs focus:border-[#20165c] focus:outline-none font-mono" 
                                   placeholder="600.00">
                        </div>
                        <span class="text-[10px] text-slate-400">Minimum platform standard: ৳600 BDT</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Desired Timeline (Days)
                        </label>
                        <div class="relative">
                            <input type="number" min="1" max="90" name="desired_days" id="inquiryDaysInput" 
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs focus:border-[#20165c] focus:outline-none font-mono" 
                                   placeholder="3">
                        </div>
                        <span class="text-[10px] text-slate-400">Default: Creator's base delivery</span>
                    </div>
                </div>

                <!-- OPTIONAL REFERENCE ATTACHMENT -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Attach Reference File / Mockup <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <input type="file" name="reference_file" id="inquiryFileInput" accept="image/*,.pdf,.zip" class="hidden" onchange="handleInquiryFileChange(this)">
                    
                    <div class="p-3 border border-dashed border-slate-300 hover:border-[#20165c] rounded-xl bg-slate-50 flex items-center justify-between gap-3 cursor-pointer transition" onclick="document.getElementById('inquiryFileInput').click()">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <span id="inquiryFileName" class="text-xs text-slate-600 truncate">Select file (Image, PDF, ZIP up to 15MB)</span>
                        </div>
                        <button type="button" class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition">
                            Browse
                        </button>
                    </div>
                </div>

                <!-- SUBMIT CTA -->
                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-sm shadow-md transition cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Send Project Inquiry</span>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">Opening an inquiry creates an escrow-secured chat thread. No charges apply until you fund a formal proposal.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var activeChips = [];

function openInquiryModal(service) {
    var modal = document.getElementById('nokshaInquiryModal');
    var form = document.getElementById('inquiryForm');
    if (!modal || !form) return;

    form.action = '/services/' + service.id + '/inquire';
    
    // Populate service summary
    document.getElementById('inqServiceTitle').textContent = service.title;
    document.getElementById('inqCategoryBadge').textContent = service.category || 'Creative';
    document.getElementById('inqStartingPrice').textContent = 'From ৳' + parseFloat(service.starting_price).toFixed(2);
    document.getElementById('inqCreatorName').textContent = service.creator_name || 'Creator';
    
    // Slot badge
    var slotEl = document.getElementById('inqSlotBadge');
    if (slotEl) {
        if (service.is_booked_out) {
            slotEl.className = 'text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200';
            slotEl.textContent = '🔴 Booked Out (Next Queue)';
        } else {
            slotEl.className = 'text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200';
            slotEl.textContent = '🟢 ' + service.available_slots + ' Slots Left';
        }
    }

    // Thumbnail
    var thumbImg = document.getElementById('inqServiceThumb');
    if (thumbImg) {
        if (service.preview_images && service.preview_images.length > 0) {
            thumbImg.src = service.preview_images[0];
            thumbImg.style.display = 'block';
        } else {
            thumbImg.style.display = 'none';
        }
    }

    // Default budget and delivery days
    document.getElementById('inquiryBudgetInput').value = service.starting_price;
    document.getElementById('inquiryDaysInput').value = service.delivery_days || 3;

    // Reset chips
    activeChips = [];
    syncChips();
    var allChipBtns = document.querySelectorAll('.inq-chip');
    allChipBtns.forEach(function(btn) {
        btn.classList.remove('bg-[#20165c]', 'text-white', 'border-[#20165c]');
        btn.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.display = 'flex';
}

function closeInquiryModal() {
    var modal = document.getElementById('nokshaInquiryModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
    }
}

function toggleInquiryChip(btn, chipText) {
    var idx = activeChips.indexOf(chipText);
    if (idx > -1) {
        activeChips.splice(idx, 1);
        btn.classList.remove('bg-[#20165c]', 'text-white', 'border-[#20165c]');
        btn.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
    } else {
        activeChips.push(chipText);
        btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-300');
        btn.classList.add('bg-[#20165c]', 'text-white', 'border-[#20165c]');
    }
    syncChips();
}

function syncChips() {
    var container = document.getElementById('hiddenChipsInputs');
    if (!container) return;
    container.innerHTML = '';
    activeChips.forEach(function(chip) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'chips[]';
        input.value = chip;
        container.appendChild(input);
    });
}

function handleInquiryFileChange(input) {
    var display = document.getElementById('inquiryFileName');
    if (input.files && input.files[0]) {
        display.textContent = input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
        display.classList.add('text-[#20165c]', 'font-semibold');
    }
}
</script>
