<!DOCTYPE html>
<html lang="en">

<head>
  <title>@yield('title','Home')</title>

  <!-- Meta -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="HTML5 Photography Portfolio Website Template by Themetorium">
  <meta name="keywords" content="HTML5, CSS3, JavaScript, Bootsrtrap, Responsive, Photography, Portfolio, Template, Theme, Website, Themetorium" />
  <meta name="author" content="themetorium.net">

  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon (http://www.favicon-generator.org/) -->
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  <!-- Google font (https://www.google.com/fonts) -->
  <link href='https://fonts.googleapis.com/css?family=Roboto+Mono:400,300,500,700,100' rel='stylesheet' type='text/css'>

  <!-- Bootstrap CSS (http://getbootstrap.com) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}"> <!-- bootstrap CSS (http://getbootstrap.com) -->

  <!-- Libs and Plugins CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}"> <!-- Font Icons CSS (https://fontawesome.com) Free version! -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/lightgallery/css/lightgallery.min.css') }}"> <!-- lightGallery CSS (http://sachinchoolur.github.io/lightGallery) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/justifiedgallery/css/justifiedGallery.min.css') }}"> <!-- Justified Gallery CSS (http://miromannino.github.io/Justified-Gallery/) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/ytplayer/css/jquery.mb.YTPlayer.min.css') }}"> <!-- YTPlayer CSS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/owl-carousel/css/owl.carousel.min.css') }}"> <!-- Owl Carousel CSS (https://owlcarousel2.github.io/OwlCarousel2/) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/owl-carousel/css/owl.theme.default.min.css') }}"> <!-- Owl Carousel default theme CSS (https://owlcarousel2.github.io/OwlCarousel2/) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/animate.min.css') }}"> <!-- Animate libs CSS (http://daneden.me/animate) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Template master CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/twentytwenty.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('assets/css/dark-style.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('assets/css/helper.css') }}">

  <!-- Template dark style CSS (comment or uncomment below line to enable/disable template dark style) -->
  <!-- <link rel="stylesheet" href="assets/css/dark-style.css"> -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body id="body">

  <!-- Begin page preloader -->
  <div id="preloader">
    <div class="pulse bg-main"></div>
  </div>
  <!-- End page preloader -->

  @include('frontend.partials.header')

  <!-- ==============================
		/////// Begin body content ///////
		=============================== -->
  <div id="body-content">


    @yield('content')


  </div>
  @include('frontend.partials.footer')
  <!-- End body content -->
  <div id="beforeAfterModal">

    <span class="ba-modal-close" onclick="closeBeforeAfterModal()">&times;</span>

    <!-- Prev Button -->
    <span class="ba-modal-prev" onclick="changeBeforeAfter(-1)"><i class="fa-solid fa-chevron-left"></i></span>

    <div id="modalWrapper">
      <div id="modalBAContainer">
        <img id="modalBeforeImg" src="" alt="Before">
        <img id="modalAfterImg" src="" alt="After">
      </div>
      <!-- Counter -->
      <div class="ba-modal-counter">
        <span id="baCurrentIndex">1</span> / <span id="baTotalCount">1</span>
      </div>
    </div>

    <!-- Next Button -->
    <span class="ba-modal-next" onclick="changeBeforeAfter(1)"><i class="fa-solid fa-chevron-right"></i></span>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const themeToggle = document.getElementById('theme-toggle');
      if (!themeToggle) return;

      const moonSvg = themeToggle.querySelector('.moon-svg');
      const sunSvg = themeToggle.querySelector('.sun-svg');
      const darkLogo = document.querySelector('.logo-dark');
      const lightLogo = document.querySelector('.logo-light');

      let darkStyleLink = document.getElementById('dark-style-css');

      function showMoon() {
        if (moonSvg) moonSvg.style.display = 'block';
        if (sunSvg) sunSvg.style.display = 'none';
      }

      function showSun() {
        if (moonSvg) moonSvg.style.display = 'none';
        if (sunSvg) sunSvg.style.display = 'block';
      }

      function updateTheme(theme, animate = false) {

        if (theme === 'dark') {

          document.documentElement.setAttribute('data-theme', 'dark');

          if (!darkStyleLink) {
            darkStyleLink = document.createElement('link');
            darkStyleLink.rel = 'stylesheet';
            darkStyleLink.id = 'dark-style-css';
            darkStyleLink.href = '{{ asset("assets/css/dark-style.css") }}';
            document.head.appendChild(darkStyleLink);
          }

          themeToggle.classList.remove('light-mode');

          if (animate) {
            setTimeout(showMoon, 150);
          } else {
            showMoon();
          }

          if (darkLogo) darkLogo.style.display = 'none';
          if (lightLogo) lightLogo.style.display = 'block';

        } else {

          document.documentElement.removeAttribute('data-theme');

          if (darkStyleLink) {
            darkStyleLink.remove();
            darkStyleLink = null;
          }

          themeToggle.classList.add('light-mode');

          if (animate) {
            setTimeout(showSun, 150);
          } else {
            showSun();
          }

          if (darkLogo) darkLogo.style.display = 'block';
          if (lightLogo) lightLogo.style.display = 'none';
        }

        localStorage.setItem('theme', theme);
      }

      const savedTheme = localStorage.getItem('theme') || 'light';
      updateTheme(savedTheme, false);

      themeToggle.addEventListener('click', function(e) {
        e.preventDefault();
        const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        updateTheme(newTheme, true);
      });

    });
  </script>

  <script>
    // Saare before/after items ka array
    var baItems = [];
    var baCurrentIdx = 0;

    // Page load hone ke baad saare items collect karo
    $(document).ready(function() {
      // Har .view-icon jo openBeforeAfterModal call kare usse collect karo
      $('.ba-trigger').each(function() {
        baItems.push({
          before: $(this).data('before'),
          after: $(this).data('after')
        });
      });

      // Agar 1 ya kam items hain to arrows hide karo
      if (baItems.length <= 1) {
        $('.ba-modal-prev, .ba-modal-next').addClass('hidden');
      }

      $('#baTotalCount').text(baItems.length);
    });

    /**
     * MODIFIED: Ab yeh direct HTML element object receive karega
     * Jis se editor ka 'Property assignment expected' error hamesha ke liye khatam ho gaya.
     */
    function openBeforeAfterModal(element) {
      // DOM element se data-index nikal kar integer me convert karo
      var index = parseInt(element.getAttribute('data-index'), 10);

      baCurrentIdx = index;
      loadBeforeAfter(baItems[index].before, baItems[index].after);

      $('#baCurrentIndex').text(index + 1);

      var modal = document.getElementById('beforeAfterModal');
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function changeBeforeAfter(direction) {
      baCurrentIdx = baCurrentIdx + direction;

      // Loop around
      if (baCurrentIdx < 0) baCurrentIdx = baItems.length - 1;
      if (baCurrentIdx >= baItems.length) baCurrentIdx = 0;

      $('#baCurrentIndex').text(baCurrentIdx + 1);
      loadBeforeAfter(baItems[baCurrentIdx].before, baItems[baCurrentIdx].after);
    }

    function loadBeforeAfter(before, after) {
      var container = document.getElementById('modalBAContainer');
      var beforeImg = document.getElementById('modalBeforeImg');
      var afterImg = document.getElementById('modalAfterImg');

      // TwentyTwenty reset
      var $container = $(container);
      $container.removeClass('twentytwenty-container');
      $container.find('.twentytwenty-handle, .twentytwenty-overlay').remove();
      beforeImg.style.display = 'block';
      afterImg.style.display = 'block';

      // Size reset
      container.style.width = 'auto';
      container.style.height = 'auto';
      beforeImg.style.width = '';
      beforeImg.style.height = '';
      afterImg.style.width = '';
      afterImg.style.height = '';
      beforeImg.style.maxWidth = '';
      beforeImg.style.maxHeight = '';
      afterImg.style.maxWidth = '';
      afterImg.style.maxHeight = '';

      beforeImg.src = before;
      afterImg.src = after;

      afterImg.onload = function() {
        var natW = afterImg.naturalWidth;
        var natH = afterImg.naturalHeight;

        var maxW = window.innerWidth * 0.85;
        var maxH = (window.innerHeight - 120) * 0.90;

        var ratio = Math.min(maxW / natW, maxH / natH, 1);
        var finalW = Math.round(natW * ratio);
        var finalH = Math.round(natH * ratio);

        container.style.width = finalW + 'px';
        container.style.height = finalH + 'px';

        beforeImg.style.width = finalW + 'px';
        beforeImg.style.height = finalH + 'px';
        beforeImg.style.maxWidth = 'none';
        beforeImg.style.maxHeight = 'none';

        afterImg.style.width = finalW + 'px';
        afterImg.style.height = finalH + 'px';
        afterImg.style.maxWidth = 'none';
        afterImg.style.maxHeight = 'none';

        $container.twentytwenty({
          default_offset_pct: 0.5
        });
      };

      if (afterImg.complete && afterImg.naturalWidth > 0) {
        afterImg.onload();
      }
    }

    function closeBeforeAfterModal() {
      document.getElementById('beforeAfterModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (document.getElementById('beforeAfterModal').style.display === 'flex') {
        if (e.key === 'Escape') closeBeforeAfterModal();
        if (e.key === 'ArrowRight') changeBeforeAfter(1);
        if (e.key === 'ArrowLeft') changeBeforeAfter(-1);
      }
    });

    // Bahar click se band
    document.getElementById('beforeAfterModal').addEventListener('click', function(e) {
      if (e.target === this || e.target.id === 'modalWrapper') closeBeforeAfterModal();
    });

    $(document).ready(function() {
      // Agar template selectbox ko normal handle kar raha ho
      $(document).on('change', '#show-items', function() {
        $('#perPageForm').submit();
      });

      // Pro-Tip: Agar aapka template custom layout dropdown generate kar raha hai,
      // to yeh snippet us custom li/span click ko bhi target kar lega:
      $(document).on('click', '.select-styled + .options li, .show-on-page ul li', function() {
        // Thodi der rukh kar submit karein taaki value select box me update ho jaye
        setTimeout(function() {
          $('#perPageForm').submit();
        }, 100);
      });
    });
  </script>

  <!-- Scroll to top button -->
  <a href="#body" class="scrolltotop sm-scroll"><i class="fas fa-chevron-up"></i></a>

  <!-- ====================
		///// Scripts below /////
		===================== -->

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script> <!-- jquery JS (https://jquery.com) -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script> <!-- bootstrap JS (http://getbootstrap.com) -->

  <!-- Libs and Plugins JS -->
  <script src="{{ asset('assets/vendor/lightgallery/js/lightgallery-all.min.js') }}"></script> <!-- lightGallery JS (http://sachinchoolur.github.io/lightGallery) -->

  <script src="{{ asset('assets/vendor/jquery.mousewheel.min.js') }}"></script> <!-- A jQuery plugin that adds cross browser mouse wheel support -->
  <script src="{{ asset('assets/vendor/jquery.easing.min.js') }}"></script> <!-- Easing JS (http://gsgd.co.uk/sandbox/jquery/easing/) -->
  <script src="{{ asset('assets/vendor/isotope.pkgd.min.js') }}"></script> <!-- Isotope JS (http://isotope.metafizzy.co) -->
  <script src="{{ asset('assets/vendor/imagesloaded.pkgd.min.js') }}"></script> <!-- ImagesLoaded JS (https://github.com/desandro/imagesloaded) -->
  <script src="{{ asset('assets/vendor/justifiedgallery/js/jquery.justifiedGallery.min.js') }}"></script> <!-- Justified Gallery JS (http://miromannino.github.io/Justified-Gallery/) -->
  <script src="{{ asset('assets/vendor/ytplayer/js/jquery.mb.YTPlayer.min.js') }}"></script> <!-- YTPlayer JS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
  <script src="{{ asset('assets/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script> <!-- Owl Carousel JS (https://owlcarousel2.github.io/OwlCarousel2/) -->


  <!-- Theme master JS -->
  <script src="{{ asset('assets/js/jquery.event.move.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.twentytwenty.js') }}"></script>
  <script src="{{ asset('assets/js/theme.js') }}"></script>



  <!--==============================
		///// Begin Google Analytics /////
		============================== -->

  <!-- Paste your Google Analytics code here. 
		Go to http://www.google.com/analytics/ for more information. -->

  <!--==============================
		///// End Google Analytics /////
		============================== -->

  @stack('scripts')
</body>

</html>