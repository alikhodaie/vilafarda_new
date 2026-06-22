@extends('layouts.admin.admin', ['title' => __('title.homes'), 'active' => 'homes'])

@section('content')
    <x-admin.search-card route="{{ route('admin.homes.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="name">@lang('title.name')</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="user">@lang('title.user')</label>
            <user-input
                @if(request()->filled('user'))
                old="{{ request('user') }}"
                @endif
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('text.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="status">@lang('title.status')</label>
            <select name="status" id="status" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Home::STATUSES as $status)
                    <option value="{{ $status['value'] }}" @if($status['value'] == request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="province">@lang('title.province')</label>
            <province-input
                route="{{ route('dashboard.provinces') }}"
                placeholder="@lang('text.insert_province')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
                old="{{ request('province') }}"
            ></province-input>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="city">@lang('title.city')</label>
            <city-input
                placeholder="@lang('text.insert_city')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
                old="{{ request('city') }}"
            ></city-input>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.homes') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Home::class) }}"
        buttonLink="{{ route('admin.homes.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($homes->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.user')</th>
                            <th scope="col">@lang('title.name')</th>
                            <th scope="col">@lang('title.status')</th>
                            <th scope="col">وضعیت میزبان</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($homes as $home)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $home->id }}</td>
                            <td class="text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span>{{ $home->user->full_name }}</span>
                                    <x-admin.user-contact-button :home="$home" />
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    @if($home->cover)
                                        <img width="75" src="{{ $home->cover_path }}" alt="{{ $home->title }}" loading="lazy" onerror="this.remove()">
                                    @endif
                                    <div class="ms-2">{{ $home->name }}</div>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <span class="badge rounded-pill d-block p-2 badge-soft-{{ $home->status('color') }}">{{ $home->status() }}</span>
                            </td>
                            <td class="text-nowrap" style="min-width: 160px;">
                                @if($home->isHostActive())
                                    <span class="badge rounded-pill d-block p-2 badge-soft-success">فعال</span>
                                @else
                                    <span class="badge rounded-pill d-block p-2 badge-soft-secondary">غیرفعال</span>
                                    @if($home->hostDeactivationReasonLabel())
                                        <small class="d-block mt-1 text-muted" style="font-size: 11px; line-height: 1.5;">
                                            {{ $home->hostDeactivationReasonLabel() }}
                                        </small>
                                    @endif
                                @endif
                            </td>
                            <td class="text-end">
                                @can('showDate', $home)
                                    <a href="{{ route('admin.homes.date.show', $home->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit') @lang('title.calendar')">
                                        <span class="text-500 fas fa-calendar"></span>
                                    </a>
                                @endcan
                                @can('update', $home)
                                    <a href="{{ route('admin.homes.edit', $home->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $home)
                                    <delete-modal
                                        route="{{ route('admin.homes.destroy', $home->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete home')"
                                        text="@lang('text.delete home')"
                                        button_hover_text="@lang('title.delete')"
                                        button_cancel_text="@lang('title.cancel')"
                                        button_submit_text="@lang('title.delete')"
                                    ></delete-modal>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $homes->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection

@push('modals')
    <div class="modal fade" id="adminUserContactModal" tabindex="-1" aria-labelledby="adminUserContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="adminUserContactModalLabel">اطلاعات تماس و پروفایل میزبان</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body pt-2">
                    <div id="adminUserContactContent">
                        <p class="fw-semibold mb-1" id="adminUserContactName">—</p>
                        <p class="text-muted small mb-3" id="adminUserContactHomeName">—</p>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2 d-flex align-items-start gap-2">
                                <span class="fas fa-mobile-alt text-500 mt-1"></span>
                                <span>
                                    <span class="text-muted d-block">موبایل</span>
                                    <a href="#" id="adminUserContactMobile" class="fw-semibold text-decoration-none">—</a>
                                </span>
                            </li>
                            <li class="mb-2 d-flex align-items-start gap-2">
                                <span class="fas fa-envelope text-500 mt-1"></span>
                                <span>
                                    <span class="text-muted d-block">ایمیل</span>
                                    <a href="#" id="adminUserContactEmail" class="fw-semibold text-decoration-none text-break">—</a>
                                </span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="fas fa-hashtag text-500 mt-1"></span>
                                <span>
                                    <span class="text-muted d-block">شناسه کاربر</span>
                                    <span id="adminUserContactId" class="fw-semibold">—</span>
                                </span>
                            </li>
                        </ul>

                        <hr class="my-3">

                        <div class="small">
                            <p class="fw-semibold mb-2">پاسخ به درخواست‌های رزرو این اقامتگاه</p>
                            <div id="adminUserContactOrdersEmpty" class="text-muted d-none">هنوز درخواست رزروی برای این اقامتگاه بررسی نشده است.</div>
                            <div id="adminUserContactOrdersStats" class="d-none">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">درجه عملکرد</span>
                                    <span id="adminUserContactOrdersTier" class="badge rounded-pill">—</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">تایید</span>
                                    <span id="adminUserContactApprovalPercent" class="fw-semibold text-success">—</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">رد</span>
                                    <span id="adminUserContactRejectionPercent" class="fw-semibold text-danger">—</span>
                                </div>
                                <div class="text-muted mt-2" style="font-size: 11px;">
                                    <span id="adminUserContactOrdersCounts">—</span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="small">
                            <p class="fw-semibold mb-2">امتیاز نظرات مهمان‌های این اقامتگاه</p>
                            <div id="adminUserContactReviewEmpty" class="text-muted d-none">نظری ثبت نشده است.</div>
                            <div id="adminUserContactReviewStats" class="d-none">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">میانگین امتیاز</span>
                                    <span id="adminUserContactReviewScore" class="fw-semibold">—</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted">درجه</span>
                                    <span id="adminUserContactReviewTier" class="badge rounded-pill">—</span>
                                </div>
                                <div class="text-muted mt-2" style="font-size: 11px;">
                                    <span id="adminUserContactReviewCount">—</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="adminUserContactError" class="alert alert-danger small mb-0 d-none" role="alert">
                        اطلاعات میزبان در دسترس نیست.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <a href="#" id="adminUserContactEditLink" class="btn btn-primary btn-sm d-none">مشاهده پروفایل</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('after-vue')
    <script>
        (function () {
            const MODAL_ID = 'adminUserContactModal';
            let lastTrigger = null;

            document.addEventListener('click', function (event) {
                const trigger = event.target.closest('.admin-user-contact-trigger');
                if (trigger) {
                    lastTrigger = trigger;
                }
            }, true);

            function profileFromTrigger(trigger) {
                if (!trigger) {
                    return null;
                }

                const ds = trigger.dataset;
                if (!ds.userId) {
                    return null;
                }

                const accepted = parseInt(ds.ordersAccepted || '0', 10);
                const rejected = parseInt(ds.ordersRejected || '0', 10);
                const guestCount = parseInt(ds.guestReviewsCount || '0', 10);

                return {
                    home_id: ds.homeId || '',
                    home_name: ds.homeName || '',
                    id: ds.userId,
                    name: ds.userName || '',
                    mobile: ds.userMobile || '',
                    email: ds.userEmail || '',
                    edit_url: ds.userEditUrl || '',
                    order_response: {
                        accepted: accepted,
                        rejected: rejected,
                        total: accepted + rejected,
                        approval_percent_display: ds.ordersApprovalPercent || null,
                        rejection_percent_display: ds.ordersRejectionPercent || null,
                        tier_label: ds.ordersTierLabel || null,
                        tier_color: ds.ordersTierColor || 'secondary',
                    },
                    guest_reviews: {
                        count: guestCount,
                        average_score_display: ds.guestReviewsScore || null,
                        tier_label: ds.guestTierLabel || null,
                        tier_color: ds.guestTierColor || 'secondary',
                    },
                };
            }

            function setContactLink(el, value, prefix) {
                if (value) {
                    el.textContent = value;
                    el.href = prefix + value;
                    el.classList.remove('text-muted');
                } else {
                    el.textContent = 'ثبت نشده';
                    el.removeAttribute('href');
                    el.classList.add('text-muted');
                }
            }

            function setTierBadge(el, label, color) {
                el.textContent = label || '—';
                el.className = 'badge rounded-pill badge-soft-' + (color || 'secondary');
            }

            function showProfile(data) {
                const contentEl = document.getElementById('adminUserContactContent');
                const errorEl = document.getElementById('adminUserContactError');

                document.getElementById('adminUserContactName').textContent = data.name || '—';
                document.getElementById('adminUserContactHomeName').textContent = data.home_name
                    ? 'اقامتگاه: ' + data.home_name
                    : '—';
                document.getElementById('adminUserContactId').textContent = data.id || '—';
                setContactLink(document.getElementById('adminUserContactMobile'), data.mobile, 'tel:');
                setContactLink(document.getElementById('adminUserContactEmail'), data.email, 'mailto:');

                const editLink = document.getElementById('adminUserContactEditLink');
                if (data.edit_url) {
                    editLink.href = data.edit_url;
                    editLink.classList.remove('d-none');
                } else {
                    editLink.classList.add('d-none');
                }

                const orderResponse = data.order_response || {};
                const ordersEmpty = document.getElementById('adminUserContactOrdersEmpty');
                const ordersStats = document.getElementById('adminUserContactOrdersStats');

                if (!orderResponse.total) {
                    ordersEmpty.classList.remove('d-none');
                    ordersStats.classList.add('d-none');
                } else {
                    ordersEmpty.classList.add('d-none');
                    ordersStats.classList.remove('d-none');
                    document.getElementById('adminUserContactApprovalPercent').textContent = orderResponse.approval_percent_display || '—';
                    document.getElementById('adminUserContactRejectionPercent').textContent = orderResponse.rejection_percent_display || '—';
                    document.getElementById('adminUserContactOrdersCounts').textContent =
                        (orderResponse.accepted || 0) + ' تایید · ' + (orderResponse.rejected || 0) + ' رد از ' + orderResponse.total + ' درخواست رزرو';
                    setTierBadge(
                        document.getElementById('adminUserContactOrdersTier'),
                        orderResponse.tier_label,
                        orderResponse.tier_color
                    );
                }

                const reviews = data.guest_reviews || {};
                const reviewEmpty = document.getElementById('adminUserContactReviewEmpty');
                const reviewStats = document.getElementById('adminUserContactReviewStats');

                if (!reviews.count || !reviews.average_score_display) {
                    reviewEmpty.classList.remove('d-none');
                    reviewStats.classList.add('d-none');
                } else {
                    reviewEmpty.classList.add('d-none');
                    reviewStats.classList.remove('d-none');
                    document.getElementById('adminUserContactReviewScore').textContent = reviews.average_score_display;
                    document.getElementById('adminUserContactReviewCount').textContent =
                        'بر اساس ' + reviews.count + ' نظر مهمان';
                    setTierBadge(
                        document.getElementById('adminUserContactReviewTier'),
                        reviews.tier_label,
                        reviews.tier_color
                    );
                }

                contentEl.classList.remove('d-none');
                errorEl.classList.add('d-none');
            }

            document.addEventListener('show.bs.modal', function (event) {
                if (!event.target || event.target.id !== MODAL_ID) {
                    return;
                }

                const related = event.relatedTarget;
                const trigger = (related && related.closest)
                    ? related.closest('.admin-user-contact-trigger') || related
                    : (related || lastTrigger);

                const profile = profileFromTrigger(trigger);

                const contentEl = document.getElementById('adminUserContactContent');
                const errorEl = document.getElementById('adminUserContactError');

                if (!profile) {
                    contentEl.classList.add('d-none');
                    errorEl.classList.remove('d-none');
                    return;
                }

                showProfile(profile);
            });
        })();
    </script>
@endpush
