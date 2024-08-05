<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="{{ asset('img/logo.png') }}" rel="icon">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-login" id="container-login">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <h1 class="mb-4">Create Account</h1>
                <input type="text" class="form-control mb-3" placeholder="Name" name="name"
                    value="{{ old('name') }}" required autocomplete="name">
                <input type="email" class="form-control mb-3" placeholder="Email" name="email"
                    value="{{ old('email') }}" required autocomplete="email">
                <input type="password" class="form-control mb-3" placeholder="Password" name="password" id="password"
                    required autocomplete="new-password">
                <input type="password" class="form-control mb-3" placeholder="Confirm Password"
                    name="password_confirmation" required autocomplete="new-password">
                <button type="submit" class="btn btn-warning text-white btn-block mb-3">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <h1 class="mb-4">Sign In</h1>
                <input type="email" class="form-control mb-3" placeholder="Email" name="email"
                    value="{{ old('email') }}" required autocomplete="email">
                <input type="password" class="form-control mb-3" placeholder="Password" name="password" required
                    autocomplete="current-password">
                <button type="submit" class="btn btn-warning text-white btn-block mb-3">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Login to your account to use all site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Guardian!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
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

    <div class="modal fade" id="loginErrorModal" tabindex="-1" role="dialog" aria-labelledby="loginErrorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginErrorModalLabel">Login Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Login failed. Please check your email and password.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailUsedModal" tabindex="-1" role="dialog" aria-labelledby="emailUsedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailUsedModalLabel">Email Used</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>The email you provided is already in use. Please use a different email address.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const container = document.querySelector(".container-login");
            const registerBtn = document.getElementById("register");
            const loginBtn = document.getElementById("login");

            registerBtn.addEventListener("click", () => {
                container.classList.add("active");
            });

            loginBtn.addEventListener("click", () => {
                container.classList.remove("active");
            });

            $('#registerForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var errorList = $('#errorList');
                var errorModal = $('#errorModal');
                var emailUsedModal = $('#emailUsedModal');

                errorList.empty();

                if (form.find('input[name="name"]').val().trim() === '') {
                    errorList.append('<p>Name is required</p>');
                }

                var email = form.find('input[name="email"]').val().trim();
                if (email === '') {
                    errorList.append('<p>Email is required</p>');
                } else if (!validateEmail(email)) {
                    errorList.append('<p>Invalid email format</p>');
                }

                var password = form.find('input[name="password"]').val().trim();
                var confirmPassword = form.find('input[name="password_confirmation"]').val().trim();
                if (password === '') {
                    errorList.append('<p>Password is required</p>');
                } else if (password.length < 8) {
                    errorList.append('<p>Password must be at least 8 characters long</p>');
                } else if (password !== confirmPassword) {
                    errorList.append('<p>Passwords do not match</p>');
                }

                if (errorList.children().length > 0) {
                    errorModal.modal('show');
                } else {
                    $.ajax({
                        url: '{{ route('checkEmail') }}',
                        method: 'POST',
                        data: {
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.available) {
                                form[0].submit();
                            } else {
                                emailUsedModal.modal('show');
                            }
                        }
                    });
                }
            });

            $("#loginForm").submit(function(e) {
                var form = $(this);
                var errorList = $('#errorList');

                errorList.empty();

                if (form.find('input[name="email"]').val().trim() === '') {
                    errorList.append('<p>Email is required</p>');
                }

                if (form.find('input[name="password"]').val().trim() === '') {
                    errorList.append('<p>Password is required</p>');
                }

                $.ajax({
                    url: '{{ route('authh') }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        window.location.href = '{{ route('index') }}';
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 401) {
                            $('#loginErrorModal').modal('show');
                        }
                    }
                });

                e.preventDefault();
            });

            function validateEmail(email) {
                var re = /\S+@\S+\.\S+/;
                return re.test(email);
            }
        });
    </script>
</body>

</html>
