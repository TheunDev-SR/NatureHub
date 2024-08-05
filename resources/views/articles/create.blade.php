@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-dark hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Create Article</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Create Article</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container wow fadeInUp" data-wow-delay="0.1s">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h4 class="text-center mb-3">Create</h4>
                <div class="card card-green p-3">
                    <div class="card-body">
                        <div id="error-message" class="alert alert-danger" style="display: none;">Pengiriman form gagal.
                            Silakan coba lagi.</div>
                        <form id="articleForm" action="{{ route('articles.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter the title of your article" required>
                            </div>
                            <div class="mb-3">
                                <label for="editor" class="form-label">Content</label>
                                <textarea class="form-control" id="editor" name="content" rows="12"
                                    placeholder="Enter the content of your article" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    onchange="previewImage(event)">
                            </div>
                            <div class="mb-3" id="preview-container" style="display: none;">
                                <label for="preview-image" class="form-label">Preview</label>
                                <br>
                                <img id="preview-image" src="#" alt="Preview" style="max-height: 200px;">
                            </div>
                            <button id="openConfirmationModalBtn" class="btn btn-primary mt-2" type="button">Save
                                Changes</button>
                            <button id="openDiscardModalBtn" type="button" class="btn btn-danger mt-2">Discard</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSubmitModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to save the changes?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="saveChangesBtn" type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDiscardModal" tabindex="-1" aria-labelledby="confirmDiscardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDiscardModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to discard the changes?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="discardChangesBtn" type="button" class="btn btn-danger">Discard</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-circle" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="errorList"></ul>
                </div>
                <div class="modal-footer">
                    <button id="errorModalCloseBtn" type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-image');
                output.src = reader.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function() {
            $("#openConfirmationModalBtn").click(function() {
                $("#confirmSubmitModal").modal("show");
            });

            $("#openDiscardModalBtn").click(function() {
                $("#confirmDiscardModal").modal("show");
            });

            $("#saveChangesBtn").click(function() {
                $("#articleForm").submit();
            });

            $("#discardChangesBtn").click(function() {
                discardForm();
                $("#confirmDiscardModal").modal("hide");
            });

            $("#errorModalCloseBtn, #errorModal .close").click(function() {
                $("#errorModal").modal("hide");
            });

            @if ($errors->any())
                $('#errorModal').modal('show');
                let errorList = $('#errorList');
                errorList.empty();
                @foreach ($errors->all() as $error)
                    errorList.append('<li>{{ $error }}</li>');
                @endforeach
            @endif
        });

        function discardForm() {
            document.getElementById('title').value = '';
            document.getElementById('content').value = '';
            document.getElementById('image').value = '';
            document.getElementById('preview-container').style.display = 'none';
        }
    </script>
@endsection
