<div class="property_dashboard_navbar">

    <div class="dash_user_avater">
        <img src="{{ auth()->user()->avatar_path }}" class="img-fluid avater rounded-circle" alt="{{ auth()->user()->full_name }}">
        <h4>{{ auth()->user()->full_name }}</h4>
    </div>

    <div class="dash_user_menues">
        <ul class="d-none d-md-block">
            @include('layouts.dashboard.partials.sidebar-items')
        </ul>
        <ul @if(request()->routeIs('dashboard.index')) class="collapsed show d-block d-md-none" @else class="collapse" @endif id="dashboard_menu">
            @include('layouts.dashboard.partials.sidebar-items')
        </ul>

        <button class="d-block d-md-none btn btn-info w-100 @if(request()->routeIs('dashboard.index')) collapsed @endif" type="button" data-toggle="collapse" data-target="#dashboard_menu" @if(request()->routeIs('dashboard.index')) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="dashboard_menu">
            <i class="fa fa-bars"></i>
        </button>
    </div>

</div>
