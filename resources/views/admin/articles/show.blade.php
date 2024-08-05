@extends('admin.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (!$article->approved_at)
                    <div class="card-footer d-flex justify-content-end">
                        <form action="{{ route('articles.approve', $article->slug) }}" method="POST" id="approveForm">
                            @csrf
                            <button type="button" class="btn btn-success mr-2 " data-toggle="modal"
                                data-target="#approveModal">Approve</button>
                        </form>

                        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog"
                            aria-labelledby="approveModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="approveModalLabel">Approve Article</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to approve this article?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" form="approveForm" class="btn btn-success">Yes,
                                            Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('articles.reject', $article->slug) }}" method="POST" id="rejectForm">
                            @csrf
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#rejectModal">Reject</button>
                        </form>

                        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
                            aria-labelledby="rejectModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="rejectReasonForm" action="{{ route('articles.reject', $article->slug) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject Article</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea id="reason" name="reason" class="form-control" placeholder="Reason for rejection" rows="4" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" form="rejectReasonForm"
                                                class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title d-flex align-items-center justify-content-center">
                            <img src="{{ $article->user->pict ? asset('storage/public/' . $article->user->pict) : asset('img/profil.png') }}"
                                alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                            <p class="card-text me-3"><strong class="text-cus">{{ $article->user->name }}</strong></p>
                        </div>
                        <p class="card-text">{{ $article->created_at->format('M d, Y') }}</p>
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
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.articles') }}" class="btn btn-primary">Back</a>
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

        .text-cus {
            margin-left: 10px;
        }
    </style>

    <script>
        $('#approveModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('form').attr('action', button.closest('form').attr('action'));
        });

        $('#rejectModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('form').attr('action', button.closest('form').attr('action'));
        });

        function submitRejectForm() {
            var reason = document.getElementById('reason')
                .value;
            if (reason.trim() === '') {
                alert('Please provide a reason for rejection.');
            } else {
                document.getElementById('rejectForm').submit();
            }
        }
    </script>
@endsection
