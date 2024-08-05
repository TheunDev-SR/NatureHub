@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-success hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Article Detail
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">
                        Article Detail
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-3 card-green">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title d-flex align-items-center">
                            <img src="{{ $article->user->pict ? asset('storage/public/' . $article->user->pict) : asset('img/profil.png') }}"
                                alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                            <p class="card-text ms-2"><strong>{{ $article->user->name }}</strong></p>
                        </div>
                        <p class="card-text">{{ $article->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">{{ $article->title }}</h2>
                        @if ($article->image)
                            <div class="card mb-3 bg-light">
                                <img class="card-img-top custom-img img-fluid mb-3 rounded"
                                    src="{{ asset('storage/public/' . $article->image) }}" alt="">
                            </div>
                        @else
                            <img class="card-img-top custom-img mb-3" src="{{ asset('img/noimg.png') }}" alt="">
                        @endif
                        <div id="articleContent" class="card-text justify mt-4">
                            {!! Parsedown::instance()->text($article->content) !!}
                        </div>
                    </div>
                    <div class="card-body float-start">
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Back to Articles</a>
                        @auth
                            <form id="likeForm" action="{{ route('articles.like', $article) }}" method="POST"
                                class="d-inline float-end me-2">
                                @csrf
                                <button type="submit" class="btn btn-link" id="likeButton">
                                    @if ($article->isLikedBy(auth()->user()))
                                        <i class="fas fa-heart"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif
                                </button>
                                <span class="ms-2" id="likeCount">{{ $article->likes()->count() }}</span>
                            </form>
                        @endauth
                        @guest
                            <div class="d-inline float-end me-2">
                                <div class="btn">
                                    <i class="fas fa-heart text-primary"></i>
                                </div>
                                <span class="ms-2" id="likeCount-{{ $article->id }}">
                                    {{ $article->likes()->count() }}
                                </span>
                            </div>
                        @endguest
                    </div>

                    <div class="card-footer mt-2">
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
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments
                                powered by
                                Disqus.</a></noscript>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-img {
            max-width: 100%;
            height: auto;
            max-height: 300px;
        }

        .justify {
            text-align: justify;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const likeForm = document.getElementById('likeForm');

            @auth
            const likeButton = document.getElementById('likeButton');
            const likeCount = document.getElementById('likeCount');
            let isLiked = {{ $article->isLikedBy(auth()->user()) ? 'true' : 'false' }};

            likeForm.addEventListener('submit', function(e) {
                e.preventDefault();

                let method = 'POST';
                let url = '{{ route('articles.like', $article) }}';
                if (isLiked) {
                    method = 'DELETE';
                    url = '{{ route('articles.unlike', $article) }}';
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
                        likeButton.innerHTML = data.is_liked ? '<i class="fas fa-heart"></i>' :
                            '<i class="far fa-heart"></i>';
                        isLiked = data.is_liked;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        @endauth
        });
    </script>
@endsection
