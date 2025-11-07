@extends('Layout.master')

@section('title')
 Trainner
@endsection


@section('content')

<section class="hero-wrap hero-wrap-2" style="background-image: url('style/images/bg_3.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
        <div class="col-md-9 ftco-animate text-center">
          <h1 class="mb-3 bread">Our Professional Trainer</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Trainer</span></p>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
      <div class="container">
          <div class="row">
            @foreach ($coaches as $coach)
              <div class="col-lg-3 d-flex ftco-animate">
                  <div class="coach align-items-stretch">
                      <div class="img" style="background-image: url(style/images/trainer-1.jpg);"></div>
                      <div class="text bg-white p-4 ftco-animate">
                          <span class="subheading">{{ $coach->specialty }}</span>
                          <h3><a href="#">{{ $coach->name }}</a></h3>
                          <p>{{ $coach->bio}}</p>
                          <ul class="ftco-social-media d-flex mt-4">
                  <li class="ftco-animate"><a href="#" class="mr-2 d-flex justify-content-center align-items-center"><span class="icon-twitter"></span></a></li>
                  <li class="ftco-animate"><a href="#" class="mr-2 d-flex justify-content-center align-items-center"><span class="icon-facebook"></span></a></li>
                  <li class="ftco-animate"><a href="#" class="mr-2 d-flex justify-content-center align-items-center"><span class="icon-instagram"></span></a></li>
                </ul>
                          <p></p>
                      </div>
                  </div>
              </div>
              @endforeach


          </div>
      </div>
  </section>

@endsection