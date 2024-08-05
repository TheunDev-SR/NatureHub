@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-dark hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Articles
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href={{ route('index') }}>Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">
                        Articles
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container wow fadeInUp" data-wow-delay="0.1s">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h2 class="text-center mb-4">{{ $title }}</h2>
                <div class="dropdown mb-3" style="width: 20px">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Sort By
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sortDropdown">
                        <a class="dropdown-item"
                            href="{{ route('articles.index', ['sort_by' => 'updated_at', 'sort_dir' => 'desc']) }}">published
                            date
                            (latest)</a>
                        <a class="dropdown-item"
                            href="{{ route('articles.index', ['sort_by' => 'updated_at', 'sort_dir' => 'asc']) }}">published
                            date
                            (earliest)</a>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <form id="searchForm" class="d-flex flex-grow-1 me-2">
                        <input type="text" id="searchInput" name="query" class="form-control me-2"
                            placeholder="Search articles...">
                        <button type="submit" class="btn btn-primary btn-oval">Search</button>
                    </form>
                    <a href="{{ route('articles.create') }}" class="btn btn-primary btn-oval ms-auto">
                        <i class="fa fa-plus me-2"></i> Add Article
                    </a>
                </div>
            </div>
        </div>

        <div id="articleList">
            @foreach ($articles as $article)
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card card-green">
                            @if ($article->image)
                                <img class="card-img-top img-fluid article-img"
                                    src="{{ asset('storage/public/' . $article->image) }}" alt="">
                            @else
                                <img class="card-img-top img-fluid article-img" src="{{ asset('img/noimg.png') }}"
                                    alt="">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="card mb-3 card-green" style="background-color: #f0f0f0;">
                            <div class="card-body">
                                <h4 class="card-title">{{ $article->title }}</h4>
                                <p class="card-text mb-2">Article by: <strong><a
                                            href="{{ route('articles.author', $article->user->name) }}">{{ $article->user->name }}</a></strong>
                                </p>
                                <p class="card-text"> {!! Str::limit(Parsedown::instance()->text($article->content), 300) !!} </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <a href="{{ route('articles.show', $article->slug) }}"
                                    class="btn btn-primary btn-oval">Read More</a>
                                <div class="d-flex float-end">
                                    <div>
                                        <i class="fas fa-heart text-primary"></i>
                                        <span class="ms-2" id="likeCount">{{ $article->likes()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="notFoundMessage" class="row" style="display: none;">
            <div class="col-lg-12">
                <div class="alert alert-warning" role="alert">
                    No articles found.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $articles->links() }}
        </div>
    </div>

    <style>
        .article-img {
            height: 100%;
        }

        .card {
            height: 100%;
        }

        .form-control {
            width: 100%;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').submit(function(event) {
                event.preventDefault();
                liveSearch();
            });

            $('#searchInput').on('input', function() {
                liveSearch();
            });

            function liveSearch() {
                var searchText = $('#searchInput').val();

                if (searchText.trim() === '') {
                    $('#articleList').empty();
                    return;
                }

                $.ajax({
                    url: '{{ route('article.search') }}',
                    type: 'GET',
                    data: {
                        query: searchText
                    },
                    dataType: 'json',
                    success: function(data) {
                        displaySearchResults(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            function displaySearchResults(results) {
                var articleList = $('#articleList');
                articleList.empty();

                if (results.length > 0) {
                    $('#notFoundMessage').hide();

                    $.each(results, function(index, article) {
                        var imageUrl = article.image ? `{{ asset('storage/public/') }}/${article.image}` :
                            `{{ asset('img/noimg.png') }}`;

                        var articleElement = `
                            <div class="row mb-4 article-item">
                                <div class="col-lg-6">
                                    <div class="card card-green">
                                        <img class="card-img-top img-fluid article-img" src="${imageUrl}" alt="" loading="lazy">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mb-3 card-green" style="background-color: #f0f0f0;">
                                        <div class="card-body ">
                                            <h4 class="card-title">${article.title}</h4>
                                            <p class="card-text mb-2">Article by: <strong><a href="{{ route('articles.author', $article->user->name) }}">{{ $article->user->name }}</a></strong></p>
                                            <p class="card-text"> {{ Str::limit(strip_tags(Parsedown::instance()->text($article->content)), 300) }} </p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                <a href="{{ route('articles.show', $article->slug) }}"
                                    class="btn btn-primary btn-oval">Read More</a>
                                <div class="d-flex float-end">
                                    <div>
                                        <i class="fas fa-heart text-primary"></i>
                                        <span class="ms-2" id="likeCount">{{ $article->likes()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        articleList.append(articleElement);
                    });
                } else {
                    $('#articleList').empty();
                    $('#notFoundMessage').show();
                }
            }
        });
    </script>
@endsection
