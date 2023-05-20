@extends('layouts.app')

@section('content')

 @if (session('status'))
     <div class="alert alert-success" role="alert">
     {{ session('status') }}
     </div>
 @endif


 <section class="about full-screen d-lg-flex justify-content-center align-items-center" id="about">
    <div class="container">
        <div class="row">

            <div class="col-lg-7 col-md-12 col-12 d-flex align-items-center">
                <div class="about-text">
                    <small class="small-text">{{ __('You are logged in!') }} <span class="mobile-block"></span></small>
                    <h2 class="animated animated-text">
                        <span class="mr-2">Selamat Datang di Aplikasi Dapoer Van Kriwil</span>
                            <div class="animated-info">
                                <span class="animated-item">{{ Auth::user()->name }}</span>
                                <span class="animated-item">Have a nice day...</span>
                            </div>
                    </h2>
                    <br><br>
                    {{-- <p>Building a successful product is a challenge. I am highly energetic in user experience design, interfaces and web development.</p>

                    <div class="custom-btn-group mt-4">
                      <a href="#" class="btn mr-lg-2 custom-btn"><i class='uil uil-file-alt'></i> Download Resume</a>
                      <a href="#contact" class="btn custom-btn custom-btn-bg custom-btn-link">Get a free quote</a>
                    </div> --}}
                </div>
            </div>

            <div class="col-lg-5 col-md-12 col-12">
                <div class="about-image svg">
                    <img src="undraw_software_engineer_lvl5.svg" class="img-fluid" alt="svg image">
                </div>
            </div>

        </div>
    </div>
</section>
@endsection


