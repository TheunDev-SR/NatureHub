@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-dark hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Events</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href={{ route('index') }}>Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Events</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container wow fadeInUp" data-wow-delay="0.1s">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h2 class="text-center mb-4">All Events</h2>
                <div class="dropdown mb-3" style="width: 20px">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Sort By
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sortDropdown">
                        <a class="dropdown-item"
                            href="{{ route('events.index', ['sort_by' => 'start_time', 'sort_dir' => 'asc']) }}">coming soon
                            (quick)</a>
                        <a class="dropdown-item"
                            href="{{ route('events.index', ['sort_by' => 'start_time', 'sort_dir' => 'desc']) }}">coming
                            soon
                            (long)</a>
                    </div>
                </div>
                <form id="searchForm" class="d-flex flex-grow-1 me-2">
                    <input type="text" id="searchInput" name="query" class="form-control me-2"
                        placeholder="Search events...">
                    <button type="submit" class="btn btn-primary btn-oval">Search</button>
                </form>
            </div>
        </div>

        <div id="eventList" class="row">
            @foreach ($events as $event)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 card-green">
                        @if ($event->image)
                            <img class="card-img-top" src="{{ asset('storage/public/' . $event->image) }}" alt="">
                        @else
                            <img class="card-img-top" src="{{ asset('img/noimg.png') }}" alt="">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $event->name }}</h4>
                        </div>
                        <div class="card-body text-muted d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <p class="mb-0">
                                    {{ $event->start_time->format('F d, Y') }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-map-marker text-primary me-2"></i>
                                <p class="mb-0">
                                    {{ Str::limit($event->location, 20) }}
                                </p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal"
                                data-bs-target="#eventModal{{ $event->id }}">
                                View
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="notFoundMessage" class="row" style="display: none;">
            <div class="col-lg-12">
                <div class="alert alert-warning" role="alert">
                    No events found.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    </div>

    @foreach ($events as $event)
        <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1"
            aria-labelledby="eventModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content card-green">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel{{ $event->id }}">{{ $event->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <h3>Event Detail</h3>
                        <p>{!! $event->description !!}</p>
                        <div class="modal-footer d-flex flex-column align-items-start">
                            <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                <p class="mb-0"><i class="bi bi-clock text-primary me-3"></i>
                                    {{ $event->start_time->format('F d, Y') }}</p>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                <p class="mb-0"><i class="bi bi-calendar text-primary me-3"></i>
                                    {{ $event->start_time->format('H:i') }} WIB - {{ $event->end_time->format('H:i') }}
                                    WIB</p>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="mb-0"><i class="fa fa-map-marker text-primary me-4"></i> {{ $event->location }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ $event->url }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .btn-block {
            display: block;
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
                    $('#eventList').empty();
                    $('#notFoundMessage').hide();
                    return;
                }

                $.ajax({
                    url: '{{ route('event.search') }}',
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
                var eventList = $('#eventList');
                eventList.empty();

                if (results.length > 0) {
                    $('#notFoundMessage').hide();

                    $.each(results, function(index, event) {
                        var imageUrl = event.image ? `{{ asset('storage/public/') }}/${event.image}` :
                            `{{ asset('img/noimg.png') }}`;

                        var eventElement = `
                           <div class="col-lg-6 mb-4">
                    <div class="card h-100 card-green">
                        @if ($event->image)
                            <img class="card-img-top" src="{{ asset('storage/public/' . $event->image) }}" alt="">
                        @else
                            <img class="card-img-top" src="{{ asset('img/noimg.png') }}" alt="">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $event->name }}</h4>
                        </div>
                        <div class="card-body text-muted d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <p class="mb-0">
                                    {{ $event->start_time->format('F d, Y') }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-map-marker text-primary me-2"></i>
                                <p class="mb-0">
                                    {{ $event->location }}
                                </p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal"
                                data-bs-target="#eventModal{{ $event->id }}">
                                View
                            </button>
                        </div>
                    </div>
                </div>
                        `;
                        eventList.append(eventElement);
                    });
                } else {
                    $('#eventList').empty();
                    $('#notFoundMessage').show();
                }
            }
        });
    </script>
@endsection
