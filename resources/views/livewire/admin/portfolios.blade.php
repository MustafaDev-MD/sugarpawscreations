<div class="p-4 sm:p-6 lg:p-8 rounded-2xl space-y-10 min-h-screen text-slate-100 antialiased w-full max-w-[1400px] mx-auto relative overflow-hidden">

    {{-- Ambient neon orbs --}}
    <div class="absolute top-[-15%] left-[-10%] w-[600px] h-[600px] bg-violet-600/20 rounded-full blur-[140px] pointer-events-none animate-pulse" style="animation-duration: 14s;"></div>
    <div class="absolute bottom-[10%] right-[-15%] w-[700px] h-[700px] bg-fuchsia-500/15 rounded-full blur-[160px] pointer-events-none"></div>
    <div class="absolute top-[40%] left-[40%] w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    {{-- HEADER --}}
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6 backdrop-blur-sm">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-violet-200 to-fuchsia-300 drop-shadow-sm">
                ✦ Manage Portfolios
            </h1>
            <p class="text-sm text-slate-400 mt-1.5">
                Upload and manage before/after portfolio items with categories.
            </p>
        </div>
    </div>

    {{-- FORM SECTION --}}
    <div
        x-data="{
            isDraggingBefore: false,
            isDraggingAfter: false,
            beforePreview: null,
            afterPreview: null,
            readFile(file, target) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => { this[target] = e.target.result; };
                reader.readAsDataURL(file);
            },
            resetPreviews() {
                this.beforePreview = null;
                this.afterPreview = null;
            }
        }"

        x-on:reset-previews.window="resetPreviews()"
        x-on:edit-mode-activated.window="
        beforePreview = null;
        afterPreview = null;
        $el.scrollIntoView({ behavior: 'smooth', block: 'start' });"

        class="relative z-10 p-6 sm:p-8 bg-white/[0.04] backdrop-blur-2xl border border-white/15 rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] space-y-6 w-full ring-1 ring-white/5 transition-all duration-300 hover:border-violet-500/30 cursor-pointer">

        {{-- Form mode indicator --}}
        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $editMode ? 'bg-amber-400' : 'bg-fuchsia-400' }}"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 {{ $editMode ? 'bg-amber-500' : 'bg-fuchsia-500' }}"></span>
            </span>
            <span class="text-[11px] font-bold uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r {{ $editMode ? 'from-amber-400 to-orange-400' : 'from-violet-400 to-fuchsia-400' }}">
                {{ $editMode ? 'Update Existing Portfolio' : 'Create New Portfolio' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6">

            {{-- TITLE --}}
            <div class="space-y-2 w-full flex flex-col">
                <label class="text-[11px] font-bold text-violet-300/80 uppercase tracking-wider pl-1">Title (Optional)</label>
                <input
                    type="text"
                    wire:model="title"
                    placeholder="e.g. Hair Transformation, Skin Glow Up"
                    class="w-full bg-black/40 border border-white/15 focus:border-fuchsia-500/60 focus:ring-4 focus:ring-fuchsia-500/15 p-3 pl-4 text-sm rounded-xl text-white placeholder-slate-500 outline-none transition-all duration-300 h-[52px] shadow-inner shadow-black/40">
                @error('title')
                <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- CATEGORY --}}
            <div class="space-y-2 w-full flex flex-col">
                <label class="text-[11px] font-bold text-violet-300/80 uppercase tracking-wider pl-1">Category</label>
                <select
                    wire:model="category_id"
                    class="w-full bg-black/40 border border-white/15 focus:border-fuchsia-500/60 focus:ring-4 focus:ring-fuchsia-500/15 p-3 pl-4 text-sm rounded-xl text-white outline-none transition-all duration-300 h-[52px] shadow-inner shadow-black/40">
                    <option value="" class="bg-zinc-900">— Select Category —</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" class="bg-zinc-900">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- BEFORE + AFTER IMAGES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- BEFORE IMAGE --}}
                <div class="space-y-2 flex flex-col">
                    <label class="text-[11px] font-bold text-fuchsia-300/80 uppercase tracking-wider pl-1">Before Image</label>
                    <div
                        @dragover.prevent="isDraggingBefore = true"
                        @dragleave.prevent="isDraggingBefore = false"
                        @drop.prevent="
                            isDraggingBefore = false;
                            const file = $event.dataTransfer.files[0];
                            if (file) {
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                $refs.beforeInput.files = dt.files;
                                $refs.beforeInput.dispatchEvent(new Event('change', { bubbles: true }));
                                readFile(file, 'beforePreview');
                            }
                        "
                        :class="isDraggingBefore ? 'border-fuchsia-500/80 bg-fuchsia-500/[0.06] scale-[1.01]' : 'border-white/15 hover:border-fuchsia-500/50 hover:bg-white/[0.02]'"
                        class="relative flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed rounded-2xl px-4 py-8 transition-all duration-300 shadow-inner shadow-black/40 cursor-pointer group min-h-[180px]">

                        <input
                            x-ref="beforeInput"
                            type="file"
                            wire:model="before_image"
                            accept="image/*"
                            @change="readFile($event.target.files[0], 'beforePreview')"
                            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">

                        {{-- Alpine preview (new file selected) --}}
                        <template x-if="beforePreview">
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-24 h-24 rounded-xl overflow-hidden border border-white/20 bg-black/50">
                                    <img :src="beforePreview" class="w-full h-full object-cover" alt="before preview">
                                </div>
                                <span class="text-[10px] text-fuchsia-300/80 font-bold uppercase tracking-widest">Click to replace</span>
                            </div>
                        </template>

                        {{-- Edit mode: show existing image (only when no new file selected) --}}
                        <template x-if="!beforePreview">
                            @if($editMode && $currentPortfolio?->before_image)
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-24 h-24 rounded-xl overflow-hidden border border-white/20 bg-black/50">
                                    <!-- <img src="{{ asset('storage/'.\App\Models\Portfolio::find($portfolioId)->before_image) }}" class="w-full h-full object-cover" alt="current before"> -->
                                    <img
                                        src="{{ asset('storage/'.$currentPortfolio->before_image) }}"
                                        class="w-full h-full object-cover"
                                        alt="current before">
                                </div>
                                <span class="text-[10px] text-slate-400">Current Before</span>
                                <span class="text-[10px] text-fuchsia-300/80 font-bold uppercase tracking-widest">Click to replace</span>
                            </div>
                            @else
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-fuchsia-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-200"><span class="text-fuchsia-300">Before</span> image</p>
                                <p class="text-[10px] text-slate-500">PNG, JPG, WEBP · 4MB max</p>
                            </div>
                            @endif
                        </template>

                    </div>
                    <div wire:loading wire:target="before_image" class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Uploading...
                    </div>
                    @error('before_image')
                    <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- AFTER IMAGE --}}
                <div class="space-y-2 flex flex-col">
                    <label class="text-[11px] font-bold text-cyan-300/80 uppercase tracking-wider pl-1">After Image</label>
                    <div
                        @dragover.prevent="isDraggingAfter = true"
                        @dragleave.prevent="isDraggingAfter = false"
                        @drop.prevent="
                            isDraggingAfter = false;
                            const file = $event.dataTransfer.files[0];
                            if (file) {
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                $refs.afterInput.files = dt.files;
                                $refs.afterInput.dispatchEvent(new Event('change', { bubbles: true }));
                                readFile(file, 'afterPreview');
                            }
                        "
                        :class="isDraggingAfter ? 'border-cyan-500/80 bg-cyan-500/[0.06] scale-[1.01]' : 'border-white/15 hover:border-cyan-500/50 hover:bg-white/[0.02]'"
                        class="relative flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed rounded-2xl px-4 py-8 transition-all duration-300 shadow-inner shadow-black/40 cursor-pointer group min-h-[180px]">

                        <input
                            x-ref="afterInput"
                            type="file"
                            wire:model="after_image"
                            accept="image/*"
                            @change="readFile($event.target.files[0], 'afterPreview')"
                            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">

                        {{-- Alpine preview (new file selected) --}}
                        <template x-if="afterPreview">
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-24 h-24 rounded-xl overflow-hidden border border-white/20 bg-black/50">
                                    <img :src="afterPreview" class="w-full h-full object-cover" alt="after preview">
                                </div>
                                <span class="text-[10px] text-cyan-300/80 font-bold uppercase tracking-widest">Click to replace</span>
                            </div>
                        </template>

                        {{-- Edit mode: show existing image (only when no new file selected) --}}
                        <template x-if="!afterPreview">
                            @if($editMode && $currentPortfolio?->after_image)
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-24 h-24 rounded-xl overflow-hidden border border-white/20 bg-black/50">
                                    <!-- <img src="{{ asset('storage/'.\App\Models\Portfolio::find($portfolioId)->after_image) }}" class="w-full h-full object-cover" alt="current after"> -->
                                    <img
                                        src="{{ asset('storage/'.$currentPortfolio->after_image) }}"
                                        class="w-full h-full object-cover"
                                        alt="current after">
                                </div>
                                <span class="text-[10px] text-slate-400">Current After</span>
                                <span class="text-[10px] text-cyan-300/80 font-bold uppercase tracking-widest">Click to replace</span>
                            </div>
                            @else
                            <div class="flex flex-col items-center gap-2 pointer-events-none">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-violet-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-cyan-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-200"><span class="text-cyan-300">After</span> image</p>
                                <p class="text-[10px] text-slate-500">PNG, JPG, WEBP · 4MB max</p>
                            </div>
                            @endif
                        </template>

                    </div>
                    <div wire:loading wire:target="after_image" class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Uploading...
                    </div>
                    @error('after_image')
                    <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="w-full">
                @if($editMode)
                <div class="flex gap-2.5">
                    <button wire:click="update" @click="resetPreviews()"
                        class="flex-1 h-[52px] bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 hover:shadow-[0_8px_25px_-5px_rgba(251,146,60,0.5)] hover:brightness-110 text-slate-950 font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 15M12 10v4l3 3" />
                        </svg>
                        Update Portfolio
                    </button>
                    <button wire:click="resetInput" @click="resetPreviews()"
                        class="px-6 h-[52px] bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 hover:border-white/20 font-bold text-xs uppercase tracking-widest rounded-xl transition-all duration-200 flex items-center justify-center cursor-pointer">
                        Cancel
                    </button>
                </div>
                @else
                <button wire:click="save" @click="resetPreviews()"
                    class="w-full h-[52px] bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 hover:shadow-[0_8px_25px_-5px_rgba(217,70,239,0.5)] hover:brightness-110 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-fuchsia-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Save Portfolio
                </button>
                @endif
            </div>

        </div>
    </div>

    {{-- BULK UPLOAD SECTION --}}
    <div
        x-data="{
            bulkBeforePreviews: [],
            bulkAfterPreviews: [],
            readMultiple(files, target) {
                this[target] = [];
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => { this[target].push(e.target.result); };
                    reader.readAsDataURL(file);
                });
            }
        }"
        class="relative z-10 p-6 sm:p-8 bg-white/[0.04] backdrop-blur-2xl border border-white/15 rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] space-y-6 w-full ring-1 ring-white/5">

        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[11px] font-bold uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-cyan-400">
                Bulk Upload — Multiple Before/After Pairs
            </span>
        </div>

        <p class="text-xs text-slate-400">
            Upload all <span class="text-fuchsia-300 font-bold">Before</span> images first, then all <span class="text-cyan-300 font-bold">After</span> images. Make sure both are selected in the same order.
        </p>

        <div class="grid grid-cols-1 gap-6">

            {{-- BULK CATEGORY --}}
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-violet-300/80 uppercase tracking-wider pl-1">Category</label>
                <select wire:model="bulk_category_id"
                    class="w-full bg-black/40 border border-white/15 focus:border-fuchsia-500/60 focus:ring-4 focus:ring-fuchsia-500/15 p-3 pl-4 text-sm rounded-xl text-white outline-none transition-all duration-300 h-[52px] shadow-inner shadow-black/40">
                    <option value="" class="bg-zinc-900">— Select Category —</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" class="bg-zinc-900">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('bulk_category_id')
                <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- BULK BEFORE IMAGES --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-fuchsia-300/80 uppercase tracking-wider pl-1">
                        Before Images
                        @if(count($bulk_before_images) > 0)
                        <span class="ml-2 text-emerald-400">({{ count($bulk_before_images) }} selected)</span>
                        @endif
                    </label>
                    <div class="relative flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed border-white/15 hover:border-fuchsia-500/50 rounded-2xl px-4 py-8 transition-all duration-300 cursor-pointer group min-h-[160px]">
                        <input type="file"
                            wire:model="bulk_before_images"
                            accept="image/*"
                            multiple
                            @change="readMultiple($event.target.files, 'bulkBeforePreviews')"
                            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">

                        <template x-if="bulkBeforePreviews.length > 0">
                            <div class="pointer-events-none space-y-2">
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    <template x-for="(src, i) in bulkBeforePreviews.slice(0, 6)" :key="i">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-white/20">
                                            <img :src="src" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <template x-if="bulkBeforePreviews.length > 6">
                                        <div class="w-12 h-12 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center text-xs text-white font-bold">
                                            +<span x-text="bulkBeforePreviews.length - 6"></span>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs text-fuchsia-300 font-semibold"><span x-text="bulkBeforePreviews.length"></span> Before images ready</p>
                                <span class="text-[10px] text-slate-400">Click to change</span>
                            </div>
                        </template>

                        <template x-if="bulkBeforePreviews.length === 0">
                            <div class="pointer-events-none flex flex-col items-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-fuchsia-500/20 to-violet-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-6 h-6 text-fuchsia-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-200"><span class="text-fuchsia-300">Click</span> to select multiple</p>
                                <p class="text-[10px] text-slate-500">Before images — same order mein</p>
                            </div>
                        </template>
                    </div>
                    <div wire:loading wire:target="bulk_before_images" class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Uploading...
                    </div>
                    @error('bulk_before_images') <span class="text-rose-400 text-xs pl-1">{{ $message }}</span> @enderror
                    @error('bulk_before_images.*') <span class="text-rose-400 text-xs pl-1">{{ $message }}</span> @enderror
                </div>

                {{-- BULK AFTER IMAGES --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-cyan-300/80 uppercase tracking-wider pl-1">
                        After Images
                        @if(count($bulk_after_images) > 0)
                        <span class="ml-2 text-emerald-400">({{ count($bulk_after_images) }} selected)</span>
                        @endif
                    </label>
                    <div class="relative flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed border-white/15 hover:border-cyan-500/50 rounded-2xl px-4 py-8 transition-all duration-300 cursor-pointer group min-h-[160px]">
                        <input type="file"
                            wire:model="bulk_after_images"
                            accept="image/*"
                            multiple
                            @change="readMultiple($event.target.files, 'bulkAfterPreviews')"
                            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">

                        <template x-if="bulkAfterPreviews.length > 0">
                            <div class="pointer-events-none space-y-2">
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    <template x-for="(src, i) in bulkAfterPreviews.slice(0, 6)" :key="i">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-white/20">
                                            <img :src="src" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <template x-if="bulkAfterPreviews.length > 6">
                                        <div class="w-12 h-12 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center text-xs text-white font-bold">
                                            +<span x-text="bulkAfterPreviews.length - 6"></span>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs text-cyan-300 font-semibold"><span x-text="bulkAfterPreviews.length"></span> After images ready</p>
                                <span class="text-[10px] text-slate-400">Click to change</span>
                            </div>
                        </template>

                        <template x-if="bulkAfterPreviews.length === 0">
                            <div class="pointer-events-none flex flex-col items-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-violet-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-6 h-6 text-cyan-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-200"><span class="text-cyan-300">Click</span> to select multiple</p>
                                <p class="text-[10px] text-slate-500">After images — same order mein</p>
                            </div>
                        </template>
                    </div>
                    <div wire:loading wire:target="bulk_after_images" class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Uploading...
                    </div>
                    @error('bulk_after_images') <span class="text-rose-400 text-xs pl-1">{{ $message }}</span> @enderror
                    @error('bulk_after_images.*') <span class="text-rose-400 text-xs pl-1">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- Count match warning --}}
            @if(count($bulk_before_images) > 0 && count($bulk_after_images) > 0 && count($bulk_before_images) !== count($bulk_after_images))
            <div class="p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-semibold">
                ⚠️ Before ({{ count($bulk_before_images) }}) aur After ({{ count($bulk_after_images) }}) images ki count match nahi karti!
            </div>
            @endif

            {{-- Count match success --}}
            @if(count($bulk_before_images) > 0 && count($bulk_after_images) > 0 && count($bulk_before_images) === count($bulk_after_images))
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs font-semibold">
                ✓ {{ count($bulk_before_images) }} pairs ready to upload!
            </div>
            @endif

            {{-- Save Bulk Button --}}
            <button wire:click="saveBulk"
                @click="bulkBeforePreviews = []; bulkAfterPreviews = [];"
                wire:loading.attr="disabled"
                class="w-full h-[52px] bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:shadow-[0_8px_25px_-5px_rgba(16,185,129,0.5)] hover:brightness-110 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="saveBulk">
                    <svg class="w-4 h-4 stroke-[2] inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload All Pairs
                </span>
                <span wire:loading wire:target="saveBulk" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    Uploading...
                </span>
            </button>

        </div>
    </div>

    {{-- CATEGORY FILTER --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <button
            wire:click="filterCategory('all')"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300 cursor-pointer transform
            {{ $selectedCategory == 'all'
                ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/30 border border-violet-500'
                : 'bg-white/5 text-slate-300 border border-white/10 hover:bg-violet-500 hover:text-white hover:border-violet-500 hover:shadow-lg hover:shadow-violet-500/30' }}">
            All
        </button>
        @foreach($categories as $category)
        <button
            wire:click="filterCategory({{ $category->id }})"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300 cursor-pointer transform
                {{ $selectedCategory == $category->id
                    ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/30 border border-violet-500'
                    : 'bg-white/5 text-slate-300 border border-white/10 hover:bg-violet-500 hover:text-white hover:border-violet-500 hover:shadow-lg hover:shadow-violet-500/30' }}">
            {{ $category->name }}
        </button>
        @endforeach

    </div>

    <div class="flex justify-between items-center">

        <div
            x-data="{ open:false }"
            class="relative w-44">
    
            <button
                @click="open=!open"
                type="button"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-white/5 border border-white/10 hover:border-fuchsia-500/40 text-white text-sm font-semibold backdrop-blur-xl cursor-pointer">
    
                <span>{{ $perPage }} Items</span>
    
                <svg class="w-4 h-4 text-violet-300">
                    <path fill="currentColor" d="M7 10l5 5 5-5z" />
                </svg>
            </button>
    
            <div
                x-show="open"
                @click.away="open=false"
                x-transition
                class="absolute z-50 mt-2 w-full rounded-xl bg-zinc-900/95 backdrop-blur-xl border border-white/10 overflow-hidden shadow-2xl">
    
                @foreach([5,10,15,25,50] as $size)
                <button
                    @click="open=false"
                    wire:click="$set('perPage', {{ $size }})"
                    class="w-full text-left px-4 py-3 text-sm transition-all cursor-pointer
                    {{ $perPage == $size
                        ? 'bg-fuchsia-500/20 text-fuchsia-300'
                        : 'text-slate-300 hover:bg-white/5' }}">
                    {{ $size }} Items
                </button>
                @endforeach
    
            </div>
        </div>
    
        <div class="text-sm text-slate-400">
            Showing
            <span class="text-fuchsia-300 font-semibold">
                {{ $portfolios->firstItem() ?? 0 }}
            </span>
            -
            <span class="text-fuchsia-300 font-semibold">
                {{ $portfolios->lastItem() ?? 0 }}
            </span>
            of
            <span class="text-violet-300 font-semibold">
                {{ $portfolios->total() }}
            </span>
            portfolios
        </div>

    </div>

    {{-- CARDS LIST --}}
    <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 w-full">

        @forelse($portfolios as $portfolio)
        <div wire:key="portfolio-{{ $portfolio->id }}" class="group relative bg-white/[0.03] backdrop-blur-2xl border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/[0.06] hover:border-violet-500/40 hover:shadow-2xl hover:shadow-violet-950/40 hover:-translate-y-1 transition-all duration-500 flex flex-col justify-between overflow-hidden h-full">

            <div class="absolute -inset-px bg-gradient-to-br from-violet-500/0 via-transparent to-fuchsia-500/0 group-hover:from-violet-500/15 group-hover:to-fuchsia-500/15 rounded-2xl transition-all duration-500 pointer-events-none"></div>

            <div class="relative z-10 w-full space-y-2">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="relative overflow-hidden rounded-xl h-32 bg-black/40 border border-white/5">
                        @if($portfolio->before_image)
                        <img loading="lazy" src="{{ asset('storage/'.$portfolio->before_image) }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-700 ease-out"
                            alt="before">
                        @endif
                        <span class="absolute bottom-1 left-1 text-[9px] font-bold uppercase tracking-widest bg-black/60 text-fuchsia-300 px-1.5 py-0.5 rounded-md backdrop-blur-sm">Before</span>
                    </div>
                    <div class="relative overflow-hidden rounded-xl h-32 bg-black/40 border border-white/5">
                        @if($portfolio->after_image)
                        <img loading="lazy" src="{{ asset('storage/'.$portfolio->after_image) }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-700 ease-out"
                            alt="after">
                        @endif
                        <span class="absolute bottom-1 left-1 text-[9px] font-bold uppercase tracking-widest bg-black/60 text-cyan-300 px-1.5 py-0.5 rounded-md backdrop-blur-sm">After</span>
                    </div>
                </div>

                <h2 class="font-bold text-sm text-white tracking-wide group-hover:text-fuchsia-300 transition-colors duration-300 line-clamp-1 px-1 mt-1">
                    {{ $portfolio->title ?: '—' }}
                </h2>
                <p class="text-[10px] text-slate-500 px-1 truncate">
                    {{ $portfolio->category->name ?? '—' }}
                </p>
            </div>

            <div class="relative z-10 flex gap-2.5 mt-5 pt-3.5 border-t border-white/10 w-full">
                <button wire:click="edit({{ $portfolio->id }})"
                    class="flex-1 py-2.5 bg-gradient-to-b from-amber-500/15 to-amber-500/5 hover:from-amber-500/30 hover:to-amber-500/15 text-amber-300 border border-amber-500/20 hover:border-amber-500/50 text-[10px] font-bold rounded-xl tracking-widest uppercase transition-all duration-200 flex items-center justify-center gap-1.5 backdrop-blur-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11 20H8v-3l9.414-9.414z" />
                    </svg>
                    Edit
                </button>
                <button
                    x-data
                    @click.prevent="
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This portfolio will be permanently deleted.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#e11d48',
                            cancelButtonColor: '#6b7280',
                            background: '#18181b',
                            color: '#f4f4f5'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.delete({{ $portfolio->id }});
                            }
                        })
                    "
                    class="flex-1 py-2.5 bg-gradient-to-b from-rose-500/15 to-rose-500/5 hover:from-rose-500/30 hover:to-rose-500/15 text-rose-300 border border-rose-500/20 hover:border-rose-500/50 text-[10px] font-bold rounded-xl tracking-widest uppercase transition-all duration-200 flex items-center justify-center gap-1.5 backdrop-blur-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-full flex justify-center items-center p-12 bg-white/[0.02] backdrop-blur-sm rounded-2xl border border-dashed border-white/15">
            <div class="text-center space-y-2">
                <div class="text-fuchsia-300/30 text-6xl mb-2">🖼️</div>
                <p class="text-sm text-slate-400">No portfolios yet. Upload your first before/after above ✨</p>
            </div>
        </div>
        @endforelse

        @if($portfolios->hasPages())
        <div class="col-span-full mt-8 cursor-pointer">
            {{ $portfolios->links(data: ['scrollTo' => false]) }}
        </div>
        @endif

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
                newestOnTop: true,
                preventDuplicates: true,
            };

            Livewire.on('success', (event) => {
                const message = Array.isArray(event) ? event[0]?.message : event?.message;
                toastr.success(message ?? 'Success');
            });

            Livewire.on('error', (event) => {
                const message = Array.isArray(event) ? event[0]?.message : event?.message;
                toastr.error(message ?? 'Something went wrong');
            });
        });
    </script>

    <style>
        #toast-container>div {
            background-color: rgba(24, 24, 27, 0.6) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 0.75rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
            opacity: 1 !important;
            white-space: nowrap;
            padding: 12px 16px 12px 50px;
            width: auto;
            min-width: 280px;
        }

        #toast-container>.toast-success {
            background-image: none !important;
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7 !important;
        }

        #toast-container>.toast-error {
            background-image: none !important;
            border: 1px solid rgba(244, 63, 94, 0.4);
            color: #fda4af !important;
        }

        #toast-container>div::before {
            font-family: "Font Awesome 5 Free", sans-serif;
            font-weight: 900;
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
        }

        #toast-container>.toast-success::before {
            content: "✓";
            color: #34d399;
        }

        #toast-container>.toast-error::before {
            content: "✕";
            color: #fb7185;
        }

        .toast-progress {
            opacity: 0.6;
        }

        #toast-container>.toast-success .toast-progress {
            background-color: #34d399;
        }

        #toast-container>.toast-error .toast-progress {
            background-color: #fb7185;
        }

        #toast-container .toast-message {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        #toast-container .toast-close-button {
            color: #d4d4d8;
            opacity: 0.7;
        }
    </style>

</div>