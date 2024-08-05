@extends('admin.app')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Articles</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

    <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary mb-3">Download Report</a>
    <div class="row mb-3">
          <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.users') }}">
                <div class="card h-100 hover-effect">
                    <div class="card-body hover-text-white">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <p class="style="text-decoration: none; color: inherit;">
                                        {{ \App\Models\User::count() }}</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
       
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.articles') }}">
                <div class="card h-100 hover-effect">
                    <div class="card-body hover-text-white">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1 hover-text-white">Total Articles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <p style="text-decoration: none; color: inherit;">
                                        {{ \App\Models\Article::whereNotNull('approved_at')->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-newspaper fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
       
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.events') }}">
                <div class="card h-100 hover-effect">
                    <div class="card-body hover-text-white">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Events</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <p style="text-decoration: none; color: inherit;">
                                        {{ \App\Models\Event::count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

      
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.campaigns') }}">
                <div class="card h-100 hover-effect">
                    <div class="card-body hover-text-white">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Campaigns</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <p style="text-decoration: none; color: inherit;">
                                        {{ \App\Models\Campaign::count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-flag fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Recap Report</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area mx-auto" style="width: 60%;">
                        {!! $chartPie->render() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Annual Recap Report</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        {!! $chartBar->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hover-effect:hover {
            background-color: #3f51b5;
        }

        .hover-effect:hover .text-gray-800,
        .hover-effect:hover .text-info,
        .hover-effect:hover .text-warning,
        .hover-effect:hover .text-primary {
            color: white !important;
        }

        .hover-effect:hover .fa-2x {
            color: white !important;
        }

        .hover-text-white:hover {
            color: white !important;
        }
    </style>
@endsection
