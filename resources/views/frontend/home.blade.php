@extends('frontend.layouts.app')

@section('title','Home')

@section('content')

<!-- ======================================================
			/////// Begin content section (Album list carousel) ///////
			* Use class "full-carousel" to enable full height carousel.
			======================================================= -->
<section id="content-section" class="album-list-carousel full-carousel">

    <!-- Begin content wrap
				==========================
				* Use class "hover-zoom" to enable image zoom effect om hover.
				* Use class "caption-gradient" to enable gradient caption.
				* Use class "caption-dark" to enable dark caption (no effect if "caption-gradient" or "caption-outside" is used).
				* Use class "caption-boxed" to enable boxed caption (no effect if "caption-gradient" or "caption-outside" is used).
				* Use class "caption-sm", "caption-lg" or "caption-xlg" to change caption size.
				-->
    <div class="content-wrap caption-boxed hover-zoom">


        <!-- Begin content carousel (https://owlcarousel2.github.io/OwlCarousel2/)
					====================================================================
					* Use class "nav-outside" for outside nav.
					* Use class "nav-outside-top" for outside top nav.
					* Use class "nav-rounded" for rounded nav.
					* Use class "dots-outside" for outside dots.
					* Use class "dots-left" or "dots-right" to align dots.
					* Use class "dots-rounded" for rounded dots.
					* Use class "owl-mousewheel" to enable mousewheel support.
					* Available carousel data attributes:
							data-items="5".......................(items visible on desktop)
							data-tablet-landscape="4"............(items visible on mobiles)
							data-tablet-portrait="3".............(items visible on mobiles)
							data-mobile-landscape="2"............(items visible on tablets)
							data-mobile-portrait="1".............(items visible on tablets)
							data-loop="true".....................(true/false)
							data-margin="10".....................(space between items)
							data-center="true"...................(true/false)
							data-start-position="0"..............(item start position)
							data-animate-in="fadeIn".............(more animations: http://daneden.github.io/animate.css/)
							data-animate-out="fadeOut"...........(more animations: http://daneden.github.io/animate.css/)
							data-mouse-drag="false"..............(true/false)
							data-touch-drag="false"..............(true/false)
							data-autoheight="true"...............(true/false)
							data-autoplay="true".................(true/false)
							data-autoplay-timeout="5000".........(milliseconds)
							data-autoplay-hover-pause="true".....(true/false)
							data-autoplay-speed="800"............(milliseconds)
							data-drag-end-speed="800"............(milliseconds)
							data-lazy-load="true"................(true/false)
							data-nav="true"......................(true/false)
							data-nav-speed="800".................(milliseconds)
							data-dots="false"....................(true/false)
							data-dots-speed="800"................(milliseconds)
					-->
        <!-- <div class="owl-carousel owl-mousewheel nav-rounded" data-items="5" data-margin="6" data-nav="true" data-dots="false" data-tablet-landscape="4" data-tablet-portrait="3" data-mobile-landscape="2" data-mobile-portrait="1">

            <div class="album-list-item">
                <a class="ali-link" href="album-single-masonry-no-gutter.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-10.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">2D</h2>
                        <div class="ali-meta">15 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-2.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-2.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">PFPs</h2>
                        <div class="ali-meta">48 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-3.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-9.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">Emotes</h2>
                        <div class="ali-meta">15 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-4.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-11.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">Banner</h2>
                        <div class="ali-meta">152 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-5.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-7.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">Reference Sheets</h2>
                        <div class="ali-meta">9 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="album-single-masonry-no-gutter.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-12.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">Animations</h2>
                        <div class="ali-meta">211 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-2.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-3.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">3D</h2>
                        <div class="ali-meta">153 photos</div>
                    </div>
                </a>
            </div>

            <div class="album-list-item">
                <a class="ali-link" href="portfolio-single-3.html">
                    <div class="ali-img-wrap">
                        <div class="ali-img bg-image" style="background-image: url(assets/img/album-list/big/img-13.jpg); background-position: 50% 50%;"></div>
                    </div>
                    <div class="ali-caption">
                        <h2 class="ali-title">NSFW</h2>
                        <div class="ali-meta">347 photos</div>
                    </div>
                </a>
            </div>

        </div> -->

        <div class="owl-carousel owl-mousewheel nav-rounded"
            data-items="5"
            data-margin="6"
            data-nav="true"
            data-dots="false">

            @foreach($categories as $category)

            <div class="album-list-item">

                <a class="ali-link"
                    href="{{ route('category.show', $category->slug) }}">

                    <div class="ali-img-wrap">

                        @php
                        $image = asset('storage/' . $category->image);
                        @endphp

                        <div class="ali-img bg-image"
                            style="background-image: url('{{ $image }}');">
                        </div>

                    </div>

                    <div class="ali-caption">

                        <h2 class="ali-title">
                            {{ $category->name }}
                        </h2>

                        <div class="ali-meta">
                            {{ $category->portfolios_count }}
                            Images
                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>
        <!-- End content carousel -->

    </div>
    <!-- End content wrap -->

</section>
<!-- End content section -->

@endsection