@extends('admin.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Events</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Events
            </li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6><i class="fas fa-check"></i><b> Success!</b></h6>
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6><i class="fas fa-ban"></i><b> Stop!</b></h6>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        All Events
                    </h6>
                    <div class="dropdown">
                        <a href="{{ route('events.create') }}"><button type="button"
                                class="btn btn-primary">Create</button></a>
                        <button class="btn btn-primary dropdown-toggle ml-2" type="button" id="sortDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortDropdown">
                            <a class="dropdown-item"
                                href="{{ route('admin.events', ['sort_by' => 'start_time', 'sort_dir' => 'asc']) }}">coming
                                soon
                                (quick)</a>
                            <a class="dropdown-item"
                                href="{{ route('admin.events', ['sort_by' => 'start_time', 'sort_dir' => 'desc']) }}">coming
                                soon
                                (long)</a>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rowNumber = ($events->currentPage() - 1) * $events->perPage();
                            @endphp

                            @foreach ($events as $event)
                                @php
                                    $rowNumber++;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $rowNumber }}</td>
                                    <td>{{ $event->id }}</td>
                                    <td>{{ substr($event->name, 0, 10) }}{{ strlen($event->name) > 10 ? '...' : '' }}
                                    </td>
                                    <td>{{ substr($event->location, 0, 5) }}{{ strlen($event->location) > 5 ? '...' : '' }}
                                    </td>
                                    <td>{{ $event->start_time ? $event->start_time->format('d-m-y H:i') : 'N/A' }}</td>
                                    <td>{{ $event->end_time ? $event->end_time->format('d-m-y H:i') : 'N/A' }}</td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-star bg-success"
                                            data-slug="{{ $event->slug }}">
                                            <i
                                                class="fas fa-star {{ $event->is_starred ? 'text-warning' : 'text-secondary' }}"></i>
                                        </button>
                                        <a href="{{ route('events.edit', $event->slug) }}"
                                            class="btn btn-sm btn-primary"><i class="fas fa-eye mr-2"></i>Edit</a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal{{ $event->slug }}">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="deleteModal{{ $event->slug }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Delete Events</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this event?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('events.destroy', $event->slug) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center mt-4">
                        {{ $events->appends(request()->input())->links() }}
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('.btn-star').on('click', function() {
                            var button = $(this);
                            var slug = button.data('slug');

                            $.ajax({
                                url: '{{ route('star.event') }}',
                                method: 'POST',
                                data: {
                                    slug: slug,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        if (response.is_starred) {
                                            button.find('.fas').removeClass('text-secondary').addClass(
                                                'text-warning');
                                        } else {
                                            button.find('.fas').removeClass('text-warning').addClass(
                                                'text-secondary');
                                        }
                                    }
                                }
                            });
                        });
                    });
                </script>

                <style>
                    .btn-star .fas {
                        transition: color 0.3s;
                    }

                    .btn-star .fas.text-warning {
                        color: #ffc107 !important;
                    }

                    .btn-star .fas.text-secondary {
                        color: #6c757d !important;
                    }
                </style>
            </div>
        </div>
    </div>
@endsection
