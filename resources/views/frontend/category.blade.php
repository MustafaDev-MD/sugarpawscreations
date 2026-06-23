@extends('frontend.layouts.app')

@section('title', $category->name)

@section('content')

<!-- ================================
			///// Begin page header section /////
			================================= -->
<section id="page-header-secion">

    <!-- Begin page header image 
				===============================
				* Use class "parallax" to enable parallax effect.
				-->
    {{-- HTML me humne asset ko style se bahar nikal kar data-bg me rakh diya, ab editor kabhi error nahi dega --}}
    <div class="page-header-image parallax bg-image"
        id="banner-view"
        data-bg="{{ asset('assets/img/background-banner.png') }}"
        style="background-position: 50% 50%; 
            background-size: cover !important;
            background-attachment: fixed;
            width: 100%; 
            min-height: 400px; 
            display: block !important;">
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var banner = document.getElementById('banner-view');
            if (banner) {
                var bgUrl = banner.getAttribute('data-bg');
                banner.style.backgroundImage = "url('" + bgUrl + "')";
            }
        });
    </script>
    <!-- End page header image -->

    <!-- Element cover -->
    <div class="cover page-header-cover"></div>

    <!-- Begin page header info -->
    <div class="container-fluid page-header-content no-padding">
        <div class="row">
            <div class="col-md-4">

                <h1 class="album-title">{{ $category->name }}</h1>

                <!-- Begin album meta -->
                <div class="album-meta">
                    <span class="photos-count">{{ $category->portfolios_count ?? $category->portfolios()->count() }} Photos</span>
                </div>
                <!-- End album meta -->

                <!-- Begin author -->
                <!-- <div class="author">
                    <a href="#" class="author-avatar bg-image" style="background-image: url(assets/img/author.jpg); background-position: 50% 50%;"></a>
                    <a href="" class="author-info">- by: John Smith</a>
                </div> -->
                <!-- End author -->

            </div> <!-- /.col -->

            <div class="col-md-4">

                <!-- <div class="album-description">
                    <div class="al-desc-inner">
                        <p>Suspendisse metus urna, faucibus nec ex et, suscipit blandit turpis. Suspendisse maximus sodales sem aliquet vehicula. Vivamus augue felis, finibus ut augue in, condimentum malesuada metus navesis.</p>

                        <div class="al-desc-toggle">
                            <p>Praesent ultricies interdum augue sit amet tempor. Maecenas at ultricies arcu. Sed lacinia vulputate nulla, a sollicitudin turpis varius quis. Pellentesque vulputate pellentesque.</p>
                        </div>
                    </div>

                    <div class="al-desc-toggle-trigger">
                        <span class="al-desc-more"><i class="fas fa-chevron-down"></i> More</span>
                        <span class="al-desc-less"><i class="fas fa-chevron-up"></i> Less</span>
                    </div>
                </div> -->

            </div>

            <div class="col-md-4">
                <div class="album-nav">

                    @php
                    // Prev Category Thumbnail Logic
                    <!-- $prevThumb = $prevCategory->image
                    ? asset('storage/' . $prevCategory->image)
                    : asset('assets/img/album-list/grid/img-2.jpg'); -->
                    $prevThumb = $prevCategory->image
                    ? url('/img/' . $prevCategory->image)
                    : asset('assets/img/album-list/grid/img-2.jpg');
                    @endphp

                    <a href="{{ route('category.show', $prevCategory->slug) }}"
                        class="an-item prev-album bg-image"
                        style="background-image: url('{{ $prevThumb }}'); background-position: 50% 50%;"
                        title="Previous Category: {{ $prevCategory->name }}">

                        <div class="cover"></div>
                        <div class="an-item-info">
                            <span class="an-icon"><i class="fas fa-chevron-left"></i></span>
                            <span class="an-text">Prev</span>
                        </div>
                    </a>

                    <a href="javascript:void(0)"
                        class="an-item to-album-list bg-image"
                        title="Back to all categories">

                        <div class="cover"></div>
                        <div class="an-item-info">
                            <span class="an-icon"><i class="fas fa-th-list"></i></span>
                            <span class="an-text">Categories</span>
                        </div>
                    </a>

                    @php
                    // Next Category Thumbnail Logic
                    <!-- $nextThumb = $nextCategory->image
                    ? asset('storage/' . $nextCategory->image)
                    : asset('assets/img/album-list/grid/img-7.jpg'); -->
                    $nextThumb = $nextCategory->image
                    ? url('/img/' . $nextCategory->image)
                    : asset('assets/img/album-list/grid/img-7.jpg');
                    @endphp

                    <a href="{{ route('category.show', $nextCategory->slug) }}"
                        class="an-item next-album bg-image"
                        style="background-image: url('{{ $nextThumb }}'); background-position: 50% 50%;"
                        title="Next Category: {{ $nextCategory->name }}">

                        <div class="cover"></div>
                        <div class="an-item-info">
                            <span class="an-icon"><i class="fas fa-chevron-right"></i></span>
                            <span class="an-text">Next</span>
                        </div>
                    </a>

                </div>
            </div><!-- /.col -->
        </div> <!-- /.row -->
    </div>
    <!-- End page header info -->

</section>
<!-- End page header section -->


<!-- ============================
			///// Begin content section /////
			============================= -->
<section id="content-section" class="album-single">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- Begin content wrap -->
                <div class="content-wrap">

                    <!-- Begin isotope
								===================
								* Use classes "gutter-1", "gutter-2" or "gutter-3" to add more space between items.
								* Use class "col-1", "col-2", "col-3", "col-4", "col-5" or "col-6" for columns.
								-->
                    <div class="isotope col-5">

                        <!-- Begin gallery top content -->
                        <div class="gallery-top-content">

                            <div class="row margin-bottom-40">
                                <div class="col-xs-4">

                                    <!-- Begin columns switch -->
                                    <!-- <div class="columns-switch">
                                        <a href="#0" class="lsw-1" title="Columns switch"><i class="fas fa-th"></i></a>
                                        <a href="#0" class="lsw-2" title="Columns switch"><i class="fas fa-th-large"></i></a>
                                    </div> -->
                                    <!-- End columns switch -->

                                </div> <!-- /.col -->

                                <div class="col-xs-8">

                                    <!-- Begin album attributes -->
                                    <ul class="album-attributes">

                                        <!-- Begin show items on page
													==============================
													* Use class "options-dark" to enable dark dropdown menu.
													-->
                                        <li class="hide-from-md">
                                            <div class="form-inline show-on-page margin-right-15">
                                                <div class="form-group">
                                                    <label for="show-items" class="margin-right-5">Show:</label>

                                                    <!-- {{-- Bootstrap Button Dropdown Structure --}} -->
                                                    <div class="dropdown inline-block">
                                                        <button id="show-items" class="select-styled styledSelect" type="button" id="show-items" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="cursor: pointer; min-width: 100px; text-align: left;">
                                                            {{ request('per_page', 12) }} items
                                                        </button>

                                                        <!-- {{-- Dropdown Menu Items [4, 8, 12, 16] --}} -->
                                                        <ul class="dropdown-menu" aria-labelledby="show-items" style="min-width: 100px;">
                                                            <li class="{{ request('per_page') == 4 ? 'active' : '' }}">
                                                                <a href="{{ url()->current() }}?per_page=4">4 items</a>
                                                            </li>
                                                            <li class="{{ request('per_page') == 8 ? 'active' : '' }}">
                                                                <a href="{{ url()->current() }}?per_page=8">8 items</a>
                                                            </li>
                                                            <li class="{{ request('per_page', 12) == 12 ? 'active' : '' }}">
                                                                <a href="{{ url()->current() }}?per_page=12">12 items</a>
                                                            </li>
                                                            <li class="{{ request('per_page') == 16 ? 'active' : '' }}">
                                                                <a href="{{ url()->current() }}?per_page=16">16 items</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </li>

                                        <!-- End show items on page -->



                                    </ul>
                                    <!-- End album attributes -->

                                </div> <!-- /.col -->
                            </div> <!-- /.row -->

                        </div>
                        <!-- End gallery top content -->


                        <!-- Begin isotope items (album single items)
									=============================================== 
									* Use classes "hover-center", "hover-boxed", "hover-dark", "hover-simple" to change album single item hover variations.
									* Note1: For grid layout make sure that your images are the same dimensions.
									* Note2: For masonry layout make sure that your images are the different dimensions.
									-->
                        <!-- <div id="gallery" class="isotope-items-wrap lightgallery hover-center hover-boxed">

                            <div class="grid-sizer"></div>

                           

                            <div class="isotope-item">
                                <div class="album-single-item">

                                    <div class="before-after-container">
                                        <img src="{{ asset('assets/img/album-single/masonry/84s.jpg') }}" alt="">
                                        <img src="{{ asset('assets/img/album-single/masonry/84.jpg') }}" alt="">
                                    </div>

                                    
                                    <a class="view-icon lg-trigger"
                                        href="{{ asset('assets/img/album-single/masonry/84.jpg') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>


                                </div>
                            </div>

                            

                        </div> -->

                        @php $baIndex = 0; @endphp

                        <div id="gallery" class="isotope-items-wrap hover-center hover-boxed">
                            <div class="grid-sizer"></div>

                            @foreach($portfolios as $portfolio)

                            <div class="isotope-item">
                                <div class="album-single-item">

                                    <div class="before-after-container">
                                        <!-- <img src="{{ asset('storage/'.$portfolio->before_image) }}" alt="Before" class="asi-img" loading="lazy">
                                        <img src="{{ asset('storage/'.$portfolio->after_image) }}" alt="After" class="asi-img" loading="lazy"> -->
                                        <img src="{{ url('/img/'.$portfolio->before_image) }}" alt="Before" class="asi-img" loading="lazy">
                                        <img src="{{ url('/img/'.$portfolio->after_image) }}" alt="After" class="asi-img" loading="lazy">
                                    </div>

                                    <!-- <a class="view-icon ba-trigger"
                                        href="#"
                                        data-before="{{ asset('storage/'.$portfolio->before_image) }}"
                                        data-after="{{ asset('storage/'.$portfolio->after_image) }}"
                                        data-index="{{ $baIndex }}"
                                        onclick="openBeforeAfterModal({{ $baIndex }}); return false;">
                                        <i class="fas fa-eye"></i>
                                    </a> -->

                                    <!-- data-before="{{ asset('storage/'.$portfolio->before_image) }}"
                                    data-after="{{ asset('storage/'.$portfolio->after_image) }}" -->
                                    <a class="view-icon ba-trigger"
                                        href="javascript:void(0)"
                                        data-before="{{ url('/img/'.$portfolio->before_image) }}"
                                        data-after="{{ url('/img/'.$portfolio->after_image) }}"
                                        data-index="{{ $baIndex }}"
                                        onclick="openBeforeAfterModal(this); return false;">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @php $baIndex++ @endphp

                                </div>
                            </div>

                            @endforeach

                        </div>

                        <script>
                            var baItems = JSON.parse('{!! json_encode($baItems) !!}') || [];
                        </script>
                        <!-- End isotope items wrap -->

                    </div>
                    <!-- End isotope -->

                </div>
                <!-- End content wrap -->

            </div> <!-- /.col -->
        </div> <!-- /.row -->


        <div class="row">
            <div class="col-md-8">

                @if ($portfolios->hasPages())
                <nav class="pagination-wrap">
                    <ul class="pagination">
                        {{-- First Page Link --}}
                        @if ($portfolios->onFirstPage())
                        <li class="disabled"><span aria-hidden="true">First</span></li>
                        @else
                        <li><a href="{{ $portfolios->url(1) }}" aria-label="First"><span aria-hidden="true">First</span></a></li>
                        @endif

                        {{-- Previous Page Link --}}
                        @if ($portfolios->onFirstPage())
                        <li class="disabled"><span>Prev</span></li>
                        @else
                        <li><a href="{{ $portfolios->previousPageUrl() }}" rel="prev">Prev</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($portfolios->linkCollection() as $link)
                        {{-- Skip 'First', 'Previous', 'Next', and 'Last' buttons from the collection loop --}}
                        @if (in_array($link['label'], ['&laquo; Previous', 'Next &raquo;', 'pagination.previous', 'pagination.next']))
                        @continue
                        @endif

                        {{-- "Three Dots" Separator --}}
                        @if ($link['url'] === null && $link['label'] === '...')
                        <li class="disabled"><span>...</span></li>
                        @endif

                        {{-- Page Number Links --}}
                        @if ($link['url'] !== null && $link['label'] !== '...')
                        @if ($link['active'])
                        <li class="active"><a href="#0">{{ $link['label'] }}</a></li>
                        @else
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endif
                        @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($portfolios->hasMorePages())
                        <li><a href="{{ $portfolios->nextPageUrl() }}" rel="next">Next</a></li>
                        @else
                        <li class="disabled"><span>Next</span></li>
                        @endif

                        {{-- Last Page Link --}}
                        @if ($portfolios->hasMorePages())
                        <li><a href="{{ $portfolios->url($portfolios->lastPage()) }}" aria-label="Last"><span aria-hidden="true">Last</span></a></li>
                        @else
                        <li class="disabled"><span aria-hidden="true">Last</span></li>
                        @endif
                    </ul>
                </nav>
                @endif

            </div>
            <div class="col-md-4">

                <div class="pagination-info">
                    <span>Showing page {{ $portfolios->currentPage() }} of {{ $portfolios->lastPage() }}</span>
                    <span>Images {{ $portfolios->firstItem() }} - {{ $portfolios->lastItem() }} of {{ $portfolios->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Begin album bottom nav -->
        <div class="album-bottom-nav">
            <div class="abn-item prev-album">
                @if($prevCategory)
                <a href="{{ route('category.show', $prevCategory->slug) }}" title="Previous Category: {{ $prevCategory->name }}">
                    <span class="abn-icon"><i class="fas fa-chevron-left"></i></span>
                    <span class="abn-text">Prev<span class="hide-from-xs">ious</span> Category</span>
                </a>
                @else
                {{-- Optional Fallback: Agar pehli category ho to click par disable dikhane ke bajaye link khatam kar sakte hain --}}
                <a href="#" style="opacity: 0.5; pointer-events: none;">
                    <span class="abn-icon"><i class="fas fa-chevron-left"></i></span>
                    <span class="abn-text">Prev<span class="hide-from-xs">ious</span> Category</span>
                </a>
                @endif
            </div>

            <div class="abn-item next-album">
                @if($nextCategory)
                <a href="{{ route('category.show', $nextCategory->slug) }}" title="Next Category: {{ $nextCategory->name }}">
                    <span class="abn-text">Next Category</span>
                    <span class="abn-icon"><i class="fas fa-chevron-right"></i></span>
                </a>
                @else
                {{-- Optional Fallback --}}
                <a href="#" style="opacity: 0.5; pointer-events: none;">
                    <span class="abn-text">Next Category</span>
                    <span class="abn-icon"><i class="fas fa-chevron-right"></i></span>
                </a>
                @endif
            </div>
        </div>
        <!-- End album bottom nav -->

    </div> <!-- /.container -->
</section>
<!-- End content section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twentytwenty/1.0.0/twentytwenty.css">
<!-- End isotope item -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.event.move/2.0.0/jquery.event.move.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twentytwenty/1.0.0/jquery.twentytwenty.min.js"></script>
<script>
    $(window).on('load', function() {

        $(".before-after-container").twentytwenty({
            default_offset_pct: 0.5
        });

    });
    console.log(typeof $.fn.twentytwenty);
</script>
@endsection