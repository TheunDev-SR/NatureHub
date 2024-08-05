@extends('admin.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Campaign</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.campaigns') }}">Campaigns</a></li>
                            <li class="breadcrumb-item active">Edit Campaign</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Form</h6>
            </div>
            <div class="card-body">
                <form id="campaignForm" action="{{ route('campaigns.update', $campaign->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" id="title" aria-describedby="titleHelp" name="title"
                            value="{{ old('title', $campaign->title) }}" placeholder="Enter title" required></input>
                    </div>
                    <div class="form-group">
                        <label for="name">Hastag</label>
                        <input class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter hastag"
                            name="name" value="{{ old('name', $campaign->name) }}" required />
                    </div>
                    <div class="form-group">
                        <label for="editor">Content</label>
                        <textarea class="form-control" id="editor" rows="3" name="content" placeholder="Enter content" required>{{ old('content', $campaign->content) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="customFile">Image</label>
                        <div class="input-group">
                            <input type="file" class="custom-file-input" id="customFile" name="image"
                                onchange="previewImage(event)" />
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        @if ($campaign->image)
                            <div id="preview-container" class="mt-3 mb-3">
                                <img id="preview" src="{{ asset('storage/public/' . $campaign->image) }}" alt="Current Image"
                                    style="max-width: 200px; height: auto;">
                            </div>
                        @else
                            <div id="preview-container" class="mt-3 mb-3" style="display: none;">
                                <img id="preview" src="#" alt="Image Preview"
                                    style="max-width: 200px; height: auto;">
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button id="openConfirmationModalBtn" class="btn btn-primary mt-2" type="button">Submit</button>
                        <button id="openDiscardModalBtn" type="button" class="btn btn-danger mt-2">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSubmitModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to save the changes?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="cancelSubmitBtn">Cancel</button>
                    <button id="saveChangesBtn" type="button" class="btn btn-primary">Submit</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to discard the changes?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="cancelDiscardBtn">Cancel</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="errorList"></ul>
                </div>
                <div class="modal-footer">
                    <button id="errorModalCloseBtn" type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                document.getElementById('preview-container').style.display = 'block'; // Show preview container
            }
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
                $("#campaignForm").submit();
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
            document.getElementById('name').value = '';
            document.getElementById('title').value = '';
            document.getElementById('customFile').value = '';
            document.getElementById('preview-container').style.display = 'none';
        }
    </script>
@endsection
