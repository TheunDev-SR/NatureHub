<!DOCTYPE html>
<html lang="en">

<head>
    <title>NatureHub</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="{{ asset('img/logoico.ico') }}" rel="icon">

    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap">
    </noscript>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link href="{{ asset('home/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('home/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('home/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css" />
    @yield('css')
</head>

<body>
    <div class="container-fluid bg-white p-0">
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="{{ route('index') }}" class="navbar-brand p-0">
                    <img src="{{ asset('img/logonobg.png') }}" alt="Logo" style="width: 150px; height: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-0 ps-4 me-0 my-0">
                        <a href="{{ route('index') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About Us</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Content</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('articles.index') }}" class="dropdown-item">Articles</a>
                                <a href="{{ route('events.index') }}" class="dropdown-item">Events</a>
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                        @auth
                            <div id="notificationSection" class="navbar-nav py-0 pe-4 position-static">
                                <div class="dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-bell position-relative">
                                            @php
                                                $unreadCount = auth()
                                                    ->user()
                                                    ->receivedMessages()
                                                    ->where('read', 0)
                                                    ->count();
                                            @endphp
                                            @if ($unreadCount > 0)
                                                <span id="notificationBadge" class="badge bg-danger">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </i>
                                    </a>

                                    @if ($unreadCount > 0)
                                        <ul class="dropdown-menu" aria-labelledby="notificationDropdown">
                                            @foreach (auth()->user()->receivedMessages()->where('read', 0)->orderByDesc('created_at')->paginate(2) as $notification)
                                                @php
                                                    $article = $notification->article;
                                                @endphp
                                                <li>
                                                    @if ($article && $article->approved_at === null)
                                                        <a class="dropdown-item"
                                                            href="{{ route('articles.edit', $article->slug) }}">
                                                            {{ $notification->content }}
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item"
                                                            href="{{ route('articles.show', $article->slug) }}">
                                                            {{ $notification->content }}
                                                        </a>
                                                    @endif
                                                </li>
                                            @endforeach
                                            <li>
                                                <form action="{{ url('/notifications/mark-all-read') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-center">Mark All as
                                                        Read</button>
                                                </form>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endauth
                    </div>
                    <div id="userSection" class="navbar-nav py-0 pe-4">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4 me-1 ms-4">Login</a>
                        @else
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle me-4" data-bs-toggle="dropdown">
                                    @if (Auth::user()->pict)
                                        <img src="{{ asset('storage/public/' . Auth::user()->pict) }}"
                                            alt="{{ Auth::user()->name }}'s Profile"
                                            style="width: 32px; height: 32px; border-radius: 50%;">
                                    @else
                                        <img src="{{ asset('img/profil.png') }}" alt="{{ Auth::user()->name }}'s Profile"
                                            style="width: 32px; height: 32px; border-radius: 50%;">
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end me-auto">
                                    <a href="{{ route('profile', ['slug' => auth()->user()->slug]) }}"
                                        class="dropdown-item">Profile</a>
                                    @if (Auth::user()->hasRole('admin'))
                                        <a href="{!! route('admin.dashboard') !!}" class="dropdown-item">Dashboard</a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </nav>
        </div>

        @yield('content')

        <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4"
                            style="font-size: 48px;"> #SaveOurEarth </h4>
                    </div>
                </div>
            </div>
            <div class="container py-3">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Sahrul Romadi</a>, All Right Reserved.
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a><br><br>
                            Distributed By <a class="border-bottom" href="https://themewagon.com"
                                target="_blank">ThemeWagon</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('home/lib/wow/wow.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/easing/easing.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/waypoints/waypoints.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/counterup/counterup.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/owlcarousel/owl.carousel.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/tempusdominus/js/moment.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/tempusdominus/js/moment-timezone.min.js') }}" defer></script>
    <script src="{{ asset('home/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}" defer></script>
    <script src="{{ asset('home/js/main.js') }}" defer></script>
    <script id="dsq-count-scr" src="//naturehub.disqus.com/count.js" async></script>

    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.1/"
            }
        }
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph,
            List
        } from 'ckeditor5';

        ClassicEditor
            .create(document.querySelector('#editor'), {
                plugins: [Essentials, Bold, Italic, Font, Paragraph, List],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'numberedList', 'bulletedList'
                    ]
                }
            })
            .then( /* ... */ )
            .catch( /* ... */ );
    </script>

    <script>
        $(document).ready(function() {
            var isLoggedIn = {!! Auth::check() ? 'true' : 'false' !!};
            var isAdmin = {!! Auth::user() && Auth::user()->hasRole('admin') ? 'true' : 'false' !!};

            function updateNavbar(isLoggedIn, isAdmin) {
                @auth
                if (isLoggedIn) {
                    var profileLink = isAdmin ? '{!! route('admin.dashboard') !!}' : '#';
                    var profileImage = {!! Auth::check() && Auth::user()->pict ? json_encode(Auth::user()->pict) : 'null' !!};
                    var profileName = {!! Auth::check() && Auth::user()->name ? json_encode(Auth::user()->name) : 'null' !!};
                    var profileImageTag = profileImage ?
                        `<img src="{{ asset('storage/public/' . Auth::user()->pict) }}" alt="${profileName}'s Profile"
                    style="width: 32px; height: 32px; border-radius: 50%;">` :
                        `<img src="{{ asset('img/profil.png') }}" alt="${profileName}'s Profile"
                    style="width: 32px; height: 32px; border-radius: 50%;">`;
                    var navbarContent =
                        `
                <div class="nav-item dropdown">
                    <a href="${profileLink}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        ${profileImageTag}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end m-0">
                        <a href="{{ route('profile', ['slug' => auth()->user()->slug]) }}" class="dropdown-item">Profile</a>`;
                    if (isAdmin) {
                        navbarContent += `<a href="{!! route('admin.dashboard') !!}" class="dropdown-item">Dashboard</a>`;
                    }
                    navbarContent += `
                        <form action="{!! route('logout') !!}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>`;
                    $('#userSection').html(navbarContent);
                @endauth
            } else {
                $('#userSection').html(
                    '<a href="{!! route('login') !!}" class="btn btn-primary py-2 px-4 me-1 ms-4">Login</a>'
                );
            }
        }

        updateNavbar(isLoggedIn, isAdmin);
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateNotificationBadge() {
                $.get('/get-unread-notifications-count', function(count) {
                    $('#notificationBadge').text(count);
                });
            }
            updateNotificationBadge();
            $('#notificationDropdown').on('show.bs.dropdown', function() {
                $.post('/mark-all-notifications-as-read', function(response) {
                    updateNotificationBadge();
                });
            });
        });
    </script>

</body>

</html>
