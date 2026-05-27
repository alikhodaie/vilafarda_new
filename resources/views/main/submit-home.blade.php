@extends('layouts.main.main', ['title' => setting('submit-home:page-title')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title" style="background:#f4f4f4 url({{ settingFilePath('submit-home:banner') }});" data-overlay="5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="breadcrumbs-wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">{{ setting('submit-home:page-title') }}</li>
                        </ol>
                        <h2 class="breadcrumb-title">{{ setting('submit-home:title') }}</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <section>

        <div class="container">

            <!-- row Start -->
            <div class="row align-items-center">

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ setting('submit-home:first-title') }}</h2>
                        </div>
                        <div class="card-body" style="height: 250px; overflow: auto">
                            <p>{!! setting('submit-home:first-description') !!}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ setting('submit-home:second-title') }}</h2>
                        </div>
                        <div class="card-body" style="height: 250px; overflow: auto">
                            <p>{!! setting('submit-home:second-description') !!}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 px-5">
                    <a href="{{ route('dashboard.homes.create') }}" class="btn btn-success w-100">@lang('title.submit_home')</a>
                </div>

            </div>
            <!-- /row -->

        </div>

    </section>
@endsection
