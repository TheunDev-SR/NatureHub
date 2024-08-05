@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-dark hero-header mb-5">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Profile
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">
                        Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="content">
        <div class="container wow fadeInUp" data-wow-delay="0.1s">
            <div class="row flex-lg-nowrap justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col mb-3">
                            <div class="card card-green">
                                <div class="card-body">
                                    <div class="e-profile">
                                        <div class="row">
                                            <div class="col-12 col-sm-auto mb-3">
                                                <div class="mx-auto" style="width: 140px;">
                                                    <div class="d-flex justify-content-center align-items-center rounded"
                                                        style="height: 140px; background-color: rgb(233, 236, 239);">
                                                        <img id="preview_image"
                                                            src="{{ $user->pict ? asset('storage/public/' . $user->pict) : asset('img/profil.png') }}"
                                                            alt="Preview Image" style="max-width: 100%; max-height: 100%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                                <div class="text-center text-sm-left mb-2 mb-sm-0">
                                                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">{{ $user->name }}</h4>
                                                    <div class="mt-2">
                                                        <label for="pict" class="btn btn-primary">
                                                            <i class="fa fa-fw fa-camera"></i>
                                                            <span>Change Photo</span>
                                                            <input id="pict" type="file" name="pict"
                                                                style="display: none;" form="update-form">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="text-center text-sm-right">
                                                    <span class="badge badge-secondary">{{ $user->role }}</span>
                                                    <div class="text-muted"><small>Joined
                                                            {{ $user->created_at->format('d M Y') }}</small></div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item active nav-link bg-primary text-white">Settings</li>
                                        </ul>
                                        <div class="tab-content pt-3">
                                            <div class="tab-pane active">
                                                <form class="form" method="POST"
                                                    action="{{ route('users.update', $user->slug) }}"
                                                    enctype="multipart/form-data" id="update-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <label>Full Name</label>
                                                                        <input class="form-control" type="text"
                                                                            name="name" placeholder="John Smith"
                                                                            value="{{ $user->name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <label>Email</label>
                                                                        <input class="form-control" type="email"
                                                                            name="email" placeholder="user@example.com"
                                                                            value="{{ $user->email }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 mb-3">
                                                            <ul class="nav nav-tabs mt-4 mb-3">
                                                                <li class="nav-item active nav-link bg-primary text-white">
                                                                    Change Password</li>
                                                            </ul>

                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label>Current Password</label>
                                                                        <div class="input-group">
                                                                            <input id="current_password"
                                                                                class="form-control" type="password"
                                                                                name="current_password"
                                                                                placeholder="••••••">
                                                                            <div class="input-group-append">
                                                                                <span id="toggle_current_password"
                                                                                    class="input-group-text btn-lg">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label>New Password</label>
                                                                        <div class="input-group">
                                                                            <input id="new_password" class="form-control"
                                                                                type="password" name="new_password"
                                                                                placeholder="••••••">
                                                                            <div class="input-group-append">
                                                                                <span id="toggle_new_password"
                                                                                    class="input-group-text btn-lg">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label for="confirm_password">Confirm
                                                                            Password</label>
                                                                        <div class="input-group">
                                                                            <input id="confirm_password"
                                                                                class="form-control" type="password"
                                                                                name="confirm_password"
                                                                                placeholder="••••••">
                                                                            <div class="input-group-append">
                                                                                <span id="toggle_confirm_password"
                                                                                    class="input-group-text btn-lg">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    <button id="openConfirmationModalBtn" class="btn btn-primary"
                                                        type="button">Save Changes</button>
                                                </div>
                                            </div>
                                            </form>

                                            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                                                aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmationModalLabel">
                                                                Confirmation</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to save changes?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button id="confirmationModalCancelBtn" type="button"
                                                                class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button id="saveChangesBtn" type="submit"
                                                                class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

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
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#openConfirmationModalBtn").click(function() {
                var newPassword = $("#new_password").val();
                var confirmPassword = $("#confirm_password").val();

                if (newPassword !== confirmPassword) {
                    $("#errorModal").modal("show");
                    let errorList = $('#errorList');
                    errorList.empty();
                    errorList.append('<li>New password and confirm password do not match.</li>');
                    return;
                }
                $("#confirmationModal").modal("show");
            });

            $("#saveChangesBtn").click(function() {
                $("#update-form").submit();
            });

            $("#confirmationModalCancelBtn").click(function() {
                $("#confirmationModal").modal("hide");
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

        $("#toggle_current_password").click(function() {
            var input = $("#current_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $("#toggle_new_password").click(function() {
            var newPasswordInput = $("#new_password");
            if (newPasswordInput.attr("type") === "password") {
                newPasswordInput.attr("type", "text");
            } else {
                newPasswordInput.attr("type", "password");
            }
        });

        $("#toggle_confirm_password").click(function() {
            var confirmPasswordInput = $("#confirm_password");
            if (confirmPasswordInput.attr("type") === "password") {
                confirmPasswordInput.attr("type", "text");
            } else {
                confirmPasswordInput.attr("type", "password");
            }
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#pict').change(function() {
            previewImage(this);
        });
    </script>
@endsection
