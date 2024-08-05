@extends('layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel container-fluid py-5 bg-dark hero-header mb-5" data-bs-ride="carousel">
        <div class="carousel-inner container my-5 py-5">
            @if ($starredCampaigns->isEmpty())
                <div class="carousel-item active">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="display-3 text-white animated slideInLeft">Default <br> Campaign</h1>
                            <p class="text-white animated slideInLeft mb-4 pb-2">Ini adalah konten default karena tidak ada
                                campaign yang tersedia.</p>
                            <a href="#"
                                class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft cek-keadaan-btn">Our
                                Campaign</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <img class="img-fluid" src="{{ asset('img/bumi.png') }}" alt="">
                        </div>
                    </div>
                </div>
            @else
                @foreach ($starredCampaigns as $index => $campaign)
                    <div class="carousel-item @if ($index === 0) active @endif">
                        <div class="row align-items-center g-5">
                            <div class="col-lg-6 text-center text-lg-start text-white">
                                <h1 class="text-white display-3 animated slideInLeft" style="word-wrap: break-word;">
                                    {{ $campaign->title }}</h1>
                                <p class="animated slideInLeft mb-4 pb-2">
                                    {!! strlen($campaign->content) > 350 ? substr($campaign->content, 0, 350) . '...' : $campaign->content !!}
                                </p>
                                <a href="#"
                                    class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft cek-keadaan-btn"
                                    data-bs-toggle="modal" data-bs-target="#cekKeadaanModal-{{ $campaign->id }}">Our
                                    Campaign</a>
                            </div>
                            <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                                <img class="img-fluid" src="{{ asset('img/bumi.png') }}" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="cekKeadaanModal-{{ $campaign->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="cekKeadaanModalLabel-{{ $campaign->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 1080px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cekKeadaanModalLabel-{{ $campaign->id }}">
                                        {{ $campaign->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                @if ($campaign && $campaign->image)
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/public/' . $campaign->image) }}" alt="Campaign Image"
                                            class="img-fluid modal-sm">
                                    </div>
                                @else
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('img/noimg.png') }}" alt="Default Image"
                                            class="img-fluid modal-sm">
                                    </div>
                                @endif
                                <div class="modal-body px-4" style="text-align: justify;">
                                    <p>{!! $campaign->content !!}</p>
                                </div>
                                <div class="card-footer mt-2 px-4">
                                    <div id="disqus_thread"></div>
                                    <script>
                                        /**
                                         * Function to load Disqus using JavaScript
                                         */
                                        (function() { // DON'T EDIT BELOW THIS LINE
                                            var d = document,
                                                s = d.createElement('script');
                                            s.src = 'https://naturehub.disqus.com/embed.js';
                                            s.setAttribute('data-timestamp', +new Date());
                                            (d.head || d.body).appendChild(s);
                                        })
                                        ();
                                    </script>
                                    <noscript>Please enable JavaScript to view the <a
                                            href="https://disqus.com/?ref_noscript">comments
                                            powered by
                                            Disqus.</a></noscript>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    @auth
                                        <form id="likeForm-{{ $campaign->id }}"
                                            action="{{ route('campaigns.like', $campaign) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link" id="likeButton-{{ $campaign->id }}">
                                                @if ($campaign->isLikedBy(auth()->user()))
                                                    <i class="fas fa-heart"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                            <span id="likeCount-{{ $campaign->id }}">{{ $campaign->likes()->count() }}</span>
                                        </form>
                                    @endauth
                                    @guest
                                        <div class="d-inline">
                                            <i class="fas fa-heart text-primary ms-3"></i>
                                            <span class="ms-3"
                                                id="likeCount-{{ $campaign->id }}">{{ $campaign->likes()->count() }}</span>
                                        </div>
                                    @endguest
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        @if ($starredCampaigns->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon me-5" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next ms-5" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Content</h5>
                <h1 class="mb-5">Most Popular Content</h1>
            </div>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill"
                            href="#tab-1">
                            <i class="bi bi-journals fa-2x text primary"></i>
                            <div class="ps-3">
                                <small class="text-body">Popular</small>
                                <h6 class="mt-n1 mb-0">Articles</h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 pb-3 custom-hover" data-bs-toggle="pill"
                            href="#tab-2">
                            <i class="bi bi-calendar-event fa-2x text-primary"></i>
                            <div class="ps-3">
                                <small class="text-body">Popular</small>
                                <h6 class="mt-n1 mb-0">Events</h6>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            @foreach ($starredArticles as $article)
                                <div class="col-lg-6">
                                    <div class="card card-green">
                                        @if ($article->image)
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/public/' . $article->image) }}" alt=""
                                                style="height: 200px; object-fit: cover;">
                                        @else
                                            <img class="img-fluid rounded w-100" src="{{ asset('img/noimg.png') }}"
                                                alt="" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body text-start">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>{{ strlen($article->title) > 35 ? substr($article->title, 0, 35) . '...' : $article->title }}</span>
                                                <span>
                                                    <a href="{{ route('articles.show', $article->slug) }}"
                                                        class="text-primary">Read More</a>
                                                </span>
                                            </h5>
                                            <small class="fst-italic">{!! Parsedown::instance()->text(
                                                strlen($article->content) > 290 ? substr($article->content, 0, 290) . '...' : $article->content,
                                            ) !!}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @foreach ($starredEvents as $event)
                                <div class="col-lg-6">
                                    <div class="card card-green">
                                        @if ($event->image)
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/public/' . $event->image) }}" alt="No Image"
                                                style="height: 200px; object-fit: cover;">
                                        @else
                                            <img class="img-fluid rounded w-100" src="{{ asset('img/noimg.png') }}"
                                                alt="No Image" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body text-start">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>{{ strlen($event->name) > 40 ? substr($event->name, 0, 40) . '...' : $event->name }}</span>
                                                <span>
                                                    <a href="#" class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#eventModal{{ $event->id }}">View</a>
                                                </span>
                                            </h5>
                                            <small class="fst-italic">{!! strlen($event->description) > 300 ? substr($event->description, 0, 300) . '...' : $event->description !!}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1"
                                    aria-labelledby="eventModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content card-green">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="eventModalLabel{{ $event->id }}">
                                                    {{ $event->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <h3>Event Detail</h3>
                                                <p>{!! $event->description !!}</p>
                                                <div class="modal-footer d-flex flex-column align-items-start">
                                                    <div
                                                        class="w-100 d-flex justify-content-between align-items-center mb-2">
                                                        <p class="mb-0"><i class="bi bi-clock text-primary me-3"></i>
                                                            {{ $event->start_time->format('F d, Y') }}</p>
                                                    </div>
                                                    <div
                                                        class="w-100 d-flex justify-content-between align-items-center mb-2">
                                                        <p class="mb-0"><i class="bi bi-calendar text-primary me-3"></i>
                                                            {{ $event->start_time->format('H:i') }} WIB -
                                                            {{ $event->end_time->format('H:i') }} WIB</p>
                                                    </div>
                                                    <div class="w-100 d-flex justify-content-between align-items-center">
                                                        <p class="mb-0"><i
                                                                class="fa fa-map-marker text-primary me-4"></i>
                                                            {{ $event->location }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <a href="{{ $event->url }}" class="btn btn-primary">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            const likeForms = document.querySelectorAll('form[id^="likeForm-"]');
            likeForms.forEach(form => {
                const campaignId = form.getAttribute('id').split('-')[1];
                const likeButton = document.getElementById(`likeButton-${campaignId}`);
                const likeCount = document.getElementById(`likeCount-${campaignId}`);
                @if (isset($campaign))
                    let isLiked = {{ $campaign->isLikedBy(auth()->user()) ? 'true' : 'false' }};
                @else
                    let isLiked = 'false';
                @endif

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let method = 'POST';
                    let url = `{{ route('campaigns.like', ['campaign' => ':campaignId']) }}`
                        .replace(':campaignId', campaignId);
                    if (isLiked) {
                        method = 'DELETE';
                        url = `{{ route('campaigns.unlike', ['campaign' => ':campaignId']) }}`
                            .replace(':campaignId', campaignId);
                    }

                    fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            likeCount.textContent = data.likes_count;
                            likeButton.innerHTML = data.is_liked ?
                                '<i class="fas fa-heart"></i>' :
                                '<i class="far fa-heart"></i>';
                            isLiked = data.is_liked;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        @endauth
        });

        const cekKeadaanBtn = document.querySelector('.cek-keadaan-btn');
        cekKeadaanBtn.addEventListener('click', function(e) {
            e.preventDefault();
            $('#cekKeadaanModal').modal('show');
        });
    </script>
@endsection
