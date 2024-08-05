@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-dark hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                About Us
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href={{ route('index') }}>Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">
                        About Us
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s" src="img/img1.jpg">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s" src="img/img2.jpg"
                                style="margin-top: 25%;">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s" src="img/img3.jpg">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s" src="img/img4.jpg">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                    <h1 class="mb-4">Welcome to <img src="img/logo.png" alt="Logo" class="img-fluid"></h1>
                    <p class="mb-4">Kita adalah platform aplikasi berbasis web yang berfokus pada lingkungan serta
                        menjaga alam dan bumi agar bumi menjadi tempat yang nyaman untuk ditinggali oleh generasi
                        setelah
                        kita. Disini kalian akan dapat mendapatkan berbagai macam informasi dan acara untuk menjaga alam
                        kita.
                    </p>
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">
                                    1</h1>
                                <div class="ps-4">
                                    <p class="mb-0">Since</p>
                                    <h6 class="text-uppercase mb-0">Year</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">
                                    {{ $userCount }}</h1>

                                <div class="ps-4">
                                    <p class="mb-0">People</p>
                                    <h6 class="text-uppercase mb-0">Guardian</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary py-3 px-5 mt-2" href="#" data-bs-toggle="modal"
                        data-bs-target="#myModal">Join with us</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content card-green">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Join with Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h4 class="mb-4">Scan Here</h4>
                    <img src="{{ asset('img/barcode.png') }}" class="img-fluid rounded mb-3" style="max-width: 300px;"
                        alt="Barcode">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl pt-5 pb-3">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Team Members</h5>
                <h1 class="mb-5">Our Team</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-2 col-md-6 col-6 wow fadeInUp text-center" data-wow-delay="0.1s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="rounded-circle overflow-hidden m-4">
                            <img class="img-fluid" src="{{ 'img/sahrul.jpg' }}" alt="">
                        </div>
                        <h5 class="mb-0">Sahrul R</h5>
                        <small>Back End</small>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-square btn-primary mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href="https://x.com/sahrulromadi_"><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href="https://www.instagram.com/sahrulr87/"><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6 wow fadeInUp text-center" data-wow-delay="0.3s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="rounded-circle overflow-hidden m-4">
                            <img class="img-fluid" src="{{ 'img/adrian.jpg' }}" alt="">
                        </div>
                        <h5 class="mb-0">Adrian</h5>
                        <small>Project Manager</small>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6 wow fadeInUp text-center" data-wow-delay="0.5s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="rounded-circle overflow-hidden m-4">
                            <img class="img-fluid" src="{{ 'img/rangga.jpg' }}" alt="">
                        </div>
                        <h5 class="mb-0">Rangga P M</h5>
                        <small>Front End</small>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6 wow fadeInUp text-center" data-wow-delay="0.7s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="rounded-circle overflow-hidden m-4">
                            <img class="img-fluid" src="{{ 'img/firman.jpg' }}" alt="">
                        </div>
                        <h5 class="mb-0">Adik F M</h5>
                        <small>Front End</small>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6 wow fadeInUp text-center" data-wow-delay="0.9s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="rounded-circle overflow-hidden m-4">
                            <img class="img-fluid" src="{{ 'img/ibnu.jpg' }}" alt="">
                        </div>
                        <h5 class="mb-0">Ibnu H</h5>
                        <small>Front End</small>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
