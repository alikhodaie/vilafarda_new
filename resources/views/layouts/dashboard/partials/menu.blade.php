<div class="btn-group account-drop">
    <button type="button" class="btn btn-order-by-filt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ auth()->user()->avatar_path }}" class="avater-img rounded-circle" alt="{{ auth()->user()->full_name }}">
    </button>
    <div class="dropdown-menu pull-right animated flipInX" x-placement="top-start" style="position: absolute; transform: translate3d(15px, 1px, 0px); top: 0px; left: 0px; will-change: transform;">
        <div class="drp_menu_headr">
            <h4>{{ auth()->user()->full_name }}</h4>
        </div>
        <ul>
            <li class="d-md-none">
                <a href="{{ route('main.submit.home') }}">
                    <i class="fas fa-plus-circle ml-1"></i>
                    @lang('title.submit_home')
                </a>
            </li>
            @if(auth()->user()->isAdmin())
                <li class="">
                    <a href="{{ route('admin.index') }}">
                        <i class="fa fa-user-ninja"></i>
                        @lang('title.admin')
                    </a>
                </li>
            @endif
            <li class="">
                <a href="{{ route('dashboard.index') }}">
                    <i class="fa fa-tachometer-alt"></i>
                    @lang('title.dashboard')
                </a>
            </li>
            <li class="">
                <a href="{{ route('dashboard.profile.edit') }}">
                    <i class="fa fa-user-tie"></i>
                    @lang('title.my_profile')
                </a>
            </li>
            <li class="">
                <a href="{{ route('dashboard.favorites.index') }}">
                    <i class="fa fa-heart"></i>
                    @lang('title.favorites')
                </a>
            </li>
            <li class="">
                <a href="{{ route('dashboard.rents.index') }}">
                    <i class="fa fa-shopping-basket"></i>
                    @lang('title.rents')
                </a>
            </li>
            <li class="">
                <a href="javascript:" onclick="document.getElementById('logout').click()">
                    <i class="fa fa-sign-out-alt"></i>
                    @lang('title.logout')
                </a>
            </li>
        </ul>
    </div>
</div>
