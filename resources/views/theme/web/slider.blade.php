<section id="slider" class="slider-element slider-parallax swiper_wrapper min-vh-60 min-vh-md-100 include-header">
    <div class="slider-inner">
        <div class="swiper-container swiper-parent">
            {{-- @php
            $banners = \App\Models\Banner::get();
            @endphp --}}
            <div class="swiper-wrapper">
                {{-- @foreach ($banners as $banner) --}}
                <div class="swiper-slide dark">
                    <div class="container">
                        <div class="slider-caption slider-caption-center">
                            <h2 data-animate="fadeInUp">Selamat Datang</h2>
                            <p class="d-none d-sm-block" data-animate="fadeInUp" data-delay="200">
                                {{-- {{$banner->description}}     --}}
                                Selamat Berbelanja
                            </p>
                        </div>
                    </div>
                    <div class="swiper-slide-bg" style="background-image: url('{{asset('img/banner.jpg')}}');"></div>
                </div>
                {{-- @endforeach --}}
            </div>
            {{-- @if ($banners->count() > 1) --}}
            <div class="slider-arrow-left"><i class="icon-angle-left"></i></div>
            <div class="slider-arrow-right"><i class="icon-angle-right"></i></div>
            {{-- @endif --}}
        </div>
        <a href="javascript:;" data-scrollto="#content" data-offset="100" class="one-page-arrow dark"><i class="icon-angle-down infinite animated fadeInDown"></i></a>
    </div>
</section>