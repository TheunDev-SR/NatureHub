@extends('admin.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Event</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.events') }}">Events</a></li>
                            <li class="breadcrumb-item active">Edit Event</li>
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
                <form id="eventForm" action="{{ route('events.update', $event->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter name"
                            name="name" value="{{ $event->name }}" />
                    </div>
                    <div class="form-group">
                        <label for="editor">Description</label>
                        <textarea class="form-control" id="editor" rows="3" name="description">{{ $event->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="datetime-start">Start Date</label>
                        <div class="input-group" style="width: 250px">
                            <span class="btn btn-primary mb-1"><i class="fas fa-calendar"></i></span>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                                value="{{ \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="datetime-end">End Date</label>
                        <div class="input-group" style="width: 250px">
                            <span class="btn btn-primary mb-1"><i class="fas fa-calendar"></i></span>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                                value="{{ \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="province">Provinsi</label>
                        <select class="form-control" id="province" name="province">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}"
                                    {{ $event->province_id == $province->id ? 'selected' : '' }}>{{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="regency">Kota</label>
                        <select class="form-control" id="regency" name="regency">
                            <option value="">Pilih Kota</option>
                            @foreach ($regencies as $regency)
                                <option value="{{ $regency->id }}"
                                    {{ $event->regency_id == $regency->id ? 'selected' : '' }}>{{ $regency->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="district">Kecamatan</label>
                        <select class="form-control" id="district" name="district">
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}"
                                    {{ $event->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <textarea class="form-control" id="detail" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="url" class="form-control" placeholder="Enter Link" id="url" name="url"
                            value="{{ $event->url }}" required>
                    </div>

                    <div class="form-group">
                        <label for="customFile">Image</label>
                        <div class="input-group">
                            <input type="file" class="custom-file-input" id="customFile" name="image"
                                onchange="previewImage(event)" />
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <div id="preview-container" class="mt-3 mb-3" style="display: block;">
                            <img id="preview"
                                src="{{ $event->image ? asset('storage/public/' . $event->image) : asset('img/noimg.png') }}"
                                alt="Image Preview" style="max-width: 200px; height: auto;">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="location" name="location" value="{{ $event->location }}">
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
                document.getElementById('preview-container').style.display = 'block';
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
                $("#eventForm").submit();
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
            document.getElementById('description').value = '';
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';
            document.getElementById('location').value = '';
            document.getElementById('preview-container').style.display = 'none';
        }

        $(document).ready(function() {
            function updateLocation() {
                var location = $('#province option:selected').text() + ' ' +
                    $('#regency option:selected').text() + ' ' +
                    $('#district option:selected').text() + ' ' +
                    $('#village option:selected').text() + ' ' +
                    $('#detail').val();

                $('#location').val(location);
            }

            $('#province').change(function() {
                var provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: '/get-regencies/' + provinceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#regency').empty();
                            $('#district').empty();
                            $('#village').empty();
                            $('#regency').append('<option value="">Select Regency</option>');
                            $.each(data, function(key, value) {
                                $('#regency').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            updateLocation();
                        }
                    });
                }
            });

            $('#regency').change(function() {
                var regencyId = $(this).val();
                if (regencyId) {
                    $.ajax({
                        url: '/get-districts/' + regencyId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#district').empty();
                            $('#village').empty();
                            $('#district').append('<option value="">Select District</option>');
                            $.each(data, function(key, value) {
                                $('#district').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            updateLocation();
                        }
                    });
                }
            });

            $('#district').change(function() {
                var districtId = $(this).val();
                if (districtId) {
                    $.ajax({
                        url: '/get-villages/' + districtId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#village').empty();
                            $('#village').append('<option value="">Select Village</option>');
                            $.each(data, function(key, value) {
                                $('#village').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            updateLocation();
                        }
                    });
                }
            });

            $('#detail').on('input', function() {
                updateLocation();
            });

            updateLocation();
        });
    </script>
@endsection
