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
    <!-- x-data="{
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
    }" -->
    <div
        x-data="{
        isDragging: false,
        imagePreview: null,

        cropper: null,
        cropImageUrl: null,
        cropModal: false,

        readFile(file) {
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                toastr.error('Please select a valid image.');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                toastr.error('Image size must be less than 2MB.');
                return;
            }

            const reader = new FileReader();

            reader.onload = e => {

            this.cropImageUrl = e.target.result;

            this.cropModal = true;

            this.$nextTick(() => {

                const image = this.$refs.cropImage;

                if (!image) return;

                if (image.complete && image.naturalWidth > 0) {

                    this.initCropper();

                } else {

                    image.onload = () => {
                        this.initCropper();
                    };

                }

            });

        };

            reader.readAsDataURL(file);
        },

        initCropper() {
            const image = this.$refs.cropImage;

            if (!image) return;

            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }

            const createCropper = () => {

                if (!image.naturalWidth || !image.naturalHeight) {
                    return;
                }

                this.cropper = new Cropper(image, {

                    // 315 × 636
                    aspectRatio: 315 / 636,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.75,

                    responsive: true,
                    restore: false,

                    // Crop box
                    guides: true,
                    center: true,
                    highlight: false,

                    cropBoxMovable: true,
                    cropBoxResizable: true,

                    toggleDragModeOnDblclick: false,

                    // Image
                    movable: true,
                    zoomable: true,
                    zoomOnWheel: true,
                    zoomOnTouch: true,

                    // Rotation
                    rotatable: true,

                    // Scaling disable
                    scalable: false,

                    background: false,

                    checkOrientation: true,

                    ready() {
                        this.cropper.setCropBoxData({
                            width: 315,
                            height: 636
                        });
                    }
                });
            };

                if (image.complete && image.naturalWidth > 0) {
                    createCropper();
                } else {
                    image.onload = () => {
                        createCropper();
                    };
                }
            },

            applyCrop() {
                if (!this.cropper) {
                    toastr.error('Cropper is not ready.');
                    return;
                }

                const canvas = this.cropper.getCroppedCanvas({
                    width: 630,
                    height: 1272,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                    fillColor: '#ffffff'
                });

                if (!canvas) {
                    toastr.error('Unable to crop image.');
                    return;
                }

                canvas.toBlob((blob) => {

                    if (!blob) {
                        toastr.error('Unable to create cropped image.');
                        return;
                    }

                    const file = new File(
                        [blob],
                        'category-image-' + Date.now() + '.jpg',
                        {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        }
                    );

                    // Show cropped preview
                    this.imagePreview = canvas.toDataURL(
                        'image/jpeg',
                        0.90
                    );

                    // Upload cropped image to Livewire
                    this.$wire.upload(
                        'image',
                        file,

                        () => {
                            toastr.success(
                                'Cropped image uploaded successfully.'
                            );

                            this.closeCropper();
                        },

                        (error) => {
                            console.error(
                                'Livewire image upload error:',
                                error
                            );

                            toastr.error(
                                'Image upload failed.'
                            );
                        },

                        (event) => {
                            console.log(
                                'Upload progress:',
                                event.detail.progress + '%'
                            );
                        }
                    );

                }, 'image/jpeg', 0.90);
            },

            closeCropper() {
                this.cropModal = false;

                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }

                this.cropImageUrl = null;
            },

            resetPreview() {
                this.imagePreview = null;

                this.$wire.set('image', null);

                // File input reset
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }

                // Cropper close
                this.closeCropper();
            },

            resetFormPreview() {
                this.imagePreview = null;
                this.cropImageUrl = null;
                this.cropModal = false;

                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }

                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        }"

        x-on:edit-mode-activated.window="
            resetFormPreview();

            $el.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        "

        x-on:category-form-reset.window="
            resetFormPreview();
        "

        class="relative z-10 p-6 sm:p-8 bg-white/[0.04] backdrop-blur-2xl border border-white/15 rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] space-y-6 w-full ring-1 ring-white/5 transition-all duration-300 hover:border-violet-500/30">

        <!-- HEADER -->
        <div class="flex items-center gap-2">

            <span class="relative flex h-2 w-2">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $editMode ? 'bg-amber-400' : 'bg-fuchsia-400' }}"></span>

                <span
                    class="relative inline-flex rounded-full h-2 w-2 {{ $editMode ? 'bg-amber-500' : 'bg-fuchsia-500' }}"></span>
            </span>

            <span
                class="text-[11px] font-bold uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r {{ $editMode ? 'from-amber-400 to-orange-400' : 'from-violet-400 to-fuchsia-400' }}">
                {{ $editMode ? 'Update Existing Category' : 'Create New Category' }}
            </span>

        </div>


        <!-- FORM -->
        <div class="grid grid-cols-1 gap-6">

            <!-- CATEGORY NAME -->
            <div class="space-y-2 w-full flex flex-col">

                <label
                    class="text-[11px] font-bold text-violet-300/80 uppercase tracking-wider pl-1">
                    Category Name
                </label>

                <input
                    type="text"
                    wire:model="name"
                    placeholder="e.g. Gaming Gadgets, Summer Collection"
                    class="w-full bg-black/40 border border-white/15 focus:border-fuchsia-500/60 focus:ring-4 focus:ring-fuchsia-500/15 p-3 pl-4 text-sm rounded-xl text-white placeholder-slate-500 outline-none transition-all duration-300 h-[52px] shadow-inner shadow-black/40">

                @error('name')
                <span class="text-rose-400 text-xs pl-1">
                    {{ $message }}
                </span>
                @enderror

            </div>


            <!-- IMAGE UPLOAD -->
            <div class="space-y-2 w-full flex flex-col">

                <label
                    class="text-[11px] font-bold text-fuchsia-300/80 uppercase tracking-wider pl-1">
                    Upload Image
                </label>


                <!-- DROP AREA -->
                <div
                    @dragover.prevent="isDragging = true"

                    @dragleave.prevent="isDragging = false"

                    @drop.prevent="
                    isDragging = false;

                    const file = $event.dataTransfer.files[0];

                    if (file) {
                        readFile(file);
                    }
                "

                    :class="
                    isDragging
                        ? 'border-fuchsia-500/80 bg-fuchsia-500/[0.06] scale-[1.01]'
                        : 'border-white/15 hover:border-fuchsia-500/50 hover:bg-white/[0.02]'
                "

                    class="relative isolate flex flex-col items-center justify-center text-center bg-black/40 border-2 border-dashed rounded-2xl px-6 py-10 sm:py-14 transition-all duration-300 shadow-inner shadow-black/40 cursor-pointer group">

                    <!-- FILE INPUT -->
                    <input
                        x-ref="fileInput"
                        type="file"
                        accept="image/*"
                        @change="readFile($event.target.files[0])"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-0">


                    <!-- NEW IMAGE PREVIEW -->
                    <template x-if="imagePreview">

                        <div class="relative z-10 flex flex-col items-center gap-3">

                            {{-- IMAGE PREVIEW --}}
                            <div
                                class="relative w-28 h-40 rounded-2xl overflow-hidden border border-white/20 bg-black/50 shadow-lg shadow-black/40">

                                <img
                                    :src="imagePreview"
                                    class="w-full h-full object-cover"
                                    alt="preview">

                            </div>

                            <span
                                class="text-[10px] uppercase tracking-widest text-fuchsia-300/80 font-bold">
                                Image selected
                            </span>


                            {{-- BUTTONS --}}
                            <div class="relative z-30 flex items-center gap-2">

                                {{-- REMOVE --}}
                                <button
                                    type="button"
                                    @click.stop.prevent="resetPreview()"
                                    class="relative z-50 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 hover:border-rose-500/40 text-rose-300 hover:text-rose-200 text-[10px] font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer">

                                    <svg
                                        class="w-3.5 h-3.5 pointer-events-none"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                                    </svg>

                                    Remove Image

                                </button>


                                {{-- REPLACE --}}
                                <button
                                    type="button"
                                    @click.stop.prevent="$refs.fileInput.click()"
                                    class="relative z-50 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 hover:border-violet-500/40 text-violet-300 hover:text-violet-200 text-[10px] font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer">

                                    <svg
                                        class="w-3.5 h-3.5 pointer-events-none"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M8 12l4-4m0 0l4 4m-4-4v9" />

                                    </svg>

                                    Replace

                                </button>

                            </div>

                        </div>

                    </template>


                    <!-- NO NEW IMAGE -->
                    <template x-if="!imagePreview">

                        @if($editMode && $existingImage)

                        <div
                            class="flex flex-col items-center gap-3 pointer-events-none">

                            <div
                                class="relative w-28 h-28 rounded-2xl overflow-hidden border border-white/20 bg-black/50 shadow-lg shadow-black/40">

                                <img
                                    src="{{ asset('storage/'.$existingImage) }}"
                                    class="w-full h-full object-cover"
                                    alt="current image">

                            </div>

                            <div class="text-xs text-slate-300 font-medium">
                                Current image
                            </div>

                            <span
                                class="text-[10px] uppercase tracking-widest text-fuchsia-300/80 font-bold">
                                Click or drop to replace
                            </span>

                        </div>

                        @else

                        <div
                            class="flex flex-col items-center gap-3 pointer-events-none">

                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 border border-white/10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">

                                <svg
                                    class="w-7 h-7 text-fuchsia-300 stroke-[1.5]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
                                </svg>

                            </div>

                            <div class="space-y-1">

                                <p class="text-sm font-semibold text-slate-200">

                                    <span class="text-fuchsia-300">
                                        Click to upload
                                    </span>

                                    or drag and drop

                                </p>

                                <p class="text-[11px] text-slate-500">
                                    PNG, JPG or WEBP · up to 2MB
                                </p>

                            </div>

                        </div>

                        @endif

                    </template>

                </div>


                @error('image')
                <span class="text-rose-400 text-xs pl-1">
                    {{ $message }}
                </span>
                @enderror


                <!-- UPLOAD LOADING -->
                <div
                    wire:loading
                    wire:target="image"
                    class="flex items-center gap-2 text-[11px] text-violet-300/80 pl-1">

                    <svg
                        class="w-3.5 h-3.5 animate-spin"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>

                    Uploading...

                </div>

            </div>


            <!-- ACTION BUTTONS -->
            <div class="w-full">

                @if($editMode)

                <div class="flex gap-2.5">

                    <button
                        wire:click="update"
                        class="flex-1 h-[52px] bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 hover:shadow-[0_8px_25px_-5px_rgba(251,146,60,0.5)] hover:brightness-110 text-slate-950 font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer btn-press">

                        <svg
                            class="w-4 h-4 stroke-[2]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 15M12 10v4l3 3" />
                        </svg>

                        Update Category

                    </button>


                    <button
                        wire:click="resetInput"
                        class="px-6 h-[52px] bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 hover:border-white/20 font-bold text-xs uppercase tracking-widest rounded-xl transition-all duration-200 flex items-center justify-center cursor-pointer btn-press">
                        Cancel
                    </button>

                </div>

                @else

                <button
                    wire:click="save"
                    class="w-full h-[52px] bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 hover:shadow-[0_8px_25px_-5px_rgba(217,70,239,0.5)] hover:brightness-110 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-fuchsia-900/30 active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer btn-press">

                    <svg
                        class="w-4 h-4 stroke-[2]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4v16m8-8H4" />
                    </svg>

                    Save Category

                </button>

                @endif

            </div>

        </div>

        <!-- ====================================================== -->
        <!-- FULL SCREEN IMAGE CROPPER MODAL -->
        <!-- ====================================================== -->

        <template x-teleport="body">

            <div
                x-show="cropModal"
                x-cloak
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-[999999] w-screen h-screen"
                style="display: none;">

                <!-- ================================================== -->
                <!-- FULL SCREEN BACKDROP -->
                <!-- ================================================== -->

                <div
                    class="absolute inset-0 bg-slate-950/75 backdrop-blur-2xl"
                    @click="closeCropper()"></div>


                <!-- ================================================== -->
                <!-- MODAL CENTER WRAPPER -->
                <!-- ================================================== -->

                <div
                    class="relative z-10 w-full h-full flex items-center justify-center p-3 sm:p-5 lg:p-8">

                    <!-- ================================================== -->
                    <!-- MODAL -->
                    <!-- ================================================== -->

                    <div
                        class="relative w-full max-w-6xl h-auto max-h-[94vh] overflow-hidden rounded-[28px] border border-violet-400/20 bg-slate-900/95 shadow-[0_30px_100px_-20px_rgba(0,0,0,0.9)] backdrop-blur-xl flex flex-col"
                        @click.stop>

                        <!-- ================================================== -->
                        <!-- HEADER -->
                        <!-- ================================================== -->

                        <div
                            class="shrink-0 flex items-center justify-between px-5 sm:px-7 py-4 sm:py-5 border-b border-white/10 bg-gradient-to-r from-violet-500/[0.08] via-transparent to-fuchsia-500/[0.08]">

                            <div class="flex items-center gap-3">

                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 border border-violet-400/20 flex items-center justify-center">

                                    <svg
                                        class="w-5 h-5 text-fuchsia-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M3 7h4l2-3h6l2 3h4a2 2 0 012 2v9a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 012-2z" />

                                        <circle
                                            cx="12"
                                            cy="13"
                                            r="3.5" />
                                    </svg>

                                </div>


                                <div>

                                    <h3 class="text-base sm:text-lg font-bold text-white">
                                        Crop Image
                                    </h3>

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Select the area you want to use
                                    </p>

                                </div>

                            </div>


                            <!-- CLOSE -->
                            <button
                                type="button"
                                @click="closeCropper()"
                                class="w-10 h-10 rounded-xl bg-white/5 hover:bg-rose-500/10 border border-white/10 hover:border-rose-400/30 text-slate-400 hover:text-rose-300 flex items-center justify-center transition-all duration-200 cursor-pointer">

                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>

                            </button>

                        </div>


                        <!-- ================================================== -->
                        <!-- CROP AREA -->
                        <!-- ================================================== -->

                        <!-- <div
                            class="flex-1 min-h-0 p-4 sm:p-6 lg:p-7 overflow-hidden">

                            <div
                                class="relative w-full h-[55vh] min-h-[280px] max-h-[600px] rounded-2xl overflow-hidden border border-white/10 bg-black/70">

                                <img
                                    x-ref="cropImage"
                                    :src="cropImageUrl"
                                    alt="Crop image">

                            </div>

                        </div> -->

                        <div
                            class="relative w-full h-[55vh] min-h-[300px] max-h-[600px] rounded-2xl overflow-hidden border border-white/10 bg-black">
                            <img
                                x-ref="cropImage"
                                :src="cropImageUrl"
                                alt="Crop image">
                        </div>


                        <!-- ================================================== -->
                        <!-- CONTROLS -->
                        <!-- ================================================== -->

                        <div
                            class="shrink-0 px-4 sm:px-6 lg:px-7 pb-4">

                            <div
                                class="flex flex-wrap items-center justify-center gap-2.5">

                                <button
                                    type="button"
                                    @click="cropper && cropper.zoom(0.1)"
                                    class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-violet-500/10 border border-white/10 hover:border-violet-400/30 text-slate-300 hover:text-violet-200 text-xs font-semibold transition-all cursor-pointer">
                                    + Zoom
                                </button>


                                <button
                                    type="button"
                                    @click="cropper && cropper.zoom(-0.1)"
                                    class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-violet-500/10 border border-white/10 hover:border-violet-400/30 text-slate-300 hover:text-violet-200 text-xs font-semibold transition-all cursor-pointer">
                                    − Zoom
                                </button>


                                <button
                                    type="button"
                                    @click="cropper && cropper.rotate(-90)"
                                    class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-violet-500/10 border border-white/10 hover:border-violet-400/30 text-slate-300 hover:text-violet-200 text-xs font-semibold transition-all cursor-pointer">
                                    ↶ Rotate
                                </button>


                                <button
                                    type="button"
                                    @click="cropper && cropper.rotate(90)"
                                    class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-violet-500/10 border border-white/10 hover:border-violet-400/30 text-slate-300 hover:text-violet-200 text-xs font-semibold transition-all cursor-pointer">
                                    ↷ Rotate
                                </button>


                                <button
                                    type="button"
                                    @click="cropper && cropper.reset()"
                                    class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-violet-500/10 border border-white/10 hover:border-violet-400/30 text-slate-300 hover:text-violet-200 text-xs font-semibold transition-all cursor-pointer">
                                    Reset
                                </button>

                            </div>

                        </div>


                        <!-- ================================================== -->
                        <!-- FOOTER -->
                        <!-- ================================================== -->

                        <div
                            class="shrink-0 flex flex-col sm:flex-row gap-3 px-5 sm:px-7 py-4 border-t border-white/10 bg-slate-950/50">

                            <button
                                type="button"
                                @click="closeCropper()"
                                class="flex-1 h-12 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-slate-300 text-xs font-bold uppercase tracking-widest transition-all cursor-pointer">
                                Cancel
                            </button>


                            <button
                                type="button"
                                @click="applyCrop()"
                                class="flex-1 h-12 rounded-xl bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 hover:brightness-110 text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-fuchsia-900/20 transition-all cursor-pointer">
                                Crop & Use Image
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </template>

    </div>

    <div class="flex justify-between items-center">

        <div
            x-data="{ open:false }"
            class="relative w-44">

            <button
                @click="open=!open"
                type="button"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-white/5 border border-white/10 hover:border-fuchsia-500/40 text-white text-sm font-semibold backdrop-blur-xl cursor-pointer">

                <span>
                    {{ $perPage === 'all' ? 'All Items' : $perPage . ' Items' }}
                </span>

                <svg
                    class="w-4 h-4 text-violet-300"
                    viewBox="0 0 24 24"
                    fill="currentColor">
                    <path d="M7 10l5 5 5-5z" />
                </svg>

            </button>

            <div
                x-show="open"
                @click.away="open=false"
                x-transition
                class="absolute z-50 mt-2 w-full rounded-xl bg-zinc-900/95 backdrop-blur-xl border border-white/10 overflow-hidden shadow-2xl">

                @foreach([5, 10, 15, 25, 50] as $size)

                <button
                    type="button"
                    @click="open=false"
                    wire:click="$set('perPage', {{ $size }})"
                    class="w-full text-left px-4 py-3 text-sm transition-all cursor-pointer
                {{ $perPage == $size
                    ? 'bg-fuchsia-500/20 text-fuchsia-300'
                    : 'text-slate-300 hover:bg-white/5' }}">

                    {{ $size }} Items

                </button>

                @endforeach

                {{-- ALL --}}
                <button
                    type="button"
                    @click="open=false"
                    wire:click="$set('perPage', 'all')"
                    class="w-full text-left px-4 py-3 text-sm transition-all cursor-pointer
            {{ $perPage === 'all'
                ? 'bg-fuchsia-500/20 text-fuchsia-300'
                : 'text-slate-300 hover:bg-white/5' }}">

                    All Items

                </button>

            </div>

        </div>

        <div class="text-sm text-slate-400">

            @if($perPage === 'all')

                Showing
                <span class="text-fuchsia-300 font-semibold">
                    {{ $categories->count() }}
                </span>

                of

                <span class="text-violet-300 font-semibold">
                    {{ $categories->count() }}
                </span>

                categories

            @else

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

            @endif

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
                    <img
                        src="{{ asset('storage/'.$cat->image) }}?v={{ $cat->updated_at?->timestamp }}"
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

        @if($perPage !== 'all')
        <div class="col-span-full cursor-pointer">
            {{ $categories->links(data: ['scrollTo' => false]) }}
        </div>
        @endif

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

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

            // Refresh page after category update
            Livewire.on('category-updated', () => {
                window.location.reload();
            });

        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .cropper-view-box {
            outline: 1px solid rgba(255, 255, 255, 0.7);
        }

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