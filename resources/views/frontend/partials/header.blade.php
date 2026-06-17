<!-- ===================
		///// Begin header /////
		========================
		* Use class "show-hide-on-scroll" to hide header on scroll down and show on scroll up.
		* Use class "fixed-top" to set header to fixed position.
		-->
<header id="header" class="fixed-top">
    <div class="header-inner">

        <!-- Begin logo -->
        <div id="logo">
            <a href="index.html" class="logo-dark"><img src="{{ asset('assets/img/logo-dark.png') }}" alt="logo"></a>
            <a href="index.html" class="logo-light"><img src="{{ asset('assets/img/logo-light.png') }}" alt="logo"></a>
        </div>
        <!-- End logo -->

        <!-- Begin header tools -->
        <div class="header-tools">
            <ul>
                <li>
                    <!-- off-canvas menu trigger (menu button) -->
                    <a id="cd-menu-trigger" href="#0"><span class="cd-menu-icon"></span>menu</a>
                </li>
            </ul>
        </div>
        <!-- End header tools -->

        <!-- Begin menu (Bootstrap navbar)
				=================================== 
				* Use class "navbar-default" or "navbar-border-bottom" for navbar style.
				-->
        <nav class="navbar navbar-default">
            <div class="navbar-inner">

                <!-- Toggle for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> <!-- /.navbar-header -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">

                        <!-- Begin dropdown
								==============================
								* Use class "dropdown-hover" to make navigation toggle on desktop hover.
								* Use class "dropdown-menu-right" to right align the dropdown menu.
								* Use class "dropdown-menu-dark" to enable dark dropdown menu.
								-->
                        <li class="dropdown dropdown-hover">
                            <a href="#0" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Home 
                                <!-- <span class="caret-2">
                                    <i class="fas fa-chevron-down"></i>
                                </span> -->
                            </a>
                            <!-- <ul class="dropdown-menu">
                                <li><a href="home-single-image.html">Single Image</a></li>
                                <li><a href="album-list-slideshow.html">Slideshow</a></li>
                                <li><a href="home-video-background.html">Video Background</a></li>
                                <li><a href="home-scrolling-bg.html">Scrolling Background</a></li>
                                <li><a href="home-photo-wall.html">Photo Wall</a></li>
                                <li><a href="album-list-carousel-full.html">Striped Carousel</a></li>
                            </ul> -->
                        </li>
                        <!-- End dropdown -->

                       

                    </ul> <!-- /.nav -->
                </div> <!-- /.navbar-collapse -->

            </div> <!-- /.navbar-inner -->
        </nav>
        <!-- End menu -->

    </div> <!-- /.header-inner -->
</header>
<!-- End header -->


<!-- ==================================================================================================
		///// Begin off-canvas menu (more info: http://codyhouse.co/gem/secondary-expandable-navigation/) /////
		=================================================================================================== -->
<nav id="cd-lateral-nav">
    <div class="nav-inner">

        <!-- Menu header -->
        <div class="menu-header">Extra Stuff</div>

        <!-- Begin nav links 
				===================== -->
        <ul class="cd-navigation">

            <li><a class="link" href="page-about-us.html">About Us</a></li>

            <!-- Begin submenu -->
            <li class="item-has-children">
                <a href="#0">Submenu<span class="caret-2"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="sub-menu">
                    <li><a class="link" href="#">Sub Link 1</a></li>
                    <li><a class="link" href="#">Sub Link 2</a></li>
                    <li><a class="link" href="#">Sub Link 3</a></li>
                </ul>
            </li>
            <!-- End submenu -->

            <li class="cd-menu-separator"></li>

        </ul>
        <!-- End nav links -->

        <!-- Begin nav content box -->
        <div class="cd-content-box">

            <h2 class="cd-menu-heading">Instagram:</h2>

            <!-- Begin thumbnail list 
					==========================
					* Use class "col-2", "col-3", "col-4" "col-5" or "col-6" for thumbnail list columns.
					* Use class "gutter-1", "gutter-2", "gutter-3", "gutter-4" or "gutter-5" to add more space between items.
					-->
            <!-- <ul class="thumb-list col-3 gutter-3">
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-1.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-2.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-3.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-4.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-5.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-6.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-7.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-8.jpg);"></a></li>
                <li><a target="_blank" href="https://www.instagram.com" class="thumb-list-item bg-image" style="background-image: url(assets/img/album-list/small/img-9.jpg);"></a></li>
            </ul> -->
            <!-- End thumbnail list -->

        </div>
        <!-- End nav content box -->

       

    </div> <!-- /.nav-inner -->
</nav>
<!-- End off-canvas menu -->