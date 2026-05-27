<li @if($active === 'dashboard') class="active" @endif>
    <a href="{{ route('dashboard.index') }}">
        <i class="fa fa-tachometer-alt"></i>
        @lang('title.dashboard')
    </a>
</li>
<li @if($active === 'my-profile') class="active" @endif>
    <a href="{{ route('dashboard.profile.edit') }}">
        <i class="fa fa-user-tie"></i>
        @lang('title.my_profile')
    </a>
</li>
<li @if($active === 'favorites') class="active" @endif>
    <a href="{{ route('dashboard.favorites.index') }}">
        <i class="fa fa-heart"></i>
        @lang('title.favorites')
    </a>
</li>
<li @if($active === 'comments') class="active" @endif>
    <a href="{{ route('dashboard.comments.index') }}">
        <i class="fa fa-comments"></i>
        @lang('title.comments')
    </a>
</li>
<li @if($active === 'guest-transactions') class="active" @endif>
    <a href="{{ route('dashboard.guest-transactions.index') }}">
        <i class="fa fa-credit-card"></i>
        @lang('title.guest_transactions')
    </a>
</li>
<li @if($active === 'rents') class="active" @endif>
    <a href="{{ route('dashboard.rents.index') }}">
        <i class="fa fa-shopping-basket"></i>
        @lang('title.rents')

        @php($rents_count = auth()->user()->rents()->whereIn('status', [\App\Models\Order::AWAITING_PAYMENT, \App\Models\Order::WAITING_FOR_RENTER])->count())
        @if($rents_count)
            <span class="notti_coun style-4">{{ $rents_count }}</span>
        @endif
    </a>
</li>
<li @if($active === 'my-homes') class="active" @endif>
    <a href="{{ route('dashboard.homes.index') }}">
        <i class="fa fa-tasks"></i>
        @lang('title.my_homes')

        @php($home_count = auth()->user()->homes()->where('status', \App\Models\Home::PENDING)->where('is_draft', false)->count())
        @if($home_count)
            <span class="notti_coun style-4">{{ $home_count }}</span>
        @endif
    </a>
</li>
<li @if($active === 'rent-requests') class="active" @endif>
    <a href="{{ route('dashboard.orders.index') }}">
        <i class="fa fa-arrow-alt-circle-left"></i>
        @lang('title.rent_requests')

        @php($order_count = auth()->user()->orders()->where('status', \App\Models\Order::PENDING)->count())
        @if($order_count)
            <span class="notti_coun style-4">{{ $order_count }}</span>
        @endif
    </a>
</li>
<li @if($active === 'host-transactions') class="active" @endif>
    <a href="{{ route('dashboard.host-transactions.index') }}">
        <i class="fa fa-dollar-sign"></i>
        @lang('title.host_transactions')
    </a>
</li>
<li @if($active === 'tickets') class="active" @endif>
    <a href="{{ route('dashboard.tickets.index') }}">
        <i class="fa fa-envelope"></i>
        @lang('title.tickets')

        @php($ticket_count = auth()->user()->tickets()->where('status', \App\Models\Ticket::ADMIN_ANSWERED)->count())
        @if($ticket_count)
            <span class="notti_coun style-4">{{ $ticket_count }}</span>
        @endif
    </a>
</li>
<li>
    <a href="javascript:" onclick="document.getElementById('logout').click()">
        <i class="fa fa-sign-out-alt"></i>
        @lang('title.logout')
    </a>
</li>
{{--            <li><a href="bookmark-list.html"><i class="fa fa-bookmark"></i>املاک ذخیره شده<span class="notti_coun style-2">7</span></a></li>--}}
{{--            <li><a href="my-property.html"><i class="fa fa-tasks"></i>ملک های من</a></li>--}}
{{--            <li><a href="messages.html"><i class="fa fa-envelope"></i>پیام ها<span class="notti_coun style-3">3</span></a></li>--}}
{{--            <li><a href="choose-package.html"><i class="fa fa-gift"></i>انتخاب بسته<span class="expiration">10 روز مونده</span></a></li>--}}
{{--            <li><a href="submit-property-dashboard.html"><i class="fa fa-pen-nib"></i>ثبت املاک جدید</a></li>--}}
{{--            <li><a href="change-password.html"><i class="fa fa-unlock-alt"></i>تغییر رمز عبور</a></li>--}}
