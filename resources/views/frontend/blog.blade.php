@extends('frontend.layouts.app')
@section('title', 'Blog - GrandStay')
@section('content')



    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        

        <section class="jarallax text-light relative rounded-1 overflow-hidden mt-80 mt-sm-70 mx-2">
            <div class="de-gradient-edge-top"></div>
            <img src="{{ asset('assets/frontend/images/background/1.webp') }}" class="jarallax-img" alt="">
            <div class="container relative z-2">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp mb-2">Enjoy Your Stay</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Blog</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Blog</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section class="bg-light">
            <div class="container">
                <div class="row g-4">

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">20</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/1.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 1]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 1]) }}">
                                    <h3>Top Hotel Amenities That Guests Love in 2025 Trends</h3>
                                </a>
                                <p>From smart-room technology to wellness features that elevate guest comfort and overall satisfaction.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">19</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/2.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 2]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 2]) }}">
                                    <h3>How to Choose the Perfect Room for Your Stay</h3>
                                </a>
                                <p>Learn how to choose the ideal hotel room based on comfort, layout, and travel needs for a better stay.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">18</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/3.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 3]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 3]) }}">
                                    <h3>The Art of Hospitality: Behind Our Signature Services</h3>
                                </a>
                                <p>Discover the thoughtful services and attention to detail that define exceptional hospitality experiences.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">17</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/4.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 4]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 4]) }}">
                                    <h3>Why Location Matters: Choosing a Hotel for Your Next Trip</h3>
                                </a>
                                <p>See how hotel location impacts convenience, accessibility, and overall travel experience.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">16</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/5.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 5]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 5]) }}">
                                    <h3>Luxury vs Budget Hotels: What’s Right for You?</h3>
                                </a>
                                <p>Compare luxury and budget hotels to find the best balance between comfort, value, and features.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">15</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/6.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 6]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 6]) }}">
                                    <h3>5 Tips to Get the Best Hotel Deals Online</h3>
                                </a>
                                <p>Learn smart strategies to book better hotel deals online while avoiding hidden fees.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">14</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/7.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 7]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 7]) }}">
                                    <h3>What Makes a Hotel Stay Truly Memorable?</h3>
                                </a>
                                <p>Explore the key elements that turn an ordinary hotel stay into a lasting memory.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6">
                        <div class="overflow-hidden">
                            <div class="hover relative">
                                <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                    <div class="fs-36 fw-bold lh-1">13</div>
                                    <span>Jun</span>
                                </div>
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/blog/8.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                                </div>
                                <a href="{{ route('blog.single', ['id' => 8]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>

                            <div class="pt-4 relative">
                                <a href="{{ route('blog.single', ['id' => 8]) }}">
                                    <h3>Design Trends Shaping Modern Hotel Interiors</h3>
                                </a>
                                <p>Discover modern hotel interior trends that blend style, comfort, and functionality.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        
    </main>


    <!-- Javascript Files
    ================================================== -->
    @push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>

    <!-- swiper slider -->
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
    @endpush

@endsection