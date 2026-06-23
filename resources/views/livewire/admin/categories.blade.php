<div class="p-4 sm:p-6 lg:p-8 rounded-2xl space-y-10 min-h-screen text-slate-100 antialiased w-full max-w-[1400px] mx-auto relative overflow-hidden">

    {{-- Ambient neon orbs --}}
    <div class="absolute top-[-15%] left-[-10%] w-[600px] h-[600px] bg-violet-600/20 rounded-full blur-[140px] pointer-events-none animate-pulse" style="animation-duration: 14s;"></div>
    <div class="absolute bottom-[10%] right-[-15%] w-[700px] h-[700px] bg-fuchsia-500/15 rounded-full blur-[160px] pointer-events-none"></div>
    <div class="absolute top-[40%] left-[40%] w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    {{-- HEADER --}}
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6 backdrop-blur-sm">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-violet-200 to-fuchsia-300 drop-shadow-sm">
                ✦ Manage Categories
            </h1>
            <p class="text-sm text-slate-400 mt-1.5 flex items-center gap-1">
                Create, edit, and organize your system categories with a fluid glassmorphic interface.
            </p>
        </div>
    </div>

    {{-- SUCCESS / FLASH MESSAGE --}}
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2700)" x-transition.duration.300ms
        class="relative z-10 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 backdrop-blur-2xl rounded-2xl flex items-center gap-3 shadow-xl shadow-emerald-950/30 animate-fade-in w-full">
        <div class="p-1.5 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <span class="text-xs font-semibold tracking-wide">{{ session('message') }}</span>
    </div>
    @endif

    {{-- FORM SECTION --}}
    <div
        x-data="{
            isDragging: false,
            imagePreview: null,
            readFile(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => { this.imagePreview = e.target.result; };
                reader.readAsDataURL(file);
            },
            resetPreview() {
                this.imagePreview = null;
            }
        }"
        x-on:edit-mode-activated.window="
        imagePreview = null;
        $el.scrollIntoView({ behavior: 'smooth', block: 'start' });"
        class="relative z-10 p-6 sm:p-8 bg-white/[0.04] backdrop-blur-2xl border border-white/15 rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] space-y-6 w-full ring-1 ring-white/5 transition-all duration-300 hover:border-violet-500/30">

        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $editMode ? 'bg-amber-400' : 'bg-fuchsia-400' }}"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 {{ $editMode ? 'bg-amber-500' : 'bg-fuchsia-500' }}"></span>
            </span>
            <span class="text-[11px] font-bold uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r {{ $editMode ? 'from-amber-400 to-orange-400' : 'from-violet-400 to-fuchsia-400' }}">
                {{ $editMode ? 'Update Existing Category' : 'Create New Category' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6">

            {{-- NAME --}}
            <div class="space-y-2 w-full flex flex-col">
                <label class="text-[11px] font-bold text-violet-300/80 uppercase tracking-wider pl-1">Category Name</label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="e.g. Gaming Gadgets, Summer Collection"
                    class="w-full bg-black/40 border border-white/15 focus:border-fuchsia-500/60 focus:ring-4 focus:ring-fuchsia-500/15 p-3 pl-4 text-sm rounded-xl text-white placeholder-slate-500 outline-none transition-all duration-300 h-[52px] shadow-inner shadow-black/40">
                @error('name')
                <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- IMAGE UPLOAD --}}
            <div class="space-y-2 w-full flex flex-col">
                <label class="text-[11px] font-bold text-fuchsia-300/80 uppercase tracking-wider pl-1">Upload Image</label>

                <div
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="
                        isDragging = false;
                        const file = $event.dataTransfer.files[0];
                        if (file) {
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            $refs.fileInput.files = dt.files;
                            $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                            readFile(file);
                        }
                    "
                    :class="isDragging ? 'border-fuchsia-500/80 bg-fuchsia-500/[0.06] scale-[1.01]' : 'border-white/15 hover:border-fuchsia-500/50 hover:bg-white/[0.02]'"
                    class="relative flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed rounded-2xl px-6 py-10 sm:py-14 transition-all duration-300 shadow-inner shadow-black/40 cursor-pointer group">

                    <input
                        x-ref="fileInput"
                        type="file"
                        wire:model="image"
                        accept="image/*"
                        @change="readFile($event.target.files[0])"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">

                    {{-- Alpine preview: new file selected --}}
                    <template x-if="imagePreview">
                        <div class="flex flex-col items-center gap-3 pointer-events-none">
                            <div class="relative w-28 h-28 rounded-2xl overflow-hidden border border-white/20 bg-black/50 shadow-lg shadow-black/40">
                                <img :src="imagePreview" class="w-full h-full object-cover" alt="preview">
                            </div>
                            <span class="text-[10px] uppercase tracking-widest text-fuchsia-300/80 font-bold">Click or drop to replace</span>
                        </div>
                    </template>

                    {{-- No new file selected --}}
                    <template x-if="!imagePreview">
                        {{-- @if($editMode && $categoryId && \App\Models\Category::find($categoryId)?->image) --}}
                        @if($editMode && $existingImage)
                        {{-- Edit mode: show existing image --}}
                        <div class="flex flex-col items-center gap-3 pointer-events-none">
                            <div class="relative w-28 h-28 rounded-2xl overflow-hidden border border-white/20 bg-black/50 shadow-lg shadow-black/40">
                                <!-- src="{{ asset('storage/'.$existingImage) }}"  -->
                                <img 
                                    src="{{ url('/img/'.$existingImage) }}"
                                    class="w-full h-full object-cover" 
                                    alt="current image"
                                    >
                            </div>
                            <div class="text-xs text-slate-300 font-medium">Current image</div>
                            <span class="text-[10px] uppercase tracking-widest text-fuchsia-300/80 font-bold">Click or drop to replace</span>
                        </div>
                        @else
                        {{-- Empty state --}}
                        <div class="flex flex-col items-center gap-3 pointer-events-none">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-7 h-7 text-fuchsia-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-slate-200">
                                    <span class="text-fuchsia-300">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-[11px] text-slate-500">PNG, JPG or WEBP &middot; up to 2MB</p>
                            </div>
                        </div>
                        @endif
                    </template>

                </div>

                @error('image')
                <span class="text-rose-400 text-xs pl-1">{{ $message }}</span>
                @enderror

                <div wire:loading wire:target="image" class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    Uploading...
                </div>
            </div>

            {{-- ACTION BUTTON --}}
            <div class="w-full">
                @if($editMode)
                <div class="flex gap-2.5">
                    <button
                        wire:click="update"
                        @click="resetPreview()"
                        class="flex-1 h-[52px] bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 hover:shadow-[0_8px_25px_-5px_rgba(251,146,60,0.5)] hover:brightness-110 text-slate-950 font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer btn-press">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 15M12 10v4l3 3" />
                        </svg>
                        Update Category
                    </button>
                    <button
                        wire:click="resetInput"
                        @click="resetPreview()"
                        class="px-6 h-[52px] bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 hover:border-white/20 font-bold text-xs uppercase tracking-widest rounded-xl transition-all duration-200 flex items-center justify-center cursor-pointer btn-press">
                        Cancel
                    </button>
                </div>
                @else
                <button
                    wire:click="save"
                    @click="resetPreview()"
                    class="w-full h-[52px] bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 hover:shadow-[0_8px_25px_-5px_rgba(217,70,239,0.5)] hover:brightness-110 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-fuchsia-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer btn-press">
                    <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Save Category
                </button>
                @endif
            </div>
        </div>
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
                {{ $categories->firstItem() ?? 0 }}
            </span>
            -
            <span class="text-fuchsia-300 font-semibold">
                {{ $categories->lastItem() ?? 0 }}
            </span>
            of
            <span class="text-violet-300 font-semibold">
                {{ $categories->total() }}
            </span>
            categories
        </div>

    </div>


    {{-- CARDS LIST SECTION --}}
    <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 w-full">

        @forelse($categories as $cat)
        <div class="group relative bg-white/[0.03] backdrop-blur-2xl border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/[0.06] hover:border-violet-500/40 hover:shadow-2xl hover:shadow-violet-950/40 hover:-translate-y-1 transition-all duration-500 flex flex-col justify-between overflow-hidden h-full">

            <div class="absolute -inset-px bg-gradient-to-br from-violet-500/0 via-transparent to-fuchsia-500/0 group-hover:from-violet-500/15 group-hover:to-fuchsia-500/15 rounded-2xl transition-all duration-500 pointer-events-none"></div>

            <div class="relative z-10 w-full">
                <div class="overflow-hidden rounded-xl h-44 w-full mb-3.5 shadow-lg bg-black/40 border border-white/5">
                    @if($cat->image)
                    <!-- src="{{ asset('storage/'.$cat->image) }}"  -->
                    <img
                        src="{{ asset('storage/'.$cat->image) }}"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-700 ease-out"
                        alt="{{ $cat->name }}">
                    @else
                    <div class="h-44 w-full bg-gradient-to-br from-violet-950/50 to-fuchsia-950/40 flex flex-col items-center justify-center text-slate-500 gap-1.5 backdrop-blur-sm">
                        <svg class="w-8 h-8 stroke-[1.2] text-violet-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] font-medium tracking-wider uppercase text-slate-400">No Image Asset</span>
                    </div>
                    @endif
                </div>
                <h2 class="font-bold text-sm text-white tracking-wide group-hover:text-fuchsia-300 transition-colors duration-300 line-clamp-1 px-1">
                    {{ $cat->name }}
                </h2>
                <p class="text-[10px] text-slate-500 mt-1 px-1 truncate">
                    Slug: {{ $cat->slug }}
                </p>
            </div>

            <div class="relative z-10 flex gap-2.5 mt-5 pt-3.5 border-t border-white/10 w-full">
                <button wire:click="edit({{ $cat->id }})" class="flex-1 py-2.5 bg-gradient-to-b from-amber-500/15 to-amber-500/5 hover:from-amber-500/30 hover:to-amber-500/15 text-amber-300 border border-amber-500/20 hover:border-amber-500/50 text-[10px] font-bold rounded-xl tracking-widest uppercase transition-all duration-200 flex items-center justify-center gap-1.5 backdrop-blur-sm btn-press cursor-pointer">
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
                            text: 'This category will be permanently deleted. This action cannot be undone.',
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
                                $wire.delete({{ $cat->id }});
                            }
                        })
                    "
                    class="flex-1 py-2.5 bg-gradient-to-b from-rose-500/15 to-rose-500/5 hover:from-rose-500/30 hover:to-rose-500/15 text-rose-300 border border-rose-500/20 hover:border-rose-500/50 text-[10px] font-bold rounded-xl tracking-widest uppercase transition-all duration-200 flex items-center justify-center gap-1.5 backdrop-blur-sm btn-press cursor-pointer">
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
                <div class="text-fuchsia-300/30 text-6xl mb-2">⚡</div>
                <p class="text-sm text-slate-400">No categories yet. Create a stunning category above ✨</p>
            </div>
        </div>
        @endforelse

        <div class="col-span-full cursor-pointer">
            {{ $categories->links(data: ['scrollTo' => false]) }}
        </div>

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

            Livewire.on('deleted', (event) => {
                const message = Array.isArray(event) ? event[0]?.message : event?.message;
                toastr.error(message ?? 'Deleted');
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