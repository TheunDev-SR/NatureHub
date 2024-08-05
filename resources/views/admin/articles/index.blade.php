@extends('admin.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Articles</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Articles
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
                        All Articles
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortDropdown">
                            <a class="dropdown-item"
                                href="{{ route('admin.articles', ['sort_by' => 'id', 'sort_dir' => 'asc']) }}">ID
                                (asc)</a>
                            <a class="dropdown-item"
                                href="{{ route('admin.articles', ['sort_by' => 'id', 'sort_dir' => 'desc']) }}">ID
                                (desc)</a>
                            <a class="dropdown-item"
                                href="{{ route('admin.articles', ['sort_by' => 'likes', 'sort_dir' => 'desc']) }}">likes
                                (most)</a>
                            <a class="dropdown-item"
                                href="{{ route('admin.articles', ['sort_by' => 'likes', 'sort_dir' => 'asc']) }}">likes
                                (least)</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Created at</th>
                                <th>Likes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rowNumber = ($articles->currentPage() - 1) * $articles->perPage();
                            @endphp

                            @foreach ($articles as $article)
                                @php
                                    $rowNumber++;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $rowNumber }}</td>
                                    <td>{{ $article->id }}</td>
                                    <td>{{ substr($article->title, 0, 10) }}{{ strlen($article->content) > 10 ? '...' : '' }}
                                    </td>
                                    <td>{{ substr($article->user->name, 0, 7) }}{{ strlen($article->user->name) > 7 ? '...' : '' }}
                                    </td>
                                    <td>{{ $article->created_at ? $article->created_at->format('d-m-y H:i') : 'N/A' }}</td>
                                    <td>{{ $article->likes->count() }}</td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-star bg-success"
                                            data-slug="{{ $article->slug }}">
                                            <i
                                                class="fas fa-star {{ $article->is_starred ? 'text-warning' : 'text-secondary' }}"></i>
                                        </button>
                                        <a href="{{ route('admin.articles.show', $article->slug) }}"
                                            class="btn btn-sm btn-primary"><i class="fas fa-eye mr-2"></i>View</a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal{{ $article->slug }}">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="deleteModal{{ $article->slug }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Delete Article</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this article?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('articles.destroy', $article->slug) }}"
                                                    method="POST">
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
                        {{ $articles->appends(request()->input())->links() }}
                    </div>
                </div>
         
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>             
                <script>
                    $(document).ready(function() {
                        $('.btn-star').on('click', function() {
                            var button = $(this);
                            var slug = button.data('slug');

                            $.ajax({
                                url: '{{ route('star.article') }}',
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
