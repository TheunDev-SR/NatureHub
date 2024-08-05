<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="{{ asset('ra/img/logo/logo.png') }}" rel="icon" />
    <title>RuangAdmin</title>
    <link href="{{ asset('ra/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ra/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ra/css/ruang-admin.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css" />
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('ra/img/logo/logo2.png') }}" />
                </div>
                <div class="sidebar-brand-text mx-3">RuangAdmin</div>
            </a>

            <hr class="sidebar-divider my-0" />
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider" />
            <div class="sidebar-heading">Features</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable"
                    aria-expanded="true" aria-controls="collapseTable">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Content</span>
                </a>
                <div id="collapseTable" class="collapse show" aria-labelledby="headingTable"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Content</h6>
                        <a class="collapse-item" href="{{ route('admin.articles') }}">Articles</a>
                        <a class="collapse-item" href="{{ route('admin.articles.pending') }}">Pending
                            Articles</a>
                        <a class="collapse-item" href="{{ route('admin.events') }}">Events</a>
                        <a class="collapse-item" href="{{ route('admin.campaigns') }}">Campaigns</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm"
                    aria-expanded="true" aria-controls="collapseForm">
                    <i class="fab fa-fw fa-wpforms"></i>
                    <span>Users</span>
                </a>
                <div id="collapseForm" class="collapse show" aria-labelledby="headingForm"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Users</h6>
                        <a class="collapse-item" href="{{ route('admin.call') }}">Messages</a>
                        <a class="collapse-item" href="{{ route('admin.users') }}"> Manage Users</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider" />
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link" href="{{ route('admin.call') }}">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-warning badge-counter">
                                    {{ $calls->where('is_read', false)->count() }}
                                </span>
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <span class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (auth()->user()->pict)
                                    <img class="img-profile rounded-circle"
                                        src="{{ asset('storage/public/' . auth()->user()->pict) }}" alt="Profile Image"
                                        style="max-width: 60px">
                                @else
                                    <img class="img-profile rounded-circle" src="{{ asset('img/profil.png') }}"
                                        alt="Default Image" style="max-width: 60px">
                                @endif

                                <span
                                    class="ml-2 d-none d-lg-inline text-white small">{{ auth()->user()->name }}</span>
                            </span>
                        </li>

                    </ul>
                </nav>

                <div class="container-fluid" id="container-wrapper">
                    @unless (request()->routeIs('admin.dashboard'))
                        <div class="card-header d-flex justify-content-end">
                            <button class="btn btn-primary" onclick="printPage()">
                                <i class="fas fa-print mr-2"></i> Print Page
                            </button>
                        </div>
                    @endunless
                    @yield('content')
                </div>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>
                            copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            - developed by
                            <b><a href="https://indrijunanda.gitlab.io/" target="_blank">indrijunanda</a></b>
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('ra/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('ra/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('ra/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('ra/js/ruang-admin.min.js') }}"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>
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
</body>

</html>
